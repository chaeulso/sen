#!/bin/bash

# Astro Theme - Quick Rebuild Script
# Run this after making changes to the theme files
# Usage: bash rebuild.sh

set -e

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}🔄 Rebuilding Astro Theme...${NC}"

# Check directory
if [ ! -d ".blueprint/dev/astrotheme" ]; then
    echo -e "${RED}❌ Extension not found. Run install.sh first.${NC}"
    exit 1
fi

# Rebuild
blueprint -build

# Clear caches
php artisan view:clear > /dev/null 2>&1
php artisan cache:clear > /dev/null 2>&1

echo -e "${GREEN}✅ Done! Changes applied.${NC}"
