import { defineStore } from 'pinia';

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: JSON.parse(localStorage.getItem('coffee_cart') || '[]'),
  }),
  getters: {
    totalCount: (state) => state.items.reduce((sum, item) => sum + item.quantity, 0),
    totalPrice: (state) => state.items.reduce((sum, item) => sum + (item.price * item.quantity), 0),
  },
  actions: {
    addItem(product, quantity = 1, grindType = 'grano') {
      const existingIndex = this.items.findIndex(
        (item) => item.product_id === product.id && item.grind_type === grindType
      );
      
      if (existingIndex > -1) {
        // Asegurar que no exceda el stock disponible
        const newQty = this.items[existingIndex].quantity + quantity;
        this.items[existingIndex].quantity = Math.min(newQty, product.stock);
      } else {
        this.items.push({
          product_id: product.id,
          name: product.name,
          price: parseFloat(product.price),
          image_url: product.image_url,
          grind_type: grindType,
          quantity: Math.min(quantity, product.stock),
          origin: product.origin,
          maxStock: product.stock
        });
      }
      this.saveToStorage();
    },
    removeItem(productId, grindType) {
      this.items = this.items.filter(
        (item) => !(item.product_id === productId && item.grind_type === grindType)
      );
      this.saveToStorage();
    },
    updateQuantity(productId, grindType, newQty) {
      const item = this.items.find(
        (item) => item.product_id === productId && item.grind_type === grindType
      );
      if (item) {
        item.quantity = Math.max(1, Math.min(newQty, item.maxStock));
        this.saveToStorage();
      }
    },
    clearCart() {
      this.items = [];
      this.saveToStorage();
    },
    saveToStorage() {
      localStorage.setItem('coffee_cart', JSON.stringify(this.items));
    }
  }
});
