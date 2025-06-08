// Direct script to clear fallback items from localStorage
// This can be run in the browser console on any page of the site

console.log('🔧 Starting fallback item removal...');

// Get current cart data
const cartKey = 'restaurant_cart';
const cartData = localStorage.getItem(cartKey);

if (!cartData) {
    console.log('📭 No cart data found in localStorage');
} else {
    try {
        const cart = JSON.parse(cartData);
        console.log(`📋 Current cart has ${cart.length} items:`, cart);
        
        // Find fallback items
        const fallbackItems = cart.filter(item => item.name.includes('FALLBACK'));
        console.log(`⚠️ Found ${fallbackItems.length} fallback items:`, fallbackItems);
        
        if (fallbackItems.length === 0) {
            console.log('✅ No fallback items found - cart is clean!');
        } else {
            // Remove fallback items
            const cleanCart = cart.filter(item => !item.name.includes('FALLBACK'));
            
            // Save cleaned cart
            localStorage.setItem(cartKey, JSON.stringify(cleanCart));
            
            console.log(`✅ Removed ${fallbackItems.length} fallback items`);
            console.log(`🛒 Cart now has ${cleanCart.length} items:`, cleanCart);
            
            // Update global cart variable if it exists
            if (typeof window.cart !== 'undefined') {
                window.cart = cleanCart;
                console.log('🔄 Updated global cart variable');
            }
        }
        
    } catch (error) {
        console.error('❌ Error processing cart:', error);
    }
}

console.log('🏁 Fallback removal script completed');
console.log('💡 You can now reload the order page to see the clean state');
