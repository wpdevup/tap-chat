<?php
namespace Tap_Chat;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * First-party analytics store.
 *
 * Records click / view / visitor events into a single daily-aggregated table
 * (one row per date + event type + device + page) using an upsert, so the table
 * stays small regardless of traffic. All reads are simple grouped SUM queries.
 *
 * No cookies, no personal data: IP addresses and user agents are never stored,
 * only a coarse device class and the page path. Data never leaves the site.
 */
class Analytics_Store {

    const DB_VERSION = '1';
    const DB_VERSION_OPTION = 'tap_chat_db_version';

    const EVENT_CLICK   = 'click';
    const EVENT_VIEW    = 'view';
    const EVENT_VISITOR = 'visitor';

    /**
     * Fully-qualified table name.
     */
    public static function table() {
        global $wpdb;
        return $wpdb->prefix . 'tapchat_stats';
    }

    /**
     * Create or upgrade the stats table. Safe to call repeatedly.
     */
    public static function create_table() {
        global $wpdb;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $table   = self::table();
        $charset = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$table} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            stat_date DATE NOT NULL,
            event_type VARCHAR(12) NOT NULL,
            device VARCHAR(8) NOT NULL DEFAULT 'unknown',
            page_hash CHAR(32) NOT NULL,
            page_key VARCHAR(255) NOT NULL DEFAULT '',
            page_title VARCHAR(255) NOT NULL DEFAULT '',
            hits BIGINT UNSIGNED NOT NULL DEFAULT 0,
            PRIMARY KEY  (id),
            UNIQUE KEY bucket (stat_date, event_type, device, page_hash),
            KEY date_type (stat_date, event_type)
        ) {$charset};";

        dbDelta( $sql );

        update_option( self::DB_VERSION_OPTION, self::DB_VERSION );
    }

    /**
     * Create the table on demand if the schema version changed (covers updates
     * applied without re-running the activation hook, e.g. WP.org auto-update).
     */
    public static function maybe_upgrade() {
        if ( get_option( self::DB_VERSION_OPTION ) !== self::DB_VERSION ) {
            self::create_table();
        }
    }

    /**
     * Record a single event, aggregated into its daily bucket.
     *
     * @param string $event_type One of the EVENT_* constants.
     * @param string $device     desktop|mobile|tablet|unknown.
     * @param string $page_key   Normalized page path (e.g. /product/serum).
     * @param string $page_title Page title for display.
     */
    public static function record( $event_type, $device, $page_key, $page_title = '' ) {
        global $wpdb;

        $allowed_events = array( self::EVENT_CLICK, self::EVENT_VIEW, self::EVENT_VISITOR );
        if ( ! in_array( $event_type, $allowed_events, true ) ) {
            return false;
        }

        $allowed_devices = array( 'desktop', 'mobile', 'tablet', 'unknown' );
        if ( ! in_array( $device, $allowed_devices, true ) ) {
            $device = 'unknown';
        }

        $page_key = self::normalize_page_key( $page_key );
        $page_title = self::normalize_title( $page_title );
        $page_hash = md5( $page_key );

        $stat_date = current_time( 'Y-m-d' );
        $table = self::table();

        // Upsert: increment the daily bucket, keep the latest human-readable label.
        $sql = $wpdb->prepare(
            "INSERT INTO {$table} (stat_date, event_type, device, page_hash, page_key, page_title, hits)
             VALUES (%s, %s, %s, %s, %s, %s, 1)
             ON DUPLICATE KEY UPDATE hits = hits + 1, page_key = VALUES(page_key), page_title = VALUES(page_title)",
            $stat_date,
            $event_type,
            $device,
            $page_hash,
            $page_key,
            $page_title
        );

        return false !== $wpdb->query( $sql );
    }

    /**
     * Normalize a page path: strip host/query/fragment, force a leading slash,
     * cap length. Falls back to '/'.
     */
    public static function normalize_page_key( $raw ) {
        $raw = (string) $raw;

        // If a full URL sneaks in, keep only the path.
        $path = wp_parse_url( $raw, PHP_URL_PATH );
        if ( ! is_string( $path ) || '' === $path ) {
            $path = $raw;
        }

        $path = strtok( $path, '?' );
        $path = strtok( $path, '#' );
        $path = sanitize_text_field( (string) $path );
        $path = wp_strip_all_tags( $path );

        if ( '' === $path ) {
            return '/';
        }
        if ( '/' !== $path[0] ) {
            $path = '/' . $path;
        }

        if ( strlen( $path ) > 255 ) {
            $path = substr( $path, 0, 255 );
        }

        return $path;
    }

    private static function normalize_title( $title ) {
        $title = sanitize_text_field( (string) $title );
        $title = wp_strip_all_tags( $title );
        if ( strlen( $title ) > 255 ) {
            $title = substr( $title, 0, 255 );
        }
        return $title;
    }

    /**
     * Total hits for an event type within an optional inclusive date range.
     */
    public static function get_total( $event_type, $from = null, $to = null ) {
        global $wpdb;
        $table = self::table();

        if ( $from && $to ) {
            $sql = $wpdb->prepare(
                "SELECT COALESCE(SUM(hits),0) FROM {$table} WHERE event_type = %s AND stat_date BETWEEN %s AND %s",
                $event_type, $from, $to
            );
        } else {
            $sql = $wpdb->prepare(
                "SELECT COALESCE(SUM(hits),0) FROM {$table} WHERE event_type = %s",
                $event_type
            );
        }

        return (int) $wpdb->get_var( $sql );
    }

    /**
     * Hits grouped by device for an event type within a date range.
     *
     * @return array device => hits
     */
    public static function get_by_device( $event_type, $from, $to ) {
        global $wpdb;
        $table = self::table();

        $rows = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT device, SUM(hits) AS hits FROM {$table}
                 WHERE event_type = %s AND stat_date BETWEEN %s AND %s
                 GROUP BY device ORDER BY hits DESC",
                $event_type, $from, $to
            ),
            ARRAY_A
        );

        $out = array();
        foreach ( (array) $rows as $row ) {
            $out[ $row['device'] ] = (int) $row['hits'];
        }
        return $out;
    }

    /**
     * Top pages by hits for an event type within a date range.
     *
     * @return array of [ page_key, page_title, hits ]
     */
    public static function get_top_pages( $event_type, $from, $to, $limit = 10 ) {
        global $wpdb;
        $table = self::table();
        $limit = max( 1, min( 100, (int) $limit ) );

        $rows = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT page_key, MAX(page_title) AS page_title, SUM(hits) AS hits FROM {$table}
                 WHERE event_type = %s AND stat_date BETWEEN %s AND %s
                 GROUP BY page_hash, page_key
                 ORDER BY hits DESC
                 LIMIT %d",
                $event_type, $from, $to, $limit
            ),
            ARRAY_A
        );

        $out = array();
        foreach ( (array) $rows as $row ) {
            $out[] = array(
                'page_key'   => $row['page_key'],
                'page_title' => $row['page_title'],
                'hits'       => (int) $row['hits'],
            );
        }
        return $out;
    }

    /**
     * Daily totals for an event type across a date range, zero-filled.
     *
     * @return array Y-m-d => hits, ordered ascending.
     */
    public static function get_daily_series( $event_type, $from, $to ) {
        global $wpdb;
        $table = self::table();

        $rows = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT stat_date, SUM(hits) AS hits FROM {$table}
                 WHERE event_type = %s AND stat_date BETWEEN %s AND %s
                 GROUP BY stat_date",
                $event_type, $from, $to
            ),
            ARRAY_A
        );

        $found = array();
        foreach ( (array) $rows as $row ) {
            $found[ $row['stat_date'] ] = (int) $row['hits'];
        }

        $series = array();
        try {
            $start = new \DateTime( $from );
            $end   = new \DateTime( $to );
            $end->modify( '+1 day' );
            $period = new \DatePeriod( $start, new \DateInterval( 'P1D' ), $end );
            foreach ( $period as $day ) {
                $key = $day->format( 'Y-m-d' );
                $series[ $key ] = isset( $found[ $key ] ) ? $found[ $key ] : 0;
            }
        } catch ( \Exception $e ) {
            return $found;
        }

        return $series;
    }

    /**
     * Delete rows older than the given number of days.
     */
    public static function prune( $days ) {
        global $wpdb;
        $days = max( 1, (int) $days );
        $table = self::table();

        $cutoff = gmdate( 'Y-m-d', time() - ( $days * DAY_IN_SECONDS ) );

        return $wpdb->query(
            $wpdb->prepare( "DELETE FROM {$table} WHERE stat_date < %s", $cutoff )
        );
    }

    /**
     * Whether any data has been recorded yet.
     */
    public static function has_data() {
        global $wpdb;
        $table = self::table();
        return (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table}" ) > 0;
    }

    /**
     * Delete all recorded analytics rows (keeps the table and settings).
     */
    public static function reset() {
        global $wpdb;
        $table = self::table();
        return $wpdb->query( "TRUNCATE TABLE {$table}" );
    }
}
