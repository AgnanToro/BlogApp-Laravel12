@extends('layouts.admin')

@section('title', 'Kelola Komentar - BlogSpace')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-modern">
                <div class="card-body">
                    <div id="comment-approval-app" class="comment-approval-app">
                        <!-- Header inside Vue app -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0 fw-bold text-dark">
                                <i class="fas fa-comments me-2 text-primary"></i>Kelola Komentar
                            </h4>
                            <button v-on:click="fetchComments" class="btn btn-admin-primary" v-bind:disabled="loading">
                                <i class="fas fa-sync-alt me-2" v-bind:class="{'fa-spin': loading}"></i>Refresh
                            </button>
                        </div>
                        <!-- Loading State -->
                        <div v-show="loading" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="mt-2 text-muted">Memuat komentar...</div>
                        </div>

                        <!-- Error State -->
                        <div v-show="error" class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <span v-text="error"></span>
                            <button v-on:click="fetchComments" class="btn btn-sm btn-outline-danger ms-2">
                                <i class="fas fa-redo"></i> Coba Lagi
                            </button>
                        </div>

                        <!-- Comments Table -->
                        <div v-show="!loading && !error">
                            <div v-show="comments.length === 0" class="text-center py-4 text-muted">
                                <i class="fas fa-comments fa-3x mb-3 text-secondary opacity-50"></i>
                                <br>Tidak ada komentar yang menunggu persetujuan.
                            </div>

                            <div v-show="comments.length > 0" class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Komentator</th>
                                            <th>Komentar</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(comment, index) in comments" v-bind:key="comment.id">
                                            <td class="fw-bold text-muted">#<span v-text="comment.id"></span></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                    <span class="fw-semibold" v-text="comment.nama_komentator"></span>
                                                </div>
                                            </td>
                                            <td class="text-muted">
                                                <span v-if="comment.isi_komentar.length <= 80" v-text="comment.isi_komentar"></span>
                                                <span v-else>
                                                    <span v-text="comment.isi_komentar.substring(0, 80) + '...'"></span>
                                                    <button class="btn btn-link btn-sm p-0" 
                                                            data-bs-toggle="modal" 
                                                            v-bind:data-bs-target="'#commentModal' + comment.id">
                                                        Baca selengkapnya...
                                                    </button>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-clock me-1"></i>
                                                    Pending
                                                </span>
                                            </td>
                                            <td class="text-muted">
                                                <small v-text="formatDate(comment.created_at)"></small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button v-on:click="approveComment(comment.id)" 
                                                            v-bind:disabled="processing"
                                                            class="btn btn-outline-success btn-sm"
                                                            title="Setujui komentar">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    
                                                    <button v-on:click="rejectComment(comment.id)" 
                                                            v-bind:disabled="processing"
                                                            class="btn btn-outline-danger btn-sm"
                                                            title="Tolak dan hapus komentar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Modal untuk menampilkan komentar lengkap -->
                        <div v-for="comment in comments" v-bind:key="'modal-' + comment.id" 
                             class="modal fade" v-bind:id="'commentModal' + comment.id" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            Komentar dari <span v-text="comment.nama_komentator"></span>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><span v-text="comment.isi_komentar"></span></p>
                                        <hr>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            <span v-text="formatDate(comment.created_at)"></span>
                                        </small>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button v-on:click="approveComment(comment.id)" 
                                                v-bind:disabled="processing"
                                                class="btn btn-success" 
                                                data-bs-dismiss="modal">
                                            <i class="fas fa-check me-1"></i>
                                            Setujui
                                        </button>
                                        <button v-on:click="rejectComment(comment.id)" 
                                                v-bind:disabled="processing"
                                                class="btn btn-danger" 
                                                data-bs-dismiss="modal">
                                            <i class="fas fa-trash me-1"></i>
                                            Tolak & Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-sm {
    width: 2.25rem;
    height: 2.25rem;
    font-size: 0.875rem;
}

.comment-approval-app .btn-group-sm .btn {
    border-radius: 0.375rem;
    margin-right: 0.5rem;
}

.comment-approval-app .btn-group-sm .btn:last-child {
    margin-right: 0;
}

.comment-approval-app .table th {
    border-top: none;
    font-weight: 600;
    color: #374151;
    background-color: #f9fafb;
}

.comment-approval-app .table td {
    vertical-align: middle;
    border-color: #e5e7eb;
}

.comment-approval-app .badge {
    font-size: 0.75rem;
    font-weight: 500;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/comment-approval.js') }}"></script>
@endpush
