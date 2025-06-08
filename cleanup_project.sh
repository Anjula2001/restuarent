#!/bin/bash

# Grand Restaurant Project Cleanup Script
# Removes unnecessary debug, test, and documentation files while preserving core functionality
# Date: June 8, 2025

echo "🧹 Starting Grand Restaurant Project Cleanup..."
echo "📍 Working directory: $(pwd)"

# Keep track of what we're removing
REMOVED_COUNT=0
PRESERVED_COUNT=0

# Function to remove file with logging
remove_file() {
    if [ -f "$1" ]; then
        rm "$1"
        echo "❌ Removed: $1"
        ((REMOVED_COUNT++))
    fi
}

# Function to remove directory with logging
remove_directory() {
    if [ -d "$1" ]; then
        rm -rf "$1"
        echo "🗂️ Removed directory: $1"
        ((REMOVED_COUNT++))
    fi
}

# Function to log preserved files
preserve_file() {
    if [ -f "$1" ]; then
        echo "✅ Preserved: $1"
        ((PRESERVED_COUNT++))
    fi
}

echo ""
echo "🔍 Removing debugging and testing files..."

# Remove test HTML files
remove_file "test_complete_order_flow.html"
remove_file "complete_popular_foods_diagnosis.html"
remove_file "comprehensive_rice_test.html"
remove_file "image_debug_simple.html"
remove_file "reviews_verification_complete.html"
remove_file "final_popular_foods_debug.html"
remove_file "reviews_minimal_test.html"
remove_file "test_main_reservation_form.html"
remove_file "test_admin_login.html"
remove_file "test_images.html"
remove_file "homepage_debug_popular_foods.html"
remove_file "live_homepage_debug.html"
remove_file "final_order_system_test.html"
remove_file "verify_system.html"
remove_file "homepage_popular_foods_fix_verification.html"
remove_file "dashboard_order_layout_test.html"
remove_file "debug_menu.html"
remove_file "test_empty_state.html"
remove_file "test_category_management.html"
remove_file "dynamic_popular_foods_test.html"
remove_file "final_cart_image_test.html"
remove_file "verification_complete.html"
remove_file "database_consolidation_verification.html"
remove_file "function_verification_test.html"
remove_file "category_system_final_verification.html"
remove_file "empty_state_verification.html"
remove_file "test_empty_admin_behavior.html"
remove_file "test_auth.html"
remove_file "test_remove_button.html"
remove_file "test_cart_fix.html"
remove_file "live_rice_curry_monitor.html"
remove_file "test_carousel.html"
remove_file "final_rice_curry_test.html"
remove_file "test_admin_table_layout.html"
remove_file "test_logout.html"
remove_file "test_final_fixes.html"
remove_file "test_cart_consistency.html"
remove_file "test_home_reviews.html"
remove_file "debug_rice_curry_issue.html"
remove_file "test_category_update_function.html"
remove_file "test_reservation_final.html"
remove_file "rice_curry_final_test.html"
remove_file "reviews_debug_focused.html"
remove_file "debug_fallback_items.html"
remove_file "cart_image_final_debug.html"
remove_file "auto_cleanup.html"
remove_file "menu_behavior_monitor.html"
remove_file "cart_image_live_debug.html"
remove_file "direct_homepage_test.html"
remove_file "duplicate_fix_verification.html"
remove_file "duplicate_investigation_admin.html"
remove_file "duplicate_investigation.html"
remove_file "exact_menu_test.html"
remove_file "debug_popular_foods_api.html"
remove_file "debug_popular_images.html"
remove_file "debug_reservation.html"
remove_file "debug_reviews_page.html"
remove_file "debug_rice_curry.html"
remove_file "debug_rice_display.html"
remove_file "definitive_cart_test.html"
remove_file "debug_cart_images.html"
remove_file "debug_images.html"
remove_file "debug_menu_rice_curry.html"
remove_file "debug_order.html"
remove_file "comprehensive_order_test.html"
remove_file "complete_reviews_test.html"
remove_file "cart_image_final_test.html"
remove_file "cart_image_verification.html"
remove_file "clear_storage_test.html"
remove_file "admin_clear_button_integration_test.html"
remove_file "admin_table_layout_verification.html"
remove_file "force_clear_fallback.html"
remove_file "mamp_demo.html"
remove_file "real_time_homepage_monitor.html"
remove_file "star_rating_demo.html"
remove_file "verify_rice_curry_fix.html"
remove_file "quick_admin.html"
remove_file "final_integration_test.html"

echo ""
echo "📚 Removing completion reports and documentation files..."

# Remove completion reports and status files
remove_file "IMAGE_UPLOAD_ISSUE_RESOLVED.md"
remove_file "MYSQL_MIGRATION_COMPLETE.md"
remove_file "MYSQL_MIGRATION_SUCCESS.md"
remove_file "LOGOUT_FUNCTION_FIXED.md"
remove_file "FINAL_TASK_COMPLETION_REPORT.md"
remove_file "ALL_REVIEWS_DISPLAY_COMPLETE.md"
remove_file "DYNAMIC_MENU_SYSTEM_COMPLETE.md"
remove_file "ADMIN_PANEL_FIX_SUMMARY.md"
remove_file "FINAL_SETUP_STATUS.md"
remove_file "CART_IMAGE_FIX_COMPLETE.md"
remove_file "DATABASE_CONVERSION_SUMMARY.md"
remove_file "SQLITE_CLEANUP_COMPLETE.md"
remove_file "DASHBOARD_NAVIGATION_FIX_COMPLETE.md"
remove_file "FINAL_STATUS_COMPLETE.md"
remove_file "DELETE_FUNCTIONALITY_FIX_COMPLETE.md"
remove_file "RICE_CURRY_ISSUE_FINAL_RESOLUTION.md"
remove_file "MAMP_STATUS.md"
remove_file "MENU_LOADING_ISSUE_RESOLVED.md"
remove_file "CONSOLIDATION_COMPLETE.md"
remove_file "DATABASE_CLEANUP_COMPLETE.md"
remove_file "DATABASE_CONSOLIDATION_COMPLETE.md"
remove_file "DATABASE_CONSOLIDATION_FINAL_REPORT.md"
remove_file "DATABASE_FINAL_CONSOLIDATION_COMPLETE.md"
remove_file "SQL_CONSOLIDATION_SUMMARY.md"
remove_file "CART_POSITIONING_FIX_COMPLETE.md"
remove_file "CATEGORY_SYSTEM_FIX_COMPLETE.md"
remove_file "EMPTY_STATE_FIX_COMPLETE.md"
remove_file "FALLBACK_ITEMS_REMOVAL_COMPLETE.md"
remove_file "POPULAR_FOODS_SYNC_COMPLETION_REPORT.md"
remove_file "POPULAR_FOODS_SYNC_FIX_COMPLETE.md"
remove_file "RESERVATION_SYSTEM_COMPLETION_REPORT.md"
remove_file "TASK_COMPLETION_FINAL_VERIFICATION.md"
remove_file "ADMIN_CATEGORY_MANAGEMENT_COMPLETE.md"
remove_file "ADMIN_PANEL_TABLE_LAYOUT_FIX_COMPLETE.md"
remove_file "DUPLICATE_RESERVATION_FINAL_RESOLUTION.md"
remove_file "DUPLICATE_RESERVATION_ISSUE_FINAL_RESOLUTION.md"
remove_file "AUTHENTICATION_TEST_RESULTS.md"

echo ""
echo "🧪 Removing debug scripts and test files..."

# Remove debug JavaScript files
remove_file "cart_test_console.js"
remove_file "clear_fallback_script.js"

# Remove debug PHP files
remove_file "diagnose.php"
remove_file "check_database_structure.php"
remove_file "final_database_test.php"

# Remove verification scripts  
remove_file "verify_mamp_setup.sh"

echo ""
echo "✅ Files that will be PRESERVED (core functionality):"

# Core HTML pages
preserve_file "index.html"
preserve_file "menu.html"
preserve_file "order.html"
preserve_file "contact.html"
preserve_file "reservation.html"
preserve_file "reviews.html"
preserve_file "dashboard.html"
preserve_file "profile.html"
preserve_file "login.html"
preserve_file "register.html"

# Essential documentation
preserve_file "README.md"
preserve_file "PROJECT_SETUP_GUIDE.md"
preserve_file "CROSS_PLATFORM.md"
preserve_file "WINDOWS_XAMPP.md"
preserve_file "MAMP_SETUP.md"
preserve_file "DEPLOYMENT.md"

# Core directories (just logging - not removing)
if [ -d "admin" ]; then
    echo "✅ Preserved directory: admin/"
fi
if [ -d "api" ]; then
    echo "✅ Preserved directory: api/"
fi
if [ -d "classes" ]; then
    echo "✅ Preserved directory: classes/"
fi
if [ -d "config" ]; then
    echo "✅ Preserved directory: config/"
fi
if [ -d "css" ]; then
    echo "✅ Preserved directory: css/"
fi
if [ -d "js" ]; then
    echo "✅ Preserved directory: js/"
fi
if [ -d "images" ]; then
    echo "✅ Preserved directory: images/"
fi
if [ -d "database" ]; then
    echo "✅ Preserved directory: database/"
fi

# Essential configuration and setup files
preserve_file "backup.sh"
preserve_file "backup_windows.bat"

echo ""
echo "📊 CLEANUP SUMMARY:"
echo "❌ Files removed: $REMOVED_COUNT"
echo "✅ Files preserved: $PRESERVED_COUNT"
echo ""
echo "🎉 Project cleanup completed!"
echo "🔧 Your Grand Restaurant project is now clean and optimized"
echo "💾 All core functionality and data preserved"
echo "🚀 Ready for production or sharing"
