<template>
  <AdminLayout page-title="Kelola Posts">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold">Kelola Posts</h2>
      <router-link to="/admin/posts/create" class="btn btn-admin-primary">
        <i class="fas fa-plus me-2"></i>Tambah Post Baru
      </router-link>
    </div>

    <div class="card card-modern">
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
                  <th>Komentar</th>
                  <th width="200">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="post in posts" :key="post.id">
                  <td>{{ post.id }}</td>
                  <td>
                    <strong>{{ truncateText(post.judul, 50) }}</strong>
                    <br>
                    <small class="text-muted">{{ truncateText(post.konten, 80) }}</small>
                  </td>
                  <td>
                    <span class="badge bg-primary">{{ formatDate(post.tanggal_post) }}</span>
                    <br>
                    <small class="text-muted">{{ formatTime(post.tanggal_post) }}</small>
                  </td>
                  <td>
                    <span class="badge bg-success">{{ post.comments_count || 0 }} komentar</span>
                  </td>
                  <td>
                    <div class="btn-group" role="group" style="gap:0.4rem;">
                      <router-link 
                        :to="{ name: 'admin.posts.show', params: { id: post.id } }" 
                        class="btn btn-sm btn-outline-info" 
                        style="margin-right:2px;"
                      >
                        <i class="fas fa-eye"></i>
                      </router-link>
                      <router-link 
                        :to="{ name: 'admin.posts.edit', params: { id: post.id } }" 
                        class="btn btn-sm btn-outline-warning" 
                        style="margin-right:2px;"
                      >
                        <i class="fas fa-edit"></i>
                      </router-link>
                      <router-link 
                        :to="`/posts/${post.id}`" 
                        class="btn btn-sm btn-outline-success" 
                        target="_blank" 
                        style="margin-right:2px;"
                      >
                        <i class="fas fa-external-link-alt"></i>
                      </router-link>
                      <button 
                        @click="confirmDelete(post)"
                        class="btn btn-sm btn-outline-danger"
                        :disabled="deleting === post.id"
                      >
                        <i v-if="deleting === post.id" class="fas fa-spinner fa-spin"></i>
                        <i v-else class="fas fa-trash"></i>
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
                    @click="changePage(pagination.current_page - 1)"
                    :disabled="pagination.current_page === 1"
                  >&lt; Back</button>
                </li>
                <li v-for="page in pagination.last_page" :key="page" class="page-item" :class="{ 'active': page === pagination.current_page }">
                  <button
                    class="page-link px-3 py-2 border border-gray-300"
                    :class="page === pagination.current_page ? 'bg-blue-600 text-white font-bold shadow' : 'bg-white text-blue-600 hover:bg-blue-50'"
                    @click="changePage(page)"
                    :disabled="page === pagination.current_page"
                    style="min-width:2.5rem"
                  >{{ page }}</button>
                </li>
                <li class="page-item" :class="{ 'disabled': pagination.current_page === pagination.last_page }">
                  <button 
                    class="page-link px-3 py-2 rounded-r-lg border border-gray-300 bg-white text-blue-600 font-semibold hover:bg-blue-50 disabled:opacity-50 disabled:cursor-not-allowed" 
                    @click="changePage(pagination.current_page + 1)"
                    :disabled="pagination.current_page === pagination.last_page"
                  >Next &gt;</button>
                </li>
              </ul>
            </nav>
          </div>
        </div>

        <div v-else class="text-center py-5">
          <i class="fas fa-newspaper text-muted mb-3" style="font-size: 4rem;"></i>
          <h4 class="fw-bold">Belum Ada Posts</h4>
          <p class="text-muted mb-4">Mulai dengan membuat post pertama Anda.</p>
          <router-link to="/admin/posts/create" class="btn btn-admin-primary">
            <i class="fas fa-plus me-2"></i>Buat Post Pertama
          </router-link>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import AdminLayout from '@/layouts/AdminLayout.vue'
import axios from '@/lib/axios'

export default {
  name: 'AdminPostsIndex',
  components: {
    AdminLayout
  },
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()
    
    const loading = ref(true)
    const posts = ref([])
    const deleting = ref(null)
    const pagination = ref({
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0
    })

    const paginationPages = computed(() => {
      const pages = []
      const startPage = Math.max(1, pagination.value.current_page - 2)
      const endPage = Math.min(pagination.value.last_page, pagination.value.current_page + 2)
      
      for (let i = startPage; i <= endPage; i++) {
        pages.push(i)
      }
      
      return pages
    })

    const fetchPosts = async (page = 1) => {
      loading.value = true
      try {
          const response = await axios.get(`/api/admin/posts?page=${page}&per_page=5`)
        
        posts.value = response.data.data
        pagination.value = {
          current_page: response.data.current_page,
          last_page: response.data.last_page,
          per_page: response.data.per_page,
          total: response.data.total
        }
      } catch (error) {
        console.error('Failed to fetch posts:', error)
        console.error('Error details:', error.response?.data || error.message)
      } finally {
        loading.value = false
      }
    }

    const changePage = (page) => {
      if (page >= 1 && page <= pagination.value.last_page) {
        fetchPosts(page)
      }
    }

    const confirmDelete = (post) => {
      if (confirm(`Yakin ingin menghapus post "${post.judul}"?`)) {
        deletePost(post.id)
      }
    }

    const deletePost = async (postId) => {
      deleting.value = postId
      try {
        await axios.delete(`/api/admin/posts/${postId}`)
        
        // Remove post from list
        posts.value = posts.value.filter(post => post.id !== postId)
        
        // If current page is empty, go to previous page
        if (posts.value.length === 0 && pagination.value.current_page > 1) {
          fetchPosts(pagination.value.current_page - 1)
        }
      } catch (error) {
        console.error('Failed to delete post:', error)
        alert('Gagal menghapus post. Silakan coba lagi.')
      } finally {
        deleting.value = null
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
      fetchPosts()
    })


    return {
      loading,
      posts,
      deleting,
      pagination,
      paginationPages,
      changePage,
      confirmDelete,
      truncateText,
      formatDate,
      formatTime
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

.card-modern {
  border: none;
  border-radius: 15px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.custom-pagination .pagination {
  gap: 0.5rem;
}

.custom-pagination .page-link {
  border: none;
  border-radius: 0.75rem;
  background: #fff;
  color: #222;
  font-weight: 500;
  min-width: 2.5rem;
  min-height: 2.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: none;
  transition: background 0.2s, color 0.2s;
}

.custom-pagination .page-item.active .page-link {
  background: #2563eb;
  color: #fff;
  font-weight: bold;
}

.custom-pagination .page-item.disabled .page-link {
  background: #f5f5f5;
  color: #bbb;
}

.custom-pagination .page-link:hover {
  background: #2563eb;
  color: #2563eb;
}
</style>