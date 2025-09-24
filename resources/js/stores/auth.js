import { defineStore } from 'pinia'
import axios from 'axios'

// Configure axios defaults
axios.defaults.baseURL = '/api'
axios.defaults.withCredentials = true

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
    isLoading: false
  }),

  getters: {
    isAuthenticated: (state) => !!state.token && !!state.user,
    isAdmin: (state) => state.user?.role === 'admin',
    isPenulis: (state) => state.user?.role === 'penulis'
  },

  actions: {
    async initialize() {
      if (this.token) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        try {
          await this.fetchUser()
        } catch (error) {
          this.logout()
        }
      }
    },

    async fetchUser() {
      try {
        const response = await axios.get('/user')
        this.user = response.data
      } catch (error) {
        throw error
      }
    },

    async login(email, password) {
      this.isLoading = true
      try {
        // Get CSRF token first
        await axios.get('/sanctum/csrf-cookie')
        
        const response = await axios.post('/login', {
          email,
          password
        })
        
        this.token = response.data.token
        this.user = response.data.user
        
        localStorage.setItem('token', this.token)
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        
        return response.data
      } catch (error) {
        throw error
      } finally {
        this.isLoading = false
      }
    },

    async register(userData) {
      this.isLoading = true
      try {
        // Get CSRF token first
        await axios.get('/sanctum/csrf-cookie')
        
        const response = await axios.post('/register', userData)
        
        this.token = response.data.token
        this.user = response.data.user
        
        localStorage.setItem('token', this.token)
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        
        return response.data
      } catch (error) {
        throw error
      } finally {
        this.isLoading = false
      }
    },

    async logout() {
      try {
        if (this.token) {
          await axios.post('/logout')
        }
      } catch (error) {
        // Continue with logout even if API call fails
      } finally {
        this.user = null
        this.token = null
        localStorage.removeItem('token')
        delete axios.defaults.headers.common['Authorization']
      }
    }
  }
})