import { defineStore } from 'pinia'
import axios from 'axios'

export const usePostsStore = defineStore('posts', {
  state: () => ({
    posts: [],
    post: null,
    comments: [],
    isLoading: false,
    pagination: {
      current_page: 1,
      last_page: 1,
      per_page: 6,
      total: 0
    }
  }),

  actions: {
    // Hapus post milik user (penulis)
    async deletePost(id) {
      this.isLoading = true
      try {
        // Untuk penulis, gunakan endpoint khusus agar tidak 404
        await axios.delete(`/my-posts/${id}`)
        this.posts = this.posts.filter(p => p.id !== id)
        return true
      } catch (error) {
        throw error
      } finally {
        this.isLoading = false
      }
    },
    // Ambil post milik user yang sedang login (penulis) dengan pagination
    async fetchMyPosts(page = 1, perPage = 5) {
      this.isLoading = true
      try {
        const response = await axios.get(`/my-posts?page=${page}&per_page=${perPage}`)
        // Laravel pagination response
        if (response.data && Array.isArray(response.data.data)) {
          this.posts = response.data.data;
          this.pagination = {
            current_page: response.data.current_page,
            last_page: response.data.last_page,
            per_page: response.data.per_page ?? perPage,
            total: response.data.total,
            next_page_url: response.data.next_page_url,
            prev_page_url: response.data.prev_page_url
          };
        } else {
          // fallback: treat as array
          this.posts = Array.isArray(response.data) ? response.data : [];
          this.pagination = {
            current_page: 1,
            last_page: 1,
            per_page: this.posts.length,
            total: this.posts.length,
            next_page_url: null,
            prev_page_url: null
          };
        }
        return this.posts;
      } catch (error) {
        this.posts = [];
        throw error
      } finally {
        this.isLoading = false
      }
    },
    async fetchPosts(page = 1, perPage = 6) {
      this.isLoading = true
      try {
        const response = await axios.get(`/posts?page=${page}&per_page=${perPage}`)
        // Laravel pagination response
        if (response.data && Array.isArray(response.data.data)) {
          this.posts = response.data.data;
          this.pagination = {
            current_page: response.data.current_page,
            last_page: response.data.last_page,
            per_page: response.data.per_page ?? perPage,
            total: response.data.total,
            next_page_url: response.data.next_page_url,
            prev_page_url: response.data.prev_page_url
          };
        } else {
          // fallback: treat as array
          this.posts = Array.isArray(response.data) ? response.data : [];
          this.pagination = {
            current_page: 1,
            last_page: 1,
            per_page: this.posts.length,
            total: this.posts.length,
            next_page_url: null,
            prev_page_url: null
          };
        }
      } catch (error) {
        throw error
      } finally {
        this.isLoading = false
      }
    },

    async fetchPost(id) {
      this.isLoading = true
      try {
        const response = await axios.get(`/posts/${id}`)
        // API response: { success: true, data: {...} }
        if (response.data && response.data.data) {
          this.post = response.data.data;
          return response.data.data;
        } else {
          this.post = null;
          return null;
        }
      } catch (error) {
        throw error
      } finally {
        this.isLoading = false
      }
    },

    async createPost(postData) {
      this.isLoading = true
      try {
        const response = await axios.post('/posts', postData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        return response.data
      } catch (error) {
        throw error
      } finally {
        this.isLoading = false
      }
    },

    async updatePost(id, postData) {
      this.isLoading = true
      try {
        const response = await axios.post(`/posts/${id}`, postData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        return response.data
      } catch (error) {
        throw error
      } finally {
        this.isLoading = false
      }
    },

    // (duplikat dihapus)

    async fetchComments(postId) {
      try {
        const response = await axios.get(`/posts/${postId}/comments`)
        this.comments = response.data
      } catch (error) {
        throw error
      }
    },

    async createComment(postId, commentData) {
      try {
        const response = await axios.post(`/posts/${postId}/comments`, commentData)
        this.comments.push(response.data)
        return response.data
      } catch (error) {
        throw error
      }
    },

    async approveComment(commentId) {
      try {
        const response = await axios.patch(`/comments/${commentId}/approve`)
        const commentIndex = this.comments.findIndex(c => c.id === commentId)
        if (commentIndex !== -1) {
          this.comments[commentIndex] = response.data
        }
        return response.data
      } catch (error) {
        throw error
      }
    },

    async deleteComment(commentId) {
      try {
        await axios.delete(`/comments/${commentId}`)
        this.comments = this.comments.filter(comment => comment.id !== commentId)
      } catch (error) {
        throw error
      }
    }
  }
})