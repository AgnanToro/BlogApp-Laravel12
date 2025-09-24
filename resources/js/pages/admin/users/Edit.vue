<template>
  <AdminLayout>
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card card-modern">
            <div class="card-header bg-white py-3">
              <div class="d-flex align-items-center">
                <router-link 
                  :to="{ name: 'admin.users.index' }" 
                  class="btn btn-outline-secondary btn-sm me-3"
                >
                  <i class="fas fa-arrow-left"></i>
                </router-link>
                <h4 class="card-title mb-0 fw-bold text-dark">
                  <i class="fas fa-user-edit me-2 text-primary"></i>
                  Edit User: {{ user?.name || 'Loading...' }}
                </h4>
              </div>
            </div>

            <div class="card-body">
              <!-- Loading State -->
              <div v-if="loading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted mt-2">Memuat data user...</p>
              </div>

              <!-- Form -->
              <div v-else>
                <!-- Error Alert -->
                <div 
                  v-if="errors.length > 0" 
                  class="alert alert-danger alert-dismissible fade show" 
                  role="alert"
                >
                  <i class="fas fa-exclamation-triangle me-2"></i>
                  <strong>Terdapat kesalahan:</strong>
                  <ul class="mb-0 mt-2">
                    <li v-for="error in errors" :key="error">{{ error }}</li>
                  </ul>
                  <button 
                    type="button" 
                    class="btn-close" 
                    @click="clearErrors"
                  ></button>
                </div>

                <form @submit.prevent="submitForm">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-4">
                        <label for="name" class="form-label fw-semibold">
                          Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input 
                          type="text" 
                          id="name" 
                          v-model="form.name"
                          class="form-control"
                          :class="{ 'is-invalid': fieldErrors.name }"
                          placeholder="Masukkan nama lengkap"
                          required
                        >
                        <div 
                          v-if="fieldErrors.name" 
                          class="invalid-feedback"
                        >
                          {{ fieldErrors.name[0] }}
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="mb-4">
                        <label for="email" class="form-label fw-semibold">
                          Email <span class="text-danger">*</span>
                        </label>
                        <input 
                          type="email" 
                          id="email" 
                          v-model="form.email"
                          class="form-control"
                          :class="{ 'is-invalid': fieldErrors.email }"
                          placeholder="Masukkan alamat email"
                          required
                        >
                        <div 
                          v-if="fieldErrors.email" 
                          class="invalid-feedback"
                        >
                          {{ fieldErrors.email[0] }}
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">
                          Password Baru <span class="text-muted">(opsional)</span>
                        </label>
                        <input 
                          type="password" 
                          id="password" 
                          v-model="form.password"
                          class="form-control"
                          :class="{ 'is-invalid': fieldErrors.password }"
                          placeholder="Masukkan password baru"
                        >
                        <div 
                          v-if="fieldErrors.password" 
                          class="invalid-feedback"
                        >
                          {{ fieldErrors.password[0] }}
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold">
                          Konfirmasi Password Baru
                        </label>
                        <input 
                          type="password" 
                          id="password_confirmation" 
                          v-model="form.password_confirmation"
                          class="form-control"
                          placeholder="Konfirmasi password baru"
                        >
                      </div>
                    </div>
                  </div>

                  <div class="mb-4">
                    <label for="role" class="form-label fw-semibold">
                      Role <span class="text-danger">*</span>
                    </label>
                    <select 
                      id="role" 
                      v-model="form.role"
                      class="form-select"
                      :class="{ 'is-invalid': fieldErrors.role }"
                      required
                    >
                      <option value="">Pilih Role</option>
                      <option value="admin">Admin</option>
                      <option value="penulis">Penulis</option>
                    </select>
                    <div 
                      v-if="fieldErrors.role" 
                      class="invalid-feedback"
                    >
                      {{ fieldErrors.role[0] }}
                    </div>
                    
                    <div class="form-text">
                      <div class="row mt-2">
                        <div class="col-md-6">
                          <div class="p-3 bg-danger bg-opacity-10 rounded">
                            <strong class="text-danger">
                              <i class="fas fa-user-shield me-1"></i> Admin:
                            </strong><br>
                            <small class="text-muted">
                              Akses penuh untuk mengelola semua konten dan user
                            </small>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="p-3 bg-info bg-opacity-10 rounded">
                            <strong class="text-info">
                              <i class="fas fa-user-edit me-1"></i> Penulis:
                            </strong><br>
                            <small class="text-muted">
                              Dapat menulis dan mengelola post sendiri
                            </small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Info User -->
                  <div class="mb-4" v-if="user">
                    <div class="card bg-light">
                      <div class="card-body">
                        <h6 class="card-title fw-bold mb-3">
                          <i class="fas fa-info-circle me-2 text-info"></i>Informasi User
                        </h6>
                        <div class="row">
                          <div class="col-md-3">
                            <small class="text-muted d-block">ID User</small>
                            <strong>#{{ user.id }}</strong>
                          </div>
                          <div class="col-md-3">
                            <small class="text-muted d-block">Terdaftar</small>
                            <strong>{{ formatDateTime(user.created_at) }}</strong>
                          </div>
                          <div class="col-md-3">
                            <small class="text-muted d-block">Terakhir Update</small>
                            <strong>{{ formatDateTime(user.updated_at) }}</strong>
                          </div>
                          <div class="col-md-3">
                            <small class="text-muted d-block">Status Email</small>
                            <span 
                              v-if="user.email_verified_at" 
                              class="badge bg-success"
                            >
                              <i class="fas fa-check-circle me-1"></i>Terverifikasi
                            </span>
                            <span 
                              v-else 
                              class="badge bg-warning"
                            >
                              <i class="fas fa-exclamation-triangle me-1"></i>Belum Terverifikasi
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <hr class="my-4">

                  <div class="d-flex justify-content-between">
                    <router-link 
                      :to="{ name: 'admin.users.index' }" 
                      class="btn btn-secondary"
                    >
                      <i class="fas fa-times me-2"></i>Batal
                    </router-link>
                    <button 
                      type="submit" 
                      class="btn btn-admin-primary"
                      :disabled="submitting"
                    >
                      <span v-if="submitting" class="spinner-border spinner-border-sm me-2"></span>
                      <i v-else class="fas fa-save me-2"></i>
                      {{ submitting ? 'Mengupdate...' : 'Update User' }}
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import { ref, reactive, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import AdminLayout from '@/layouts/AdminLayout.vue'
import axios from '@/lib/axios'

export default {
  name: 'AdminUsersEdit',
  components: {
    AdminLayout
  },
  setup() {
    const router = useRouter()
    const route = useRoute()
    
    const loading = ref(true)
    const submitting = ref(false)
    const errors = ref([])
    const fieldErrors = ref({})
    const user = ref(null)
    
    const form = reactive({
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      role: ''
    })
    
    // Methods
    const clearErrors = () => {
      errors.value = []
      fieldErrors.value = {}
    }
    
    const fetchUser = async () => {
      try {
        loading.value = true
        const response = await axios.get(`/api/admin/users/${route.params.id}`)
        user.value = response.data.data
        
        // Populate form
        form.name = user.value.name
        form.email = user.value.email
        form.role = user.value.role
        form.password = ''
        form.password_confirmation = ''
        
      } catch (error) {
        console.error('Error fetching user:', error)
        if (error.response?.status === 404) {
          router.push({ name: 'admin.users.index' })
        }
      } finally {
        loading.value = false
      }
    }
    
    const submitForm = async () => {
      if (submitting.value) return
      
      try {
        submitting.value = true
        clearErrors()
        
        const formData = { ...form }
        
        // Remove empty password fields
        if (!formData.password) {
          delete formData.password
          delete formData.password_confirmation
        }
        
        const response = await axios.put(`/api/admin/users/${route.params.id}`, formData)
        
        // Update local user data
        user.value = response.data.data
        
        // Show success message (you can implement toast notification here)
        console.log('User berhasil diupdate')
        
        // Redirect to users index
        router.push({ name: 'admin.users.index' })
        
      } catch (error) {
        if (error.response?.status === 422) {
          // Validation errors
          const validationErrors = error.response.data.errors
          fieldErrors.value = validationErrors
          
          // Collect all error messages
          const allErrors = []
          Object.values(validationErrors).forEach(fieldErrors => {
            fieldErrors.forEach(error => allErrors.push(error))
          })
          errors.value = allErrors
          
        } else {
          // Other errors
          errors.value = ['Terjadi kesalahan saat mengupdate user. Silakan coba lagi.']
        }
        
        console.error('Error updating user:', error)
      } finally {
        submitting.value = false
      }
    }
    
    const formatDateTime = (dateString) => {
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
      fetchUser()
    })
    
    return {
      loading,
      submitting,
      errors,
      fieldErrors,
      user,
      form,
      clearErrors,
      submitForm,
      formatDateTime
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

.form-label {
  color: #495057;
  margin-bottom: 0.5rem;
}

.form-control,
.form-select {
  border-radius: 0.5rem;
  border: 1px solid #ced4da;
  padding: 0.75rem 1rem;
  font-size: 0.875rem;
}

.form-control:focus,
.form-select:focus {
  border-color: #0d6efd;
  box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.form-control.is-invalid,
.form-select.is-invalid {
  border-color: #dc3545;
}

.invalid-feedback {
  display: block;
  width: 100%;
  margin-top: 0.25rem;
  font-size: 0.875rem;
  color: #dc3545;
}

.btn-admin-primary {
  background: linear-gradient(135deg,  #0d6efd 100%);
  border: none;
  color: white;
  font-weight: 500;
  padding: 0.75rem 2rem;
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

.bg-opacity-10 {
  --bs-bg-opacity: 0.1;
}

.card-header {
  border-bottom: 1px solid rgba(0, 0, 0, 0.125);
  border-radius: 0.75rem 0.75rem 0 0 !important;
}

.form-text .rounded {
  border-radius: 0.5rem !important;
}

.card.bg-light {
  background-color: #f8f9fa !important;
  border: 1px solid #e9ecef;
  border-radius: 0.5rem;
}

.badge {
  font-size: 0.75rem;
  font-weight: 500;
}

hr {
  border-color: rgba(0, 0, 0, 0.1);
  margin: 2rem 0;
}
</style>