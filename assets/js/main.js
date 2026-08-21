// User Authentication
function logout() {
    fetch('api/logout.php', { method: 'POST' })
        .then(() => {
            showToast('Logged out successfully', 'success');
            setTimeout(() => window.location.href = 'index.html', 1000);
        })
        .catch(err => showToast('Logout failed', 'error'));
}

// Toast Notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Update Navbar Icons
function updateNavbar() {
    // Update cart count
    fetch('api/cart.php', { method: 'GET' })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('cartCount').textContent = data.data.length;
            }
        })
        .catch(err => console.log(err));

    // Update wishlist count
    fetch('api/wishlist.php', { method: 'GET' })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('wishlistCount').textContent = data.data.length;
            }
        })
        .catch(err => console.log(err));
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', updateNavbar);

// Add to Cart
function addToCart(productId, quantity = 1) {
    fetch('api/cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            action: 'add',
            productId: productId,
            quantity: quantity,
            variantId: 0
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('Added to cart!', 'success');
            updateNavbar();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(err => showToast('Error adding to cart', 'error'));
}

// Add to Wishlist
function addToWishlist(productId) {
    fetch('api/wishlist.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            action: 'add',
            productId: productId
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('Added to wishlist!', 'success');
            updateNavbar();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(err => showToast('Error adding to wishlist', 'error'));
}

// Subscribe to Newsletter
function subscribeNewsletter() {
    const email = document.getElementById('newsletterEmail').value;
    if (email) {
        showToast('Thank you for subscribing!', 'success');
        document.getElementById('newsletterEmail').value = '';
    }
}

// Format Price
function formatPrice(price) {
    return '₹' + parseFloat(price).toLocaleString('en-IN', { minimumFractionDigits: 0 });
}

// Get Auth Status
let isUserLoggedIn = false;
document.addEventListener('DOMContentLoaded', () => {
    // Check if user is logged in via session
    const userInfo = document.querySelector('[data-user-info]');
    if (userInfo) {
        isUserLoggedIn = true;
    }
});

// Redirect to login if required
function requireLogin() {
    if (!isUserLoggedIn) {
        window.location.href = 'login.html';
        return false;
    }
    return true;
}