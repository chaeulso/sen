#!/bin/bash

# Astro Theme - Simple Installation Script
# Run this from ANY directory - it auto-detects everything
# Usage: sudo bash install.sh

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}🌟 Astro Theme Installer${NC}"
echo ""

# Must be root
if [ "$EUID" -ne 0 ]; then
    echo -e "${RED}❌ Run with: sudo bash install.sh${NC}"
    exit 1
fi

# Pterodactyl directory
PTERO="/var/www/pterodactyl"
if [ ! -d "$PTERO" ]; then
    echo -e "${RED}❌ Pterodactyl not found at $PTERO${NC}"
    exit 1
fi

echo -e "✓ Found Pterodactyl at $PTERO"

# Find the extension files (sen folder)
SOURCE=""
if [ -d "$PTERO/sen" ]; then
    SOURCE="$PTERO/sen"
elif [ -d "$PTERO/.blueprint/dev/astrotheme" ]; then
    SOURCE="$PTERO/.blueprint/dev/astrotheme"
    echo -e "${YELLOW}⚠ Using existing extension in .blueprint/dev/astrotheme${NC}"
else
    echo -e "${RED}❌ Cannot find extension files${NC}"
    echo "Expected: $PTERO/sen"
    exit 1
fi

echo -e "✓ Found extension at $SOURCE"

# Check Blueprint
if ! command -v blueprint &> /dev/null; then
    echo -e "${RED}❌ Blueprint not installed${NC}"
    exit 1
fi

echo -e "✓ Blueprint is installed"

# Create directories
echo -e "→ Creating directories..."
mkdir -p "$PTERO/.blueprint/dev"
mkdir -p "$PTERO/.blueprint/tmp"

# Copy extension to dev directory
DEV_DIR="$PTERO/.blueprint/dev"

# Check if there are OTHER extensions in dev directory
OTHER_EXTENSIONS=$(find "$DEV_DIR" -maxdepth 1 -mindepth 1 -type d ! -name "astrotheme" 2>/dev/null | head -1)

if [ -n "$OTHER_EXTENSIONS" ]; then
    echo -e "→ Found other extensions, backing them up..."
    mkdir -p "$DEV_DIR/.backup"
    for dir in "$DEV_DIR"/*/; do
        dirname=$(basename "$dir")
        if [ "$dirname" != "astrotheme" ] && [ "$dirname" != ".backup" ]; then
            mv "$dir" "$DEV_DIR/.backup/$dirname"
        fi
    done
fi

# Remove old astrotheme
if [ -d "$DEV_DIR/astrotheme" ]; then
    echo -e "→ Removing old version..."
    rm -rf "$DEV_DIR/astrotheme"
fi

# Copy extension
echo -e "→ Copying extension files..."
cp -r "$SOURCE" "$DEV_DIR/astrotheme"

# Verify files
if [ ! -f "$DEV_DIR/astrotheme/conf.yml" ]; then
    echo -e "${RED}❌ conf.yml not found after copy${NC}"
    echo "Contents of $DEV_DIR/astrotheme:"
    ls -la "$DEV_DIR/astrotheme/"
    exit 1
fi

echo -e "✓ Files copied successfully"

# Show what we copied
echo ""
echo -e "${BLUE}Extension contents:${NC}"
ls -la "$DEV_DIR/astrotheme/" | grep -v "^total" | grep -v "^\.\." | grep -v "^\.$"
echo ""

# Now we need to make astrotheme the ONLY thing in dev for blueprint -build
# Blueprint's -build only works when there's exactly one extension in dev

# Check if there are other extensions
OTHER_COUNT=$(find "$DEV_DIR" -maxdepth 1 -mindepth 1 -type d ! -name "astrotheme" ! -name ".backup" 2>/dev/null | wc -l)

if [ "$OTHER_COUNT" -gt 0 ]; then
    echo -e "${YELLOW}⚠ Moving other extensions out of dev temporarily...${NC}"
    mkdir -p "$DEV_DIR/.backup"
    for dir in "$DEV_DIR"/*/; do
        dirname=$(basename "$dir")
        if [ "$dirname" != "astrotheme" ] && [ "$dirname" != ".backup" ]; then
            echo "  Moving: $dirname"
            mv "$dir" "$DEV_DIR/.backup/$dirname"
        fi
    done
fi

# Build from the dev directory
echo -e "→ Building extension..."
cd "$DEV_DIR"
echo -e "  Working directory: $(pwd)"
echo -e "  Contents: $(ls -1 | tr '\n' ' ')"
echo ""

if blueprint -build; then
    echo -e "${GREEN}✓ Build successful${NC}"
else
    echo -e "${RED}❌ Build failed${NC}"
    
    # Try alternative: manually copy files
    echo ""
    echo -e "${YELLOW}→ Attempting manual installation...${NC}"
    manual_install "$DEV_DIR/astrotheme" "$PTERO"
fi

# Restore other extensions
if [ -d "$DEV_DIR/.backup" ]; then
    echo -e "→ Restoring other extensions..."
    for dir in "$DEV_DIR/.backup"/*/; do
        dirname=$(basename "$dir")
        if [ -d "$dir" ] && [ ! -d "$DEV_DIR/$dirname" ]; then
            mv "$dir" "$DEV_DIR/$dirname"
        fi
    done
    rm -rf "$DEV_DIR/.backup"
fi

# Clear caches
echo -e "→ Clearing caches..."
cd "$PTERO"
php artisan view:clear > /dev/null 2>&1
php artisan cache:clear > /dev/null 2>&1
php artisan config:clear > /dev/null 2>&1
echo -e "✓ Caches cleared"

# Success!
echo ""
echo -e "${GREEN}╔════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║   ✅ Installation Complete!            ║${NC}"
echo -e "${GREEN}╚════════════════════════════════════════╝${NC}"
echo ""
echo -e "${BLUE}Next steps:${NC}"
echo "1. Go to: ${YELLOW}/admin/extensions${NC}"
echo "2. Click: ${YELLOW}Astro Theme${NC}"
echo "3. Toggle: ${YELLOW}Enable Theme${NC} → ON"
echo "4. Click: ${YELLOW}Save Changes${NC}"
echo ""
echo -e "${GREEN}🎉 Done!${NC}"

# Manual install function (fallback)
manual_install() {
    local EXT_DIR="$1"
    local PTERO="$2"
    
    echo "  → Manual install from: $EXT_DIR"
    
    # Admin views
    mkdir -p "$PTERO/resources/views/admin/extensions/astrotheme"
    cp -r "$EXT_DIR/admin/"* "$PTERO/resources/views/admin/extensions/astrotheme/" 2>/dev/null || true
    
    # Admin wrapper
    mkdir -p "$PTERO/resources/views/blueprint/admin/wrappers"
    cp "$EXT_DIR/admin/wrapper.blade.php" "$PTERO/resources/views/blueprint/admin/wrappers/astrotheme.blade.php" 2>/dev/null || true
    
    # Dashboard wrapper
    mkdir -p "$PTERO/resources/views/blueprint/dashboard/wrappers"
    cp "$EXT_DIR/dashboard/wrapper.blade.php" "$PTERO/resources/views/blueprint/dashboard/wrappers/astrotheme.blade.php" 2>/dev/null || true
    
    # Dashboard CSS
    cp "$EXT_DIR/dashboard/dashboard.css" "$PTERO/resources/views/blueprint/dashboard/wrappers/astrotheme.css" 2>/dev/null || true
    
    # Public assets (symlink)
    mkdir -p "$PTERO/public/extensions"
    if [ -L "$PTERO/public/extensions/astrotheme" ]; then
        rm "$PTERO/public/extensions/astrotheme"
    fi
    ln -s "$EXT_DIR/public" "$PTERO/public/extensions/astrotheme"
    
    # Views (symlink)
    mkdir -p "$PTERO/resources/views/blueprint/extensions"
    if [ -d "$EXT_DIR/views" ]; then
        if [ -L "$PTERO/resources/views/blueprint/extensions/astrotheme" ]; then
            rm "$PTERO/resources/views/blueprint/extensions/astrotheme"
        fi
        ln -s "$EXT_DIR/views" "$PTERO/resources/views/blueprint/extensions/astrotheme"
    fi
    
    # App (symlink)
    mkdir -p "$PTERO/app/BlueprintFramework/Extensions"
    if [ -d "$EXT_DIR/app" ]; then
        if [ -L "$PTERO/app/BlueprintFramework/Extensions/astrotheme" ]; then
            rm "$PTERO/app/BlueprintFramework/Extensions/astrotheme"
        fi
        ln -s "$EXT_DIR/app" "$PTERO/app/BlueprintFramework/Extensions/astrotheme"
    fi
    
    # Admin CSS
    if [ -f "$EXT_DIR/admin/admin.css" ]; then
        mkdir -p "$PTERO/public/extensions/astrotheme/css"
        cp "$EXT_DIR/admin/admin.css" "$PTERO/public/extensions/astrotheme/css/admin.css"
    fi
    
    # Routes
    if [ -f "$EXT_DIR/routes/web.php" ]; then
        mkdir -p "$PTERO/routes/blueprint/web"
        cp "$EXT_DIR/routes/web.php" "$PTERO/routes/blueprint/web/astrotheme.php"
    fi
    
    echo -e "  ${GREEN}✓ Manual install complete${NC}"
}
