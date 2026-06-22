import { computed, ref } from 'vue';
import { useProductStore } from '../stores/productStore';

export function useProducts() {
  const store = useProductStore();
  const selectedRoast = ref('');
  const selectedOrigin = ref('');

  const filteredProducts = computed(() => {
    return store.products.filter(product => {
      const matchRoast = !selectedRoast.value || product.roast_level === selectedRoast.value;
      const matchOrigin = !selectedOrigin.value || product.origin === selectedOrigin.value;
      return matchRoast && matchOrigin;
    });
  });

  const origins = computed(() => {
    return [...new Set(store.products.map(p => p.origin))];
  });

  const roastLevels = computed(() => {
    return [...new Set(store.products.map(p => p.roast_level))];
  });

  return {
    products: computed(() => store.products),
    loading: computed(() => store.loading),
    error: computed(() => store.error),
    currentProduct: computed(() => store.currentProduct),
    selectedRoast,
    selectedOrigin,
    filteredProducts,
    origins,
    roastLevels,
    loadProducts: store.loadProducts,
    loadProductById: store.loadProductById,
  };
}
