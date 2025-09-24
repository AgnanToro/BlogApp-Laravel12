<template>
  <div class="min-h-screen bg-white">
    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center py-20">
      <div class="text-xl text-gray-600">Loading...</div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="flex justify-center items-center py-20">
      <div class="text-xl text-red-600">{{ error }}</div>
    </div>

    <!-- Article Content -->
    <div v-else-if="post">
      <!-- Hero Section -->
      <section class="gradient-bg text-white py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center">
            <!-- Article Title -->
            <h1 class="text-3xl md:text-4xl font-bold mb-6 leading-tight">{{ post.judul }}</h1>
          </div>
        </div>
      </section>

      <!-- Article Content -->
      <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- Article Header Image -->
          <img 
            v-if="post.foto" 
            :src="getImageUrl(post.foto)" 
            :alt="post.judul"
            class="w-full h-64 md:h-96 object-cover object-center rounded-xl mb-8"
          />
          <div v-else class="h-64 md:h-96 bg-gradient-to-r from-blue-400 to-blue-600 rounded-xl mb-8 flex items-center justify-center">
            <div class="text-center">
              <i class="fas fa-image text-8xl text-white opacity-50 mb-4"></i>
              
            </div>
          </div>
          <!-- Article Content -->
          <div class="prose prose-lg max-w-none">
            <div class="text-gray-700 leading-relaxed text-lg" v-html="formatContent(post.konten)"></div>
          </div>
          <!-- Article Footer -->
          <div class="mt-12 pt-8 border-t border-gray-200">
            <div class="flex items-center justify-between flex-wrap gap-4">
              <div class="flex items-center text-gray-600">
                <i class="fas fa-clock mr-2"></i>
                <span>Dipublikasikan {{ formatDate(post.tanggal_post) }}</span>
                <span v-if="post.user" class="mx-3">•</span>
                <i v-if="post.user" class="fas fa-user mr-2"></i>
                <span v-if="post.user">Oleh <strong>{{ post.user.name }}</strong></span>
              </div>
              <div class="flex items-center">
                <span class="text-gray-600">
                  <i class="fas fa-comments mr-1"></i>{{ comments.length }} komentar
                </span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Comments Section -->
      <section class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-8">
              <i class="fas fa-comments text-blue-600 mr-3"></i>
              Komentar ({{ comments.length }})
            </h2>
            <!-- Comment Form -->
            <div class="mb-8">
              <div class="p-6 bg-blue-50 rounded-xl">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">
                  <i class="fas fa-comment-dots mr-2 text-blue-500"></i>
                  Tinggalkan Komentar
                </h3>
                <template v-if="authStore.isAuthenticated">
                  <form @submit.prevent="submitComment">
                    <div class="mb-4">
                      <label for="nama_komentator" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Anda
                      </label>
                      <input 
                        type="text" 
                        id="nama_komentator"
                        v-model="commentForm.nama_komentator"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="Masukkan nama Anda"
                        required
                      />
                    </div>
                    <div class="mb-6">
                      <label for="isi_komentar" class="block text-sm font-medium text-gray-700 mb-2">
                        Komentar
                      </label>
                      <textarea 
                        id="isi_komentar"
                        v-model="commentForm.isi_komentar"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                        placeholder="Tulis komentar Anda di sini..."
                        required
                      ></textarea>
                    </div>
                    <div class="flex justify-end">
                      <button 
                        type="submit" 
                        :disabled="submittingComment"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 hover:shadow-lg transition-all duration-200 flex items-center space-x-2 disabled:opacity-50"
                      >
                        <i class="fas fa-paper-plane"></i>
                        <span>{{ submittingComment ? 'Mengirim...' : 'Kirim Komentar' }}</span>
                      </button>
                    </div>
                  </form>
                </template>
                <template v-else>
                  <div class="text-center text-blue-600 font-semibold py-4">
                    Login dulu untuk bisa menulis komentar
                  </div>
                </template>
              </div>
            </div>
            <!-- Comments List -->
            <div v-if="comments.length > 0" class="space-y-6">
              <div 
                v-for="comment in comments" 
                :key="comment.id"
                class="bg-gray-50 p-6 rounded-xl border-l-4 border-blue-500"
              >
                <div class="flex items-center justify-between mb-3">
                  <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                      <i class="fas fa-user text-white"></i>
                    </div>
                    <div>
                      <h4 class="font-semibold text-gray-800">{{ comment.nama_komentator }}</h4>
                      <p class="text-sm text-gray-500">
                        <i class="fas fa-clock mr-1"></i>
                        {{ formatDate(comment.created_at) }}
                      </p>
                    </div>
                  </div>
                </div>
                <p class="text-gray-700 leading-relaxed">{{ comment.isi_komentar }}</p>
              </div>
            </div>
            <div v-else class="text-center py-12">
              <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-comments text-3xl text-gray-400"></i>
              </div>
              <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Komentar</h3>
              <p class="text-gray-600">Jadilah yang pertama memberikan komentar untuk artikel ini!</p>
            </div>
          </div>
        </div>
      </section>
      <!-- Navigation Button -->
      <section class="py-8 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <router-link 
            to="/" 
            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
          >
            Kembali ke Beranda
          </router-link>
        </div>
      </section>
   
    </div>
  </div>
</template>

<script>
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import axios from '@/lib/axios'

export default {
  name: 'PostDetail',
  setup() {
    const route = useRoute()
    const router = useRouter()
    const authStore = useAuthStore()
    
    const post = ref(null)
    const loading = ref(true)
    const error = ref(null)
    const comments = ref([])
    const showAdminMenu = ref(false)
    const showUserMenu = ref(false)
    // Add comment form state
    const commentForm = ref({
      nama_komentator: '',
      isi_komentar: ''
    });
    const submittingComment = ref(false);

    // Submit comment
    const submitComment = async () => {
      if (!commentForm.value.nama_komentator || !commentForm.value.isi_komentar) return;
      submittingComment.value = true;
      try {
        await axios.post(`/api/posts/${route.params.id}/comments`, {
          nama_komentator: commentForm.value.nama_komentator,
          isi_komentar: commentForm.value.isi_komentar
        });
        commentForm.value.nama_komentator = '';
        commentForm.value.isi_komentar = '';
        await fetchComments();
      } catch (err) {
        alert('Gagal mengirim komentar.');
      } finally {
        submittingComment.value = false;
      }
    };
    const fetchComments = async () => {
      try {
        const response = await axios.get(`/api/posts/${route.params.id}/comments`)
        // Komentar hanya yang approved, ambil dari response.data.data jika ada
        if (response.data && Array.isArray(response.data.data)) {
          comments.value = response.data.data;
        } else if (Array.isArray(response.data)) {
          comments.value = response.data;
        } else {
          comments.value = [];
        }
      } catch (err) {
        console.error('Error fetching comments:', err)
        comments.value = []
      }
    }

    const fetchPost = async () => {
      try {
        loading.value = true
        const response = await axios.get(`/api/posts/${route.params.id}`)
        // Always use response.data.data for post detail jika ada
        if (response.data && response.data.data) {
          post.value = response.data.data;
        } else if (response.data) {
          post.value = response.data;
        } else {
          post.value = null;
        }
      } catch (err) {
        console.error('Error fetching post:', err)
        error.value = 'Artikel tidak ditemukan atau terjadi kesalahan.'
      } finally {
        loading.value = false
      }
    }

    const formatDate = (dateString) => {
      if (!dateString) return ''
      return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
      })
    }

    const formatContent = (content) => {
      if (!content) return ''
      // Convert line breaks to paragraphs for better formatting
      return content.replace(/\n\n/g, '</p><p>').replace(/\n/g, '<br>')
    }

    const getImageUrl = (imagePath) => {
      if (!imagePath) return ''
      // If the path already starts with http, return as is
      if (imagePath.startsWith('http')) {
        return imagePath
      }
      // Otherwise, construct the full URL
      return `${window.location.origin}/storage/${imagePath}`
    }

    const handleImageError = (event) => {
      // Hide image if it fails to load
      event.target.style.display = 'none'
    }

    const shareOnTwitter = () => {
      const url = encodeURIComponent(window.location.href)
      const text = encodeURIComponent(post.value.judul)
      window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank')
    }

    const shareOnFacebook = () => {
      const url = encodeURIComponent(window.location.href)
      window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank')
    }

    const copyLink = async () => {
      try {
        await navigator.clipboard.writeText(window.location.href)
        alert('Link berhasil disalin!')
      } catch (err) {
        console.error('Failed to copy link:', err)
      }
    }

    const handleLogout = async () => {
      try {
        await authStore.logout()
        router.push('/')
      } catch (error) {
        console.error('Logout error:', error)
      }
    }


    // Fetch post and comments on mount and when route changes
    const loadArticle = () => {
      error.value = null;
      loading.value = true;
      post.value = null;
      comments.value = [];
      fetchPost();
      fetchComments();
    };

    onMounted(() => {
      loadArticle();
    });

    watch(() => route.params.id, () => {
      loadArticle();
    });

    return {
      post,
      loading,
      error,
      comments,
      authStore,
      showAdminMenu,
      showUserMenu,
      commentForm,
      submittingComment,
      formatDate,
      formatContent,
      getImageUrl,
      handleImageError,
      shareOnTwitter,
      shareOnFacebook,
      copyLink,
      handleLogout,
      submitComment
    }
  }
}
</script>

<style scoped>
.prose {
  max-width: none;
}

.prose p {
  margin-bottom: 1rem;
  line-height: 1.75;
}

.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
  margin-top: 2rem;
  margin-bottom: 1rem;
  font-weight: 600;
}

.prose img {
  margin: 2rem 0;
  border-radius: 0.5rem;
}

.prose blockquote {
  border-left: 4px solid #8b5cf6;
  padding-left: 1rem;
  margin: 2rem 0;
  font-style: italic;
  color: #6b7280;
}
</style>