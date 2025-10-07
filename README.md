# Tap Chat - WhatsApp Click to Chat for WordPress

A lightweight, privacy-focused WhatsApp click-to-chat plugin for WordPress. Add a beautiful floating button or use shortcodes anywhere on your site.

## ✨ Features

### 🕐 Working Hours Management
- **Business Hours Scheduler** - Set different hours for each day of the week
- **Timezone Support** - Choose your business timezone
- **Offline Mode** - Show custom message or hide button outside working hours
- **Day-Specific Control** - Enable/disable specific days independently
- **Flexible Scheduling** - Perfect for customer support teams

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

### Working Hours Setup
1. Navigate to **Settings → Tap Chat → Working Hours**
2. Enable **Working Hours** checkbox
3. Select your business timezone
4. Set hours for each day of the week
5. Disable days you're closed (weekends, holidays)
6. Optionally add an offline message
7. Save changes

**Working Hours Options:**
- **Show button only during business hours** - Button appears during set times
- **Hide button completely when offline** - Leave offline message empty
- **Show offline message** - Display custom message when closed

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

### Working Hours Examples

#### Customer Support (Mon-Fri, 9-5)
```
Monday-Friday: 09:00 - 17:00 (Enabled)
Saturday-Sunday: Closed (Disabled)
Offline Message: "Our support team is available Monday-Friday, 9 AM - 5 PM"
```

#### Retail Store (7 Days, Different Hours)
```
Monday-Friday: 10:00 - 20:00
Saturday: 10:00 - 18:00
Sunday: 12:00 - 17:00
Timezone: Europe/Berlin
```

#### E-commerce with 24/7 Chat
```
All days: 00:00 - 23:59
OR disable working hours completely
```

### Use Cases

#### E-commerce Store
```
✓ Show on: Product pages, Shop page
✗ Hide on: Checkout, Thank you page, Cart
⏰ Hours: Mon-Sat 9 AM - 6 PM
```

#### Service Business
```
✓ Show on: Services page, Contact page, Homepage
✗ Hide on: Privacy Policy, Terms of Service
⏰ Hours: Mon-Fri 8 AM - 5 PM
```

#### Landing Page
```
✓ Show ONLY on: Specific landing page
✗ Hide on: (not needed)
⏰ Hours: Always available
```

## 🎯 Visibility Control Logic

| Mode | Behavior |
|------|----------|
| None enabled | Show everywhere |
| Show Only | Only on selected pages |
| Hide On | Everywhere except selected pages |
| Both enabled | Show only on selected pages, excluding hide list |
| Working Hours | Show only during business hours |

## 🔄 Changelog

### Version 1.0.0
#### 🎉 New Features
- **Working Hours Management** - Set business hours for each day
- **Timezone Support** - Choose your business timezone
- **Offline Mode** - Custom message or hide button when closed
- **Day-Specific Scheduling** - Enable/disable individual days

#### 🎨 Improvements
- Better admin UI organization
- Enhanced settings structure
- Improved documentation

### Version 0.9.0
#### 🎉 New Features
- Advanced visibility controls with show/hide options
- Smart country picker with 150+ countries and flags
- Real-time country search functionality
- Automatic country detection from WordPress locale
- Automatic leading zero removal from phone numbers
- WooCommerce shop page support
- Page/post search in visibility selector

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

## 💡 Pro Tips

### For Best Results
1. **Set realistic working hours** - Match your actual availability
2. **Use timezone correctly** - Ensure it matches your business location
3. **Test offline message** - Make sure it's helpful and professional
4. **Combine with visibility rules** - Hide on irrelevant pages
5. **Update phone number** - Make sure it's active and monitored

### Common Use Cases
- **E-commerce Support** - Quick customer service during business hours
- **Appointment Booking** - Instant scheduling within working hours
- **Sales Inquiries** - Direct communication when team is available
- **Technical Support** - Real-time help during support hours
- **Lead Generation** - Convert visitors during peak hours

### Working Hours Best Practices
- **Set buffer time** - End 15 minutes before actual closing
- **Consider time zones** - Use customer's timezone for global businesses
- **Holiday schedules** - Temporarily disable on holidays
- **Lunch breaks** - Consider splitting into morning/afternoon shifts
- **Auto-responders** - Use offline message to set expectations

---

**Made with ❤️ for the WordPress Community**

*If you find this plugin helpful, please consider [rating it on WordPress.org](https://wordpress.org/support/plugin/tap-chat/reviews/) ⭐⭐⭐⭐⭐*
