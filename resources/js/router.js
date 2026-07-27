import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from './store/index';

const DefaultLayout = () => import('./layouts/Default.vue');
const APP_NAME = 'لوحة تحكم موقع جامعة السودان للعلوم  والتكنولوجيا';

const routes = [
  {
    name: 'Login',
    path: '/',
    component: () => import('./admin/pages/index.vue'),
    meta: { isPublic: true, title: 'تسجيل الدخول' }
  },
  {
    path: '/album',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'album',
      component: () => import('./admin/pages/album.vue'),
      meta: { requiresAuth: true, title: 'الألبوم' }
    }]
  },
  {
    path: '/college_album',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'college_album',
      component: () => import('./admin/pages/college_album.vue'),
      meta: { requiresAuth: true, title: 'الألبوم الرئيسي' }
    }]
  },
  {
    path: '/college_strategic',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'college_strategic',
      component: () => import('./admin/pages/college_startegic.vue'),
      meta: { requiresAuth: true, title: 'الرؤية الرسالة الأهداف' }
    }]
  },
  {
    path: '/ads',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'ads',
      component: () => import('./admin/pages/ads.vue'),
      meta: { requiresAuth: true, title: 'الإعلانات' }
    }]
  },
  {
    path: '/ads_en',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'ads_en',
      component: () => import('./admin/pages/ads_en.vue'),
      meta: { requiresAuth: true, title: 'Advertisements' }
    }]
  },
  {
    path: '/calendar',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'calendar',
      component: () => import('./admin/pages/calendar.vue'),
      meta: { requiresAuth: true, title: 'التقويم' }
    }]
  },
  {
    path: '/calendar_en',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'calendar_en',
      component: () => import('./admin/pages/calendar_en.vue'),
      meta: { requiresAuth: true, title: 'Calendar' }
    }]
  },
  {
    path: '/college',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'college',
      component: () => import('./admin/pages/college.vue'),
      meta: { requiresAuth: true, title: 'الكليات' }
    }]
  },
  {
    path: '/category',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'category',
      component: () => import('./admin/pages/category.vue'),
      meta: { requiresAuth: true, title: 'الفئات' }
    }]
  },
  {
    path: '/category_page',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'category_page',
      component: () => import('./admin/pages/category_page.vue'),
      meta: { requiresAuth: true, title: 'صفحة الفئة' }
    }]
  },
  {
    path: '/category_page_user',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'category_page_user',
      component: () => import('./admin/pages/category_page_user.vue'),
      meta: { requiresAuth: true, title: 'صفحة مستخدم الفئة' }
    }]
  },
  {
    path: '/dashboard',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'dashboard',
      component: () => import('./admin/pages/dashboard.vue'),
      meta: { requiresAuth: true, title: 'لوحة التحكم' }
    }]
  },
  {
    path: '/department',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'department',
      component: () => import('./admin/pages/department.vue'),
      meta: { requiresAuth: true, title: 'الأقسام' }
    }]
  },
  {
    path: '/page/:slug',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'dynamicPage',
      component: () => import('./admin/pages/page/DynamicPage.vue'),
      meta: { requiresAuth: true }
    }]
  },
  {
    path: '/head_of_department',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'head_of_department',
      component: () => import('./admin/pages/head_of_department.vue'),
      meta: { requiresAuth: true, title: 'رئيس القسم' }
    }]
  },
  {
    path: '/dean_of_college',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'dean_of_college',
      component: () => import('./admin/pages/dean_of_college.vue'),
      meta: { requiresAuth: true, title: 'عميد الكلية' }
    }],
  },
  {
    path: '/news',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'news',
      component: () => import('./admin/pages/news.vue'),
      meta: { requiresAuth: true, title: 'الأخبار' }
    }]
  },
  {
    path: '/news_en',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'news_en',
      component: () => import('./admin/pages/news_en.vue'),
      meta: { requiresAuth: true, title: 'News' }
    }]
  },
  {
    path: '/staff_academic/',
    component: DefaultLayout,
    children: [
      { path: 'about_me', name: 'about_me', component: () => import('./admin/pages/about_me.vue'), meta: { requiresAuth: true, title: 'نبذة عني' } },
      { path: 'books', name: 'books', component: () => import('./admin/pages/books.vue'), meta: { requiresAuth: true, title: 'الكتب' } },
      { path: 'scientific_papers', name: 'scientific_papers', component: () => import('./admin/pages/scientific_papers.vue'), meta: { requiresAuth: true, title: 'الأوراق العلمية' } },
      { path: 'running_projects', name: 'running_projects', component: () => import('./admin/pages/running_projects.vue'), meta: { requiresAuth: true, title: 'المشاريع الجارية' } },
      { path: 'courses', name: 'courses', component: () => import('./admin/pages/courses.vue'), meta: { requiresAuth: true, title: 'الكورسات' } },
      { path: 'links', name: 'links', component: () => import('./admin/pages/links.vue'), meta: { requiresAuth: true, title: 'الروابط' } },
      { path: 'communityservice', name: 'communityservice', component: () => import('./admin/pages/communityservice.vue'), meta: { requiresAuth: true, title: 'خدمة المجتمع' } },
      { path: 'supervising_projects', name: 'supervising_projects', component: () => import('./admin/pages/supervising_projects.vue'), meta: { requiresAuth: true, title: 'المشاريع المشرفة' } },
      { path: 'research_topics', name: 'research_topics', component: () => import('./admin/pages/research_topics.vue'), meta: { requiresAuth: true, title: 'مواضيع البحث' } },
      { path: 'positions', name: 'positions', component: () => import('./admin/pages/positions.vue'), meta: { requiresAuth: true, title: 'المناصب' } },
      { path: 'committees', name: 'committees', component: () => import('./admin/pages/committees.vue'), meta: { requiresAuth: true, title: 'اللجان' } },
      { path: 'training_courses', name: 'training_courses', component: () => import('./admin/pages/training_courses.vue'), meta: { requiresAuth: true, title: 'دورات التدريب' } },
      { path: 'google_scholar', name: 'google_scholar', component: () => import('./admin/pages/google_scholar.vue'), meta: { requiresAuth: true, title: 'Google Scholar' } },
      { path: 'articles', name: 'articles', component: () => import('./admin/pages/articles.vue'), meta: { requiresAuth: true, title: 'المقالات' } },
      { path: 'staff_resume', name: 'staff_resume', component: () => import('./admin/pages/staff_resume.vue'), meta: { requiresAuth: true, title: 'سيرة الموظف' } },
      { path: 'staff_album_photos', name: 'staff_album_photos', component: () => import('./admin/pages/staff_album_photos.vue'), meta: { requiresAuth: true, title: 'ألبوم صور الموظف' } },
    ]
  },
  {
    path: '/academic_programs',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'academic_programs',
      component: () => import('./admin/pages/academic_programs.vue'),
      meta: { requiresAuth: true, title: 'البرامج الأكاديمية' }
    }]
  },
  {
    path: '/academic_courses',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'academic_courses',
      component: () => import('./admin/pages/academic_courses.vue'),
      meta: { requiresAuth: true, title: 'المقررات الدراسية' }
    }]
  },
  {
    path: '/user',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'user',
      component: () => import('./admin/pages/user.vue'),
      meta: { requiresAuth: true, title: 'المستخدم' }
    }]
  },
  {
    path: '/user_upload_file',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'user_upload_file',
      component: () => import('./admin/pages/user_upload_file.vue'),
      meta: { requiresAuth: true, title: 'رفع ملف المستخدم' }
    }]
  },
  {
    path: '/vice_chancellor',
    component: DefaultLayout,
    children: [{
      path: '',
      name: 'vice_chancellor',
      component: () => import('./admin/pages/vice_chancellor.vue'),
      meta: { requiresAuth: true, title: 'مدير الجامعة' }
    }],
  },
  {
    path: '/:pathMatch(.*)*',
    component: () => import('./admin/pages/PageNotFound.vue'),
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

router.beforeEach(async (to) => {
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

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    localStorage.setItem('redirectUrl', to.fullPath);
    return { name: 'Login' };
  }

  if (to.name === 'Login' && authStore.isAuthenticated) {
    const redirectUrl = localStorage.getItem('redirectUrl') || 'dashboard';
    localStorage.removeItem('redirectUrl');
    return { name: redirectUrl, replace: true };
  }

  return true;
});

export default router;
