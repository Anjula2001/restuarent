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
});

// Grand Restaurant Frontend JavaScript - Backend Integration
const API_BASE = 'api';

// Load menu items dynamically
async function loadMenuItems() {
    try {
        const response = await fetch(`${API_BASE}/menu.php`);
        const menuItems = await response.json();
        
        // Update popular foods section with real data
        updatePopularFoods(menuItems.slice(0, 3));
    } catch (error) {
        console.error('Error loading menu items:', error);
    }
}

// Update popular foods section
function updatePopularFoods(items) {
    // Don't interfere with menu page - check if we're on menu.html
    if (window.location.pathname.includes('menu.html')) {
        console.log('On menu page - skipping popular foods update to avoid conflicts');
        return;
    }
    
    const foodCards = document.querySelector('.food-cards');
    if (!foodCards || items.length === 0) {
        console.log('No food cards container found or no items to display');
        return;
    }

    console.log('Updating popular foods with items:', items);

    // Use predefined popular items with correct images instead of random items
    const popularItems = [
        {
            id: 'popular-1',
            name: 'String Hoppers',
            description: 'A Sri Lankan delicacy, perfect for breakfast or dinner.',
            price: '350.00',
            image_url: 'images/popular/1.png'
        },
        {
            id: 'popular-2', 
            name: 'Kottu Roti',
            description: 'A flavorful Sri Lankan street food favorite.',
            price: '750.00',
            image_url: 'images/popular/2.png'
        },
        {
            id: 'popular-3',
            name: 'Fish Ambulthiyal', 
            description: 'A tangy and spicy fish curry, unique to Sri Lanka.',
            price: '950.00',
            image_url: 'images/popular/3.png'
        }
    ];

    foodCards.innerHTML = popularItems.map(item => `
        <div class="food-card">
            <img src="${item.image_url}" alt="${item.name}" 
                 onerror="console.log('Popular image failed: ${item.image_url}'); this.src='images/2.png';">
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
        </div>
    `).join('');
    
    console.log('Popular foods section updated successfully');
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

// Notification system
function showNotification(message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #28a745;
        color: white;
        padding: 15px 20px;
        border-radius: 5px;
        z-index: 1000;
        animation: slideIn 0.3s ease-out;
    `;
    
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Contact form submission (review submission)
function setupContactForm() {
    const contactForm = document.querySelector('#contactForm');
    if (!contactForm) return;

    contactForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(contactForm);
        const reviewData = {
            customer_name: formData.get('name'),
            customer_email: formData.get('email'),
            rating: parseInt(formData.get('rating')) || 5,
            review_text: formData.get('message')
        };

        try {
            const response = await fetch(`${API_BASE}/reviews.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(reviewData)
            });

            const result = await response.json();
            
            if (response.ok) {
                showNotification('Thank you for your review! It will be published after approval.');
                contactForm.reset();
            } else {
                alert(result.error || 'Failed to submit review');
            }
        } catch (error) {
            alert('Error submitting review. Please try again.');
        }
    });
}

// Reservation form functionality
function setupReservationForm() {
    console.log('Setting up reservation form...');
    const reservationForm = document.querySelector('#reservationForm, .reservation-form');
    console.log('Found reservation form:', reservationForm);
    if (!reservationForm) {
        console.log('No reservation form found');
        return;
    }    reservationForm.addEventListener('submit', async (e) => {
        console.log('Form submit event triggered!');
        e.preventDefault();
        
        // Prevent multiple submissions
        const submitButton = reservationForm.querySelector('button[type="submit"]');
        if (submitButton.disabled) {
            console.log('Form submission already in progress, ignoring...');
            return;
        }
        
        // Disable submit button and change text
        submitButton.disabled = true;
        const originalText = submitButton.textContent;
        submitButton.textContent = 'Submitting...';
        
        const formData = new FormData(reservationForm);
        const reservationData = {
            name: formData.get('name'),
            email: formData.get('email'),
            phone: formData.get('phone'),
            date: formData.get('date'),
            time: formData.get('time'),
            guests: parseInt(formData.get('guests')),
            special_requests: formData.get('special_requests') || ''
        };

        console.log('Reservation data:', reservationData);

        // Validate required fields
        const requiredFields = ['name', 'email', 'phone', 'date', 'time', 'guests'];
        for (const field of requiredFields) {
            if (!reservationData[field]) {
                alert(`Please fill in the ${field.replace('_', ' ')} field`);
                // Re-enable button on validation error
                submitButton.disabled = false;
                submitButton.textContent = originalText;
                return;
            }
        }

        try {
            console.log('Sending request to:', `${API_BASE}/reservations.php`);
            const response = await fetch(`${API_BASE}/reservations.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(reservationData)
            });

            console.log('Response status:', response.status);
            const result = await response.json();
            console.log('Response data:', result);
            
            if (response.ok) {
                showNotification('Reservation request submitted successfully! We will confirm shortly.');
                reservationForm.reset();
            } else {
                alert(result.error || 'Failed to submit reservation');
            }
        } catch (error) {
            console.error('Reservation submission error:', error);
            alert('Error submitting reservation. Please try again.');
        } finally {
            // Re-enable submit button
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    });

    // Load available time slots when date changes
    const dateInput = reservationForm.querySelector('input[name="date"]');
    const timeInput = reservationForm.querySelector('input[name="time"]');
    
    if (dateInput && timeInput) {
        dateInput.addEventListener('change', async (e) => {
            const selectedDate = e.target.value;
            if (!selectedDate) return;

            try {
                const response = await fetch(`${API_BASE}/reservations.php?available_slots=1&date=${selectedDate}`);
                const availableSlots = await response.json();
                
                // For now, just log available slots - we can enhance this later
                console.log('Available time slots for', selectedDate, ':', availableSlots);
            } catch (error) {
                console.error('Error loading available slots:', error);
            }
        });
    }
}

// Order Page Functionality
function loadOrderPage() {
    if (window.location.pathname.includes('order.html')) {
        displayOrderItems();
        calculateOrderTotals();
    }
}

// Display cart items on order page
function displayOrderItems() {
    const orderContainer = document.getElementById('order-items-container');
    const emptyMessage = document.getElementById('empty-cart-message');
    const summaryContainer = document.getElementById('order-summary-container');
    
    if (!orderContainer) return;
    
    if (cart.length === 0) {
        emptyMessage.style.display = 'block';
        summaryContainer.style.display = 'none';
        orderContainer.innerHTML = '';
        return;
    }
    
    emptyMessage.style.display = 'none';
    summaryContainer.style.display = 'block';
    
    orderContainer.innerHTML = cart.map(item => `
        <div class="order-item">
            <img src="images/popular/3.png" alt="${item.name}">
            <div class="order-item-details">
                <div class="order-item-name">${item.name}</div>
                <div class="order-item-price">Rs. ${item.price} each</div>
                <div class="order-item-quantity">Quantity: ${item.quantity}</div>
            </div>
            <div class="order-item-controls">
                <div class="quantity-controls-order">
                    <button class="quantity-btn-order" onclick="updateOrderQuantity(${item.id}, ${item.quantity - 1})">-</button>
                    <span class="quantity-display">${item.quantity}</span>
                    <button class="quantity-btn-order" onclick="updateOrderQuantity(${item.id}, ${item.quantity + 1})">+</button>
                </div>
                <button class="remove-order-item" onclick="removeFromOrderPage(${item.id})">Remove</button>
                <div class="order-item-total">Rs. ${(item.price * item.quantity).toFixed(2)}</div>
            </div>
        </div>
    `).join('');
}

// Update quantity from order page
function updateOrderQuantity(itemId, newQuantity) {
    if (newQuantity <= 0) {
        removeFromOrderPage(itemId);
        return;
    }
    
    const item = cart.find(item => item.id === itemId);
    if (item) {
        item.quantity = newQuantity;
        saveCartToStorage();
        displayOrderItems();
        calculateOrderTotals();
        updateCartUI();
    }
}

// Remove item from order page
function removeFromOrderPage(itemId) {
    cart = cart.filter(item => item.id !== itemId);
    saveCartToStorage();
    displayOrderItems();
    calculateOrderTotals();
    updateCartUI();
    showNotification('Item removed from cart');
}

// Calculate order totals
function calculateOrderTotals() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    const orderType = document.getElementById('order-type')?.value;
    const deliveryFee = orderType === 'delivery' ? 50 : 0; // Rs. 50 delivery fee
    const taxRate = 0.05; // 5% tax
    const taxAmount = subtotal * taxRate;
    const finalTotal = subtotal + deliveryFee + taxAmount;
    
    // Update display elements
    const elements = {
        'total-items': totalItems,
        'subtotal': subtotal.toFixed(2),
        'delivery-fee': deliveryFee.toFixed(2),
        'tax-amount': taxAmount.toFixed(2),
        'final-total': finalTotal.toFixed(2)
    };
    
    Object.entries(elements).forEach(([id, value]) => {
        const element = document.getElementById(id);
        if (element) element.textContent = value;
    });
}

// Toggle delivery address field
function toggleDeliveryAddress() {
    const orderType = document.getElementById('order-type').value;
    const deliveryGroup = document.getElementById('delivery-address-group');
    const deliveryAddress = document.getElementById('delivery-address');
    
    if (orderType === 'delivery') {
        deliveryGroup.style.display = 'block';
        deliveryAddress.required = true;
    } else {
        deliveryGroup.style.display = 'none';
        deliveryAddress.required = false;
    }
    
    calculateOrderTotals(); // Recalculate totals when order type changes
}

// Clear cart with confirmation
function clearCartConfirm() {
    if (confirm('Are you sure you want to clear your entire cart? This action cannot be undone.')) {
        clearCart();
        displayOrderItems();
        calculateOrderTotals();
    }
}

// Enhanced checkout function for order page
async function placeOrder() {
    if (cart.length === 0) {
        showNotification('Your cart is empty!');
        return;
    }
    
    const form = document.getElementById('customer-form');
    const formData = new FormData(form);
    
    // Validate required fields
    const requiredFields = ['name', 'email', 'phone', 'order_type'];
    for (let field of requiredFields) {
        if (!formData.get(field)) {
            showNotification(`Please fill in ${field.replace('_', ' ')}`);
            return;
        }
    }
    
    // Validate delivery address if delivery is selected
    if (formData.get('order_type') === 'delivery' && !formData.get('delivery_address')) {
        showNotification('Please provide delivery address');
        return;
    }
    
    // Prepare order data
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const deliveryFee = formData.get('order_type') === 'delivery' ? 50 : 0;
    const taxAmount = subtotal * 0.05;
    const finalTotal = subtotal + deliveryFee + taxAmount;
    
    const orderData = {
        customer_name: formData.get('name'),
        customer_email: formData.get('email'),
        customer_phone: formData.get('phone'),
        delivery_address: formData.get('delivery_address') || '',
        order_type: formData.get('order_type'),
        special_instructions: formData.get('special_instructions') || '',
        subtotal: subtotal,
        delivery_fee: deliveryFee,
        tax_amount: taxAmount,
        total_amount: finalTotal,
        items: cart.map(item => ({
            menu_item_id: item.id,
            item_name: item.name,
            quantity: item.quantity,
            price: item.price,
            total: item.price * item.quantity
        }))
    };
    
    // Disable place order button
    const placeOrderBtn = document.querySelector('.place-order-button');
    placeOrderBtn.disabled = true;
    placeOrderBtn.textContent = 'Placing Order...';
    
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
            // Order successful
            showNotification('Order placed successfully! You will receive a confirmation email shortly.');
            
            // Clear cart and redirect
            clearCart();
            
            // Show order confirmation
            showOrderConfirmation(result.order_id, finalTotal);
            
        } else {
            throw new Error(result.error || 'Failed to place order');
        }
    } catch (error) {
        console.error('Order error:', error);
        showNotification('Error placing order. Please try again.');
    } finally {
        // Re-enable button
        placeOrderBtn.disabled = false;
        placeOrderBtn.textContent = 'Place Your Order';
    }
}

// Show order confirmation
function showOrderConfirmation(orderId, total) {
    const confirmationHTML = `
        <div class="order-confirmation">
            <h3>🎉 Order Confirmed!</h3>
            <p><strong>Order ID:</strong> #${orderId}</p>
            <p><strong>Total Amount:</strong> Rs. ${total.toFixed(2)}</p>
            <p>Thank you for your order! We'll prepare it with care.</p>
            <div class="confirmation-actions">
                <button onclick="window.location.href='menu.html'" class="continue-shopping-btn">Continue Shopping</button>
                <button onclick="window.location.href='index.html'" class="go-home-btn">Go to Home</button>
            </div>
        </div>
    `;
    
    const orderSection = document.querySelector('.order-review-section');
    orderSection.innerHTML = confirmationHTML;
}

// Initialize order page when DOM loads
document.addEventListener("DOMContentLoaded", function() {
    // ...existing code...
    
    // Load order page if we're on it
    loadOrderPage();
    
    // Listen for order type changes
    const orderTypeSelect = document.getElementById('order-type');
    if (orderTypeSelect) {
        orderTypeSelect.addEventListener('change', calculateOrderTotals);
    }
});

// Menu page specific functionality
async function loadFullMenu() {
    const loadingMessage = document.getElementById('loading-message');
    const menuSections = document.getElementById('menu-sections');
    
    if (!menuSections) return;
    
    try {
        // Show loading message
        if (loadingMessage) {
            loadingMessage.style.display = 'block';
        }
        
        const response = await fetch(`${API_BASE}/menu.php`);
        const menuItems = await response.json();
        
        // Group items by category
        const categories = {};
        menuItems.forEach(item => {
            const category = item.category || 'Other';
            if (!categories[category]) {
                categories[category] = [];
            }
            categories[category].push(item);
        });
        
        // Hide loading message
        if (loadingMessage) {
            loadingMessage.style.display = 'none';
        }
        
        // Create sections for each category
        menuSections.innerHTML = Object.entries(categories).map(([category, items]) => `
            <section class="food-category" id="${category.toLowerCase().replace(/\s+/g, '-')}">
                <h2>${category.toUpperCase()}</h2>
                <div class="food-cards">
                    ${items.map(item => `
                        <div class="food-card">
                            <img src="${item.image_url || 'images/popular/3.png'}" alt="${item.name}">
                            <div class="food-card-content">
                                <h3>${item.name}</h3>
                                <p>${item.description || ''}</p>
                                <p class="food-price">Rs. ${item.price}</p>
                                <div class="food-card-actions">
                                    <div class="quantity-selector">
                                        <label>Qty:</label>
                                        <input type="number" class="quantity-input" min="1" max="10" value="1" id="qty-${item.id}">
                                    </div>
                                    <button onclick="addToCartWithQuantity(${item.id}, '${item.name}', ${item.price})" class="add-to-cart-btn">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </section>
        `).join('');
        
    } catch (error) {
        console.error('Error loading menu:', error);
        if (loadingMessage) {
            loadingMessage.innerHTML = '<h3>Error loading menu. Please try again later.</h3>';
        }
    }
}

// Make carousel functions globally accessible
window.previousReviews = previousReviews;
window.nextReviews = nextReviews;
window.goToReviewSlide = goToReviewSlide;

// Export functions for global access
window.updateOrderQuantity = updateOrderQuantity;
window.removeFromOrderPage = removeFromOrderPage;
window.toggleDeliveryAddress = toggleDeliveryAddress;
window.clearCartConfirm = clearCartConfirm;
window.placeOrder = placeOrder;
window.addToCartWithQuantity = addToCartWithQuantity;