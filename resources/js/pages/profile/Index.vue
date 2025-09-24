<template>
  <div v-if="user">
    <component :is="user.role === 'admin' ? 'AdminLayout' : 'PenulisLayout'" :page-title="user.role === 'admin' ? 'Profile Admin' : 'Profile Penulis'">
      <div class="container py-4" style="background: #f8fafc; min-height: 100vh;">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card card-modern shadow-sm mb-4">
            <div class="card-header bg-white" style="border-radius: 14px 14px 0 0;">
              <h1 class="mb-0 fw-bold fs-2">
                <i v-if="user?.role === 'admin'" class="fas fa-user-shield me-2 text-primary"></i>
                <i v-else class="fas fa-user-circle me-2 text-primary"></i>
                {{ user?.role === 'admin' ? 'Profile Admin' : 'Profile Penulis' }}
              </h1>
            </div>
            <div class="card-body">
              <form @submit.prevent="submitForm">
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label text-muted">Nama</label>
                    <input v-if="editMode" v-model="form.name" type="text" class="form-control" required />
                    <div v-else class="form-control-plaintext fw-bold">{{ user?.name }}</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label text-muted">Email</label>
                    <div v-if="editMode">
                      <input v-model="form.email" type="email" class="form-control" required />
                    </div>
                    <div v-else class="d-flex align-items-center">
                      <div class="form-control-plaintext">{{ user?.email }}</div>
                      <span v-if="user?.email_verified_at" class="badge bg-success ms-2">Terverifikasi</span>
                      <span v-else class="badge bg-warning text-dark ms-2">Belum Terverifikasi</span>
                    </div>
                  </div>
                </div>
                <div v-if="editMode" class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label text-muted">Password Baru (Opsional)</label>
                    <input v-model="form.password" type="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password" />
                  </div>
                  <div class="col-md-6">
                    <label class="form-label text-muted">Konfirmasi Password</label>
                    <input v-model="form.password_confirmation" type="password" class="form-control" placeholder="Ulangi password baru" />
                  </div>
                </div>
                <div class="row mb-3" v-if="editMode">
  <div class="col-md-6">
    <label class="form-label text-muted">Status Email</label>
    <select v-model="form.email_verified_at" class="form-select">
      <option :value="null">Belum Terverifikasi</option>
      <option :value="true">Terverifikasi</option>
    </select>
  </div>
</div>
                <!-- moved buttons below last update -->
                <div v-if="editMode" class="mb-4 d-flex justify-content-between gap-2">
                  <button type="button" class="btn btn-secondary px-4" @click="cancelEdit"><i class="fas fa-arrow-left me-2"></i>Kembali</button>
                  <button type="submit" class="btn btn-primary px-4" :disabled="loading">
                    <i v-if="loading" class="fas fa-spinner fa-spin me-2"></i>
                    <i v-else class="fas fa-save me-2"></i>Simpan Perubahan
                  </button>
                </div>
              </form>
              <hr>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label text-muted">Role</label>
                  <div class="form-control-plaintext">{{ user?.role ? user.role.charAt(0).toUpperCase() + user.role.slice(1) : '' }}</div>
                </div>
                <div class="col-md-6">
                  <label class="form-label text-muted">Bergabung</label>
                  <div class="form-control-plaintext">{{ formatDate(user?.created_at) }}</div>
                </div>
              </div>
              <div class="row mb-4">
                <div class="col-md-6">
                  <label class="form-label text-muted">Last Update</label>
                  <div class="form-control-plaintext">{{ formatDateTime(user?.updated_at) }}</div>
                </div>
              </div>
              <div v-if="!editMode" class="mb-4 d-flex justify-content-between gap-2">
                <button type="button" class="btn btn-secondary px-4" @click="$router.back()"><i class="fas fa-arrow-left me-2"></i>Kembali</button>
                <button type="button" class="btn btn-primary px-4" @click="editMode = true"><i class="fas fa-edit me-2"></i>Edit Profil</button>
              </div>

          
             
            </div>
          </div>
        </div>
      </div>
      </div>
    </component>
  </div>
</template>


<script>
import { computed, onMounted, ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import PenulisLayout from '@/layouts/PenulisLayout.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import axios from '@/lib/axios'

export default {
  name: 'ProfileIndex',
  components: { 
    PenulisLayout, 
    AdminLayout },
  setup() {
    const authStore = useAuthStore()
    const user = computed(() => authStore.user)
    const stats = ref({ posts: 0, comments: 0 })
    const editMode = ref(false)
    const loading = ref(false)
    const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  email_verified_at: null
})

    const formatDate = (dateString) => {
      if (!dateString) return '-';
      const date = new Date(dateString)
      const options = { year: 'numeric', month: 'long', day: 'numeric' }
      return date.toLocaleDateString('id-ID', options)
    }
    const formatDateTime = (dateString) => {
      if (!dateString) return '-';
      const date = new Date(dateString)
      const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' }
      return date.toLocaleDateString('id-ID', options) + ' ' + date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
    }

    const fetchStats = async () => {
      try {
        const res = await axios.get('/api/user/stats')
        stats.value = res.data
      } catch (e) {
        stats.value = { posts: 0, comments: 0 }
      }
    }


    const submitForm = async () => {
      loading.value = true
      try {
        await axios.put('/api/user/profile', {
          name: form.value.name,
          email: form.value.email,
          password: form.value.password,
          password_confirmation: form.value.password_confirmation,
          email_verified_at: form.value.email_verified_at ? new Date().toISOString() : null
        })
        alert('Profil berhasil diperbarui!')
        editMode.value = false
        authStore.fetchUser()
      } catch (error) {
        alert('Gagal memperbarui profil. Pastikan data valid.')
      } finally {
        loading.value = false
      }
    }

    const cancelEdit = () => {
      editMode.value = false
  form.value.name = user.value?.name || ''
  form.value.email = user.value?.email || ''
  form.value.password = ''
  form.value.password_confirmation = ''
  form.value.email_verified_at = user.value?.email_verified_at ? true : null
    }

    onMounted(() => {
      if (!user.value) {
        authStore.fetchUser()
      }
      fetchStats()
      // Set form awal
  form.value.email_verified_at = user.value?.email_verified_at ? true : null
  form.value.name = user.value?.name || ''
  form.value.email = user.value?.email || ''
    })

    return {
      user,
      stats,
      editMode,
      loading,
      form,
      formatDate,
      formatDateTime,
      submitForm,
      cancelEdit
    }
  }
}
</script>

<style scoped>
.card-modern {
  border: 1px solid #e3e6ed;
  border-radius: 14px;
  box-shadow: 0 2px 16px 0 rgba(60,72,88,.08);
  background: #fff;
}
</style>
