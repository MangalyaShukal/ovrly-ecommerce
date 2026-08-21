// Load Cart
async function loadCart() {
    try {
        const response = await fetch('api/cart.php');
        const data = await response.json();
        
        if (!data.success) {
            showCart(false);
            return;
        }
        
        if (data.data.length === 0) {
            showCart(false);
        } else {
            displayCartItems(data.data);
            showCart(true);
            calculateTotal();
        }
    } catch (error) {
        console.error('Error loading cart:', error);
        showCart(false);
    }
}

// Display Cart Items
function displayCartItems(items) {
    const tbody = document.getElementById('cartItems');
    if (!tbody) return;
    
    tbody.innerHTML = items.map(item => `
        <tr>
            <td>${item.name}</td>
            <td>${formatPrice(item.price)}</td>
            <td>
                <input type="number" value="${item.quantity}" min="1" max="${item.stock}" 
                    onchange="updateCartItem(${item.id}, this.value)">
            </td>
            <td>${formatPrice(item.price * item.quantity)}</td>
            <td>
                <button class="remove-btn" onclick="removeCartItem(${item.id})">Remove</button>
            </td>
        </tr>
    `).join('');
}

// Update Cart Item
function updateCartItem(itemId, quantity) {
    fetch('api/cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            action: 'update',
            itemId: itemId,
            quantity: quantity
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            loadCart();
        }
    })
    .catch(err => console.error(err));
}

// Remove Cart Item
function removeCartItem(itemId) {
    fetch('api/cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            action: 'remove',
            itemId: itemId
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('Item removed', 'success');
            loadCart();
        }
    })
    .catch(err => console.error(err));
}

// Calculate Total
function calculateTotal() {
    const rows = document.querySelectorAll('#cartItems tr');
    let subtotal = 0;
    
    rows.forEach(row => {
        const cells = row.cells;
        const price = parseFloat(cells[1].textContent.replace('₹', '').replace(',', ''));
        const quantity = parseInt(cells[2].querySelector('input').value);
        subtotal += price * quantity;
    });
    
    const tax = subtotal * 0.18;
    const delivery = 100;
    const total = subtotal + tax + delivery;
    
    if (document.getElementById('subtotal')) {
        document.getElementById('subtotal').textContent = formatPrice(subtotal);
        document.getElementById('tax').textContent = formatPrice(tax);
        document.getElementById('total').textContent = formatPrice(total);
    }
}

// Show/Hide Cart
function showCart(show) {
    const emptyCart = document.getElementById('emptyCart');
    const cartContent = document.getElementById('cartContent');
    
    if (emptyCart) emptyCart.style.display = show ? 'none' : 'block';
    if (cartContent) cartContent.style.display = show ? 'grid' : 'none';
}

// Proceed to Checkout
function proceedToCheckout() {
    window.location.href = 'checkout.html';
}

// Initialize
document.addEventListener('DOMContentLoaded', loadCart);