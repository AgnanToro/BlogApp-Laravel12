const { createApp } = Vue;

createApp({
    data() {
        return {
            comments: [],
            loading: true,
            error: null,
            processing: false
        }
    },
    
    mounted() {
        this.fetchComments();
    },
    
    methods: {
        async fetchComments() {
            try {
                this.loading = true;
                const response = await fetch('/api/admin/comments/pending');
                
                if (!response.ok) {
                    throw new Error('Failed to fetch comments');
                }
                
                const data = await response.json();
                this.comments = data || [];
                this.error = null;
            } catch (error) {
                console.error('Error fetching comments:', error);
                this.error = 'Failed to load comments';
            } finally {
                this.loading = false;
            }
        },
        
        async approveComment(commentId) {
            if (!confirm('Apakah Anda yakin ingin menyetujui komentar ini?')) {
                return;
            }
            
            try {
                this.processing = true;
                const response = await fetch(`/api/admin/comments/${commentId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to approve comment');
                }
                
                const result = await response.json();
                
                if (result.success) {
                    // Remove comment from list
                    this.comments = this.comments.filter(comment => comment.id !== commentId);
                    this.showToast('Komentar berhasil disetujui!', 'success');
                } else {
                    throw new Error(result.message || 'Failed to approve comment');
                }
            } catch (error) {
                console.error('Error approving comment:', error);
                this.showToast('Gagal menyetujui komentar', 'error');
            } finally {
                this.processing = false;
            }
        },
        
        async rejectComment(commentId) {
            if (!confirm('Apakah Anda yakin ingin menolak dan menghapus komentar ini?')) {
                return;
            }
            
            try {
                this.processing = true;
                const response = await fetch(`/api/admin/comments/${commentId}/reject`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to reject comment');
                }
                
                const result = await response.json();
                
                if (result.success) {
                    // Remove comment from list
                    this.comments = this.comments.filter(comment => comment.id !== commentId);
                    this.showToast('Komentar berhasil ditolak dan dihapus!', 'success');
                } else {
                    throw new Error(result.message || 'Failed to reject comment');
                }
            } catch (error) {
                console.error('Error rejecting comment:', error);
                this.showToast('Gagal menolak komentar', 'error');
            } finally {
                this.processing = false;
            }
        },
        
        showToast(message, type = 'info') {
            // Simple toast notification
            const toast = document.createElement('div');
            toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            toast.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                    ${message}
                </div>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        },
        
        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }
}).mount('#comment-approval-app');
