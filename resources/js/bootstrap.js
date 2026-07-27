import axios from 'axios';

var token = localStorage.getItem('authToken');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}
// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
