<template>
  <AdminLayout>
    <div class="container-fluid">
      <!-- Loading State -->
      <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <p class="text-muted mt-2">Memuat post...</p>
      </div>

      <!-- Content -->
      <div v-else-if="post">
        <!-- Header -->
        <div class="row">
          <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h1 class="fw-bold text-dark">{{ post.judul }}</h1>
              <div>
                <router-link 
                  :to="{ name: 'admin.posts.edit', params: { id: post.id } }" 
                  class="btn btn-warning me-2"
                >
                  <i class="fas fa-edit me-1"></i>Edit
                </router-link>
                <router-link 
                  :to="{ name: 'admin.posts.index' }" 
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
                <div v-if="post.image" class="mb-4">
                  <img 
                    :src="getImageUrl(post.image)" 
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
            <!-- Post Information -->
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
            
            <!-- Comments Section -->
            <div class="card card-modern mt-3">
              <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                  <i class="fas fa-comments me-2 text-success"></i>Komentar
                </h5>
                <span class="badge bg-info">{{ comments.length }} komentar</span>
              </div>
              <div class="card-body">
                <!-- Jika tidak ada komentar -->
                <div v-if="comments.length === 0" class="text-center py-4 text-muted">
                  <i class="fas fa-comment-slash fa-2x mb-2"></i>
                  <p class="mb-0">Belum ada komentar untuk post ini</p>
                </div>
                
                <!-- Daftar Komentar -->
                <div v-else>
                  <div v-for="comment in comments" :key="comment.id" class="comment-item border-bottom pb-3 mb-3">
                    <div class="d-flex align-items-start">
                      <!-- Avatar -->
                      <div class="avatar-sm bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0">
                        <i class="fas fa-user text-white"></i>
                      </div>
                      
                      <!-- Content -->
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
                          
                          <!-- Delete Button -->
                          <button 
                            @click="deleteComment(comment.id)" 
                            class="btn btn-sm btn-outline-danger"
                            title="Hapus Komentar"
                            :disabled="deleting"
                          >
                            <i class="fas fa-trash"></i>
                          </button>
                        </div>
                        
                        <!-- Comment Text -->
                        <div class="comment-content bg-light p-3 rounded">
                          <p class="mb-0">{{ comment.isi_komentar || comment.content || comment.comment }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Show more comments button if there are more -->
                <div v-if="post.comments_count > comments.length" class="text-center mt-3">
                  <button @click="loadMoreComments" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-chevron-down me-1"></i>
                    Lihat Lebih Banyak ({{ post.comments_count - comments.length }} lainnya)
                  </button>
                </div>
              </div>
            </div>

            <!-- Actions Card -->
            <div class="card card-modern mt-3">
              <div class="card-header bg-light">
                <h5 class="mb-0 fw-semibold">
                  <i class="fas fa-cogs me-2 text-warning"></i>Aksi
                </h5>
              </div>
              <div class="card-body">
                <div class="d-grid gap-2">
                  <router-link 
                    :to="{ name: 'admin.posts.edit', params: { id: post.id } }" 
                    class="btn btn-warning"
                  >
                    <i class="fas fa-edit me-2"></i>Edit Post
                  </router-link>
                  <button @click="confirmDelete" class="btn btn-danger">
                    <i class="fas fa-trash me-2"></i>Hapus Post
                  </button>
                  <a :href="getPostUrl(post.id)" target="_blank" class="btn btn-info">
                    <i class="fas fa-external-link-alt me-2"></i>Lihat di Website
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Error State -->
      <div v-else class="text-center py-5">
        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
        <h4>Post Tidak Ditemukan</h4>
        <p class="text-muted">Post yang Anda cari tidak dapat ditemukan.</p>
        <router-link :to="{ name: 'admin.posts.index' }" class="btn btn-primary">
          <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Post
        </router-link>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" ref="deleteModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Konfirmasi Hapus Post</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p>Apakah Anda yakin ingin menghapus post <strong>"{{ post?.judul }}"</strong>?</p>
            <p class="text-muted small">Tindakan ini tidak dapat dibatalkan dan akan menghapus semua komentar terkait.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button 
              type="button" 
              class="btn btn-danger" 
              @click="deletePost"
              :disabled="deleting"
            >
              <span v-if="deleting" class="spinner-border spinner-border-sm me-2"></span>
              <i v-else class="fas fa-trash me-2"></i>
              {{ deleting ? 'Menghapus...' : 'Hapus Post' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import AdminLayout from '@/layouts/AdminLayout.vue'
import axios from '@/lib/axios'

export default {
  name: 'AdminPostsShow',
  components: {
    AdminLayout
  },
  setup() {
    const router = useRouter()
    const route = useRoute()
    
    const loading = ref(true)
    const deleting = ref(false)
    const post = ref(null)
    const comments = ref([])
    const deleteModal = ref(null)
    
    // Methods
    const fetchPost = async () => {
      try {
        loading.value = true
        console.log('Fetching post with ID:', route.params.id)
        const response = await axios.get(`/api/admin/posts/${route.params.id}`)
        console.log('Post response:', response.data)
        post.value = response.data.data || response.data
        console.log('Post value set to:', post.value)
        
        // Fetch comments
        await fetchComments()
        
      } catch (error) {
        console.error('Error fetching post:', error)
        console.error('Error details:', error.response?.data || error.message)
        if (error.response?.status === 404) {
          post.value = null
        }
      } finally {
        loading.value = false
      }
    }
    
    const fetchComments = async () => {
      try {
        console.log('Fetching comments for post ID:', route.params.id)
        const response = await axios.get(`/api/posts/${route.params.id}/comments`)
        console.log('Comments response:', response.data)
        comments.value = response.data.data || response.data || []
        console.log('Comments loaded:', comments.value.length)
        
        // Update comments count in post object
        if (post.value) {
          post.value.comments_count = comments.value.length
        }
      } catch (error) {
        console.error('Error fetching comments:', error)
        comments.value = []
      }
    }
    
    const loadMoreComments = async () => {
      try {
        const response = await axios.get(`/api/posts/${route.params.id}/comments`)
        comments.value = response.data.data || response.data
      } catch (error) {
        console.error('Error loading more comments:', error)
      }
    }
    
    const deleteComment = async (commentId) => {
      if (!confirm('Apakah Anda yakin ingin menghapus komentar ini?')) {
        return
      }
      try {
        deleting.value = true
        console.log('Deleting comment ID:', commentId)
        // Call API to delete comment (admin route)
        await axios.delete(`/api/admin/comments/${commentId}`)
        // Remove comment from local array
        comments.value = comments.value.filter(comment => comment.id !== commentId)
        // Update comments count in post
        if (post.value && post.value.comments_count > 0) {
          post.value.comments_count--
        }
        console.log('Komentar berhasil dihapus')
        alert('Komentar berhasil dihapus!')
      } catch (error) {
        console.error('Error deleting comment:', error)
        console.error('Error details:', error.response?.data || error.message)
        alert('Gagal menghapus komentar. Silakan coba lagi.')
      } finally {
        deleting.value = false
      }
    }
    
    const confirmDelete = () => {
      const modal = new bootstrap.Modal(deleteModal.value)
      modal.show()
    }
    
    const deletePost = async () => {
      if (!post.value) return
      
      try {
        deleting.value = true
        await axios.delete(`/api/admin/posts/${post.value.id}`)
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(deleteModal.value)
        modal.hide()
        
        // Show success message (you can implement toast notification here)
        console.log('Post berhasil dihapus')
        
        // Redirect to posts index
        router.push({ name: 'admin.posts.index' })
        
      } catch (error) {
        console.error('Error deleting post:', error)
        // Show error message (you can implement toast notification here)
      } finally {
        deleting.value = false
      }
    }
    
    const formatDateTime = (dateString) => {
      const date = new Date(dateString)
      return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
    }
    
    const formatDate = (dateString) => {
      const date = new Date(dateString)
      return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
      })
    }
    
    const formatContent = (content) => {
      // Convert line breaks to <br> tags and preserve HTML
      return content.replace(/\n/g, '<br>')
    }
    
    const getImageUrl = (imagePath) => {
      if (imagePath.startsWith('http')) {
        return imagePath
      }
      return `/storage/${imagePath}`
    }
    
    const getPostUrl = (postId) => {
      return `/posts/${postId}`
    }
    
    // Lifecycle
    onMounted(() => {
      fetchPost()
    })
    
    return {
      loading,
      deleting,
      post,
      comments,
      deleteModal,
      fetchPost,
      loadMoreComments,
      deleteComment,
      confirmDelete,
      deletePost,
      formatDateTime,
      formatDate,
      formatContent,
      getImageUrl,
      getPostUrl
    }
  }
}
</script>

<style scoped>
.card-modern {
  border-radius: 0.75rem;
  border: none;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.avatar-sm {
  width: 2.5rem;
  height: 2.5rem;
  font-size: 0.875rem;
}

.card-header {
  border-bottom: 1px solid rgba(0, 0, 0, 0.125);
  border-radius: 0.75rem 0.75rem 0 0 !important;
}

.badge {
  font-size: 0.75rem;
  font-weight: 500;
}

.modal-content {
  border-radius: 0.75rem;
  border: none;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal-header {
  border-bottom: 1px solid #e9ecef;
  padding: 1.5rem;
}

.modal-body {
  padding: 1.5rem;
}

.modal-footer {
  border-top: 1px solid #e9ecef;
  padding: 1.5rem;
}

.last\:border-0:last-child {
  border-bottom: none !important;
}

.last\:pb-0:last-child {
  padding-bottom: 0 !important;
}

.last\:mb-0:last-child {
  margin-bottom: 0 !important;
}

.flex-shrink-0 {
  flex-shrink: 0;
}

.flex-grow-1 {
  flex-grow: 1;
}

/* Content styling */
.card-text {
  line-height: 1.7;
  color: #374151;
}

.card-text :deep(br) {
  margin-bottom: 0.5rem;
}

.card-text :deep(p) {
  margin-bottom: 1rem;
}

.card-text :deep(h1),
.card-text :deep(h2),
.card-text :deep(h3),
.card-text :deep(h4),
.card-text :deep(h5),
.card-text :deep(h6) {
  margin-top: 1.5rem;
  margin-bottom: 1rem;
  font-weight: 600;
}

.card-text :deep(ul),
.card-text :deep(ol) {
  margin-bottom: 1rem;
  padding-left: 1.5rem;
}

.card-text :deep(blockquote) {
  border-left: 4px solid #e5e7eb;
  padding-left: 1rem;
  margin: 1.5rem 0;
  font-style: italic;
  color: #6b7280;
}

/* Comment Styles */
.comment-item {
  transition: all 0.3s ease;
}

.comment-item:hover {
  background-color: #f8f9fa;
  border-radius: 0.5rem;
  padding: 1rem;
  margin: -0.5rem;
  margin-bottom: 0.5rem;
}

.comment-item:last-child {
  border-bottom: none !important;
}

.comment-content {
  border-left: 3px solid #007bff;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.avatar-sm {
  width: 40px;
  height: 40px;
  font-size: 14px;
}

.btn-outline-danger:hover {
  transform: scale(1.05);
  transition: all 0.2s ease;
}
</style>