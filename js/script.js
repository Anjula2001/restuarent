// Wait for DOM to fully load
document.addEventListener("DOMContentLoaded", function() {
    console.log('DOM loaded, starting initialization...');
    // Initialize backend integration
    loadMenuItems();
    loadReviews();
    setupSearch();
    setupContactForm();
    setupReservationForm();
    initializeCart();
    
    // Load order page if we're on it
    loadOrderPage();
    
    // Listen for order type changes
    const orderTypeSelect = document.getElementById('order-type');
    if (orderTypeSelect) {
        orderTypeSelect.addEventListener('change', calculateOrderTotals);
    }
    
    // Set minimum date for reservation to today
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.min = new Date().toISOString().split('T')[0];
    });

    // Add hover effects for menu items
    const menuItems = document.querySelectorAll('.main-menu li a');
    menuItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.fontWeight = 'bold';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.fontWeight = 'normal';
        });
    });
    
    // For the menu categories page
    const categoryButtons = document.querySelectorAll('.category-button');
    if (categoryButtons.length > 0) {
        categoryButtons.forEach(button => {
            button.addEventListener('click', function() {
                const category = this.textContent;
                console.log(`Selected category: ${category}`);
                
                // Scroll to the category section
                const categoryId = category.toLowerCase().replace(/\s+/g, '-');
                const categorySection = document.getElementById(categoryId);
                if (categorySection) {
                    categorySection.scrollIntoView({ behavior: 'smooth' });
                }
                
                // Highlight the selected category
                categoryButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }
    
    // Animate the hero text
    const heroH2 = document.querySelector('.hero h2');
    const heroH3 = document.querySelector('.hero h3');
    
    if (heroH2 && heroH3) {
        // Simple animation effect
        setTimeout(() => {
            heroH2.style.opacity = '1';
            heroH2.style.transform = 'translateY(0)';
        }, 300);
        
        setTimeout(() => {
            heroH3.style.opacity = '1';
            heroH3.style.transform = 'translateY(0)';
        }, 600);
    }
    
    // Add CSS for the animations 
    const style = document.createElement('style');
    style.textContent = `
        .hero h2, .hero h3 {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.8s, transform 0.8s;
        }
        .category-button.active {
            background-color: #6d4520;
            transform: translateY(-3px);
        }
    `;
    document.head.appendChild(style);
    
    // Handle photo gallery hover effects
    const photoPlaceholders = document.querySelectorAll('.photo-placeholder');
    photoPlaceholders.forEach(placeholder => {
        placeholder.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 10px 20px rgba(0,0,0,0.1)';
        });
        
        placeholder.addEventListener('mouseleave', function() {
            this.style.boxShadow = 'none';
        });
    });
    
    // Initialize form validation on page load
    setupFormValidation();
});

// Grand Restaurant Frontend JavaScript - Backend Integration
const API_BASE = 'api';

// Load menu items dynamically
async function loadMenuItems() {
    console.log('🍽️ loadMenuItems() called - Starting popular foods load');
    try {
        // Add cache busting parameter to ensure fresh data
        const url = `${API_BASE}/menu.php?popular=true&limit=3&_t=${Date.now()}`;
        console.log('🌐 Making API call to:', url);
        
        // Load popular items from the new API endpoint
        const response = await fetch(url);
        console.log('📡 API Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const popularItems = await response.json();
        console.log('📊 API Response data:', popularItems);
        console.log('📊 Data type:', Array.isArray(popularItems) ? 'Array' : typeof popularItems);
        console.log('📊 Items count:', popularItems?.length || 0);
        
        if (Array.isArray(popularItems) && popularItems.length > 0) {
            console.log('✅ API data valid, calling updatePopularFoods with data');
        } else {
            console.log('⚠️ API data empty or invalid, calling updatePopularFoods with empty array');
        }
        
        // Update popular foods section with real data from database
        updatePopularFoods(popularItems);
    } catch (error) {
        console.error('❌ Error loading popular menu items:', error);
        console.log('🔄 Falling back to static items');
        // Fallback to static items if API fails
        updatePopularFoods([]);
    }
}

// Update popular foods section
function updatePopularFoods(items) {
    console.log('🔄 updatePopularFoods() called with:', items);
    console.log('🌍 Current pathname:', window.location.pathname);
    
    // Don't interfere with menu page - check if we're on menu.html
    if (window.location.pathname.includes('menu.html')) {
        console.log('📄 On menu page - skipping popular foods update to avoid conflicts');
        return;
    }
    
    const foodCards = document.querySelector('.food-cards');
    if (!foodCards) {
        console.log('❌ No food cards container found');
        return;
    }
    console.log('✅ Found food-cards container');

    let popularItems = [];

    // Use dynamic items from database if available
    if (items && Array.isArray(items) && items.length > 0) {
        console.log('🎯 Processing dynamic items from database:', items.length, 'items');
        popularItems = items.map(item => ({
            id: item.id,
            name: item.name,
            description: item.description || `Delicious ${item.name.toLowerCase()}`,
            price: parseFloat(item.price).toFixed(2),
            image_url: item.image_url || 'images/popular/2.png' // Fallback image
        }));
        console.log('✅ Using dynamic popular items from database');
        console.log('🍽️ Dynamic items processed:', popularItems);
    } else {
        console.log('⚠️ No valid dynamic items, using fallback static items');
        console.log('📊 Items data check - items:', items, 'isArray:', Array.isArray(items), 'length:', items?.length);
        // Fallback to predefined popular items when no dynamic data available
        popularItems = [
            {
                id: 'popular-1',
                name: 'String Hoppers (FALLBACK)',
                description: 'A Sri Lankan delicacy, perfect for breakfast or dinner.',
                price: '350.00',
                image_url: 'images/popular/1.png'
            },
            {
                id: 'popular-2',
                name: 'Kottu Roti (FALLBACK)',
                description: 'A flavorful Sri Lankan street food favorite.',
                price: '750.00',
                image_url: 'images/popular/2.png'
            },
            {
                id: 'popular-3',
                name: 'Fish Ambulthiyal (FALLBACK)',
                description: 'A tangy and spicy fish curry, unique to Sri Lanka.',
                price: '950.00',
                image_url: 'images/popular/3.png'
            }
        ];
        console.log('🔄 Using fallback static popular items');
    }

    foodCards.innerHTML = popularItems.map(item => `
        <div class="food-card">
            <img src="${item.image_url}" alt="${item.name}" 
                 onerror="console.log('Popular image failed: ${item.image_url}'); this.src='images/popular/2.png';">
            <div class="food-card-content">
                <h3>${item.name}</h3>
                <p>${item.description}</p>
                <p class="food-price">Rs. ${item.price}</p>
                <div class="food-card-actions">
                    <div class="quantity-selector">
                        <label>Qty:</label>
                        <input type="number" class="quantity-input" min="1" max="10" value="1" id="qty-${item.id}">
                    </div>
                    <button onclick="addToCartWithQuantity('${item.id}', '${item.name}', ${item.price})" class="add-to-cart-btn">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>    `).join('');
    
    console.log('🎨 Popular foods section updated successfully with', popularItems.length, 'items');
    console.log('🎯 Final items displayed:', popularItems.map(item => item.name));
    
    // Additional verification
    setTimeout(() => {
        const updatedCards = document.querySelectorAll('.food-cards .food-card');
        console.log('🔍 Verification: Found', updatedCards.length, 'cards in DOM');
        if (updatedCards.length > 0) {
            const firstCardName = updatedCards[0].querySelector('h3')?.textContent;
            console.log('🏆 First card name:', firstCardName);
            if (firstCardName && !firstCardName.includes('FALLBACK')) {
                console.log('✅ SUCCESS: API items are displayed!');
            } else {
                console.log('⚠️ WARNING: Fallback items are displayed');
            }
        }
    }, 100);
}

// Load customer reviews dynamically with carousel functionality
let allCustomerReviews = [];
let currentReviewSlide = 0;
let reviewsPerSlide = 3;

async function loadReviews() {
    console.log('Loading customer reviews...');
    try {
        const response = await fetch(`${API_BASE}/reviews.php`); // Get all reviews
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const reviews = await response.json();
        console.log('Reviews loaded:', reviews);
        
        allCustomerReviews = reviews;
        initializeReviewsCarousel();
    } catch (error) {
        console.error('Error loading reviews:', error);
        showReviewsError();
    }
}

function initializeReviewsCarousel() {
    if (!allCustomerReviews || allCustomerReviews.length === 0) {
        showNoReviews();
        return;
    }

    updateReviewsCarousel();
    setupCarouselIndicators();
    setupCarouselEventListeners();
    
    // Auto-rotate carousel every 5 seconds
    setInterval(() => {
        if (allCustomerReviews.length > reviewsPerSlide) {
            nextReviews();
        }
    }, 5000);
}

function setupCarouselEventListeners() {
    const prevBtn = document.getElementById('prevReview');
    const nextBtn = document.getElementById('nextReview');
    
    if (prevBtn) {
        prevBtn.addEventListener('click', previousReviews);
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', nextReviews);
    }
}

function updateReviewsCarousel() {
    const carousel = document.getElementById('reviewsCarousel');
    if (!carousel) return;

    const totalSlides = Math.ceil(allCustomerReviews.length / reviewsPerSlide);
    
    // Create slides
    let slidesHTML = '';
    for (let i = 0; i < totalSlides; i++) {
        const slideStart = i * reviewsPerSlide;
        const slideEnd = Math.min(slideStart + reviewsPerSlide, allCustomerReviews.length);
        const slideReviews = allCustomerReviews.slice(slideStart, slideEnd);
        
        slidesHTML += `
            <div class="review-slide">
                ${slideReviews.map(review => `
                    <div class="review-card">
                        <div class="review-text">${review.review_text}</div>
                        <div class="reviewer-info">
                            <div class="reviewer-name">${review.customer_name}</div>
                            <div class="reviewer-rating">${'★'.repeat(review.rating)}</div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }
    
    carousel.innerHTML = slidesHTML;
    
    // Update navigation buttons
    updateNavigationButtons(totalSlides);
}

function setupCarouselIndicators() {
    const indicatorsContainer = document.getElementById('carouselIndicators');
    if (!indicatorsContainer || allCustomerReviews.length <= reviewsPerSlide) {
        indicatorsContainer.style.display = 'none';
        return;
    }
    
    const totalSlides = Math.ceil(allCustomerReviews.length / reviewsPerSlide);
    let indicatorsHTML = '';
      for (let i = 0; i < totalSlides; i++) {
        indicatorsHTML += `
            <div class="indicator ${i === currentReviewSlide ? 'active' : ''}" 
                 data-slide="${i}"></div>
        `;
    }
    
    indicatorsContainer.innerHTML = indicatorsHTML;
    indicatorsContainer.style.display = 'flex';
    
    // Add click event listeners to indicators
    const indicators = indicatorsContainer.querySelectorAll('.indicator');
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => goToReviewSlide(index));
    });
}

function updateNavigationButtons(totalSlides) {
    const prevBtn = document.getElementById('prevReview');
    const nextBtn = document.getElementById('nextReview');
    
    if (totalSlides <= 1) {
        prevBtn.style.display = 'none';
        nextBtn.style.display = 'none';
        return;
    }
    
    prevBtn.style.display = 'flex';
    nextBtn.style.display = 'flex';
    prevBtn.disabled = currentReviewSlide === 0;
    nextBtn.disabled = currentReviewSlide === totalSlides - 1;
}

function previousReviews() {
    if (currentReviewSlide > 0) {
        currentReviewSlide--;
        animateCarousel();
        updateIndicators();
        updateNavigationButtons(Math.ceil(allCustomerReviews.length / reviewsPerSlide));
    }
}

function nextReviews() {
    const totalSlides = Math.ceil(allCustomerReviews.length / reviewsPerSlide);
    if (currentReviewSlide < totalSlides - 1) {
        currentReviewSlide++;
        animateCarousel();
        updateIndicators();
        updateNavigationButtons(totalSlides);
    } else {
        // Loop back to first slide
        currentReviewSlide = 0;
        animateCarousel();
        updateIndicators();
        updateNavigationButtons(totalSlides);
    }
}

function goToReviewSlide(slideIndex) {
    currentReviewSlide = slideIndex;
    animateCarousel();
    updateIndicators();
    updateNavigationButtons(Math.ceil(allCustomerReviews.length / reviewsPerSlide));
}

function animateCarousel() {
    const carousel = document.getElementById('reviewsCarousel');
    if (!carousel) return;
    
    const translateX = -currentReviewSlide * 100;
    carousel.style.transform = `translateX(${translateX}%)`;
}

function updateIndicators() {
    const indicators = document.querySelectorAll('.indicator');
    indicators.forEach((indicator, index) => {
        indicator.classList.toggle('active', index === currentReviewSlide);
    });
}

function showNoReviews() {
    const carousel = document.getElementById('reviewsCarousel');
    if (!carousel) return;
    
    carousel.innerHTML = `
        <div class="review-slide">
            <div class="review-loading">
                No reviews available at the moment. 
                <a href="contact.html" style="color: #8B5A2B;">Be the first to leave a review!</a>
            </div>
        </div>
    `;
    
    // Hide navigation
    document.getElementById('prevReview').style.display = 'none';
    document.getElementById('nextReview').style.display = 'none';
    document.getElementById('carouselIndicators').style.display = 'none';
}

function showReviewsError() {
    const carousel = document.getElementById('reviewsCarousel');
    if (!carousel) return;
    
    carousel.innerHTML = `
        <div class="review-slide">
            <div class="review-loading">
                Unable to load reviews at the moment. Please try again later.
            </div>
        </div>
    `;
    
    // Hide navigation
    document.getElementById('prevReview').style.display = 'none';
    document.getElementById('nextReview').style.display = 'none';
    document.getElementById('carouselIndicators').style.display = 'none';
}

// Legacy function for backward compatibility
function updateReviewsSection(reviews) {
    // This function is now handled by the carousel system
    allCustomerReviews = reviews || [];
    initializeReviewsCarousel();
}

// Search functionality
function setupSearch() {
    const searchInput = document.querySelector('.search-bar input');
    if (!searchInput) return;

    searchInput.addEventListener('input', debounce(async (e) => {
        const searchTerm = e.target.value.trim();
        if (searchTerm.length < 2) {
            loadMenuItems(); // Reset to original items
            return;
        }

        try {
            const response = await fetch(`${API_BASE}/menu.php?search=${encodeURIComponent(searchTerm)}`);
            const searchResults = await response.json();
            updatePopularFoods(searchResults.slice(0, 3));
        } catch (error) {
            console.error('Error searching menu:', error);
        }
    }, 300));
}

// Debounce function for search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Enhanced Cart System
let cart = JSON.parse(localStorage.getItem('restaurant_cart')) || [];

// Initialize cart functionality
function initializeCart() {
    updateCartUI();
    setupCartEventListeners();
    
    // Load cart from localStorage on page load
    if (cart.length > 0) {
        updateCartUI();
    }
}

// Setup cart event listeners
function setupCartEventListeners() {
    const cartToggle = document.getElementById('cart-toggle');
    const cartDropdown = document.getElementById('cart-dropdown');
    const closeCart = document.getElementById('close-cart');
    
    if (cartToggle) {
        cartToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleCartDropdown();
        });
    }
    
    if (closeCart) {
        closeCart.addEventListener('click', () => {
            hideCartDropdown();
        });
    }
    
    // Close cart when clicking outside
    document.addEventListener('click', (e) => {
        if (cartDropdown && !cartDropdown.contains(e.target) && !cartToggle.contains(e.target)) {
            hideCartDropdown();
        }
    });
}

// Toggle cart dropdown visibility
function toggleCartDropdown() {
    const cartDropdown = document.getElementById('cart-dropdown');
    if (cartDropdown) {
        if (cartDropdown.style.display === 'none' || !cartDropdown.style.display) {
            cartDropdown.style.display = 'block';
            updateCartDropdown();
        } else {
            cartDropdown.style.display = 'none';
        }
    }
}

// Hide cart dropdown
function hideCartDropdown() {
    const cartDropdown = document.getElementById('cart-dropdown');
    if (cartDropdown) {
        cartDropdown.style.display = 'none';
    }
}

// Add item to cart with quantity
function addToCartWithQuantity(itemId, name, price) {
    const quantityInput = document.getElementById(`qty-${itemId}`);
    const quantity = quantityInput ? parseInt(quantityInput.value) || 1 : 1;
    
    const existingItem = cart.find(item => item.id === itemId);
    
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cart.push({
            id: itemId,
            name: name,
            price: price,
            quantity: quantity
        });
    }
    
    // Reset quantity input
    if (quantityInput) {
        quantityInput.value = 1;
    }
    
    saveCartToStorage();
    updateCartUI();
    showNotification(`${name} (${quantity}) added to cart!`);
}

// Legacy function for compatibility
function addToCart(itemId, name, price) {
    addToCartWithQuantity(itemId, name, price);
}

// Update cart quantity
function updateCartQuantity(itemId, newQuantity) {
    const item = cart.find(item => item.id === itemId);
    if (item) {
        if (newQuantity <= 0) {
            removeFromCart(itemId);
        } else {
            item.quantity = newQuantity;
            saveCartToStorage();
            updateCartUI();
        }
    }
}

// Remove item from cart
function removeFromCart(itemId) {
    cart = cart.filter(item => item.id !== itemId);
    saveCartToStorage();
    updateCartUI();
    showNotification('Item removed from cart');
}

// Clear entire cart
function clearCart() {
    cart = [];
    saveCartToStorage();
    updateCartUI();
    showNotification('Cart cleared');
}

// Save cart to localStorage
function saveCartToStorage() {
    localStorage.setItem('restaurant_cart', JSON.stringify(cart));
}

// Update cart UI elements
function updateCartUI() {
    updateCartCount();
    updateCartDropdown();
}

// Update cart count badge
function updateCartCount() {
    const cartCount = document.getElementById('cart-count');
    if (cartCount) {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCount.textContent = totalItems;
        cartCount.style.display = totalItems > 0 ? 'flex' : 'none';
    }
}

// Update cart dropdown content
function updateCartDropdown() {
    const cartItems = document.getElementById('cart-items');
    const cartFooter = document.getElementById('cart-footer');
    const cartTotal = document.getElementById('cart-total');
    
    if (!cartItems) return;
    
    if (cart.length === 0) {
        cartItems.innerHTML = '<p class="empty-cart">Your cart is empty</p>';
        if (cartFooter) cartFooter.style.display = 'none';
        return;
    }
    
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    cartItems.innerHTML = cart.map(item => `
        <div class="cart-item">
            <div class="cart-item-info">
                <div class="cart-item-name">${item.name}</div>
                <div class="cart-item-price">Rs. ${item.price} each</div>
            </div>
            <div class="quantity-controls">
                <button class="quantity-btn" onclick="updateCartQuantity(${item.id}, ${item.quantity - 1})">-</button>
                <span class="quantity-number">${item.quantity}</span>
                <button class="quantity-btn" onclick="updateCartQuantity(${item.id}, ${item.quantity + 1})">+</button>
            </div>
            <button class="remove-item" onclick="removeFromCart(${item.id})">×</button>
        </div>
    `).join('');
    
    if (cartTotal) cartTotal.textContent = total.toFixed(2);
    if (cartFooter) cartFooter.style.display = 'block';
}

// Navigate to order page
function goToOrderPage() {
    if (cart.length === 0) {
        showNotification('Your cart is empty!');
        return;
    }
    window.location.href = 'order.html';
}

async function checkout() {
    if (cart.length === 0) {
        alert('Your cart is empty');
        return;
    }

    const customerName = prompt('Enter your name:');
    const customerEmail = prompt('Enter your email:');
    const customerPhone = prompt('Enter your phone number:');
    const orderType = confirm('Click OK for Delivery, Cancel for Pickup') ? 'delivery' : 'pickup';
    
    let deliveryAddress = '';
    if (orderType === 'delivery') {
        deliveryAddress = prompt('Enter your delivery address:');
        if (!deliveryAddress) {
            alert('Delivery address is required');
            return;
        }
    }

    const orderData = {
        customer_name: customerName,
        customer_email: customerEmail,
        customer_phone: customerPhone,
        delivery_address: deliveryAddress,
        order_type: orderType,
        items: cart.map(item => ({
            menu_item_id: item.id,
            quantity: item.quantity,
            price: item.price
        }))
    };

    try {
        const response = await fetch(`${API_BASE}/orders.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(orderData)
        });

        const result = await response.json();
        
        if (response.ok) {
            alert(`Order placed successfully! Order ID: #${result.order_id}`);
            cart = [];
            updateCartDisplay();
        } else {
            alert(result.error || 'Failed to place order');
        }
    } catch (error) {
        alert('Error placing order. Please try again.');
    }
}

// Enhanced form validation with visual feedback
function validateOrderForm() {
    const form = document.getElementById('customer-form');
    const formData = new FormData(form);
    let isValid = true;
    const errors = {};
    
    // Clear previous validation states
    document.querySelectorAll('.form-group').forEach(group => {
        group.classList.remove('error', 'success');
        const errorMsg = group.querySelector('.error-message');
        if (errorMsg) errorMsg.style.display = 'none';
    });
    
    // Validate name
    const name = formData.get('name');
    if (!name || name.trim().length < 2) {
        errors.name = 'Please enter a valid full name (at least 2 characters)';
        isValid = false;
    }
    
    // Validate email
    const email = formData.get('email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email || !emailRegex.test(email)) {
        errors.email = 'Please enter a valid email address';
        isValid = false;
    }
    
    // Validate phone
    const phone = formData.get('phone');
    const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
    if (!phone || !phoneRegex.test(phone)) {
        errors.phone = 'Please enter a valid phone number (at least 10 digits)';
        isValid = false;
    }
    
    // Validate order type
    const orderType = formData.get('order_type');
    if (!orderType) {
        errors.order_type = 'Please select an order type';
        isValid = false;
    }
    
    // Validate delivery address if delivery is selected
    if (orderType === 'delivery') {
        const address = formData.get('delivery_address');
        if (!address || address.trim().length < 10) {
            errors.delivery_address = 'Please provide a complete delivery address (at least 10 characters)';
            isValid = false;
        }
    }
    
    // Display validation results
    Object.keys(errors).forEach(fieldName => {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (field) {
            const formGroup = field.closest('.form-group');
            formGroup.classList.add('error');
            
            let errorMsg = formGroup.querySelector('.error-message');
            if (!errorMsg) {
                errorMsg = document.createElement('div');
                errorMsg.className = 'error-message';
                formGroup.appendChild(errorMsg);
            }
            errorMsg.textContent = errors[fieldName];
            errorMsg.style.display = 'block';
        }
    });
    
    // Mark valid fields as success
    if (isValid) {
        document.querySelectorAll('.form-group').forEach(group => {
            const input = group.querySelector('input, select, textarea');
            if (input && input.value.trim()) {
                group.classList.add('success');
            }
        });
    }
    
    return isValid;
}

// Real-time form validation
function setupFormValidation() {
    const form = document.getElementById('customer-form');
    if (!form) return;
    
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', () => validateField(input));
        input.addEventListener('input', () => {
            // Clear error state on input
            const formGroup = input.closest('.form-group');
            if (formGroup.classList.contains('error')) {
                formGroup.classList.remove('error');
                const errorMsg = formGroup.querySelector('.error-message');
                if (errorMsg) errorMsg.style.display = 'none';
            }
        });
    });
}

function validateField(field) {
    const formGroup = field.closest('.form-group');
    const fieldName = field.name;
    const value = field.value.trim();
    
    let isValid = true;
    let errorMessage = '';
    
    switch (fieldName) {
        case 'name':
            if (!value || value.length < 2) {
                isValid = false;
                errorMessage = 'Please enter a valid full name';
            }
            break;
        case 'email':
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!value || !emailRegex.test(value)) {
                isValid = false;
                errorMessage = 'Please enter a valid email address';
            }
            break;
        case 'phone':
            const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
            if (!value || !phoneRegex.test(value)) {
                isValid = false;
                errorMessage = 'Please enter a valid phone number';
            }
            break;
        case 'order_type':
            if (!value) {
                isValid = false;
                errorMessage = 'Please select an order type';
            }
            break;
        case 'delivery_address':
            if (field.closest('#delivery-address-group').style.display !== 'none') {
                if (!value || value.length < 10) {
                    isValid = false;
                    errorMessage = 'Please provide a complete delivery address';
                }
            }
            break;
    }
    
    formGroup.classList.remove('error', 'success');
    const errorMsg = formGroup.querySelector('.error-message');
    if (errorMsg) errorMsg.style.display = 'none';
    
    if (!isValid) {
        formGroup.classList.add('error');
        let errorElement = formGroup.querySelector('.error-message');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'error-message';
            formGroup.appendChild(errorElement);
        }
        errorElement.textContent = errorMessage;
        errorElement.style.display = 'block';
    } else if (value) {
        formGroup.classList.add('success');
    }
}

// Enhanced notification system
function showNotification(message, type = 'error', duration = 5000) {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => {
        notification.remove();
    });
    
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Trigger show animation
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Auto-hide after duration
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, duration);
}

// Loading overlay functions
function showLoadingOverlay(container) {
    const overlay = document.createElement('div');
    overlay.className = 'loading-overlay';
    overlay.innerHTML = '<div class="spinner"></div>';
    container.style.position = 'relative';
    container.appendChild(overlay);
}

function hideLoadingOverlay(container) {
    const overlay = container.querySelector('.loading-overlay');
    if (overlay) {
        overlay.remove();
    }
}

// Enhanced toggle delivery address with animation
function toggleDeliveryAddress() {
    const orderType = document.getElementById('order-type').value;
    const addressGroup = document.getElementById('delivery-address-group');
    const addressField = document.getElementById('delivery-address');
    
    if (orderType === 'delivery') {
        addressGroup.style.display = 'block';
        addressField.required = true;
        // Animate in
        addressGroup.style.opacity = '0';
        addressGroup.style.transform = 'translateY(-10px)';
        setTimeout(() => {
            addressGroup.style.transition = 'all 0.3s ease';
            addressGroup.style.opacity = '1';
            addressGroup.style.transform = 'translateY(0)';
        }, 10);
    } else {
        addressField.required = false;
        // Animate out
        addressGroup.style.transition = 'all 0.3s ease';
        addressGroup.style.opacity = '0';
        addressGroup.style.transform = 'translateY(-10px)';
        setTimeout(() => {
            addressGroup.style.display = 'none';
        }, 300);
    }
    
    // Recalculate totals
    calculateOrderTotals();
}

// Load and display order page content
function loadOrderPage() {
    const orderContentWrapper = document.getElementById('order-content-wrapper');
    const emptyCartMessage = document.getElementById('empty-cart-message');
    
    if (!orderContentWrapper || !emptyCartMessage) return;
    
    if (cart.length === 0) {
        // Show empty cart message
        emptyCartMessage.style.display = 'block';
        orderContentWrapper.style.display = 'none';
    } else {
        // Show order content
        emptyCartMessage.style.display = 'none';
        orderContentWrapper.style.display = 'grid';
        
        // Load cart items into order page
        displayOrderItems();
        calculateOrderTotals();
        
        // Setup form validation for order page
        setupFormValidation();
    }
}

// Display cart items in the order page
function displayOrderItems() {
    const orderItemsContainer = document.getElementById('order-items-container');
    if (!orderItemsContainer) return;
    
    orderItemsContainer.innerHTML = cart.map(item => `
        <div class="order-item" data-item-id="${item.id}">
            <img src="images/popular/3.png" alt="${item.name}" class="order-item-image">
            <div class="order-item-details">
                <h4 class="order-item-name">${item.name}</h4>
                <div class="order-item-price">Rs. ${item.price} each</div>
                <div class="order-item-quantity">Quantity: ${item.quantity}</div>
                <div class="order-item-subtotal">Subtotal: Rs. ${(item.price * item.quantity).toFixed(2)}</div>
            </div>
            <div class="order-item-controls">
                <div class="quantity-controls-order">
                    <button class="quantity-btn-order" onclick="updateOrderQuantity(${item.id}, ${item.quantity - 1})" ${item.quantity <= 1 ? 'disabled' : ''}>-</button>
                    <span class="quantity-display">${item.quantity}</span>
                    <button class="quantity-btn-order" onclick="updateOrderQuantity(${item.id}, ${item.quantity + 1})">+</button>
                </div>
                <button class="remove-order-item" onclick="removeFromOrderPage(${item.id})">Remove</button>
            </div>
        </div>
    `).join('');
}

// Update item quantity on order page
function updateOrderQuantity(itemId, newQuantity) {
    if (newQuantity <= 0) {
        removeFromOrderPage(itemId);
        return;
    }
    
    const item = cart.find(item => item.id === itemId);
    if (item) {
        item.quantity = newQuantity;
        saveCartToStorage();
        updateCartUI();
        
        // Update order page display
        displayOrderItems();
        calculateOrderTotals();
        
        showNotification(`Quantity updated to ${newQuantity}`, 'success', 2000);
    }
}

// Remove item from order page
function removeFromOrderPage(itemId) {
    const item = cart.find(item => item.id === itemId);
    if (item && confirm(`Remove ${item.name} from your order?`)) {
        cart = cart.filter(item => item.id !== itemId);
        saveCartToStorage();
        updateCartUI();
        
        // Refresh order page
        loadOrderPage();
        
        showNotification(`${item.name} removed from cart`, 'success', 2000);
    }
}

// Calculate and display order totals
function calculateOrderTotals() {
    const totalItemsElement = document.getElementById('total-items');
    const subtotalElement = document.getElementById('subtotal');
    const deliveryFeeElement = document.getElementById('delivery-fee');
    const taxAmountElement = document.getElementById('tax-amount');
    const finalTotalElement = document.getElementById('final-total');
    
    if (!totalItemsElement || !subtotalElement) return;
    
    // Calculate totals
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    // Delivery fee based on order type
    const orderTypeSelect = document.getElementById('order-type');
    const orderType = orderTypeSelect ? orderTypeSelect.value : '';
    const deliveryFee = orderType === 'delivery' ? 50.00 : 0.00;
    
    // Tax calculation (5%)
    const taxRate = 0.05;
    const taxAmount = subtotal * taxRate;
    
    // Final total
    const finalTotal = subtotal + deliveryFee + taxAmount;
    
    // Update display
    totalItemsElement.textContent = totalItems;
    subtotalElement.textContent = subtotal.toFixed(2);
    
    if (deliveryFeeElement) {
        deliveryFeeElement.textContent = deliveryFee.toFixed(2);
    }
    
    if (taxAmountElement) {
        taxAmountElement.textContent = taxAmount.toFixed(2);
    }
    
    if (finalTotalElement) {
        finalTotalElement.textContent = finalTotal.toFixed(2);
    }
}

// Enhanced placeOrder function with validation and loading states
async function placeOrder() {
    // Check if cart has items
    if (!cart || cart.length === 0) {
        showNotification('Your cart is empty! Please add some items before placing an order.', 'warning');
        return;
    }

    // Validate form first
    if (!validateOrderForm()) {
        showNotification('Please fix the errors in your form before placing the order.', 'error');
        return;
    }

    const form = document.getElementById('customer-form');
    const formData = new FormData(form);
    const submitButton = document.querySelector('.place-order-button');
    const originalText = submitButton.textContent;
    
    try {
        // Disable button and show loading state
        submitButton.disabled = true;
        submitButton.textContent = '🔄 Placing Order...';
        
        // Show loading overlay
        const orderSummary = document.getElementById('order-summary-container');
        showLoadingOverlay(orderSummary);

        // Prepare order data
        const orderData = {
            customer_name: formData.get('name').trim(),
            customer_email: formData.get('email').trim(),
            customer_phone: formData.get('phone').trim(),
            order_type: formData.get('order_type'),
            delivery_address: formData.get('delivery_address') || '',
            special_instructions: formData.get('special_instructions') || '',
            items: cart.map(item => ({
                menu_item_id: item.id,
                quantity: item.quantity,
                price: item.price
            }))
        };

        console.log('Placing order:', orderData);

        // Send order to server
        const response = await fetch(`${API_BASE}/orders.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(orderData)
        });

        const result = await response.json();
        
        if (response.ok && result.success) {
            // Success - show confirmation
            showNotification(
                `🎉 Order placed successfully! Order ID: #${result.order_id}. You will receive a confirmation email shortly.`, 
                'success', 
                8000
            );
            
            // Clear cart and reset form
            cart = [];
            saveCartToStorage();
            updateCartUI();
              // Reset form
            form.reset();
            document.querySelectorAll('.form-group').forEach(group => {
                group.classList.remove('error', 'success');
                const errorMsg = group.querySelector('.error-message');
                if (errorMsg) errorMsg.style.display = 'none';
            });
            
            // Refresh page after short delay to show clean state
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            // Error - show error message
            showNotification(
                result.error || 'Failed to place order. Please try again.', 
                'error', 
                5000
            );
        }
        
    } catch (error) {
        console.error('Order placement error:', error);
        showNotification(
            'An error occurred while placing your order. Please check your connection and try again.', 
            'error', 
            5000
        );
    } finally {
        // Reset button state
        submitButton.disabled = false;
        submitButton.textContent = originalText;
        
        // Hide loading overlay
        const orderSummary = document.getElementById('order-summary-container');
        hideLoadingOverlay(orderSummary);
    }
}

// Enhanced clear cart confirmation
function clearCartConfirm() {
    if (cart.length === 0) {
        showNotification('Your cart is already empty!', 'warning');
        return;
    }
    
    if (confirm('Are you sure you want to clear your cart? This action cannot be undone.')) {
        clearCart();
        loadOrderPage(); // Refresh the page to show empty state
        showNotification('Cart cleared successfully!', 'success');
    }
}

// Setup reservation form handling
function setupReservationForm() {
    const reservationForm = document.getElementById('reservationForm');
    if (!reservationForm) {
        console.log('Reservation form not found on this page');
        return;
    }

    console.log('Setting up reservation form...');
    
    reservationForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitButton = this.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        
        // Disable button to prevent double submission
        submitButton.disabled = true;
        submitButton.innerHTML = '⏳ Processing...';
        
        // Collect form data
        const formData = new FormData(this);
        const reservationData = {
            name: formData.get('name'),
            email: formData.get('email'),
            phone: formData.get('phone'),
            date: formData.get('date'),
            time: formData.get('time'),
            guests: formData.get('guests'),
            occasion: formData.get('occasion') || '',
            special_requests: formData.get('special_requests') || ''
        };
        
        // Basic validation
        if (!reservationData.name || !reservationData.email || !reservationData.phone || 
            !reservationData.date || !reservationData.time || !reservationData.guests) {
            showNotification('Please fill in all required fields.', 'error');
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
            return;
        }
        
        // Validate date is not in the past
        const selectedDate = new Date(reservationData.date);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate < today) {
            showNotification('Please select a future date for your reservation.', 'error');
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
            return;
        }
        
        try {
            console.log('Submitting reservation:', reservationData);
            
            const response = await fetch('api/reservations.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(reservationData)
            });
            
            const result = await response.json();
            console.log('Reservation response:', result);
            
            if (result.success) {
                showNotification(
                    `🎉 Reservation confirmed! Your table for ${reservationData.guests} guests on ${reservationData.date} at ${reservationData.time} has been reserved. Confirmation details sent to ${reservationData.email}.`,
                    'success'
                );
                
                // Reset form after successful submission
                this.reset();
                
                // Optional: Redirect to confirmation page or show more details
                setTimeout(() => {
                    if (confirm('Would you like to view your reservation details?')) {
                        // You could redirect to a reservations page here
                        console.log('Reservation ID:', result.reservation_id);
                    }
                }, 2000);
                
            } else {
                showNotification(
                    result.message || 'Failed to make reservation. Please try again or call us directly.',
                    'error'
                );
            }
            
        } catch (error) {
            console.error('Reservation submission error:', error);
            showNotification(
                'Network error occurred. Please check your connection and try again, or call us directly at (555) 123-4567.',
                'error'
            );
        } finally {
            // Re-enable submit button
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        }
    });
}

// Setup contact form handling (placeholder for future implementation)
function setupContactForm() {
    const contactForm = document.getElementById('contactForm');
    if (!contactForm) {
        console.log('Contact form not found on this page');
        return;
    }
    
    console.log('Setting up contact form...');
    
    // TODO: Implement contact form submission when contact form is added to contact.html
    contactForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        showNotification('Contact form functionality coming soon!', 'info');
    });
}

// Export functions to global scope for HTML onclick access
window.placeOrder = placeOrder;
window.clearCartConfirm = clearCartConfirm;
window.updateOrderQuantity = updateOrderQuantity;
window.removeFromOrderPage = removeFromOrderPage;
window.toggleDeliveryAddress = toggleDeliveryAddress;
window.loadOrderPage = loadOrderPage;
window.setupReservationForm = setupReservationForm;
window.setupContactForm = setupContactForm;