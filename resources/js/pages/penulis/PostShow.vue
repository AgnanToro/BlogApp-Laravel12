<template>
  <PenulisLayout :pageTitle="'Kelola Post'">
    <div class="container-fluid">
      <!-- Loading State -->
      <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <p class="text-muted mt-2">Memuat post...</p>
      </div>

      <!-- Content -->
      <div v-else-if="!error && post && post.id">
        <!-- Header -->
        <div class="row">
          <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h1 class="fw-bold text-dark">{{ post.judul }}</h1>
              <div>
                <router-link 
                  :to="{ name: 'penulis.posts.edit', params: { id: post.id } }" 
                  class="btn btn-warning me-2"
                >
                  <i class="fas fa-edit me-1"></i>Edit
                </router-link>
                <router-link 
                  :to="{ name: 'penulis.posts.index' }" 
                  class="btn btn-secondary"
                >
                  <i class="fas fa-arrow-left me-1"></i>Kembali
                </router-link>
              </div>
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="row mt-4">
          <!-- Konten Utama -->
          <div class="col-md-8">
            <div class="card card-modern">
              <div class="card-body">
                <h5 class="card-title fw-bold">{{ post.judul }}</h5>
                <p class="text-muted">
                  <small>
                    <i class="fas fa-calendar me-1"></i>
                    Dipublikasikan: {{ formatDateTime(post.tanggal_post) }}
                  </small>
                </p>
                <!-- Post Image -->
                <div v-if="post.foto" class="mb-4">
                  <img 
                    :src="getImageUrl(post.foto)" 
                    :alt="post.judul"
                    class="img-fluid rounded shadow-sm"
                    style="max-width: 100%; height: auto;"
                  >
                </div>
                <!-- Post Content -->
                <div class="card-text">
                  <div v-html="formatContent(post.konten)"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Sidebar -->
          <div class="col-md-4">
            <!-- Informasi Post -->
            <div class="card card-modern">
              <div class="card-header bg-light">
                <h5 class="mb-0 fw-semibold">
                  <i class="fas fa-info-circle me-2 text-info"></i>Informasi Post
                </h5>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <strong class="text-muted">ID:</strong>
                  <span class="ms-2">#{{ post.id }}</span>
                </div>
                <div class="mb-3">
                  <strong class="text-muted">Penulis:</strong>
                  <div class="d-flex align-items-center mt-1">
                    <div class="avatar-sm bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center me-2">
                      <i class="fas fa-user text-white"></i>
                    </div>
                    <span>{{ post.user?.name || 'Unknown' }}</span>
                  </div>
                </div>
                <div class="mb-3">
                  <strong class="text-muted">Tanggal Post:</strong>
                  <div class="mt-1">{{ formatDateTime(post.created_at) }}</div>
                </div>
                <div class="mb-3">
                  <strong class="text-muted">Dibuat:</strong>
                  <div class="mt-1">{{ formatDateTime(post.created_at) }}</div>
                </div>
                <div class="mb-3">
                  <strong class="text-muted">Diupdate:</strong>
                  <div class="mt-1">{{ formatDateTime(post.updated_at) }}</div>
                </div>
                <div class="mb-0">
                  <strong class="text-muted">Total Komentar:</strong>
                  <span class="badge bg-info ms-2">{{ post.comments_count || 0 }}</span>
                </div>
              </div>
            </div>

            <!-- Komentar -->
            <div class="card card-modern mt-3">
              <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                  <i class="fas fa-comments me-2 text-success"></i>Komentar
                </h5>
                <span class="badge bg-info">{{ comments.length }} komentar</span>
              </div>
              <div class="card-body">
                <div v-if="comments.length === 0" class="text-center py-4 text-muted">
                  <i class="fas fa-comment-slash fa-2x mb-2"></i>
                  <p class="mb-0">Belum ada komentar untuk post ini</p>
                </div>
                <div v-else>
                  <div v-for="comment in comments" :key="comment.id" class="comment-item border-bottom pb-3 mb-3">
                    <div class="d-flex align-items-start">
                      <div class="avatar-sm bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0">
                        <i class="fas fa-user text-white"></i>
                      </div>
                      <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                          <div>
                            <strong class="fw-bold text-dark">{{ comment.nama_komentator || comment.name || 'Guest' }}</strong>
                            <br>
                            <small class="text-muted">
                              <i class="fas fa-clock me-1"></i>
                              {{ formatDateTime(comment.created_at) }}
                            </small>
                          </div>
                        </div>
                        <div class="comment-content bg-light p-3 rounded">
                          <p class="mb-0">{{ comment.isi_komentator || comment.content || comment.comment }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Aksi (pindah ke bawah komentar) -->
            <div class="card card-modern mt-3">
              <div class="card-header bg-light">
                <h5 class="mb-0 fw-semibold">
                  <i class="fas fa-cogs me-2 text-warning"></i>Aksi
                </h5>
              </div>
              <div class="card-body">
                <div class="d-grid gap-2">
                  <router-link 
                    :to="{ name: 'penulis.posts.edit', params: { id: post.id } }" 
                    class="btn btn-warning"
                  >
                    <i class="fas fa-edit me-2"></i>Edit Post
                  </router-link>
                  <button @click="deletePost(post.id)" class="btn btn-danger">
                    <i class="fas fa-trash me-2"></i>Hapus Post
                  </button>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-5">
        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
        <h4>Post Tidak Ditemukan</h4>
        <p class="text-muted">Post yang Anda cari tidak dapat ditemukan.</p>
        <router-link :to="{ name: 'penulis.posts.index' }" class="btn btn-primary">
          <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Post
        </router-link>
      </div>
    </div>
  </PenulisLayout>
</template>


<script>
import { computed, onMounted, reactive, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { usePostsStore } from '@/stores/posts'
import { useAuthStore } from '@/stores/auth'
import axios from '@/lib/axios'
import PenulisLayout from '@/layouts/PenulisLayout.vue'


export default {
  name: 'PenulisPostsIndex',
  components: { PenulisLayout },
  setup() {
    const route = useRoute()
  const postsStore = usePostsStore()
  const authStore = useAuthStore()
  const router = useRouter()
    
    const loading = ref(false)
    const error = ref(null)
    const post = ref(null)
    const comments = ref([])
    const isSubmittingComment = ref(false)
    const newComment = reactive({
      content: ''
    })

    const isAuthenticated = computed(() => authStore.isAuthenticated)

    const getImageUrl = (imagePath) => {
      if (!imagePath) return null
      if (imagePath.startsWith('http')) return imagePath
      return `/storage/${imagePath}`
    }

    const getInitials = (name) => {
      if (!name) return 'A'
      return name.split(' ').map(word => word.charAt(0)).join('').toUpperCase().slice(0, 2)
    }


    const formatDate = (dateString) => {
      const date = new Date(dateString)
      const options = { year: 'numeric', month: 'short', day: 'numeric' }
      return date.toLocaleDateString('id-ID', options)
    }

    // Tambahkan formatDateTime agar tidak error di template
    const formatDateTime = (dateString) => {
      if (!dateString) return '-';
      const date = new Date(dateString)
      const options = {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      }
      return date.toLocaleDateString('id-ID', options) + ' ' + date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
    }

    const formatContent = (content) => {
      if (!content) return ''
      // Convert line breaks to <br> and handle basic formatting
      return content
        .replace(/\n/g, '<br>')
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\*(.*?)\*/g, '<em>$1</em>')
    }

    const fetchPost = async () => {
      loading.value = true
      error.value = null
      try {
        const response = await axios.get(`/api/posts/${route.params.id}`)
        console.log('API response:', response.data)
        post.value = response.data.data
        if (!post.value || !post.value.id) {
          error.value = 'Post tidak ditemukan'
        }
      } catch (err) {
        error.value = 'Post tidak ditemukan'
        console.error('Error fetching post:', err)
      } finally {
        loading.value = false
      }
    }

    const fetchComments = async () => {
      try {
        const response = await axios.get(`/api/posts/${route.params.id}/comments`)
        // response.data = { success: true, data: [...] }
        comments.value = Array.isArray(response.data.data)
          ? response.data.data.filter(comment => comment.approved)
          : []
      } catch (err) {
        console.error('Error fetching comments:', err)
      }
    }

    const submitComment = async () => {
      if (!newComment.content.trim()) return

      isSubmittingComment.value = true
      try {
        const response = await axios.post(`/api/posts/${route.params.id}/comments`, {
          content: newComment.content
        }, {
          headers: {
            Authorization: `Bearer ${authStore.token}`
          }
        })
        
        // Add the new comment to the list if it's approved, or show message
        if (response.data.approved) {
          comments.value.unshift(response.data)
        } else {
          alert('Komentar Anda telah dikirim dan sedang menunggu persetujuan admin.')
        }
        
        newComment.content = ''
      } catch (error) {
        console.error('Failed to submit comment:', error)
        alert('Gagal mengirim komentar. Silakan coba lagi.')
      } finally {
        isSubmittingComment.value = false
      }
    }
    // Fungsi hapus post dengan konfirmasi sederhana
    const deletePost = async (id) => {
      if (!confirm('Yakin ingin menghapus post ini?')) return
      try {
        await postsStore.deletePost(id)
        // Redirect ke daftar post setelah hapus (SPA)
        router.push({ name: 'penulis.posts.index' })
      } catch (e) {
        alert('Gagal menghapus post.')
      }
    }

    // Fungsi untuk mendapatkan URL publik post
    const getPostUrl = (id) => {
      return `/posts/${id}`;
    }
    onMounted(async () => {
      await fetchPost()
      await fetchComments()
    })

    return {
      loading,
      error,
      post,
      comments,
      isAuthenticated,
      isSubmittingComment,
      newComment,
      getImageUrl,
      getInitials,
      formatDate,
      formatDateTime,
      formatContent,
      submitComment,
  deletePost,
      getPostUrl
    }
  }
}
</script>

<style scoped>
.gradient-bg {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.prose {
  line-height: 1.8;
}

.prose p {
  margin-bottom: 1.5rem;
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
  border-left: 4px solid #3b82f6;
  padding-left: 1rem;
  margin: 2rem 0;
  font-style: italic;
  color: #6b7280;
}

.prose ul, .prose ol {
  margin: 1.5rem 0;
  padding-left: 2rem;
}

.prose li {
  margin-bottom: 0.5rem;
}
</style>