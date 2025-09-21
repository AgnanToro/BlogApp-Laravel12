<template>
  <PenulisLayout :page-title="'Tambah Post Baru'">
      <div class="row">
        <div class="col-lg-8">
          <div class="card card-modern">
            <div class="card-header bg-transparent border-bottom">
              <h5 class="mb-0 fw-bold">
                <i class="fas fa-plus-circle text-primary me-2"></i>Form Tambah Post
              </h5>
            </div>
            <div class="card-body">
              <form @submit.prevent="handleSubmit" enctype="multipart/form-data">
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
                  <router-link to="/penulis/posts" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                  </router-link>
                  <div>
                    <button type="button" @click="resetForm" class="btn btn-outline-warning me-2">
                      <i class="fas fa-undo me-2"></i>Reset
                    </button>
                    <button type="submit" class="btn btn-penulis-primary" :disabled="loading">
                      <i v-if="loading" class="fas fa-spinner fa-spin me-2"></i>
                      <i v-else class="fas fa-save me-2"></i>
                      {{ loading ? 'Menyimpan...' : 'Simpan Post' }}
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
                  <p class="text-muted mb-0 small">Akan langsung dipublikasikan/draft sesuai pilihan</p>
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
   
  </PenulisLayout>
</template>

<script setup>

import { ref, reactive, inject } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { usePostsStore } from '@/stores/posts'
import PenulisLayout from '@/layouts/PenulisLayout.vue'

const postsStore = usePostsStore()
const authStore = useAuthStore()
const router = useRouter()
const showSuccess = inject('showSuccess')
const showError = inject('showError')
const loading = ref(false)
const errors = ref({})
const imagePreview = ref(null)
const fileInput = ref(null)
const selectedFile = ref(null)
const form = reactive({
  judul: '',
  konten: '',
  foto: null,
  status: 'published'
})

function handleFileChange(event) {
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

function resetForm() {
  form.judul = ''
  form.konten = ''
  form.image = null
  form.status = 'published'
  selectedFile.value = null
  imagePreview.value = null
  if (fileInput.value) fileInput.value.value = ''
  errors.value = {}
}

async function handleSubmit() {
  loading.value = true
  errors.value = {}
  const formData = new FormData()
  formData.append('judul', form.judul)
  formData.append('konten', form.konten)
  formData.append('status', form.status)
  if (selectedFile.value) {
    formData.append('foto', selectedFile.value)
  }
  try {
    await postsStore.createPost(formData)
    await postsStore.fetchMyPosts()
    showSuccess && showSuccess('Post berhasil dibuat!')
    router.push({ name: 'penulis.posts.index' })
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {}
    } else {
      showError && showError('Gagal membuat post. Pastikan data sudah benar.')
    }
  } finally {
    loading.value = false
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
</style>