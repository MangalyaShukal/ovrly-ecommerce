// Load Products
async function loadProducts() {
    try {
        const response = await fetch('api/products.php?action=list');
        const data = await response.json();
        
        if (data.success) {
            displayProducts(data.data);
        }
    } catch (error) {
        console.error('Error loading products:', error);
    }
}

// Display Products
function displayProducts(products) {
    const grid = document.getElementById('productsGrid') || document.getElementById('featuredProducts');
    if (!grid) return;
    
    grid.innerHTML = products.map(product => `
        <div class="product-card">
            <div class="product-image">
                <img src="assets/images/products/product-${product.id}.jpg" alt="${product.name}" onerror="this.src='assets/images/placeholder.jpg'">
                ${product.discount_price ? '<span class="product-badge">Sale</span>' : ''}
            </div>
            <div class="product-info">
                <div class="product-name">${product.name}</div>
                <div class="product-price">
                    ${formatPrice(product.discount_price || product.price)}
                    ${product.discount_price ? `<span class="product-original">${formatPrice(product.price)}</span>` : ''}
                </div>
                <div class="product-rating">
                    ${'★'.repeat(Math.round(product.rating || 0))}${'☆'.repeat(5 - Math.round(product.rating || 0))}
                </div>
                <div class="product-actions">
                    <button onclick="addToCart(${product.id})">Add to Cart</button>
                    <button onclick="addToWishlist(${product.id})"><i class="fas fa-heart"></i></button>
                    <a href="product-details.html?id=${product.id}">View</a>
                </div>
            </div>
        </div>
    `).join('');
}

// Search Products
function searchProducts() {
    const query = document.getElementById('searchInput').value;
    if (!query) {
        loadProducts();
        return;
    }
    
    fetch(`api/products.php?action=search&q=${query}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                displayProducts(data.data);
            }
        })
        .catch(err => console.error(err));
}

// Apply Filters
function applyFilters() {
    const priceMin = document.getElementById('priceMin').value;
    const priceMax = document.getElementById('priceMax').value;
    
    if (document.getElementById('priceDisplay')) {
        document.getElementById('priceDisplay').textContent = `₹${priceMin} - ₹${priceMax}`;
    }
    
    // Load and filter products
    loadProducts();
}

// Sort Products
function applySort() {
    const sortOption = document.getElementById('sortOption').value;
    loadProducts();
}

// Initialize
document.addEventListener('DOMContentLoaded', loadProducts);