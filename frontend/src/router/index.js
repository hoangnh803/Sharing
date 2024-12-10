import { createRouter, createWebHistory } from 'vue-router';

import HomePage from '../views/HomePage.vue';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import ForgotPassword from '../views/ForgotPassword.vue';
import UploadDocument from '../views/UploadDocument.vue';
import AdminLayout from '../layouts/AdminLayout.vue';
import AdminDashboard from '../views/AdminDashboard.vue';
import AdminUsers from '../views/AdminUsers.vue';
import AdminDocuments from '../views/AdminDocuments.vue';
import UniversityPage from '../views/UniversityPage.vue';
import SearchView from '../views/SearchView.vue';
import UniversityChild from '../views/UniversityChild.vue';
import CoursePage from '../views/CoursePage.vue';
import DocumentView from '../views/DocumentView.vue';
import HomeView from '../views/HomeView.vue';
import AdminUniversity from '../views/AdminUniversity.vue';
import UniversitySubjects from '../views/UniversitySubjects.vue';
import ClassManagement from '../views/ClassManagement.vue';
import ClassSubjects from '../views/ClassSubjects.vue';
import UserLayout from '../layouts/UserLayout.vue';
import ProfilePage from '../views/ProfilePage.vue';
import UserSettingLayout from '../layouts/UserSettingLayout.vue';
import UserDocument from '../views/UserDocument.vue';
import UserNotification from '../views/UserNotification.vue';
import UploadDetail from '../views/UploadDetail.vue';
import Congartulations from '../views/CongartulationPage.vue';

const routes = [
  {path: '/', name: 'HomePage', component: HomePage},
  { 
    path: '/upload', 
    component: UploadDocument, 
    meta: { requiresAuth: true },
  },
  {
    path: '/upload/details',
    component: UploadDetail,
  },
  {
    path: '/',
    component: UserLayout,
    children: [
      { path: '/university', component: UniversityPage },
      { path: '/university/:id', component: UniversityChild },
      { path: '/courses/:id', component: CoursePage },
      { path: '/document/:id', component: DocumentView },
      {
        path: '/search',
        name: 'SearchView',
        component: SearchView,
      },
      {
        path: '/congartulations',
        name: 'Congartulations',
        component: Congartulations,
      },
      {
        path: '/user',
        component: UserSettingLayout,
        meta: { requiresAuth: true },
        children: [
          {
            path: 'profile',
            name: 'Profile',
            component: ProfilePage
          },
          {
            path: 'documents',
            name: 'UserDocument',
            component: UserDocument
          },
          {
            path: 'notifications',
            name: 'UserNotification',
            component: UserNotification
          },
        ]
      }
    ],
  },
  { path: '/', component: HomePage },
  { path: '/login', component: Login, name: 'Login' },
  { path: '/register', component: Register },
  { path: '/forgot-password', component: ForgotPassword },

  {
    path: '/home', 
    component: HomeView, 
    meta: { requiresAuth: true } // Yêu cầu đăng nhập
  },
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true, requiresAdmin: true },
    children: [
      { path: 'dashboard', component: AdminDashboard, name: 'AdminDashboard' },
      { path: 'users', component: AdminUsers },
      { path: 'documents', component: AdminDocuments },
      { path: 'university', component: AdminUniversity },
      {
        path: 'university/:universityId/subjects',
        component: UniversitySubjects,
        name: 'UniversitySubjects',
        props: true, // Để truyền universityId vào component
      },
      { path: 'class', component: ClassManagement },
      {
        path: 'class/:classId/subjects',
        component: ClassSubjects,
        name: 'ClassSubjects',
        props: true, // Để truyền universityId vào component
      },
    ],
  },

];



const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior(to, from, savedPosition) {
    // Kiểm tra xem có vị trí được lưu từ lần trước hay không
    if (savedPosition) {
      return savedPosition; // Trả về vị trí đã lưu (nếu có)
    }

    // Mặc định cuộn lên đầu trang khi chuyển hướng
    return { top: 0 };
  },
});

// Unified navigation guard
router.beforeEach((to, from, next) => {
  const publicRoutes = ['/', '/login', '/register'];
  const isAuthenticated = checkAuth() // Hoặc dùng checkAuth()
  const isAdmin = checkAdmin(); // Hàm kiểm tra quyền admin
  if (isAuthenticated && publicRoutes.includes(to.path)) {
    // Redirect logged-in users away from public routes
    return next('/home'); // Redirect to the dashboard or another protected route
  }

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isAuthenticated) {
      return next({ name: 'Login' });
    }
    if (to.matched.some(record => record.meta.requiresAdmin) && !isAdmin) {
      alert('Bạn không có quyền truy cập vào trang này.');
      return next({ path: '/' }); // Điều hướng về trang chủ hoặc trang khác phù hợp
    }
  }

  next(); // Cho phép truy cập nếu không có vấn đề
});

// Kiểm tra đăng nhập
function checkAuth() {
  return localStorage.getItem('token') !== null;
}

// Kiểm tra quyền admin
function checkAdmin() {
  const user = JSON.parse(localStorage.getItem('user'));
  return user && user.role === 'admin';

}
console.log(checkAuth());
console.log(checkAdmin());

export default router;
