import { defineStore } from 'pinia';
import axios from 'axios';

export const useStudentAuthStore = defineStore('studentAuth', {
  state: () => ({
    user: null,
    authToken: localStorage.getItem('studentAuthToken') || null,
    isAuthenticated: !!localStorage.getItem('studentAuthToken'),
    error: null,
  }),
  actions: {
    async login(username, password) {
      this.error = null;
      try {
        const response = await axios.post('/api/student/login', { username, password });

        if (response.data && response.data.access_token) {
          this.authToken = response.data.access_token;
          localStorage.setItem('studentAuthToken', this.authToken);
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.authToken}`;
        }

        if (response.data && response.data.student) {
          this.user = response.data.student;
          this.isAuthenticated = true;
        }
      } catch (error) {
        this.error = error.response?.data?.message || 'فشل الاتصال بالخادم';
        throw error;
      }
    },
    async confirmEmail(email) {
      try {
        const response = await axios.post('/api/student/confirm-email', { email }, {
          headers: {
            Authorization: `Bearer ${this.authToken}`,
            Accept: 'application/json'
          }
        });
        if (response.data && response.data.student) {
          this.user = response.data.student;
        }
        return response.data;
      } catch (error) {
        throw error;
      }
    },
    logout() {
      this.user = null;
      this.authToken = null;
      this.isAuthenticated = false;
      localStorage.removeItem('studentAuthToken');
      delete axios.defaults.headers.common['Authorization'];
    }
  },
});