<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <router-link to="/" class="text-xl font-bold text-gray-800">
                Blog App
              </router-link>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
              <router-link 
                to="/" 
                class="text-gray-500 hover:text-gray-700 px-3 py-2 text-sm font-medium"
                active-class="text-indigo-600 border-b-2 border-indigo-600"
              >
                Home
              </router-link>
            </div>
          </div>
          
          <div class="flex items-center space-x-4">
            <template v-if="authStore.isAuthenticated">
              <!-- Admin Menu -->
              <div v-if="authStore.isAdmin" class="relative">
                <button 
                  @click="showAdminMenu = !showAdminMenu"
                  class="text-gray-500 hover:text-gray-700 px-3 py-2 text-sm font-medium"
                >
                  Admin
                </button>
                <div 
                  v-show="showAdminMenu" 
                  class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10"
                >
                  <router-link 
                    to="/admin"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    Dashboard
                  </router-link>
                  <router-link 
                    to="/admin/posts"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    Manage Posts
                  </router-link>
                  <router-link 
                    to="/admin/comments"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    Manage Comments
                  </router-link>
                </div>
              </div>
              
              <!-- Penulis Menu -->
              <div v-if="authStore.isPenulis" class="relative">
                <button 
                  @click="showPenulisMenu = !showPenulisMenu"
                  class="text-gray-500 hover:text-gray-700 px-3 py-2 text-sm font-medium"
                >
                  Penulis
                </button>
                <div 
                  v-show="showPenulisMenu" 
                  class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10"
                >
                  <router-link 
                    to="/penulis"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    Dashboard
                  </router-link>
                  <router-link 
                    to="/penulis/posts/create"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    Create Post
                  </router-link>
                </div>
              </div>
              
              <!-- User Menu -->
              <div class="relative">
                <button 
                  @click="showUserMenu = !showUserMenu"
                  class="text-gray-500 hover:text-gray-700 px-3 py-2 text-sm font-medium"
                >
                  {{ authStore.user?.name }}
                </button>
                <div 
                  v-show="showUserMenu" 
                  class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10"
                >
                  <button 
                    @click="handleLogout"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    Logout
                  </button>
                </div>
              </div>
            </template>
            
            <template v-else>
              <router-link 
                to="/login"
                class="text-gray-500 hover:text-gray-700 px-3 py-2 text-sm font-medium"
              >
                Login
              </router-link>
              <router-link 
                to="/register"
                class="bg-indigo-600 text-white px-4 py-2 text-sm font-medium rounded-md hover:bg-indigo-700"
              >
                Register
              </router-link>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main>
      <slot />
    </main>
  </div>
</template>

<script>
import { useAuthStore } from '@/stores/auth'
import { ref } from 'vue'

export default {
  name: 'AppLayout',
  setup() {
    const authStore = useAuthStore()
    const showAdminMenu = ref(false)
    const showPenulisMenu = ref(false)
    const showUserMenu = ref(false)

    const handleLogout = async () => {
      try {
        await authStore.logout()
        this.$router.push('/')
      } catch (error) {
        console.error('Logout error:', error)
      }
    }

    return {
      authStore,
      showAdminMenu,
      showPenulisMenu,
      showUserMenu,
      handleLogout
    }
  }
}
</script>