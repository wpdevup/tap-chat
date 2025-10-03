=== Tap Chat ===
Contributors: iruserwp9
Tags: whatsapp, chat, click to chat, support, contact
Requires at least: 5.8
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 0.9.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Lightweight click-to-chat button for WhatsApp. Floating action button + shortcode. GDPR-friendly, no tracking.

== Description ==
Tap Chat adds a beautiful floating button that opens WhatsApp chat in one click. It's intentionally simple and performance-first: tiny CSS/JS (≈3 KB), no external requests, no trackers, and no database bloat—so it won't slow your site. Use the global floating button or insert a button anywhere with the `[tapchat]` shortcode.

**Features:**
- **Smart Country Selector**: Choose from 150+ countries with flags and search functionality
- **Intelligent Phone Input**: Automatically detects country from WordPress locale and removes leading zeros
- **Advanced Visibility Controls**: Show or hide button on specific pages/posts
- **Floating Button**: Site-wide button with customizable position (left/right)
- **Full Customization**: Custom phone number, message, label, size, and color
- **Separate Mobile/Desktop Controls**: Different sizes and label visibility for mobile and desktop
- **WordPress Color Picker**: Easy color selection with visual picker
- **Shortcode Support**: `[tapchat phone="49123456789" message="Hello" label="Chat now"]`
- **WooCommerce Compatible**: Works perfectly with WooCommerce shop and product pages
- **RTL Support**: Fully compatible with right-to-left languages
- **Performance Optimized**: Minimal footprint, no external dependencies
- **GDPR Friendly**: No tracking, no cookies, no personal data collection

**New in 0.9.0**
- Advanced page visibility controls (show only on specific pages or hide on specific pages)
- Smart country picker with search functionality
- Automatic country detection based on WordPress language
- Automatic leading zero removal from phone numbers
- WooCommerce shop page support
- Better mobile responsiveness

**New in 0.8.0**
- Country picker with 150+ countries and flag emojis
- Search functionality for easy country selection
- Automatic country detection from WordPress locale
- Smart phone number formatting (removes leading zeros)
- Improved admin UI with better organization

**New in 0.7.0**
- WordPress Color Picker integration for button color
- Separate mobile button size control
- Hide label on desktop option for cleaner design
- Improved button shape (perfectly round when label hidden)
- Better responsive design controls

== Installation ==
1. Upload `tap-chat` to `/wp-content/plugins/`.
2. Activate the plugin via *Plugins → Installed Plugins*.
3. Go to *Settings → Tap Chat* and configure:
   - Select your country from the dropdown (auto-detected from your WordPress language)
   - Enter your phone number (without country code or leading zero)
   - Customize button appearance and behavior
   - Optionally configure visibility settings to show/hide on specific pages

== Screenshots ==
1. Floating chat button on the site (front-end).
2. General settings page with country picker.
3. Visibility settings with page selector.
4. Mobile responsive button.

== Frequently Asked Questions ==

= Does it collect personal data? =
No. The plugin only renders a link to WhatsApp. It doesn't collect, store, or transmit any personal data.

= Will it slow down my site? =
No. It loads a tiny CSS/JS (~3KB total) with no external dependencies.

= Can I have different button sizes for mobile and desktop? =
Yes! Version 0.7.0+ includes separate size controls for desktop and mobile devices.

= Can I hide the button label and show only the icon? =
Yes! You can hide the label on mobile, desktop, or both for a cleaner icon-only appearance.

= How do I control where the button appears? =
Go to Settings → Tap Chat → Visibility Settings. You can:
- Show button ONLY on specific pages (useful for landing pages)
- Hide button on specific pages (useful for checkout/thank you pages)
- Use both options together for fine-grained control

= Does it work with WooCommerce? =
Yes! The plugin is fully compatible with WooCommerce, including the Shop page and product pages.

= Can I use it with page builders? =
Yes! Works with Elementor, Gutenberg, and other page builders.

= How do I format the phone number? =
1. Select your country from the dropdown (searches by name or code)
2. Enter your phone number WITHOUT the country code or leading zero
3. Examples: For Germany +49 1234567890, just enter: 1234567890

= Can I have multiple buttons with different numbers? =
Yes! Use the shortcode: `[tapchat phone="49123456789" message="Sales Team" label="Contact Sales"]`

= Does it support RTL languages? =
Yes! The plugin fully supports right-to-left languages like Arabic, Hebrew, and Persian.

== Changelog ==

= 0.9.0 =
- **New:** Advanced visibility controls - show button ONLY on specific pages
- **New:** Hide button on specific pages (great for checkout/thank you pages)
- **New:** Smart country picker with search functionality (150+ countries)
- **New:** Automatic country detection based on WordPress locale
- **New:** Automatic leading zero removal from phone numbers
- **New:** WooCommerce shop page support in visibility settings
- **New:** Page/post search in visibility selector
- **Improvement:** Better UX with checkbox-based visibility controls
- **Improvement:** Clear descriptions and visual indicators for each option
- **Improvement:** Both visibility options can work together
- **Fix:** Shop page now correctly respects visibility settings

= 0.8.0 =
- feat: Country picker with flags and search
- feat: Automatic country detection from WordPress locale
- feat: Smart phone number formatting (removes leading zeros)
- improvement: Better admin UI organization
- improvement: Migration system for existing phone numbers

= 0.7.0 =
- feat: WordPress Color Picker for button color selection
- feat: Separate mobile button size control
- feat: Hide label on desktop option (icon-only mode)
- improvement: Perfectly round button shape when label is hidden
- improvement: Better responsive design controls

= 0.6.1 =
- i18n/compat: Text Domain set to `tap-chat`, removed `load_plugin_textdomain()` per WP 4.6+, update "Tested up to" to 6.8

= 0.1.5 =
- fix: set Text Domain to plugin slug, remove `load_plugin_textdomain()` (per WP 4.6+ rules), update "Tested up to" to 6.8

= 0.1.4 =
- fix: properly save checkbox settings (enable/disable floating button, hide label on mobile)

= 0.1.3 =
- chore: add license header and uninstall.php to clean options

= 0.1.2 =
- feat: RTL-aware layout and explicit icon/label order
- feat: setting to hide label on small screens
- feat: setting to append page title & URL into message

= 0.1.1 =
- fix: replace WhatsApp icon with precise glyph (SVG)
- chore: bump version

= 0.1.0 =
- First public MVP.

== Upgrade Notice ==

= 0.9.0 =
Major update: Advanced page visibility controls, smart country picker, and better UX. Recommended for all users.

= 0.8.0 =
New country picker with flags and automatic detection. Easier phone number setup.

= 0.7.0 =
New features: WordPress Color Picker, separate mobile/desktop controls, and improved button design.

= 0.6.1 =
i18n header & compatibility updates.

== Privacy Policy ==

Tap Chat does not collect, store, or transmit any personal data. The plugin:
- Does not use cookies
- Does not track users
- Does not send data to external servers
- Only creates a link to WhatsApp with the provided phone number

When users click the button, they are redirected to WhatsApp (wa.me), which is governed by WhatsApp's privacy policy.

== Support ==

For support, feature requests, or bug reports:
- WordPress.org support forum: [Plugin Support](https://wordpress.org/support/plugin/tap-chat/)
- GitHub: Contact via WordPress.org profile

== Author ==
- Author: iruserwp9
- Profile: https://profiles.wordpress.org/iruserwp9/

== Credits ==
- WhatsApp icon: Official WhatsApp brand assets
- Country flags: Unicode emoji flags
- Inspired by the need for a lightweight, privacy-focused chat solution