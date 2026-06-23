import OrderRepository from '../repositories/OrderRepository';

export default {
  async placeOrder(orderData) {
    try {
      const response = await OrderRepository.checkout(orderData);
      return response.data || null;
    } catch (error) {
      console.error('Error placing order:', error);
      throw error;
    }
  },
  async trackOrders(email) {
    try {
      const response = await OrderRepository.track(email);
      return response.data || [];
    } catch (error) {
      console.error('Error tracking orders:', error);
      throw error;
    }
  },
  async getMyOrders() {
    try {
      const response = await OrderRepository.getMyOrders();
      return response.data || [];
    } catch (error) {
      console.error('Error fetching my orders:', error);
      throw error;
    }
  }
};
