# AuraWP - Modern Glassmorphism WordPress Theme

A clean, modern WordPress theme for advertising agencies featuring glassmorphism UI, dark/light mode toggle, smooth animations, and an immersive 3D cityscape background.

## ✨ Features

### Visual Design
- **Glassmorphism UI** - Frosted glass effects with backdrop blur, soft shadows, and glow effects
- **Dark/Light Mode** - Toggle between themes with localStorage persistence and system preference detection
- **Smooth Animations** - Fade, slide, scale, and rotate animations powered by GSAP
- **3D Cityscape Background** - Mirror's Edge-inspired abstract city with scroll-controlled camera flight (Three.js)

### Functionality
- **Fully Responsive** - Mobile-first design with breakpoints at 320px, 768px, 1024px, and 1440px
- **WordPress Customizer API** - Extensive customization options:
  - Colors (primary, secondary, glass transparency, glow intensity)
  - Animations (type, duration, easing, stagger, per-section toggles)
  - 3D Background (camera speed, fog density, LOD, custom GLB model support)
- **Accessibility** - WCAG compliant with keyboard navigation, ARIA attributes, and reduced motion support
- **Performance Optimized** - Critical CSS inline, lazy loading, deferred scripts, CDN preconnect

### Technical
- **No Page Builder Dependency** - Clean code without Elementor or WPBakery
- **Modular Architecture** - Separate JS modules for customizer, animations, and 3D scene
- **SCSS Architecture** - Organized with abstracts, base, components, layout, and themes
- **Export/Import Settings** - JSON-based theme settings backup and restore

## 📁 Theme Structure

```
aurawp/
├── assets/
│   ├── js/
│   │   ├── main.js          # Theme initialization, handlers
│   │   ├── customizer.js    # Live preview in admin
│   │   ├── three-city.js    # 3D scene with scroll controls
│   │   └── animations.js    # GSAP animations
│   ├── scss/
│   │   ├── abstracts/       # Variables, mixins
│   │   ├── base/            # Reset, typography, utilities
│   │   ├── components/      # Cards, buttons
│   │   ├── layout/          # Header, footer, grid
│   │   ├── themes/          # Light/dark themes
│   │   └── main.scss        # Entry point
│   └── models/
│       └── city-min.glb     # Optional 3D model
├── inc/
│   ├── customizer/          # Customizer sections
│   ├── template-tags.php    # Template helper functions
│   └── performance.php      # Optimization features
├── template-parts/          # Reusable template parts
├── page-templates/          # Custom page templates
├── functions.php            # Theme setup and includes
├── style.css                # Theme header + critical CSS
├── index.php                # Fallback template
└── README.md                # This file
```

## 🚀 Installation

### Requirements
- WordPress 5.9 or higher
- PHP 7.4 or higher
- Modern browser with WebGL support (for 3D background)

### Manual Installation
1. Download the `aurawp` folder
2. Upload to `/wp-content/themes/` directory
3. Activate through WordPress Admin → Appearance → Themes

### Using Git
```bash
cd wp-content/themes
git clone <repository-url> aurawp
```

## ⚙️ Configuration

### Basic Setup
1. Go to **Appearance → Customize**
2. Configure your brand colors in **Colors** section
3. Set up navigation menus in **Menus**
4. Add widgets to footer areas in **Widgets**

### 3D Background Settings
Navigate to **Appearance → Customize → 3D Background**:
- **Camera Speed** - Adjust how fast the camera moves during scroll
- **Fog Density** - Control atmospheric fog thickness
- **Level of Detail** - Building density (affects performance)
- **Custom Model** - Upload your own `.glb` file

### Animation Settings
Navigate to **Appearance → Customize → Animations**:
- Choose animation type (fade, slide, scale, rotate)
- Set duration (0.3s - 2s)
- Configure easing function
- Enable/disable per section

### Export/Import Settings
1. Go to **Appearance → Customize → Export / Import**
2. Click **Export** to download current settings as JSON
3. Use **Import** to restore settings from a JSON file

## 🎨 Customization Examples

### Adding Animated Sections
```php
<?php aurawp_animated_section('hero', 'slideUp'); ?>
    <!-- Your content here -->
<?php aurawp_close_animated_section(); ?>
```

### Creating Glass Cards
```php
<?php
aurawp_glass_card(array(
    'title'       => 'Service Title',
    'content'     => 'Description text...',
    'link'        => '/service-page',
    'link_text'   => 'Learn More',
    'glow'        => 'primary',
    'interactive' => true
));
?>
```

### Theme Toggle Button
```php
<?php aurawp_theme_toggle(); ?>
```

## ♿ Accessibility

The theme includes:
- Skip link for keyboard navigation
- ARIA labels on interactive elements
- Focus indicators with visible outlines
- Color contrast ratio ≥ 4.5:1
- `prefers-reduced-motion` support
- Screen reader announcements for form errors

## 🏎️ Performance

Optimizations included:
- Critical CSS inlined in `<head>`
- Non-critical JavaScript deferred
- Native lazy loading for images
- Preconnect to CDN domains
- Query strings removed from static resources
- Limited post revisions (5)
- Self-pingbacks disabled

## 🌙 Dark Mode

Dark mode is automatically applied based on:
1. User's manual selection (saved in localStorage)
2. System preference (`prefers-color-scheme`)

Users can toggle manually using the theme switcher button.

## 📱 Responsive Breakpoints

| Breakpoint | Min Width | Target Devices |
|------------|-----------|----------------|
| xs         | 320px     | Small phones   |
| sm         | 768px     | Tablets        |
| md         | 1024px    | Laptops        |
| lg         | 1440px    | Desktops       |

## 🔧 Development

### Compiling SCSS
```bash
# Install dependencies
npm install

# Watch for changes
npm run watch

# Build production CSS
npm run build
```

### SCSS Architecture
- **abstracts/** - Variables, mixins, functions
- **base/** - Reset, typography, utilities
- **components/** - Reusable UI components
- **layout/** - Structural styles
- **themes/** - Light/dark theme overrides

## 📄 License

GNU General Public License v2 or later

## 🤝 Support

For issues and feature requests, please open an issue on GitHub.

## 🙏 Credits

- **Three.js** - 3D graphics library
- **GSAP** - Animation platform
- **Google Fonts** - Inter and Poppins typefaces

---

Built with ❤️ for modern advertising agencies
