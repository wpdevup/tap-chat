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
        // Only load scripts on plugin settings page
        if ( 'settings_page_tap-chat' !== $hook ) {
            return;
        }
        
        // WordPress Color Picker scripts
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        
        // Custom admin styles
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
            
            /* Page Selector Styles */
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
        ' );
        
        // Custom JS for country search and selection
        wp_add_inline_script( 'wp-color-picker', "
            jQuery(document).ready(function($) {
                // Color Picker
                $('.tap-chat-color-picker').wpColorPicker();
                
                // Country Selector with Search
                var selectedCode = $('input[name=\"tap_chat_settings[country_code]\"]').val();
                
                // Update selected display
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
                
                // Toggle dropdown
                $('.tap-chat-selected-country').on('click', function() {
                    $('.tap-chat-country-search').toggle().focus();
                    $('.tap-chat-country-select').toggle();
                });
                
                // Search functionality
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
                
                // Select country
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
                
                // Mark selected
                $('.tap-chat-country-option[data-code=\"' + selectedCode + '\"]').addClass('selected');
                
                // Hide dropdown when clicking outside
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.tap-chat-country-wrapper').length) {
                        $('.tap-chat-country-search').hide();
                        $('.tap-chat-country-select').hide();
                    }
                });
                
                // Page Selector Search
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
                
                // Update selected count
                function updateSelectedCount(list) {
                    var count = $(list).find('input[type=\"checkbox\"]:checked').length;
                    $(list).prev('.tap-chat-selected-count').text(count + ' selected');
                }
                
                $('.tap-chat-pages-list input[type=\"checkbox\"]').on('change', function() {
                    updateSelectedCount($(this).closest('.tap-chat-pages-list'));
                });
                
                // Initialize counts
                $('.tap-chat-pages-list').each(function() {
                    updateSelectedCount(this);
                });
                
                // Visibility mode toggle - THIS IS THE KEY PART
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
                
                // Initialize visibility sections on page load
                toggleVisibilitySections();
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
        
        // Visibility section
        add_settings_section( 'tapchat_visibility', __( 'Visibility Settings', 'tap-chat' ), array( $this, 'visibility_section_callback' ), 'tap-chat' );
        add_settings_field( 'visibility_controls', __( 'Control Button Display', 'tap-chat' ), array( $this, 'field_visibility_controls' ), 'tap-chat', 'tapchat_visibility' );
    }
    
    public function visibility_section_callback() {
        echo '<p>' . esc_html__( 'Control where the WhatsApp button appears on your site. By default, it shows on all pages.', 'tap-chat' ) . '</p>';
    }

    private function get_default_country_code() {
        $locale = get_locale();
        
        // Map of locale to country codes
        $locale_map = array(
            'de_DE' => '49',
            'de_AT' => '43',
            'de_CH' => '41',
            'en_US' => '1',
            'en_GB' => '44',
            'en_CA' => '1',
            'en_AU' => '61',
            'en_NZ' => '64',
            'fr_FR' => '33',
            'fr_BE' => '32',
            'fr_CA' => '1',
            'fr_CH' => '41',
            'es_ES' => '34',
            'es_MX' => '52',
            'es_AR' => '54',
            'es_CO' => '57',
            'es_CL' => '56',
            'es_PE' => '51',
            'es_VE' => '58',
            'it_IT' => '39',
            'it_CH' => '41',
            'nl_NL' => '31',
            'nl_BE' => '32',
            'pt_PT' => '351',
            'pt_BR' => '55',
            'ru_RU' => '7',
            'pl_PL' => '48',
            'tr_TR' => '90',
            'ja' => '81',
            'zh_CN' => '86',
            'zh_TW' => '886',
            'ko_KR' => '82',
            'ar' => '966',
            'he_IL' => '972',
            'hi_IN' => '91',
            'th' => '66',
            'vi' => '84',
            'id_ID' => '62',
            'ms_MY' => '60',
            'sv_SE' => '46',
            'da_DK' => '45',
            'fi' => '358',
            'no' => '47',
            'cs_CZ' => '420',
            'hu_HU' => '36',
            'ro_RO' => '40',
            'uk' => '380',
            'el' => '30',
            'bg_BG' => '359',
            'hr' => '385',
            'sk_SK' => '421',
            'sl_SI' => '386',
            'sr_RS' => '381',
        );
        
        if ( isset( $locale_map[ $locale ] ) ) {
            return $locale_map[ $locale ];
        }
        
        // Try to match just the language part (first 2 characters)
        $lang = substr( $locale, 0, 2 );
        foreach ( $locale_map as $loc => $code ) {
            if ( substr( $loc, 0, 2 ) === $lang ) {
                return $code;
            }
        }
        
        // Default to Germany if no match
        return '49';
    }

    public function sanitize( $input ) {
        $out = array();
        $out['enable_floating'] = ( isset( $input['enable_floating'] ) && $input['enable_floating'] === 'yes' ) ? 'yes' : 'no';
        $out['country_code'] = isset( $input['country_code'] ) ? sanitize_text_field( $input['country_code'] ) : $this->get_default_country_code();
        
        // Clean phone number: remove spaces, dashes, and leading zeros
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
        
        // Visibility settings - now using checkboxes
        $out['enable_show_on'] = ( isset( $input['enable_show_on'] ) && $input['enable_show_on'] === 'yes' ) ? 'yes' : 'no';
        $out['enable_hide_on'] = ( isset( $input['enable_hide_on'] ) && $input['enable_hide_on'] === 'yes' ) ? 'yes' : 'no';
        $out['show_on_pages'] = isset( $input['show_on_pages'] ) && is_array( $input['show_on_pages'] ) ? array_map( 'absint', $input['show_on_pages'] ) : array();
        $out['hide_on_pages'] = isset( $input['hide_on_pages'] ) && is_array( $input['hide_on_pages'] ) ? array_map( 'absint', $input['hide_on_pages'] ) : array();
        
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
            '376' => array('name' => 'Andorra', 'flag' => '🇦🇩'),
            '244' => array('name' => 'Angola', 'flag' => '🇦🇴'),
            '54' => array('name' => 'Argentina', 'flag' => '🇦🇷'),
            '374' => array('name' => 'Armenia', 'flag' => '🇦🇲'),
            '61' => array('name' => 'Australia', 'flag' => '🇦🇺'),
            '43' => array('name' => 'Austria', 'flag' => '🇦🇹'),
            '994' => array('name' => 'Azerbaijan', 'flag' => '🇦🇿'),
            '973' => array('name' => 'Bahrain', 'flag' => '🇧🇭'),
            '880' => array('name' => 'Bangladesh', 'flag' => '🇧🇩'),
            '375' => array('name' => 'Belarus', 'flag' => '🇧🇾'),
            '32' => array('name' => 'Belgium', 'flag' => '🇧🇪'),
            '501' => array('name' => 'Belize', 'flag' => '🇧🇿'),
            '229' => array('name' => 'Benin', 'flag' => '🇧🇯'),
            '975' => array('name' => 'Bhutan', 'flag' => '🇧🇹'),
            '591' => array('name' => 'Bolivia', 'flag' => '🇧🇴'),
            '387' => array('name' => 'Bosnia and Herzegovina', 'flag' => '🇧🇦'),
            '267' => array('name' => 'Botswana', 'flag' => '🇧🇼'),
            '55' => array('name' => 'Brazil', 'flag' => '🇧🇷'),
            '673' => array('name' => 'Brunei', 'flag' => '🇧🇳'),
            '359' => array('name' => 'Bulgaria', 'flag' => '🇧🇬'),
            '226' => array('name' => 'Burkina Faso', 'flag' => '🇧🇫'),
            '257' => array('name' => 'Burundi', 'flag' => '🇧🇮'),
            '855' => array('name' => 'Cambodia', 'flag' => '🇰🇭'),
            '237' => array('name' => 'Cameroon', 'flag' => '🇨🇲'),
            '1' => array('name' => 'Canada / United States', 'flag' => '🇨🇦'),
            '238' => array('name' => 'Cape Verde', 'flag' => '🇨🇻'),
            '236' => array('name' => 'Central African Republic', 'flag' => '🇨🇫'),
            '235' => array('name' => 'Chad', 'flag' => '🇹🇩'),
            '56' => array('name' => 'Chile', 'flag' => '🇨🇱'),
            '86' => array('name' => 'China', 'flag' => '🇨🇳'),
            '57' => array('name' => 'Colombia', 'flag' => '🇨🇴'),
            '269' => array('name' => 'Comoros', 'flag' => '🇰🇲'),
            '242' => array('name' => 'Congo', 'flag' => '🇨🇬'),
            '243' => array('name' => 'Congo (DRC)', 'flag' => '🇨🇩'),
            '506' => array('name' => 'Costa Rica', 'flag' => '🇨🇷'),
            '385' => array('name' => 'Croatia', 'flag' => '🇭🇷'),
            '53' => array('name' => 'Cuba', 'flag' => '🇨🇺'),
            '357' => array('name' => 'Cyprus', 'flag' => '🇨🇾'),
            '420' => array('name' => 'Czech Republic', 'flag' => '🇨🇿'),
            '45' => array('name' => 'Denmark', 'flag' => '🇩🇰'),
            '253' => array('name' => 'Djibouti', 'flag' => '🇩🇯'),
            '593' => array('name' => 'Ecuador', 'flag' => '🇪🇨'),
            '20' => array('name' => 'Egypt', 'flag' => '🇪🇬'),
            '503' => array('name' => 'El Salvador', 'flag' => '🇸🇻'),
            '240' => array('name' => 'Equatorial Guinea', 'flag' => '🇬🇶'),
            '291' => array('name' => 'Eritrea', 'flag' => '🇪🇷'),
            '372' => array('name' => 'Estonia', 'flag' => '🇪🇪'),
            '251' => array('name' => 'Ethiopia', 'flag' => '🇪🇹'),
            '358' => array('name' => 'Finland', 'flag' => '🇫🇮'),
            '33' => array('name' => 'France', 'flag' => '🇫🇷'),
            '241' => array('name' => 'Gabon', 'flag' => '🇬🇦'),
            '220' => array('name' => 'Gambia', 'flag' => '🇬🇲'),
            '995' => array('name' => 'Georgia', 'flag' => '🇬🇪'),
            '49' => array('name' => 'Germany', 'flag' => '🇩🇪'),
            '233' => array('name' => 'Ghana', 'flag' => '🇬🇭'),
            '30' => array('name' => 'Greece', 'flag' => '🇬🇷'),
            '502' => array('name' => 'Guatemala', 'flag' => '🇬🇹'),
            '224' => array('name' => 'Guinea', 'flag' => '🇬🇳'),
            '245' => array('name' => 'Guinea-Bissau', 'flag' => '🇬🇼'),
            '592' => array('name' => 'Guyana', 'flag' => '🇬🇾'),
            '509' => array('name' => 'Haiti', 'flag' => '🇭🇹'),
            '504' => array('name' => 'Honduras', 'flag' => '🇭🇳'),
            '852' => array('name' => 'Hong Kong', 'flag' => '🇭🇰'),
            '36' => array('name' => 'Hungary', 'flag' => '🇭🇺'),
            '354' => array('name' => 'Iceland', 'flag' => '🇮🇸'),
            '91' => array('name' => 'India', 'flag' => '🇮🇳'),
            '62' => array('name' => 'Indonesia', 'flag' => '🇮🇩'),
            '98' => array('name' => 'Iran', 'flag' => '🇮🇷'),
            '964' => array('name' => 'Iraq', 'flag' => '🇮🇶'),
            '353' => array('name' => 'Ireland', 'flag' => '🇮🇪'),
            '972' => array('name' => 'Israel', 'flag' => '🇮🇱'),
            '39' => array('name' => 'Italy', 'flag' => '🇮🇹'),
            '225' => array('name' => 'Ivory Coast', 'flag' => '🇨🇮'),
            '81' => array('name' => 'Japan', 'flag' => '🇯🇵'),
            '962' => array('name' => 'Jordan', 'flag' => '🇯🇴'),
            '7' => array('name' => 'Kazakhstan / Russia', 'flag' => '🇰🇿'),
            '254' => array('name' => 'Kenya', 'flag' => '🇰🇪'),
            '965' => array('name' => 'Kuwait', 'flag' => '🇰🇼'),
            '996' => array('name' => 'Kyrgyzstan', 'flag' => '🇰🇬'),
            '856' => array('name' => 'Laos', 'flag' => '🇱🇦'),
            '371' => array('name' => 'Latvia', 'flag' => '🇱🇻'),
            '961' => array('name' => 'Lebanon', 'flag' => '🇱🇧'),
            '266' => array('name' => 'Lesotho', 'flag' => '🇱🇸'),
            '231' => array('name' => 'Liberia', 'flag' => '🇱🇷'),
            '218' => array('name' => 'Libya', 'flag' => '🇱🇾'),
            '423' => array('name' => 'Liechtenstein', 'flag' => '🇱🇮'),
            '370' => array('name' => 'Lithuania', 'flag' => '🇱🇹'),
            '352' => array('name' => 'Luxembourg', 'flag' => '🇱🇺'),
            '389' => array('name' => 'Macedonia', 'flag' => '🇲🇰'),
            '261' => array('name' => 'Madagascar', 'flag' => '🇲🇬'),
            '265' => array('name' => 'Malawi', 'flag' => '🇲🇼'),
            '60' => array('name' => 'Malaysia', 'flag' => '🇲🇾'),
            '960' => array('name' => 'Maldives', 'flag' => '🇲🇻'),
            '223' => array('name' => 'Mali', 'flag' => '🇲🇱'),
            '356' => array('name' => 'Malta', 'flag' => '🇲🇹'),
            '222' => array('name' => 'Mauritania', 'flag' => '🇲🇷'),
            '230' => array('name' => 'Mauritius', 'flag' => '🇲🇺'),
            '52' => array('name' => 'Mexico', 'flag' => '🇲🇽'),
            '373' => array('name' => 'Moldova', 'flag' => '🇲🇩'),
            '377' => array('name' => 'Monaco', 'flag' => '🇲🇨'),
            '976' => array('name' => 'Mongolia', 'flag' => '🇲🇳'),
            '382' => array('name' => 'Montenegro', 'flag' => '🇲🇪'),
            '212' => array('name' => 'Morocco', 'flag' => '🇲🇦'),
            '258' => array('name' => 'Mozambique', 'flag' => '🇲🇿'),
            '95' => array('name' => 'Myanmar', 'flag' => '🇲🇲'),
            '264' => array('name' => 'Namibia', 'flag' => '🇳🇦'),
            '977' => array('name' => 'Nepal', 'flag' => '🇳🇵'),
            '31' => array('name' => 'Netherlands', 'flag' => '🇳🇱'),
            '64' => array('name' => 'New Zealand', 'flag' => '🇳🇿'),
            '505' => array('name' => 'Nicaragua', 'flag' => '🇳🇮'),
            '227' => array('name' => 'Niger', 'flag' => '🇳🇪'),
            '234' => array('name' => 'Nigeria', 'flag' => '🇳🇬'),
            '47' => array('name' => 'Norway', 'flag' => '🇳🇴'),
            '968' => array('name' => 'Oman', 'flag' => '🇴🇲'),
            '92' => array('name' => 'Pakistan', 'flag' => '🇵🇰'),
            '970' => array('name' => 'Palestine', 'flag' => '🇵🇸'),
            '507' => array('name' => 'Panama', 'flag' => '🇵🇦'),
            '595' => array('name' => 'Paraguay', 'flag' => '🇵🇾'),
            '51' => array('name' => 'Peru', 'flag' => '🇵🇪'),
            '63' => array('name' => 'Philippines', 'flag' => '🇵🇭'),
            '48' => array('name' => 'Poland', 'flag' => '🇵🇱'),
            '351' => array('name' => 'Portugal', 'flag' => '🇵🇹'),
            '974' => array('name' => 'Qatar', 'flag' => '🇶🇦'),
            '40' => array('name' => 'Romania', 'flag' => '🇷🇴'),
            '250' => array('name' => 'Rwanda', 'flag' => '🇷🇼'),
            '966' => array('name' => 'Saudi Arabia', 'flag' => '🇸🇦'),
            '221' => array('name' => 'Senegal', 'flag' => '🇸🇳'),
            '381' => array('name' => 'Serbia', 'flag' => '🇷🇸'),
            '65' => array('name' => 'Singapore', 'flag' => '🇸🇬'),
            '421' => array('name' => 'Slovakia', 'flag' => '🇸🇰'),
            '386' => array('name' => 'Slovenia', 'flag' => '🇸🇮'),
            '252' => array('name' => 'Somalia', 'flag' => '🇸🇴'),
            '27' => array('name' => 'South Africa', 'flag' => '🇿🇦'),
            '82' => array('name' => 'South Korea', 'flag' => '🇰🇷'),
            '211' => array('name' => 'South Sudan', 'flag' => '🇸🇸'),
            '34' => array('name' => 'Spain', 'flag' => '🇪🇸'),
            '94' => array('name' => 'Sri Lanka', 'flag' => '🇱🇰'),
            '249' => array('name' => 'Sudan', 'flag' => '🇸🇩'),
            '597' => array('name' => 'Suriname', 'flag' => '🇸🇷'),
            '268' => array('name' => 'Swaziland', 'flag' => '🇸🇿'),
            '46' => array('name' => 'Sweden', 'flag' => '🇸🇪'),
            '41' => array('name' => 'Switzerland', 'flag' => '🇨🇭'),
            '963' => array('name' => 'Syria', 'flag' => '🇸🇾'),
            '886' => array('name' => 'Taiwan', 'flag' => '🇹🇼'),
            '992' => array('name' => 'Tajikistan', 'flag' => '🇹🇯'),
            '255' => array('name' => 'Tanzania', 'flag' => '🇹🇿'),
            '66' => array('name' => 'Thailand', 'flag' => '🇹🇭'),
            '228' => array('name' => 'Togo', 'flag' => '🇹🇬'),
            '216' => array('name' => 'Tunisia', 'flag' => '🇹🇳'),
            '90' => array('name' => 'Turkey', 'flag' => '🇹🇷'),
            '993' => array('name' => 'Turkmenistan', 'flag' => '🇹🇲'),
            '256' => array('name' => 'Uganda', 'flag' => '🇺🇬'),
            '380' => array('name' => 'Ukraine', 'flag' => '🇺🇦'),
            '971' => array('name' => 'United Arab Emirates', 'flag' => '🇦🇪'),
            '44' => array('name' => 'United Kingdom', 'flag' => '🇬🇧'),
            '598' => array('name' => 'Uruguay', 'flag' => '🇺🇾'),
            '998' => array('name' => 'Uzbekistan', 'flag' => '🇺🇿'),
            '58' => array('name' => 'Venezuela', 'flag' => '🇻🇪'),
            '84' => array('name' => 'Vietnam', 'flag' => '🇻🇳'),
            '967' => array('name' => 'Yemen', 'flag' => '🇾🇪'),
            '260' => array('name' => 'Zambia', 'flag' => '🇿🇲'),
            '263' => array('name' => 'Zimbabwe', 'flag' => '🇿🇼'),
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
        
        <!-- Show Only On Selected Pages -->
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
                <?php esc_html_e( 'When enabled, button will appear only on pages you select below. Useful for showing the button on specific landing pages or product pages.', 'tap-chat' ); ?>
            </p>
            
            <div id="tap-chat-show-on-section" style="margin-left: 28px; <?php echo $enable_show_on === 'yes' ? '' : 'display:none;'; ?>">
                <?php $this->render_page_selector( 'show_on_pages', $show_on_pages ); ?>
            </div>
        </div>
        
        <!-- Hide On Selected Pages -->
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
                <?php esc_html_e( 'Hide the button on selected pages. Useful for hiding on checkout, thank you pages, or other pages where you don\'t want the button to appear.', 'tap-chat' ); ?>
            </p>
            
            <div id="tap-chat-hide-on-section" style="margin-left: 28px; <?php echo $enable_hide_on === 'yes' ? '' : 'display:none;'; ?>">
                <?php $this->render_page_selector( 'hide_on_pages', $hide_on_pages ); ?>
            </div>
        </div>
        
        <?php if ( $enable_show_on === 'yes' && $enable_hide_on === 'yes' ) : ?>
        <p style="margin-top: 15px; padding: 10px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px;">
            <strong><?php esc_html_e( 'Note:', 'tap-chat' ); ?></strong>
            <?php esc_html_e( ' Both options are enabled. The "Show only" list takes priority - button will only appear on those pages, excluding any in the "Hide" list.', 'tap-chat' ); ?>
        </p>
        <?php endif; ?>
        
        <?php
    }
    
    private function render_page_selector( $field_name, $selected_pages ) {
        // Get all pages and posts
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