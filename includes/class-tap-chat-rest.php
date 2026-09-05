<?php
namespace Tap_Chat;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * REST collector for first-party analytics.
 *
 * The endpoint is intentionally public (permission_callback returns true) so it
 * keeps working on fully page-cached sites where a WordPress nonce would be
 * stale. It stores no personal data, gates on the plugin's own settings, and is
 * rate limited per IP using a short-lived transient (the IP is only hashed into
 * the transient key, never persisted).
 */
class Rest_Collector {

    const NAMESPACE_V1 = 'tapchat/v1';
    const ROUTE = '/collect';

    const RATE_LIMIT_PER_MINUTE = 120;

    public function __construct() {
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }

    public function register_routes() {
        register_rest_route(
            self::NAMESPACE_V1,
            self::ROUTE,
            array(
                'methods'             => 'POST',
                'callback'            => array( $this, 'handle' ),
                'permission_callback' => '__return_true',
                'args'                => array(
                    'e' => array(
                        'required' => true,
                        'type'     => 'string',
                    ),
                    'p' => array(
                        'required' => false,
                        'type'     => 'string',
                    ),
                    't' => array(
                        'required' => false,
                        'type'     => 'string',
                    ),
                    'u' => array(
                        'required' => false,
                        'type'     => 'integer',
                    ),
                ),
            )
        );
    }

    public function handle( \WP_REST_Request $request ) {
        $settings = get_option( 'tap_chat_settings', array() );

        $click_enabled   = isset( $settings['enable_click_analytics'] ) ? ( 'yes' === $settings['enable_click_analytics'] ) : true;
        $traffic_enabled = isset( $settings['enable_traffic_analytics'] ) ? ( 'yes' === $settings['enable_traffic_analytics'] ) : false;

        $event = sanitize_key( (string) $request->get_param( 'e' ) );

        // Gate on settings so the table can't be filled while a feature is off.
        if ( 'click' === $event && ! $click_enabled ) {
            return $this->ok();
        }
        if ( 'view' === $event && ! $traffic_enabled ) {
            return $this->ok();
        }
        if ( ! in_array( $event, array( 'click', 'view' ), true ) ) {
            return $this->ok();
        }

        if ( ! $this->within_rate_limit() ) {
            return new \WP_REST_Response( array( 'ok' => false ), 429 );
        }

        $page  = (string) $request->get_param( 'p' );
        $title = (string) $request->get_param( 't' );
        $device = $this->detect_device();

        if ( 'click' === $event ) {
            Analytics_Store::record( Analytics_Store::EVENT_CLICK, $device, $page, $title );
        } elseif ( 'view' === $event ) {
            Analytics_Store::record( Analytics_Store::EVENT_VIEW, $device, $page, $title );

            // A unique daily browser (cookieless localStorage flag) counts as a visitor.
            if ( (int) $request->get_param( 'u' ) === 1 ) {
                Analytics_Store::record( Analytics_Store::EVENT_VISITOR, $device, $page, $title );
            }
        }

        return $this->ok();
    }

    private function ok() {
        return new \WP_REST_Response( array( 'ok' => true ), 204 );
    }

    /**
     * Coarse device classification from the User-Agent. The UA itself is never
     * stored — only one of desktop|mobile|tablet|unknown.
     */
    private function detect_device() {
        $ua = isset( $_SERVER['HTTP_USER_AGENT'] ) ? (string) wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) : '';
        if ( '' === $ua ) {
            return 'unknown';
        }

        $ua_l = strtolower( $ua );

        $is_tablet = ( false !== strpos( $ua_l, 'ipad' ) )
            || ( false !== strpos( $ua_l, 'tablet' ) )
            || ( false !== strpos( $ua_l, 'playbook' ) )
            || ( false !== strpos( $ua_l, 'silk' ) )
            || ( false !== strpos( $ua_l, 'kindle' ) )
            || ( false !== strpos( $ua_l, 'android' ) && false === strpos( $ua_l, 'mobile' ) );

        if ( $is_tablet ) {
            return 'tablet';
        }

        $is_mobile = ( false !== strpos( $ua_l, 'mobile' ) )
            || ( false !== strpos( $ua_l, 'iphone' ) )
            || ( false !== strpos( $ua_l, 'ipod' ) )
            || ( false !== strpos( $ua_l, 'windows phone' ) )
            || ( false !== strpos( $ua_l, 'blackberry' ) )
            || ( false !== strpos( $ua_l, 'opera mini' ) )
            || ( false !== strpos( $ua_l, 'iemobile' ) );

        return $is_mobile ? 'mobile' : 'desktop';
    }

    /**
     * Per-IP, per-minute rate limit backed by a transient. The IP is hashed into
     * the key only; it is not stored anywhere.
     */
    private function within_rate_limit() {
        $ip = isset( $_SERVER['REMOTE_ADDR'] ) ? (string) wp_unslash( $_SERVER['REMOTE_ADDR'] ) : '';
        if ( '' === $ip ) {
            return true;
        }

        $bucket = (int) floor( time() / MINUTE_IN_SECONDS );
        $key = 'tapchat_rl_' . md5( $ip . '|' . $bucket );

        $count = (int) get_transient( $key );
        if ( $count >= self::RATE_LIMIT_PER_MINUTE ) {
            return false;
        }

        set_transient( $key, $count + 1, MINUTE_IN_SECONDS );
        return true;
    }
}
