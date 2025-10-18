<?php
namespace Tap_Chat;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Admin {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'menu' ) );
        add_action( 'admin_init', array( $this, 'settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
    }

    public function admin_scripts( $hook ) {
        if ( 'settings_page_tap-chat' !== $hook ) {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        
        wp_add_inline_style( 'wp-color-picker', '
            .tap-chat-phone-wrapper {
                display: flex;
                gap: 10px;
                align-items: flex-start;
                flex-wrap: wrap;
            }
            .tap-chat-country-wrapper {
                position: relative;
                min-width: 280px;
                max-width: 320px;
            }
            .tap-chat-country-search {
                width: 100%;
                padding: 6px 10px;
                border: 1px solid #8c8f94;
                border-radius: 4px;
                font-size: 14px;
                box-shadow: 0 0 0 transparent;
                transition: border-color .1s ease-in-out, box-shadow .1s ease-in-out;
            }
            .tap-chat-country-search:focus {
                border-color: #2271b1;
                box-shadow: 0 0 0 1px #2271b1;
                outline: 2px solid transparent;
            }
            .tap-chat-country-select {
                width: 100%;
                margin-top: 5px;
                max-height: 200px;
                overflow-y: auto;
                border: 1px solid #8c8f94;
                border-radius: 4px;
                background: #fff;
            }
            .tap-chat-country-option {
                padding: 8px 12px;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 8px;
                transition: background-color .15s ease;
            }
            .tap-chat-country-option:hover {
                background-color: #f0f0f1;
            }
            .tap-chat-country-option.selected {
                background-color: #2271b1;
                color: #fff;
            }
            .tap-chat-country-flag {
                font-size: 20px;
                line-height: 1;
            }
            .tap-chat-country-info {
                flex: 1;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .tap-chat-country-name {
                font-weight: 500;
            }
            .tap-chat-country-code {
                color: #646970;
                font-size: 13px;
            }
            .tap-chat-country-option.selected .tap-chat-country-code {
                color: rgba(255,255,255,0.8);
            }
            .tap-chat-phone-input-wrapper {
                flex: 1;
                min-width: 200px;
            }
            .tap-chat-phone-input {
                width: 100%;
            }
            .tap-chat-selected-country {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 6px 10px;
                background: #f0f0f1;
                border: 1px solid #8c8f94;
                border-radius: 4px;
                cursor: pointer;
                margin-bottom: 5px;
            }
            .tap-chat-selected-country:hover {
                background: #e8e8e8;
            }
            
            .tap-chat-page-selector {
                max-width: 600px;
            }
            .tap-chat-search-pages {
                width: 100%;
                padding: 8px 12px;
                border: 1px solid #8c8f94;
                border-radius: 4px;
                font-size: 14px;
                margin-bottom: 10px;
            }
            .tap-chat-search-pages:focus {
                border-color: #2271b1;
                box-shadow: 0 0 0 1px #2271b1;
                outline: 2px solid transparent;
            }
            .tap-chat-pages-list {
                max-height: 250px;
                overflow-y: auto;
                border: 1px solid #8c8f94;
                border-radius: 4px;
                background: #fff;
                margin-bottom: 10px;
            }
            .tap-chat-page-item {
                padding: 8px 12px;
                border-bottom: 1px solid #f0f0f1;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .tap-chat-page-item:last-child {
                border-bottom: none;
            }
            .tap-chat-page-item:hover {
                background-color: #f6f7f7;
            }
            .tap-chat-page-item input[type="checkbox"] {
                margin: 0;
            }
            .tap-chat-page-title {
                font-weight: 500;
                flex: 1;
            }
            .tap-chat-page-type {
                font-size: 12px;
                color: #646970;
                background: #f0f0f1;
                padding: 2px 8px;
                border-radius: 3px;
            }
            .tap-chat-selected-count {
                font-size: 13px;
                color: #2271b1;
                font-weight: 500;
                margin-bottom: 8px;
            }
            .tap-chat-visibility-mode {
                margin-bottom: 15px;
            }
            .tap-chat-visibility-mode label {
                display: inline-block;
                margin-right: 20px;
            }
            
            .tap-chat-working-hours-table {
                width: 100%;
                max-width: 600px;
                border-collapse: collapse;
                margin-top: 10px;
            }
            .tap-chat-working-hours-table th {
                text-align: left;
                padding: 10px;
                background: #f0f0f1;
                font-weight: 600;
            }
            .tap-chat-working-hours-table td {
                padding: 10px;
                border-bottom: 1px solid #f0f0f1;
            }
            .tap-chat-working-hours-table input[type="time"] {
                padding: 4px 8px;
                border: 1px solid #8c8f94;
                border-radius: 3px;
                font-size: 14px;
            }
            .tap-chat-working-hours-table input[type="checkbox"] {
                margin: 0;
            }
            .tap-chat-day-row.disabled {
                opacity: 0.5;
            }
            .tap-chat-day-row.disabled input[type="time"] {
                background: #f0f0f1;
                cursor: not-allowed;
            }
            
            .tapchat-fab-offline {
                cursor: not-allowed !important;
                opacity: 0.7;
            }
            .tapchat-fab-offline:hover {
                transform: none !important;
            }
            
            .tap-chat-avatar-upload {
                display: flex;
                gap: 10px;
                align-items: center;
            }
            .tap-chat-avatar-preview {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                overflow: hidden;
                border: 2px solid #ddd;
                display: none;
            }
            .tap-chat-avatar-preview img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .tap-chat-avatar-preview.has-image {
                display: block;
            }
            .tap-chat-avatar-buttons {
                display: flex;
                gap: 5px;
            }
            
            .tapchat-bubble-close {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 28px !important;
    height: 28px !important;
    border: none !important;
    background: none !important;
    background-color: transparent !important;
    box-shadow: none !important;
    cursor: pointer;
    padding: 0 !important;
    margin: 0 !important;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0.4;
    transition: all 0.3s ease;
    color: #666 !important;
    font-size: 20px !important;
    line-height: 1 !important;
    font-family: Arial, sans-serif !important;
    font-weight: 400 !important;
    z-index: 10;
    outline: none;
    min-width: 28px !important;
    min-height: 28px !important;
    border-radius: 50%;
}
        ' );
        
        wp_add_inline_script( 'wp-color-picker', "
            jQuery(document).ready(function($) {
                $('.tap-chat-color-picker').wpColorPicker();
                
                var selectedCode = $('input[name=\"tap_chat_settings[country_code]\"]').val();
                
                function updateSelectedDisplay() {
                    var code = $('input[name=\"tap_chat_settings[country_code]\"]').val();
                    var option = $('.tap-chat-country-option[data-code=\"' + code + '\"]');
                    if (option.length) {
                        var flag = option.find('.tap-chat-country-flag').text();
                        var name = option.find('.tap-chat-country-name').text();
                        var codeText = option.find('.tap-chat-country-code').text();
                        $('.tap-chat-selected-country').html(
                            '<span class=\"tap-chat-country-flag\">' + flag + '</span>' +
                            '<span>' + name + ' ' + codeText + '</span>'
                        );
                    }
                }
                
                updateSelectedDisplay();
                
                $('.tap-chat-selected-country').on('click', function() {
                    $('.tap-chat-country-search').toggle().focus();
                    $('.tap-chat-country-select').toggle();
                });
                
                $('.tap-chat-country-search').on('input', function() {
                    var search = $(this).val().toLowerCase();
                    $('.tap-chat-country-option').each(function() {
                        var text = $(this).text().toLowerCase();
                        if (text.indexOf(search) > -1) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                });
                
                $('.tap-chat-country-option').on('click', function() {
                    var code = $(this).data('code');
                    $('input[name=\"tap_chat_settings[country_code]\"]').val(code);
                    $('.tap-chat-country-option').removeClass('selected');
                    $(this).addClass('selected');
                    $('.tap-chat-country-search').hide().val('');
                    $('.tap-chat-country-select').hide();
                    $('.tap-chat-country-option').show();
                    updateSelectedDisplay();
                });
                
                $('.tap-chat-country-option[data-code=\"' + selectedCode + '\"]').addClass('selected');
                
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.tap-chat-country-wrapper').length) {
                        $('.tap-chat-country-search').hide();
                        $('.tap-chat-country-select').hide();
                    }
                });
                
                $('.tap-chat-search-pages').on('input', function() {
                    var search = $(this).val().toLowerCase();
                    $('.tap-chat-page-item').each(function() {
                        var title = $(this).find('.tap-chat-page-title').text().toLowerCase();
                        if (title.indexOf(search) > -1) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                });
                
                function updateSelectedCount(list) {
                    var count = $(list).find('input[type=\"checkbox\"]:checked').length;
                    $(list).prev('.tap-chat-selected-count').text(count + ' selected');
                }
                
                $('.tap-chat-pages-list input[type=\"checkbox\"]').on('change', function() {
                    updateSelectedCount($(this).closest('.tap-chat-pages-list'));
                });
                
                $('.tap-chat-pages-list').each(function() {
                    updateSelectedCount(this);
                });
                
                function toggleVisibilitySections() {
                    var showOnChecked = $('#enable_show_on').is(':checked');
                    var hideOnChecked = $('#enable_hide_on').is(':checked');
                    
                    if (showOnChecked) {
                        $('#tap-chat-show-on-section').slideDown(200);
                    } else {
                        $('#tap-chat-show-on-section').slideUp(200);
                    }
                    
                    if (hideOnChecked) {
                        $('#tap-chat-hide-on-section').slideDown(200);
                    } else {
                        $('#tap-chat-hide-on-section').slideUp(200);
                    }
                }
                
                $('#enable_show_on, #enable_hide_on').on('change', function() {
                    toggleVisibilitySections();
                });
                
                toggleVisibilitySections();
                
                function toggleWorkingHoursSection() {
                    if ($('#enable_working_hours').is(':checked')) {
                        $('#tap-chat-working-hours-section').slideDown(200);
                    } else {
                        $('#tap-chat-working-hours-section').slideUp(200);
                    }
                }
                
                $('#enable_working_hours').on('change', function() {
                    toggleWorkingHoursSection();
                });
                
                toggleWorkingHoursSection();
                
                $('.tap-chat-day-enabled').on('change', function() {
                    var row = $(this).closest('tr');
                    var timeInputs = row.find('input[type=\"time\"]');
                    
                    if ($(this).is(':checked')) {
                        row.removeClass('disabled');
                        timeInputs.prop('disabled', false);
                    } else {
                        row.addClass('disabled');
                        timeInputs.prop('disabled', true);
                    }
                });
                
                $('.tap-chat-day-enabled').each(function() {
                    $(this).trigger('change');
                });
                
                function toggleWelcomeBubbleSection() {
                    if ($('#enable_welcome_bubble').is(':checked')) {
                        $('#tap-chat-welcome-bubble-section').slideDown(200);
                    } else {
                        $('#tap-chat-welcome-bubble-section').slideUp(200);
                    }
                }
                
                $('#enable_welcome_bubble').on('change', function() {
                    toggleWelcomeBubbleSection();
                });
                
                toggleWelcomeBubbleSection();
                
                // Avatar Upload
                var mediaUploader;
                
                $('#tap-chat-upload-avatar').on('click', function(e) {
                    e.preventDefault();
                    
                    if (mediaUploader) {
                        mediaUploader.open();
                        return;
                    }
                    
                    mediaUploader = wp.media({
                        title: 'Choose Avatar Image',
                        button: {
                            text: 'Use this image'
                        },
                        multiple: false,
                        library: {
                            type: 'image'
                        }
                    });
                    
                    mediaUploader.on('select', function() {
                        var attachment = mediaUploader.state().get('selection').first().toJSON();
                        $('#tap-chat-avatar-url').val(attachment.url);
                        $('#tap-chat-avatar-preview').attr('src', attachment.url);
                        $('.tap-chat-avatar-preview').addClass('has-image');
                    });
                    
                    mediaUploader.open();
                });
                
                $('#tap-chat-remove-avatar').on('click', function(e) {
                    e.preventDefault();
                    $('#tap-chat-avatar-url').val('');
                    $('#tap-chat-avatar-preview').attr('src', '');
                    $('.tap-chat-avatar-preview').removeClass('has-image');
                });
            });
        " );
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

    public function settings() {
        register_setting( 'tap_chat_settings_group', 'tap_chat_settings', array(
            'type' => 'array',
            'sanitize_callback' => array( $this, 'sanitize' ),
            'default' => array(
                'enable_floating' => 'yes',
                'country_code' => $this->get_default_country_code(),
                'phone' => '',
                'message' => '',
                'label' => __( 'Chat with us', 'tap-chat' ),
                'position' => 'right',
                'size' => 56,
                'mobile_size' => 56,
                'color' => '#25D366',
                'hide_label_mobile' => 'yes',
                'hide_label_desktop' => 'no',
                'append_page_context' => 'no',
                'enable_show_on' => 'no',
                'enable_hide_on' => 'no',
                'show_on_pages' => array(),
                'hide_on_pages' => array(),
                'enable_working_hours' => 'no',
                'timezone' => wp_timezone_string(),
                'offline_message' => '',
                'working_hours' => $this->get_default_working_hours(),
                'enable_welcome_bubble' => 'no',
                'welcome_bubble_message' => __('Need help? Let\'s chat! 💬', 'tap-chat'),
                'welcome_bubble_name' => __('Support Team', 'tap-chat'),
                'welcome_bubble_avatar' => '',
                'welcome_bubble_delay' => 3,
            )
        ) );

        add_settings_section( 'tapchat_main', __( 'General Settings', 'tap-chat' ), '__return_false', 'tap-chat' );

        $fields = array(
            'enable_floating' => __( 'Enable Floating Button', 'tap-chat' ),
            'phone'   => __( 'WhatsApp Phone Number', 'tap-chat' ),
            'message' => __( 'Default Message', 'tap-chat' ),
            'label'   => __( 'Button Label', 'tap-chat' ),
            'position'=> __( 'Floating Position', 'tap-chat' ),
            'size'    => __( 'Desktop Button Size (px)', 'tap-chat' ),
            'mobile_size' => __( 'Mobile Button Size (px)', 'tap-chat' ),
            'color'   => __( 'Button Color', 'tap-chat' ),
            'hide_label_mobile' => __( 'Hide Label on Mobile', 'tap-chat' ),
            'hide_label_desktop' => __( 'Hide Label on Desktop', 'tap-chat' ),
            'append_page_context' => __( 'Append Page Title & URL to Message', 'tap-chat' ),
        );

        foreach ( $fields as $key => $label ) {
            add_settings_field( $key, $label, array( $this, 'field_' . $key ), 'tap-chat', 'tapchat_main' );
        }
        
        add_settings_section( 'tapchat_visibility', __( 'Visibility Settings', 'tap-chat' ), array( $this, 'visibility_section_callback' ), 'tap-chat' );
        add_settings_field( 'visibility_controls', __( 'Control Button Display', 'tap-chat' ), array( $this, 'field_visibility_controls' ), 'tap-chat', 'tapchat_visibility' );
        
        add_settings_section( 'tapchat_working_hours', __( 'Working Hours', 'tap-chat' ), array( $this, 'working_hours_section_callback' ), 'tap-chat' );
        add_settings_field( 'working_hours_controls', __( 'Business Hours Settings', 'tap-chat' ), array( $this, 'field_working_hours_controls' ), 'tap-chat', 'tapchat_working_hours' );
        
        add_settings_section( 'tapchat_welcome_bubble', __( 'Welcome Bubble', 'tap-chat' ), array( $this, 'welcome_bubble_section_callback' ), 'tap-chat' );
        add_settings_field( 'welcome_bubble_controls', __( 'Chat Bubble Settings', 'tap-chat' ), array( $this, 'field_welcome_bubble_controls' ), 'tap-chat', 'tapchat_welcome_bubble' );
    }
    
    public function visibility_section_callback() {
        echo '<p>' . esc_html__( 'Control where the WhatsApp button appears on your site. By default, it shows on all pages.', 'tap-chat' ) . '</p>';
    }
    
    public function working_hours_section_callback() {
        echo '<p>' . esc_html__( 'Set your business hours. The button will only be shown during these hours. Useful for customer service teams.', 'tap-chat' ) . '</p>';
    }
    
    public function welcome_bubble_section_callback() {
        echo '<p>' . esc_html__( 'Display a friendly welcome message above your chat button to encourage visitors to start a conversation.', 'tap-chat' ) . '</p>';
    }

    private function get_default_country_code() {
        $locale = get_locale();
        
        $locale_map = array(
            'de_DE' => '49', 'de_AT' => '43', 'de_CH' => '41',
            'en_US' => '1', 'en_GB' => '44', 'en_CA' => '1', 'en_AU' => '61', 'en_NZ' => '64',
            'fr_FR' => '33', 'fr_BE' => '32', 'fr_CA' => '1', 'fr_CH' => '41',
            'es_ES' => '34', 'es_MX' => '52', 'es_AR' => '54', 'es_CO' => '57',
            'it_IT' => '39', 'nl_NL' => '31', 'pt_PT' => '351', 'pt_BR' => '55',
        );
        
        if ( isset( $locale_map[ $locale ] ) ) {
            return $locale_map[ $locale ];
        }
        
        $lang = substr( $locale, 0, 2 );
        foreach ( $locale_map as $loc => $code ) {
            if ( substr( $loc, 0, 2 ) === $lang ) {
                return $code;
            }
        }
        
        return '49';
    }
    
    private function get_default_working_hours() {
        return array(
            'monday' => array('enabled' => 'yes', 'start' => '09:00', 'end' => '17:00'),
            'tuesday' => array('enabled' => 'yes', 'start' => '09:00', 'end' => '17:00'),
            'wednesday' => array('enabled' => 'yes', 'start' => '09:00', 'end' => '17:00'),
            'thursday' => array('enabled' => 'yes', 'start' => '09:00', 'end' => '17:00'),
            'friday' => array('enabled' => 'yes', 'start' => '09:00', 'end' => '17:00'),
            'saturday' => array('enabled' => 'no', 'start' => '09:00', 'end' => '17:00'),
            'sunday' => array('enabled' => 'no', 'start' => '09:00', 'end' => '17:00'),
        );
    }

    public function sanitize( $input ) {
        $out = array();
        $out['enable_floating'] = ( isset( $input['enable_floating'] ) && $input['enable_floating'] === 'yes' ) ? 'yes' : 'no';
        $out['country_code'] = isset( $input['country_code'] ) ? sanitize_text_field( $input['country_code'] ) : $this->get_default_country_code();
        
        $phone = isset( $input['phone'] ) ? sanitize_text_field( $input['phone'] ) : '';
        $phone = preg_replace('/[\s\-\(\)]/', '', $phone);
        $phone = ltrim($phone, '0');
        $out['phone'] = $phone;
        
        $out['message'] = isset( $input['message'] ) ? sanitize_textarea_field( $input['message'] ) : '';
        $out['label']   = isset( $input['label'] ) ? sanitize_text_field( $input['label'] ) : '';
        $out['position']= ( isset( $input['position'] ) && in_array( $input['position'], array( 'left','right' ), true ) ) ? $input['position'] : 'right';
        $out['size']    = isset( $input['size'] ) ? absint( $input['size'] ) : 56;
        $out['mobile_size'] = isset( $input['mobile_size'] ) ? absint( $input['mobile_size'] ) : 56;
        $out['color']   = isset( $input['color'] ) ? sanitize_hex_color( $input['color'] ) : '#25D366';
        $out['hide_label_mobile'] = ( isset( $input['hide_label_mobile'] ) && $input['hide_label_mobile'] === 'yes' ) ? 'yes' : 'no';
        $out['hide_label_desktop'] = ( isset( $input['hide_label_desktop'] ) && $input['hide_label_desktop'] === 'yes' ) ? 'yes' : 'no';
        $out['append_page_context'] = ( isset( $input['append_page_context'] ) && $input['append_page_context'] === 'yes' ) ? 'yes' : 'no';
        
        $out['enable_show_on'] = ( isset( $input['enable_show_on'] ) && $input['enable_show_on'] === 'yes' ) ? 'yes' : 'no';
        $out['enable_hide_on'] = ( isset( $input['enable_hide_on'] ) && $input['enable_hide_on'] === 'yes' ) ? 'yes' : 'no';
        $out['show_on_pages'] = isset( $input['show_on_pages'] ) && is_array( $input['show_on_pages'] ) ? array_map( 'absint', $input['show_on_pages'] ) : array();
        $out['hide_on_pages'] = isset( $input['hide_on_pages'] ) && is_array( $input['hide_on_pages'] ) ? array_map( 'absint', $input['hide_on_pages'] ) : array();
        
        $out['enable_working_hours'] = ( isset( $input['enable_working_hours'] ) && $input['enable_working_hours'] === 'yes' ) ? 'yes' : 'no';
        $out['timezone'] = isset( $input['timezone'] ) ? sanitize_text_field( $input['timezone'] ) : wp_timezone_string();
        $out['offline_message'] = isset( $input['offline_message'] ) ? sanitize_textarea_field( $input['offline_message'] ) : '';
        
        if ( isset( $input['working_hours'] ) && is_array( $input['working_hours'] ) ) {
            $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
            $out['working_hours'] = array();
            
            foreach ( $days as $day ) {
                if ( isset( $input['working_hours'][$day] ) ) {
                    $out['working_hours'][$day] = array(
                        'enabled' => ( isset( $input['working_hours'][$day]['enabled'] ) && $input['working_hours'][$day]['enabled'] === 'yes' ) ? 'yes' : 'no',
                        'start' => isset( $input['working_hours'][$day]['start'] ) ? sanitize_text_field( $input['working_hours'][$day]['start'] ) : '09:00',
                        'end' => isset( $input['working_hours'][$day]['end'] ) ? sanitize_text_field( $input['working_hours'][$day]['end'] ) : '17:00',
                    );
                }
            }
        } else {
            $out['working_hours'] = $this->get_default_working_hours();
        }
        
        $out['enable_welcome_bubble'] = ( isset( $input['enable_welcome_bubble'] ) && $input['enable_welcome_bubble'] === 'yes' ) ? 'yes' : 'no';
        $out['welcome_bubble_message'] = isset( $input['welcome_bubble_message'] ) ? sanitize_textarea_field( $input['welcome_bubble_message'] ) : '';
        $out['welcome_bubble_name'] = isset( $input['welcome_bubble_name'] ) ? sanitize_text_field( $input['welcome_bubble_name'] ) : '';
        $out['welcome_bubble_avatar'] = isset( $input['welcome_bubble_avatar'] ) ? esc_url_raw( $input['welcome_bubble_avatar'] ) : '';
        $out['welcome_bubble_delay'] = isset( $input['welcome_bubble_delay'] ) ? absint( $input['welcome_bubble_delay'] ) : 3;
        
        return $out;
    }

    private function get( $key, $default = '' ) {
        $opts = get_option( 'tap_chat_settings', array() );
        return isset( $opts[ $key ] ) ? $opts[ $key ] : $default;
    }

    private function get_countries() {
        return array(
            '93' => array('name' => 'Afghanistan', 'flag' => '🇦🇫'),
            '355' => array('name' => 'Albania', 'flag' => '🇦🇱'),
            '213' => array('name' => 'Algeria', 'flag' => '🇩🇿'),
            '1' => array('name' => 'Canada / United States', 'flag' => '🇨🇦'),
            '49' => array('name' => 'Germany', 'flag' => '🇩🇪'),
            '44' => array('name' => 'United Kingdom', 'flag' => '🇬🇧'),
            '33' => array('name' => 'France', 'flag' => '🇫🇷'),
            '34' => array('name' => 'Spain', 'flag' => '🇪🇸'),
            '39' => array('name' => 'Italy', 'flag' => '🇮🇹'),
            '31' => array('name' => 'Netherlands', 'flag' => '🇳🇱'),
            '32' => array('name' => 'Belgium', 'flag' => '🇧🇪'),
            '41' => array('name' => 'Switzerland', 'flag' => '🇨🇭'),
            '43' => array('name' => 'Austria', 'flag' => '🇦🇹'),
            '351' => array('name' => 'Portugal', 'flag' => '🇵🇹'),
            '55' => array('name' => 'Brazil', 'flag' => '🇧🇷'),
            '86' => array('name' => 'China', 'flag' => '🇨🇳'),
            '91' => array('name' => 'India', 'flag' => '🇮🇳'),
            '81' => array('name' => 'Japan', 'flag' => '🇯🇵'),
            '61' => array('name' => 'Australia', 'flag' => '🇦🇺'),
        );
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
        $country_code = $this->get( 'country_code', $this->get_default_country_code() );
        $phone = $this->get( 'phone', '' );
        $countries = $this->get_countries();
        ?>
        <div class="tap-chat-phone-wrapper">
            <div class="tap-chat-country-wrapper">
                <input type="hidden" name="tap_chat_settings[country_code]" value="<?php echo esc_attr( $country_code ); ?>" />
                
                <div class="tap-chat-selected-country">
                    <?php 
                    $selected = $countries[$country_code];
                    echo esc_html( $selected['flag'] . ' ' . $selected['name'] . ' (+' . $country_code . ')' );
                    ?>
                </div>
                
                <input type="text" 
                       class="tap-chat-country-search" 
                       placeholder="<?php esc_attr_e( 'Search country...', 'tap-chat' ); ?>" 
                       style="display:none;" />
                
                <div class="tap-chat-country-select" style="display:none;">
                    <?php foreach ( $countries as $code => $data ) : ?>
                        <div class="tap-chat-country-option" data-code="<?php echo esc_attr( $code ); ?>">
                            <span class="tap-chat-country-flag"><?php echo esc_html( $data['flag'] ); ?></span>
                            <span class="tap-chat-country-info">
                                <span class="tap-chat-country-name"><?php echo esc_html( $data['name'] ); ?></span>
                                <span class="tap-chat-country-code">+<?php echo esc_html( $code ); ?></span>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="tap-chat-phone-input-wrapper">
                <input type="text" 
                       name="tap_chat_settings[phone]" 
                       value="<?php echo esc_attr( $phone ); ?>" 
                       placeholder="<?php esc_attr_e( '123456789', 'tap-chat' ); ?>" 
                       class="regular-text tap-chat-phone-input" />
            </div>
        </div>
        <p class="description">
            <?php esc_html_e( 'Select your country and enter your phone number (without country code or leading zero)', 'tap-chat' ); ?>
        </p>
        <?php
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
            '<input type="number" min="40" max="96" name="tap_chat_settings[size]" value="%d" /> <small>%s</small>',
            absint( $this->get( 'size', 56 ) ),
            esc_html__( 'Size for desktop screens', 'tap-chat' )
        );
    }

    public function field_mobile_size() {
        printf(
            '<input type="number" min="40" max="96" name="tap_chat_settings[mobile_size]" value="%d" /> <small>%s</small>',
            absint( $this->get( 'mobile_size', 56 ) ),
            esc_html__( 'Size for mobile screens (≤ 480px)', 'tap-chat' )
        );
    }

    public function field_color() {
        printf(
            '<input type="text" class="tap-chat-color-picker" name="tap_chat_settings[color]" value="%s" data-default-color="#25D366" />',
            esc_attr( $this->get( 'color', '#25D366' ) )
        );
    }

    public function field_hide_label_mobile() {
        $val = $this->get( 'hide_label_mobile', 'yes' );
        ?>
        <input type="hidden" name="tap_chat_settings[hide_label_mobile]" value="no" />
        <label><input type="checkbox" name="tap_chat_settings[hide_label_mobile]" value="yes" <?php checked( $val, 'yes' ); ?> /> <?php esc_html_e( 'Hide label on screens ≤ 480px (show only icon)', 'tap-chat' ); ?></label>
        <?php
    }

    public function field_hide_label_desktop() {
        $val = $this->get( 'hide_label_desktop', 'no' );
        ?>
        <input type="hidden" name="tap_chat_settings[hide_label_desktop]" value="no" />
        <label><input type="checkbox" name="tap_chat_settings[hide_label_desktop]" value="yes" <?php checked( $val, 'yes' ); ?> /> <?php esc_html_e( 'Hide label on screens > 480px (show only icon)', 'tap-chat' ); ?></label>
        <?php
    }

    public function field_append_page_context() {
        $val = $this->get( 'append_page_context', 'no' );
        ?>
        <input type="hidden" name="tap_chat_settings[append_page_context]" value="no" />
        <label><input type="checkbox" name="tap_chat_settings[append_page_context]" value="yes" <?php checked( $val, 'yes' ); ?> /> <?php esc_html_e( 'Append current page title & URL to message', 'tap-chat' ); ?></label>
        <?php
    }
    
    public function field_visibility_controls() {
        $enable_show_on = $this->get( 'enable_show_on', 'no' );
        $enable_hide_on = $this->get( 'enable_hide_on', 'no' );
        $show_on_pages = $this->get( 'show_on_pages', array() );
        $hide_on_pages = $this->get( 'hide_on_pages', array() );
        ?>
        
        <div style="margin-bottom: 30px; padding: 15px; background: #f9f9f9; border-left: 4px solid #2271b1; border-radius: 4px;">
            <input type="hidden" name="tap_chat_settings[enable_show_on]" value="no" />
            <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; margin-bottom: 15px;">
                <input type="checkbox" 
                       name="tap_chat_settings[enable_show_on]" 
                       value="yes" 
                       id="enable_show_on"
                       <?php checked( $enable_show_on, 'yes' ); ?> />
                <?php esc_html_e( '✓ Show button ONLY on specific pages', 'tap-chat' ); ?>
            </label>
            <p class="description" style="margin: 0 0 15px 28px;">
                <?php esc_html_e( 'When enabled, button will appear only on pages you select below.', 'tap-chat' ); ?>
            </p>
            
            <div id="tap-chat-show-on-section" style="margin-left: 28px;">
                <?php $this->render_page_selector( 'show_on_pages', $show_on_pages ); ?>
            </div>
        </div>
        
        <div style="padding: 15px; background: #f9f9f9; border-left: 4px solid #d63638; border-radius: 4px;">
            <input type="hidden" name="tap_chat_settings[enable_hide_on]" value="no" />
            <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; margin-bottom: 15px;">
                <input type="checkbox" 
                       name="tap_chat_settings[enable_hide_on]" 
                       value="yes"
                       id="enable_hide_on" 
                       <?php checked( $enable_hide_on, 'yes' ); ?> />
                <?php esc_html_e( '✗ Hide button on specific pages', 'tap-chat' ); ?>
            </label>
            <p class="description" style="margin: 0 0 15px 28px;">
                <?php esc_html_e( 'Hide the button on selected pages.', 'tap-chat' ); ?>
            </p>
            
            <div id="tap-chat-hide-on-section" style="margin-left: 28px;">
                <?php $this->render_page_selector( 'hide_on_pages', $hide_on_pages ); ?>
            </div>
        </div>
        <?php
    }
    
    public function field_working_hours_controls() {
        $enable_working_hours = $this->get( 'enable_working_hours', 'no' );
        $timezone = $this->get( 'timezone', wp_timezone_string() );
        $offline_message = $this->get( 'offline_message', '' );
        $working_hours = $this->get( 'working_hours', $this->get_default_working_hours() );
        
        $days = array(
            'monday' => __( 'Monday', 'tap-chat' ),
            'tuesday' => __( 'Tuesday', 'tap-chat' ),
            'wednesday' => __( 'Wednesday', 'tap-chat' ),
            'thursday' => __( 'Thursday', 'tap-chat' ),
            'friday' => __( 'Friday', 'tap-chat' ),
            'saturday' => __( 'Saturday', 'tap-chat' ),
            'sunday' => __( 'Sunday', 'tap-chat' ),
        );
        ?>
        
        <div style="padding: 15px; background: #f9f9f9; border-left: 4px solid #00a32a; border-radius: 4px;">
            <input type="hidden" name="tap_chat_settings[enable_working_hours]" value="no" />
            <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; margin-bottom: 15px;">
                <input type="checkbox" 
                       name="tap_chat_settings[enable_working_hours]" 
                       value="yes" 
                       id="enable_working_hours"
                       <?php checked( $enable_working_hours, 'yes' ); ?> />
                <?php esc_html_e( '🕐 Enable Working Hours', 'tap-chat' ); ?>
            </label>
            <p class="description" style="margin: 0 0 15px 28px;">
                <?php esc_html_e( 'Show the button only during your business hours. Perfect for customer support teams.', 'tap-chat' ); ?>
            </p>
            
            <div id="tap-chat-working-hours-section" style="margin-left: 28px;">
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 5px;">
                        <?php esc_html_e( 'Timezone', 'tap-chat' ); ?>
                    </label>
                    <select name="tap_chat_settings[timezone]" class="regular-text">
                        <?php
                        $timezones = timezone_identifiers_list();
                        foreach ( $timezones as $tz ) {
                            printf(
                                '<option value="%s" %s>%s</option>',
                                esc_attr( $tz ),
                                selected( $timezone, $tz, false ),
                                esc_html( $tz )
                            );
                        }
                        ?>
                    </select>
                    <p class="description">
                        <?php esc_html_e( 'Select your business timezone', 'tap-chat' ); ?>
                    </p>
                </div>
                
                <table class="tap-chat-working-hours-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Day', 'tap-chat' ); ?></th>
                            <th><?php esc_html_e( 'Open', 'tap-chat' ); ?></th>
                            <th><?php esc_html_e( 'Start Time', 'tap-chat' ); ?></th>
                            <th><?php esc_html_e( 'End Time', 'tap-chat' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $days as $day => $label ) : 
                            $day_data = isset( $working_hours[$day] ) ? $working_hours[$day] : array('enabled' => 'no', 'start' => '09:00', 'end' => '17:00');
                        ?>
                        <tr class="tap-chat-day-row">
                            <td><strong><?php echo esc_html( $label ); ?></strong></td>
                            <td>
                                <input type="hidden" name="tap_chat_settings[working_hours][<?php echo esc_attr( $day ); ?>][enabled]" value="no" />
                                <input type="checkbox" 
                                       class="tap-chat-day-enabled"
                                       name="tap_chat_settings[working_hours][<?php echo esc_attr( $day ); ?>][enabled]" 
                                       value="yes" 
                                       <?php checked( $day_data['enabled'], 'yes' ); ?> />
                            </td>
                            <td>
                                <input type="time" 
                                       name="tap_chat_settings[working_hours][<?php echo esc_attr( $day ); ?>][start]" 
                                       value="<?php echo esc_attr( $day_data['start'] ); ?>" />
                            </td>
                            <td>
                                <input type="time" 
                                       name="tap_chat_settings[working_hours][<?php echo esc_attr( $day ); ?>][end]" 
                                       value="<?php echo esc_attr( $day_data['end'] ); ?>" />
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div style="margin-top: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 5px;">
                        <?php esc_html_e( 'Offline Message (Optional)', 'tap-chat' ); ?>
                    </label>
                    <textarea name="tap_chat_settings[offline_message]" 
                              rows="3" 
                              class="large-text" 
                              placeholder="<?php esc_attr_e( 'We are currently offline. Our business hours are Monday-Friday, 9 AM - 5 PM.', 'tap-chat' ); ?>"><?php echo esc_textarea( $offline_message ); ?></textarea>
                    <p class="description">
                        <?php esc_html_e( 'This message will replace the button label when outside working hours. Leave empty to hide the button completely.', 'tap-chat' ); ?>
                    </p>
                </div>
                
            </div>
        </div>
        <?php
    }
    
    public function field_welcome_bubble_controls() {
        $enable = $this->get('enable_welcome_bubble', 'no');
        $message = $this->get('welcome_bubble_message', __('Need help? Let\'s chat! 💬', 'tap-chat'));
        $delay = $this->get('welcome_bubble_delay', 3);
        $avatar = $this->get('welcome_bubble_avatar', '');
        $name = $this->get('welcome_bubble_name', __('Support Team', 'tap-chat'));
        ?>
        
        <div style="padding: 15px; background: #f9f9f9; border-left: 4px solid #2271b1; border-radius: 4px;">
            
            <input type="hidden" name="tap_chat_settings[enable_welcome_bubble]" value="no" />
            <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; margin-bottom: 15px;">
                <input type="checkbox" 
                       name="tap_chat_settings[enable_welcome_bubble]" 
                       value="yes" 
                       id="enable_welcome_bubble"
                       <?php checked($enable, 'yes'); ?> />
                <?php esc_html_e('💬 Enable Welcome Bubble', 'tap-chat'); ?>
            </label>
            <p class="description" style="margin: 0 0 15px 28px;">
                <?php esc_html_e('Show a friendly message bubble above the chat button to encourage visitors to start a conversation.', 'tap-chat'); ?>
            </p>
            
            <div id="tap-chat-welcome-bubble-section" style="margin-left: 28px;">
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 5px;">
                        <?php esc_html_e('Welcome Message', 'tap-chat'); ?>
                    </label>
                    <textarea name="tap_chat_settings[welcome_bubble_message]" 
                              rows="3" 
                              class="large-text" 
                              placeholder="<?php esc_attr_e('Need help? Let\'s chat! 💬', 'tap-chat'); ?>"><?php echo esc_textarea($message); ?></textarea>
                    <p class="description">
                        <?php esc_html_e('The message to display in the welcome bubble. Emojis are supported!', 'tap-chat'); ?>
                    </p>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 5px;">
                        <?php esc_html_e('Agent/Team Name', 'tap-chat'); ?>
                    </label>
                    <input type="text" 
                           name="tap_chat_settings[welcome_bubble_name]" 
                           value="<?php echo esc_attr($name); ?>" 
                           class="regular-text"
                           placeholder="<?php esc_attr_e('Support Team', 'tap-chat'); ?>" />
                    <p class="description">
                        <?php esc_html_e('Display name for the agent or team', 'tap-chat'); ?>
                    </p>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 5px;">
                        <?php esc_html_e('Avatar URL (Optional)', 'tap-chat'); ?>
                    </label>
                    <div class="tap-chat-avatar-upload">
                        <div class="tap-chat-avatar-preview<?php echo !empty($avatar) ? ' has-image' : ''; ?>">
                            <img id="tap-chat-avatar-preview" src="<?php echo esc_url($avatar); ?>" alt="Avatar" />
                        </div>
                        <div>
                            <input type="url" 
                                   id="tap-chat-avatar-url"
                                   name="tap_chat_settings[welcome_bubble_avatar]" 
                                   value="<?php echo esc_url($avatar); ?>" 
                                   class="regular-text"
                                   placeholder="https://example.com/avatar.jpg"
                                   style="margin-bottom: 5px;" />
                            <div class="tap-chat-avatar-buttons">
                                <button type="button" id="tap-chat-upload-avatar" class="button">
                                    <?php esc_html_e('Choose Image', 'tap-chat'); ?>
                                </button>
                                <button type="button" id="tap-chat-remove-avatar" class="button">
                                    <?php esc_html_e('Remove', 'tap-chat'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    <p class="description">
                        <?php esc_html_e('Upload an avatar image or enter URL. Leave empty to use default WhatsApp icon.', 'tap-chat'); ?>
                    </p>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 5px;">
                        <?php esc_html_e('Display Delay (seconds)', 'tap-chat'); ?>
                    </label>
                    <input type="number" 
                           name="tap_chat_settings[welcome_bubble_delay]" 
                           value="<?php echo esc_attr($delay); ?>" 
                           min="0" 
                           max="60" 
                           step="1" />
                    <p class="description">
                        <?php esc_html_e('How many seconds to wait before showing the bubble. 0 = show immediately.', 'tap-chat'); ?>
                    </p>
                </div>
                
            </div>
        </div>
        
        <?php
    }
    
    private function render_page_selector( $field_name, $selected_pages ) {
        $pages = get_pages( array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'numberposts' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ) );
        
        $posts = get_posts( array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'numberposts' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ) );
        
        $all_content = array_merge( $pages, $posts );
        ?>
        <div class="tap-chat-page-selector">
            <input type="text" 
                   class="tap-chat-search-pages" 
                   placeholder="<?php esc_attr_e( 'Search pages or posts...', 'tap-chat' ); ?>" />
            
            <div class="tap-chat-selected-count">0 selected</div>
            
            <div class="tap-chat-pages-list">
                <?php if ( ! empty( $all_content ) ) : ?>
                    <?php foreach ( $all_content as $content ) : ?>
                        <div class="tap-chat-page-item">
                            <input type="checkbox" 
                                   name="tap_chat_settings[<?php echo esc_attr( $field_name ); ?>][]" 
                                   value="<?php echo esc_attr( $content->ID ); ?>"
                                   id="page-<?php echo esc_attr( $field_name . '-' . $content->ID ); ?>"
                                   <?php checked( in_array( $content->ID, (array) $selected_pages ) ); ?> />
                            <label for="page-<?php echo esc_attr( $field_name . '-' . $content->ID ); ?>" class="tap-chat-page-title">
                                <?php echo esc_html( $content->post_title ); ?>
                            </label>
                            <span class="tap-chat-page-type">
                                <?php echo esc_html( ucfirst( $content->post_type ) ); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="tap-chat-page-item">
                        <p><?php esc_html_e( 'No pages or posts found.', 'tap-chat' ); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}