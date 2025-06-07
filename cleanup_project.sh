#!/bin/bash

# Grand Restaurant Project Cleanup Script
# Removes unnecessary debug, test, and documentation files

echo "🧹 Starting Grand Restaurant Project Cleanup..."
echo "================================================"

cd /Applications/MAMP/htdocs/restuarent

# Count files before cleanup
echo "📊 Files before cleanup:"
find . -type f | wc -l

echo ""
echo "🗑️  Removing unnecessary files..."

# Remove all markdown documentation files (except README.md)
echo "  - Removing documentation files..."
find . -name "*.md" -not -name "README.md" -type f -delete

# Remove all debug files
echo "  - Removing debug files..."
find . -name "debug_*.html" -type f -delete
find . -name "*debug*.html" -type f -delete

# Remove all test files
echo "  - Removing test files..."
find . -name "test_*.html" -type f -delete
find . -name "*test*.html" -type f -delete
find . -name "test_*.php" -type f -delete
find . -name "*test*.php" -type f -delete

# Remove verification files
echo "  - Removing verification files..."
find . -name "*verification*.html" -type f -delete
find . -name "verify_*.html" -type f -delete
find . -name "verify_*.php" -type f -delete

# Remove diagnosis files
echo "  - Removing diagnosis files..."
find . -name "*diagnosis*.html" -type f -delete
find . -name "diagnose*.php" -type f -delete

# Remove fix files
echo "  - Removing fix files..."
find . -name "*fix*.html" -type f -delete
find . -name "fix_*.html" -type f -delete

# Remove monitor files
echo "  - Removing monitor files..."
find . -name "*monitor*.html" -type f -delete

# Remove final/complete files
echo "  - Removing final/complete files..."
find . -name "final_*.html" -type f -delete
find . -name "*final*.html" -type f -delete
find . -name "complete_*.html" -type f -delete

# Remove specific unnecessary files
echo "  - Removing specific unnecessary files..."
rm -f demo.html
rm -f mamp_demo.html
rm -f quick_admin.html
rm -f check_database_structure.php
rm -f star_rating_demo.html
rm -f star_rating_test.html
rm -f image_debug_simple.html
rm -f simple_*.html
rm -f getMessage
rm -f setup.sh
rm -f verify_*.sh

# Remove duplicate investigation files
find . -name "duplicate_*.html" -type f -delete

# Remove comprehensive files
find . -name "comprehensive_*.html" -type f -delete

# Remove category system files
find . -name "category_*.html" -type f -delete

# Remove popular foods specific files
find . -name "popular_*.html" -type f -delete

# Remove reservation specific test files
find . -name "reservation_*.html" -not -name "reservation.html" -type f -delete

# Remove reviews specific test files
find . -name "reviews_*.html" -not -name "reviews.html" -type f -delete

# Remove rice curry specific files
find . -name "rice_*.html" -type f -delete

# Remove order specific test files
find . -name "order_*.html" -type f -delete

# Remove homepage specific files
find . -name "homepage_*.html" -type f -delete

# Remove menu specific test files
find . -name "menu_*.html" -not -name "menu.html" -type f -delete

# Remove live monitoring files
find . -name "live_*.html" -type f -delete

# Remove real time files
find . -name "real_time_*.html" -type f -delete

# Remove street food files
find . -name "street_food_*.html" -type f -delete

# Remove admin specific test files
find . -name "admin_*.html" -type f -delete

# Remove dashboard specific test files
find . -name "dashboard_*.html" -not -name "dashboard.html" -type f -delete

# Remove navigation test file (keep core files)
rm -f navigation_test.html

# Remove .DS_Store files
find . -name ".DS_Store" -type f -delete

# Remove backup files
find . -name "*.bak" -type f -delete
find . -name "*.backup" -type f -delete
find . -name "*~" -type f -delete

echo ""
echo "📊 Files after cleanup:"
find . -type f | wc -l

echo ""
echo "📁 Remaining core files:"
echo "  HTML Pages:"
ls -1 *.html 2>/dev/null | grep -E "(index|menu|order|dashboard|profile|login|register|contact|reservation|reviews)\.html" | sort

echo ""
echo "  Directories:"
ls -1d */ 2>/dev/null | sort

echo ""
echo "✅ Cleanup completed successfully!"
echo "🎯 Project now contains only essential files for the restaurant website."
