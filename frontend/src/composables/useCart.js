import { computed } from 'vue';
import { useCartStore } from '../stores/cartStore';

export function useCart() {
  const store = useCartStore();

  return {
    items: computed(() => store.items),
    totalCount: computed(() => store.totalCount),
    totalPrice: computed(() => store.totalPrice),
    addItem: store.addItem,
    removeItem: store.removeItem,
    updateQuantity: store.updateQuantity,
    clearCart: store.clearCart,
  };
}
