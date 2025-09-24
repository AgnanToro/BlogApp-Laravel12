<template>
  <AdminLayout>
    <!-- Header with title and action -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="text-primary fw-bold mb-1">Kelola User</h4>
        <p class="text-muted small mb-0">Mengelola user yang terdaftar dalam sistem</p>
      </div>
      <router-link 
        :to="{ name: 'admin.users.create' }" 
        class="btn btn-admin-primary"
      >
        <i class="fas fa-plus me-2"></i>Tambah User
      </router-link>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
      <div class="col-md-3">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="bg-primary bg-gradient rounded-3 p-3 me-3">
                <i class="fas fa-users text-white fa-lg"></i>
              </div>
              <div>
                <h5 class="card-title mb-1">{{ stats.total || 0 }}</h5>
                <p class="card-text text-muted small mb-0">Total User</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="bg-danger bg-gradient rounded-3 p-3 me-3">
                <i class="fas fa-user-shield text-white fa-lg"></i>
              </div>
              <div>
                <h5 class="card-title mb-1">{{ stats.admin || 0 }}</h5>
                <p class="card-text text-muted small mb-0">Admin</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="bg-info bg-gradient rounded-3 p-3 me-3">
                <i class="fas fa-user-edit text-white fa-lg"></i>
              </div>
              <div>
                <h5 class="card-title mb-1">{{ stats.penulis || 0 }}</h5>
                <p class="card-text text-muted small mb-0">Penulis</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="bg-success bg-gradient rounded-3 p-3 me-3">
                <i class="fas fa-user-plus text-white fa-lg"></i>
              </div>
              <div>
                <h5 class="card-title mb-1">{{ stats.today || 0 }}</h5>
                <p class="card-text text-muted small mb-0">Hari Ini</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="row">
      <div class="col-12">
        <div class="card shadow-sm border-0 card-modern">
          <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-semibold">
              <i class="fas fa-list text-primary me-2"></i>Daftar User
            </h6>
          </div>
          <div class="card-body p-0">
            <!-- Loading State -->
            <div v-if="loading" class="text-center py-5">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
              <p class="text-muted mt-2">Memuat data user...</p>
            </div>

            <!-- Users Table -->
            <div v-else class="table-responsive">
              <table class="table table-hover mb-0">
                <thead class="table-light">
                  <tr>
                    <th class="ps-4">ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Terdaftar</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="user in users" :key="user.id" class="border-bottom">
                    <td class="ps-4 text-muted">
                      <small>#{{ user.id }}</small>
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center me-3">
                          <i class="fas fa-user text-white"></i>
                        </div>
                        <span class="fw-semibold">{{ user.name }}</span>
                      </div>
                    </td>
                    <td class="text-muted">{{ user.email }}</td>
                    <td>
                      <span 
                        class="badge"
                        :class="user.role === 'admin' ? 'bg-danger' : 'bg-info'"
                      >
                        <i 
                          class="fas me-1"
                          :class="user.role === 'admin' ? 'fa-user-shield' : 'fa-user-edit'"
                        ></i>
                        {{ user.role.charAt(0).toUpperCase() + user.role.slice(1) }}
                      </span>
                    </td>
                    <td class="text-muted">
                      <small>{{ formatDate(user.created_at) }}</small>
                    </td>
                    <td>
                      <div class="btn-group btn-group-sm">
                        <router-link 
                          :to="{ name: 'admin.users.edit', params: { id: user.id } }"
                          class="btn btn-outline-primary btn-sm"
                        >
                          <i class="fas fa-edit"></i>
                        </router-link>
                        <button 
                          v-if="user.id !== currentUser?.id"
                          type="button" 
                          class="btn btn-outline-danger btn-sm"
                          @click="confirmDelete(user)"
                        >
                          <i class="fas fa-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                  
                  <!-- Empty State -->
                  <tr v-if="!loading && users.length === 0">
                    <td colspan="6" class="text-center py-4 text-muted">
                      <i class="fas fa-users fa-3x mb-3 text-secondary opacity-50"></i>
                      <br>Tidak ada user yang ditemukan.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div v-if="pagination && pagination.last_page > 1" class="d-flex justify-content-center mt-4 pb-3">
              <nav>
                <ul class="pagination">
                  <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
                    <button 
                      class="page-link" 
                      @click="changePage(pagination.current_page - 1)"
                      :disabled="pagination.current_page === 1"
                    >
                      <i class="fas fa-chevron-left"></i>
                    </button>
                  </li>
                  
                  <li 
                    v-for="page in paginationPages" 
                    :key="page"
                    class="page-item"
                    :class="{ active: page === pagination.current_page }"
                  >
                    <button 
                      class="page-link" 
                      @click="changePage(page)"
                      v-if="page !== '...'"
                    >
                      {{ page }}
                    </button>
                    <span v-else class="page-link">...</span>
                  </li>
                  
                  <li class="page-item" :class="{ disabled: pagination.current_page === pagination.last_page }">
                    <button 
                      class="page-link" 
                      @click="changePage(pagination.current_page + 1)"
                      :disabled="pagination.current_page === pagination.last_page"
                    >
                      <i class="fas fa-chevron-right"></i>
                    </button>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" ref="deleteModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Konfirmasi Hapus User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p>Apakah Anda yakin ingin menghapus user <strong>{{ selectedUser?.name }}</strong>?</p>
            <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button 
              type="button" 
              class="btn btn-danger" 
              @click="deleteUser"
              :disabled="deleting"
            >
              <span v-if="deleting" class="spinner-border spinner-border-sm me-2"></span>
              {{ deleting ? 'Menghapus...' : 'Hapus User' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import AdminLayout from '@/layouts/AdminLayout.vue'
import axios from '@/lib/axios'

export default {
  name: 'AdminUsersIndex',
  components: {
    AdminLayout
  },
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()
    
    const loading = ref(true)
    const deleting = ref(false)
    const users = ref([])
    const stats = ref({})
    const pagination = ref(null)
    const selectedUser = ref(null)
    const deleteModal = ref(null)
    
    // Computed
    const currentUser = computed(() => authStore.user)
    
    const paginationPages = computed(() => {
      if (!pagination.value) return []
      
      const current = pagination.value.current_page
      const last = pagination.value.last_page
      const pages = []
      
      if (last <= 7) {
        for (let i = 1; i <= last; i++) {
          pages.push(i)
        }
      } else {
        if (current <= 4) {
          for (let i = 1; i <= 5; i++) {
            pages.push(i)
          }
          pages.push('...')
          pages.push(last)
        } else if (current >= last - 3) {
          pages.push(1)
          pages.push('...')
          for (let i = last - 4; i <= last; i++) {
            pages.push(i)
          }
        } else {
          pages.push(1)
          pages.push('...')
          for (let i = current - 1; i <= current + 1; i++) {
            pages.push(i)
          }
          pages.push('...')
          pages.push(last)
        }
      }
      
      return pages
    })
    
    // Methods
    const fetchUsers = async (page = 1) => {
      try {
        loading.value = true
        const response = await axios.get(`/api/admin/users?page=${page}`)
        users.value = response.data.data
        pagination.value = response.data.meta || response.data
        stats.value = response.data.stats || {}
      } catch (error) {
        console.error('Error fetching users:', error)
      } finally {
        loading.value = false
      }
    }
    
    const changePage = (page) => {
      if (page >= 1 && page <= pagination.value.last_page) {
        fetchUsers(page)
      }
    }
    
    const confirmDelete = (user) => {
      selectedUser.value = user
      const modal = new bootstrap.Modal(deleteModal.value)
      modal.show()
    }
    
    const deleteUser = async () => {
      if (!selectedUser.value) return
      
      try {
        deleting.value = true
        await axios.delete(`/api/admin/users/${selectedUser.value.id}`)
        
        // Remove user from list
        users.value = users.value.filter(user => user.id !== selectedUser.value.id)
        
        // Update stats
        stats.value.total = (stats.value.total || 1) - 1
        if (selectedUser.value.role === 'admin') {
          stats.value.admin = (stats.value.admin || 1) - 1
        } else {
          stats.value.penulis = (stats.value.penulis || 1) - 1
        }
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(deleteModal.value)
        modal.hide()
        
        selectedUser.value = null
        
        // Show success message (you can implement toast notification here)
        console.log('User berhasil dihapus')
        
      } catch (error) {
        console.error('Error deleting user:', error)
        // Show error message (you can implement toast notification here)
      } finally {
        deleting.value = false
      }
    }
    
    const formatDate = (dateString) => {
      const date = new Date(dateString)
      return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
      })
    }
    
    // Lifecycle
    onMounted(() => {
      fetchUsers()
    })
    
    return {
      loading,
      deleting,
      users,
      stats,
      pagination,
      selectedUser,
      deleteModal,
      currentUser,
      paginationPages,
      fetchUsers,
      changePage,
      confirmDelete,
      deleteUser,
      formatDate
    }
  }
}
</script>

<style scoped>
.btn-admin-primary {
  background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
  border: none;
  color: white;
  font-weight: 600;
  padding: 0.5rem 1.5rem;
  border-radius: 8px;
  transition: all 0.3s ease;
  box-shadow: 0 5px 15px rgba(37, 99, 235, 0.08);
}
.btn-admin-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
  color: white;
}
.avatar-sm {
  width: 2.5rem;
  height: 2.5rem;
  font-size: 0.875rem;
}

.card-modern {
  border-radius: 0.75rem;
}

.table th {
  font-weight: 600;
  color: #6c757d;
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  border-bottom: 2px solid #e9ecef;
}

.table td {
  vertical-align: middle;
  padding: 1rem 0.75rem;
}

.table-hover tbody tr:hover {
  background-color: rgba(0, 123, 255, 0.025);
}

.badge {
  font-size: 0.75rem;
  font-weight: 500;
}

.btn-group-sm .btn {
  padding: 0.25rem 0.5rem;
  font-size: 0.75rem;
}

.pagination .page-link {
  color: #6c757d;
  border: 1px solid #dee2e6;
  padding: 0.5rem 0.75rem;
}

.pagination .page-item.active .page-link {
  background-color: #0d6efd;
  border-color: #0d6efd;
  color: white;
}

.pagination .page-link:hover {
  color: #0d6efd;
  background-color: rgba(0, 123, 255, 0.1);
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
</style>