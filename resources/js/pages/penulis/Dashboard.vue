<template>
  <PenulisLayout :pageTitle="'Dashboard'">

    <!-- Welcome Section -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card card-modern border-0 shadow-sm" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-left: 4px solid #2563eb !important;">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h2 class="fw-bold mb-2 text-primary">Selamat Datang, {{ authStore.user?.name }}!</h2>
                <p class="mb-0 text-muted">Kelola artikel dan konten Anda dengan mudah melalui dashboard penulis.</p>
              </div>
              <div class="col-md-4 text-end">
                <i class="fas fa-user-edit text-primary" style="font-size: 3rem; opacity: 0.7;"></i>
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
                <h2 class="fw-bold text-primary mb-0">{{ statistics.totalPosts }}</h2>
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
                <h2 class="fw-bold text-success mb-0">{{ statistics.totalComments }}</h2>
                <small class="text-info">
                  <i class="fas fa-comments me-1"></i>Komentar masuk
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
                <router-link :to="{ name: 'penulis.posts.create' }" class="btn btn-penulis-primary w-100 py-3">
                  <i class="fas fa-plus-circle fa-2x mb-2"></i>
                  <div>Buat Post Baru</div>
                </router-link>
              </div>
              <div class="col-md-3 mb-3">
                <router-link :to="{ name: 'penulis.posts.index' }" class="btn btn-outline-primary w-100 py-3">
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
                <button @click="authStore.logout()" class="btn btn-outline-danger w-100 py-3">
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
              <router-link :to="{ name: 'penulis.posts.index' }" class="btn btn-sm btn-outline-primary">
                Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
              </router-link>
            </div>
          </div>
          <div class="card-body">
            <template v-if="loading">
              <div class="text-center py-4">
                <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                <p class="mt-2 text-muted">Loading...</p>
              </div>
            </template>
            <template v-else>
              <template v-if="recentPosts.length > 0">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead class="table-light">
                      <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Tanggal Post</th>
                        <th>Jam</th>
                        <th>Komentar</th>
                        <th width="120">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="post in recentPosts" :key="post.id">
                        <td>{{ post.id }}</td>
                        <td>
                          <strong>{{ post.title || post.judul }}</strong>
                          <br>
                          <small class="text-muted">{{ truncateText(post.content || post.konten, 70) }}</small>
                        </td>
                        <td>{{ formatDate(post.created_at || post.tanggal_post) }}</td>
                        <td>{{ formatTime(post.created_at || post.tanggal_post) }}</td>
                        <td>{{ post.comments_count || 0 }}</td>
                        <td>
                          <div class="btn-group btn-group-sm" role="group">
                            <router-link 
                              :to="{ name: 'penulis.posts.show', params: { id: post.id } }" 
                              class="btn btn-outline-info me-2"
                            >
                              <i class="fas fa-eye"></i>
                            </router-link>
                            <router-link 
                              :to="{ name: 'penulis.posts.edit', params: { id: post.id } }" 
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
              </template>
              <template v-else>
                <div class="text-center py-5">
                  <i class="fas fa-newspaper text-muted mb-3" style="font-size: 4rem;"></i>
                  <h5 class="fw-bold">Belum Ada Posts</h5>
                  <p class="text-muted mb-4">Mulai dengan membuat post pertama Anda.</p>
                  <router-link :to="{ name: 'penulis.posts.create' }" class="btn btn-penulis-primary">
                    <i class="fas fa-plus me-2"></i>Buat Post Pertama
                  </router-link>
                </div>
              </template>
            </template>
          </div>
        </div>
      </div>
    </div>
  </PenulisLayout>
</template>

<script>
import { ref, onMounted, onActivated, computed } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { usePostsStore } from '@/stores/posts'
import PenulisLayout from '@/layouts/PenulisLayout.vue'

export default {
  name: 'PenulisDashboard',
  components: { PenulisLayout },
  setup() {
    const authStore = useAuthStore()
    const postsStore = usePostsStore()

    // Ambil data post penulis dari store
    const loading = computed(() => postsStore.isLoading)
    const posts = computed(() => postsStore.posts || [])

    // Statistik
    const statistics = computed(() => ({
      totalPosts: postsStore.pagination.total,
      totalComments: posts.value.reduce((sum, p) => sum + (p.comments_count || 0), 0)
    }))

    // Posts terbaru (5 post terakhir, urut terbaru)
    const recentPosts = computed(() =>
      [...posts.value].sort((a, b) => new Date(b.created_at || b.tanggal_post) - new Date(a.created_at || a.tanggal_post)).slice(0, 5)
    )

    // Fungsi utilitas
    const truncateText = (text, length = 100) => {
      if (!text) return ''
      return text.length > length ? text.substring(0, length) + '...' : text
    }
    const formatDate = (dateString) => {
      if (!dateString) return '-'
      const d = new Date(dateString)
      return d.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' })
    }
    const formatTime = (dateString) => {
      if (!dateString) return '-'
      const d = new Date(dateString)
      return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
    }

    // Fetch data saat mounted/activated
    const fetchDashboardData = async () => {
      await postsStore.fetchMyPosts()
    }
    onMounted(fetchDashboardData)
    onActivated(fetchDashboardData)

    return {
      authStore,
      loading,
      statistics,
      recentPosts,
      truncateText,
      formatDate,
      formatTime,
    }
  }
}
</script>
    

<style scoped>
.text-purple {
  color: #8b5cf6 !important;
}

.card-modern {
  border: none;
  border-radius: 15px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
}

.card-modern:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.stats-card {
  border: 1px solid #e2e8f0;
  border-radius: 15px;
  transition: all 0.3s ease;
  background: linear-gradient(135deg, #fff 0%, #f8fafc 100%);
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

.btn-penulis-primary {
  background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
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

.table th {
  font-weight: 600;
  color: #374151;
  border-bottom: 2px solid #e5e7eb;
}

.table td {
  vertical-align: middle;
  border-bottom: 1px solid #f3f4f6;
}

.table-hover tbody tr:hover {
  background-color: rgba(59, 130, 246, 0.05);
}

.btn-group .btn {
  border-radius: 6px;
  margin-right: 2px;
}

.badge {
  font-size: 0.75rem;
  padding: 0.5em 0.75em;
  border-radius: 6px;
}

.card-header {
  background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
  border-bottom: 1px solid #e5e7eb;
  border-radius: 15px 15px 0 0 !important;
  padding: 1.25rem 1.5rem;
}

.g-4 {
  gap: 1.5rem;
}

.row {
  margin: 0;
}

[class*="col-"] {
  padding: 0 0.75rem;
}

.text-primary {
  color: #2563eb !important;
}

.text-success {
  color: #059669 !important;
}

.text-danger {
  color: #dc2626 !important;
}

.text-muted {
  color: #6b7280 !important;
}
</style>