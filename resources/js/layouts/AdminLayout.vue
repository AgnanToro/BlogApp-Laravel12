<template>
  <div id="app">
    <div v-if="authStore.isAuthenticated">
      <!-- Sidebar -->
      <nav class="admin-sidebar">
        <router-link to="/admin" class="sidebar-brand d-flex align-items-center gap-3 p-4">
          <span class="d-flex align-items-center justify-content-center" style="width:48px; height:48px; background:rgba(255,255,255,0.15); border-radius:12px;">
            <i class="fas fa-blog" style="font-size:2rem; color:#fff;"></i>
          </span>
          <div class="d-flex flex-column align-items-start justify-content-center lh-1">
            <span class="fw-bold" style="font-size:1.3rem; letter-spacing:1px;">BlogSpace</span>
            <span class="fw-semibold" style="font-size:1.05rem; letter-spacing:1px; margin-top:2px;">Admin</span>
          </div>
        </router-link>
        
        <ul class="nav nav-pills flex-column sidebar-nav">
          <li class="nav-item">
            <router-link 
              to="/admin" 
              class="nav-link"
              :class="{ 'active': $route.name === 'admin.dashboard' }"
            >
              <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </router-link>
          </li>
          <li class="nav-item">
            <router-link 
              to="/admin/posts" 
              class="nav-link"
              :class="{ 'active': $route.name?.startsWith('admin.posts') }"
            >
              <i class="fas fa-newspaper me-2"></i>Kelola Posts
            </router-link>
          </li>
          <li class="nav-item">
            <router-link 
              to="/admin/users" 
              class="nav-link"
              :class="{ 'active': $route.name?.startsWith('admin.users') }"
            >
              <i class="fas fa-users me-2"></i>Kelola Users
            </router-link>
          </li>
          <li class="nav-item">
            <router-link 
              to="/admin/comments" 
              class="nav-link"
              :class="{ 'active': $route.name?.startsWith('admin.comments') }"
            >
              <i class="fas fa-comments me-2"></i>Kelola Komentar
            </router-link>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/" target="_blank">
              <i class="fas fa-external-link-alt me-2"></i>Lihat Website
            </a>
          </li>
          <li class="nav-item mt-4">
            <button @click="handleLogout" class="nav-link border-0 bg-transparent w-100 text-start">
              <i class="fas fa-sign-out-alt me-2"></i>Logout
            </button>
          </li>
        </ul>
      </nav>
      
      <!-- Main Content -->
      <div class="admin-content">
        <!-- Header -->
        <header class="admin-header">
          <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <button class="btn btn-link d-md-none me-3 text-white" type="button" @click="toggleSidebar">
                <i class="fas fa-bars"></i>
              </button>
              <h5 class="mb-0 text-white">{{ pageTitle || 'Admin Panel' }}</h5>
            </div>
            <div class="d-flex align-items-center">
              <span class="me-3 text-white">Welcome, {{ authStore.user?.name }}</span>
              <div class="dropdown" ref="dropdown">
                <button class="btn btn-outline-light dropdown-toggle" type="button" @click="toggleDropdown">
                  <i class="fas fa-user-circle me-2"></i>{{ authStore.user?.name }}
                </button>
                <ul class="dropdown-menu" :class="{ 'show': dropdownOpen }">
                  <li>
                    <router-link class="dropdown-item" :to="{ name: 'profile.index' }">
                      <i class="fas fa-user me-2"></i>Profil
                    </router-link>
                  </li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <button @click="handleLogout" class="dropdown-item">
                      <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </button>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </header>
        
        <!-- Page Content -->
        <main class="p-4">
          <!-- Success Alert -->
          <div v-if="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ successMessage }}
            <button type="button" class="btn-close" @click="successMessage = ''"></button>
          </div>

          <!-- Error Alert -->
          <div v-if="errorMessage" class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ errorMessage }}
            <button type="button" class="btn-close" @click="errorMessage = ''"></button>
          </div>

          <!-- Main Content Slot -->
          <slot></slot>
        </main>
      </div>
    </div>
    
    <!-- Content for non-authenticated users -->
    <div v-else>
      <slot name="guest"></slot>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

export default {
  name: 'AdminLayout',
  props: {
    pageTitle: {
      type: String,
      default: 'Admin Panel'
    }
  },
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()
    
    const dropdownOpen = ref(false)
    const successMessage = ref('')
    const errorMessage = ref('')
    const dropdown = ref(null)

    const toggleSidebar = () => {
      document.querySelector('.admin-sidebar').classList.toggle('show')
    }

    const toggleDropdown = () => {
      dropdownOpen.value = !dropdownOpen.value
    }

    const handleLogout = async () => {
      try {
        await authStore.logout()
        router.push('/login')
      } catch (error) {
        console.error('Logout failed:', error)
        errorMessage.value = 'Gagal logout. Silakan coba lagi.'
      }
    }

    // Close dropdown when clicking outside
    const handleClickOutside = (event) => {
      if (dropdown.value && !dropdown.value.contains(event.target)) {
        dropdownOpen.value = false
      }
    }

    onMounted(() => {
      document.addEventListener('click', handleClickOutside)
    })

    onUnmounted(() => {
      document.removeEventListener('click', handleClickOutside)
    })

    // Method to show success message
    const showSuccess = (message) => {
      successMessage.value = message
      setTimeout(() => {
        successMessage.value = ''
      }, 5000)
    }

    // Method to show error message
    const showError = (message) => {
      errorMessage.value = message
      setTimeout(() => {
        errorMessage.value = ''
      }, 5000)
    }

    return {
      authStore,
      dropdownOpen,
      dropdown,
      successMessage,
      errorMessage,
      toggleSidebar,
      toggleDropdown,
      handleLogout,
      showSuccess,
      showError
    }
  }
}
</script>

<style scoped>
.admin-sidebar {
  background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%) !important;
  min-height: 100vh;
  width: 250px;
  position: fixed;
  left: 0;
  top: 0;
  z-index: 1000;
  transition: all 0.3s ease;
  color: #fff !important;
  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.admin-content {
  margin-left: 250px;
  min-height: 100vh;
  transition: all 0.3s ease;
}

.admin-header {
  background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%);
  color: #fff;
  padding: 1rem 2rem;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  border-bottom: none;
}

.sidebar-brand {
  padding: 1.5rem;
  border-bottom: 1px solid rgba(255,255,255,0.1);
  color: #fff !important;
  text-decoration: none;
  display: block;
  background: transparent !important;
}

.sidebar-brand:hover {
  color: white !important;
  text-decoration: none;
}

.sidebar-nav {
  padding: 1rem 0;
}

.sidebar-nav .nav-link {
  color: var(--sidebar-text);
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 0;
  transition: all 0.3s ease;
  font-weight: 500;
  text-decoration: none;
}

.sidebar-nav .nav-link i {
  width: 20px;
  text-align: center;
}

.sidebar-nav .nav-link:hover {
  color: #fff;
  background: rgba(59, 130, 246, 0.2);
  border-left: 3px solid #fff;
}

.sidebar-nav .nav-link.active {
  color: #fff;
  background: rgba(59, 130, 246, 0.4);
  border-left: 3px solid #fff;
}

.card-modern {
  border: none;
  border-radius: 15px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.btn-admin-primary {
  background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-secondary) 100%);
  border: none;
  color: white;
  border-radius: 10px;
  padding: 0.75rem 1.5rem;
  font-weight: 500;
  transition: all 0.3s ease;
}

.btn-admin-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
  color: white;
}

.icon-circle {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
}

.table th {
  background-color: #f8f9fa;
  border-bottom: 2px solid #dee2e6;
  font-weight: 600;
  color: #2d3748;
}

.badge {
  font-size: 0.75em;
  padding: 0.5em 0.75em;
}

/* Button group spacing */
.btn-group .btn {
  margin-right: 0.25rem;
}

.btn-group .btn:last-child {
  margin-right: 0;
}

/* Action buttons spacing */
.action-buttons .btn {
  margin-right: 0.5rem;
}

.action-buttons .btn:last-child {
  margin-right: 0;
}

/* Small button group spacing */
.btn-group-sm .btn {
  margin-right: 0.5rem;
  border-radius: 0.375rem !important;
}

.btn-group-sm .btn:last-child {
  margin-right: 0;
}

@media (max-width: 768px) {
  .admin-sidebar {
    margin-left: -250px;
  }
  
  .admin-content {
    margin-left: 0;
  }
  
  .admin-sidebar.show {
    margin-left: 0;
  }
}

/* Bootstrap Dropdown Fix */
.dropdown-menu.show {
  display: block;
}
</style>