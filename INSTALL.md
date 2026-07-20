# 🚀 Astro Theme - Installation Guide

## Quick Install (Recommended)

```bash
# 1. Clone the repository
cd /var/www/pterodactyl
git clone https://github.com/chaeulso/sen.git

# 2. Run the install script
cd sen
sudo bash install.sh
```

That's it! The script will:
- ✅ Check if you're in the right directory
- ✅ Create Blueprint directory structure
- ✅ Copy extension files
- ✅ Build the extension
- ✅ Clear caches
- ✅ Set permissions
- ✅ Verify installation

Then just enable it in your admin panel: `/admin/extensions` → Astro Theme → Enable

---

## Manual Install

If you prefer to install manually:

```bash
cd /var/www/pterodactyl

# Create dev directory
mkdir -p .blueprint/dev
mkdir -p .blueprint/tmp

# Copy extension
cp -r sen .blueprint/dev/astrotheme

# Build
blueprint -build

# Clear caches
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

---

## Development Workflow

### Making Changes

Edit files in `/var/www/pterodactyl/.blueprint/dev/astrotheme/`

Then rebuild:

```bash
cd /var/www/pterodactyl
sudo bash rebuild.sh
```

Or manually:

```bash
blueprint -build
php artisan view:clear
```

### File Locations

- **Extension source**: `/var/www/pterodactyl/.blueprint/dev/astrotheme/`
- **Admin views**: `resources/views/admin/extensions/astrotheme/`
- **Public assets**: `public/extensions/astrotheme/` (symlinked)
- **Dashboard wrapper**: `resources/views/blueprint/dashboard/wrappers/astrotheme.blade.php`

---

## Troubleshooting

### "Blueprint is not installed"
Install Blueprint first: https://blueprint.zip/guides/admin/install

### "Extension not found"
Make sure you're in `/var/www/pterodactyl` and the `sen` folder exists

### "Developer mode not enabled"
Go to `/admin/extensions` → Blueprint → Set "developer" to true

### "Permission denied"
Run with `sudo`: `sudo bash install.sh`

### Theme not showing after install
1. Clear browser cache (Ctrl+Shift+R)
2. Check if theme is enabled: `/admin/extensions/astrotheme`
3. Run `php artisan view:clear`

---

## Updating the Theme

```bash
cd /var/www/pterodactyl/sen
git pull origin main
cd ..
sudo bash install.sh
```

---

## Uninstalling

```bash
cd /var/www/pterodactyl
blueprint -remove astrotheme
php artisan view:clear
php artisan cache:clear
```

---

## Support

- **GitHub Issues**: https://github.com/chaeulso/sen/issues
- **Blueprint Docs**: https://blueprint.zip/docs
- **Pterodactyl Docs**: https://pterodactyl.io/project/introduction.html

---

## Requirements

- Pterodactyl Panel 1.11+
- Blueprint Framework (latest)
- PHP 8.0+
- Node.js 16+ (for building assets, if needed)

---

## License

MIT License - Free for personal and commercial use
