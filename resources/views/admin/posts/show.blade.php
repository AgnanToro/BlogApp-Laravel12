@extends('layouts.admin')

@section('title', 'View Post: ' . $post->judul)

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1>{{ $post->judul }}</h1>
            <div>
                <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $post->judul }}</h5>
                <p class="text-muted">
                    <small>Dipublikasikan: {{ $post->tanggal_post->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }}</small>
                </p>
                <div class="card-text">
                    {!! nl2br(e($post->konten)) !!}
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Informasi Post</h5>
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> {{ $post->id }}</p>
                <p><strong>Tanggal Post:</strong> {{ $post->tanggal_post->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }}</p>
                <p><strong>Dibuat:</strong> {{ $post->created_at->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }}</p>
                <p><strong>Diupdate:</strong> {{ $post->updated_at->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }}</p>
                <p><strong>Total Komentar:</strong> {{ $post->comments->count() }}</p>
            </div>
        </div>
        
        @if($post->comments->count() > 0)
        <div class="card mt-3">
            <div class="card-header">
                <h5>Komentar</h5>
            </div>
            <div class="card-body" id="admin-comments-list">
                @foreach($post->comments as $comment)
                    <div class="d-flex justify-content-between align-items-start border-bottom py-2" id="comment-{{ $comment->id }}">
                        <div>
                            <strong>{{ $comment->nama_komentator }}</strong>
                            <div class="text-xs text-muted">{{ $comment->created_at->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }}</div>
                            <div>{{ $comment->isi_komentar }}</div>
                        </div>
                        <button class="btn btn-sm btn-danger btn-delete-comment" data-id="{{ $comment->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <div class="card mt-3">
            <div class="card-header">
                <h5>Action</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('posts.show', $post) }}" class="btn btn-success btn-sm" target="_blank">Lihat di Public</a>
                <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-warning btn-sm">Edit Post</a>
                <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" 
                      style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus post ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete Post</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-delete-comment').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if(confirm('Yakin hapus komentar ini?')) {
                var commentId = this.getAttribute('data-id');
                fetch('/admin/comments/' + commentId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async res => {
                    if (!res.ok) {
                        let msg = 'Gagal menghapus komentar';
                        try { const data = await res.json(); if(data.message) msg = data.message; } catch(e){}
                        alert(msg);
                        return;
                    }
                    return res.json();
                })
                .then(data => {
                    if(data && data.success) {
                        document.getElementById('comment-' + commentId).remove();
                    }
                })
                .catch(function(err) {
                    alert('Error JS: ' + err);
                });
            }
        });
    });
});
</script>
@endpush
@endsection
