<?php
namespace Tap_Chat;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Admin {

    private $settings;
    private $fields;

    public function __construct() {
        require_once TAP_CHAT_PLUGIN_DIR . 'includes/admin/class-tap-chat-settings.php';
        require_once TAP_CHAT_PLUGIN_DIR . 'includes/admin/class-tap-chat-fields.php';
        
        $this->settings = new Admin_Settings();
        $this->fields = new Admin_Fields();
        
        add_action( 'admin_menu', array( $this, 'menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
    }

    public function menu() {
        add_options_page(
            __( 'Tap Chat', 'tap-chat' ),
            __( 'Tap Chat', 'tap-chat' ),
            'manage_options',
            'tap-chat',
            array( $this, 'settings_page' )
        );
    }

    public function admin_scripts( $hook ) {
        if ( 'settings_page_tap-chat' !== $hook ) {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        
        wp_enqueue_style( 
            'tap-chat-admin', 
            TAP_CHAT_PLUGIN_URL . 'assets/css/admin.css', 
            array(), 
            TAP_CHAT_VERSION 
        );
        
        wp_enqueue_script( 
            'tap-chat-admin', 
            TAP_CHAT_PLUGIN_URL . 'assets/js/admin.js', 
            array('jquery', 'wp-color-picker'), 
            TAP_CHAT_VERSION, 
            true 
        );
    }

    public function settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'tap-chat' ) );
        }
        
        $tabs = array(
            'general' => __( 'General', 'tap-chat' ),
            'bubble' => __( 'Welcome Bubble', 'tap-chat' ),
            'hours' => __( 'Working Hours', 'tap-chat' ),
            'visibility' => __( 'Visibility', 'tap-chat' ),
            'advanced' => __( 'Advanced', 'tap-chat' ),
        );
        ?>
        <div class="wrap tap-chat-admin-wrap">
            <h1><?php esc_html_e('Tap Chat Settings','tap-chat'); ?></h1>
            
            <nav class="nav-tab-wrapper wp-clearfix tap-chat-tabs">
                <?php foreach ( $tabs as $tab_id => $tab_name ) : ?>
                    <a href="#<?php echo esc_attr( $tab_id ); ?>" 
                       class="nav-tab <?php echo $tab_id === 'general' ? 'nav-tab-active' : ''; ?>"
                       data-tab="<?php echo esc_attr( $tab_id ); ?>">
                        <?php echo esc_html( $tab_name ); ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            
            <form method="post" action="options.php" class="tap-chat-settings-form">
                <?php settings_fields( 'tap_chat_settings_group' ); ?>
                
                <div class="tap-chat-tab-content" id="tab-general">
                    <?php do_settings_sections( 'tap-chat-general' ); ?>
                </div>
                
                <div class="tap-chat-tab-content" id="tab-bubble" style="display: none;">
                    <?php do_settings_sections( 'tap-chat-bubble' ); ?>
                </div>
                
                <div class="tap-chat-tab-content" id="tab-hours" style="display: none;">
                    <?php do_settings_sections( 'tap-chat-hours' ); ?>
                </div>
                
                <div class="tap-chat-tab-content" id="tab-visibility" style="display: none;">
                    <?php do_settings_sections( 'tap-chat-visibility' ); ?>
                </div>
                
                <div class="tap-chat-tab-content" id="tab-advanced" style="display: none;">
                    <?php do_settings_sections( 'tap-chat-advanced' ); ?>
                </div>
                
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}