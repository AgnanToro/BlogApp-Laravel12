<template>
  <div class="min-h-screen bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
      <div class="w-full max-w-md p-6">
        <slot />
        
        <!-- Toast Notifications -->
        <div v-if="successMessage" class="alert alert-success alert-dismissible fade show mt-4" role="alert">
          <i class="fas fa-check-circle me-2"></i>
          {{ successMessage }}
          <button type="button" class="btn-close" @click="successMessage = ''"></button>
        </div>
        
        <div v-if="errorMessage" class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
          <i class="fas fa-exclamation-circle me-2"></i>
          {{ errorMessage }}
          <button type="button" class="btn-close" @click="errorMessage = ''"></button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, provide } from 'vue'

export default {
  name: 'AuthLayout',
  setup() {
    const successMessage = ref('')
    const errorMessage = ref('')
    
    // Provide methods to child components
    const showSuccess = (message) => {
      successMessage.value = message
      setTimeout(() => {
        successMessage.value = ''
      }, 5000)
    }
    
    const showError = (message) => {
      errorMessage.value = message
      setTimeout(() => {
        errorMessage.value = ''
      }, 5000)
    }
    
    provide('showSuccess', showSuccess)
    provide('showError', showError)
    
    return {
      successMessage,
      errorMessage,
      showSuccess,
      showError
    }
  }
}
</script>

<style scoped>
/* Tailwind-like utilities for auth layout */
.min-h-screen {
  min-height: 100vh;
}

.bg-gray-50 {
  background-color: #f9fafb;
}

.flex {
  display: flex;
}

.items-center {
  align-items: center;
}

.justify-center {
  justify-content: center;
}

.w-full {
  width: 100%;
}

.max-w-md {
  max-width: 28rem;
}

.p-6 {
  padding: 1.5rem;
}

.alert {
  border-radius: 0.5rem;
  border: none;
}

.alert-success {
  background-color: rgba(34, 197, 94, 0.1);
  color: #166534;
  border-left: 4px solid #22c55e;
}

.alert-danger {
  background-color: rgba(239, 68, 68, 0.1);
  color: #991b1b;
  border-left: 4px solid #ef4444;
}

.btn-close {
  background: none;
  border: none;
  font-size: 1.25rem;
  opacity: 0.5;
  cursor: pointer;
}

.btn-close:hover {
  opacity: 0.75;
}

.fade {
  transition: opacity 0.15s linear;
}

.show {
  opacity: 1;
}

.mt-4 {
  margin-top: 1rem;
}

.me-2 {
  margin-right: 0.5rem;
}
</style>