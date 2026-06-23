import apiClient from '../apiClient';

export default {
  async register(data) {
    const response = await apiClient.post('/auth/register', data);
    return response.data;
  },
  async login(credentials) {
    const response = await apiClient.post('/auth/login', credentials);
    return response.data;
  },
  async logout() {
    const response = await apiClient.post('/auth/logout');
    return response.data;
  },
  async getMe() {
    const response = await apiClient.get('/auth/me');
    return response.data;
  },
  async refresh() {
    const response = await apiClient.post('/auth/refresh');
    return response.data;
  }
};
