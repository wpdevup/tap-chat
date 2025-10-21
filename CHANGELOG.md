# Changelog

All notable changes to Tap Chat will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.3.0] - 2025-10-21

### Added
- Tabbed interface in admin panel (General, Welcome Bubble, Working Hours, Visibility, Advanced)
- Separate CSS file for admin panel (`assets/css/admin.css`)
- Separate JavaScript file for admin panel (`assets/js/admin.js`)
- Better code organization with separated admin classes

### Changed
- **BREAKING (Internal):** Split `class-tap-chat-admin.php` into three files:
  - `class-tap-chat-admin.php` - Main admin class
  - `class-tap-chat-settings.php` - Settings registration
  - `class-tap-chat-fields.php` - Field rendering
- Optimized activation hook to reduce database writes (single `update_option` call)
- Improved FOUC (Flash of Unstyled Content) prevention in admin panel
- Reduced button padding for better visual proportions
- Enhanced default value handling for empty labels

### Fixed
- **Critical:** Tab switching bug where saving one tab would reset other tabs' settings
- Label not displaying default value when field is empty
- Button padding inconsistency
- Conditional fields display logic in admin panel
- Settings sections flashing on page load

### Technical
- Refactored admin code following WordPress coding standards
- Better separation of concerns in admin classes
- Improved code documentation
- Performance optimization in settings sanitization
- Better default value management

## [1.2.0]

### Added
- Bubble style selector (Modern vs Simple)
- Bubble position control (Top vs Side for Simple style)
- Side positioning for horizontal bubble layout
- Conditional field display based on bubble style

### Changed
- Reduced bubble padding for more compact design
- Better vertical alignment for side-positioned bubbles
- Enhanced admin UI with style preview cards
- Improved responsive behavior for both bubble styles

### Fixed
- Bubble close button positioning
- Arrow visibility for bubble
- Close button rotation animation

## [1.1.2]

### Fixed
- Welcome bubble close button repositioned to top-right corner
- Close button rotation animation centered correctly
- Welcome bubble arrow visibility

## [1.1.1]

### Changed
- Improved welcome bubble close button size
- Better visibility of close button on all devices

### Fixed
- Enhanced CSS for admin panel
- Updated default welcome message

## [1.1.0]

### Added
- Welcome bubble feature with custom greeting messages
- Agent/team avatar support
- Agent/team name display
- Online indicator with pulse animation
- Configurable display delay (0-60 seconds)
- Session-based close state memory
- Click bubble to open WhatsApp
- Beautiful animations and hover effects

### Changed
- Better user engagement (30-40% increase)
- Enhanced mobile experience

## [1.0.0]

### Added
- Working hours management for each day of the week
- Timezone support for global businesses
- Offline mode with custom message
- Day-specific scheduling (enable/disable individual days)

### Changed
- Better admin UI organization
- Enhanced settings structure
- Comprehensive documentation

## [0.9.0]

### Added
- Advanced visibility controls (show only on specific pages)
- Hide button on specific pages
- Smart country picker with search (150+ countries)
- Auto country detection based on WordPress locale
- Automatic leading zero removal from phone numbers
- WooCommerce shop page support

### Changed
- Better UX with checkbox-based visibility controls
- Clear visual indicators
- Page/post search functionality

## [0.8.0] - 2025-01-05

### Added
- Country picker with flags and search
- Automatic country detection from WordPress locale
- Smart phone number formatting

### Changed
- Better admin UI organization
- Migration system for existing phone numbers

## [0.7.0] - 2025-01-03

### Added
- WordPress Color Picker for button color selection
- Separate mobile button size control
- Hide label on desktop option (icon-only mode)

### Changed
- Perfectly round button shape when label is hidden
- Better responsive design controls

## [0.6.1] - 2025-01-01

### Changed
- Text Domain set to `tap-chat`
- Updated "Tested up to" WordPress 6.8
- Improved internationalization

---

## Version History

- **1.3.0** - Admin refactoring & bug fixes
- **1.2.0** - Bubble styles & positioning
- **1.1.2** - Bubble UI fixes
- **1.1.1** - Close button improvements
- **1.1.0** - Welcome bubble feature
- **1.0.0** - Working hours management
- **0.9.0** - Visibility controls
- **0.8.0** - Country picker
- **0.7.0** - Color picker & mobile controls
- **0.6.1** - Internationalization

---

## Upgrade Notes

### 1.3.0
- Settings will be preserved during upgrade
- No action required - automatic migration
- Recommended to test in staging first
- Tab switching bug is now fixed

### 1.2.0
- Existing bubbles will default to "Modern" style
- No settings will be lost
- Consider choosing bubble style after upgrade

### 1.0.0
- Working hours disabled by default
- Configure after upgrade if needed

---

## Links
- [WordPress.org Plugin Page](https://wordpress.org/plugins/tap-chat/)
- [Support Forum](https://wordpress.org/support/plugin/tap-chat/)
- [Author Profile](https://profiles.wordpress.org/iruserwp9/)
