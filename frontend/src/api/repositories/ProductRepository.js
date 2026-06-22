import apiClient from '../apiClient';

export default {
  async getAll() {
    const response = await apiClient.get('/products');
    return response.data; // Retorna { code: 200, data: [...] }
  },
  async getById(id) {
    const response = await apiClient.get(`/products/${id}`);
    return response.data; // Retorna { code: 200, data: {...} }
  }
};
