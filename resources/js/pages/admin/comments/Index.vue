<template>
  <AdminLayout>
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card card-modern">
            <div class="card-body">
              <!-- Header -->
              <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 fw-bold text-dark">
                  <i class="fas fa-comments me-2 text-primary"></i>Kelola Komentar
                </h4>
                <button 
                  @click="fetchComments" 
                  class="btn btn-admin-primary" 
                  :disabled="loading"
                >
                  <i 
                    class="fas fa-sync-alt me-2" 
                    :class="{'fa-spin': loading}"
                  ></i>Refresh
                </button>
              </div>

              <!-- Loading State -->
              <div v-if="loading" class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
                <div class="mt-2 text-muted">Memuat komentar...</div>
              </div>

              <!-- Error State -->
              <div 
                v-if="error" 
                class="alert alert-danger alert-dismissible fade show" 
                role="alert"
              >
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ error }}
                <button 
                  @click="fetchComments" 
                  class="btn btn-sm btn-outline-danger ms-2"
                >
                  <i class="fas fa-redo"></i> Coba Lagi
                </button>
              </div>

              <!-- Comments Content -->
              <div v-if="!loading && !error">
                <!-- Empty State -->
                <div v-if="comments.length === 0" class="text-center py-4 text-muted">
                  <i class="fas fa-comments fa-3x mb-3 text-secondary opacity-50"></i>
                  <br>Tidak ada komentar yang menunggu persetujuan.
                </div>

                <!-- Comments Table -->
                <div v-else class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Komentator</th>
                        <th>Komentar</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="comment in comments" :key="comment.id">
                        <td class="fw-bold text-muted">#{{ comment.id }}</td>
                        <td>
                          <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center me-3">
                              <i class="fas fa-user text-white"></i>
                            </div>
                            <span class="fw-semibold">{{ comment.nama_komentator || 'Guest' }}</span>
                          </div>
                        </td>
                        <td class="text-muted">
                          <span v-if="comment.isi_komentar.length <= 80">
                            {{ comment.isi_komentar }}
                          </span>
                          <span v-else>
                            {{ comment.isi_komentar.substring(0, 80) }}...
                            <router-link
                              :to="{ name: 'admin.posts.show', params: { id: comment.post_id } }"
                              class="btn btn-link btn-sm p-0"
                              title="Lihat artikel"
                            >
                              Baca selengkapnya...
                            </router-link>
                          </span>
                        </td>
                        <td>
                          <span v-if="!comment.approved" class="badge bg-warning text-dark">
                            <i class="fas fa-clock me-1"></i>
                            Pending
                          </span>
                          <span v-else class="badge bg-success text-white">
                            <i class="fas fa-check me-1"></i>
                            Approved
                          </span>
                        </td>
                        <td class="text-muted">
                          <small>{{ formatDate(comment.created_at) }}</small>
                        </td>
                        <td>
                          <div class="btn-group btn-group-sm">
                            <button 
                              @click="approveComment(comment.id)" 
                              :disabled="processing"
                              class="btn btn-outline-success btn-sm"
                              title="Setujui komentar"
                            >
                              <i class="fas fa-check"></i>
                            </button>
                            
                            <button 
                              @click="rejectComment(comment.id)" 
                              :disabled="processing"
                              class="btn btn-outline-danger btn-sm"
                              title="Tolak dan hapus komentar"
                            >
                              <i class="fas fa-trash"></i>
                            </button>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Comment Detail Modal -->
    <div class="modal fade" id="commentModal" tabindex="-1" ref="commentModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              Komentar dari {{ selectedComment?.nama_komentator }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p>{{ selectedComment?.isi_komentar }}</p>
            <hr>
            <small class="text-muted">
              <i class="fas fa-calendar me-1"></i>
              {{ selectedComment ? formatDate(selectedComment.created_at) : '' }}
            </small>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              Tutup
            </button>
            <button 
              @click="approveComment(selectedComment?.id)" 
              :disabled="processing"
              class="btn btn-success" 
              data-bs-dismiss="modal"
            >
              <i class="fas fa-check me-1"></i>
              Setujui
            </button>
            <button 
              @click="rejectComment(selectedComment?.id)" 
              :disabled="processing"
              class="btn btn-danger" 
              data-bs-dismiss="modal"
            >
              <i class="fas fa-trash me-1"></i>
              Tolak & Hapus
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import { ref, onMounted } from 'vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import axios from '@/lib/axios'

export default {
  name: 'AdminCommentsIndex',
  components: {
    AdminLayout
  },
  setup() {
    const loading = ref(true)
    const processing = ref(false)
    const error = ref('')
    const comments = ref([])
    const selectedComment = ref(null)
    const commentModal = ref(null)
    
    // Methods
    const fetchComments = async () => {
      try {
        loading.value = true
        error.value = ''
        console.log('Fetching all comments...')
        const response = await axios.get('/api/admin/comments')
        console.log('Comments response:', response.data)
        // Only show pending comments in moderation table
        const allComments = response.data.data || response.data.items || response.data || [];
        comments.value = allComments.filter(comment => !comment.approved);
        console.log('Pending comments loaded:', comments.value.length)
      } catch (err) {
        console.error('Error fetching comments:', err)
        console.error('Error details:', err.response?.data || err.message)
        error.value = 'Gagal memuat komentar. Silakan coba lagi.'
      } finally {
        loading.value = false
      }
    }
    
    const approveComment = async (commentId) => {
      if (processing.value || !commentId) return
      
      try {
        processing.value = true
        
        await axios.patch(`/api/comments/${commentId}/approve`)
        
        // Remove comment from list
        comments.value = comments.value.filter(comment => comment.id !== commentId)
        
        // Show success message (you can implement toast notification here)
        console.log('Komentar berhasil disetujui')
        
      } catch (err) {
        console.error('Error approving comment:', err)
        error.value = 'Gagal menyetujui komentar. Silakan coba lagi.'
      } finally {
        processing.value = false
      }
    }
    
    const rejectComment = async (commentId) => {
      if (processing.value || !commentId) return
      
      if (!confirm('Apakah Anda yakin ingin menolak komentar ini?')) {
        return
      }
      
      try {
        processing.value = true
        
        await axios.patch(`/api/comments/${commentId}/reject`)
        
        // Remove comment from list (rejected comments are typically removed from pending list)
        comments.value = comments.value.filter(comment => comment.id !== commentId)
        
        console.log('Komentar berhasil ditolak')
        
      } catch (err) {
        console.error('Error rejecting comment:', err)
        error.value = 'Gagal menolak komentar. Silakan coba lagi.'
      } finally {
        processing.value = false
      }
    }
    
    const showCommentModal = (comment) => {
      selectedComment.value = comment
      const modal = new bootstrap.Modal(commentModal.value)
      modal.show()
    }
    
    const formatDate = (dateString) => {
      const date = new Date(dateString)
      return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
    }
    
    // Lifecycle
    onMounted(() => {
      fetchComments()
    })
    
    return {
      loading,
      processing,
      error,
      comments,
      selectedComment,
      commentModal,
      fetchComments,
      approveComment,
      rejectComment,
      showCommentModal,
      formatDate
    }
  }
}
</script>

<style scoped>
.avatar-sm {
  width: 2.25rem;
  height: 2.25rem;
  font-size: 0.875rem;
}

.card-modern {
  border-radius: 0.75rem;
  border: none;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.btn-group-sm .btn {
  border-radius: 0.375rem;
  margin-right: 0.5rem;
  padding: 0.25rem 0.5rem;
  font-size: 0.75rem;
}

.btn-group-sm .btn:last-child {
  margin-right: 0;
}

.table th {
  border-top: none;
  font-weight: 600;
  color: #374151;
  background-color: #f9fafb;
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  border-bottom: 2px solid #e9ecef;
}

.table td {
  vertical-align: middle;
  border-color: #e5e7eb;
  padding: 1rem 0.75rem;
}

.table-hover tbody tr:hover {
  background-color: rgba(0, 123, 255, 0.025);
}

.badge {
  font-size: 0.75rem;
  font-weight: 500;
}

.btn-admin-primary {
  background: linear-gradient(135deg,  #0a58ca 100%);
  border: none;
  color: white;
  font-weight: 500;
  padding: 0.5rem 1.5rem;
  border-radius: 0.5rem;
  transition: all 0.3s ease;
}

.btn-admin-primary:hover {
  transform: translateY(-1px);
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
  color: white;
}

.btn-admin-primary:disabled {
  opacity: 0.65;
  transform: none;
  box-shadow: none;
}

.alert {
  border-radius: 0.5rem;
  border: none;
}

.alert-danger {
  background-color: rgba(220, 53, 69, 0.1);
  color: #842029;
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

.btn-link {
  text-decoration: none;
  color: #0d6efd;
}

.btn-link:hover {
  text-decoration: underline;
  color: #0a58ca;
}
</style>