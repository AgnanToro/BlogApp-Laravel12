

<?php $__env->startSection('title', 'Debug Vue.js Comments'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Debug Vue.js Comments</h3>
                </div>
                <div class="card-body">
                    <div id="comment-approval-app">
                        <h4>Debug Info:</h4>
                        <p>Loading: <span v-text="loading"></span></p>
                        <p>Error: <span v-text="error"></span></p>
                        <p>Comments Length: <span v-text="comments.length"></span></p>
                        
                        <hr>
                        
                        <button v-on:click="testFetch" class="btn btn-primary">Test Fetch API</button>
                        
                        <hr>
                        
                        <!-- Loading State -->
                        <div v-show="loading" class="alert alert-info">
                            <i class="fas fa-spinner fa-spin me-2"></i>
                            Memuat komentar...
                        </div>

                        <!-- Error State -->
                        <div v-show="error" class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span v-text="error"></span>
                            <button v-on:click="fetchComments" class="btn btn-sm btn-outline-danger ms-2">
                                <i class="fas fa-redo"></i> Coba Lagi
                            </button>
                        </div>

                        <!-- Comments List -->
                        <div v-show="!loading && !error && comments.length > 0">
                            <h5>Daftar Komentar:</h5>
                            <ul class="list-group">
                                <li v-for="comment in comments" v-bind:key="comment.id" class="list-group-item">
                                    <strong><span v-text="comment.nama_komentator"></span></strong>: 
                                    <span v-text="comment.isi_komentar"></span>
                                </li>
                            </ul>
                        </div>

                        <!-- No Comments -->
                        <div v-show="!loading && !error && comments.length === 0" class="alert alert-info">
                            Tidak ada komentar yang menunggu persetujuan.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
console.log('Starting Vue.js app...');

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
        console.log('Vue.js mounted, fetching comments...');
        this.fetchComments();
    },
    
    methods: {
        async testFetch() {
            console.log('Testing fetch...');
            try {
                const response = await fetch('/api/admin/comments/pending');
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                const data = await response.json();
                console.log('Response data:', data);
                alert('Check console for details');
            } catch (error) {
                console.error('Fetch error:', error);
                alert('Error: ' + error.message);
            }
        },
        
        async fetchComments() {
            console.log('fetchComments called...');
            try {
                this.loading = true;
                this.error = null;
                
                console.log('Fetching from: /api/admin/comments/pending');
                const response = await fetch('/api/admin/comments/pending');
                
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status + ': ' + response.statusText);
                }
                
                const data = await response.json();
                console.log('Received data:', data);
                console.log('Data type:', typeof data);
                console.log('Data length:', data.length);
                
                this.comments = data || [];
                console.log('Comments set to:', this.comments);
                
                this.error = null;
            } catch (error) {
                console.error('Error fetching comments:', error);
                this.error = 'Failed to load comments: ' + error.message;
            } finally {
                this.loading = false;
                console.log('Loading set to false');
            }
        }
    }
}).mount('#comment-approval-app');

console.log('Vue.js app created');
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\NGODING\Dafidea - testcase\resources\views/admin/comments/debug-vue.blade.php ENDPATH**/ ?>