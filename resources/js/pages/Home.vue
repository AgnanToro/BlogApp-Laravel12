<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <router-link to="/" class="flex items-center space-x-2 text-2xl font-bold text-blue-600">
              <span class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-blue-500 mr-2">
                <i class="fas fa-blog text-white text-2xl"></i>
              </span>
              <span>BlogSpace</span>
            </router-link>
          </div>
          
          <div class="flex items-center space-x-4">
            <template v-if="authStore.isAuthenticated">
              <div v-if="authStore.isAdmin" class="relative">
                <button
                  @click="showAdminMenu = !showAdminMenu"
                  class="bg-blue-600 text-white px-4 py-2 text-sm font-medium rounded-md hover:bg-blue-700 flex items-center"
                >
                  <i class="fas fa-user-shield mr-2"></i>
                  Admin
                  <svg :class="['ml-2 transition-transform', showAdminMenu ? 'rotate-180' : '']" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1.646 5.646a.5.5 0 0 1 .708 0L8 11.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                  </svg>
                </button>
                <div
                  v-show="showAdminMenu"
                  class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10"
                >
                  <router-link
                    to="/admin"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                  </router-link>
                  <button
                    @click="handleLogout"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                  </button>
                </div>
              </div>
              <!-- User Menu -->
             <div v-else class="relative">
  <button 
    @click="showUserMenu = !showUserMenu"
     class="bg-blue-600 text-white px-4 py-2 text-sm font-medium rounded-md hover:bg-blue-700 flex items-center"
                >
    <i class="fas fa-user-edit mr-2"></i>
    {{ authStore.user?.name }}
    <svg :class="['ml-2 transition-transform', showUserMenu ? 'rotate-180' : '']" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M1.646 5.646a.5.5 0 0 1 .708 0L8 11.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
    </svg>
  </button>
  <div 
    v-show="showUserMenu" 
    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10"
  >
    <router-link
      to="/penulis"
      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
    >
       <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Penulis
    </router-link>
    <button 
      @click="handleLogout"
      class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
    >
      <i class="fas fa-sign-out-alt mr-2"></i>Logout
    </button>
  </div>
</div>
            </template>
            
            <template v-else>
              <router-link 
                to="/login"
                class="bg-blue-600 text-white px-4 py-2 text-sm font-medium rounded-md hover:bg-blue-700 flex items-center"
              >
                <i class="fas fa-user-shield mr-2"></i>
                Login
              </router-link>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
          <h1 class="text-4xl md:text-5xl font-bold mb-4">
            Welcome to BlogSpace
          </h1>
          <p class="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto">
            Platform berbagi cerita dan pengetahuan untuk komunitas Indonesia. 
            Temukan artikel menarik dan bergabunglah dengan diskusi yang bermakna.
          </p>
        </div>
      </div>
    </div>

    <!-- Search Section -->
    <div class="bg-white py-8 border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-6">
          <h2 class="text-2xl font-semibold text-gray-900 mb-2">Cari Artikel</h2>
          <p class="text-gray-600">Temukan artikel yang Anda cari dengan mudah</p>
        </div>
        <div class="max-w-md mx-auto">
          <div class="relative">
            <div class="relative flex items-center">
              <button type="button" @click="searchPosts" class="absolute left-3 top-1/2 -translate-y-1/2 flex items-center focus:outline-none">
                <svg class="w-6 h-6 text-gray-400 hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <circle cx="11" cy="11" r="8" stroke-width="2"/>
                  <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2"/>
                </svg>
              </button>
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Cari artikel..."
                class="w-full px-5 py-3 pl-12 pr-4 text-lg text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400"
                @input="searchPosts"
                autocomplete="off"
              >
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Articles Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="text-center mb-10">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Artikel Terbaru</h2>
        <p class="text-gray-600 text-lg">Temukan insight dan pengetahuan baru dari artikel pilihan kami</p>
      </div>

      <!-- Loading State -->
      <div v-if="postsStore.isLoading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        <p class="mt-4 text-gray-600">Loading posts...</p>
      </div>

      <!-- Posts Grid -->
      <div v-else-if="postsStore.posts.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <article 
          v-for="post in postsStore.posts" 
          :key="post.id"
          class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col h-full"
        >
          <!-- Post Image -->
          <div class="aspect-w-16 aspect-h-9">
            <img 
              v-if="post.foto"
              :src="getImageUrl(post.foto)" 
              :alt="post.judul || post.title"
              class="w-full h-48 object-cover"
              @error="$event.target.style.display='none'"
            >
            <div 
              v-else
              class="w-full h-48 bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center"
            >
              <i class="fas fa-book text-white text-5xl opacity-80"></i>
            </div>
          </div>

          <!-- Post Content -->
          <div class="flex flex-col flex-1 p-6">
            <div class="mb-3 flex-0">
              <p class="text-gray-600 text-sm mb-2">
                {{ formatDate(post.tanggal_post || post.created_at) }}
                •
                {{ post.user?.name || 'Agnan Toro' }}
              </p>
              <h3 class="text-xl font-bold text-gray-900 mb-2">
                {{ post.judul || post.title }}
              </h3>
              <p class="text-gray-600 text-sm">
                {{ truncateText(post.konten || post.content, 100) }}
              </p>
            </div>
            <div class="flex-1"></div>
            <div class="border-t border-gray-200 pt-4 flex items-center justify-between mt-6">
              <span class="text-sm text-gray-500">
                {{ post.comments_count || 0 }} komentar 
              </span>
              <router-link 
                :to="`/posts/${post.id}`"
                class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors"
              >
                Baca Selengkapnya
              </router-link>
            </div>
          </div>
        </article>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <p class="text-gray-500 text-lg">No posts available yet.</p>
      </div>

      <!-- Pagination -->
        <div class="mt-12 flex flex-col items-center justify-center gap-4">
          <!-- Numbered pagination UI -->
          <div v-if="postsStore.pagination && postsStore.pagination.last_page > 1" class="simple-pagination flex items-center justify-center gap-1 mb-6">
            <button
              :disabled="postsStore.pagination.current_page === 1"
              @click="loadPage(postsStore.pagination.current_page - 1)"
              class="px-4 py-2 rounded-l-lg border border-gray-300 bg-white text-blue-600 font-semibold hover:bg-blue-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >&lt; Back</button>
            <template v-for="page in postsStore.pagination.last_page" :key="page">
              <button
                @click="loadPage(page)"
                :class="[
                  'px-3 py-2 border border-gray-300',
                  page === postsStore.pagination.current_page
                    ? 'bg-blue-600 text-white font-bold shadow'
                    : 'bg-white text-blue-600 hover:bg-blue-50',
                  'transition-colors duration-150',
                  'focus:outline-none'
                ]"
                :disabled="page === postsStore.pagination.current_page"
                style="min-width:2.5rem"
              >
                {{ page }}
              </button>
            </template>
            <button
              :disabled="postsStore.pagination.current_page === postsStore.pagination.last_page"
              @click="loadPage(postsStore.pagination.current_page + 1)"
              class="px-4 py-2 rounded-r-lg border border-gray-300 bg-white text-blue-600 font-semibold hover:bg-blue-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >Next &gt;</button>
          </div>
          <!-- Original Pagination component -->
          <Pagination
            :current-page="postsStore.pagination.current_page"
            :total-pages="postsStore.pagination.last_page"
            :max="5"
            @page-change="loadPage"
          />
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div>
            <h3 class="text-2xl font-bold text-blue-400 mb-4">BlogSpace</h3>
            <p class="text-gray-300">Platform berbagi cerita dan pengetahuan untuk komunitas Indonesia.</p>
          </div>
          
          <div>
            <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
            <ul class="space-y-2">
              <li><router-link to="/" class="text-gray-300 hover:text-white">Home</router-link></li>
              <li><router-link to="/admin" class="text-gray-300 hover:text-white">Admin</router-link></li>
              
            </ul>
          </div>
          
          <div>
            <h4 class="text-lg font-semibold mb-4">Contact</h4>
            <div class="space-y-2 text-gray-300">
              <p>agnanpes1@gmail.com</p>
              <p>+62 85624313465</p>
            </div>
          </div>
        </div>
        
        <div class="border-t border-gray-800 mt-8 pt-8 text-center">
          <p class="text-gray-400">&copy; 2025 BlogSpace. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>

import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { usePostsStore } from '@/stores/posts'
import { useAuthStore } from '@/stores/auth'
import Pagination from '@/components/Pagination.vue'


export default {
  name: 'Home',
  setup() {
    const postsStore = usePostsStore()
    const authStore = useAuthStore()
    const router = useRouter()
    
    const searchQuery = ref('')
    const showAdminMenu = ref(false)
    const showUserMenu = ref(false)

    const paginationPages = computed(() => {
      const pages = []
      const current = postsStore.pagination.current_page
      const last = postsStore.pagination.last_page
      
      const start = Math.max(1, current - 2)
      const end = Math.min(last, current + 2)
      
      for (let i = start; i <= end; i++) {
        pages.push(i)
      }
      
      return pages
    })

    const formatDate = (dateString) => {
      return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
      })
    }

    const truncateText = (text, length) => {
      if (!text) return ''
      return text.length > length ? text.substring(0, length) + '...' : text
    }

    // getCommentsCount removed: now using post.comments_count from API

    const loadPage = async (page) => {
      if (page >= 1 && page <= postsStore.pagination.last_page) {
        await postsStore.fetchPosts(page, 6)
      }
    }

    const searchPosts = async () => {
      const query = searchQuery.value.trim();
      if (!query) {
        await postsStore.fetchPosts(1);
        return;
      }
      try {
        // Use backend API for search if available
        const response = await fetch(`/api/search?q=${encodeURIComponent(query)}`);
        const data = await response.json();
        if (data && data.data) {
          postsStore.posts = data.data;
          postsStore.pagination = {
            current_page: 1,
            last_page: 1,
            per_page: data.data.length,
            total: data.data.length
          };
          return;
        }
      } catch (error) {}
      // fallback: client-side filter
      postsStore.posts = postsStore.posts.filter(post => {
        const title = (post.judul || post.title || '').toLowerCase();
        const content = (post.konten || post.content || '').toLowerCase();
        return title.includes(query.toLowerCase()) || content.includes(query.toLowerCase());
      });
    }

    const getImageUrl = (imagePath) => {
      if (!imagePath) return ''
      return `http://localhost:8000/storage/${imagePath}`
    }

    const handleLogout = async () => {
      try {
        await authStore.logout()
        router.push('/')
      } catch (error) {
        console.error('Logout error:', error)
      }
    }

    onMounted(async () => {
      await postsStore.fetchPosts(1, 6)
    })

    return {
  postsStore,
  authStore,
  searchQuery,
  showAdminMenu,
  showUserMenu,
  formatDate,
  truncateText,
  getImageUrl,
  loadPage,
  searchPosts,
  handleLogout
    }
  }
}
</script>