import ProductRepository from '../repositories/ProductRepository';

export default {
  async fetchAllProducts() {
    try {
      const response = await ProductRepository.getAll();
      return response.data || [];
    } catch (error) {
      console.error('Error fetching products:', error);
      throw error;
    }
  },
  async fetchProductById(id) {
    try {
      const response = await ProductRepository.getById(id);
      return response.data || null;
    } catch (error) {
      console.error(`Error fetching product ${id}:`, error);
      throw error;
    }
  }
};
