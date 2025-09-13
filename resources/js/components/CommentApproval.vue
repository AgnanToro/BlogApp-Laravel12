<template>
  <div class="comment-approval">
    <div class="card card-modern">
      <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
          <h4 class="card-title mb-0 fw-bold text-dark">
            <i class="fas fa-comments me-2 text-primary"></i>Moderasi Komentar
          </h4>
          <div class="d-flex gap-2">
            <button 
              @click="bulkApprove" 
              :disabled="selectedComments.length === 0 || loading"
              class="btn btn-success btn-sm"
            >
              <i class="fas fa-check me-1"></i>
              Setujui Terpilih ({{ selectedComments.length }})
            </button>
            <button @click="refreshComments" :disabled="loading" class="btn btn-outline-primary btn-sm">
              <i class="fas fa-sync me-1" :class="{ 'fa-spin': loading }"></i> Refresh
            </button>
          </div>
        </div>
      </div>

      <div class="card-body">
        <!-- Loading State -->
        <div v-if="loading && comments.length === 0" class="text-center py-4">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-2 text-muted">Memuat komentar...</p>
        </div>

        <!-- Empty State -->
        <div v-else-if="comments.length === 0" class="text-center py-5">
          <i class="fas fa-comments text-muted mb-3" style="font-size: 4rem;"></i>
          <h5 class="text-muted">Tidak ada komentar yang perlu disetujui</h5>
          <p class="text-muted">Semua komentar sudah dimoderasi</p>
        </div>

        <!-- Comments List -->
        <div v-else>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead class="table-light">
                <tr>
                  <th width="40">
                    <input 
                      type="checkbox" 
                      v-model="selectAll" 
                      @change="toggleSelectAll"
                      class="form-check-input"
                    >
                  </th>
                  <th>Komentar</th>
                  <th width="200">Post</th>
                  <th width="150">Tanggal</th>
                  <th width="120" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="comment in comments" :key="comment.id">
                  <td>
                    <input 
                      type="checkbox" 
                      :value="comment.id" 
                      v-model="selectedComments"
                      class="form-check-input"
                    >
                  </td>
                  <td>
                    <div class="comment-content">
                      <strong class="d-block">{{ comment.nama_komentator }}</strong>
                      <p class="mb-1 text-muted small">{{ comment.isi_komentar }}</p>
                    </div>
                  </td>
                  <td>
                    <span class="badge bg-info">{{ comment.post?.judul || 'Post Dihapus' }}</span>
                  </td>
                  <td>
                    <small class="text-muted">{{ formatDate(comment.created_at) }}</small>
                  </td>
                  <td class="text-center">
                    <div class="btn-group btn-group-sm">
                      <button 
                        @click="approveComment(comment)" 
                        :disabled="loading"
                        class="btn btn-success"
                        title="Setujui"
                      >
                        <i class="fas fa-check"></i>
                      </button>
                      <button 
                        @click="rejectComment(comment)" 
                        :disabled="loading"
                        class="btn btn-danger"
                        title="Tolak & Hapus"
                      >
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <nav v-if="pagination.last_page > 1" class="mt-4">
            <ul class="pagination justify-content-center">
              <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
                <button @click="loadComments(pagination.current_page - 1)" class="page-link">Previous</button>
              </li>
              <li 
                v-for="page in visiblePages" 
                :key="page" 
                class="page-item" 
                :class="{ active: page === pagination.current_page }"
              >
                <button @click="loadComments(page)" class="page-link">{{ page }}</button>
              </li>
              <li class="page-item" :class="{ disabled: pagination.current_page === pagination.last_page }">
                <button @click="loadComments(pagination.current_page + 1)" class="page-link">Next</button>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>

    <!-- Success/Error Toast -->
    <div 
      v-if="toast.show" 
      class="toast-container position-fixed top-0 end-0 p-3"
      style="z-index: 1055;"
    >
      <div class="toast show" :class="toast.type === 'success' ? 'bg-success' : 'bg-danger'">
        <div class="toast-body text-white">
          <i :class="toast.type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'" class="me-2"></i>
          {{ toast.message }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CommentApproval',
  data() {
    return {
      comments: [],
      loading: false,
      selectedComments: [],
      selectAll: false,
      pagination: {
        current_page: 1,
        last_page: 1,
        total: 0
      },
      toast: {
        show: false,
        type: 'success',
        message: ''
      }
    }
  },
  computed: {
    visiblePages() {
      const current = this.pagination.current_page;
      const last = this.pagination.last_page;
      const pages = [];
      
      for (let i = Math.max(1, current - 2); i <= Math.min(last, current + 2); i++) {
        pages.push(i);
      }
      
      return pages;
    }
  },
  mounted() {
    console.log('Vue component mounted, loading comments...');
    this.loadComments();
  },
  methods: {
    async loadComments(page = 1) {
      this.loading = true;
      console.log('Loading comments from API...');
      
      try {
        const response = await fetch(`/api/comments/pending?page=${page}`, {
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          credentials: 'same-origin'
        });
        
        console.log('API Response status:', response.status);
        const data = await response.json();
        console.log('API Response data:', data);
        
        if (response.ok) {
          this.comments = data.data || data;
          this.pagination = {
            current_page: data.current_page || 1,
            last_page: data.last_page || 1,
            total: data.total || 0
          };
          console.log('Comments loaded:', this.comments);
        } else {
          console.error('API Error:', data);
          this.showToast('error', 'Gagal memuat komentar: ' + (data.message || 'Unknown error'));
        }
      } catch (error) {
        console.error('Fetch error:', error);
        this.showToast('error', 'Terjadi kesalahan saat memuat data: ' + error.message);
      } finally {
        this.loading = false;
      }
    },

    async approveComment(comment) {
      this.loading = true;
      try {
        const response = await fetch(`/api/comments/${comment.id}/approve`, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          credentials: 'same-origin'
        });
        
        const data = await response.json();
        
        if (response.ok) {
          this.comments = this.comments.filter(c => c.id !== comment.id);
          this.showToast('success', data.message);
        } else {
          this.showToast('error', data.message || 'Gagal menyetujui komentar');
        }
      } catch (error) {
        this.showToast('error', 'Terjadi kesalahan saat menyetujui komentar');
      } finally {
        this.loading = false;
      }
    },

    async rejectComment(comment) {
      if (!confirm('Yakin ingin menolak dan menghapus komentar ini?')) return;
      
      this.loading = true;
      try {
        const response = await fetch(`/api/comments/${comment.id}/reject`, {
          method: 'DELETE',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        });
        
        const data = await response.json();
        
        if (response.ok) {
          this.comments = this.comments.filter(c => c.id !== comment.id);
          this.showToast('success', data.message);
        } else {
          this.showToast('error', data.message || 'Gagal menolak komentar');
        }
      } catch (error) {
        this.showToast('error', 'Terjadi kesalahan saat menolak komentar');
      } finally {
        this.loading = false;
      }
    },

    async bulkApprove() {
      if (this.selectedComments.length === 0) return;
      
      this.loading = true;
      try {
        const response = await fetch('/api/comments/bulk-approve', {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          credentials: 'same-origin',
          body: JSON.stringify({
            comment_ids: this.selectedComments
          })
        });
        
        const data = await response.json();
        
        if (response.ok) {
          this.comments = this.comments.filter(c => !this.selectedComments.includes(c.id));
          this.selectedComments = [];
          this.selectAll = false;
          this.showToast('success', data.message);
        } else {
          this.showToast('error', data.message || 'Gagal menyetujui komentar');
        }
      } catch (error) {
        this.showToast('error', 'Terjadi kesalahan saat menyetujui komentar');
      } finally {
        this.loading = false;
      }
    },

    toggleSelectAll() {
      if (this.selectAll) {
        this.selectedComments = this.comments.map(c => c.id);
      } else {
        this.selectedComments = [];
      }
    },

    refreshComments() {
      this.loadComments(this.pagination.current_page);
    },

    formatDate(dateString) {
      return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },

    showToast(type, message) {
      this.toast = { show: true, type, message };
      setTimeout(() => {
        this.toast.show = false;
      }, 3000);
    },


  }
}
</script>

<style scoped>
.comment-content {
  max-width: 300px;
}

.comment-content p {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.toast-container .toast {
  min-width: 300px;
}
</style>
