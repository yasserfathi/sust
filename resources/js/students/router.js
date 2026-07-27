import { createRouter, createWebHistory } from 'vue-router';
import { useStudentAuthStore } from './store/index';

const routes = [
  {
    path: '/student/login',
    name: 'StudentLogin',
    component: () => import('./pages/Login.vue'),
    meta: { title: 'تسجيل الدخول', isPublic: true }
  },
  {
    path: '/student/check',
    name: 'StudentCheck',
    component: () => import('./pages/Check.vue'),
    meta: { title: 'التحقق من الرقم الجامعي', isPublic: true }
  },
  {
    path: '/student/register',
    name: 'StudentRegister',
    component: () => import('./pages/Register.vue'),
    meta: { title: 'إنشاء حساب جديد', isPublic: true }
  },
  {
    path: '/student/forgot-password',
    name: 'StudentForgotPassword',
    component: () => import('./pages/ForgotPassword.vue'),
    meta: { title: 'استعادة كلمة المرور', isPublic: true }
  },
  {
    path: '/student/reset-password',
    name: 'StudentResetPassword',
    component: () => import('./pages/ResetPassword.vue'),
    meta: { title: 'إعادة تعيين كلمة المرور', isPublic: true }
  },
  {
    path: '/student/setup-email',
    name: 'StudentSetupEmail',
    component: () => import('./pages/StudentSetupEmail.vue'),
    meta: { title: 'تحديث البريد الإلكتروني', requiresAuth: true }
  },
  {
    path: '/student/confirmEmail',
    name: 'StudentConfirmEmail',
    component: () => import('./pages/ConfirmEmail.vue'),
    meta: { title: 'تأكيد الحساب وإعداد كلمة المرور', isPublic: true }
  },
  {
    path: '/student',
    component: () => import('./pages/Default.vue'),
    children: [
      {
        path: '',
        name: 'StudentDashboard',
        component: () => import('./pages/Dashboard.vue'),
        meta: { title: 'لوحة التحكم', requiresAuth: true }
      },
      {
        path: 'results',
        name: 'StudentResults',
        component: () => import('./pages/Results.vue'),
        meta: { title: 'النتائج الأكاديمية', requiresAuth: true }
      },
      {
        path: 'payments',
        name: 'StudentPayments',
        component: () => import('./pages/Payments.vue'),
        meta: { title: 'الرسوم والدفع', requiresAuth: true }
      }
    ]
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/student'
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const authStore = useStudentAuthStore();
  document.title = to.meta.title ? `${to.meta.title} | بوابة الطالب` : 'بوابة الطالب';

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'StudentLogin' });
  } else if (to.meta.isPublic && authStore.isAuthenticated) {
    next({ name: 'StudentDashboard' });
  } else {
    // إجبار الطالب على إعداد الإيميل إذا كان مفقوداً ولم يكن متجهاً لصفحة الإعداد أصلاً
    if (to.meta.requiresAuth && authStore.isAuthenticated && authStore.user && !authStore.user.email && to.name !== 'StudentSetupEmail') {
      return next({ name: 'StudentSetupEmail' });
    }
    // منعه من الدخول لصفحة إعداد الإيميل إذا كان قد قام بإدخاله مسبقاً
    if (to.name === 'StudentSetupEmail' && authStore.user && authStore.user.email) {
      return next({ name: 'StudentDashboard' });
    }

    next();
  }
});

export default router;