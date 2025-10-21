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
        $active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'general';
        ?>
        <div class="wrap tap-chat-admin-wrap">
            <h1><?php esc_html_e('Tap Chat Settings','tap-chat'); ?></h1>
            
            <nav class="nav-tab-wrapper wp-clearfix">
                <a href="?page=tap-chat&tab=general" 
                   class="nav-tab <?php echo $active_tab === 'general' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e( 'General', 'tap-chat' ); ?>
                </a>
                <a href="?page=tap-chat&tab=bubble" 
                   class="nav-tab <?php echo $active_tab === 'bubble' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e( 'Welcome Bubble', 'tap-chat' ); ?>
                </a>
                <a href="?page=tap-chat&tab=hours" 
                   class="nav-tab <?php echo $active_tab === 'hours' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e( 'Working Hours', 'tap-chat' ); ?>
                </a>
                <a href="?page=tap-chat&tab=visibility" 
                   class="nav-tab <?php echo $active_tab === 'visibility' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e( 'Visibility', 'tap-chat' ); ?>
                </a>
                <a href="?page=tap-chat&tab=advanced" 
                   class="nav-tab <?php echo $active_tab === 'advanced' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e( 'Advanced', 'tap-chat' ); ?>
                </a>
            </nav>
            
            <form method="post" action="options.php" class="tap-chat-settings-form">
                <?php
                    settings_fields( 'tap_chat_settings_group' );
                    
                    // Hidden field to track which tab is being submitted
                    $tab_name = 'general';
                    switch( $active_tab ) {
                        case 'bubble':
                            $tab_name = 'bubble';
                            do_settings_sections( 'tap-chat-bubble' );
                            break;
                        case 'hours':
                            $tab_name = 'hours';
                            do_settings_sections( 'tap-chat-hours' );
                            break;
                        case 'visibility':
                            $tab_name = 'visibility';
                            do_settings_sections( 'tap-chat-visibility' );
                            break;
                        case 'advanced':
                            $tab_name = 'advanced';
                            do_settings_sections( 'tap-chat-advanced' );
                            break;
                        default:
                            do_settings_sections( 'tap-chat-general' );
                            break;
                    }
                    
                    echo '<input type="hidden" name="tap_chat_settings[_active_tab]" value="' . esc_attr( $tab_name ) . '" />';
                    
                    submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}