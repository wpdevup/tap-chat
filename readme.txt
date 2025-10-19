=== Tap Chat ===
Contributors: iruserwp9
Tags: whatsapp, chat, click to chat, support, business hours
Requires at least: 5.8
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Lightweight WhatsApp chat button with bubble styles, working hours, and page controls. GDPR-friendly, no tracking.

== Description ==
Tap Chat adds a beautiful floating WhatsApp button with advanced features. Set business hours, show welcome messages with multiple styles, control visibility, and customize appearance - all while keeping your site fast and privacy-friendly.

**Key Features:**
- **Welcome Bubble with Styles** - Choose Modern or Simple bubble styles
- **Bubble Position Control** - Top or Side positioning (Simple style)
- **Working Hours Management** - Show button only during business hours
- **Smart Country Selector** - 150+ countries with flags and search
- **Page Visibility Controls** - Show/hide on specific pages
- **Full Customization** - Colors, sizes, labels, positions
- **Timezone Support** - Set your business timezone
- **Offline Mode** - Custom message when unavailable
- **WooCommerce Compatible** - Works with shop and product pages
- **Performance First** - Only ~5KB, no external requests
- **GDPR Friendly** - No tracking or cookies

**Welcome Bubble Styles**
Choose between two distinct bubble styles:

*Modern Style:*
- Rich design with avatar display
- Agent/team name with online indicator
- Animated pulse effect
- Perfect for personalized customer support

*Simple Style:*
- Clean, minimalist design
- Message-only display
- Compact layout
- Side or Top positioning options
- Perfect for quick notifications

**Bubble Position (Simple Style Only)**
When using Simple style, choose where the bubble appears:
- **Top** - Above the WhatsApp button (default)
- **Side** - Next to the WhatsApp button (horizontal layout)

**Welcome Bubble Features**
Increase engagement by 30-40% with a friendly welcome message:
- Custom greeting text with emoji support
- Display agent/team name and avatar (Modern style)
- Two distinct visual styles
- Flexible positioning (Simple style)
- Configurable display delay (0-60 seconds)
- Click bubble to open WhatsApp instantly
- Session-based close memory
- Beautiful animations and hover effects

**Working Hours**
Perfect for customer support teams and businesses with specific hours:
- Set different hours for each day of the week
- Enable/disable specific days (e.g., close on weekends)
- Choose your business timezone
- Show custom offline message or hide button completely
- Ideal for Mon-Fri 9-5 support teams

**Visibility Controls**
Control exactly where your button appears:
- Show ONLY on specific pages (landing pages, product pages)
- Hide on specific pages (checkout, cart, thank you pages)
- Search and select pages/posts easily
- Combine both modes for precise control

**Easy Setup**
1. Select your country (auto-detected from WordPress language)
2. Enter your phone number (without country code)
3. Choose bubble style (Modern or Simple)
4. Set bubble position (Top or Side for Simple style)
5. Set your business hours and timezone
6. Configure welcome bubble
7. Customize appearance
8. Done!

**Use Cases**
- E-commerce customer support (Mon-Fri, 9-5)
- Retail stores with different weekend hours
- Service businesses with specific availability
- Global businesses with timezone support
- Landing pages with targeted contact options
- Lead generation with engaging welcome messages

== Installation ==
1. Go to **Plugins → Add New**
2. Search for "Tap Chat"
3. Click **Install Now** → **Activate**
4. Go to **Settings → Tap Chat**
5. Configure your settings and save

== Frequently Asked Questions ==

= Does it collect personal data? =
No. The plugin only renders a link to WhatsApp. It doesn't collect, store, or transmit any personal data.

= Will it slow down my site? =
No. It loads a tiny CSS/JS (~5KB total) with no external dependencies.

= What's the difference between Modern and Simple bubble styles? =
Modern style includes avatar, name, and online indicator - perfect for personalized support. Simple style is minimalist with message-only display and offers flexible positioning options.

= Can I position the bubble next to the button instead of above it? =
Yes! When using Simple style, you can choose "Side" position to display the bubble horizontally next to the WhatsApp button.

= How do I set up the welcome bubble? =
Go to Settings → Tap Chat → Welcome Bubble. Enable it, choose your style (Modern or Simple), enter your message, and configure position if using Simple style. Set the delay and save.

= What's the optimal delay for the welcome bubble? =
3-5 seconds works best for most sites. It gives users time to look around before engaging them.

= How do I set working hours? =
Go to Settings → Tap Chat → Working Hours. Enable the checkbox, select your timezone, and set hours for each day. You can disable specific days (like weekends).

= What happens outside working hours? =
You can either:
1. Hide the button completely (leave offline message empty)
2. Show a custom offline message (e.g., "Available Mon-Fri, 9 AM - 5 PM")

= Can I show the button only on specific pages? =
Yes! Go to Settings → Tap Chat → Visibility Settings and enable "Show button ONLY on specific pages". Then select your pages.

= Can I hide the button on checkout pages? =
Yes! Go to Settings → Tap Chat → Visibility Settings and enable "Hide button on specific pages". Then select checkout, cart, etc.

= Does it work with WooCommerce? =
Yes! Fully compatible with WooCommerce, including Shop page and product pages.

= How do I format the phone number? =
1. Select your country from the dropdown
2. Enter your phone number WITHOUT the country code or leading zero
3. Example: For Germany +49 1234567890, just enter: 1234567890

= Can I have different button sizes for mobile and desktop? =
Yes! The plugin includes separate size controls for desktop and mobile devices.

= Does it support different timezones? =
Yes! You can select any timezone from the dropdown. This is perfect for businesses serving global customers.

= Can I customize the welcome bubble avatar? =
Yes! In Modern style, you can provide a URL to any image. It will be displayed as a circular avatar. Leave empty to use the default WhatsApp icon.

= How does the bubble remember if I closed it? =
The plugin uses sessionStorage to remember your choice. It will show again in a new browser session or on a different page.

= Can I use it with page builders? =
Yes! Works with Elementor, Gutenberg, and other page builders.

= Can I have multiple buttons with different numbers? =
Yes! Use the shortcode: `[tapchat phone="49123456789" message="Sales Team" label="Contact Sales"]`

= Does it support RTL languages? =
Yes! The plugin fully supports right-to-left languages like Arabic, Hebrew, and Persian.

== Screenshots ==
1. Floating chat button with welcome bubble on the site (front-end)
2. General settings page with country picker
3. Welcome bubble style selector (Modern vs Simple)
4. Bubble position settings (Top vs Side for Simple style)
5. Working hours scheduler with timezone
6. Visibility settings with page selector
7. Offline mode with custom message
8. Mobile responsive design with both bubble styles

== Changelog ==

= 1.2.0 =
- **New:** Bubble style selector - Choose between Modern and Simple styles
- **New:** Bubble position control for Simple style (Top or Side)
- **New:** Side positioning - Display bubble horizontally next to button
- **Improvement:** Reduced bubble padding for more compact design
- **Improvement:** Conditional field display - Avatar and Name fields only show for Modern style
- **Improvement:** Better vertical alignment for Side positioned bubbles
- **Improvement:** Optimized bubble sizing and spacing
- **Enhancement:** Improved admin UI with style preview cards

= 1.1.2 =
- **Fix:** Welcome bubble close button repositioned to top-right corner inside bubble
- **Fix:** Close button now rotates perfectly centered on hover without shifting
- **Fix:** Welcome bubble arrow now properly visible above WhatsApp button
- **Improvement:** Better close button styling with smooth rotation animation
- **Improvement:** Optimized close button character (✕) for perfect centering
- **Improvement:** Added Arial font family for consistent close button rendering

= 1.1.1 =
- **Fix:** Improved welcome bubble close button size - larger and easier to click
- **Fix:** Better visibility of close button on all devices
- **Improvement:** Enhanced CSS for admin panel bubble preview
- **Improvement:** Updated default welcome message to be more concise

= 1.1.0 =
- **New:** Welcome bubble feature with custom greeting messages
- **New:** Agent/team name and avatar support
- **New:** Configurable display delay (0-60 seconds)
- **New:** Session-based close state memory
- **New:** Click bubble to open WhatsApp instantly
- **New:** Beautiful animations and hover effects
- **Improvement:** Better user engagement (30-40% increase)
- **Improvement:** Enhanced mobile experience
- **Improvement:** Smooth fade-in animations

= 1.0.0 =
- **New:** Working hours management for each day of the week
- **New:** Timezone support for global businesses
- **New:** Offline mode with custom message
- **New:** Day-specific scheduling (enable/disable individual days)
- **Improvement:** Better admin UI organization
- **Improvement:** Enhanced settings structure
- **Improvement:** Comprehensive documentation

= 0.9.0 =
- **New:** Advanced visibility controls - show button ONLY on specific pages
- **New:** Hide button on specific pages (great for checkout/thank you pages)
- **New:** Smart country picker with search functionality (150+ countries)
- **New:** Automatic country detection based on WordPress locale
- **New:** Automatic leading zero removal from phone numbers
- **New:** WooCommerce shop page support in visibility settings
- **New:** Page/post search in visibility selector
- **Improvement:** Better UX with checkbox-based visibility controls
- **Improvement:** Clear descriptions and visual indicators
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
- i18n/compat: Text Domain set to `tap-chat`, update "Tested up to" to 6.8

== Upgrade Notice ==

= 1.2.0 =
Major update: Choose between Modern and Simple bubble styles with flexible positioning options. Simple style now supports Side positioning for horizontal layout. Highly recommended for all users.

= 1.1.2 =
Bug fix release for welcome bubble positioning and close button behavior. Recommended update for all users.

= 1.1.1 =
Minor fix for welcome bubble close button - larger and easier to click. Recommended update.

= 1.1.0 =
Major feature update: Welcome bubble to boost engagement by 30-40%. New greeting messages, avatars, and smart animations. Highly recommended for all users.

= 1.0.0 =
Major feature update: Working hours management with timezone support. Perfect for businesses with specific availability. Highly recommended for all users.

= 0.9.0 =
Advanced page visibility controls, smart country picker, and better UX. Recommended for all users.

= 0.8.0 =
New country picker with flags and automatic detection. Easier phone number setup.

= 0.7.0 =
New features: WordPress Color Picker, separate mobile/desktop controls, and improved button design.

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

== Author ==
- Author: iruserwp9
- Profile: https://profiles.wordpress.org/iruserwp9/

== Credits ==
- WhatsApp icon: Official WhatsApp brand assets
- Country flags: Unicode emoji flags
- Timezone support: WordPress built-in timezone functions