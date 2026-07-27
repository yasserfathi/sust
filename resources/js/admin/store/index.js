import { defineStore } from 'pinia';
import axios from 'axios';
import { route } from 'ziggy-js';


export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    authToken: localStorage.getItem('authToken'),
    error: null,
    loading: false,
    initialized: false,
    _initPromise: null
  }),

  getters: {
    currentUser: (state) => state.user,
    isAuthenticated: (state) => !!state.authToken || !!state.user,
    authHeader: (state) => state.authToken ? `Bearer ${state.authToken}` : null
  },

  actions: {
    async initialize() {
      if (this.initialized) return;
      if (this._initPromise) return this._initPromise;

      this._initPromise = (async () => {
        if (this.authToken) {
          try {
            await axios.get(route('sanctum.csrf-cookie'));
          } catch (e) {
            // Ignore CSRF fetch errors on boot
          }
          await this.checkAuthStatus();
        } else {
          this.clearAuth();
        }
        this.initialized = true;
      })();

      return this._initPromise;
    },

    async checkAuthStatus() {
      try {
        this.loading = true;
        const response = await axios.get(route('user'), {
          headers: { Authorization: this.authHeader }
        });
        this.user = response.data.user || response.data;
      } catch (error) {
        if (error.response && (error.response.status === 401 || error.response.status === 419)) {
          this.clearAuth();
        } else {
          console.error('Authentication check failed:', error);
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

        if (response.data && response.data.access_token) {
          this.authToken = response.data.access_token;
          localStorage.setItem('authToken', this.authToken);
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.authToken}`;
        }

        if (response.data && response.data.user) {
          this.user = response.data.user;
        } else {
          await this.checkAuthStatus();
        }

        if (!this.user) {
          throw new Error('فشل جلب بيانات المستخدم بعد تسجيل الدخول');
        }

        this.initialized = true;

        const redirectUrl = localStorage.getItem('redirectUrl');
        localStorage.removeItem('redirectUrl');

        const { default: router } = await import('../router');
        if (redirectUrl && redirectUrl !== '/' && redirectUrl.startsWith('/')) {
          router.push(redirectUrl).catch(() => router.push('/dashboard').catch(() => { }));
        } else {
          router.push('/dashboard').catch(() => { });
        }
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
        const { default: router } = await import('../router');
        await router.push({ name: 'Login' });
      }
    },

    clearAuth() {
      this.user = null;
      this.authToken = null;
      localStorage.removeItem('authToken');
      delete axios.defaults.headers.common['Authorization'];
    }
  }
});