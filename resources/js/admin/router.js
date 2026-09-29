import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from './store/index';

const DefaultLayout = () => import('./layouts/Default.vue');
const APP_NAME = 'لوحة تحكم موقع جامعة السودان للعلوم والتكنولوجيا';

const routes = [
  {
    name: 'Login',
    path: '/',
    component: () => import('./pages/index.vue'),
    meta: { isPublic: true, title: 'تسجيل الدخول' }
  },
  {
    path: '/album',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'album',
      component: () => import('./pages/album.vue'),
      meta: { requiresAuth: true, title: 'الألبوم' }
    }]
  },
  {
    path: '/college_album',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'college_album',
      component: () => import('./pages/college_album.vue'),
      meta: { requiresAuth: true, title: 'الألبوم الرئيسي' }
    }]
  },
  {
    path: '/college_strategic',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'college_strategic',
      component: () => import('./pages/college_startegic.vue'),
      meta: { requiresAuth: true, title: 'الرؤية الرسالة الأهداف' }
    }]
  },
  {
    path: '/ads',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'ads',
      component: () => import('./pages/ads.vue'),
      meta: { requiresAuth: true, title: 'الإعلانات' }
    }]
  },
  {
    path: '/ads_en',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'ads_en',
      component: () => import('./pages/ads_en.vue'),
      meta: { requiresAuth: true, title: 'Advertisements' }
    }]
  },
  {
    path: '/calendar',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'calendar',
      component: () => import('./pages/calendar.vue'),
      meta: { requiresAuth: true, title: 'التقويم' }
    }]
  },
  {
    path: '/calendar_en',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'calendar_en',
      component: () => import('./pages/calendar_en.vue'),
      meta: { requiresAuth: true, title: 'Calendar' }
    }]
  },
  {
    path: '/college',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'college',
      component: () => import('./pages/college.vue'),
      meta: { requiresAuth: true, title: 'الكليات' }
    }]
  },
  {
    path: '/category',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'category',
      component: () => import('./pages/category.vue'),
      meta: { requiresAuth: true, title: 'الفئات' }
    }]
  },
  {
    path: '/category_page',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'category_page',
      component: () => import('./pages/category_page.vue'),
      meta: { requiresAuth: true, title: 'صفحة الفئة' }
    }]
  },
  {
    path: '/category_page_user',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'category_page_user',
      component: () => import('./pages/category_page_user.vue'),
      meta: { requiresAuth: true, title: 'صفحة مستخدم الفئة' }
    }]
  },
  {
    path: '/dashboard',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'dashboard',
      component: () => import('./pages/dashboard.vue'),
      meta: { requiresAuth: true, title: 'لوحة التحكم' }
    }]
  },
  {
    path: '/profile',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'profile',
      component: () => import('./pages/profile.vue'),
      meta: { requiresAuth: true, title: 'الملف الشخصي' }
    }]
  },
  {
    path: '/department',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'department',
      component: () => import('./pages/department.vue'),
      meta: { requiresAuth: true, title: 'الأقسام' }
    }]
  },
  {
    path: '/section',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'section',
      component: () => import('./pages/section.vue'),
      meta: { requiresAuth: true, title: 'الشعب' }
    }]
  },
  {
    path: '/school',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'school',
      component: () => import('./pages/school.vue'),
      meta: { requiresAuth: true, title: 'المدارس' }
    }]
  },
  {
    path: '/administrative_positions',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'administrative_positions',
      component: () => import('./pages/administrative_positions.vue'),
      meta: { requiresAuth: true, title: 'المناصب الإدارية' }
    }]
  },
  {
    path: '/page/:slug',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'dynamicPage',
      component: () => import('./pages/page/DynamicPage.vue'),
      meta: { requiresAuth: true }
    }]
  },
  {
    path: '/head_of_department',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'head_of_department',
      component: () => import('./pages/head_of_department.vue'),
      meta: { requiresAuth: true, title: 'رئيس القسم' }
    }]
  },
  {
    path: '/dean_of_college',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'dean_of_college',
      component: () => import('./pages/dean_of_college.vue'),
      meta: { requiresAuth: true, title: 'عميد الكلية' }
    }],
  },
  {
    path: '/news',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'news',
      component: () => import('./pages/news.vue'),
      meta: { requiresAuth: true, title: 'الأخبار' }
    }]
  },
  {
    path: '/news_en',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'news_en',
      component: () => import('./pages/news_en.vue'),
      meta: { requiresAuth: true, title: 'News' }
    }]
  },
  {
    path: '/news_migration',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'news_migration',
      component: () => import('./pages/news_migration.vue'),
      meta: { requiresAuth: true, title: 'نقل الأخبار من الموقع السابق' }
    }]
  },
  {
    path: '/workshops',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'college_workshops',
      component: () => import('./pages/college_workshops.vue'),
      meta: { requiresAuth: true, title: 'الورش والمؤتمرات' }
    }]
  },
  {
    path: '/workshops_en',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'college_workshops_en',
      component: () => import('./pages/college_workshops_en.vue'),
      meta: { requiresAuth: true, title: 'Workshops & Conferences' }
    }]
  },
  {
    path: '/staff_academic/',
    component: DefaultLayout,
    children: [
      { path: 'about_me', name: 'about_me', component: () => import('./pages/about_me.vue'), meta: { requiresAuth: true, title: 'نبذة عني' } },
      { path: 'books', name: 'books', component: () => import('./pages/books.vue'), meta: { requiresAuth: true, title: 'الكتب' } },
      { path: 'scientific_papers', name: 'scientific_papers', component: () => import('./pages/scientific_papers.vue'), meta: { requiresAuth: true, title: 'الأوراق العلمية' } },
      { path: 'running_projects', name: 'running_projects', component: () => import('./pages/running_projects.vue'), meta: { requiresAuth: true, title: 'المشاريع الجارية' } },
      { path: 'courses', name: 'courses', component: () => import('./pages/courses.vue'), meta: { requiresAuth: true, title: 'الكورسات' } },
      { path: 'links', name: 'links', component: () => import('./pages/links.vue'), meta: { requiresAuth: true, title: 'الروابط' } },
      { path: 'communityservice', name: 'communityservice', component: () => import('./pages/communityservice.vue'), meta: { requiresAuth: true, title: 'خدمة المجتمع' } },
      { path: 'supervising_projects', name: 'supervising_projects', component: () => import('./pages/supervising_projects.vue'), meta: { requiresAuth: true, title: 'المشاريع المشرفة' } },
      { path: 'research_topics', name: 'research_topics', component: () => import('./pages/research_topics.vue'), meta: { requiresAuth: true, title: 'مواضيع البحث' } },
      { path: 'positions', name: 'positions', component: () => import('./pages/positions.vue'), meta: { requiresAuth: true, title: 'المناصب' } },
      { path: 'committees', name: 'committees', component: () => import('./pages/committees.vue'), meta: { requiresAuth: true, title: 'اللجان' } },
      { path: 'training_courses', name: 'training_courses', component: () => import('./pages/training_courses.vue'), meta: { requiresAuth: true, title: 'دورات التدريب' } },
      { path: 'google_scholar', name: 'google_scholar', component: () => import('./pages/google_scholar.vue'), meta: { requiresAuth: true, title: 'Google Scholar' } },
      { path: 'articles', name: 'articles', component: () => import('./pages/articles.vue'), meta: { requiresAuth: true, title: 'المقالات' } },
      { path: 'workshops', name: 'workshops', component: () => import('./pages/workshops.vue'), meta: { requiresAuth: true, title: 'الورش والمؤتمرات والسمنارات' } },
      { path: 'workshpos', name: 'workshpos', component: () => import('./pages/workshops.vue'), meta: { requiresAuth: true, title: 'الورش والمؤتمرات والسمنارات' } },
      { path: 'certificates', name: 'certificates', component: () => import('./pages/certificates.vue'), meta: { requiresAuth: true, title: 'الجوائز وشهادات التقدير' } },
      { path: 'staff_resume', name: 'staff_resume', component: () => import('./pages/staff_resume.vue'), meta: { requiresAuth: true, title: 'سيرة الموظف' } },
      { path: 'staff_album_photos', name: 'staff_album_photos', component: () => import('./pages/staff_album_photos.vue'), meta: { requiresAuth: true, title: 'ألبوم صور الموظف' } },
    ]
  },
  {
    path: '/academic_programs',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'academic_programs',
      component: () => import('./pages/academic_programs.vue'),
      meta: { requiresAuth: true, title: 'البرامج الأكاديمية' }
    }]
  },
  {
    path: '/academic_courses',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'academic_courses',
      component: () => import('./pages/academic_courses.vue'),
      meta: { requiresAuth: true, title: 'المقررات الدراسية' }
    }]
  },
  {
    path: '/user',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'user',
      component: () => import('./pages/user.vue'),
      meta: { requiresAuth: true, title: 'المستخدم' }
    }]
  },
  {
    path: '/user_upload_file',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'user_upload_file',
      component: () => import('./pages/user_upload_file.vue'),
      meta: { requiresAuth: true, title: 'رفع ملف المستخدم' }
    }]
  },
  {
    path: '/vice_chancellor',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'vice_chancellor',
      component: () => import('./pages/vice_chancellor.vue'),
      meta: { requiresAuth: true, title: 'مدير الجامعة' }
    }],
  },
  {
    path: '/head_administrative_positions',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'head_administrative_positions',
      component: () => import('./pages/head_administrative_positions.vue'),
      meta: { requiresAuth: true, title: 'الإدارة العليا للجامعة' }
    }],
  },
  {
    path: '/administrative_positions',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'administrative_positions',
      component: () => import('./pages/administrative_positions.vue'),
      meta: { requiresAuth: true, title: 'المناصب الإدارية' }
    }],
  },
  {
    path: '/:pathMatch(.*)*',
    component: () => import('./pages/PageNotFound.vue'),
    meta: { isPublic: true, title: 'الصفحة غير موجودة' }
  }
];

const router = createRouter({
  history: createWebHistory('/admin'),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (to.path !== from.path) return { top: 0, behavior: 'smooth' };
    return savedPosition || { top: 0 };
  }
});

router.beforeEach(async (to, from) => {
  // Check for new version on navigation (if __APP_VERSION__ is defined by Vite)
  if (from && to.name !== from.name && typeof __APP_VERSION__ !== 'undefined') {
    try {
      const response = await fetch('/version.json?t=' + Date.now(), { cache: 'no-store' });
      const data = await response.json();
      if (data.version && data.version !== __APP_VERSION__) {
        console.log('New version detected! Reloading...');
        window.location.reload(true);
        return;
      }
    } catch (e) {
      // Ignore fetch errors to not block navigation
    }
  }

  const authStore = useAuthStore();

  document.title = to.meta.title
    ? `${to.meta.title} | ${APP_NAME}`
    : APP_NAME;

  if (!authStore.initialized) {
    try {
      await authStore.initialize();
    } catch (error) {
      console.error('Auth initialization failed:', error);
    }
  }

  const needsPasswordChange = authStore.user?.force_password_change;

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    localStorage.setItem('redirectUrl', to.fullPath);
    return { name: 'Login' };
  }

  if (to.meta.requiresAuth && authStore.isAuthenticated && needsPasswordChange) {
    authStore.clearAuth();
    return { name: 'Login' };
  }

  if (to.name === 'Login' && authStore.isAuthenticated) {
    if (needsPasswordChange) {
      authStore.clearAuth();
      return true;
    }
    const redirectUrl = localStorage.getItem('redirectUrl');
    localStorage.removeItem('redirectUrl');

    if (redirectUrl && redirectUrl !== '/') {
      if (redirectUrl.startsWith('/')) {
        return { path: redirectUrl, replace: true };
      }
      return { name: redirectUrl, replace: true };
    }
    return { name: 'dashboard', replace: true };
  }

  return true;
});

// Automatic Chunk Load Error Recovery (prevents blank pages after new builds without requiring manual Ctrl+F5)
router.onError((error, to) => {
  const isChunkLoadError = 
    error?.message?.includes('Failed to fetch dynamically imported module') ||
    error?.message?.includes('Importing a module script failed') ||
    error?.name === 'ChunkLoadError' ||
    error?.message?.includes('404');

  if (isChunkLoadError && to?.fullPath) {
    const key = `chunk_reload_${to.fullPath}`;
    if (!sessionStorage.getItem(key)) {
      sessionStorage.setItem(key, 'true');
      window.location.reload();
    }
  }
});

export default router;
