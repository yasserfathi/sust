import axios from 'axios';

var token = localStorage.getItem('token');
axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
