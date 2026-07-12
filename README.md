# Tap Chat - WordPress Click-to-Chat Plugin

**Version:** 1.8.0 | **Requires:** WordPress 5.8+ | **PHP:** 7.4+

[![WordPress Plugin Version](https://img.shields.io/wordpress/plugin/v/tap-chat.svg)](https://wordpress.org/plugins/tap-chat/)
[![WordPress Plugin Downloads](https://img.shields.io/wordpress/plugin/dt/tap-chat.svg)](https://wordpress.org/plugins/tap-chat/)
[![License](https://img.shields.io/badge/license-GPL--2.0%2B-blue.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

Lightweight WordPress plugin for adding a professional floating chat button to your website. Connect with visitors instantly through popular messaging platforms with zero configuration complexity.

## 🚀 Features

### Core Features
- ✨ **Floating Chat Button** - Beautiful, customizable button that sticks to your site
- 🎨 **Custom Icon Upload** - Replace WhatsApp icon with your own brand logo or custom image
- 💬 **Welcome Bubble** - Friendly greeting message with Modern & Simple styles
- 🎯 **Smart Triggers** - Show bubble at the perfect moment based on visitor behavior
- 🌍 **Country Selector** - 150+ country codes with flags
- ⏰ **Working Hours** - Display button only during business hours
- 👁️ **Page Visibility** - Control where button appears
- 🎨 **Fully Customizable** - Colors, sizes, positions, labels
- 📱 **Mobile Optimized** - Separate mobile/desktop configurations
- 🔒 **GDPR Compliant** - Zero tracking, no cookies
- 🌐 **Translation Ready** - Fully translatable with .pot file

### Custom Icon Upload (New in 1.5.0)

Replace the default WhatsApp icon with your own branding:

- 🖼️ **Upload Your Logo** - Use WordPress Media Library to select any image
- ⭕ **Perfect Circle Display** - Icons automatically sized and styled as perfect circles
- 📱 **Fully Responsive** - Works perfectly on all devices and screen sizes
- 🔄 **Easy Fallback** - Restore default WhatsApp icon with one click
- 🎯 **Multiple Uses** - Works in floating button, offline button, and bubble avatar
- 📏 **Format Support** - PNG, JPG, SVG formats supported

**How it works:**
1. Go to General Settings
2. Click "Choose Icon" button
3. Select image from WordPress Media Library
4. Icon automatically displays as perfect circle
5. Click "Use Default" to restore WhatsApp icon anytime

### Smart Triggers (New in 1.4.0)

Control when the welcome bubble appears based on visitor behavior:

- ⏱️ **Time on Page** - After visitor stays for X seconds (enabled by default, recommended)
- 📜 **Scroll Depth** - When visitor scrolls to X% of page
- 🚪 **Exit Intent** - When visitor moves mouse to close tab
- 💤 **Idle Detection** - When visitor is inactive for X seconds

**How it works:** When multiple triggers are enabled, the bubble shows when ANY condition is met first. This gives you flexible control over user engagement.

### Welcome Bubble Styles

**Modern Style:**
- Avatar image
- Agent/team name
- Online status indicator
- Positioned above button

**Simple Style:**
- Clean, minimal design
- Message only
- Positioned above or beside button
- Faster configuration

## 📦 Installation

### From WordPress.org

1. Go to **WordPress Dashboard → Plugins → Add New**
2. Search for "**Tap Chat**"
3. Click **Install Now** and then **Activate**
4. Go to **Settings → Tap Chat** to configure

### Manual Installation

1. Download the latest release from [WordPress.org](https://wordpress.org/plugins/tap-chat/)
2. Upload to `/wp-content/plugins/` directory
3. Activate through the **Plugins** menu in WordPress
4. Configure via **Settings → Tap Chat**

### For Developers
```bash
# Clone the repository
git clone https://github.com/wpdevup/tap-chat.git

# Navigate to plugin directory
cd tap-chat

# Link to WordPress plugins directory
ln -s $(pwd) /path/to/wordpress/wp-content/plugins/tap-chat
```

## ⚙️ Configuration

### Basic Setup

1. **General Tab:**
   - Select country code
   - Enter phone number (without country code)
   - **Upload custom icon (optional)** - Replace WhatsApp icon with your logo
   - Customize appearance (color, size, position)
   - Set default message and label

2. **Welcome Bubble Tab:**
   - Enable welcome bubble
   - Choose style (Modern or Simple)
   - Customize message, name, avatar
   - **Configure smart triggers:**
     - Time on Page (default: 3 seconds)
     - Scroll Depth (customize percentage)
     - Exit Intent (mouse leaves viewport)
     - Idle Detection (no activity timeout)

3. **Business Hours Tab:**
   - Set working hours per day
   - Choose timezone
   - Add optional offline message

4. **Visibility Tab:**
   - Show on specific pages only
   - Hide on specific pages
   - Combine rules for precise control

5. **Advanced Tab:**
   - Page context appending
   - Additional options

### Shortcode Usage

Basic shortcode:
```php
[tapchat]
```

With custom parameters:
```php
[tapchat phone="+1234567890" message="Hello!" label="Contact us"]
```

### Programmatic Usage
```php
// Get plugin instance
$tap_chat = \Tap_Chat\Plugin::instance();

// Hooks available
add_action( 'tapchat_click', 'my_custom_function' );
```

## 🎯 Smart Triggers Usage

### Best Practices

**For E-commerce Sites:**
```
Recommended: Scroll Depth + Exit Intent
- Enable Scroll Depth: 50%
- Enable Exit Intent
```

**For Service/Support Sites:**
```
Recommended: Time on Page (default)
- Enable Time on Page: 3-5 seconds
```

**For High-Traffic Blogs:**
```
Recommended: Scroll Depth + Idle
- Enable Scroll Depth: 30%
- Enable Idle Detection: 30 seconds
```

### Trigger Priority

When multiple triggers are enabled:
1. Whichever condition is met **first** will trigger the bubble
2. Once shown, bubble won't show again in the same session
3. User can manually close bubble at any time
4. Closed bubbles are remembered for the current session only

### Testing Triggers
```javascript
// Clear session storage to reset bubble
sessionStorage.clear();

// Reload page to test triggers again
location.reload();
```

## 🎨 Customization

### CSS Customization

Override default styles in your theme:
```css
/* Custom button color */
.tapchat-fab {
    --tapchat-color: #your-color !important;
}

/* Custom bubble style */
.tapchat-welcome-bubble {
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

/* Custom positioning */
.tapchat-fab.tapchat-pos-right {
    bottom: 30px;
    right: 30px;
}

/* Custom icon styling */
.tapchat-fab img.tapchat-icon {
    border-radius: 50%;
    object-fit: cover;
}
```

### JavaScript Hooks
```javascript
// Listen for button clicks
jQuery(document).on('click', '.tapchat-fab', function() {
    console.log('Tap Chat button clicked!');
});

// WordPress hook (if wp.hooks available)
wp.hooks.addAction('tapchat_click', 'myNamespace', function() {
    // Custom tracking or analytics
});
```

## 🛠️ Development

### Requirements
- WordPress 5.8 or higher
- PHP 7.4 or higher
- Modern browser with ES6 support

### File Structure
```
tap-chat/
├── assets/
│   ├── css/
│   │   ├── tapchat.css          # Frontend styles
│   │   └── admin.css            # Admin styles
│   ├── js/
│   │   ├── tapchat.js           # Frontend scripts
│   │   └── admin.js             # Admin scripts
│   └── images/
├── includes/
│   ├── class-tap-chat.php       # Main plugin class
│   └── admin/
│       ├── class-tap-chat-admin.php      # Admin controller
│       ├── class-tap-chat-settings.php   # Settings handler
│       └── class-tap-chat-fields.php     # Field renderer
├── languages/
│   └── tap-chat.pot             # Translation template
├── tap-chat.php                 # Plugin entry point
└── readme.txt                   # WordPress.org readme
```

### Coding Standards

This plugin follows:
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
- [WordPress Security Best Practices](https://developer.wordpress.org/plugins/security/)

### Building for Production
```bash
# Run WordPress coding standards check
phpcs --standard=WordPress tap-chat.php includes/

# Check for translation strings
wp i18n make-pot . languages/tap-chat.pot

# Create release package
zip -r tap-chat.zip . -x "*.git*" "node_modules/*" "*.DS_Store"
```

## 🧪 Testing

### Manual Testing Checklist

- [ ] Button displays correctly on frontend
- [ ] Custom icon uploads and displays properly
- [ ] Custom icon shows as perfect circle
- [ ] Custom icon works on mobile devices
- [ ] Welcome bubble shows with enabled triggers
- [ ] Smart triggers work as expected (Time, Scroll, Exit, Idle)
- [ ] Country selector works properly
- [ ] Phone number validation works
- [ ] Working hours logic is correct
- [ ] Visibility controls work as expected
- [ ] Mobile responsiveness is good
- [ ] Shortcode works in posts/pages
- [ ] Settings save correctly
- [ ] No JavaScript console errors
- [ ] No PHP errors in debug mode

### Browser Compatibility

Tested and working on:
- ✅ Chrome/Edge (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Mobile Safari (iOS)
- ✅ Chrome Mobile (Android)

## 🌍 Translations

Tap Chat is translation-ready! We welcome translations in all languages.

### How to Translate

1. Download [POEdit](https://poedit.net/)
2. Open `languages/tap-chat.pot`
3. Create translation for your language
4. Save as `tap-chat-{locale}.po` and `tap-chat-{locale}.mo`
5. Submit via [WordPress.org translation system](https://translate.wordpress.org/)

### Available Translations

- 🇬🇧 English (default)
- 🇩🇪 German (coming soon)
- 🇪🇸 Spanish (coming soon)
- 🇫🇷 French (coming soon)

Want to contribute a translation? [Contact us](https://wordpress.org/support/plugin/tap-chat/)!

## 📊 Performance

Tap Chat is optimized for performance:

- **Tiny Footprint:** < 15KB total (CSS + JS)
- **No External Requests:** Everything loads locally
- **Async Loading:** Scripts load asynchronously
- **No jQuery Dependency:** Vanilla JavaScript on frontend
- **Optimized CSS:** Minimal, modern CSS3
- **No Database Queries on Frontend:** Settings cached

### Performance Metrics

- ✅ GTmetrix Grade: A
- ✅ Google PageSpeed: 95+/100
- ✅ Pingdom Speed: A
- ✅ Load Time: < 50ms

## 🔒 Security

Security is our priority:

- ✅ All inputs sanitized and validated
- ✅ Nonces for all form submissions
- ✅ Capability checks for admin actions
- ✅ SQL injection prevention
- ✅ XSS attack prevention
- ✅ CSRF protection
- ✅ No external dependencies
- ✅ Regular security audits

Found a security issue? Please report privately to [WordPress.org](https://wordpress.org/support/plugin/tap-chat/)

## 🤝 Contributing

We welcome contributions! Here's how you can help:

### Ways to Contribute

1. **Code Contributions**
   - Fork the repository
   - Create a feature branch (`git checkout -b feature/amazing-feature`)
   - Commit your changes (`git commit -m 'Add amazing feature'`)
   - Push to the branch (`git push origin feature/amazing-feature`)
   - Open a Pull Request

2. **Bug Reports**
   - Use GitHub Issues
   - Provide detailed description
   - Include steps to reproduce
   - Add screenshots if applicable

3. **Feature Requests**
   - Open an issue with [FEATURE] prefix
   - Explain the use case
   - Describe expected behavior

4. **Documentation**
   - Improve README
   - Add code comments
   - Write tutorials or guides

5. **Translations**
   - Translate to your language
   - Submit via WordPress.org

### Development Setup
```bash
# Clone repository
git clone https://github.com/wpdevup/tap-chat.git
cd tap-chat

# Create development branch
git checkout -b feature/your-feature-name

# Make changes and test

# Follow WordPress coding standards
phpcs --standard=WordPress .

# Commit with clear message
git commit -m "Add: Description of your changes"
```

## 📝 Changelog

### [1.6.0] - 2025-11-27
**Improved:**
- 🚀 Admin settings now use JavaScript tabs without page refresh
- 💾 All settings from all tabs save together in one submission
- 📐 Dynamic bubble positioning - automatically adjusts to icon size changes
- 📱 Better responsive behavior for welcome bubble on all screen sizes

**Fixed:**
- Bubble alignment issues when changing icon sizes

**Performance:**
- Smoother admin experience with no page reloads between tabs

### [1.5.0] - 2025-11-25
**Added:**
- 🎨 Custom Icon Upload feature
  - Replace WhatsApp icon with your own brand logo
  - WordPress Media Library integration
  - Perfect circular icon display with automatic sizing
  - Supports PNG, JPG, SVG formats
  - Works in floating button, offline button, and bubble avatar
  - Easy fallback to default icon with "Use Default" button

**Improved:**
- Enhanced icon display with responsive sizing across all devices
- Better CSS styling for custom icons with border-radius and object-fit
- Separate icon handling for SVG vs IMG elements
- Mobile-optimized icon rendering

**Fixed:**
- Icon sizing issues on mobile devices
- Border spacing around custom icons in hide-label mode
- Icon display inconsistencies across different themes

**Performance:**
- Optimized icon rendering with efficient CSS
- Reduced layout shifts during icon loading

### [1.4.0] - 2025-11-21
**Added:**
- 🎯 Smart Triggers system for welcome bubble
  - Time on Page trigger (enabled by default, 3 seconds)
  - Scroll Depth trigger (show after X% scroll)
  - Exit Intent trigger (show on exit attempt)
  - Idle Detection trigger (show after inactivity)
- Improved trigger configuration interface

**Improved:**
- Simplified UX for trigger settings
- Better default settings for new installations (Time on Page auto-enabled)
- Enhanced performance for trigger detection algorithms
- Optimized JavaScript for multiple trigger combinations

**Fixed:**
- Removed duplicate Display Delay field
- Fixed trigger priority when multiple conditions met
- Improved session storage handling for bubble display
- Various minor bug fixes and stability improvements

### [1.3.0] - 2025-08-15
**Added:**
- Welcome Bubble feature with Modern & Simple styles
- Bubble customization options (message, name, avatar)
- Session-based display control

**Improved:**
- Better mobile experience
- Optimized CSS animations

**Fixed:**
- Avatar upload functionality

### [1.2.0] - 2025-06-10
**Added:**
- Business Hours with timezone support
- Page visibility controls
- Offline message option

**Improved:**
- Settings organization with tabs
- Enhanced admin UI

### [1.1.0] - 2025-04-20
**Added:**
- Country selector (150+ countries)
- Separate mobile/desktop controls
- Page context appending

**Improved:**
- Phone number validation
- URL encoding for messages

### [1.0.0] - 2025-03-01
- Initial release

## 📜 License

This project is licensed under the GPL v2 or later.
```
Tap Chat - WordPress Click-to-Chat Plugin
Copyright (C) 2025 iruserwp9

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## 🙏 Credits

- Icons from messaging platform brand resources
- Country flags from Unicode emoji standard
- Developed with ❤️ by [iruserwp9](https://profiles.wordpress.org/iruserwp9/)

## 📞 Support

Need help? We're here for you!

- 📖 [Documentation](https://wordpress.org/plugins/tap-chat/)
- 💬 [Support Forum](https://wordpress.org/support/plugin/tap-chat/)
- 🐛 [Report Bug](https://github.com/wpdevup/tap-chat/issues)
- ✨ [Request Feature](https://github.com/wpdevup/tap-chat/issues)
- ⭐ [Rate Plugin](https://wordpress.org/support/plugin/tap-chat/reviews/)

## 🗺️ Roadmap

### Completed ✅
- ✅ Improved Admin UX (v1.6.0)
- ✅ Custom Icon Upload (v1.5.0)
- ✅ Smart Triggers (v1.4.0)
- ✅ Welcome Bubble (v1.3.0)
- ✅ Working Hours (v1.2.0)
- ✅ Country Selector (v1.1.0)

### Coming Soon 🚀
- [ ] Multiple agents support with round-robin
- [ ] Analytics dashboard (privacy-friendly)
- [ ] More bubble styles and animations
- [ ] Pre-chat form integration
- [ ] Quick replies templates
- [ ] Team chat widget
- [ ] A/B testing for triggers
- [ ] Advanced trigger combinations
- [ ] Custom trigger scheduling

## ⭐ Show Your Support

If you find this plugin helpful, please:

- ⭐ Star this repository
- 🔄 Share with others
- ✍️ Write a review on [WordPress.org](https://wordpress.org/support/plugin/tap-chat/reviews/)
- 🐛 Report bugs and help improve

---

Made with ❤️ for the WordPress community

[WordPress.org](https://wordpress.org/plugins/tap-chat/) | [GitHub](https://github.com/yourusername/tap-chat) | [Support](https://wordpress.org/support/plugin/tap-chat/)
