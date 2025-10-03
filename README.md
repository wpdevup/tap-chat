# Tap Chat - WhatsApp Click to Chat for WordPress

A lightweight, privacy-focused WhatsApp click-to-chat plugin for WordPress. Add a beautiful floating button or use shortcodes anywhere on your site.

## ✨ Features

### 🌍 Smart Country Selector
- **150+ countries** with flag emojis
- **Real-time search** - find your country instantly
- **Auto-detection** - automatically selects country based on WordPress language
- **Smart formatting** - automatically removes leading zeros from phone numbers

### 🎯 Advanced Visibility Controls
- **Show Only Mode** - Display button only on specific pages (perfect for landing pages)
- **Hide Mode** - Hide button on specific pages (great for checkout/thank you pages)
- **Combined Control** - Use both modes together for fine-grained control
- **Page Search** - Quickly find pages/posts with built-in search
- **WooCommerce Support** - Works perfectly with Shop pages and products

### 🎨 Full Customization
- **Floating Button** - Customizable position (left/right)
- **Color Picker** - Visual color selection with WordPress Color Picker
- **Size Control** - Separate sizes for mobile and desktop
- **Label Control** - Hide/show label on mobile or desktop independently
- **Custom Messages** - Set default WhatsApp message
- **Responsive Design** - Perfect on all devices

### 🔧 Developer-Friendly
- **Shortcode Support** - `[tapchat phone="..." message="..." label="..."]`
- **RTL Compatible** - Full support for right-to-left languages
- **No External Dependencies** - Pure WordPress solution
- **Clean Code** - Following WordPress coding standards
- **Hooks & Filters** - Extensible architecture (coming soon)

### 🚀 Performance & Privacy
- **Lightweight** - ~3KB total (CSS + JS)
- **No Tracking** - Zero cookies, zero analytics
- **GDPR Friendly** - No personal data collection
- **Fast Loading** - Optimized for speed
- **No External Requests** - Everything self-contained

## 📦 Installation

### From WordPress.org
1. Go to **Plugins → Add New**
2. Search for "Tap Chat"
3. Click **Install Now** → **Activate**

### Manual Installation
1. Download the latest release from [WordPress.org](https://wordpress.org/plugins/tap-chat/)
2. Upload to `/wp-content/plugins/tap-chat/`
3. Activate via **Plugins** menu

### From GitHub
```bash
cd wp-content/plugins
git clone https://github.com/yourusername/tap-chat.git
```

## ⚙️ Configuration

### Basic Setup
1. Go to **Settings → Tap Chat**
2. Select your country from the dropdown
3. Enter your phone number (without country code or leading zero)
4. Customize appearance (color, size, label)
5. Save changes

### Advanced Visibility
1. Navigate to **Settings → Tap Chat → Visibility Settings**
2. Choose your visibility mode:
   - **Show button ONLY on specific pages** - Perfect for landing pages
   - **Hide button on specific pages** - Great for checkout pages
3. Search and select pages/posts
4. Save changes

## 📝 Usage Examples

### Floating Button (Default)
The button appears automatically on all pages after configuration.

### Shortcode Usage
```php
// Basic usage
[tapchat]

// Custom phone and message
[tapchat phone="4915012345678" message="Hi, I have a question about your product"]

// Custom label
[tapchat label="Chat with Sales Team"]

// Full customization
[tapchat phone="4915012345678" message="Hello!" label="Contact Support"]
```

### Use Cases

#### E-commerce Store
```
✓ Show on: Product pages, Shop page
✗ Hide on: Checkout, Thank you page, Cart
```

#### Service Business
```
✓ Show on: Services page, Contact page, Homepage
✗ Hide on: Privacy Policy, Terms of Service
```

#### Landing Page
```
✓ Show ONLY on: Specific landing page
✗ Hide on: (not needed)
```

## 🎯 Visibility Control Logic

| Mode | Behavior |
|------|----------|
| None enabled | Show everywhere |
| Show Only | Only on selected pages |
| Hide On | Everywhere except selected pages |
| Both enabled | Show only on selected pages, excluding hide list |

## 🔄 Changelog

### Version 0.9.0
#### 🎉 New Features
- Advanced visibility controls with show/hide options
- Smart country picker with 150+ countries and flags
- Real-time country search functionality
- Automatic country detection from WordPress locale
- Automatic leading zero removal from phone numbers
- WooCommerce shop page support
- Page/post search in visibility selector

#### 🎨 Improvements
- Better UX with checkbox-based visibility controls
- Clear descriptions and visual indicators
- Improved admin interface organization
- Better mobile responsiveness
- Enhanced error handling

#### 🐛 Bug Fixes
- Fixed shop page visibility detection
- Fixed country selection state persistence
- Improved phone number formatting

### Version 0.8.0
- Added country picker with flags
- Auto-detection from WordPress language
- Migration system for existing users

### Version 0.7.0
- WordPress Color Picker integration
- Separate mobile/desktop controls
- Icon-only mode support

[View Full Changelog](https://wordpress.org/plugins/tap-chat/#developers)

## 🛠️ Technical Details

### Requirements
- **WordPress:** 5.8 or higher
- **PHP:** 7.4 or higher
- **MySQL:** 5.6 or higher (WordPress requirement)

### Browser Support
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

### Performance Metrics
- **Total Size:** ~50KB
- **CSS:** ~2KB (minified)
- **JS:** ~1KB (minified)
- **Load Time Impact:** <50ms
- **HTTP Requests:** 0 external

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

### Development Setup
```bash
# Clone the repository
git clone https://github.com/yourusername/tap-chat.git

# Navigate to plugin directory
cd tap-chat

# Install on local WordPress
cp -r tap-chat /path/to/wordpress/wp-content/plugins/

# Activate and test
```

### Coding Standards
- Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- Use meaningful variable and function names
- Comment complex logic
- Test on multiple WordPress versions

## 🐛 Bug Reports

Found a bug? Please [open an issue](https://wordpress.org/support/plugin/tap-chat/) on WordPress.org support forum.

Include:
- WordPress version
- PHP version
- Plugin version
- Steps to reproduce
- Expected vs actual behavior

## 📄 License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details.

## 👤 Author

**iruserwp9**
- WordPress.org: [@iruserwp9](https://profiles.wordpress.org/iruserwp9/)
- Plugin URI: [https://wordpress.org/plugins/tap-chat/](https://wordpress.org/plugins/tap-chat/)

## 🙏 Acknowledgments

- WhatsApp for their excellent messaging platform
- WordPress community for continuous inspiration
- All contributors and users who provide feedback

## 📊 Stats

- **Active Installations:** Check [WordPress.org](https://wordpress.org/plugins/tap-chat/)
- **Rating:** ⭐⭐⭐⭐⭐
- **Support Threads Resolved:** High response rate
- **Last Updated:** Regular updates

## 🔗 Links

- [WordPress.org Plugin Page](https://wordpress.org/plugins/tap-chat/)
- [Support Forum](https://wordpress.org/support/plugin/tap-chat/)
- [Changelog](https://wordpress.org/plugins/tap-chat/#developers)
- [FAQ](https://wordpress.org/plugins/tap-chat/#faq)

## 💡 Pro Tips

### For Best Results
1. **Use descriptive labels** - "Chat with Sales" is better than "Chat"
2. **Set up visibility rules** - Don't show on checkout/cart pages
3. **Test on mobile** - Most WhatsApp chats happen on mobile
4. **Keep messages short** - Pre-filled messages work better when concise
5. **Update phone number** - Make sure it's active and monitored

### Common Use Cases
- **E-commerce Support** - Quick customer service
- **Appointment Booking** - Instant scheduling
- **Sales Inquiries** - Direct communication
- **Technical Support** - Real-time help
- **Lead Generation** - Convert visitors to conversations

---

**Made with ❤️ for the WordPress Community**

*If you find this plugin helpful, please consider [rating it on WordPress.org](https://wordpress.org/support/plugin/tap-chat/reviews/) ⭐⭐⭐⭐⭐*
