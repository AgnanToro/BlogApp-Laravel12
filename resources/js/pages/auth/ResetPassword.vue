<template>
  <div class="min-h-screen bg-gray-50">
    <div class="min-h-screen flex">
      <!-- Left Side - Image/Brand -->
      <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-600 to-blue-800 items-center justify-center p-12">
        <div class="text-center text-white">
          <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-8">
            <i class="fas fa-blog text-4xl text-white"></i>
          </div>
          <h1 class="text-4xl font-bold mb-4">BlogSpace</h1>
          <p class="text-xl text-blue-100">Platform berbagi cerita dan pengetahuan untuk komunitas Indonesia</p>
        </div>
      </div>

      <!-- Right Side - Reset Password Form -->
      <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
          <!-- Mobile Logo -->
          <div class="lg:hidden text-center mb-8">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-blog text-2xl text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">BlogSpace</h2>
          </div>

          <!-- Reset Password Header -->
          <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Reset Password</h2>
            <p class="text-gray-600">Masukkan password baru untuk akun Anda.</p>
          </div>

          <!-- Success Message -->
          <div v-if="successMessage" class="mb-4 font-medium text-sm text-green-600 bg-green-100 border border-green-300 rounded p-3 text-center">
            {{ successMessage }}
          </div>

          <!-- Error Message -->
          <div v-if="errorMessage" class="mb-4 font-medium text-sm text-red-600 bg-red-100 border border-red-300 rounded p-3 text-center">
            {{ errorMessage }}
          </div>

          <!-- Reset Password Form -->
          <form @submit.prevent="handleResetPassword" class="space-y-6">
            <!-- Password Field -->
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
              <input 
                id="password" 
                type="password" 
                v-model="form.password"
                required 
                autofocus 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                :class="{ 'border-red-500': errors.password }"
                placeholder="Masukkan password baru"
              >
              <p v-if="errors.password" class="text-red-500 text-sm mt-1">{{ errors.password[0] }}</p>
            </div>

            <!-- Password Confirmation Field -->
            <div>
              <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
              <input 
                id="password_confirmation" 
                type="password" 
                v-model="form.password_confirmation"
                required 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                :class="{ 'border-red-500': errors.password_confirmation }"
                placeholder="Konfirmasi password baru"
              >
              <p v-if="errors.password_confirmation" class="text-red-500 text-sm mt-1">{{ errors.password_confirmation[0] }}</p>
            </div>

            <!-- Submit Button -->
            <button 
              type="submit" 
              :disabled="isLoading"
              class="w-full bg-blue-600 text-white font-medium py-3 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <i v-if="isLoading" class="fas fa-spinner fa-spin mr-2"></i>
              {{ isLoading ? 'Memproses...' : 'Reset Password' }}
            </button>
          </form>

          <!-- Back to Login -->
          <div class="mt-3 text-center">
            <router-link to="/login" class="text-blue-600 hover:underline">
              Kembali ke Login
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'

export default {
  name: 'ResetPassword',
  setup() {
    const router = useRouter()
    const route = useRoute()
    
    const isLoading = ref(false)
    const successMessage = ref('')
    const errorMessage = ref('')
    const errors = ref({})
    
    const form = reactive({
      token: '',
      email: '',
      password: '',
      password_confirmation: ''
    })

    // Get token and email from route params/query
    onMounted(() => {
      form.token = route.params.token || route.query.token || ''
      form.email = route.query.email || ''
      
      if (!form.token) {
        errorMessage.value = 'Token reset password tidak valid.'
      }
    })

    const handleResetPassword = async () => {
      if (!form.token) {
        errorMessage.value = 'Token reset password tidak valid.'
        return
      }

      isLoading.value = true
      errors.value = {}
      errorMessage.value = ''
      successMessage.value = ''
      
      try {
        const response = await axios.post('/auth/reset-password', {
          token: form.token,
          email: form.email,
          password: form.password,
          password_confirmation: form.password_confirmation
        })
        
        successMessage.value = response.data.message || 'Password berhasil direset.'
        
        // Redirect to login after success
        setTimeout(() => {
          router.push({
            path: '/login',
            query: { message: 'Password berhasil direset. Silakan login dengan password baru Anda.' }
          })
        }, 2000)
        
      } catch (error) {
        if (error.response?.status === 422) {
          // Validation errors
          errors.value = error.response.data.errors || {}
        } else if (error.response?.status === 400) {
          // Bad request (invalid token, etc.)
          errorMessage.value = error.response.data.message || 'Token tidak valid atau sudah kedaluwarsa.'
        } else {
          // General error
          errorMessage.value = 'Terjadi kesalahan. Silakan coba lagi.'
        }
      } finally {
        isLoading.value = false
      }
    }

    return {
      form,
      isLoading,
      successMessage,
      errorMessage,
      errors,
      handleResetPassword
    }
  }
}
</script>

<style scoped>
/* Additional custom styles if needed */
.bg-gradient-to-br {
  background: linear-gradient(to bottom right, var(--tw-gradient-stops));
}

.backdrop-blur-sm {
  backdrop-filter: blur(4px);
}
</style>