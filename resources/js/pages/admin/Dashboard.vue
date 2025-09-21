<template>
  <AdminLayout page-title="Dashboard">
    <!-- Welcome Section -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card card-modern border-0 shadow-sm" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-left: 4px solid #2563eb !important;">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h2 class="fw-bold mb-2 text-primary">Selamat Datang, {{ authStore.user?.name }}!</h2>
                <p class="mb-0 text-muted">Tulis dan kelola artikel Anda dengan mudah di Penulis BlogSpace</p>
              </div>
              <div class="col-md-4 text-end">
                <i class="fas fa-chart-line text-primary" style="font-size: 3rem; opacity: 0.7;"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
      <div class="col-md-6 mb-3">
        <div class="card card-modern border-0 shadow-sm">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-8">
                <h6 class="text-muted mb-1">Total Posts</h6>
                <h2 class="fw-bold text-primary mb-0">{{ stats.totalPosts }}</h2>
                <small class="text-success">
                  <i class="fas fa-arrow-up me-1"></i>Artikel tersedia
                </small>
              </div>
              <div class="col-4 text-end">
                <div class="icon-circle bg-primary bg-opacity-10">
                  <i class="fas fa-newspaper text-primary"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-md-6 mb-3">
        <div class="card card-modern border-0 shadow-sm">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-8">
                <h6 class="text-muted mb-1">Total Komentar</h6>
                <h2 class="fw-bold text-success mb-0">{{ stats.totalComments }}</h2>
                <small class="text-info">
                  <i class="fas fa-comments me-1"></i>Engagement aktif
                </small>
              </div>
              <div class="col-4 text-end">
                <div class="icon-circle bg-success bg-opacity-10">
                  <i class="fas fa-comments text-success"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card card-modern">
          <div class="card-header bg-transparent border-bottom">
            <h5 class="mb-0 fw-bold">Quick Actions</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-3 mb-3">
                <router-link :to="{ name: 'admin.posts.create' }" class="btn btn-admin-primary w-100 py-3">
                  <i class="fas fa-plus-circle fa-2x mb-2"></i>
                  <div>Buat Post Baru</div>
                </router-link>
              </div>
              <div class="col-md-3 mb-3">
                <router-link :to="{ name: 'admin.posts.index' }" class="btn btn-outline-primary w-100 py-3">
                  <i class="fas fa-list fa-2x mb-2"></i>
                  <div>Kelola Posts</div>
                </router-link>
              </div>
              <div class="col-md-3 mb-3">
                <a href="/" class="btn btn-outline-success w-100 py-3" target="_blank">
                  <i class="fas fa-globe fa-2x mb-2"></i>
                  <div>Lihat Website</div>
                </a>
              </div>
              <div class="col-md-3 mb-3">
                <button @click="handleLogout" class="btn btn-outline-danger w-100 py-3">
                  <i class="fas fa-sign-out-alt fa-2x mb-2"></i>
                  <div>Logout</div>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Posts -->
    <div class="row">
      <div class="col-12">
        <div class="card card-modern">
          <div class="card-header bg-transparent border-bottom">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="mb-0 fw-bold">Posts Terbaru</h5>
              <router-link to="/admin/posts" class="btn btn-sm btn-outline-primary">
                Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
              </router-link>
            </div>
          </div>
          <div class="card-body">
            <div v-if="loading" class="text-center py-4">
              <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
              <p class="mt-2 text-muted">Loading...</p>
            </div>

            <div v-else-if="recentPosts.length > 0">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead class="table-light">
                    <tr>
                      <th>Judul</th>
                      <th>Tanggal Post</th>
                      <th>Komentar</th>
                      <th width="150">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="post in recentPosts" :key="post.id">
                      <td>
                        <strong>{{ truncateText(post.judul, 50) }}</strong>
                        <br>
                        <small class="text-muted">{{ truncateText(post.konten, 70) }}</small>
                      </td>
                      <td>
                        <span class="badge bg-primary">{{ formatDate(post.tanggal_post) }}</span>
                        <br>
                        <small class="text-muted">{{ formatTime(post.tanggal_post) }}</small>
                      </td>
                      <td>
                        <span class="badge bg-success">{{ post.comments_count || 0 }}</span>
                      </td>
                      <td>
                        <div class="btn-group btn-group-sm" role="group">
                          <router-link 
                            :to="{ name: 'admin.posts.show', params: { id: post.id } }" 
                            class="btn btn-outline-info me-2"
                          >
                            <i class="fas fa-eye"></i>
                          </router-link>
                          <router-link 
                            :to="{ name: 'admin.posts.edit', params: { id: post.id } }" 
                            class="btn btn-outline-warning"
                          >
                            <i class="fas fa-edit"></i>
                          </router-link>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div v-else class="text-center py-5">
              <i class="fas fa-newspaper text-muted mb-3" style="font-size: 4rem;"></i>
              <h5 class="fw-bold">Belum Ada Posts</h5>
              <p class="text-muted mb-4">Mulai dengan membuat post pertama Anda.</p>
              <router-link to="/admin/posts/create" class="btn btn-admin-primary">
                <i class="fas fa-plus me-2"></i>Buat Post Pertama
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import AdminLayout from '@/layouts/AdminLayout.vue'
import axios from '@/lib/axios'

export default {
  name: 'AdminDashboard',
  components: {
    AdminLayout
  },
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()
    
    const loading = ref(true)
    const stats = ref({
      totalPosts: 0,
      totalComments: 0
    })
    const recentPosts = ref([])

    const fetchDashboardData = async () => {
      loading.value = true
      try {
        const response = await axios.get('/api/admin/dashboard')
        
        stats.value = response.data.stats
        recentPosts.value = response.data.recentPosts
      } catch (error) {
        console.error('Failed to fetch dashboard data:', error)
        console.error('Error details:', error.response?.data || error.message)
        // Set some fallback data for testing
        stats.value = {
          totalPosts: 0,
          totalComments: 0
        }
        recentPosts.value = []
      } finally {
        loading.value = false
      }
    }

    const handleLogout = async () => {
      try {
        await authStore.logout()
        router.push('/login')
      } catch (error) {
        console.error('Logout failed:', error)
      }
    }

    const truncateText = (text, length) => {
      if (!text) return ''
      return text.length > length ? text.substring(0, length) + '...' : text
    }

    const formatDate = (dateString) => {
      const date = new Date(dateString)
      return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
      })
    }

    const formatTime = (dateString) => {
      const date = new Date(dateString)
      return date.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
      })
    }

    onMounted(() => {
      fetchDashboardData()
    })

    return {
      authStore,
      loading,
      stats,
      recentPosts,
      handleLogout,
      truncateText,
      formatDate,
      formatTime
    }
  }
}
</script>

<style scoped>
/* Additional custom styles specific to dashboard */
.text-primary {
  color: #2563eb !important;
}

.btn-admin-primary {
  background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
  border: none;
  color: white;
  border-radius: 10px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.btn-admin-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
  color: white;
}

.card-modern {
  border: none;
  border-radius: 15px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
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

.bg-opacity-10 {
  --bs-bg-opacity: 0.1;
}
</style>