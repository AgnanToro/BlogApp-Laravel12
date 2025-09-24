<template>
  <div class="d-flex">
    <!-- Sidebar -->
    <nav class="penulis-sidebar" :class="{ 'show': sidebarOpen }" ref="sidebar">
      <router-link 
        :to="{ name: 'penulis.dashboard' }" 
        class="sidebar-brand d-flex align-items-center gap-3 p-4"
      >
        <span class="d-flex align-items-center justify-content-center brand-icon">
          <i class="fas fa-blog"></i>
        </span>
        <div class="d-flex flex-column align-items-start justify-content-center lh-1">
          <span class="fw-bold brand-title">BlogSpace</span>
          <span class="fw-semibold brand-subtitle">Penulis</span>
        </div>
      </router-link>
      
      <ul class="nav nav-pills flex-column sidebar-nav">
        <li class="nav-item">
          <router-link 
            :to="{ name: 'penulis.dashboard' }" 
            class="nav-link"
            :class="{ 'active': $route.name === 'penulis.dashboard' }"
          >
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
          </router-link>
        </li>
        <li class="nav-item">
          <router-link 
            :to="{ name: 'penulis.posts.index' }" 
            class="nav-link"
            :class="{ 'active': $route.name?.startsWith('penulis.posts') }"
          >
            <i class="fas fa-newspaper me-2"></i>Kelola Posts
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
    <div class="penulis-content flex-grow-1">
      <!-- Header -->
      <header class="penulis-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
          <button 
            class="btn btn-outline-light sidebar-toggle me-3" 
            @click="toggleSidebar"
          >
            <i class="fas fa-bars"></i>
          </button>
          <h5 class="mb-0 text-white">{{ pageTitle || 'Dashboard' }}</h5>
        </div>
        
        <div class="d-flex align-items-center">
          <span class="me-3 text-white">Welcome, {{ authStore.user?.name }}</span>
          <div class="dropdown" ref="dropdown">
            <button 
              class="btn btn-outline-light dropdown-toggle" 
              type="button" 
              @click="toggleDropdown"
            >
              <i class="fas fa-user-circle me-2"></i>{{ authStore.user?.name }}
            </button>
            <ul class="dropdown-menu" :class="{ 'show': dropdownOpen }">
              <li>
                <router-link :to="{ name: 'penulis.profile' }" class="dropdown-item">
                  <i class="fas fa-user me-2"></i>Profile
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
      </header>
      
      <!-- Content -->
      <main class="p-4">
        <!-- Success Alert -->
        <div 
          v-if="successMessage" 
          class="alert alert-success alert-dismissible fade show" 
          role="alert"
        >
          <i class="fas fa-check-circle me-2"></i>
          {{ successMessage }}
          <button type="button" class="btn-close" @click="successMessage = ''"></button>
        </div>
        
        <!-- Error Alert -->
        <div 
          v-if="errorMessage" 
          class="alert alert-danger alert-dismissible fade show" 
          role="alert"
        >
          <i class="fas fa-exclamation-circle me-2"></i>
          {{ errorMessage }}
          <button type="button" class="btn-close" @click="errorMessage = ''"></button>
        </div>
        
        <!-- Main Content Slot -->
        <slot />
      </main>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted, provide } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

export default {
  name: 'PenulisLayout',
  props: {
    pageTitle: {
      type: String,
      default: 'Dashboard'
    }
  },
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()
    
    const sidebarOpen = ref(false)
    const dropdownOpen = ref(false)
    const dropdown = ref(null)
    const successMessage = ref('')
    const errorMessage = ref('')
    
    const toggleSidebar = () => {
      sidebarOpen.value = !sidebarOpen.value
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

    // Provide methods to child components
    provide('showSuccess', showSuccess)
    provide('showError', showError)

    return {
      authStore,
      sidebarOpen,
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
:root {
  --penulis-primary: #2563eb;
  --penulis-secondary: #3b82f6;
  --sidebar-bg: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
  --sidebar-text: #e0e7ef;
  --sidebar-active: #fff;
}

body {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
  background: #f8fafc;
}

.penulis-sidebar {
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

.penulis-content {
  margin-left: 250px;
  min-height: 100vh;
  transition: all 0.3s ease;
}

.penulis-header {
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

.brand-icon {
  width: 48px;
  height: 48px;
  background: rgba(255,255,255,0.15);
  border-radius: 12px;
  font-size: 2rem;
  color: #fff;
}

.brand-title {
  font-size: 1.3rem;
  letter-spacing: 1px;
}

.brand-subtitle {
  font-size: 1.05rem;
  letter-spacing: 1px;
  margin-top: 2px;
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

.btn-penulis-primary {
  background: linear-gradient(135deg, var(--penulis-primary) 0%, var(--penulis-secondary) 100%);
  border: none;
  color: white;
  font-weight: 600;
  padding: 0.5rem 1.5rem;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.btn-penulis-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
  color: white;
}

.stats-card {
  background: linear-gradient(135deg, #fff 0%, #f8fafc 100%);
  border: 1px solid #e2e8f0;
  border-radius: 15px;
  transition: all 0.3s ease;
}

.stats-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.stats-icon {
  width: 60px;
  height: 60px;
  border-radius: 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
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

.sidebar-toggle {
  display: none;
}

.d-flex {
  display: flex;
}

.align-items-center {
  align-items: center;
}

.justify-content-between {
  justify-content: space-between;
}

.gap-3 {
  gap: 0.75rem;
}

.p-4 {
  padding: 1.5rem;
}

.lh-1 {
  line-height: 1;
}

.fw-bold {
  font-weight: 700;
}

.fw-semibold {
  font-weight: 600;
}

.flex-column {
  flex-direction: column;
}

.align-items-start {
  align-items: flex-start;
}

.justify-content-center {
  justify-content: center;
}

.flex-grow-1 {
  flex-grow: 1;
}

.mb-0 {
  margin-bottom: 0;
}

.me-3 {
  margin-right: 0.75rem;
}

.me-2 {
  margin-right: 0.5rem;
}

.mt-4 {
  margin-top: 1rem;
}

.text-white {
  color: white;
}

.text-start {
  text-align: start;
}

.w-100 {
  width: 100%;
}

.border-0 {
  border: 0;
}

.bg-transparent {
  background-color: transparent;
}

.nav-pills .nav-link {
  border-radius: 0;
}

.alert {
  border-radius: 0.5rem;
  border: none;
}

.alert-success {
  background-color: rgba(34, 197, 94, 0.1);
  color: #166534;
  border-left: 4px solid #22c55e;
}

.alert-danger {
  background-color: rgba(239, 68, 68, 0.1);
  color: #991b1b;
  border-left: 4px solid #ef4444;
}

.btn-close {
  background: none;
  border: none;
  font-size: 1.25rem;
  opacity: 0.5;
  cursor: pointer;
}

.btn-close:hover {
  opacity: 0.75;
}

.fade {
  transition: opacity 0.15s linear;
}

.show {
  opacity: 1;
}

.dropdown-menu.show {
  display: block;
}

.dropdown-divider {
  height: 0;
  margin: 0.5rem 0;
  overflow: hidden;
  border-top: 1px solid #dee2e6;
}

.dropdown-item {
  display: block;
  width: 100%;
  padding: 0.25rem 1rem;
  clear: both;
  font-weight: 400;
  color: #212529;
  text-align: inherit;
  text-decoration: none;
  white-space: nowrap;
  background-color: transparent;
  border: 0;
}

.dropdown-item:hover {
  color: #1e2125;
  background-color: #e9ecef;
}

@media (max-width: 768px) {
  .penulis-sidebar {
    transform: translateX(-100%);
  }
  
  .penulis-content {
    margin-left: 0;
  }
  
  .sidebar-toggle {
    display: block;
  }
  
  .penulis-sidebar.show {
    transform: translateX(0);
  }
}
</style>