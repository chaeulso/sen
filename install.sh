#!/bin/bash

# Astro Theme - Automated Installation Script
# Usage: bash install.sh

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}"
echo "╔════════════════════════════════════════════════════════════╗"
echo "║                                                            ║"
echo "║           🌟 Astro Theme Installation Script 🌟           ║"
echo "║                                                            ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo -e "${NC}"

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    echo -e "${RED}❌ Error: This script must be run as root${NC}"
    echo "Please run: sudo bash install.sh"
    exit 1
fi

# Determine Pterodactyl directory
PTERO_DIR="/var/www/pterodactyl"

if [ ! -d "$PTERO_DIR" ]; then
    echo -e "${RED}❌ Error: Pterodactyl directory not found at $PTERO_DIR${NC}"
    echo "Please install Pterodactyl Panel first"
    exit 1
fi

echo -e "${BLUE}📁 Pterodactyl directory: $PTERO_DIR${NC}"

# Check if Blueprint is installed
if ! command -v blueprint &> /dev/null; then
    echo -e "${RED}❌ Error: Blueprint is not installed${NC}"
    echo "Please install Blueprint first: https://blueprint.zip/guides/admin/install"
    exit 1
fi

echo -e "${GREEN}✓ Blueprint is installed${NC}"

# Navigate to Pterodactyl directory
cd "$PTERO_DIR"

# Create .blueprint directory structure
echo -e "${YELLOW}📂 Creating Blueprint directory structure...${NC}"
mkdir -p .blueprint/dev
mkdir -p .blueprint/tmp
echo -e "${GREEN}✓ Directory structure created${NC}"

# Check if extension already exists
if [ -d ".blueprint/dev/astrotheme" ]; then
    echo -e "${YELLOW}⚠️  Extension already exists. Updating...${NC}"
    rm -rf .blueprint/dev/astrotheme
fi

# Find the extension files
EXTENSION_SOURCE=""

# Check common locations
if [ -d "sen" ]; then
    EXTENSION_SOURCE="sen"
    echo -e "${GREEN}✓ Found extension in ./sen${NC}"
elif [ -d "../sen" ]; then
    EXTENSION_SOURCE="../sen"
    echo -e "${GREEN}✓ Found extension in ../sen${NC}"
elif [ -d "$HOME/sen" ]; then
    EXTENSION_SOURCE="$HOME/sen"
    echo -e "${GREEN}✓ Found extension in $HOME/sen${NC}"
else
    echo -e "${RED}❌ Error: Cannot find extension files${NC}"
    echo "Please ensure the 'sen' folder is in one of these locations:"
    echo "  - /var/www/pterodactyl/sen"
    echo "  - /var/www/sen"
    echo "  - ~/sen"
    exit 1
fi

# Copy extension to dev directory
echo -e "${YELLOW}📋 Copying extension files...${NC}"
cp -r "$EXTENSION_SOURCE" .blueprint/dev/astrotheme
echo -e "${GREEN}✓ Extension files copied${NC}"

# Verify conf.yml exists
if [ ! -f ".blueprint/dev/astrotheme/conf.yml" ]; then
    echo -e "${RED}❌ Error: conf.yml not found in extension${NC}"
    exit 1
fi

echo -e "${GREEN}✓ conf.yml verified${NC}"

# Check if developer mode is enabled
echo -e "${YELLOW}🔍 Checking developer mode...${NC}"
echo -e "${BLUE}ℹ️  Make sure developer mode is enabled in admin panel:${NC}"
echo "   /admin/extensions → Blueprint → Set 'developer' to true"
echo ""
read -p "Is developer mode enabled? (y/n): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo -e "${RED}❌ Please enable developer mode first, then run this script again${NC}"
    exit 1
fi

# Build the extension
echo -e "${YELLOW}🔨 Building extension...${NC}"
if blueprint -build; then
    echo -e "${GREEN}✓ Extension built successfully${NC}"
else
    echo -e "${RED}❌ Error: Blueprint build failed${NC}"
    exit 1
fi

# Clear caches
echo -e "${YELLOW}🧹 Clearing caches...${NC}"
php artisan view:clear
php artisan cache:clear
php artisan config:clear
echo -e "${GREEN}✓ Caches cleared${NC}"

# Set permissions
echo -e "${YELLOW}🔐 Setting permissions...${NC}"
chown -R www-data:www-data .blueprint/dev/astrotheme 2>/dev/null || chown -R nginx:nginx .blueprint/dev/astrotheme 2>/dev/null || true
chmod -R 755 .blueprint/dev/astrotheme
echo -e "${GREEN}✓ Permissions set${NC}"

# Verify installation
echo -e "${YELLOW}🔍 Verifying installation...${NC}"

# Check if files were copied to the right places
INSTALLED=true

if [ ! -d "resources/views/admin/extensions/astrotheme" ]; then
    echo -e "${RED}⚠️  Admin views not found${NC}"
    INSTALLED=false
fi

if [ ! -L "public/extensions/astrotheme" ] && [ ! -d "public/extensions/astrotheme" ]; then
    echo -e "${RED}⚠️  Public assets not found${NC}"
    INSTALLED=false
fi

if [ "$INSTALLED" = true ]; then
    echo -e "${GREEN}✓ Installation verified${NC}"
fi

# Final summary
echo ""
echo -e "${BLUE}"
echo "╔════════════════════════════════════════════════════════════╗"
echo "║                                                            ║"
echo "║              ✅ Installation Complete! ✅                  ║"
echo "║                                                            ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo -e "${NC}"

echo -e "${GREEN}Next steps:${NC}"
echo "1. Go to: ${YELLOW}https://your-panel.com/admin/extensions${NC}"
echo "2. Click on ${YELLOW}'Astro Theme'${NC}"
echo "3. Toggle ${YELLOW}'Enable Theme'${NC} to ON"
echo "4. Click ${YELLOW}'Save Changes'${NC}"
echo ""
echo -e "${BLUE}Extension location: ${YELLOW}$PTERO_DIR/.blueprint/dev/astrotheme${NC}"
echo -e "${BLUE}Admin settings: ${YELLOW}/admin/extensions/astrotheme${NC}"
echo ""
echo -e "${GREEN}🎉 Enjoy your new theme!${NC}"
echo ""

# Ask if user wants to open the admin panel
read -p "Would you like to see the admin panel URL? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    # Try to get the panel URL from .env
    if [ -f ".env" ]; then
        APP_URL=$(grep "^APP_URL=" .env | cut -d '=' -f2)
        if [ -n "$APP_URL" ]; then
            echo ""
            echo -e "${YELLOW}Admin panel: ${APP_URL}/admin/extensions/astrotheme${NC}"
        else
            echo -e "${YELLOW}Admin panel: https://your-domain.com/admin/extensions/astrotheme${NC}"
        fi
    else
        echo -e "${YELLOW}Admin panel: https://your-domain.com/admin/extensions/astrotheme${NC}"
    fi
fi

exit 0
