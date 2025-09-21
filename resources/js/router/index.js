import ProfileIndex from '@/pages/profile/Index.vue'
import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

// Import pages
import Home from '@/pages/Home.vue'
import PostDetail from '@/pages/PostDetail.vue'
import Login from '@/pages/auth/Login.vue'
import Register from '@/pages/auth/Register.vue'
import ForgotPassword from '@/pages/auth/ForgotPassword.vue'
import ResetPassword from '@/pages/auth/ResetPassword.vue'

// Admin pages
import AdminDashboard from '@/pages/admin/Dashboard.vue'
import AdminPostsIndex from '@/pages/admin/posts/Index.vue'
import AdminPostsCreate from '@/pages/admin/posts/Create.vue'
import AdminPostsShow from '@/pages/admin/posts/Show.vue'
import AdminPostsEdit from '@/pages/admin/posts/Edit.vue'
import AdminUsersIndex from '@/pages/admin/users/Index.vue'
import AdminUsersCreate from '@/pages/admin/users/Create.vue'
import AdminUsersEdit from '@/pages/admin/users/Edit.vue'
import AdminCommentsIndex from '@/pages/admin/comments/Index.vue'

// Penulis pages

import PenulisDashboard from '@/pages/penulis/Dashboard.vue'
import PenulisPostCreate from '@/pages/penulis/PostCreate.vue'
import PenulisPostEdit from '@/pages/penulis/PostEdit.vue'
import PenulisPostShow from '@/pages/penulis/PostShow.vue'

const routes = [
  
  {
    path: '/profile',
    name: 'profile.index',
    component: ProfileIndex,
    meta: { requiresAuth: true }
  },
  {
    path: '/',
    name: 'home',
    component: Home
  },
  {
    path: '/posts/:id',
    name: 'post.show',
    component: PostDetail
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { guest: true }
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
    meta: { guest: true }
  },
  {
    path: '/forgot-password',
    name: 'forgot-password',
    component: ForgotPassword,
    meta: { guest: true }
  },
  {
    path: '/reset-password/:token',
    name: 'reset-password',
     component: () => import('@/pages/auth/ResetPassword.vue'),
    meta: { guest: true }
  },
  {
    path: '/admin',
    children: [
      {
        path: '',
        name: 'admin.dashboard',
        component: AdminDashboard,
        meta: { requiresAuth: true, role: 'admin' }
      },
      // Posts routes
      {
        path: 'posts',
        name: 'admin.posts.index',
        component: AdminPostsIndex,
        meta: { requiresAuth: true, role: 'admin' }
      },
      {
        path: 'posts/create',
        name: 'admin.posts.create',
        component: AdminPostsCreate,
        meta: { requiresAuth: true, role: 'admin' }
      },
      {
        path: 'posts/:id',
        name: 'admin.posts.show',
        component: AdminPostsShow,
        meta: { requiresAuth: true, role: 'admin' }
      },
      {
        path: 'posts/:id/edit',
        name: 'admin.posts.edit',
        component: AdminPostsEdit,
        meta: { requiresAuth: true, role: 'admin' }
      },
      // Users routes
      {
        path: 'users',
        name: 'admin.users.index',
        component: AdminUsersIndex,
        meta: { requiresAuth: true, role: 'admin' }
      },
      {
        path: 'users/create',
        name: 'admin.users.create',
        component: AdminUsersCreate,
        meta: { requiresAuth: true, role: 'admin' }
      },
      {
        path: 'users/:id/edit',
        name: 'admin.users.edit',
        component: AdminUsersEdit,
        meta: { requiresAuth: true, role: 'admin' }
      },
      // Comments routes
      {
        path: 'comments',
        name: 'admin.comments.index',
        component: AdminCommentsIndex,
        meta: { requiresAuth: true, role: 'admin' }
      }
    ]
  },
  {
    path: '/penulis',
    children: [
      {
        path: '',
        name: 'penulis.dashboard',
        component: PenulisDashboard,
        meta: { requiresAuth: true, role: 'penulis' }
      },
      {
  path: 'posts',
  name: 'penulis.posts.index',
  component: () => import('@/pages/penulis/PostsIndex.vue'),
  meta: { requiresAuth: true, role: 'penulis' }
      },
      {
        path: 'posts/create',
        name: 'penulis.posts.create',
        component: PenulisPostCreate,
        meta: { requiresAuth: true, role: 'penulis' }
      },
      {
        path: 'posts/:id',
        name: 'penulis.posts.show',
        component: PenulisPostShow,
        meta: { requiresAuth: true, role: 'penulis' }
      },
      {
        path: 'posts/:id/edit',
        name: 'penulis.posts.edit',
        component: PenulisPostEdit,
        meta: { requiresAuth: true, role: 'penulis' }
      },
      {
        path: 'profile',
        name: 'penulis.profile',
        component: ProfileIndex,
        meta: { requiresAuth: true, role: 'penulis' }
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Navigation guards
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  
  // Check if route requires authentication
  if (to.meta.requiresAuth) {
    if (!authStore.isAuthenticated) {
      return next({ name: 'login' })
    }
    
    // Check role-based access
    if (to.meta.role && authStore.user?.role !== to.meta.role) {
      return next({ name: 'home' })
    }
  }
  
  // Redirect authenticated users away from guest pages
  if (to.meta.guest && authStore.isAuthenticated) {
    return next({ name: 'home' })
  }
  
  next()
})

export default router