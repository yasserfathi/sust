import { defineStore } from 'pinia';
import axios from 'axios';
import router from '../router';
import { route } from 'ziggy-js';


export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    authToken: localStorage.getItem('authToken'),
    error: null,
    loading: false,
    initialized: false
  }),

  getters: {
    currentUser: (state) => state.user,
    isAuthenticated: (state) => !!state.authToken,
    authHeader: (state) => state.authToken ? `Bearer ${state.authToken}` : null
  },

  actions: {
    async initialize() {
      if (this.authToken && !this.initialized) {
        try {
          await axios.get(route('sanctum.csrf-cookie'));
        } catch (e) {
          // Ignore CSRF fetch errors on boot
        }
        await this.checkAuthStatus();
      }
      this.initialized = true;
    },

    async checkAuthStatus() {
      try {
        this.loading = true;
        const response = await axios.get(route('user'), {
          headers: { Authorization: this.authHeader }
        });
        this.user = response.data.user || response.data;
      } catch (error) {
        console.error('Authentication check failed:', error);
        if (error.response && (error.response.status === 401 || error.response.status === 419)) {
          this.clearAuth();
        }
      } finally {
        this.loading = false;
      }
    },

    async login(email, password) {
      this.loading = true;
      this.error = null;

      try {
        await axios.get(route('sanctum.csrf-cookie'));

        const response = await axios.post(route('login'), { email, password, remember: true });

        this.authToken = response.data.access_token;
        localStorage.setItem('authToken', this.authToken);
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.authToken}`;
        this.user = response.data.user;

        if (!this.user) {
          throw new Error('فشل جلب بيانات المستخدم بعد تسجيل الدخول');
        }
        this.initialized = true;

        const redirectUrl = localStorage.getItem('redirectUrl') || '/dashboard';
        localStorage.removeItem('redirectUrl');
        router.replace(redirectUrl).catch(() => router.replace('/dashboard').catch(() => { }));
      } catch (error) {
        this.clearAuth();
        this.error = error.response?.data?.message || error.message;
      } finally {
        this.loading = false;
      }
    },

    async logout() {
      try {
        await axios.post(route('logout'), {}, {
          headers: { Authorization: this.authHeader }
        });
      } catch (error) {
        console.error('Logout error:', error);
      } finally {
        this.clearAuth();
        await router.push({ name: 'Login' });
      }
    },

    clearAuth() {
      this.user = null;
      this.authToken = null;
      this.initialized = false;
      localStorage.removeItem('authToken');
      delete axios.defaults.headers.common['Authorization'];
    }
  }
});