# Tap Chat - WhatsApp Click to Chat for WordPress

A lightweight, privacy-focused WhatsApp click-to-chat plugin for WordPress. Add a beautiful floating button with welcome bubble, working hours management, and advanced visibility controls.

## ✨ Features

### 💬 Welcome Bubble
- **Custom Greeting Messages** - Boost engagement by 30-40%
- **Agent/Team Name & Avatar** - Personalize your support team
- **Configurable Delay** - Show bubble after 0-60 seconds
- **Session Memory** - Remembers if user closed the bubble
- **Click to Chat** - Instant WhatsApp connection
- **Beautiful Animations** - Smooth fade-in effects

### 🕐 Working Hours Management
- **Business Hours Scheduler** - Set different hours for each day
- **Timezone Support** - Perfect for global businesses
- **Offline Mode** - Show custom message or hide button
- **Day-Specific Control** - Enable/disable individual days
- **Flexible Scheduling** - Ideal for Mon-Fri 9-5 teams

### 🌍 Smart Country Selector
- **150+ Countries** - With flag emojis
- **Real-time Search** - Find your country instantly
- **Auto-detection** - Based on WordPress language
- **Smart Formatting** - Removes leading zeros automatically

### 🎯 Advanced Visibility Controls
- **Show Only Mode** - Display on specific pages only
- **Hide Mode** - Hide on checkout/thank you pages
- **Combined Control** - Use both modes together
- **Page Search** - Built-in search functionality
- **WooCommerce Support** - Works with shop pages

### 🎨 Full Customization
- **Floating Button** - Customizable position (left/right)
- **Color Picker** - Visual color selection
- **Size Control** - Separate mobile and desktop sizes
- **Label Control** - Hide/show label independently
- **Custom Messages** - Set default WhatsApp message
- **Responsive Design** - Perfect on all devices

### 🔧 Developer-Friendly
- **Shortcode Support** - `[tapchat phone="..." message="..." label="..."]`
- **RTL Compatible** - Full right-to-left support
- **No Dependencies** - Pure WordPress solution
- **Clean Code** - WordPress coding standards
- **Hooks & Filters** - Extensible architecture

### 🚀 Performance & Privacy
- **Lightweight** - ~5KB total (CSS + JS)
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

### Welcome Bubble Setup
1. Navigate to **Settings → Tap Chat → Welcome Bubble**
2. Enable **Welcome Bubble** checkbox
3. Enter your greeting message (default: "Need help? Let's chat! 💬")
4. Set agent/team name (default: "Support Team")
5. Optionally add an avatar URL
6. Configure display delay (default: 3 seconds)
7. Save changes

**Welcome Bubble Features:**
- Engaging greeting message with emoji support
- Display agent/team name with online indicator
- Custom avatar or default WhatsApp icon
- Configurable delay (0-60 seconds)
- Session-based close memory
- Click bubble to open chat instantly

### Working Hours Setup
1. Navigate to **Settings → Tap Chat → Working Hours**
2. Enable **Working Hours** checkbox
3. Select your business timezone
4. Set hours for each day of the week
5. Disable days you're closed (weekends, holidays)
6. Optionally add an offline message
7. Save changes

**Working Hours Options:**
- Show button only during business hours
- Hide button completely when offline (leave message empty)
- Show offline message when closed

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

### Welcome Bubble Examples

#### E-commerce Store
```
Message: "Need help? Let's chat! 💬"
Name: "Sales Team"
Avatar: [Your team photo]
Delay: 3 seconds
```

#### Tech Support
```
Message: "Got questions? We're here! 🤓"
Name: "Tech Support"
Avatar: [Support agent photo]
Delay: 5 seconds
```

#### Service Business
```
Message: "Looking for our services? Ask us! 👋"
Name: "Customer Care"
Avatar: [Default WhatsApp icon]
Delay: 4 seconds
```

### Use Cases

#### E-commerce Store
```
✓ Show on: Product pages, Shop page
✗ Hide on: Checkout, Thank you page, Cart
⏰ Hours: Mon-Sat 9 AM - 6 PM
💬 Bubble: Enabled with product questions
```

#### Service Business
```
✓ Show on: Services page, Contact page, Homepage
✗ Hide on: Privacy Policy, Terms of Service
⏰ Hours: Mon-Fri 8 AM - 5 PM
💬 Bubble: Enabled with service inquiries
```

#### Landing Page
```
✓ Show ONLY on: Specific landing page
✗ Hide on: (not needed)
⏰ Hours: Always available
💬 Bubble: Immediate with special offer
```

## 🎯 Visibility Control Logic

| Mode | Behavior |
|------|----------|
| None enabled | Show everywhere |
| Show Only | Only on selected pages |
| Hide On | Everywhere except selected pages |
| Both enabled | Show only on selected pages, excluding hide list |
| Working Hours | Show only during business hours |
| Welcome Bubble | Appears after configured delay |

## 🔄 Changelog

### Version 1.1.2
#### 🐛 Bug Fixes
- **Fixed:** Welcome bubble close button repositioned to top-right corner inside bubble
- **Fixed:** Close button now rotates perfectly centered on hover without shifting left
- **Fixed:** Welcome bubble arrow now properly visible above WhatsApp button (not hidden behind button)

#### 🎨 Improvements
- Better close button styling with smooth 90-degree rotation animation on hover
- Optimized close button character (✕) for perfect centering
- Added Arial font family for consistent close button rendering across browsers
- Adjusted bubble bottom position for better spacing (105px desktop, 90px mobile)
### Version 1.1.1
#### 🐛 Fixes
- **Improved:** Welcome bubble close button size - larger and easier to click (32px desktop, 28px mobile)
- **Fixed:** Better visibility of close button on all devices with `!important` CSS
- **Enhanced:** CSS for admin panel bubble preview
- **Updated:** Default welcome message to be more concise: "Need help? Let's chat! 💬"

### Version 1.1.0
#### 🎉 New Features
- **Welcome Bubble** - Friendly greeting message to boost engagement by 30-40%
- **Agent/Team Name & Avatar** - Personalize your support team
- **Configurable Display Delay** - Show bubble after 0-60 seconds
- **Session-based Close Memory** - Remembers user preference
- **Click Bubble to Chat** - Instant WhatsApp connection
- **Beautiful Animations** - Smooth fade-in effects

#### 🎨 Improvements
- Better user engagement with welcome messages
- Enhanced mobile experience
- Smooth animations and hover effects

### Version 1.0.0
#### 🎉 New Features
- **Working Hours Management** - Set business hours for each day
- **Timezone Support** - Perfect for global businesses
- **Offline Mode** - Custom message when unavailable
- **Day-Specific Scheduling** - Enable/disable individual days

#### 🎨 Improvements
- Better admin UI organization
- Enhanced settings structure
- Comprehensive documentation

### Version 0.9.0
#### 🎉 New Features
- Advanced visibility controls - show button ONLY on specific pages
- Hide button on specific pages (great for checkout/thank you pages)
- Smart country picker with search functionality (150+ countries)
- Automatic country detection based on WordPress locale
- Automatic leading zero removal from phone numbers
- WooCommerce shop page support in visibility settings
- Page/post search in visibility selector

#### 🎨 Improvements
- Better UX with checkbox-based visibility controls
- Clear descriptions and visual indicators

#### 🐛 Fixes
- Shop page now correctly respects visibility settings

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
6. **Optimize welcome bubble** - Keep message short and engaging (under 15 words)
7. **Test delay timing** - 3-5 seconds works best for most sites
8. **Use custom avatar** - Personal photos increase trust by 40%

### Welcome Bubble Best Practices
- **Keep it short** - 10-15 words maximum
- **Use emojis** - Makes it more friendly and engaging
- **Be specific** - "Need help with shipping?" vs "Need help?"
- **Test timing** - Too fast is annoying, too slow is missed
- **Monitor engagement** - Check if people are clicking
- **A/B test messages** - Try different variations

### Common Use Cases
- **E-commerce Support** - Quick customer service during business hours with product-specific messages
- **Appointment Booking** - Instant scheduling within working hours
- **Sales Inquiries** - Direct communication when team is available with special offers
- **Technical Support** - Real-time help during support hours
- **Lead Generation** - Convert visitors during peak hours with engaging bubble messages

### Working Hours Best Practices
- **Set buffer time** - End 15 minutes before actual closing
- **Consider time zones** - Use customer's timezone for global businesses
- **Holiday schedules** - Temporarily disable on holidays
- **Lunch breaks** - Consider splitting into morning/afternoon shifts
- **Auto-responders** - Use offline message to set expectations

---

**Made with ❤️ for the WordPress Community**

*If you find this plugin helpful, please consider [rating it on WordPress.org](https://wordpress.org/support/plugin/tap-chat/reviews/) ⭐⭐⭐⭐⭐*
