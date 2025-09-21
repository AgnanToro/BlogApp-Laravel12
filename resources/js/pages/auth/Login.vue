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

      <!-- Right Side - Login Form -->
      <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
          <!-- Mobile Logo -->
          <div class="lg:hidden text-center mb-8">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-blog text-2xl text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">BlogSpace</h2>
          </div>

          <!-- Login Header -->
          <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang Kembali</h2>
            <p class="text-gray-600">Silakan masuk ke akun Anda</p>
          </div>

          <!-- Success Message -->
          <div v-if="successMessage" class="mb-4 font-medium text-sm text-green-600 bg-green-100 border border-green-300 rounded p-3 text-center">
            {{ successMessage }}
          </div>

          <!-- Error Message -->
          <div v-if="errorMessage" class="mb-4 font-medium text-sm text-red-600 bg-red-100 border border-red-300 rounded p-3 text-center">
            {{ errorMessage }}
          </div>

          <!-- Login Form -->
          <form @submit.prevent="handleLogin" class="space-y-6">
            <!-- Email Field -->
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Alamat Email</label>
              <input 
                id="email" 
                type="email" 
                v-model="form.email"
                required 
                autocomplete="email" 
                autofocus
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                :class="{ 'border-red-500': errors.email }"
                placeholder="Masukkan alamat email"
              >
              <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email[0] }}</p>
            </div>

            <!-- Password Field -->
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi</label>
              <input 
                id="password" 
                type="password" 
                v-model="form.password"
                required 
                autocomplete="current-password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                :class="{ 'border-red-500': errors.password }"
                placeholder="Masukkan kata sandi"
              >
              <p v-if="errors.password" class="text-red-500 text-sm mt-1">{{ errors.password[0] }}</p>
              <div class="mt-2 text-right">
                <router-link to="/forgot-password" class="text-sm text-blue-600 hover:underline">
                  Lupa password?
                </router-link>
              </div>
            </div>

            <!-- Submit Button -->
            <button 
              type="submit" 
              :disabled="isLoading"
              class="w-full bg-blue-600 text-white font-medium py-3 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <i v-if="isLoading" class="fas fa-spinner fa-spin mr-2"></i>
              {{ isLoading ? 'Memproses...' : 'Masuk' }}
            </button>
          </form>

          <!-- Back to Homepage -->
          <div class="mt-6 text-center">
            <router-link 
              to="/" 
              class="inline-flex items-center justify-center w-full bg-gray-100 text-gray-700 font-medium py-3 px-4 rounded-lg hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors"
            >
              <i class="fas fa-arrow-left mr-2"></i>
              Kembali ke Beranda
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
import { useAuthStore } from '@/stores/auth'

export default {
  name: 'Login',
  setup() {
    const router = useRouter()
    const route = useRoute()
    const authStore = useAuthStore()
    
    const isLoading = ref(false)
    const successMessage = ref('')
    const errorMessage = ref('')
    const errors = ref({})
    
    const form = reactive({
      email: '',
      password: ''
    })

    // Check for success message from route query
    onMounted(() => {
      if (route.query.message) {
        successMessage.value = route.query.message
      }
    })

    const handleLogin = async () => {
      isLoading.value = true
      errors.value = {}
      errorMessage.value = ''
      try {
        await authStore.login(form.email, form.password)
        // Redirect based on user role
        let redirectTo = route.query.redirect
        if (!redirectTo) {
          if (authStore.user?.role === 'admin') {
            redirectTo = '/admin'
          } else if (authStore.user?.role === 'penulis') {
            redirectTo = '/penulis'
          } else {
            redirectTo = '/'
          }
        }
        router.push(redirectTo)
      } catch (error) {
        if (error.response?.status === 422) {
          errors.value = error.response.data.errors || {}
        } else if (error.response?.status === 401) {
          errorMessage.value = 'Email atau password salah'
        } else {
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
      handleLogin
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