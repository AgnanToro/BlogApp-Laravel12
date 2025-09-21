<template>
  <AdminLayout page-title="Tambah Post Baru">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <router-link to="/admin">Dashboard</router-link>
        </li>
        <li class="breadcrumb-item">
          <router-link to="/admin/posts">Kelola Posts</router-link>
        </li>
        <li class="breadcrumb-item active">Tambah Post Baru</li>
      </ol>
    </nav>

    <div class="row">
      <div class="col-lg-8">
        <div class="card card-modern">
          <div class="card-header bg-transparent border-bottom">
            <h5 class="mb-0 fw-bold">
              <i class="fas fa-plus-circle text-primary me-2"></i>Form Tambah Post
            </h5>
          </div>
          <div class="card-body">
            <form @submit.prevent="handleSubmit">
              <!-- Foto Upload -->
              <div class="mb-4">
                <label class="form-label fw-semibold">
                  <i class="fas fa-image me-2 text-primary"></i>Foto Artikel
                </label>
                <div class="mb-2">
                  <input 
                    type="file" 
                    @change="handleFileChange"
                    accept="image/*" 
                    class="form-control"
                    :class="{ 'is-invalid': errors.foto }"
                    ref="fileInput"
                  >
                  <div v-if="imagePreview" class="mt-2">
                    <img 
                      :src="imagePreview" 
                      alt="Preview" 
                      style="max-width:200px; max-height:200px; border-radius:8px; border:1px solid #e5e7eb; box-shadow:0 2px 8px rgba(0,0,0,0.05);" 
                    />
                  </div>
                </div>
                <div v-if="errors.foto" class="invalid-feedback d-block">
                  <i class="fas fa-exclamation-circle me-1"></i>{{ errors.foto[0] }}
                </div>
              </div>
              
              <!-- Judul -->
              <div class="mb-4">
                <label for="judul" class="form-label fw-semibold">
                  <i class="fas fa-heading me-2 text-primary"></i>Judul Post
                </label>
                <input 
                  type="text" 
                  class="form-control form-control-lg" 
                  :class="{ 'is-invalid': errors.judul }"
                  id="judul" 
                  v-model="form.judul"
                  placeholder="Masukkan judul yang menarik..."
                  required
                >
                <div v-if="errors.judul" class="invalid-feedback">
                  <i class="fas fa-exclamation-circle me-1"></i>{{ errors.judul[0] }}
                </div>
                <div class="form-text">Gunakan judul yang menarik dan SEO-friendly.</div>
              </div>
              
              <!-- Konten -->
              <div class="mb-4">
                <label for="konten" class="form-label fw-semibold">
                  <i class="fas fa-align-left me-2 text-primary"></i>Konten Post
                </label>
                <textarea 
                  class="form-control" 
                  :class="{ 'is-invalid': errors.konten }"
                  id="konten" 
                  v-model="form.konten"
                  rows="12" 
                  placeholder="Tulis konten post yang menarik dan informatif..."
                  required
                ></textarea>
                <div v-if="errors.konten" class="invalid-feedback">
                  <i class="fas fa-exclamation-circle me-1"></i>{{ errors.konten[0] }}
                </div>
                <div class="form-text">Gunakan format markdown untuk styling yang lebih baik.</div>
              </div>
              
              <!-- Action Buttons -->
              <div class="d-flex justify-content-between align-items-center">
                <router-link to="/admin/posts" class="btn btn-outline-secondary">
                  <i class="fas fa-arrow-left me-2"></i>Kembali
                </router-link>
                <div>
                  <button type="button" @click="resetForm" class="btn btn-outline-warning me-2">
                    <i class="fas fa-undo me-2"></i>Reset
                  </button>
                  <button type="submit" class="btn btn-admin-primary" :disabled="isSubmitting">
                    <i v-if="isSubmitting" class="fas fa-spinner fa-spin me-2"></i>
                    <i v-else class="fas fa-save me-2"></i>
                    {{ isSubmitting ? 'Menyimpan...' : 'Simpan Post' }}
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      
      <!-- Sidebar -->
      <div class="col-lg-4">
        <div class="card card-modern mb-4">
          <div class="card-header bg-transparent border-bottom">
            <h6 class="mb-0 fw-bold">
              <i class="fas fa-info-circle text-info me-2"></i>Informasi
            </h6>
          </div>
          <div class="card-body">
            <div class="d-flex align-items-start mb-3">
              <i class="fas fa-clock text-primary me-3 mt-1"></i>
              <div>
                <h6 class="mb-1">Tanggal Publikasi</h6>
                <p class="text-muted mb-0 small">Akan otomatis diisi saat post disimpan</p>
              </div>
            </div>
            <div class="d-flex align-items-start mb-3">
              <i class="fas fa-user text-success me-3 mt-1"></i>
              <div>
                <h6 class="mb-1">Author</h6>
                <p class="text-muted mb-0 small">{{ authStore.user?.name }}</p>
              </div>
            </div>
            <div class="d-flex align-items-start">
              <i class="fas fa-eye text-warning me-3 mt-1"></i>
              <div>
                <h6 class="mb-1">Status</h6>
                <p class="text-muted mb-0 small">Akan langsung dipublikasikan</p>
              </div>
            </div>
          </div>
        </div>

        <div class="card card-modern">
          <div class="card-header bg-transparent border-bottom">
            <h6 class="mb-0 fw-bold">
              <i class="fas fa-lightbulb text-warning me-2"></i>Tips Menulis
            </h6>
          </div>
          <div class="card-body">
            <div class="small">
              <div class="mb-2">
                <i class="fas fa-check text-success me-2"></i>
                Gunakan judul yang menarik
              </div>
              <div class="mb-2">
                <i class="fas fa-check text-success me-2"></i>
                Tulis pembukaan yang engaging
              </div>
              <div class="mb-2">
                <i class="fas fa-check text-success me-2"></i>
                Gunakan paragraf pendek
              </div>
              <div class="mb-2">
                <i class="fas fa-check text-success me-2"></i>
                Sertakan call-to-action
              </div>
              <div>
                <i class="fas fa-check text-success me-2"></i>
                Proofread sebelum publish
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import AdminLayout from '@/layouts/AdminLayout.vue'
import axios from '@/lib/axios'

export default {
  name: 'AdminPostsCreate',
  components: {
    AdminLayout
  },
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()
    
    const isSubmitting = ref(false)
    const errors = ref({})
    const imagePreview = ref(null)
    const fileInput = ref(null)
    const selectedFile = ref(null)
    
    const form = reactive({
      judul: '',
      konten: '',
      foto: null
    })

    const handleFileChange = (event) => {
      const file = event.target.files[0]
      if (file && file.type.startsWith('image/')) {
        selectedFile.value = file
        const reader = new FileReader()
        reader.onload = (e) => {
          imagePreview.value = e.target.result
        }
        reader.readAsDataURL(file)
      } else {
        selectedFile.value = null
        imagePreview.value = null
      }
    }

    const resetForm = () => {
      form.judul = ''
      form.konten = ''
      selectedFile.value = null
      imagePreview.value = null
      if (fileInput.value) {
        fileInput.value.value = ''
      }
      errors.value = {}
    }

    const handleSubmit = async () => {
      isSubmitting.value = true
      errors.value = {}
      
      try {
        const formData = new FormData()
        formData.append('judul', form.judul)
        formData.append('konten', form.konten)
        if (selectedFile.value) {
          formData.append('foto', selectedFile.value)
        }
        
        const response = await axios.post('/api/admin/posts', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
            Authorization: `Bearer ${authStore.token}`
          }
        })
        
        // Redirect to posts index with success message
        router.push({
          path: '/admin/posts',
          query: { success: 'Post berhasil dibuat!' }
        })
        
      } catch (error) {
        if (error.response?.status === 422) {
          // Validation errors
          errors.value = error.response.data.errors || {}
        } else {
          alert('Terjadi kesalahan saat menyimpan post. Silakan coba lagi.')
        }
      } finally {
        isSubmitting.value = false
      }
    }

    return {
      authStore,
      form,
      isSubmitting,
      errors,
      imagePreview,
      fileInput,
      handleFileChange,
      resetForm,
      handleSubmit
    }
  }
}
</script>

<style scoped>
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

.breadcrumb-item a {
  text-decoration: none;
  color: #6b7280;
}

.breadcrumb-item a:hover {
  color: #2563eb;
}
</style>