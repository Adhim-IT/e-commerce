class ShoppingCart {
    constructor() {
        this.items = this.getCartFromStorage();
        this.SHIPPING_COST = 20000;
        this.init();
    }
    init(){
        if(window.location.pathname.includes('cart')) {
            this.updateCartUI();
        }
        this.updateCartCount();
        this.attachEventListeners();
    }
    getCartFromStorage(){
        try{
            return JSON.parse(localStorage.getItem('shopping_cart')) || [];
        }catch(error){
            console.error('Error getting cart from local storage', error);
            return[];
        }
    }
    saveCartToStoreStorage(){
        try{
            localStorage.setItem('shopping_cart', JSON.stringify(this.items));
            this.updateCartCount();
        }catch(error){
            console.error('Error saving cart to local storage   ',error);
            this.showNotification('Error saving cart to local storage');
        }
    }

    updateCartCount(){
        const cartCount = document.getElementById('cart-count');
        if(!cartCount) return;

        const totalItems = this.items.reduce((sum, item) => sum + item.quantity, 0);
        cartCount.textContent = totalItems;
        cartCount.style.display = totalItems > 0 ? 'flex' : 'none';
    }
    addItem(product){
        if(!product?.id) return;


        try{
            const existingItem = this.items.find(item => item.id === parseInt(product.id));
            if(existingItem){
                existingItem.quantity += 1;
            }else{
                this.items.push({
                    id: parseInt(product.id),
                    name: product.name,
                    price: parseFloat(product.price),
                    image: product.image,
                    category: product.category_name,
                    quantity: 1
                });
            }
            this.saveCartToStoreStorage();
            this.updateCartCount();
            this.showNotification(`1 ${product.name} added to cart`);
        } catch(error){
            console.error(error);
            this.showNotification('Error adding item to cart');
        }
    }

    removeItem(productId){
        try{
            this.items = this.items.filter(item => item.id !== parseInt(productId));
            this.saveCartToStoreStorage();
            this.updateCartCount();
            this.showNotification('Item removed from cart');
        }catch(error){
            console.error(error);
            this.showNotification('Error removing item from cart');
        }
    }
    updateQuantity(productId, changeAmount){
        try{
            const item = this.items.find(item => item.id === parseInt(productId));
            if(!item) return;

            const newQuantity = item.quantity + changeAmount;
            if(newQuantity < 1){
                this.removeItem(productId);
                return;
            }
            item.quantity = newQuantity;
            this.saveCartToStoreStorage();
            this.updateCartCount();
        }catch(error){
            console.error(error);
            this.showNotification('Error updating cart count');
        }
    }

    formatPrice(price){
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(price);
    }
    calculateSubTotal(){
        return this.items.reduce((total, item) => total + (parseFloat(item.price) * item.quantity), 0);
    }

    showNotification(message, type = 'success'){
        const notification = document.createElement('div');
        notification.classList.add(
            'fixed',
            'bottom-4',
            'right-4',
            'px-6',
            'py-3',
            'rounded-lg',
            'shadow-lg',
            'transform',
            'transition-transform',
            'duration-300',
            'translate-y-0',
            'z-50',
            type === 'success' ? 'bg-emerald-500' : 'bg-red-500',
            'text-white'
        );
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.add('translate-y-full');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    attachEventListeners() {
        const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
        addToCartButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const productCard = e.target.closest('.product-card');
                if (productCard) {
                    const product = {
                        id: productCard.dataset.id,
                        name: productCard.dataset.name,
                        price: productCard.dataset.price,
                        image: productCard.dataset.image,
                        category_name: productCard.dataset.category
                    };
                    this.addItem(product);
                }
            });
        });
    }


}

export default ShoppingCart;
