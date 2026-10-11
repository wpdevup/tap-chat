<?php
/**
 * Settings views. Every view is printed inside one form, because the
 * sanitize callback rebuilds the whole option from the submitted fields.
 *
 * @package Tap_Chat
 */

namespace Tap_Chat;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Renders the settings views.
 */
class Admin_Fields {

    /**
     * Saved settings, read once per request.
     *
     * @var array|null
     */
    private $options = null;

    private function get( $key, $default = '' ) {
        if ( null === $this->options ) {
            $opts          = get_option( Admin_UI::OPTION, array() );
            $this->options = is_array( $opts ) ? $opts : array();
        }
        return isset( $this->options[ $key ] ) ? $this->options[ $key ] : $default;
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

    /* ---------------------------------------------------------------------
     * General
     * ------------------------------------------------------------------- */

    public function render_general() {
        $link_type = $this->get( 'link_type', 'phone' );

        Admin_UI::page_header(
            __( 'General', 'tap-chat' ),
            __( 'Where the chat button sends visitors, and how it looks on your site.', 'tap-chat' )
        );

        echo '<div class="tap-chat-card"><div class="tap-chat-toggle-list">';
        Admin_UI::toggle_row(
            'enable_floating',
            'power',
            __( 'Show floating button site-wide', 'tap-chat' ),
            __( 'Enable or disable the floating WhatsApp button on your website', 'tap-chat' ),
            'yes' === $this->get( 'enable_floating', 'yes' )
        );
        echo '</div></div>';

        Admin_UI::section_title( __( 'Destination', 'tap-chat' ), __( 'Used in single-button mode. Agents have their own numbers.', 'tap-chat' ) );
        echo '<div class="tap-chat-card"><div class="tap-chat-choice-grid">';
        Admin_UI::choice( 'link_type', 'phone', 'chat', __( 'WhatsApp phone number', 'tap-chat' ), __( 'Opens a WhatsApp chat with your number and a pre-filled message.', 'tap-chat' ), $link_type );
        Admin_UI::choice( 'link_type', 'custom', 'link', __( 'Custom link (Telegram, Messenger, etc.)', 'tap-chat' ), __( 'Any messenger, contact page or URL.', 'tap-chat' ), $link_type );
        echo '</div><hr class="tap-chat-divider">';

        echo '<div' . Admin_UI::show_if( 'link_type', 'phone', $link_type ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built with esc_attr().
        echo '<div class="tap-chat-grid-2 tap-chat-grid-2--phone">';
        Admin_UI::field_open( __( 'Country', 'tap-chat' ), 'tap-chat-country-code' );
        $this->render_country_select( Admin_UI::name( 'country_code' ), $this->get( 'country_code', '49' ), 'tap-chat-country-code', false );
        Admin_UI::field_close();
        Admin_UI::field_open( __( 'Phone number', 'tap-chat' ), 'tap-chat-phone' );
        printf(
            '<input type="text" inputmode="tel" class="tap-chat-input" id="tap-chat-phone" name="%1$s" value="%2$s" placeholder="123456789" dir="ltr">',
            esc_attr( Admin_UI::name( 'phone' ) ),
            esc_attr( $this->get( 'phone', '' ) )
        );
        Admin_UI::field_close();
        echo '</div>';
        printf( '<p class="tap-chat-field__help tap-chat-mt-sm">%s</p>', esc_html__( 'Select your country and enter your phone number (without country code or leading zero)', 'tap-chat' ) );

        echo '<div class="tap-chat-mt">';
        Admin_UI::field_open( __( 'Default Message', 'tap-chat' ), 'tap-chat-message' );
        printf(
            '<textarea class="tap-chat-input tap-chat-textarea" id="tap-chat-message" name="%1$s" rows="3" placeholder="%2$s">%3$s</textarea>',
            esc_attr( Admin_UI::name( 'message' ) ),
            esc_attr__( 'Hello! I have a question…', 'tap-chat' ),
            esc_textarea( $this->get( 'message', '' ) )
        );
        echo '<p class="tap-chat-field__help">' . esc_html__( 'Pre-filled message that appears when users click the button (WhatsApp only).', 'tap-chat' ) . ' ' . wp_kses_post( __( 'Variables: <code>{TITLE}</code> = site title, <code>{TAGLINE}</code> = tagline, <code>{URL}</code> = site URL.', 'tap-chat' ) ) . '</p>';
        echo '</div></div></div>';

        echo '<div' . Admin_UI::show_if( 'link_type', 'custom', $link_type ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built with esc_attr().
        Admin_UI::field_open( __( 'Custom link', 'tap-chat' ), 'tap-chat-custom-url' );
        printf(
            '<input type="url" class="tap-chat-input" id="tap-chat-custom-url" name="%1$s" value="%2$s" placeholder="https://t.me/username" dir="ltr">',
            esc_attr( Admin_UI::name( 'custom_url' ) ),
            esc_attr( $this->get( 'custom_url', '' ) )
        );
        Admin_UI::field_close( __( 'Paste the full link including https:// — no country code needed. Works with Telegram, Messenger, a contact page, or any URL. The pre-filled message below is not sent with a custom link (only WhatsApp supports it), so you may want to upload a matching Custom Icon.', 'tap-chat' ) );
        echo '</div></div>';

        $this->render_appearance();
    }

    private function render_appearance() {
        $position  = $this->get( 'position', 'right' );
        $animation = $this->get( 'button_animation', 'none' );
        $label     = $this->get( 'label', __( 'Chat with us', 'tap-chat' ) );

        Admin_UI::section_title( __( 'Appearance', 'tap-chat' ) );
        echo '<div class="tap-chat-card"><div class="tap-chat-grid-2">';

        Admin_UI::field_open( __( 'Button Label', 'tap-chat' ), 'tap-chat-label' );
        printf(
            '<input type="text" class="tap-chat-input" id="tap-chat-label" name="%1$s" value="%2$s" placeholder="%3$s">',
            esc_attr( Admin_UI::name( 'label' ) ),
            esc_attr( (string) $label ),
            esc_attr__( 'Chat with us', 'tap-chat' )
        );
        Admin_UI::field_close( __( 'Text displayed next to the WhatsApp icon. Leave empty to use default.', 'tap-chat' ) );

        Admin_UI::field_open( __( 'Button Position', 'tap-chat' ), 'tap-chat-position' );
        printf( '<select class="tap-chat-select" id="tap-chat-position" name="%s">', esc_attr( Admin_UI::name( 'position' ) ) );
        printf( '<option value="right"%1$s>%2$s</option>', selected( $position, 'right', false ), esc_html__( 'Bottom Right', 'tap-chat' ) );
        printf( '<option value="left"%1$s>%2$s</option>', selected( $position, 'left', false ), esc_html__( 'Bottom Left', 'tap-chat' ) );
        echo '</select>';
        Admin_UI::field_close( __( 'Where to display the floating button on your site', 'tap-chat' ) );

        Admin_UI::field_open( __( 'Button Color', 'tap-chat' ), 'tap-chat-color' );
        printf(
            '<input type="text" class="tap-chat-color-picker" id="tap-chat-color" name="%1$s" value="%2$s" data-default-color="#25D366">',
            esc_attr( Admin_UI::name( 'color' ) ),
            esc_attr( $this->get( 'color', '#25D366' ) )
        );
        Admin_UI::field_close( __( 'Background color of the floating button', 'tap-chat' ) );

        Admin_UI::field_open( __( 'Button Animation', 'tap-chat' ), 'tap-chat-button-animation' );
        $options = array(
            'none'      => __( 'None', 'tap-chat' ),
            'fade'      => __( 'Fade in (on load)', 'tap-chat' ),
            'slide'     => __( 'Slide in (on load)', 'tap-chat' ),
            'bounce'    => __( 'Bounce (looping)', 'tap-chat' ),
            'ring'      => __( 'Ring / shake (looping)', 'tap-chat' ),
            'attention' => __( 'New message – badge + pulse', 'tap-chat' ),
        );
        printf( '<select class="tap-chat-select" id="tap-chat-button-animation" name="%s">', esc_attr( Admin_UI::name( 'button_animation' ) ) );
        foreach ( $options as $key => $text ) {
            printf( '<option value="%1$s"%2$s>%3$s</option>', esc_attr( $key ), selected( $animation, $key, false ), esc_html( $text ) );
        }
        echo '</select>';
        Admin_UI::field_close( __( 'Animate the floating button to catch attention. "New message" adds an unread badge and a pulsing ring. Motion is automatically disabled for visitors who prefer reduced motion.', 'tap-chat' ) );

        echo '</div>';

        Admin_UI::card_subtitle( __( 'Custom Icon', 'tap-chat' ), __( 'Upload a custom icon or leave empty to use the default WhatsApp icon. Recommended size: 64x64px (PNG/SVG)', 'tap-chat' ) );
        Admin_UI::image_picker( 'custom_icon', (string) $this->get( 'custom_icon', '' ), 'https://example.com/icon.png', __( 'Choose Icon', 'tap-chat' ), __( 'Use Default', 'tap-chat' ), 'square' );

        Admin_UI::card_subtitle( __( 'Size and label', 'tap-chat' ), __( 'Screens up to 480px wide use the mobile settings.', 'tap-chat' ) );
        echo '<div class="tap-chat-grid-2">';
        $this->render_size_field( 'size', __( 'Desktop Size (px)', 'tap-chat' ), __( 'Icon size for desktop screens (pixels)', 'tap-chat' ) );
        $this->render_size_field( 'mobile_size', __( 'Mobile Size (px)', 'tap-chat' ), __( 'Icon size for mobile screens ≤480px (pixels)', 'tap-chat' ) );
        echo '</div><div class="tap-chat-toggle-list tap-chat-mt">';
        Admin_UI::toggle_row( 'hide_label_desktop', 'monitor', __( 'Icon only on desktop', 'tap-chat' ), __( 'Hide label on desktop (>480px) - show icon only', 'tap-chat' ), 'yes' === $this->get( 'hide_label_desktop', 'yes' ) );
        Admin_UI::toggle_row( 'hide_label_mobile', 'phone', __( 'Icon only on mobile', 'tap-chat' ), __( 'Hide label on mobile (≤480px) - show icon only', 'tap-chat' ), 'yes' === $this->get( 'hide_label_mobile', 'yes' ) );
        echo '</div></div>';
    }

    private function render_size_field( $key, $label, $help ) {
        $value = absint( $this->get( $key, 40 ) );
        $value = $value ? $value : 40;
        Admin_UI::field_open( $label, Admin_UI::id( $key ) );
        printf(
            '<span class="tap-chat-range"><input type="range" class="tap-chat-range__input" id="%1$s" name="%2$s" min="25" max="96" step="1" value="%3$d"><output class="tap-chat-range__value" for="%1$s" dir="ltr">%3$d px</output></span>',
            esc_attr( Admin_UI::id( $key ) ),
            esc_attr( Admin_UI::name( $key ) ),
            (int) $value
        );
        Admin_UI::field_close( $help );
    }

    /**
     * Live preview of the floating button, the agent picker and the welcome bubble.
     * Printed once beside the views that use it; admin.js keeps it in sync with the form.
     */
    public function render_preview() {
        $color        = sanitize_hex_color( $this->get( 'color', '#25D366' ) );
        $label        = (string) $this->get( 'label', '' );
        $custom_icon  = (string) $this->get( 'custom_icon', '' );
        $position     = 'left' === $this->get( 'position', 'right' ) ? 'left' : 'right';
        $size         = absint( $this->get( 'size', 40 ) );
        $animation    = (string) $this->get( 'button_animation', 'none' );
        $agents_on    = 'agents' === $this->get( 'button_mode', 'single' );
        $bubble_on    = 'yes' === $this->get( 'enable_welcome_bubble', 'no' );
        $bubble_style = 'simple' === $this->get( 'bubble_style', 'modern' ) ? 'simple' : 'modern';
        $bubble_side  = 'simple' === $bubble_style && 'side' === $this->get( 'bubble_position', 'top' );
        $bubble_img   = (string) $this->get( 'welcome_bubble_avatar', '' );

        // The button as it looks on desktop, so the first paint already matches the settings.
        $fab_classes = array( 'tap-chat-preview__fab' );
        if ( 'yes' === $this->get( 'hide_label_desktop', 'yes' ) ) {
            $fab_classes[] = 'is-icon-only';
        }
        if ( '' !== $custom_icon ) {
            $fab_classes[] = 'has-img';
        }
        if ( in_array( $animation, array( 'fade', 'slide', 'bounce', 'ring', 'attention' ), true ) ) {
            $fab_classes[] = 'is-anim-' . $animation;
        }
        if ( 'attention' === $animation ) {
            $fab_classes[] = 'has-badge';
        }

        echo '<div class="tap-chat-preview" data-tap-chat-preview data-device="desktop" data-scene="button">';
        echo '<div class="tap-chat-preview__head">';
        printf( '<span class="tap-chat-preview__title">%s</span>', esc_html__( 'Preview', 'tap-chat' ) );
        printf( '<span class="tap-chat-seg" role="group" aria-label="%s">', esc_attr__( 'Preview device', 'tap-chat' ) );
        printf( '<button type="button" class="tap-chat-seg__btn" aria-pressed="true" data-tap-chat-device="desktop" title="%1$s"><span class="screen-reader-text">%1$s</span>', esc_attr__( 'Desktop', 'tap-chat' ) );
        Admin_Icons::render( 'monitor' );
        printf( '</button><button type="button" class="tap-chat-seg__btn" aria-pressed="false" data-tap-chat-device="mobile" title="%1$s"><span class="screen-reader-text">%1$s</span>', esc_attr__( 'Mobile', 'tap-chat' ) );
        Admin_Icons::render( 'phone' );
        echo '</button></span></div>';

        printf(
            '<div class="tap-chat-preview__stage" aria-hidden="true"><div class="tap-chat-preview__screen is-%1$s" style="%2$s">',
            esc_attr( $position ),
            esc_attr( '--tap-chat-fab-color:' . ( $color ? $color : '#25D366' ) . ';--tap-chat-fab-size:' . ( $size ? $size : 40 ) . 'px;' )
        );
        echo '<span class="tap-chat-preview__bar"></span><span class="tap-chat-preview__line is-title"></span><span class="tap-chat-preview__line"></span><span class="tap-chat-preview__line"></span><span class="tap-chat-preview__line is-short"></span><span class="tap-chat-preview__block"></span><span class="tap-chat-preview__line"></span><span class="tap-chat-preview__line is-short"></span>';

        // Agent picker. Before admin.js runs, admin.css shows it only on the Agents view.
        printf( '<span class="tap-chat-preview__panel" data-tap-chat-preview-panel%s>', $agents_on ? '' : ' hidden' );
        printf(
            '<span class="tap-chat-preview__panel-head"><span class="tap-chat-preview__panel-text"><span class="tap-chat-preview__panel-title" data-tap-chat-preview-panel-title>%1$s</span><span class="tap-chat-preview__panel-sub" data-tap-chat-preview-panel-sub>%2$s</span></span><span class="tap-chat-preview__x">&times;</span></span>',
            esc_html( trim( (string) $this->get( 'agents_panel_title', __( 'Start a conversation', 'tap-chat' ) ) ) ),
            esc_html( trim( (string) $this->get( 'agents_panel_subtitle', __( 'Choose someone to chat with', 'tap-chat' ) ) ) )
        );
        echo '<span class="tap-chat-preview__agents" data-tap-chat-preview-agents>';
        $this->render_preview_agents();
        echo '</span></span>';

        // Welcome bubble. Before admin.js runs, admin.css shows it only on the Welcome Bubble view.
        $bubble_classes = 'tap-chat-preview__bubble' . ( 'simple' === $bubble_style ? ' is-simple' : '' ) . ( $bubble_side ? ' is-side' : '' );
        $message        = trim( (string) $this->get( 'welcome_bubble_message', '' ) );
        $name           = trim( (string) $this->get( 'welcome_bubble_name', '' ) );
        printf( '<span class="%1$s" data-tap-chat-preview-bubble%2$s>', esc_attr( $bubble_classes ), $bubble_on ? '' : ' hidden' );
        printf(
            '<span class="tap-chat-preview__bubble-avatar%1$s"><img alt="" src="%2$s" data-tap-chat-preview-bubble-img%3$s>',
            '' !== $bubble_img ? ' has-img' : '',
            esc_url( $bubble_img ),
            '' !== $bubble_img ? '' : ' hidden'
        );
        echo Admin_UI::whatsapp_svg( 'tap-chat-preview__bubble-glyph' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG.
        printf(
            '</span><span class="tap-chat-preview__bubble-content"><span class="tap-chat-preview__bubble-name"><span data-tap-chat-preview-bubble-name>%1$s</span><span class="tap-chat-preview__dot"></span></span><span class="tap-chat-preview__bubble-msg" data-tap-chat-preview-bubble-msg>%2$s</span></span>',
            esc_html( '' !== $name ? $name : __( 'Support Team', 'tap-chat' ) ),
            esc_html( '' !== $message ? $message : __( 'Need help? Let\'s chat! 💬', 'tap-chat' ) )
        );
        echo '<span class="tap-chat-preview__x">&times;</span></span>';

        // Floating button.
        // The dock scales the button down; the button itself runs the same animations as on the site.
        printf( '<span class="tap-chat-preview__dock" data-tap-chat-preview-dock><span class="%s" data-tap-chat-preview-fab>', esc_attr( implode( ' ', $fab_classes ) ) );
        printf( '<img class="tap-chat-preview__img" src="%1$s" alt=""%2$s>', esc_url( $custom_icon ), '' === $custom_icon ? ' hidden' : '' );
        echo Admin_UI::whatsapp_svg( 'tap-chat-preview__glyph' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG.
        printf( '<span class="tap-chat-preview__label">%s</span>', esc_html( '' !== trim( $label ) ? $label : __( 'Chat with us', 'tap-chat' ) ) );
        echo '<span class="tap-chat-preview__badge">1</span>';
        echo '</span></span></div></div>';

        echo '<p class="tap-chat-preview__hint" data-tap-chat-preview-hint hidden></p>';
        // Hints shown only before admin.js runs (it then manages the single hint above).
        if ( ! $agents_on ) {
            printf( '<p class="tap-chat-preview__hint tap-chat-preview__hint--boot" data-tap-chat-boot-hint="agents">%s</p>', esc_html__( 'Multiple agents is off, so visitors see a single button. Choose "Multiple agents" to preview the picker.', 'tap-chat' ) );
        }
        if ( ! $bubble_on ) {
            printf( '<p class="tap-chat-preview__hint tap-chat-preview__hint--boot" data-tap-chat-boot-hint="bubble">%s</p>', esc_html__( 'The welcome bubble is off. Turn it on to preview it.', 'tap-chat' ) );
        }
        printf( '<p class="tap-chat-preview__note">%s</p>', esc_html__( 'An approximate preview. Animations play on your site.', 'tap-chat' ) );
        echo '</div>';
    }

    /**
     * Agent rows of the preview picker, same markup as admin.js builds.
     */
    private function render_preview_agents() {
        $agents = $this->get( 'agents', array() );
        $shown  = 0;
        if ( is_array( $agents ) ) {
            foreach ( array_values( $agents ) as $i => $agent ) {
                if ( ! is_array( $agent ) || ( isset( $agent['enabled'] ) && 'no' === $agent['enabled'] ) ) {
                    continue;
                }
                if ( $shown >= 3 ) {
                    break;
                }
                $shown++;
                $online = ! ( isset( $agent['online'] ) && 'no' === $agent['online'] );
                $name   = isset( $agent['name'] ) ? trim( (string) $agent['name'] ) : '';
                /* translators: %d: agent number. */
                $name   = '' !== $name ? $name : sprintf( __( 'Agent %d', 'tap-chat' ), $i + 1 );
                $role   = isset( $agent['role'] ) ? trim( (string) $agent['role'] ) : '';
                $avatar = isset( $agent['avatar'] ) ? trim( (string) $agent['avatar'] ) : '';
                $custom = isset( $agent['channel_type'] ) && 'custom' === $agent['channel_type'];

                printf( '<span class="tap-chat-preview__agent%s"><span class="tap-chat-preview__agent-avatar">', $online ? '' : ' is-offline' );
                if ( '' !== $avatar ) {
                    printf( '<img alt="" src="%s">', esc_url( $avatar ) );
                } else {
                    echo Admin_UI::whatsapp_svg( 'tap-chat-preview__glyph' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG.
                }
                if ( $online ) {
                    echo '<span class="tap-chat-preview__dot"></span>';
                }
                printf( '</span><span class="tap-chat-preview__agent-info"><span class="tap-chat-preview__agent-name">%s</span>', esc_html( $name ) );
                if ( '' !== $role ) {
                    printf( '<span class="tap-chat-preview__agent-role">%s</span>', esc_html( $role ) );
                }
                printf( '<span class="tap-chat-preview__agent-status">%s</span></span><span class="tap-chat-preview__agent-channel">', esc_html( $online ? __( 'Online', 'tap-chat' ) : __( 'Offline', 'tap-chat' ) ) );
                if ( $custom ) {
                    echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1"/><path d="M14 10a4 4 0 0 0-5.7 0l-3 3A4 4 0 0 0 11 18.7l1-1"/></svg>';
                } else {
                    echo Admin_UI::whatsapp_svg( 'tap-chat-preview__glyph' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG.
                }
                echo '</span></span>';
            }
        }
        if ( ! $shown ) {
            printf( '<span class="tap-chat-preview__empty">%s</span>', esc_html__( 'Add an agent with a name to see it here.', 'tap-chat' ) );
        }
    }

    /* ---------------------------------------------------------------------
     * Agents
     * ------------------------------------------------------------------- */

    public function render_agents() {
        $mode = $this->get( 'button_mode', 'single' );

        Admin_UI::page_header(
            __( 'Agents', 'tap-chat' ),
            __( 'Show a small panel where visitors choose which team member to chat with. Add up to 3 agents, each with their own WhatsApp number or custom link.', 'tap-chat' )
        );

        echo '<div class="tap-chat-card"><p class="tap-chat-field__label">' . esc_html__( 'Button Mode', 'tap-chat' ) . '</p><div class="tap-chat-choice-grid">';
        Admin_UI::choice( 'button_mode', 'single', 'chat', __( 'Single button', 'tap-chat' ), __( 'Uses the WhatsApp number or link from the General tab.', 'tap-chat' ), $mode );
        Admin_UI::choice( 'button_mode', 'agents', 'users', __( 'Multiple agents (up to 3)', 'tap-chat' ), __( 'Visitors pick who to chat with in a small panel.', 'tap-chat' ), $mode );
        echo '</div></div>';

        echo '<div' . Admin_UI::show_if( 'button_mode', 'agents', $mode ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built with esc_attr().

        Admin_UI::section_title( __( 'Panel Text', 'tap-chat' ), __( 'Shown at the top of the agent picker. Leave a field empty to hide it.', 'tap-chat' ) );
        echo '<div class="tap-chat-card"><div class="tap-chat-grid-2">';
        Admin_UI::field_open( __( 'Panel Title', 'tap-chat' ), 'tap-chat-agents-panel-title' );
        printf(
            '<input type="text" class="tap-chat-input" id="tap-chat-agents-panel-title" name="%1$s" value="%2$s" placeholder="%3$s">',
            esc_attr( Admin_UI::name( 'agents_panel_title' ) ),
            esc_attr( $this->get( 'agents_panel_title', __( 'Start a conversation', 'tap-chat' ) ) ),
            esc_attr__( 'Start a conversation', 'tap-chat' )
        );
        Admin_UI::field_close();
        Admin_UI::field_open( __( 'Panel Subtitle', 'tap-chat' ), 'tap-chat-agents-panel-subtitle' );
        printf(
            '<input type="text" class="tap-chat-input" id="tap-chat-agents-panel-subtitle" name="%1$s" value="%2$s" placeholder="%3$s">',
            esc_attr( Admin_UI::name( 'agents_panel_subtitle' ) ),
            esc_attr( $this->get( 'agents_panel_subtitle', __( 'Choose someone to chat with', 'tap-chat' ) ) ),
            esc_attr__( 'Choose someone to chat with', 'tap-chat' )
        );
        Admin_UI::field_close();
        echo '</div></div>';

        Admin_UI::section_title( __( 'Agents', 'tap-chat' ), __( 'Drag a card by its handle to change the order visitors see.', 'tap-chat' ) );
        $this->render_agents_repeater();

        echo '</div>';
    }

    private function render_agents_repeater() {
        $agents = $this->get( 'agents', array() );
        if ( ! is_array( $agents ) || empty( $agents ) ) {
            // One blank card so the fields are visible right away.
            $agents = array( array() );
        }
        $default_cc = $this->get( 'country_code', '49' );

        echo '<div class="tap-chat-agents" data-tap-chat-agents data-max="3"><div class="tap-chat-agents__items" data-tap-chat-agents-items>';
        $i = 0;
        foreach ( $agents as $agent ) {
            if ( $i >= 3 ) {
                break;
            }
            $this->render_agent_card( $i, is_array( $agent ) ? $agent : array(), $default_cc );
            $i++;
        }
        echo '</div><div class="tap-chat-agents__foot">';
        echo '<button type="button" class="tap-chat-btn tap-chat-btn--ghost" data-tap-chat-agent-add>';
        Admin_Icons::render( 'plus' );
        printf( '<span>%s</span></button>', esc_html__( 'Add agent', 'tap-chat' ) );
        printf( '<span class="tap-chat-agents__limit" data-tap-chat-agent-limit hidden>%s</span>', esc_html__( 'Maximum of 3 agents reached.', 'tap-chat' ) );
        echo '</div>';

        echo '<script type="text/html" data-tap-chat-agent-template>';
        $this->render_agent_card( '__INDEX__', array(), $default_cc );
        echo '</script></div>';
    }

    /**
     * One agent card of the repeater.
     *
     * @param int|string $index      Numeric index, or '__INDEX__' for the JS template.
     * @param array      $agent      Saved agent data (may be empty).
     * @param string     $default_cc Fallback country code for new or blank agents.
     */
    private function render_agent_card( $index, $agent, $default_cc = '' ) {
        $id           = isset( $agent['id'] ) ? $agent['id'] : '';
        $name         = isset( $agent['name'] ) ? $agent['name'] : '';
        $role         = isset( $agent['role'] ) ? $agent['role'] : '';
        $avatar       = isset( $agent['avatar'] ) ? $agent['avatar'] : '';
        $channel_type = ( isset( $agent['channel_type'] ) && 'custom' === $agent['channel_type'] ) ? 'custom' : 'phone';
        $country_code = isset( $agent['country_code'] ) && '' !== $agent['country_code'] ? $agent['country_code'] : $default_cc;
        $phone        = isset( $agent['phone'] ) ? $agent['phone'] : '';
        $custom_url   = isset( $agent['custom_url'] ) ? $agent['custom_url'] : '';
        $message      = isset( $agent['message'] ) ? $agent['message'] : '';
        $online       = ! ( isset( $agent['online'] ) && 'no' === $agent['online'] );
        $enabled      = ! ( isset( $agent['enabled'] ) && 'no' === $agent['enabled'] );

        $base = Admin_UI::OPTION . '[agents][' . $index . ']';
        $uid  = 'tap-chat-agent-' . $index;
        $num  = is_numeric( $index ) ? ( (int) $index + 1 ) : '';
        ?>
        <div class="tap-chat-agent<?php echo $enabled ? '' : ' is-disabled'; ?>" data-tap-chat-agent>
            <div class="tap-chat-agent__head">
                <span class="tap-chat-agent__drag" data-tap-chat-agent-drag title="<?php esc_attr_e( 'Drag to reorder', 'tap-chat' ); ?>"><?php Admin_Icons::render( 'grip' ); ?></span>
                <span class="tap-chat-agent__title">
                    <?php esc_html_e( 'Agent', 'tap-chat' ); ?> <span data-tap-chat-agent-number><?php echo esc_html( $num ); ?></span>
                </span>
                <label class="tap-chat-agent__enabled">
                    <span><?php esc_html_e( 'Enabled', 'tap-chat' ); ?></span>
                    <input type="hidden" name="<?php echo esc_attr( $base ); ?>[enabled]" value="no">
                    <input type="checkbox" class="tap-chat-switch" role="switch" data-tap-chat-agent-enabled name="<?php echo esc_attr( $base ); ?>[enabled]" value="yes" <?php checked( $enabled ); ?>>
                </label>
                <button type="button" class="tap-chat-icon-btn tap-chat-icon-btn--danger" data-tap-chat-agent-remove aria-label="<?php esc_attr_e( 'Remove agent', 'tap-chat' ); ?>" title="<?php esc_attr_e( 'Remove agent', 'tap-chat' ); ?>"><?php Admin_Icons::render( 'trash' ); ?></button>
            </div>

            <input type="hidden" name="<?php echo esc_attr( $base ); ?>[id]" value="<?php echo esc_attr( $id ); ?>">

            <div class="tap-chat-agent__body">
                <div class="tap-chat-agent__media" data-tap-chat-media>
                    <span class="tap-chat-media__preview tap-chat-media__preview--round<?php echo '' !== $avatar ? ' has-image' : ''; ?>">
                        <img src="<?php echo esc_url( $avatar ); ?>" alt="" data-tap-chat-media-img>
                        <?php Admin_Icons::render( 'user' ); ?>
                    </span>
                    <input type="hidden" name="<?php echo esc_attr( $base ); ?>[avatar]" value="<?php echo esc_url( $avatar ); ?>" data-tap-chat-media-url>
                    <button type="button" class="tap-chat-btn tap-chat-btn--ghost tap-chat-btn--sm" data-tap-chat-media-choose><?php esc_html_e( 'Avatar', 'tap-chat' ); ?></button>
                    <button type="button" class="tap-chat-btn tap-chat-btn--quiet tap-chat-btn--sm" data-tap-chat-media-clear><?php esc_html_e( 'Remove', 'tap-chat' ); ?></button>
                </div>

                <div class="tap-chat-agent__fields">
                    <div class="tap-chat-grid-2">
                        <div class="tap-chat-field">
                            <label for="<?php echo esc_attr( $uid . '-name' ); ?>"><?php esc_html_e( 'Name', 'tap-chat' ); ?></label>
                            <input type="text" class="tap-chat-input" id="<?php echo esc_attr( $uid . '-name' ); ?>" name="<?php echo esc_attr( $base ); ?>[name]" value="<?php echo esc_attr( $name ); ?>" placeholder="<?php esc_attr_e( 'e.g. Sarah', 'tap-chat' ); ?>">
                        </div>
                        <div class="tap-chat-field">
                            <label for="<?php echo esc_attr( $uid . '-role' ); ?>"><?php esc_html_e( 'Role (optional)', 'tap-chat' ); ?></label>
                            <input type="text" class="tap-chat-input" id="<?php echo esc_attr( $uid . '-role' ); ?>" name="<?php echo esc_attr( $base ); ?>[role]" value="<?php echo esc_attr( $role ); ?>" placeholder="<?php esc_attr_e( 'e.g. Sales', 'tap-chat' ); ?>">
                        </div>
                    </div>

                    <div class="tap-chat-chips" role="radiogroup" aria-label="<?php esc_attr_e( 'Channel', 'tap-chat' ); ?>">
                        <label class="tap-chat-chip">
                            <input type="radio" data-tap-chat-agent-channel name="<?php echo esc_attr( $base ); ?>[channel_type]" value="phone" <?php checked( $channel_type, 'phone' ); ?>>
                            <span><?php esc_html_e( 'WhatsApp', 'tap-chat' ); ?></span>
                        </label>
                        <label class="tap-chat-chip">
                            <input type="radio" data-tap-chat-agent-channel name="<?php echo esc_attr( $base ); ?>[channel_type]" value="custom" <?php checked( $channel_type, 'custom' ); ?>>
                            <span><?php esc_html_e( 'Custom link', 'tap-chat' ); ?></span>
                        </label>
                    </div>

                    <div data-tap-chat-agent-panel="phone"<?php echo 'custom' === $channel_type ? ' hidden' : ''; ?>>
                        <div class="tap-chat-grid-2 tap-chat-grid-2--phone">
                            <div class="tap-chat-field">
                                <label for="<?php echo esc_attr( $uid . '-country' ); ?>"><?php esc_html_e( 'Country', 'tap-chat' ); ?></label>
                                <?php $this->render_country_select( $base . '[country_code]', $country_code, $uid . '-country', true ); ?>
                            </div>
                            <div class="tap-chat-field">
                                <label for="<?php echo esc_attr( $uid . '-phone' ); ?>"><?php esc_html_e( 'Phone number', 'tap-chat' ); ?></label>
                                <input type="text" inputmode="tel" class="tap-chat-input" id="<?php echo esc_attr( $uid . '-phone' ); ?>" name="<?php echo esc_attr( $base ); ?>[phone]" value="<?php echo esc_attr( $phone ); ?>" placeholder="123456789" dir="ltr">
                            </div>
                        </div>
                        <div class="tap-chat-field tap-chat-mt-sm">
                            <label for="<?php echo esc_attr( $uid . '-message' ); ?>"><?php esc_html_e( 'Pre-filled message (optional)', 'tap-chat' ); ?></label>
                            <textarea rows="2" class="tap-chat-input tap-chat-textarea" id="<?php echo esc_attr( $uid . '-message' ); ?>" name="<?php echo esc_attr( $base ); ?>[message]" placeholder="<?php esc_attr_e( 'Hello! I have a question…', 'tap-chat' ); ?>"><?php echo esc_textarea( $message ); ?></textarea>
                        </div>
                    </div>

                    <div data-tap-chat-agent-panel="custom"<?php echo 'phone' === $channel_type ? ' hidden' : ''; ?>>
                        <div class="tap-chat-field">
                            <label for="<?php echo esc_attr( $uid . '-url' ); ?>"><?php esc_html_e( 'Custom link', 'tap-chat' ); ?></label>
                            <input type="url" class="tap-chat-input" id="<?php echo esc_attr( $uid . '-url' ); ?>" name="<?php echo esc_attr( $base ); ?>[custom_url]" value="<?php echo esc_attr( $custom_url ); ?>" placeholder="https://t.me/username" dir="ltr">
                            <p class="tap-chat-field__help"><?php esc_html_e( 'Telegram, Messenger, tel:, mailto:, or any URL. The pre-filled message is not sent with a custom link.', 'tap-chat' ); ?></p>
                        </div>
                    </div>

                    <label class="tap-chat-inline-switch">
                        <input type="hidden" name="<?php echo esc_attr( $base ); ?>[online]" value="no">
                        <input type="checkbox" class="tap-chat-switch tap-chat-switch--sm" role="switch" name="<?php echo esc_attr( $base ); ?>[online]" value="yes" <?php checked( $online ); ?>>
                        <span><?php esc_html_e( 'Show as online (green dot)', 'tap-chat' ); ?></span>
                    </label>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Native country select built from the country list.
     *
     * @param string $name        Field name.
     * @param string $selected    Selected calling code.
     * @param string $id          Element ID.
     * @param bool   $allow_empty Whether to offer an empty choice.
     */
    private function render_country_select( $name, $selected, $id, $allow_empty ) {
        printf( '<select class="tap-chat-select" id="%1$s" name="%2$s">', esc_attr( $id ), esc_attr( $name ) );
        if ( $allow_empty ) {
            echo '<option value="">' . esc_html__( 'Select country…', 'tap-chat' ) . '</option>';
        }
        foreach ( $this->get_countries() as $code => $data ) {
            printf(
                '<option value="%1$s"%2$s>%3$s</option>',
                esc_attr( $code ),
                selected( (string) $selected, (string) $code, false ),
                esc_html( $data['flag'] . ' ' . $data['name'] . ' (+' . $code . ')' )
            );
        }
        echo '</select>';
    }

    /* ---------------------------------------------------------------------
     * Welcome bubble
     * ------------------------------------------------------------------- */

    public function render_bubble() {
        $enable   = $this->get( 'enable_welcome_bubble', 'no' );
        $style    = $this->get( 'bubble_style', 'modern' );
        $position = $this->get( 'bubble_position', 'top' );

        Admin_UI::page_header(
            __( 'Welcome Bubble', 'tap-chat' ),
            __( 'Display a friendly welcome message to encourage visitors to start a conversation. Use smart triggers to show the bubble at the perfect moment.', 'tap-chat' )
        );

        echo '<div class="tap-chat-card"><div class="tap-chat-toggle-list">';
        Admin_UI::toggle_row( 'enable_welcome_bubble', 'message', __( 'Enable Welcome Bubble', 'tap-chat' ), __( 'Show a friendly message bubble to encourage visitors to start a conversation', 'tap-chat' ), 'yes' === $enable );
        echo '</div></div>';

        echo '<div' . Admin_UI::show_if( 'enable_welcome_bubble', 'yes', $enable ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built with esc_attr().

        Admin_UI::section_title( __( 'Bubble', 'tap-chat' ) );
        echo '<div class="tap-chat-card"><p class="tap-chat-field__label">' . esc_html__( 'Bubble Style', 'tap-chat' ) . '</p><div class="tap-chat-choice-grid">';
        Admin_UI::choice( 'bubble_style', 'modern', 'user', __( 'Modern', 'tap-chat' ), __( 'Rich bubble with avatar, name, and online status', 'tap-chat' ), $style );
        Admin_UI::choice( 'bubble_style', 'simple', 'message', __( 'Simple', 'tap-chat' ), __( 'Clean bubble with message only', 'tap-chat' ), $style );
        echo '</div><hr class="tap-chat-divider">';

        Admin_UI::field_open( __( 'Welcome Message', 'tap-chat' ), 'tap-chat-welcome-bubble-message' );
        printf(
            '<textarea class="tap-chat-input tap-chat-textarea" id="tap-chat-welcome-bubble-message" name="%1$s" rows="3" placeholder="%2$s">%3$s</textarea>',
            esc_attr( Admin_UI::name( 'welcome_bubble_message' ) ),
            esc_attr__( 'Need help? Let\'s chat! 💬', 'tap-chat' ),
            esc_textarea( $this->get( 'welcome_bubble_message', __( 'Need help? Let\'s chat! 💬', 'tap-chat' ) ) )
        );
        Admin_UI::field_close( __( 'The message to display in the welcome bubble. Emojis are supported!', 'tap-chat' ) );

        echo '<div class="tap-chat-grid-2 tap-chat-mt"' . Admin_UI::show_if( 'bubble_style', 'simple', $style ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built with esc_attr().
        Admin_UI::field_open( __( 'Bubble Position', 'tap-chat' ), 'tap-chat-bubble-position' );
        printf( '<select class="tap-chat-select" id="tap-chat-bubble-position" name="%s">', esc_attr( Admin_UI::name( 'bubble_position' ) ) );
        printf( '<option value="top"%1$s>%2$s</option>', selected( $position, 'top', false ), esc_html__( 'Top (Above button)', 'tap-chat' ) );
        printf( '<option value="side"%1$s>%2$s</option>', selected( $position, 'side', false ), esc_html__( 'Side (Next to button)', 'tap-chat' ) );
        echo '</select>';
        Admin_UI::field_close( __( 'Choose where the bubble appears relative to the button (Simple style only)', 'tap-chat' ) );
        echo '</div>';

        echo '<div class="tap-chat-mt"' . Admin_UI::show_if( 'bubble_style', 'modern', $style ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built with esc_attr().
        echo '<div class="tap-chat-grid-2">';
        Admin_UI::field_open( __( 'Agent/Team Name', 'tap-chat' ), 'tap-chat-welcome-bubble-name' );
        printf(
            '<input type="text" class="tap-chat-input" id="tap-chat-welcome-bubble-name" name="%1$s" value="%2$s" placeholder="%3$s">',
            esc_attr( Admin_UI::name( 'welcome_bubble_name' ) ),
            esc_attr( $this->get( 'welcome_bubble_name', __( 'Support Team', 'tap-chat' ) ) ),
            esc_attr__( 'Support Team', 'tap-chat' )
        );
        Admin_UI::field_close( __( 'Display name for the agent or team (Modern style only)', 'tap-chat' ) );
        echo '</div>';
        Admin_UI::card_subtitle( __( 'Avatar URL (Optional)', 'tap-chat' ), __( 'Upload an avatar image or enter URL. Leave empty to use default WhatsApp icon (Modern style only).', 'tap-chat' ) );
        Admin_UI::image_picker( 'welcome_bubble_avatar', (string) $this->get( 'welcome_bubble_avatar', '' ), 'https://example.com/avatar.jpg', __( 'Choose Image', 'tap-chat' ), __( 'Remove', 'tap-chat' ), 'round' );
        echo '</div></div>';

        $this->render_triggers();

        echo '</div>';
    }

    private function render_triggers() {
        $time_on   = 'yes' === $this->get( 'trigger_time_enabled', 'yes' );
        $scroll_on = 'yes' === $this->get( 'trigger_scroll_enabled', 'no' );
        $exit_on   = 'yes' === $this->get( 'trigger_exit_enabled', 'no' );
        $idle_on   = 'yes' === $this->get( 'trigger_idle_enabled', 'no' );

        Admin_UI::section_title( __( 'Smart Triggers', 'tap-chat' ), __( 'Control when the welcome bubble appears. At least one trigger must be enabled.', 'tap-chat' ) );
        echo '<div class="tap-chat-card"><div class="tap-chat-toggle-list">';

        echo '<div class="tap-chat-toggle-group">';
        Admin_UI::toggle_row( 'trigger_time_enabled', 'timer', __( 'Time on Page', 'tap-chat' ), __( 'Show bubble after visitor spends specific time on page (recommended)', 'tap-chat' ), $time_on );
        echo '<div class="tap-chat-toggle-group__body"' . Admin_UI::show_if( 'trigger_time_enabled', 'yes', $time_on ? 'yes' : 'no' ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built with esc_attr().
        $this->render_number_field( 'trigger_time_delay', __( 'Delay (seconds)', 'tap-chat' ), 3, 0, 300, 1, __( 'Show bubble after this many seconds (0-300). 0 = show immediately.', 'tap-chat' ) );
        echo '</div></div>';

        echo '<div class="tap-chat-toggle-group">';
        Admin_UI::toggle_row( 'trigger_scroll_enabled', 'scroll', __( 'Scroll Depth', 'tap-chat' ), __( 'Show bubble when visitor scrolls to a specific percentage of the page', 'tap-chat' ), $scroll_on );
        echo '<div class="tap-chat-toggle-group__body"' . Admin_UI::show_if( 'trigger_scroll_enabled', 'yes', $scroll_on ? 'yes' : 'no' ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built with esc_attr().
        $this->render_number_field( 'trigger_scroll_depth', __( 'Scroll Depth (%)', 'tap-chat' ), 50, 10, 100, 5, __( 'Show bubble after scrolling this percentage (10-100%)', 'tap-chat' ) );
        echo '</div></div>';

        echo '<div class="tap-chat-toggle-group">';
        Admin_UI::toggle_row( 'trigger_exit_enabled', 'exit', __( 'Exit Intent', 'tap-chat' ), __( 'Show bubble when visitor moves mouse towards closing the page/tab', 'tap-chat' ), $exit_on );
        echo '</div>';

        echo '<div class="tap-chat-toggle-group">';
        Admin_UI::toggle_row( 'trigger_idle_enabled', 'moon', __( 'Idle Detection', 'tap-chat' ), __( 'Show bubble when visitor is inactive (no mouse/keyboard activity)', 'tap-chat' ), $idle_on );
        echo '<div class="tap-chat-toggle-group__body"' . Admin_UI::show_if( 'trigger_idle_enabled', 'yes', $idle_on ? 'yes' : 'no' ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built with esc_attr().
        $this->render_number_field( 'trigger_idle_time', __( 'Idle Time (seconds)', 'tap-chat' ), 60, 10, 300, 10, __( 'Show bubble after this many seconds of inactivity (10-300)', 'tap-chat' ) );
        echo '</div></div>';

        echo '</div><div class="tap-chat-mt">';
        Admin_UI::alert(
            'info',
            '<strong>' . esc_html__( 'Tip:', 'tap-chat' ) . '</strong> ' . esc_html__( 'When multiple triggers are enabled, the bubble shows on whichever condition is met first. Time on Page is enabled by default and recommended for most websites.', 'tap-chat' )
        );
        echo '</div></div>';
    }

    private function render_number_field( $key, $label, $default, $min, $max, $step, $help, $suffix = '' ) {
        Admin_UI::field_open( $label, Admin_UI::id( $key ), 'tap-chat-field--inline' );
        printf(
            '<span class="tap-chat-number"><input type="number" class="tap-chat-input tap-chat-input--number" id="%1$s" name="%2$s" value="%3$d" min="%4$d" max="%5$d" step="%6$d">%7$s</span>',
            esc_attr( Admin_UI::id( $key ) ),
            esc_attr( Admin_UI::name( $key ) ),
            absint( $this->get( $key, $default ) ),
            (int) $min,
            (int) $max,
            (int) $step,
            '' !== $suffix ? '<span class="tap-chat-number__suffix">' . esc_html( $suffix ) . '</span>' : ''
        );
        Admin_UI::field_close( $help );
    }

    /* ---------------------------------------------------------------------
     * Working hours
     * ------------------------------------------------------------------- */

    public function render_hours() {
        $enable        = $this->get( 'enable_working_hours', 'no' );
        $timezone      = $this->get( 'timezone', wp_timezone_string() );
        $working_hours = $this->get( 'working_hours', $this->get_default_working_hours() );
        $days          = array(
            'monday'    => __( 'Monday', 'tap-chat' ),
            'tuesday'   => __( 'Tuesday', 'tap-chat' ),
            'wednesday' => __( 'Wednesday', 'tap-chat' ),
            'thursday'  => __( 'Thursday', 'tap-chat' ),
            'friday'    => __( 'Friday', 'tap-chat' ),
            'saturday'  => __( 'Saturday', 'tap-chat' ),
            'sunday'    => __( 'Sunday', 'tap-chat' ),
        );

        Admin_UI::page_header(
            __( 'Working Hours', 'tap-chat' ),
            __( 'Set your business hours. The button will only show during these hours.', 'tap-chat' )
        );

        echo '<div class="tap-chat-card"><div class="tap-chat-toggle-list">';
        Admin_UI::toggle_row( 'enable_working_hours', 'clock', __( 'Enable Working Hours', 'tap-chat' ), __( 'Show the button only during your business hours', 'tap-chat' ), 'yes' === $enable );
        echo '</div></div>';

        echo '<div' . Admin_UI::show_if( 'enable_working_hours', 'yes', $enable ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built with esc_attr().

        Admin_UI::section_title( __( 'Schedule', 'tap-chat' ) );
        echo '<div class="tap-chat-card"><div class="tap-chat-grid-2">';
        Admin_UI::field_open( __( 'Business Timezone', 'tap-chat' ), 'tap-chat-timezone' );
        printf( '<select class="tap-chat-select" id="tap-chat-timezone" name="%s">', esc_attr( Admin_UI::name( 'timezone' ) ) );
        $zones = timezone_identifiers_list();
        // A UTC offset (the WordPress default, e.g. "+00:00") is not a named zone; keep it selectable.
        if ( '' !== (string) $timezone && ! in_array( $timezone, $zones, true ) ) {
            array_unshift( $zones, (string) $timezone );
        }
        foreach ( $zones as $tz ) {
            printf( '<option value="%1$s"%2$s>%3$s</option>', esc_attr( $tz ), selected( $timezone, $tz, false ), esc_html( $tz ) );
        }
        echo '</select>';
        Admin_UI::field_close( __( 'Select your business timezone for accurate scheduling', 'tap-chat' ) );
        echo '</div><hr class="tap-chat-divider">';

        echo '<div class="tap-chat-days" role="table" aria-label="' . esc_attr__( 'Working Hours', 'tap-chat' ) . '">';
        echo '<div class="tap-chat-days__row tap-chat-days__row--head" role="row">';
        printf( '<span role="columnheader">%s</span>', esc_html__( 'Day', 'tap-chat' ) );
        printf( '<span role="columnheader">%s</span>', esc_html__( 'Open', 'tap-chat' ) );
        printf( '<span role="columnheader">%s</span>', esc_html__( 'Start Time', 'tap-chat' ) );
        printf( '<span role="columnheader">%s</span>', esc_html__( 'End Time', 'tap-chat' ) );
        echo '</div>';
        foreach ( $days as $day => $label ) {
            $data = isset( $working_hours[ $day ] ) && is_array( $working_hours[ $day ] ) ? $working_hours[ $day ] : array( 'enabled' => 'no', 'start' => '09:00', 'end' => '17:00' );
            $open = isset( $data['enabled'] ) && 'yes' === $data['enabled'];
            $base = Admin_UI::OPTION . '[working_hours][' . $day . ']';
            printf( '<div class="tap-chat-days__row%s" role="row" data-tap-chat-day>', $open ? '' : ' is-closed' );
            printf( '<span class="tap-chat-days__name" role="cell">%s</span>', esc_html( $label ) );
            printf(
                '<span role="cell"><input type="hidden" name="%1$s[enabled]" value="no"><input type="checkbox" class="tap-chat-switch" role="switch" name="%1$s[enabled]" value="yes" aria-label="%2$s" data-tap-chat-day-open%3$s></span>',
                esc_attr( $base ),
                /* translators: %s: day of the week. */
                esc_attr( sprintf( __( 'Open on %s', 'tap-chat' ), $label ) ),
                checked( $open, true, false )
            );
            printf(
                '<span role="cell"><input type="time" class="tap-chat-input tap-chat-input--time" name="%1$s[start]" value="%2$s" aria-label="%3$s"></span>',
                esc_attr( $base ),
                esc_attr( isset( $data['start'] ) ? $data['start'] : '09:00' ),
                esc_attr__( 'Start Time', 'tap-chat' )
            );
            printf(
                '<span role="cell"><input type="time" class="tap-chat-input tap-chat-input--time" name="%1$s[end]" value="%2$s" aria-label="%3$s"></span>',
                esc_attr( $base ),
                esc_attr( isset( $data['end'] ) ? $data['end'] : '17:00' ),
                esc_attr__( 'End Time', 'tap-chat' )
            );
            echo '</div>';
        }
        echo '</div></div>';

        Admin_UI::section_title( __( 'Outside working hours', 'tap-chat' ) );
        echo '<div class="tap-chat-card">';
        Admin_UI::field_open( __( 'Offline Message (Optional)', 'tap-chat' ), 'tap-chat-offline-message' );
        printf(
            '<textarea class="tap-chat-input tap-chat-textarea" id="tap-chat-offline-message" name="%1$s" rows="3" placeholder="%2$s">%3$s</textarea>',
            esc_attr( Admin_UI::name( 'offline_message' ) ),
            esc_attr__( 'We are currently offline. Our business hours are Monday-Friday, 9 AM - 5 PM.', 'tap-chat' ),
            esc_textarea( $this->get( 'offline_message', '' ) )
        );
        Admin_UI::field_close( __( 'This message replaces the button label when outside working hours. Leave empty to hide the button completely.', 'tap-chat' ) );
        echo '</div></div>';
    }

    /* ---------------------------------------------------------------------
     * Visibility
     * ------------------------------------------------------------------- */

    public function render_visibility() {
        $show_on = $this->get( 'enable_show_on', 'no' );
        $hide_on = $this->get( 'enable_hide_on', 'no' );
        $content = $this->get_selectable_content();

        Admin_UI::page_header(
            __( 'Visibility', 'tap-chat' ),
            __( 'Control where the WhatsApp button appears on your site.', 'tap-chat' )
        );

        echo '<div class="tap-chat-card"><div class="tap-chat-toggle-list">';

        echo '<div class="tap-chat-toggle-group">';
        Admin_UI::toggle_row( 'enable_show_on', 'eye', __( 'Show only on selected pages', 'tap-chat' ), __( 'When enabled, button will appear only on pages you select below', 'tap-chat' ), 'yes' === $show_on );
        echo '<div class="tap-chat-toggle-group__body"' . Admin_UI::show_if( 'enable_show_on', 'yes', $show_on ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built with esc_attr().
        $this->render_page_picker( 'show_on_pages', (array) $this->get( 'show_on_pages', array() ), $content );
        echo '</div></div>';

        echo '<div class="tap-chat-toggle-group">';
        Admin_UI::toggle_row( 'enable_hide_on', 'eye-off', __( 'Hide on selected pages', 'tap-chat' ), __( 'Hide the button on selected pages (e.g., checkout, cart)', 'tap-chat' ), 'yes' === $hide_on );
        echo '<div class="tap-chat-toggle-group__body"' . Admin_UI::show_if( 'enable_hide_on', 'yes', $hide_on ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built with esc_attr().
        $this->render_page_picker( 'hide_on_pages', (array) $this->get( 'hide_on_pages', array() ), $content );
        echo '</div></div>';

        echo '</div></div>';
    }

    /**
     * Published pages and posts, loaded once for both pickers.
     *
     * @return array
     */
    private function get_selectable_content() {
        $pages = get_pages(
            array(
                'post_type'   => 'page',
                'post_status' => 'publish',
                'sort_column' => 'post_title',
                'sort_order'  => 'ASC',
            )
        );
        $posts = get_posts(
            array(
                'post_type'   => 'post',
                'post_status' => 'publish',
                'numberposts' => -1,
                'orderby'     => 'title',
                'order'       => 'ASC',
            )
        );
        return array_merge( is_array( $pages ) ? $pages : array(), is_array( $posts ) ? $posts : array() );
    }

    private function render_page_picker( $field, $selected, $content ) {
        $selected = array_map( 'absint', $selected );
        $count    = 0;
        foreach ( $content as $item ) {
            if ( in_array( (int) $item->ID, $selected, true ) ) {
                $count++;
            }
        }
        /* translators: %d: number of selected pages. */
        $template = __( '%d selected', 'tap-chat' );

        printf( '<div class="tap-chat-picker" data-tap-chat-picker data-count-template="%s">', esc_attr( $template ) );
        echo '<div class="tap-chat-picker__bar"><label class="tap-chat-search">';
        Admin_Icons::render( 'search' );
        printf(
            '<span class="screen-reader-text">%1$s</span><input type="search" class="tap-chat-input" placeholder="%1$s" data-tap-chat-picker-search></label>',
            esc_attr__( 'Search pages or posts...', 'tap-chat' )
        );
        printf( '<span class="tap-chat-picker__count" data-tap-chat-picker-count aria-live="polite">%s</span></div>', esc_html( sprintf( $template, $count ) ) );

        echo '<div class="tap-chat-picker__list">';
        if ( empty( $content ) ) {
            printf( '<p class="tap-chat-picker__empty">%s</p>', esc_html__( 'No pages or posts found.', 'tap-chat' ) );
        }
        foreach ( $content as $item ) {
            $type = get_post_type_object( $item->post_type );
            printf(
                '<label class="tap-chat-picker__item" data-tap-chat-picker-item><input type="checkbox" name="%1$s[]" value="%2$d"%3$s><span class="tap-chat-picker__title">%4$s</span><span class="tap-chat-badge">%5$s</span></label>',
                esc_attr( Admin_UI::name( $field ) ),
                (int) $item->ID,
                checked( in_array( (int) $item->ID, $selected, true ), true, false ),
                esc_html( '' !== $item->post_title ? $item->post_title : __( '(no title)', 'tap-chat' ) ),
                esc_html( $type ? $type->labels->singular_name : ucfirst( $item->post_type ) )
            );
        }
        echo '</div></div>';
    }

    /* ---------------------------------------------------------------------
     * Advanced
     * ------------------------------------------------------------------- */

    public function render_advanced() {
        Admin_UI::page_header(
            __( 'Advanced', 'tap-chat' ),
            __( 'Advanced configuration options for power users.', 'tap-chat' )
        );
        echo '<div class="tap-chat-card"><div class="tap-chat-toggle-list">';
        Admin_UI::toggle_row(
            'append_page_context',
            'file',
            __( 'Automatically append page title and URL to the message', 'tap-chat' ),
            __( 'This helps you know which page the visitor is contacting you from', 'tap-chat' ),
            'yes' === $this->get( 'append_page_context', 'no' )
        );
        echo '</div></div>';
    }

    /* ---------------------------------------------------------------------
     * Tracking (analytics settings)
     * ------------------------------------------------------------------- */

    public function render_tracking( $reset_url, $was_reset ) {
        $ga_on = $this->get( 'enable_analytics', 'no' );

        Admin_UI::page_header(
            __( 'Tracking', 'tap-chat' ),
            __( 'Choose what Tap Chat records on your site and which events it sends to Google Analytics.', 'tap-chat' )
        );

        if ( $was_reset ) {
            Admin_UI::alert( 'success', esc_html__( 'Analytics data has been reset.', 'tap-chat' ) );
        }

        Admin_UI::section_title(
            __( 'Data collection', 'tap-chat' ),
            __( 'Built-in, privacy-first analytics stored on your own site. No cookies, no personal data, no external requests — your data never leaves your server. Click tracking is lightweight; traffic tracking adds one small request per page view to measure CTR.', 'tap-chat' )
        );
        echo '<div class="tap-chat-card"><div class="tap-chat-toggle-list">';
        Admin_UI::toggle_row( 'enable_click_analytics', 'chart', __( 'Click tracking', 'tap-chat' ), __( 'Lightweight and recommended. Powers Total / Today / Week / Month clicks, top pages and device breakdown.', 'tap-chat' ), 'yes' === $this->get( 'enable_click_analytics', 'yes' ) );
        Admin_UI::toggle_row( 'enable_traffic_analytics', 'activity', __( 'Traffic & CTR tracking', 'tap-chat' ), __( 'Adds one small, cookieless request per page view where the button is shown. Enables the Visitors / Button Views / Clicks / CTR funnel. Leave off if you only need click counts.', 'tap-chat' ), 'yes' === $this->get( 'enable_traffic_analytics', 'no' ) );
        Admin_UI::toggle_row( 'analytics_exclude_logged_in', 'user-off', __( 'Exclude logged-in users', 'tap-chat' ), __( 'Recommended. Keeps your own testing and staff activity out of the numbers.', 'tap-chat' ), 'yes' === $this->get( 'analytics_exclude_logged_in', 'no' ) );
        echo '</div><hr class="tap-chat-divider">';
        $this->render_number_field( 'analytics_retention_days', __( 'Keep data for', 'tap-chat' ), 365, 7, 3650, 1, __( 'Older analytics rows are deleted automatically once per day. Minimum 7, maximum 3650.', 'tap-chat' ), __( 'days', 'tap-chat' ) );
        echo '</div>';

        Admin_UI::section_title(
            __( 'Google Analytics 4 / Tag Manager', 'tap-chat' ),
            __( 'Track button clicks and welcome-bubble impressions as GA4 events. This uses the Google Analytics 4 or Google Tag Manager you already have on the site — the plugin never loads its own analytics library and never sends data anywhere else. If no GA4/GTM is present on a page, nothing is sent.', 'tap-chat' )
        );
        echo '<div class="tap-chat-card"><div class="tap-chat-toggle-list">';
        Admin_UI::toggle_row( 'enable_analytics', 'zap', __( 'Enable Event Tracking', 'tap-chat' ), __( 'Does not install Google Analytics for you — it sends events to the GA4 or GTM already on your site. If neither is present on a page, nothing happens (no errors).', 'tap-chat' ), 'yes' === $ga_on );
        echo '</div><div' . Admin_UI::show_if( 'enable_analytics', 'yes', $ga_on ) . '><hr class="tap-chat-divider"><div class="tap-chat-grid-2">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built with esc_attr().

        $method  = $this->get( 'analytics_method', 'auto' );
        $methods = array(
            'auto'      => __( 'Auto-detect (recommended)', 'tap-chat' ),
            'gtag'      => __( 'GA4 gtag.js only', 'tap-chat' ),
            'datalayer' => __( 'Google Tag Manager (dataLayer) only', 'tap-chat' ),
        );
        Admin_UI::field_open( __( 'Send Events Via', 'tap-chat' ), 'tap-chat-analytics-method' );
        printf( '<select class="tap-chat-select" id="tap-chat-analytics-method" name="%s">', esc_attr( Admin_UI::name( 'analytics_method' ) ) );
        foreach ( $methods as $key => $text ) {
            printf( '<option value="%1$s"%2$s>%3$s</option>', esc_attr( $key ), selected( $method, $key, false ), esc_html( $text ) );
        }
        echo '</select>';
        Admin_UI::field_close( __( 'Auto-detect sends via whichever is present on the page — gtag(), the GTM dataLayer, or both.', 'tap-chat' ) );

        Admin_UI::field_open( __( 'Click Event Name', 'tap-chat' ), 'tap-chat-analytics-event-name' );
        printf(
            '<input type="text" class="tap-chat-input" id="tap-chat-analytics-event-name" name="%1$s" value="%2$s" placeholder="tapchat_click" dir="ltr">',
            esc_attr( Admin_UI::name( 'analytics_event_name' ) ),
            esc_attr( $this->get( 'analytics_event_name', 'tapchat_click' ) )
        );
        Admin_UI::field_close( __( 'GA4 event name sent on a button/link click. Lowercase letters, numbers and underscores only; must start with a letter (max 40 characters). In GA4 you can mark this event as a Key Event (Conversion) to measure which campaigns drive the most chats.', 'tap-chat' ) );

        echo '</div><div class="tap-chat-toggle-list tap-chat-mt">';
        Admin_UI::toggle_row( 'analytics_track_triggers', 'message', __( 'Track Bubble Triggers', 'tap-chat' ), __( 'Includes a trigger_type parameter (time, scroll, exit, idle) so you can compare in GA4 which Smart Trigger produces the most chats.', 'tap-chat' ), 'yes' === $this->get( 'analytics_track_triggers', 'yes' ) );
        echo '</div></div></div>';

        Admin_UI::section_title( __( 'Reset analytics data', 'tap-chat' ) );
        echo '<div class="tap-chat-card tap-chat-card--danger"><div class="tap-chat-danger">';
        echo '<span class="tap-chat-danger__icon">';
        Admin_Icons::render( 'database' );
        printf( '</span><p class="tap-chat-danger__text">%s</p>', esc_html__( 'Permanently delete all recorded clicks, views and visitors. This cannot be undone. Your settings are kept.', 'tap-chat' ) );
        printf(
            '<a class="tap-chat-btn tap-chat-btn--danger" href="%1$s" data-tap-chat-confirm="%2$s">',
            esc_url( $reset_url ),
            esc_attr__( 'Delete all Tap Chat analytics data? This cannot be undone.', 'tap-chat' )
        );
        Admin_Icons::render( 'trash' );
        printf( '<span>%s</span></a>', esc_html__( 'Reset analytics data', 'tap-chat' ) );
        echo '</div></div>';
    }

    private function get_default_working_hours() {
        return array(
            'monday'    => array( 'enabled' => 'yes', 'start' => '09:00', 'end' => '17:00' ),
            'tuesday'   => array( 'enabled' => 'yes', 'start' => '09:00', 'end' => '17:00' ),
            'wednesday' => array( 'enabled' => 'yes', 'start' => '09:00', 'end' => '17:00' ),
            'thursday'  => array( 'enabled' => 'yes', 'start' => '09:00', 'end' => '17:00' ),
            'friday'    => array( 'enabled' => 'yes', 'start' => '09:00', 'end' => '17:00' ),
            'saturday'  => array( 'enabled' => 'no', 'start' => '09:00', 'end' => '17:00' ),
            'sunday'    => array( 'enabled' => 'no', 'start' => '09:00', 'end' => '17:00' ),
        );
    }
}
