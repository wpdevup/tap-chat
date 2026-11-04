# Changelog

All notable changes to Tap Chat will be documented in this file.

## [2.3.0] - 2026-11-04

More control over where the button appears and what the first message says.

### Added
- **Hide by content type** (Settings → Tap Chat → Visibility): hide the button on every post, product or other public post type in one step, including items published later
- Hide the button on archives and special pages: front page, blog page, category, tag and other taxonomy archives, post type archives (such as the Shop page), author and date archives, search results and the 404 page
- Exceptions list to keep the button on chosen pages or posts even when their type is hidden
- "Select all" and "Clear" buttons in the page pickers; selected items are listed first
- **WooCommerce product message**: on single product pages the chat starts with the product named, using `{PRODUCT}`, `{PRICE}`, `{SKU}` and `{PRODUCT_URL}` (General → Product pages). The variables also work in the default and agent messages
- **Per-page settings in the editor**: a "Tap Chat" box on posts, pages and products to hide the button, always show it, or set a message for that page only
- **Holidays and closed dates** under Working Hours: single dates or ranges, optionally repeating every year; on those dates the button shows the offline state
- **Device visibility**: all devices, phones only, or tablets and desktops only. Applied with CSS so it works with page caching; hidden buttons are not counted as views
- Multiple Agents: the button turns into a close (×) button while the picker is open, with a smooth rotating transition both ways

### Improved
- The unread badge clears once the agent picker has been opened
- The close cross stays white whatever hover colour the theme uses
- No tap highlight flash on touch devices

### Developer
- New filter `tap_chat_meta_box_post_types` to choose which post types get the editor box
- Per-page choices are stored in the `_tap_chat_visibility` and `_tap_chat_message` post meta and removed on uninstall
- Existing settings are unchanged; every new option is off by default

## [2.2.0] - 2026-10-08

A redesigned settings screen: calmer, clearer, and easier to scan.

### Added
- Redesigned admin: one screen with a side navigation, card sections, switches and option cards
- **Live preview** of the button, the agent picker and the welcome bubble on desktop and mobile
- Notices from other plugins are hidden on the Tap Chat screen (filter: `tap_chat_quiet_admin_notices`)
- Admin follows the WordPress admin colour scheme and supports right-to-left languages
- You stay on the section you were editing after saving; section links open directly
- Filter `tap_chat_suppress_button` to hide the floating button on any view

### Improved
- Analytics report, dashboard widget and notices share the same visual style
- Analytics settings moved to their own "Tracking" section

### Fixed
- Welcome-bubble avatar no longer falls back to the Custom Icon
- Modern welcome bubble avatar position on right-to-left sites
- Button no longer appears inside block widget editor previews or counts them as views
- Opening the agent picker with a mouse no longer highlights the first agent with the theme's focus style
- Times of a day switched off under Working Hours are kept when saving

### Developer
- Admin markup without inline styles, rendered by dedicated view classes; option format unchanged

## [2.1.0] - 2026-09-13

### Added
- **Multiple Agents** mode: up to 3 team members, each with their own WhatsApp number or link, avatar, role and pre-filled message
- Per-agent online / offline indicator, panel title and subtitle, drag-to-reorder
- Agent name forwarded to GA4 / Tag Manager on chat-click events
- Setup notice after activation that links to the settings

### Improved
- Button label hidden on desktop by default for new installs
- Existing single-button setups are untouched

### Fixed
- RTL "Side" welcome bubble position
- Welcome-bubble close button layout with themes that reset buttons on RTL sites

### Developer
- REST inputs sanitized; direct analytics-table queries documented for Plugin Check

## [2.0.0] - 2026-09-05

### Added
- **Built-in, privacy-first analytics dashboard**: Total / Today / This Week / This Month clicks, change vs the previous period, top pages, devices, 7 / 30 / 90-day range
- Optional traffic and CTR tracking with cookieless unique visitors
- WordPress dashboard widget with a 14-day sparkline
- Exclude logged-in users, data retention with daily pruning, and a reset action

### Performance
- Non-blocking beacon and a compact daily-aggregated table; cached dashboard widget figures

## [1.9.0] - 2026-07-13

### Added
- GA4 / Google Tag Manager events for chat clicks (`tapchat_click`) and welcome-bubble impressions (`tapchat_bubble_shown`)

## [1.8.2] - 2026-07-13

### Fixed
- `_load_textdomain_just_in_time` notice

## [1.8.1] - 2026-07-13

### Fixed
- Plugin name update

## [1.8.0] - 2026-07-13

### Added
- Button animations: fade in, slide in, bounce, ring/shake and "New message" badge with a pulsing ring (disabled for reduced motion)
- Review request notice (filter: `tap_chat_review_delay_days`)

### Changed
- Plugin name

### Fixed
- Invalid "Tested up to" header

## [1.7.0] - 2026-07-05

### Added
- Phone / Custom-link toggle to use any URL (Telegram, Messenger, a contact page)
- `{TITLE}`, `{TAGLINE}` and `{URL}` variables in the default message

## [1.6.0] - 2025-11-27

### Improved
- Admin settings now use JavaScript tabs without page refresh
- All settings from all tabs save together in one submission
- Dynamic bubble positioning - automatically adjusts to icon size changes
- Better responsive behavior for welcome bubble on all screen sizes

### Fixed
- Bubble alignment issues when changing icon sizes

### Performance
- Smoother admin experience with no page reloads between tabs

## [1.5.0] - 2025-11-25

### Added
- **Custom Icon Upload**: Replace WhatsApp icon with your own brand logo or custom image
- WordPress Media Library integration for easy icon selection
- Perfect circular icon display with automatic sizing
- Custom icon support in floating button, offline button, and welcome bubble avatar
- Icon preview in admin settings panel
- "Use Default" button to restore WhatsApp icon

### Improved
- Enhanced icon display with responsive sizing across all devices
- Better CSS styling for custom icons with border-radius and object-fit
- Separate icon handling for SVG vs IMG elements
- Mobile-optimized icon rendering

### Fixed
- Icon sizing issues on mobile devices
- Border spacing around custom icons in hide-label mode
- Icon display inconsistencies across different themes

### Performance
- Optimized icon rendering with efficient CSS
- Reduced layout shifts during icon loading

## [1.4.0] - 2025-11-21

### Added
- **Smart Triggers System** for welcome bubble display control
  - Time on Page trigger (enabled by default at 3 seconds)
  - Scroll Depth trigger (show after % scrolled)
  - Exit Intent trigger (show when leaving page)
  - Idle Detection trigger (show after inactivity)
- Multiple trigger combination support

### Improved
- Simplified trigger configuration interface
- Better default settings for new installations
- Enhanced UX with Time on Page as recommended trigger
- Optimized trigger detection algorithms

### Fixed
- Removed duplicate Display Delay field
- Various minor bug fixes and stability improvements

## [1.3.0] - 2025-08-15

### Added
- **Welcome Bubble Feature** with two styles (Modern & Simple)
- Bubble customization options (message, name, avatar)
- Welcome bubble animations and smooth interactions
- Session-based bubble display control (won't annoy visitors)
- Avatar image upload for Modern style

### Improved
- Better mobile experience with touch-optimized controls
- Enhanced admin UI for bubble settings

### Fixed
- Avatar upload functionality issues
- Bubble positioning on various themes

### Performance
- Optimized CSS animations for smooth rendering

## [1.2.0] - 2025-06-10

### Added
- **Business Hours Feature** with timezone support
- Offline message option for outside business hours
- Page visibility controls (show/hide on specific pages)
- Support for all post types and WooCommerce pages
- Tabbed admin interface for better organization

### Improved
- Better settings organization with 5 tabs (General, Bubble, Hours, Visibility, Advanced)
- Enhanced admin UI with clearer sections
- More intuitive working hours configuration

### Fixed
- Various compatibility issues with themes and page builders
- Settings save conflicts between tabs

## [1.1.0] - 2025-04-20

### Added
- **Country Selector** with 150+ countries and flag emojis
- Separate mobile and desktop size controls
- Hide label options for mobile/desktop independently
- Page context appending option (auto-add page info to message)

### Improved
- Phone number validation with country code support
- Better URL encoding for messages with special characters
- Enhanced international phone number handling

### Fixed
- Phone number format issues with different country codes
- Label display issues on certain mobile devices

## [1.0.0] - 2025-03-01

### Added
- Initial release
- Floating WhatsApp chat button
- Customizable button colors, sizes, and positions
- Pre-filled message text
- Shortcode support `[tapchat]`
- Left/right positioning options
- Hide/show label on mobile
- Translation ready with .pot file
- RTL language support
- GDPR compliant (no tracking, no cookies)
- Mobile responsive design

---

## Version History

- 2.3.0 - Hide by content type or device, WooCommerce product message, per-page settings, holidays, close button for the agent picker
- 2.2.0 - Redesigned admin with live preview
- 2.1.0 - Multiple agents
- 2.0.0 - Built-in privacy-first analytics
- 1.9.0 - GA4 / Tag Manager events
- 1.8.0 - Button animations and review notice
- 1.7.0 - Custom links and message variables
- 1.6.0 - Improved admin UX with JS tabs, dynamic bubble positioning
- 1.5.0 - Custom Icon Upload feature
- 1.4.0 - Smart Triggers system
- 1.3.0 - Welcome Bubble feature
- 1.2.0 - Business Hours and Page Visibility
- 1.1.0 - Country Selector and mobile optimization
- 1.0.0 - Initial release

---

[2.3.0]: https://github.com/wpdevup/tap-chat/releases/tag/2.3.0
[2.2.0]: https://github.com/wpdevup/tap-chat/releases/tag/2.2.0
[1.9.0]: https://github.com/wpdevup/tap-chat/releases/tag/1.9.0
[1.8.2]: https://github.com/wpdevup/tap-chat/releases/tag/1.8.2
[1.7.0]: https://github.com/wpdevup/tap-chat/releases/tag/1.7.0
