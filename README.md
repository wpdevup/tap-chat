# Tap Chat - WhatsApp Click to Chat for WordPress

A lightweight, privacy-focused WhatsApp click-to-chat plugin for WordPress. Add a beautiful floating button with welcome bubble styles, working hours management, and advanced visibility controls.

## ✨ Features

### 💬 Welcome Bubble with Styles
- **Two Distinct Styles** - Modern (rich) and Simple (minimalist)
- **Flexible Positioning** - Top or Side placement for Simple style
- **Custom Greeting Messages** - Boost engagement by 30-40%
- **Agent/Team Name & Avatar** - Personalize your support team (Modern style)
- **Configurable Delay** - Show bubble after 0-60 seconds
- **Session Memory** - Remembers if user closed the bubble
- **Click to Chat** - Instant WhatsApp connection
- **Beautiful Animations** - Smooth fade-in effects

### 🎨 Bubble Styles

#### Modern Style
- Rich bubble with avatar display
- Agent/team name with online indicator
- Animated online pulse effect
- Larger, more detailed design
- Perfect for personalized support

#### Simple Style
- Clean, minimalist design
- Message-only display
- Compact layout
- Choose Top or Side positioning
- Perfect for quick notifications

### 📍 Bubble Position (Simple Style Only)
- **Top Position** - Bubble appears above the WhatsApp button
- **Side Position** - Bubble appears next to the WhatsApp button (horizontal layout)
- Automatically adjusts for button placement (left/right)
- Perfect alignment with button in all sizes

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
3. **Choose your style:**
   - **Modern:** Rich bubble with avatar, name, and online indicator
   - **Simple:** Clean bubble with message only
4. **Set position (Simple style only):**
   - **Top:** Bubble appears above button (default)
   - **Side:** Bubble appears next to button
5. Enter your greeting message (default: "Need help? Let's chat! 💬")
6. Set agent/team name (Modern style only)
7. Optionally add an avatar URL (Modern style only)
8. Configure display delay (default: 3 seconds)
9. Save changes

**Bubble Style Features:**

| Feature | Modern | Simple |
|---------|--------|--------|
| Avatar | ✓ | ✗ |
| Name | ✓ | ✗ |
| Online Indicator | ✓ | ✗ |
| Message | ✓ | ✓ |
| Position Options | Top only | Top or Side |
| Best For | Personalized support | Quick notifications |

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

### Bubble Style Examples

#### Modern Style - E-commerce
```
Style: Modern
Message: "Need help? Let's chat! 💬"
Name: "Sales Team"
Avatar: [Your team photo]
Delay: 3 seconds
Position: Top (only option)
```

#### Simple Style - Top Position
```
Style: Simple
Message: "Questions? We're here! 👋"
Position: Top
Delay: 4 seconds
```

#### Simple Style - Side Position
```
Style: Simple
Message: "Chat with us! 💬"
Position: Side
Delay: 3 seconds
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
💬 Bubble: Simple style, Side position
```

#### Service Business
```
✓ Show on: Services page, Contact page, Homepage
✗ Hide on: Privacy Policy, Terms of Service
⏰ Hours: Mon-Fri 8 AM - 5 PM
💬 Bubble: Modern style with agent photo
```

#### Landing Page
```
✓ Show ONLY on: Specific landing page
✗ Hide on: (not needed)
⏰ Hours: Always available
💬 Bubble: Simple style, Top position
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

### Version 1.2.0
#### 🎉 New Features
- **Bubble Style Selector** - Choose between Modern and Simple styles
- **Bubble Position Control** - Top or Side positioning for Simple style
- **Side Positioning** - Display bubble horizontally next to button
- **Conditional Fields** - Avatar and Name fields only show for Modern style

#### 🎨 Improvements
- Reduced bubble padding for more compact design (4-5px smaller)
- Better vertical alignment for Side positioned bubbles
- Optimized bubble sizing and spacing
- Enhanced admin UI with style preview cards
- Improved responsive behavior for both styles

#### 🐛 Fixes
- Fixed bubble positioning calculations
- Improved arrow pointer placement for Side position
- Better mobile optimization for compact bubbles

### Version 1.1.2
- **Fix:** Welcome bubble close button repositioned to top-right corner
- **Fix:** Close button rotates perfectly centered on hover
- **Fix:** Arrow now properly visible above WhatsApp button
- **Improvement:** Better close button styling

### Version 1.1.1
- **Fix:** Improved close button size and visibility
- **Improvement:** Enhanced CSS for admin panel
- **Improvement:** Updated default welcome message

### Version 1.1.0
- **New:** Welcome bubble feature
- **New:** Agent/team name and avatar support
- **New:** Configurable display delay
- **New:** Session-based close memory
- **New:** Beautiful animations

### Version 1.0.0
- **New:** Working hours management
- **New:** Timezone support
- **New:** Offline mode
- **New:** Day-specific scheduling

### Version 0.9.0
- **New:** Advanced visibility controls
- **New:** Smart country picker
- **New:** WooCommerce shop page support
- **Improvement:** Better UX

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
- **CSS:** ~3KB (minified)
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
1. **Choose the right style** - Modern for personalized support, Simple for quick contact
2. **Use Side position wisely** - Great for desktop, but test on mobile
3. **Set realistic working hours** - Match your actual availability
4. **Use timezone correctly** - Ensure it matches your business location
5. **Test offline message** - Make sure it's helpful and professional
6. **Combine with visibility rules** - Hide on irrelevant pages
7. **Update phone number** - Make sure it's active and monitored
8. **Optimize welcome bubble** - Keep message short and engaging (under 15 words)
9. **Test delay timing** - 3-5 seconds works best for most sites
10. **Use custom avatar** - Personal photos increase trust by 40% (Modern style)

### Bubble Style Best Practices

**Modern Style:**
- Use for customer service and support
- Add team member photo for trust
- Keep name short and friendly
- Perfect for B2C businesses

**Simple Style:**
- Use for quick notifications
- Great for minimalist designs
- Side position for desktop focus
- Top position for mobile-first

**Position Selection:**
- **Top:** Best for mobile, universal compatibility
- **Side:** Best for desktop, prominent placement

### Common Use Cases
- **E-commerce Support** - Simple style, Side position, quick responses
- **Appointment Booking** - Modern style with agent photo and availability
- **Sales Inquiries** - Simple style, Top position, special offers
- **Technical Support** - Modern style with team name and hours
- **Lead Generation** - Simple style, Side position, engaging message

---

**Made with ❤️ for the WordPress Community**

*If you find this plugin helpful, please consider [rating it on WordPress.org](https://wordpress.org/support/plugin/tap-chat/reviews/) ⭐⭐⭐⭐⭐*
