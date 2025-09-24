
import './bootstrap';

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import { useAuthStore } from './stores/auth'

// Import main App component
import App from './App.vue'

// Create Vue app
const app = createApp(App)

// Use Pinia for state management
const pinia = createPinia()
app.use(pinia)

// Use Vue Router
app.use(router)

// Initialize auth store
const authStore = useAuthStore()
authStore.initialize()

// Mount the app
app.mount('#app')
