<?php
namespace Tap_Chat;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Admin {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'menu' ] );
        add_action( 'admin_init', [ $this, 'settings' ] );
    }

    public function menu() {
        add_options_page(
            __( 'Tap Chat', 'tap-chat' ),
            __( 'Tap Chat', 'tap-chat' ),
            'manage_options',
            'tap-chat',
            [ $this, 'settings_page' ]
        );
    }

    public function settings() {
        register_setting( 'tap_chat_settings_group', 'tap_chat_settings', [
            'type' => 'array',
            'sanitize_callback' => [ $this, 'sanitize' ],
            'default' => [
                'enable_floating' => 'yes',
                'phone' => '',
                'message' => '',
                'label' => __( 'Chat with us', 'tap-chat' ),
                'position' => 'right',
                'size' => 56,
                'color' => '#25D366',
                'hide_label_mobile' => 'yes',
                'append_page_context' => 'no',
            ]
        ] );

        add_settings_section( 'tapchat_main', __( 'General Settings', 'tap-chat' ), '__return_false', 'tap-chat' );

        $fields = [
            'enable_floating' => __( 'Enable Floating Button', 'tap-chat' ),
            'phone'   => __( 'WhatsApp Phone (international format, e.g. 49123456789)', 'tap-chat' ),
            'message' => __( 'Default Message', 'tap-chat' ),
            'label'   => __( 'Button Label', 'tap-chat' ),
            'position'=> __( 'Floating Position', 'tap-chat' ),
            'size'    => __( 'Button Size (px)', 'tap-chat' ),
            'color'   => __( 'Button Color (hex)', 'tap-chat' ),
            'hide_label_mobile' => __( 'Hide Label on Mobile', 'tap-chat' ),
            'append_page_context' => __( 'Append Page Title & URL to Message', 'tap-chat' ),
        ];

        foreach ( $fields as $key => $label ) {
            add_settings_field( $key, $label, [ $this, 'field_' . $key ], 'tap-chat', 'tapchat_main' );
        }
    }

    public function sanitize( $input ) {
        $out = [];
        $out['enable_floating'] = ( isset( $input['enable_floating'] ) && $input['enable_floating'] === 'yes' ) ? 'yes' : 'no';
        $out['phone']   = isset( $input['phone'] ) ? sanitize_text_field( $input['phone'] ) : '';
        $out['message'] = isset( $input['message'] ) ? sanitize_textarea_field( $input['message'] ) : '';
        $out['label']   = isset( $input['label'] ) ? sanitize_text_field( $input['label'] ) : '';
        $out['position']= ( isset( $input['position'] ) && in_array( $input['position'], [ 'left','right' ], true ) ) ? $input['position'] : 'right';
        $out['size']    = isset( $input['size'] ) ? absint( $input['size'] ) : 56;
        $out['color']   = isset( $input['color'] ) ? sanitize_hex_color( $input['color'] ) : '#25D366';
        $out['hide_label_mobile'] = ( isset( $input['hide_label_mobile'] ) && $input['hide_label_mobile'] === 'yes' ) ? 'yes' : 'no';
        $out['append_page_context'] = ( isset( $input['append_page_context'] ) && $input['append_page_context'] === 'yes' ) ? 'yes' : 'no';
        return $out;
    }

    private function get( $key, $default = '' ) {
        $opts = get_option( 'tap_chat_settings', [] );
        return isset( $opts[ $key ] ) ? $opts[ $key ] : $default;
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Tap Chat','tap-chat'); ?></h1>
            <form method="post" action="options.php">
                <?php
                    settings_fields( 'tap_chat_settings_group' );
                    do_settings_sections( 'tap-chat' );
                    submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function field_enable_floating() {
        $val = $this->get( 'enable_floating', 'yes' );
        ?>
        <input type="hidden" name="tap_chat_settings[enable_floating]" value="no" />
        <label><input type="checkbox" name="tap_chat_settings[enable_floating]" value="yes" <?php checked( $val, 'yes' ); ?> /> <?php esc_html_e( 'Show floating button site-wide', 'tap-chat' ); ?></label>
        <?php
    }

    public function field_phone() {
        printf(
            '<input type="text" class="regular-text" name="tap_chat_settings[phone]" value="%s" placeholder="%s" />',
            esc_attr( $this->get( 'phone', '' ) ),
            esc_attr__( 'e.g. 49123456789', 'tap-chat' )
        );
    }

    public function field_message() {
        printf(
            '<textarea name="tap_chat_settings[message]" rows="3" class="large-text" placeholder="%s">%s</textarea>',
            esc_attr__( 'Hello! I have a question…', 'tap-chat' ),
            esc_textarea( $this->get( 'message', '' ) )
        );
    }

    public function field_label() {
        printf(
            '<input type="text" class="regular-text" name="tap_chat_settings[label]" value="%s" placeholder="%s" />',
            esc_attr( $this->get( 'label', __( 'Chat with us', 'tap-chat' ) ) ),
            esc_attr__( 'Chat with us', 'tap-chat' )
        );
    }

    public function field_position() {
        $val = $this->get( 'position', 'right' );
        ?>
        <select name="tap_chat_settings[position]">
            <option value="right" <?php selected( $val, 'right' ); ?>><?php esc_html_e( 'Bottom Right', 'tap-chat' ); ?></option>
            <option value="left" <?php selected( $val, 'left' ); ?>><?php esc_html_e( 'Bottom Left', 'tap-chat' ); ?></option>
        </select>
        <?php
    }

    public function field_size() {
        printf(
            '<input type="number" min="40" max="96" name="tap_chat_settings[size]" value="%d" />',
            absint( $this->get( 'size', 56 ) )
        );
    }

    public function field_color() {
        printf(
            '<input type="text" class="regular-text" name="tap_chat_settings[color]" value="%s" placeholder="#25D366" />',
            esc_attr( $this->get( 'color', '#25D366' ) )
        );
    }

    public function field_hide_label_mobile() {
        $val = $this->get( 'hide_label_mobile', 'yes' );
        ?>
        <input type="hidden" name="tap_chat_settings[hide_label_mobile]" value="no" />
        <label><input type="checkbox" name="tap_chat_settings[hide_label_mobile]" value="yes" <?php checked( $val, 'yes' ); ?> /> <?php esc_html_e( 'Hide label on screens < 480px', 'tap-chat' ); ?></label>
        <?php
    }

    public function field_append_page_context() {
        $val = $this->get( 'append_page_context', 'no' );
        ?>
        <input type="hidden" name="tap_chat_settings[append_page_context]" value="no" />
        <label><input type="checkbox" name="tap_chat_settings[append_page_context]" value="yes" <?php checked( $val, 'yes' ); ?> /> <?php esc_html_e( 'Append current page title & URL to message', 'tap-chat' ); ?></label>
        <?php
    }
}
