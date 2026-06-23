import apiClient from '../apiClient';

export default {
  async checkout(orderData) {
    const response = await apiClient.post('/checkout', orderData);
    return response.data; // Retorna { code: 201, data: {...} }
  },
  async track(email) {
    const response = await apiClient.get(`/orders/track?email=${encodeURIComponent(email)}`);
    return response.data; // Retorna { code: 200, data: [...] }
  },
  async getMyOrders() {
    const response = await apiClient.get('/orders');
    return response.data; // Retorna { code: 200, data: [...] }
  }
};
