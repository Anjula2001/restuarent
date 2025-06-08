// Manual test script to verify cart image functionality
// Run this in browser console on menu.html page

console.log('🧪 Starting Cart Image Verification Test');

// Test 1: Check if new function exists
console.log('\n📋 Test 1: Function Availability');
if (typeof addMenuItemToCart === 'function') {
    console.log('✅ addMenuItemToCart function is available');
} else {
    console.log('❌ addMenuItemToCart function NOT found');
}

// Test 2: Load menu items and test addition
console.log('\n📋 Test 2: Menu Item Addition Test');

async function testMenuItemAddition() {
    try {
        // Clear cart first
        localStorage.removeItem('cart');
        console.log('🗑️ Cart cleared');
        
        // Fetch menu items
        const response = await fetch('api/menu.php');
        const menuItems = await response.json();
        console.log(`📄 Loaded ${menuItems.length} menu items`);
        
        // Find an uploaded item
        const uploadedItem = menuItems.find(item => 
            item.image_url && item.image_url.includes('uploads/menu-items/')
        );
        
        if (uploadedItem) {
            console.log('🎯 Found uploaded item:', uploadedItem.name);
            console.log('🖼️ Original image URL:', uploadedItem.image_url);
            
            // Add to cart using new function
            addMenuItemToCart(uploadedItem.id, uploadedItem.name, uploadedItem.price, uploadedItem.image_url);
            
            // Check cart contents
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const cartItem = cart.find(item => item.id == uploadedItem.id);
            
            if (cartItem) {
                console.log('✅ Item added to cart successfully');
                console.log('🖼️ Cart image URL:', cartItem.image);
                
                if (cartItem.image === uploadedItem.image_url) {
                    console.log('🎉 SUCCESS: Image URL preserved correctly!');
                } else {
                    console.log('❌ FAILURE: Image URL was modified');
                    console.log('   Expected:', uploadedItem.image_url);
                    console.log('   Got:', cartItem.image);
                }
            } else {
                console.log('❌ Item not found in cart');
            }
        } else {
            console.log('⚠️ No uploaded items found, testing with fallback');
            const testItem = menuItems[0];
            if (testItem) {
                addMenuItemToCart(testItem.id, testItem.name, testItem.price, 'images/popular/2.png');
                console.log('✅ Fallback test completed');
            }
        }
        
    } catch (error) {
        console.log('❌ Test error:', error.message);
    }
}

// Test 3: Simulate order page display
console.log('\n📋 Test 3: Order Page Display Simulation');

function simulateOrderPageDisplay() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    
    console.log(`🛒 Cart contains ${cart.length} items:`);
    
    cart.forEach((item, index) => {
        console.log(`  ${index + 1}. ${item.name}`);
        console.log(`     Image: ${item.image}`);
        
        if (item.image.includes('uploads/menu-items/')) {
            console.log('     ✅ Type: Uploaded image (CORRECT)');
        } else if (item.image.includes('images/popular/')) {
            console.log('     ⚠️ Type: Fallback image');
        } else {
            console.log('     ❓ Type: Unknown');
        }
    });
}

// Run tests
testMenuItemAddition().then(() => {
    console.log('\n📋 Test 3: Order Page Display Simulation');
    simulateOrderPageDisplay();
    
    console.log('\n🎯 Test Complete - Check results above');
});
