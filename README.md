# 🌟 Astro Theme

**A modern glassmorphism dashboard theme for Pterodactyl Panel, built as a Blueprint Extension.**

![Astro Theme Preview](https://img.shields.io/badge/Astro-Theme-4f7cff?style=for-the-badge)
![Blueprint](https://img.shields.io/badge/Blueprint-Compatible-7cc2ff?style=for-the-badge)
![Pterodactyl](https://img.shields.io/badge/Pterodactyl-1.11+-blue?style=for-the-badge)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

---

## ✨ Features

### Visual Design
- 🪟 **Glassmorphism UI** — Frosted glass effects with configurable blur and opacity
- 🎨 **Customizable Accent Colors** — Dual accent color system with gradient support
- 🌊 **Smooth Animations** — Card hover effects, fade-ins, skeleton loaders, and transitions
- 📊 **Status Indicators** — Animated online/offline/starting/stopped badges
- 🖥️ **Terminal Styling** — Dark console with syntax-highlighted output
- 💎 **Modern Typography** — Outfit (display), Inter (body), JetBrains Mono (terminal)

### Customization
- 🎛️ **30+ Settings** — Full control over every visual aspect
- 🖼️ **SVG Customizer** — Upload, paste, or URL SVG decorations with multi-layer support
- 🎨 **16 Custom Colors** — Override every color token individually
- 🖼️ **Background System** — Solid, gradient, image, video, particles, and animated backgrounds
- ✍️ **Custom Code** — Inject CSS, JS, head HTML, and footer HTML
- 🏷️ **Branding** — Custom logo, login background, footer text, and copyright

### Live Preview
- 📱 **Device Preview** — Desktop, tablet, and mobile preview modes
- ⚡ **Real-time Updates** — See changes instantly without page refresh
- 🔄 **Full Coverage** — Preview all aspects: colors, backgrounds, sidebar, animations, blur, glass

### Responsive Design
- 📱 **Mobile** — Touch-friendly controls with collapsible sidebar
- 📋 **Tablet** — Adaptive grid layouts
- 💻 **Desktop** — Full sidebar with all features
- 📐 **Foldables** — Optimized for narrow screens
- 🔄 **Landscape/Portrait** — Orientation-aware layouts
- 🚫 **No Horizontal Scroll** — Responsive typography and spacing

### Performance
- ⚡ **Lazy Loading** — Assets loaded on demand
- 🎯 **Optimized Animations** — GPU-accelerated with `prefers-reduced-motion` support
- 📦 **Minimal JS** — No framework dependencies
- 🎨 **CSS-first** — Theme rendered via CSS variables and overrides

---

## 📦 Installation

### Prerequisites
- Pterodactyl Panel v1.11+ installed and running
- [Blueprint Framework](https://blueprint.zip) installed

### Install via Blueprint

```bash
# Navigate to your Pterodactyl directory
cd /var/www/pterodactyl

# Install the extension
blueprint -install astrotheme

# Or install from source
blueprint -import astrotheme.blueprint
```

### Install from Source (Development)

```bash
# Clone or copy this repository to Blueprint's dev directory
cd /var/www/pterodactyl/.blueprint/dev
git clone https://github.com/astro-theme/pterodactyl astrotheme

# Build the extension
cd /var/www/pterodactyl
blueprint -build
```

### Post-Installation

1. Navigate to **Admin Panel → Extensions → Astro Theme**
2. Enable the theme by toggling "Enable Theme"
3. Configure settings to your preference
4. Click "Save Changes"

---

## ⚙️ Configuration

### Accessing Settings

Navigate to: **Admin Panel → Extensions → Astro Theme**

### Settings Tabs

| Tab | Description |
|-----|-------------|
| **General** | Enable theme, dark mode, blur, glass opacity, animation speed, border radius, accent colors, sidebar width, compact mode |
| **Branding** | Logo upload, logo dimensions, login background, dashboard background, footer text, copyright |
| **Colors** | 16 individual color overrides (primary, secondary, success, warning, danger, info, text, muted, border, hover, buttons, links) |
| **Background** | Background type (solid/gradient/image/video), particles toggle, overlay opacity, background blur |
| **SVG Customizer** | Upload/paste/URL SVG, multi-layer support, positioning, scaling, color override, opacity, animation toggle |
| **Custom Code** | Custom CSS, Custom JS, Custom Head HTML, Custom Footer HTML |
| **Live Preview** | Real-time preview with desktop/tablet/mobile device switching |

### Default Settings

| Setting | Default | Description |
|---------|---------|-------------|
| Enabled | `1` | Theme active |
| Dark Mode | `0` | Light mode |
| Blur Strength | `20px` | Glass blur amount |
| Glass Opacity | `0.60` | Glass background opacity |
| Animation Speed | `normal` | Animation pace |
| Border Radius | `24px` | Card rounding |
| Accent Color 1 | `#4f7cff` | Primary accent |
| Accent Color 2 | `#7cc2ff` | Secondary accent |
| Sidebar Width | `280px` | Sidebar column width |
| Compact Mode | `0` | Normal spacing |

---

## 🗂️ Folder Structure

```
astro-theme/
├── conf.yml                    # Blueprint extension manifest
├── admin/
│   ├── Controller.php          # Admin settings controller
│   ├── view.blade.php          # Admin settings page (Blade)
│   ├── wrapper.blade.php       # Admin panel CSS injection
│   └── admin.css               # Admin panel style overrides
├── dashboard/
│   ├── wrapper.blade.php       # Dashboard theme injection
│   └── dashboard.css           # React bundle CSS overrides
├── public/
│   ├── css/
│   │   ├── astro-theme.css         # Core design tokens & utilities
│   │   ├── astro-animations.css    # Keyframes & transitions
│   │   ├── astro-responsive.css    # Responsive breakpoints
│   │   ├── astro-login.css         # Login page styling
│   │   ├── astro-sidebar.css       # Navigation sidebar
│   │   ├── astro-server-cards.css  # Server card grid
│   │   ├── astro-console.css       # Console/terminal page
│   │   ├── astro-components.css    # Toasts, modals, dropdowns
│   │   └── astro-admin.css         # Settings page styles
│   ├── js/
│   │   └── astro-theme.js          # Theme initialization & interactions
│   └── images/
│       ├── icon.svg                # Extension icon
│       └── logo-default.svg        # Default logo
├── views/
│   ├── settings/                   # Settings sub-views
│   └── components/                 # Reusable Blade components
├── app/
│   ├── ThemeEngine.php             # Core theme engine
│   ├── SettingsManager.php         # Settings validation
│   └── AssetManager.php            # Asset path management
├── routes/
│   └── web.php                     # Extension routes
├── data/                           # Private extension storage
└── README.md                       # This file
```

---

## 🎨 Design System

### Color Tokens
```css
--astro-accent-1: #4f7cff;     /* Primary accent */
--astro-accent-2: #7cc2ff;     /* Secondary accent */
--astro-page-bg: #edf3ff;      /* Page background */
--astro-glass-bg: rgba(255, 255, 255, 0.60);  /* Glass surface */
--astro-text: #2b3a67;         /* Body text */
--astro-text-strong: #1e2a52;  /* Emphasis text */
--astro-text-muted: #8ba0d8;   /* Secondary text */
--astro-online: #34d399;       /* Online status */
--astro-starting: #fbbf24;     /* Starting status */
--astro-stopped: #fb7185;      /* Stopped/crashed */
--astro-offline: #94a3b8;      /* Offline */
```

### Typography
- **Display:** Outfit (headings, titles)
- **Body:** Inter (body text, labels)
- **Mono:** JetBrains Mono (console, addresses, code)

### Spacing Scale
- Cards: `1.25rem` padding
- Radius: `24px` (cards), `12px` (controls)
- Gap: `1.5rem` (grid), `0.75rem` (inner)

### Animation Timing
- **Fast:** `0.2s` — Button press, hover, focus
- **Normal:** `0.35s` — Card hover, fade-in
- **Slow:** `0.5s` — Page transitions, slide-in
- **Easing:** `cubic-bezier(0.22, 1, 0.36, 1)` — Custom ease

---

## 🔧 Development

### Building from Source

```bash
# Clone the repository
git clone https://github.com/astro-theme/pterodactyl astro-theme
cd astro-theme

# Copy to Blueprint dev directory
cp -r . /var/www/pterodactyl/.blueprint/dev/astrotheme

# Build
cd /var/www/pterodactyl
blueprint -build
```

### File Modifications

- **Theme CSS:** Edit files in `public/css/`
- **Theme JS:** Edit `public/js/astro-theme.js`
- **Admin UI:** Edit `admin/view.blade.php`
- **Dashboard Overrides:** Edit `dashboard/dashboard.css`

### Adding New Settings

1. Add the setting key to `admin/Controller.php` DEFAULTS array
2. Add the input to `admin/view.blade.php`
3. Reference in `dashboard/wrapper.blade.php` for rendering
4. Add validation in `app/SettingsManager.php`

---

## 🐛 Troubleshooting

### Theme not showing
- Ensure "Enable Theme" is toggled on in settings
- Run `blueprint -build` after installation
- Clear panel cache: `php artisan view:clear && php artisan cache:clear`

### Styles not applying to React components
- Dashboard CSS uses attribute selectors that may need updating for panel version changes
- Check browser console for CSS errors

### Admin panel looks wrong
- Admin CSS is separate from dashboard CSS
- Check `admin/wrapper.blade.php` is loading correctly

---

## 📄 License

MIT License — free for personal and commercial use.

---

## 🤝 Contributing

Contributions welcome! Please:
1. Fork the repository
2. Create a feature branch
3. Test with the latest Pterodactyl + Blueprint
4. Submit a pull request

---

## 🙏 Credits

- **Design System** — Inspired by [Astro/Aether](https://github.com/chaeulso/astro) panel concept
- **Framework** — Built for [Blueprint](https://blueprint.zip) by Emma & contributors
- **Panel** — [Pterodactyl Panel](https://pterodactyl.io)
- **Fonts** — [Google Fonts](https://fonts.google.com) (Outfit, Inter, JetBrains Mono)

---

## 📞 Support

- **GitHub Issues** — Bug reports and feature requests
- **Blueprint Discord** — Community support
- **Documentation** — [blueprint.zip/docs](https://blueprint.zip/docs)
