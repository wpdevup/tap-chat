<?php
namespace Tap_Chat;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Plugin {

    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        require_once TAP_CHAT_PLUGIN_DIR . 'includes/admin/class-tap-chat-admin.php';
        new \Tap_Chat\Admin();

        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_front' ] );
        add_action( 'wp_footer', [ $this, 'render_floating_button' ] );

        add_shortcode('tapchat', [ $this, 'shortcode' ] );
    }

    public function enqueue_front() {
        wp_enqueue_style( 'tap-chat', TAP_CHAT_PLUGIN_URL . 'assets/css/tapchat.css', [], TAP_CHAT_VERSION );
        wp_enqueue_script( 'tap-chat', TAP_CHAT_PLUGIN_URL . 'assets/js/tapchat.js', [ 'jquery' ], TAP_CHAT_VERSION, true );
        wp_localize_script( 'tap-chat', 'TapChatData', [
            'whatsAppBase' => 'https://wa.me/',
        ] );
    }

    private function get_option( $key, $default = '' ) {
        $opts = get_option( 'tap_chat_settings', [] );
        return isset( $opts[ $key ] ) ? $opts[ $key ] : $default;
    }

    private function get_full_phone_number() {
        $country_code = $this->get_option( 'country_code', '49' );
        $phone = $this->get_option( 'phone', '' );
        
        $phone = preg_replace('/\D+/', '', $phone);
        $phone = ltrim($phone, '0');
        
        if ( empty( $phone ) ) {
            return '';
        }
        
        return $country_code . $phone;
    }

    private function build_whatsapp_url( $phone, $message = '' ) {
        if ( strpos( $phone, '+' ) === false && ! preg_match('/^\d{10,15}$/', $phone ) ) {
            $phone = $this->get_full_phone_number();
        } else {
            $phone = preg_replace('/\D+/', '', $phone);
        }
        
        if ( empty( $phone ) ) {
            return '';
        }
        
        $url = 'https://wa.me/' . $phone;
        if ( ! empty( $message ) ) {
            $url .= '?text=' . rawurlencode( $message );
        }
        return esc_url( $url );
    }
    
    private function is_within_working_hours() {
        $enable_working_hours = $this->get_option( 'enable_working_hours', 'no' );
        
        if ( $enable_working_hours !== 'yes' ) {
            return true;
        }
        
        $timezone = $this->get_option( 'timezone', wp_timezone_string() );
        $working_hours = $this->get_option( 'working_hours', array() );
        
        try {
            $tz = new \DateTimeZone( $timezone );
            $now = new \DateTime( 'now', $tz );
            
            $current_day = strtolower( $now->format( 'l' ) );
            $current_time = $now->format( 'H:i' );
            
            if ( ! isset( $working_hours[ $current_day ] ) ) {
                return false;
            }
            
            $day_schedule = $working_hours[ $current_day ];
            
            if ( ! isset( $day_schedule['enabled'] ) || $day_schedule['enabled'] !== 'yes' ) {
                return false;
            }
            
            $start_time = isset( $day_schedule['start'] ) ? $day_schedule['start'] : '09:00';
            $end_time = isset( $day_schedule['end'] ) ? $day_schedule['end'] : '17:00';
            
            return ( $current_time >= $start_time && $current_time <= $end_time );
            
        } catch ( \Exception $e ) {
            return true;
        }
    }
    
    private function should_show_button() {
        $enable_show_on = $this->get_option( 'enable_show_on', 'no' );
        $enable_hide_on = $this->get_option( 'enable_hide_on', 'no' );
        
        if ( $enable_show_on === 'no' && $enable_hide_on === 'no' ) {
            return true;
        }
        
        $current_id = get_queried_object_id();
        
        if ( function_exists( 'is_shop' ) && is_shop() ) {
            $current_id = wc_get_page_id( 'shop' );
        }
        
        if ( ! $current_id ) {
            if ( $enable_show_on === 'yes' ) {
                return false;
            }
            return true;
        }
        
        $show_on_pages = $this->get_option( 'show_on_pages', array() );
        $hide_on_pages = $this->get_option( 'hide_on_pages', array() );
        
        if ( $enable_show_on === 'yes' ) {
            $in_show_list = in_array( $current_id, (array) $show_on_pages );
            
            if ( $enable_hide_on === 'yes' && $in_show_list ) {
                $in_hide_list = in_array( $current_id, (array) $hide_on_pages );
                return $in_show_list && ! $in_hide_list;
            }
            
            return $in_show_list;
        }
        
        if ( $enable_hide_on === 'yes' ) {
            $in_hide_list = in_array( $current_id, (array) $hide_on_pages );
            return ! $in_hide_list;
        }
        
        return true;
    }

    public function render_floating_button() {
        $enabled = $this->get_option( 'enable_floating', 'yes' );
        if ( 'yes' !== $enabled ) { return; }

        if ( ! $this->should_show_button() ) { return; }

        $full_phone = $this->get_full_phone_number();
        if ( empty( $full_phone ) ) { return; }

        $is_working_hours = $this->is_within_working_hours();
        $enable_working_hours = $this->get_option( 'enable_working_hours', 'no' );
        
        if ( $enable_working_hours === 'yes' && ! $is_working_hours ) {
            $offline_message = $this->get_option( 'offline_message', '' );
            
            if ( empty( $offline_message ) ) {
                return;
            }
            
            $this->render_offline_button( $offline_message );
            return;
        }

        $message = $this->get_option( 'message', '' );
        $label   = $this->get_option( 'label', __( 'Chat with us', 'tap-chat' ) );
        $pos     = $this->get_option( 'position', 'right' );
        $size    = absint( $this->get_option( 'size', 56 ) );
        $mobile_size = absint( $this->get_option( 'mobile_size', 56 ) );
        $color   = sanitize_hex_color( $this->get_option( 'color', '#25D366' ) );
        $hide_mb = $this->get_option( 'hide_label_mobile', 'yes' );
        $hide_dt = $this->get_option( 'hide_label_desktop', 'no' );
        $append  = $this->get_option( 'append_page_context', 'no' );

        $href = $this->build_whatsapp_url( $full_phone, $message );

        $classes = 'tapchat-fab tapchat-pos-' . esc_attr( $pos );
        if ( 'yes' === $hide_mb ) {
            $classes .= ' tapchat-hide-label-mobile';
        }
        if ( 'yes' === $hide_dt ) {
            $classes .= ' tapchat-hide-label-desktop';
        }

        $style = sprintf( 
            '--tapchat-size:%dpx; --tapchat-mobile-size:%dpx; --tapchat-color:%s;', 
            $size, 
            $mobile_size,
            $color ? $color : '#25D366' 
        );

        echo '<a class="' . esc_attr( $classes ) . '" style="' . esc_attr( $style ) . '" href="' . esc_url( $href ) . '" target="_blank" rel="noopener" aria-label="' . esc_attr( $label ) . '"' . ( 'yes' === $append ? ' data-append-page="1"' : '' ) . '>';
        echo '<svg class="tapchat-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false" role="img"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.031-.967-.273-.1-.472-.149-.672.15-.197.297-.768.966-.94 1.164-.173.199-.347.224-.644.075-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.173.198-.298.298-.497.099-.198.05-.372-.025-.521-.075-.149-.672-1.611-.921-2.206-.242-.58-.487-.502-.672-.512l-.573-.01c-.198 0-.521.074-.793.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.71.306 1.263.489 1.694.626.712.227 1.36.195 1.872.118.571-.085 1.758-.718 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.37l-.361-.214-3.741.982.999-3.648-.236-.374a9.86 9.86 0 0 1-1.51-5.26c0-5.45 4.436-9.884 9.89-9.884 2.64 0 5.122 1.027 6.988 2.893a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.439 9.884-9.887 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .154 5.338.157 11.892c0 2.096.549 4.142 1.595 5.945L.057 24l6.3-1.654a11.89 11.89 0 0 0 5.683 1.448h.005c6.554 0 11.895-5.338 11.898-11.893a11.82 11.82 0 0 0-3.479-8.413"/></svg>';
        echo '<span class="tapchat-label">' . esc_html( $label ) . '</span>';
        echo '</a>';
        
        $this->render_welcome_bubble();
    }
    
    private function render_offline_button( $offline_message ) {
        $pos     = $this->get_option( 'position', 'right' );
        $size    = absint( $this->get_option( 'size', 56 ) );
        $mobile_size = absint( $this->get_option( 'mobile_size', 56 ) );
        $color   = '#999999';

        $classes = 'tapchat-fab tapchat-fab-offline tapchat-pos-' . esc_attr( $pos );

        $style = sprintf( 
            '--tapchat-size:%dpx; --tapchat-mobile-size:%dpx; --tapchat-color:%s; cursor: not-allowed; opacity: 0.7;', 
            $size, 
            $mobile_size,
            $color
        );

        echo '<div class="' . esc_attr( $classes ) . '" style="' . esc_attr( $style ) . '" title="' . esc_attr( $offline_message ) . '">';
        echo '<svg class="tapchat-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false" role="img"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.031-.967-.273-.1-.472-.149-.672.15-.197.297-.768.966-.94 1.164-.173.199-.347.224-.644.075-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.173.198-.298.298-.497.099-.198.05-.372-.025-.521-.075-.149-.672-1.611-.921-2.206-.242-.58-.487-.502-.672-.512l-.573-.01c-.198 0-.521.074-.793.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.71.306 1.263.489 1.694.626.712.227 1.36.195 1.872.118.571-.085 1.758-.718 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.37l-.361-.214-3.741.982.999-3.648-.236-.374a9.86 9.86 0 0 1-1.51-5.26c0-5.45 4.436-9.884 9.89-9.884 2.64 0 5.122 1.027 6.988 2.893a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.439 9.884-9.887 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .154 5.338.157 11.892c0 2.096.549 4.142 1.595 5.945L.057 24l6.3-1.654a11.89 11.89 0 0 0 5.683 1.448h.005c6.554 0 11.895-5.338 11.898-11.893a11.82 11.82 0 0 0-3.479-8.413"/></svg>';
        echo '<span class="tapchat-label">' . esc_html( $offline_message ) . '</span>';
        echo '</div>';
    }
    
    private function render_welcome_bubble() {
        $enable = $this->get_option('enable_welcome_bubble', 'no');
        
        if ($enable !== 'yes') {
            return;
        }
        
        $bubble_style = $this->get_option('bubble_style', 'modern');
        $bubble_position = $this->get_option('bubble_position', 'top');
        $message = $this->get_option('welcome_bubble_message', __('Need help? Let\'s chat! 💬', 'tap-chat'));
        $name = $this->get_option('welcome_bubble_name', __('Support Team', 'tap-chat'));
        $avatar = $this->get_option('welcome_bubble_avatar', '');
        $delay = absint($this->get_option('welcome_bubble_delay', 3));
        
        if (empty($message)) {
            return;
        }
        
        $bubble_class = 'tapchat-welcome-bubble';
        if ($bubble_style === 'simple') {
            $bubble_class .= ' style-simple';
            if ($bubble_position === 'side') {
                $bubble_class .= ' position-side';
            }
        }
        ?>
        <div class="<?php echo esc_attr($bubble_class); ?>" data-delay="<?php echo esc_attr($delay); ?>">
            <?php if ($bubble_style === 'modern') : ?>
                <div class="tapchat-bubble-avatar">
                    <?php if (!empty($avatar)) : ?>
                        <img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($name); ?>" />
                    <?php else : ?>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.031-.967-.273-.1-.472-.149-.672.15-.197.297-.768.966-.94 1.164-.173.199-.347.224-.644.075-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.173.198-.298.298-.497.099-.198.05-.372-.025-.521-.075-.149-.672-1.611-.921-2.206-.242-.58-.487-.502-.672-.512l-.573-.01c-.198 0-.521.074-.793.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.71.306 1.263.489 1.694.626.712.227 1.36.195 1.872.118.571-.085 1.758-.718 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.37l-.361-.214-3.741.982.999-3.648-.236-.374a9.86 9.86 0 0 1-1.51-5.26c0-5.45 4.436-9.884 9.89-9.884 2.64 0 5.122 1.027 6.988 2.893a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.439 9.884-9.887 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .154 5.338.157 11.892c0 2.096.549 4.142 1.595 5.945L.057 24l6.3-1.654a11.89 11.89 0 0 0 5.683 1.448h.005c6.554 0 11.895-5.338 11.898-11.893a11.82 11.82 0 0 0-3.479-8.413"/>
                        </svg>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="tapchat-bubble-content">
                <?php if ($bubble_style === 'modern') : ?>
                    <div class="tapchat-bubble-name">
                        <?php echo esc_html($name); ?>
                        <span class="tapchat-bubble-online"></span>
                    </div>
                <?php endif; ?>
                <div class="tapchat-bubble-message">
                    <?php echo esc_html($message); ?>
                </div>
            </div>
            
            <button class="tapchat-bubble-close" aria-label="<?php esc_attr_e('Close', 'tap-chat'); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
            </button>
        </div>
        <?php
    }

    public function shortcode( $atts ) {
        $atts = shortcode_atts( [
            'phone'   => '',
            'message' => $this->get_option( 'message', '' ),
            'label'   => $this->get_option( 'label', __( 'Chat with us', 'tap-chat' ) ),
        ], $atts, 'tapchat' );

        if ( empty( $atts['phone'] ) ) {
            $atts['phone'] = $this->get_full_phone_number();
        }

        if ( empty( $atts['phone'] ) ) { return ''; }

        $href = $this->build_whatsapp_url( $atts['phone'], $atts['message'] );
        return sprintf(
            '<a class="tap-chat-inline" href="%s" target="_blank" rel="noopener">%s</a>',
            esc_url( $href ),
            esc_html( $atts['label'] )
        );
    }
}