<?php
namespace Tap_Chat;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Admin_Fields {

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
            '1' => array('name' => 'USA / Canada', 'flag' => '🇺🇸'),
            '238' => array('name' => 'Cape Verde', 'flag' => '🇨🇻'),
            '236' => array('name' => 'Central African Republic', 'flag' => '🇨🇫'),
            '235' => array('name' => 'Chad', 'flag' => '🇹🇩'),
            '56' => array('name' => 'Chile', 'flag' => '🇨🇱'),
            '86' => array('name' => 'China', 'flag' => '🇨🇳'),
            '57' => array('name' => 'Colombia', 'flag' => '🇨🇴'),
            '269' => array('name' => 'Comoros', 'flag' => '🇰🇲'),
            '242' => array('name' => 'Congo', 'flag' => '🇨🇬'),
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
            '679' => array('name' => 'Fiji', 'flag' => '🇫🇯'),
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
            '7' => array('name' => 'Russia / Kazakhstan', 'flag' => '🇷🇺'),
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
            '853' => array('name' => 'Macau', 'flag' => '🇲🇴'),
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
            '850' => array('name' => 'North Korea', 'flag' => '🇰🇵'),
            '47' => array('name' => 'Norway', 'flag' => '🇳🇴'),
            '968' => array('name' => 'Oman', 'flag' => '🇴🇲'),
            '92' => array('name' => 'Pakistan', 'flag' => '🇵🇰'),
            '970' => array('name' => 'Palestine', 'flag' => '🇵🇸'),
            '507' => array('name' => 'Panama', 'flag' => '🇵🇦'),
            '675' => array('name' => 'Papua New Guinea', 'flag' => '🇵🇬'),
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
            '248' => array('name' => 'Seychelles', 'flag' => '🇸🇨'),
            '232' => array('name' => 'Sierra Leone', 'flag' => '🇸🇱'),
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

    public function field_enable_floating() {
        $val = $this->get( 'enable_floating', 'yes' );
        ?>
        <input type="hidden" name="tap_chat_settings[enable_floating]" value="no" />
        <label>
            <input type="checkbox" name="tap_chat_settings[enable_floating]" value="yes" <?php checked( $val, 'yes' ); ?> />
            <?php esc_html_e( 'Show floating button site-wide', 'tap-chat' ); ?>
        </label>
        <p class="description">
            <?php esc_html_e( 'Enable or disable the floating WhatsApp button on your website', 'tap-chat' ); ?>
        </p>
        <?php
    }

    public function field_phone() {
        $country_code = $this->get( 'country_code', '49' );
        $phone = $this->get( 'phone', '' );
        $link_type = $this->get( 'link_type', 'phone' );
        $custom_url = $this->get( 'custom_url', '' );
        $countries = $this->get_countries();
        ?>
        <div class="tap-chat-link-type-toggle">
            <label>
                <input type="radio" name="tap_chat_settings[link_type]" value="phone" class="tap-chat-link-type-radio" <?php checked( $link_type, 'phone' ); ?> />
                <?php esc_html_e( 'WhatsApp phone number', 'tap-chat' ); ?>
            </label>
            <label>
                <input type="radio" name="tap_chat_settings[link_type]" value="custom" class="tap-chat-link-type-radio" <?php checked( $link_type, 'custom' ); ?> />
                <?php esc_html_e( 'Custom link (Telegram, Messenger, etc.)', 'tap-chat' ); ?>
            </label>
        </div>

        <div class="tap-chat-link-panel" data-link-panel="phone"<?php echo $link_type === 'custom' ? ' style="display:none;"' : ''; ?>>
        <div class="tap-chat-phone-wrapper">
            <div class="tap-chat-country-wrapper">
                <input type="hidden" name="tap_chat_settings[country_code]" value="<?php echo esc_attr( $country_code ); ?>" />
                
                <div class="tap-chat-selected-country">
                    <?php 
                    if ( isset( $countries[$country_code] ) ) {
                        $selected = $countries[$country_code];
                        echo esc_html( $selected['flag'] . ' ' . $selected['name'] . ' (+' . $country_code . ')' );
                    }
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
        </div>

        <div class="tap-chat-link-panel" data-link-panel="custom"<?php echo $link_type === 'phone' ? ' style="display:none;"' : ''; ?>>
            <input type="url"
                   name="tap_chat_settings[custom_url]"
                   value="<?php echo esc_attr( $custom_url ); ?>"
                   placeholder="https://t.me/username"
                   class="regular-text tap-chat-custom-url" />
            <p class="description">
                <?php esc_html_e( 'Paste the full link including https:// — no country code needed. Works with Telegram, Messenger, a contact page, or any URL. The pre-filled message below is not sent with a custom link (only WhatsApp supports it), so you may want to upload a matching Custom Icon.', 'tap-chat' ); ?>
            </p>
        </div>
        <?php
    }

    public function field_message() {
        printf(
            '<textarea name="tap_chat_settings[message]" rows="3" class="large-text" placeholder="%s">%s</textarea>',
            esc_attr__( 'Hello! I have a question…', 'tap-chat' ),
            esc_textarea( $this->get( 'message', '' ) )
        );
        echo '<p class="description">' . esc_html__( 'Pre-filled message that appears when users click the button (WhatsApp only).', 'tap-chat' ) . ' ' . wp_kses_post( __( 'Variables: <code>{TITLE}</code> = site title, <code>{TAGLINE}</code> = tagline, <code>{URL}</code> = site URL.', 'tap-chat' ) ) . '</p>';
    }

    public function field_label() {
        $label = $this->get( 'label', __( 'Chat with us', 'tap-chat' ) );
        if ( empty( $label ) ) {
            $label = '';
        }
        printf(
            '<input type="text" class="regular-text" name="tap_chat_settings[label]" value="%s" placeholder="%s" />',
            esc_attr( $label ),
            esc_attr__( 'Chat with us', 'tap-chat' )
        );
        echo '<p class="description">' . esc_html__( 'Text displayed next to the WhatsApp icon. Leave empty to use default.', 'tap-chat' ) . '</p>';
    }

    public function field_custom_icon() {
        $custom_icon = $this->get( 'custom_icon', '' );
        ?>
        <div class="tap-chat-icon-upload">
            <div class="tap-chat-icon-preview<?php echo !empty($custom_icon) ? ' has-image' : ''; ?>">
                <img id="tap-chat-icon-preview" src="<?php echo esc_url($custom_icon); ?>" alt="Custom Icon" />
            </div>
            <div>
                <input type="url" 
                       id="tap-chat-icon-url"
                       name="tap_chat_settings[custom_icon]" 
                       value="<?php echo esc_url($custom_icon); ?>" 
                       class="regular-text"
                       placeholder="https://example.com/icon.png" />
                <div class="tap-chat-icon-buttons">
                    <button type="button" id="tap-chat-upload-icon" class="button">
                        <?php esc_html_e('Choose Icon', 'tap-chat'); ?>
                    </button>
                    <button type="button" id="tap-chat-remove-icon" class="button">
                        <?php esc_html_e('Use Default', 'tap-chat'); ?>
                    </button>
                </div>
            </div>
        </div>
        <p class="description">
            <?php esc_html_e('Upload a custom icon or leave empty to use the default WhatsApp icon. Recommended size: 64x64px (PNG/SVG)', 'tap-chat'); ?>
        </p>
        <?php
    }

    public function field_position() {
        $val = $this->get( 'position', 'right' );
        ?>
        <select name="tap_chat_settings[position]">
            <option value="right" <?php selected( $val, 'right' ); ?>><?php esc_html_e( 'Bottom Right', 'tap-chat' ); ?></option>
            <option value="left" <?php selected( $val, 'left' ); ?>><?php esc_html_e( 'Bottom Left', 'tap-chat' ); ?></option>
        </select>
        <p class="description">
            <?php esc_html_e( 'Where to display the floating button on your site', 'tap-chat' ); ?>
        </p>
        <?php
    }

    public function field_size() {
        printf(
            '<input type="number" min="25" max="96" name="tap_chat_settings[size]" value="%d" class="small-text" />',
            absint( $this->get( 'size', 40 ) )
        );
        echo ' <span class="description">' . esc_html__( 'Icon size for desktop screens (pixels)', 'tap-chat' ) . '</span>';
    }

    public function field_mobile_size() {
        printf(
            '<input type="number" min="25" max="96" name="tap_chat_settings[mobile_size]" value="%d" class="small-text" />',
            absint( $this->get( 'mobile_size', 40 ) )
        );
        echo ' <span class="description">' . esc_html__( 'Icon size for mobile screens ≤480px (pixels)', 'tap-chat' ) . '</span>';
    }

    public function field_color() {
        printf(
            '<input type="text" class="tap-chat-color-picker" name="tap_chat_settings[color]" value="%s" data-default-color="#25D366" />',
            esc_attr( $this->get( 'color', '#25D366' ) )
        );
        echo '<p class="description">' . esc_html__( 'Background color of the floating button', 'tap-chat' ) . '</p>';
    }

    public function field_button_animation() {
        $val = $this->get( 'button_animation', 'none' );
        $options = array(
            'none'      => __( 'None', 'tap-chat' ),
            'fade'      => __( 'Fade in (on load)', 'tap-chat' ),
            'slide'     => __( 'Slide in (on load)', 'tap-chat' ),
            'bounce'    => __( 'Bounce (looping)', 'tap-chat' ),
            'ring'      => __( 'Ring / shake (looping)', 'tap-chat' ),
            'attention' => __( 'New message – badge + pulse', 'tap-chat' ),
        );
        echo '<select name="tap_chat_settings[button_animation]">';
        foreach ( $options as $key => $label ) {
            printf( '<option value="%s"%s>%s</option>', esc_attr( $key ), selected( $val, $key, false ), esc_html( $label ) );
        }
        echo '</select>';
        echo '<p class="description">' . esc_html__( 'Animate the floating button to catch attention. "New message" adds an unread badge and a pulsing ring. Motion is automatically disabled for visitors who prefer reduced motion.', 'tap-chat' ) . '</p>';
    }

    public function field_hide_label_mobile() {
        $val = $this->get( 'hide_label_mobile', 'yes' );
        ?>
        <input type="hidden" name="tap_chat_settings[hide_label_mobile]" value="no" />
        <label>
            <input type="checkbox" name="tap_chat_settings[hide_label_mobile]" value="yes" <?php checked( $val, 'yes' ); ?> />
            <?php esc_html_e( 'Hide label on mobile (≤480px) - show icon only', 'tap-chat' ); ?>
        </label>
        <?php
    }

    public function field_hide_label_desktop() {
        $val = $this->get( 'hide_label_desktop', 'no' );
        ?>
        <input type="hidden" name="tap_chat_settings[hide_label_desktop]" value="no" />
        <label>
            <input type="checkbox" name="tap_chat_settings[hide_label_desktop]" value="yes" <?php checked( $val, 'yes' ); ?> />
            <?php esc_html_e( 'Hide label on desktop (>480px) - show icon only', 'tap-chat' ); ?>
        </label>
        <?php
    }

    public function field_append_page_context() {
        $val = $this->get( 'append_page_context', 'no' );
        ?>
        <input type="hidden" name="tap_chat_settings[append_page_context]" value="no" />
        <label>
            <input type="checkbox" name="tap_chat_settings[append_page_context]" value="yes" <?php checked( $val, 'yes' ); ?> />
            <?php esc_html_e( 'Automatically append page title and URL to the message', 'tap-chat' ); ?>
        </label>
        <p class="description">
            <?php esc_html_e( 'This helps you know which page the visitor is contacting you from', 'tap-chat' ); ?>
        </p>
        <?php
    }

    public function field_visibility_controls() {
        $enable_show_on = $this->get( 'enable_show_on', 'no' );
        $enable_hide_on = $this->get( 'enable_hide_on', 'no' );
        $show_on_pages = $this->get( 'show_on_pages', array() );
        $hide_on_pages = $this->get( 'hide_on_pages', array() );
        ?>
        
        <div class="tap-chat-visibility-box tap-chat-show-box">
            <input type="hidden" name="tap_chat_settings[enable_show_on]" value="no" />
            <label class="tap-chat-visibility-label">
                <input type="checkbox" 
                       name="tap_chat_settings[enable_show_on]" 
                       value="yes" 
                       id="enable_show_on"
                       <?php checked( $enable_show_on, 'yes' ); ?> />
                <span class="tap-chat-label-text">
                    <?php esc_html_e( 'Show button ONLY on specific pages', 'tap-chat' ); ?>
                </span>
            </label>
            <p class="description">
                <?php esc_html_e( 'When enabled, button will appear only on pages you select below', 'tap-chat' ); ?>
            </p>
            
            <div id="tap-chat-show-on-section" class="tap-chat-page-section" style="display: <?php echo $enable_show_on === 'yes' ? 'block' : 'none'; ?>;">
                <?php $this->render_page_selector( 'show_on_pages', $show_on_pages ); ?>
            </div>
        </div>
        
        <div class="tap-chat-visibility-box tap-chat-hide-box">
            <input type="hidden" name="tap_chat_settings[enable_hide_on]" value="no" />
            <label class="tap-chat-visibility-label">
                <input type="checkbox" 
                       name="tap_chat_settings[enable_hide_on]" 
                       value="yes"
                       id="enable_hide_on" 
                       <?php checked( $enable_hide_on, 'yes' ); ?> />
                <span class="tap-chat-label-text">
                    <?php esc_html_e( 'Hide button on specific pages', 'tap-chat' ); ?>
                </span>
            </label>
            <p class="description">
                <?php esc_html_e( 'Hide the button on selected pages (e.g., checkout, cart)', 'tap-chat' ); ?>
            </p>
            
            <div id="tap-chat-hide-on-section" class="tap-chat-page-section" style="display: <?php echo $enable_hide_on === 'yes' ? 'block' : 'none'; ?>;">
                <?php $this->render_page_selector( 'hide_on_pages', $hide_on_pages ); ?>
            </div>
        </div>
        <?php
    }

    public function field_working_hours_controls() {
        $enable = $this->get( 'enable_working_hours', 'no' );
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
        
        <div class="tap-chat-hours-box">
            <input type="hidden" name="tap_chat_settings[enable_working_hours]" value="no" />
            <label class="tap-chat-hours-label">
                <input type="checkbox" 
                       name="tap_chat_settings[enable_working_hours]" 
                       value="yes" 
                       id="enable_working_hours"
                       <?php checked( $enable, 'yes' ); ?> />
                <span class="tap-chat-label-text">
                    <?php esc_html_e( 'Enable Working Hours', 'tap-chat' ); ?>
                </span>
            </label>
            <p class="description">
                <?php esc_html_e( 'Show the button only during your business hours', 'tap-chat' ); ?>
            </p>
            
            <div id="tap-chat-working-hours-section" class="tap-chat-hours-section" style="display: <?php echo $enable === 'yes' ? 'block' : 'none'; ?>;">
                
                <div class="tap-chat-timezone-field">
                    <label class="tap-chat-field-label">
                        <?php esc_html_e( 'Business Timezone', 'tap-chat' ); ?>
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
                        <?php esc_html_e( 'Select your business timezone for accurate scheduling', 'tap-chat' ); ?>
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
                
                <div class="tap-chat-offline-field">
                    <label class="tap-chat-field-label">
                        <?php esc_html_e( 'Offline Message (Optional)', 'tap-chat' ); ?>
                    </label>
                    <textarea name="tap_chat_settings[offline_message]" 
                              rows="3" 
                              class="large-text" 
                              placeholder="<?php esc_attr_e( 'We are currently offline. Our business hours are Monday-Friday, 9 AM - 5 PM.', 'tap-chat' ); ?>"><?php echo esc_textarea( $offline_message ); ?></textarea>
                    <p class="description">
                        <?php esc_html_e( 'This message replaces the button label when outside working hours. Leave empty to hide the button completely.', 'tap-chat' ); ?>
                    </p>
                </div>
                
            </div>
        </div>
        <?php
    }

    public function field_welcome_bubble_controls() {
    $enable = $this->get('enable_welcome_bubble', 'no');
    $bubble_style = $this->get('bubble_style', 'modern');
    $bubble_position = $this->get('bubble_position', 'top');
    $message = $this->get('welcome_bubble_message', __('Need help? Let\'s chat! 💬', 'tap-chat'));
    $name = $this->get('welcome_bubble_name', __('Support Team', 'tap-chat'));
    $avatar = $this->get('welcome_bubble_avatar', '');
    ?>
    
    <div class="tap-chat-bubble-box">
        
        <input type="hidden" name="tap_chat_settings[enable_welcome_bubble]" value="no" />
        <label class="tap-chat-bubble-label">
            <input type="checkbox" 
                   name="tap_chat_settings[enable_welcome_bubble]" 
                   value="yes" 
                   id="enable_welcome_bubble"
                   <?php checked($enable, 'yes'); ?> />
            <span class="tap-chat-label-text">
                <?php esc_html_e('Enable Welcome Bubble', 'tap-chat'); ?>
            </span>
        </label>
        <p class="description">
            <?php esc_html_e('Show a friendly message bubble to encourage visitors to start a conversation', 'tap-chat'); ?>
        </p>
        
        <div id="tap-chat-welcome-bubble-section" class="tap-chat-bubble-section" style="display: <?php echo $enable === 'yes' ? 'block' : 'none'; ?>;">
            
            <div class="tap-chat-bubble-style-selector">
                <label class="tap-chat-field-label">
                    <?php esc_html_e('Bubble Style', 'tap-chat'); ?>
                </label>
                <div class="tap-chat-style-options">
                    <div class="tap-chat-bubble-style-option <?php echo $bubble_style === 'modern' ? 'selected' : ''; ?>">
                        <input type="radio" 
                               name="tap_chat_settings[bubble_style]" 
                               value="modern" 
                               id="bubble_style_modern"
                               <?php checked($bubble_style, 'modern'); ?> />
                        <label for="bubble_style_modern" class="tap-chat-bubble-style-label">
                            <?php esc_html_e('Modern', 'tap-chat'); ?>
                        </label>
                        <p class="tap-chat-bubble-style-desc">
                            <?php esc_html_e('Rich bubble with avatar, name, and online status', 'tap-chat'); ?>
                        </p>
                    </div>
                    
                    <div class="tap-chat-bubble-style-option <?php echo $bubble_style === 'simple' ? 'selected' : ''; ?>">
                        <input type="radio" 
                               name="tap_chat_settings[bubble_style]" 
                               value="simple" 
                               id="bubble_style_simple"
                               <?php checked($bubble_style, 'simple'); ?> />
                        <label for="bubble_style_simple" class="tap-chat-bubble-style-label">
                            <?php esc_html_e('Simple', 'tap-chat'); ?>
                        </label>
                        <p class="tap-chat-bubble-style-desc">
                            <?php esc_html_e('Clean bubble with message only', 'tap-chat'); ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="tap-chat-bubble-field">
                <label class="tap-chat-field-label">
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
            
            <div id="bubble-position-field" class="tap-chat-bubble-field" style="display: <?php echo $bubble_style === 'simple' ? 'block' : 'none'; ?>;">
                <label class="tap-chat-field-label">
                    <?php esc_html_e('Bubble Position', 'tap-chat'); ?>
                </label>
                <select name="tap_chat_settings[bubble_position]" class="regular-text">
                    <option value="top" <?php selected($bubble_position, 'top'); ?>>
                        <?php esc_html_e('Top (Above button)', 'tap-chat'); ?>
                    </option>
                    <option value="side" <?php selected($bubble_position, 'side'); ?>>
                        <?php esc_html_e('Side (Next to button)', 'tap-chat'); ?>
                    </option>
                </select>
                <p class="description">
                    <?php esc_html_e('Choose where the bubble appears relative to the button (Simple style only)', 'tap-chat'); ?>
                </p>
            </div>
            
            <div id="bubble-name-field" class="tap-chat-bubble-field" style="display: <?php echo $bubble_style === 'modern' ? 'block' : 'none'; ?>;">
                <label class="tap-chat-field-label">
                    <?php esc_html_e('Agent/Team Name', 'tap-chat'); ?>
                </label>
                <input type="text" 
                       name="tap_chat_settings[welcome_bubble_name]" 
                       value="<?php echo esc_attr($name); ?>" 
                       class="regular-text"
                       placeholder="<?php esc_attr_e('Support Team', 'tap-chat'); ?>" />
                <p class="description">
                    <?php esc_html_e('Display name for the agent or team (Modern style only)', 'tap-chat'); ?>
                </p>
            </div>
            
            <div id="bubble-avatar-field" class="tap-chat-bubble-field" style="display: <?php echo $bubble_style === 'modern' ? 'block' : 'none'; ?>;">
                <label class="tap-chat-field-label">
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
                               placeholder="https://example.com/avatar.jpg" />
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
                    <?php esc_html_e('Upload an avatar image or enter URL. Leave empty to use default WhatsApp icon (Modern style only).', 'tap-chat'); ?>
                </p>
            </div>
            
        </div>
    </div>
    
    <?php
}

    public function field_smart_triggers() {
    $scroll_enabled = $this->get('trigger_scroll_enabled', 'no');
    $scroll_depth = $this->get('trigger_scroll_depth', 50);
    $exit_enabled = $this->get('trigger_exit_enabled', 'no');
    $time_enabled = $this->get('trigger_time_enabled', 'yes');
    $time_delay = $this->get('trigger_time_delay', 3);
    $idle_enabled = $this->get('trigger_idle_enabled', 'no');
    $idle_time = $this->get('trigger_idle_time', 60);
    ?>
    
    <div class="tap-chat-triggers-box">
        <p class="description" style="margin-bottom: 15px;">
            <?php esc_html_e('Control when the welcome bubble appears. At least one trigger must be enabled.', 'tap-chat'); ?>
        </p>
        
        <div class="tap-chat-trigger-item">
            <input type="hidden" name="tap_chat_settings[trigger_time_enabled]" value="no" />
            <label class="tap-chat-trigger-label">
                <input type="checkbox" 
                       name="tap_chat_settings[trigger_time_enabled]" 
                       value="yes" 
                       class="tap-chat-trigger-checkbox"
                       data-target="time-options"
                       <?php checked($time_enabled, 'yes'); ?> />
                <span class="tap-chat-trigger-title">
                    <?php esc_html_e('⏱️ Time on Page (Default)', 'tap-chat'); ?>
                </span>
            </label>
            <p class="description">
                <?php esc_html_e('Show bubble after visitor spends specific time on page (recommended)', 'tap-chat'); ?>
            </p>
            <div id="time-options" class="tap-chat-trigger-options" style="display: <?php echo $time_enabled === 'yes' ? 'block' : 'none'; ?>;">
                <label>
                    <?php esc_html_e('Delay (seconds)', 'tap-chat'); ?>
                    <input type="number" 
                           name="tap_chat_settings[trigger_time_delay]" 
                           value="<?php echo esc_attr($time_delay); ?>" 
                           min="0" 
                           max="300" 
                           step="1" 
                           class="small-text" />
                    <span class="description"><?php esc_html_e('Show bubble after this many seconds (0-300). 0 = show immediately.', 'tap-chat'); ?></span>
                </label>
            </div>
        </div>
        
        <div class="tap-chat-trigger-item">
            <input type="hidden" name="tap_chat_settings[trigger_scroll_enabled]" value="no" />
            <label class="tap-chat-trigger-label">
                <input type="checkbox" 
                       name="tap_chat_settings[trigger_scroll_enabled]" 
                       value="yes" 
                       class="tap-chat-trigger-checkbox"
                       data-target="scroll-options"
                       <?php checked($scroll_enabled, 'yes'); ?> />
                <span class="tap-chat-trigger-title">
                    <?php esc_html_e('📜 Scroll Depth', 'tap-chat'); ?>
                </span>
            </label>
            <p class="description">
                <?php esc_html_e('Show bubble when visitor scrolls to a specific percentage of the page', 'tap-chat'); ?>
            </p>
            <div id="scroll-options" class="tap-chat-trigger-options" style="display: <?php echo $scroll_enabled === 'yes' ? 'block' : 'none'; ?>;">
                <label>
                    <?php esc_html_e('Scroll Depth (%)', 'tap-chat'); ?>
                    <input type="number" 
                           name="tap_chat_settings[trigger_scroll_depth]" 
                           value="<?php echo esc_attr($scroll_depth); ?>" 
                           min="10" 
                           max="100" 
                           step="5" 
                           class="small-text" />
                    <span class="description"><?php esc_html_e('Show bubble after scrolling this percentage (10-100%)', 'tap-chat'); ?></span>
                </label>
            </div>
        </div>
        
        <div class="tap-chat-trigger-item">
            <input type="hidden" name="tap_chat_settings[trigger_exit_enabled]" value="no" />
            <label class="tap-chat-trigger-label">
                <input type="checkbox" 
                       name="tap_chat_settings[trigger_exit_enabled]" 
                       value="yes" 
                       class="tap-chat-trigger-checkbox"
                       <?php checked($exit_enabled, 'yes'); ?> />
                <span class="tap-chat-trigger-title">
                    <?php esc_html_e('🚪 Exit Intent', 'tap-chat'); ?>
                </span>
            </label>
            <p class="description">
                <?php esc_html_e('Show bubble when visitor moves mouse towards closing the page/tab', 'tap-chat'); ?>
            </p>
        </div>
        
        <div class="tap-chat-trigger-item">
            <input type="hidden" name="tap_chat_settings[trigger_idle_enabled]" value="no" />
            <label class="tap-chat-trigger-label">
                <input type="checkbox" 
                       name="tap_chat_settings[trigger_idle_enabled]" 
                       value="yes" 
                       class="tap-chat-trigger-checkbox"
                       data-target="idle-options"
                       <?php checked($idle_enabled, 'yes'); ?> />
                <span class="tap-chat-trigger-title">
                    <?php esc_html_e('💤 Idle Detection', 'tap-chat'); ?>
                </span>
            </label>
            <p class="description">
                <?php esc_html_e('Show bubble when visitor is inactive (no mouse/keyboard activity)', 'tap-chat'); ?>
            </p>
            <div id="idle-options" class="tap-chat-trigger-options" style="display: <?php echo $idle_enabled === 'yes' ? 'block' : 'none'; ?>;">
                <label>
                    <?php esc_html_e('Idle Time (seconds)', 'tap-chat'); ?>
                    <input type="number" 
                           name="tap_chat_settings[trigger_idle_time]" 
                           value="<?php echo esc_attr($idle_time); ?>" 
                           min="10" 
                           max="300" 
                           step="10" 
                           class="small-text" />
                    <span class="description"><?php esc_html_e('Show bubble after this many seconds of inactivity (10-300)', 'tap-chat'); ?></span>
                </label>
            </div>
        </div>
        
        <div class="tap-chat-trigger-note">
            <strong><?php esc_html_e('💡 Tip:', 'tap-chat'); ?></strong>
            <?php esc_html_e('When multiple triggers are enabled, the bubble shows on whichever condition is met first. Time on Page is enabled by default and recommended for most websites.', 'tap-chat'); ?>
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
            
            <div class="tap-chat-selected-count">0 <?php esc_html_e( 'selected', 'tap-chat' ); ?></div>
            
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
}