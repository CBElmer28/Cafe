import { defineStore } from 'pinia';
import AuthRepository from '../api/repositories/AuthRepository';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
  }),
  getters: {
    isAuthenticated: (state) => !!state.token,
  },
  actions: {
    async login(credentials) {
      try {
        const response = await AuthRepository.login(credentials);
        if (response.code === 200 && response.data) {
          this.token = response.data.access_token;
          this.user = response.data.user;
          localStorage.setItem('token', this.token);
          return response.data;
        }
        throw new Error(response.msg || 'Error en el inicio de sesión');
      } catch (error) {
        console.error('Error logging in:', error);
        throw error;
      }
    },
    async register(userData) {
      try {
        const response = await AuthRepository.register(userData);
        if (response.code === 201 && response.data) {
          this.token = response.data.access_token;
          this.user = response.data.user;
          localStorage.setItem('token', this.token);
          return response.data;
        }
        throw new Error(response.msg || 'Error en el registro');
      } catch (error) {
        console.error('Error registering:', error);
        throw error;
      }
    },
    async logout() {
      try {
        if (this.token) {
          await AuthRepository.logout();
        }
      } catch (error) {
        console.error('Error during logout request:', error);
      } finally {
        this.token = null;
        this.user = null;
        localStorage.removeItem('token');
      }
    },
    async fetchCurrentUser() {
      if (!this.token) return null;
      try {
        const response = await AuthRepository.getMe();
        if (response.code === 200 && response.data) {
          this.user = response.data;
          return this.user;
        }
        this.clearAuth();
        return null;
      } catch (error) {
        console.error('Error fetching current user:', error);
        this.clearAuth();
        return null;
      }
    },
    clearAuth() {
      this.token = null;
      this.user = null;
      localStorage.removeItem('token');
    }
  }
});
