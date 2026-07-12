<?php
namespace Tap_Chat;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Review request notice.
 *
 * Shows a friendly, dismissible admin notice asking the site owner to leave a
 * WordPress.org review. Each install is randomly bucketed into one of three
 * copy variants (A/B assignment). Note: without a remote endpoint the plugin
 * cannot measure which variant converts best — measurement would require
 * phoning home, which this plugin deliberately does not do.
 */
class Review_Notice {

    const REVIEW_URL  = 'https://wordpress.org/support/plugin/tap-chat/reviews/?filter=5#new-post';
    const DELAY_DAYS  = 1;   // wait this long after install before asking (set to 0 to test now)
    const SNOOZE_DAYS = 7;   // "Maybe later" pushes the notice out this far

    private $variants;

    public function __construct() {
        $this->variants = array(
            array(
                'title' => __( 'Enjoying Tap Chat?', 'tap-chat' ),
                'body'  => __( 'You set up your chat button in just a few minutes. A quick 5-star review helps other small sites find the plugin — and it genuinely makes our day. 🙏', 'tap-chat' ),
                'cta'   => __( 'Sure, leave a review', 'tap-chat' ),
            ),
            array(
                'title' => __( 'Got 30 seconds? 💬', 'tap-chat' ),
                'body'  => __( 'Tap Chat is free and built by a tiny team. If it saved you time, a short 5-star review on WordPress.org is what keeps us building.', 'tap-chat' ),
                'cta'   => __( 'Happy to help', 'tap-chat' ),
            ),
            array(
                'title' => __( 'A little help goes a long way ❤️', 'tap-chat' ),
                'body'  => __( 'Reviews are the number one way people decide to trust a plugin. Mind sharing what you think of Tap Chat? We read every single one.', 'tap-chat' ),
                'cta'   => __( 'Leave my review', 'tap-chat' ),
            ),
        );

        add_action( 'admin_init', array( $this, 'handle_actions' ) );
        add_action( 'admin_notices', array( $this, 'maybe_render' ) );
    }

    private function get_variant_index() {
        $idx = get_option( 'tap_chat_review_variant', null );
        if ( null === $idx ) {
            $idx = wp_rand( 0, count( $this->variants ) - 1 );
            update_option( 'tap_chat_review_variant', $idx );
        }
        $idx = (int) $idx;
        return isset( $this->variants[ $idx ] ) ? $idx : 0;
    }

    private function should_show() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return false;
        }

        // Keep it out of the way: only on Dashboard, Plugins, and our own page.
        $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
        $allowed = array( 'dashboard', 'plugins', 'settings_page_tap-chat' );
        if ( $screen && ! in_array( $screen->id, $allowed, true ) ) {
            return false;
        }

        if ( 'yes' === get_option( 'tap_chat_review_done', 'no' ) ) {
            return false;
        }

        $installed = (int) get_option( 'tap_chat_installed_at', 0 );
        if ( ! $installed ) {
            // Older installs that predate the timestamp: start the clock now.
            update_option( 'tap_chat_installed_at', time() );
            return false;
        }

        /**
         * Filter the number of days after install before the review notice appears.
         * For testing you can force it to show immediately:
         *   add_filter( 'tap_chat_review_delay_days', '__return_zero' );
         */
        $delay_days = (int) apply_filters( 'tap_chat_review_delay_days', self::DELAY_DAYS );
        if ( time() < $installed + ( $delay_days * DAY_IN_SECONDS ) ) {
            return false;
        }

        $snooze = (int) get_option( 'tap_chat_review_snooze', 0 );
        if ( $snooze && time() < $snooze ) {
            return false;
        }

        return true;
    }

    public function maybe_render() {
        if ( ! $this->should_show() ) {
            return;
        }

        $v     = $this->variants[ $this->get_variant_index() ];
        $later = wp_nonce_url( add_query_arg( 'tap_chat_review', 'later' ), 'tap_chat_review' );
        $done  = wp_nonce_url( add_query_arg( 'tap_chat_review', 'done' ), 'tap_chat_review' );
        ?>
        <div class="notice notice-info tap-chat-review-notice" style="border-left-color:#25D366;">
            <p style="font-size:14px;margin:.7em 0 .2em;"><strong><?php echo esc_html( $v['title'] ); ?></strong></p>
            <p style="margin:.2em 0 .9em;max-width:760px;"><?php echo esc_html( $v['body'] ); ?></p>
            <p style="margin:.2em 0 1em;">
                <a href="<?php echo esc_url( self::REVIEW_URL ); ?>" class="button button-primary" target="_blank" rel="noopener">
                    <?php echo esc_html( $v['cta'] ); ?> &#9733;&#9733;&#9733;&#9733;&#9733;
                </a>
                <a href="<?php echo esc_url( $done ); ?>" class="button-link" style="margin-left:12px;">
                    <?php esc_html_e( 'I already did', 'tap-chat' ); ?>
                </a>
                <span style="margin:0 6px;color:#ccc;">&middot;</span>
                <a href="<?php echo esc_url( $later ); ?>" class="button-link">
                    <?php esc_html_e( 'Maybe later', 'tap-chat' ); ?>
                </a>
            </p>
        </div>
        <?php
    }

    public function handle_actions() {
        if ( ! isset( $_GET['tap_chat_review'] ) ) {
            return;
        }
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        check_admin_referer( 'tap_chat_review' );

        $action = sanitize_key( wp_unslash( $_GET['tap_chat_review'] ) );

        if ( 'done' === $action ) {
            update_option( 'tap_chat_review_done', 'yes' );
        } elseif ( 'later' === $action ) {
            update_option( 'tap_chat_review_snooze', time() + ( self::SNOOZE_DAYS * DAY_IN_SECONDS ) );
        }

        wp_safe_redirect( remove_query_arg( array( 'tap_chat_review', '_wpnonce' ) ) );
        exit;
    }
}
