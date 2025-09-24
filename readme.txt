=== Tap Chat ===
Contributors: iruserwp9
Tags: whatsapp, chat, click to chat, support, customer support
Requires at least: 5.8
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 0.6.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Lightweight click-to-chat button for WhatsApp. Floating action button + shortcode. GDPR-friendly, no tracking.
== Description ==
Tap Chat adds a beautiful floating button that opens WhatsApp chat in one click. It’s intentionally simple and performance-first: tiny CSS/JS (≈3 KB), no external requests, no trackers, and no database bloat—so it won’t slow your site. Use the global floating button or insert a button anywhere with the `[tapchat]` shortcode.

**Features:**
- Floating button site-wide (position left/right).
- Custom phone (international format), default message, label, size, and color.
- Shortcode: `[tapchat phone="49123456789" message="Hello" label="Chat now"]`

**New in 0.1.2**
- RTL-aware layout (icon/label order)
- Option: hide label on mobile (<480px)
- Option: append current page title & URL to the default message

== Installation ==
1. Upload `chatly` to `/wp-content/plugins/`.
2. Activate the plugin via *Plugins → Installed Plugins*.
3. Go to *Settings → Tap Chat* and set your phone number.

== Screenshots ==
1. Floating chat button on the site (front-end).
2. Settings page (Tap Chat options in the dashboard).

== Frequently Asked Questions ==
= Does it collect personal data? =
No. The plugin only renders a link to WhatsApp.

= Will it slow down my site? =
It loads a tiny CSS/JS (~3KB).

== Changelog ==
= 0.6.1 =
- i18n/compat: Text Domain set to `chatly`, removed `load_plugin_textdomain()` per WP 4.6+, update "Tested up to" to 6.8

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
0.6.1 – i18n header & compatibility updates.


== Author ==
- Author: iruserwp9
- Profile: https://profiles.wordpress.org/iruserwp9/
