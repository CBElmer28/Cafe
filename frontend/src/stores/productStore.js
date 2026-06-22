import { defineStore } from 'pinia';
import ProductService from '../api/services/ProductService';

export const useProductStore = defineStore('products', {
  state: () => ({
    products: [],
    currentProduct: null,
    loading: false,
    error: null
  }),
  actions: {
    async loadProducts() {
      this.loading = true;
      this.error = null;
      try {
        this.products = await ProductService.fetchAllProducts();
      } catch (err) {
        this.error = 'No se pudo cargar el catálogo de café.';
      } finally {
        this.loading = false;
      }
    },
    async loadProductById(id) {
      this.loading = true;
      this.error = null;
      try {
        this.currentProduct = await ProductService.fetchProductById(id);
        return this.currentProduct;
      } catch (err) {
        this.error = 'No se pudo cargar el producto de café especificado.';
        return null;
      } finally {
        this.loading = false;
      }
    }
  }
});
