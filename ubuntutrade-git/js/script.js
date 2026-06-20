// UbuntuTrade - basic JavaScript
// Amateur level: just confirm dialogs and a utility function

document.addEventListener('DOMContentLoaded', function() {
    console.log("UbuntuTrade frontend ready");

    // Find all buttons/links that have class 'confirm-delete' or use data-confirm
    const deleteButtons = document.querySelectorAll('[data-confirm]');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm(this.getAttribute('data-confirm') || 'Are you sure?')) {
                e.preventDefault();
            }
        });
    });

    // optional: add a simple cart counter update (if needed)
    function updateCartCount() {
        fetch('php/get_cart.php')
            .then(res => res.json())
            .then(cart => {
                const cartLink = document.querySelector('a[href="cart.html"]');
                if (cartLink && cart.length) {
                    cartLink.innerHTML = `🛒 Cart (${cart.length})`;
                } else if (cartLink) {
                    cartLink.innerHTML = '🛒 Cart';
                }
            })
            .catch(err => console.log("Cart count error", err));
    }

    // if we are on any page except cart/checkout, show cart badge
    if (!window.location.pathname.includes('cart.html') && 
        !window.location.pathname.includes('checkout.html')) {
        updateCartCount();
    }
});

// helper to escape HTML (used in many inline scripts)
function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}