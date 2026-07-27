import { createRouter, createWebHistory } from 'vue-router';

const Dashboard = () => import('./pages/Dashboard.vue');
const Profile = () => import('./pages/Profile.vue');

const routes = [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard,
    },
    {
        path: '/profile',
        name: 'profile',
        component: Profile,
    },
];

const router = createRouter({
    history: createWebHistory('/students'), // Base path for the student panel
    routes,
});

export default router;