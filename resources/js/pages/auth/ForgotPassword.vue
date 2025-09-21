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

      <!-- Right Side - Forgot Password Form -->
      <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
          <!-- Mobile Logo -->
          <div class="lg:hidden text-center mb-8">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-blog text-2xl text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">BlogSpace</h2>
          </div>

          <!-- Forgot Password Header -->
          <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Lupa Password</h2>
            <p class="text-gray-600">Masukkan email Anda untuk menerima link reset password.</p>
          </div>

          <!-- Success Message -->
          <div v-if="successMessage" class="mb-4 font-medium text-sm text-green-600 bg-green-100 border border-green-300 rounded p-3 text-center">
            {{ successMessage }}
          </div>

          <!-- Error Message -->
          <div v-if="errorMessage" class="mb-4 font-medium text-sm text-red-600 bg-red-100 border border-red-300 rounded p-3 text-center">
            {{ errorMessage }}
          </div>

          <!-- Forgot Password Form -->
          <form @submit.prevent="handleForgotPassword" class="space-y-6">
            <!-- Email Field -->
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
              <input 
                type="email" 
                name="email" 
                id="email" 
                v-model="form.email"
                required 
                autofocus 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                :class="{ 'border-red-500': errors.email }"
                placeholder="Masukkan alamat email"
              >
              <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email[0] }}</p>
            </div>

            <!-- Submit Button -->
            <button 
              type="submit" 
              :disabled="isLoading"
              class="w-full bg-blue-600 text-white font-medium py-3 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <i v-if="isLoading" class="fas fa-spinner fa-spin mr-2"></i>
              {{ isLoading ? 'Mengirim...' : 'Kirim Link Reset' }}
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
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

export default {
  name: 'ForgotPassword',
  setup() {
    const router = useRouter()
    
    const isLoading = ref(false)
    const successMessage = ref('')
    const errorMessage = ref('')  
    const errors = ref({})
    
    const form = reactive({
      email: ''
    })

    const handleForgotPassword = async () => {
      isLoading.value = true
      errors.value = {}
      errorMessage.value = ''
      successMessage.value = ''
      
      try {
        const response = await axios.post('/auth/forgot-password', {
          email: form.email
        })
        
        successMessage.value = response.data.message || 'Link reset password telah dikirim ke email Anda.'
        form.email = '' // Clear form
        
      } catch (error) {
        if (error.response?.status === 422) {
          // Validation errors
          errors.value = error.response.data.errors || {}
        } else if (error.response?.status === 404) {
          // Email not found
          errorMessage.value = 'Email tidak ditemukan dalam sistem.'
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
      handleForgotPassword
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