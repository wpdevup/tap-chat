# Changelog

All notable changes to Tap Chat will be documented in this file.

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

- 1.6.0 - Improved admin UX with JS tabs, dynamic bubble positioning
- 1.5.0 - Custom Icon Upload feature
- 1.4.0 - Smart Triggers system
- 1.3.0 - Welcome Bubble feature
- 1.2.0 - Business Hours and Page Visibility
- 1.1.0 - Country Selector and mobile optimization
- 1.0.0 - Initial release

---

[1.6.0]: https://github.com/wpdevup/tap-chat/releases/tag/1.6.0
[1.5.0]: https://github.com/wpdevup/tap-chat/releases/tag/1.5.0
[1.4.0]: https://github.com/wpdevup/tap-chat/releases/tag/1.4.0
[1.3.0]: https://github.com/wpdevup/tap-chat/releases/tag/1.3.0
[1.2.0]: https://github.com/wpdevup/tap-chat/releases/tag/1.2.0
[1.1.0]: https://github.com/wpdevup/tap-chat/releases/tag/1.1.0
[1.0.0]: https://github.com/wpdevup/tap-chat/releases/tag/1.0.0
