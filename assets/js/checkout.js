// Load Checkout Data
async function loadCheckout() {
    try {
        const response = await fetch('api/cart.php');
        const data = await response.json();
        
        if (!data.success || data.data.length === 0) {
            window.location.href = 'cart.html';
            return;
        }
        
        displayOrderItems(data.data);
        calculateOrderTotal();
    } catch (error) {
        console.error('Error loading checkout:', error);
        window.location.href = 'cart.html';
    }
}

// Display Order Items
function displayOrderItems(items) {
    const orderItems = document.getElementById('orderItems');
    if (!orderItems) return;
    
    orderItems.innerHTML = items.map(item => `
        <div class="order-item">
            <div>${item.name} x${item.quantity}</div>
            <div>${formatPrice(item.price * item.quantity)}</div>
        </div>
    `).join('');
}

// Calculate Order Total
function calculateOrderTotal() {
    const rows = document.querySelectorAll('#cartItems tr') || [];
    let subtotal = 0;
    
    if (rows.length === 0) {
        // Fallback calculation
        fetch('api/cart.php')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    let total = 0;
                    data.data.forEach(item => {
                        total += item.price * item.quantity;
                    });
                    updateSummary(total);
                }
            });
    } else {
        rows.forEach(row => {
            const cells = row.cells;
            const total = parseFloat(cells[3].textContent.replace('₹', '').replace(',', ''));
            subtotal += total;
        });
        updateSummary(subtotal);
    }
}

function updateSummary(subtotal) {
    const tax = subtotal * 0.18;
    const delivery = 100;
    const total = subtotal + tax + delivery;
    
    if (document.getElementById('summarySubtotal')) {
        document.getElementById('summarySubtotal').textContent = formatPrice(subtotal);
        document.getElementById('summaryTax').textContent = formatPrice(tax);
        document.getElementById('summaryTotal').textContent = formatPrice(total);
    }
}

// Toggle Shipping Address
document.addEventListener('DOMContentLoaded', () => {
    const sameAsShipping = document.getElementById('sameAsShipping');
    if (sameAsShipping) {
        sameAsShipping.addEventListener('change', () => {
            const shippingSection = document.getElementById('shippingSection');
            shippingSection.style.display = sameAsShipping.checked ? 'none' : 'block';
        });
    }
    
    loadCheckout();
});

// Apply Coupon
function applyCoupon() {
    const code = document.getElementById('couponCode').value;
    const subtotal = parseFloat(document.getElementById('summarySubtotal').textContent.replace('₹', '').replace(',', '')) || 0;
    
    fetch('api/coupon.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            code: code,
            amount: subtotal
        })
    })
    .then(res => res.json())
    .then(data => {
        const message = document.getElementById('couponMessage');
        if (data.valid) {
            message.innerHTML = `<div style="color: green;">Coupon applied! Discount: ${formatPrice(data.discount)}</div>`;
            // Update total with discount
            const discount = data.discount;
            const tax = (subtotal - discount) * 0.18;
            const delivery = 100;
            const total = subtotal - discount + tax + delivery;
            
            document.getElementById('summaryDiscount').textContent = '-' + formatPrice(discount);
            document.getElementById('summaryTax').textContent = formatPrice(tax);
            document.getElementById('summaryTotal').textContent = formatPrice(total);
        } else {
            message.innerHTML = `<div style="color: red;">${data.message}</div>`;
        }
    })
    .catch(err => {
        document.getElementById('couponMessage').innerHTML = '<div style="color: red;">Error applying coupon</div>';
    });
}

// Place Order
function placeOrder(e) {
    e.preventDefault();
    
    if (!requireLogin()) return;
    
    const billingAddress = {
        fullName: document.getElementById('billingName').value,
        phone: document.getElementById('billingPhone').value,
        address: document.getElementById('billingAddress').value,
        city: document.getElementById('billingCity').value,
        state: document.getElementById('billingState').value,
        pincode: document.getElementById('billingPincode').value
    };
    
    const sameAsShipping = document.getElementById('sameAsShipping').checked;
    const shippingAddress = sameAsShipping ? {} : {
        fullName: document.getElementById('shippingName').value,
        phone: document.getElementById('shippingPhone').value,
        address: document.getElementById('shippingAddress').value,
        city: document.getElementById('shippingCity').value,
        state: document.getElementById('shippingState').value,
        pincode: document.getElementById('shippingPincode').value
    };
    
    const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
    const couponCode = document.getElementById('couponCode').value;
    
    fetch('api/checkout.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            billingAddress,
            shippingAddress,
            paymentMethod,
            couponCode
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('Order placed successfully!', 'success');
            setTimeout(() => {
                window.location.href = `orders.html?orderId=${data.orderId}`;
            }, 1500);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(err => {
        showToast('Error placing order', 'error');
    });
}