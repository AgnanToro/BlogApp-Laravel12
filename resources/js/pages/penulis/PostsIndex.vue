<template>
  <PenulisLayout :pageTitle="'Kelola Post'">
    
      <div class="card card-modern mb-4">
        <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-bold">Kelola Post</h5>
          <router-link :to="{ name: 'penulis.posts.create' }" class="btn btn-penulis-primary">
            <i class="fas fa-plus me-2"></i>Buat Post Baru
          </router-link>
        </div>
        <div class="card-body">
          <div v-if="loading" class="text-center py-4">
            <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
            <p class="mt-2 text-muted">Loading...</p>
          </div>
          <div v-else-if="posts.length > 0">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="table-light">
                  <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Tanggal Post</th>
                    <th>Jam</th>
                    <th>Komentar</th>
                    <th width="150">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="post in posts" :key="post.id">
                    <td>{{ post.id }}</td>
                    <td>
                      <strong>{{ post.title || post.judul }}</strong>
                      <br>
                      <small class="text-muted">{{ truncateText(post.content || post.konten, 70) }}</small>
                    </td>
                    <td>{{ formatDate(post.created_at || post.tanggal_post) }}</td>
                    <td>{{ formatTime(post.created_at || post.tanggal_post) }}</td>
                    <td>{{ post.comments_count || 0 }} komentar</td>
                    <td>
                      <div class="btn-group btn-group-sm" role="group">
                        <router-link :to="{ name: 'penulis.posts.show', params: { id: post.id } }" class="btn btn-outline-info me-2">
                          <i class="fas fa-eye"></i>
                        </router-link>
                        <router-link :to="{ name: 'penulis.posts.edit', params: { id: post.id } }" class="btn btn-outline-warning">
                          <i class="fas fa-edit"></i>
                        </router-link>
                        <button @click="deletePost(post.id)" class="btn btn-outline-danger ms-2">
                          <i class="fas fa-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- Pagination -->
            <div v-if="pagination.last_page > 1" class="d-flex justify-content-center mt-4">
              <nav>
                <ul class="pagination custom-pagination" style="display:flex;gap:4px;align-items:center;">
                  <li class="page-item" :class="{ 'disabled': pagination.current_page === 1 }">
                    <button 
                      class="page-link px-3 py-2 rounded-l-lg border border-gray-300 bg-white text-blue-600 font-semibold hover:bg-blue-50 disabled:opacity-50 disabled:cursor-not-allowed" 
                      @click="() => changePage(pagination.current_page - 1)"
                      :disabled="pagination.current_page === 1"
                    >&lt; Back</button>
                  </li>
                  <li v-for="page in pageNumbers" :key="page" class="page-item" :class="{ 'active': page === pagination.current_page }">
                    <button
                      class="page-link px-3 py-2 border border-gray-300"
                      :class="page === pagination.current_page ? 'bg-blue-600 text-white font-bold shadow' : 'bg-white text-blue-600 hover:bg-blue-50'"
                      @click="() => changePage(page)"
                      :disabled="page === pagination.current_page"
                      style="min-width:2.5rem"
                    >{{ page }}</button>
                  </li>
                  <li class="page-item" :class="{ 'disabled': pagination.current_page === pagination.last_page }">
                    <button 
                      class="page-link px-3 py-2 rounded-r-lg border border-gray-300 bg-white text-blue-600 font-semibold hover:bg-blue-50 disabled:opacity-50 disabled:cursor-not-allowed" 
                      @click="() => changePage(pagination.current_page + 1)"
                      :disabled="pagination.current_page === pagination.last_page"
                    >Next &gt;</button>
                  </li>
                </ul>
              </nav>
            </div>
          </div>


          
          <div v-else class="text-center py-5">
            <i class="fas fa-newspaper text-muted mb-3" style="font-size: 4rem;"></i>
            <h5 class="fw-bold">Belum Ada Posts</h5>
            <p class="text-muted mb-4">Mulai dengan membuat post pertama Anda.</p>
            <router-link :to="{ name: 'penulis.posts.create' }" class="btn btn-penulis-primary">
              <i class="fas fa-plus me-2"></i>Buat Post Pertama
            </router-link>
          </div>
        </div>
      </div>
    
  </PenulisLayout>
</template>

<script>
import { ref, onMounted, onActivated, computed } from 'vue'
import { usePostsStore } from '@/stores/posts'
import PenulisLayout from '@/layouts/PenulisLayout.vue'

export default {
  name: 'PenulisPostsIndex',
  components: { PenulisLayout },
  setup() {
    const postsStore = usePostsStore()
    const loading = computed(() => postsStore.isLoading)
    const posts = computed(() => postsStore.posts || [])
    const pagination = ref({
      current_page: 1,
      last_page: 1,
      per_page: 5,
      total: 0
    })

    // Ambil post milik user yang sedang login (penulis) dengan pagination
    const fetchPosts = async (page = 1) => {
      await postsStore.fetchMyPosts(page, 5)
      if (postsStore.pagination) {
        pagination.value = { ...postsStore.pagination }
      }
    }

    // Helper: generate array of page numbers for pagination
    const pageNumbers = computed(() => {
      const pages = []
      for (let i = 1; i <= pagination.value.last_page; i++) {
        pages.push(i)
      }
      return pages
    })

    const changePage = async (page) => {
      if (page >= 1 && page <= pagination.value.last_page && page !== pagination.value.current_page) {
        await fetchPosts(page)
      }
    }

    const deletePost = async (id) => {
      if (!confirm('Yakin ingin menghapus post ini?')) return
      try {
        await postsStore.deletePost(id)
        // Setelah hapus, refresh data
        fetchPosts(pagination.value.current_page)
      } catch (e) {
        alert('Gagal menghapus post.')
      }
    }

    

    const truncateText = (text, length = 100) => {
      if (!text) return ''
      return text.length > length ? text.substring(0, length) + '...' : text
    }

    const formatDate = (dateString) => {
      const options = { year: 'numeric', month: 'short', day: 'numeric' }
      return new Date(dateString).toLocaleDateString('id-ID', options)
    }
    const formatTime = (dateString) => {
      const options = { hour: '2-digit', minute: '2-digit' }
      return new Date(dateString).toLocaleTimeString('id-ID', options)
    }
    const getStatusText = (status) => {
      const statusMap = { published: 'Terbit', draft: 'Draft', archived: 'Arsip' }
      return statusMap[status] || status
    }
    const getStatusBadgeClass = (status) => {
      const classMap = { published: 'bg-success', draft: 'bg-warning', archived: 'bg-secondary' }
      return classMap[status] || 'bg-primary'
    }


    // Fetch data saat halaman pertama kali dimount dan saat diaktifkan kembali
    onMounted(() => {
      fetchPosts()
    })
    onActivated(() => {
      fetchPosts()
    })

    return {
      loading,
      posts,
      pagination,
      pageNumbers,
      truncateText,
      formatDate,
      formatTime,
      getStatusText,
      getStatusBadgeClass,
      deletePost,
      changePage
    }
  }
}
</script>

<style scoped>
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
</style>
