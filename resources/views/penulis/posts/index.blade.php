@extends('layouts.penulis')

@section('title', 'Kelola Posts - Penulis BlogSpace')
@section('page-title', 'Kelola Posts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Kelola Posts</h2>
    <a href="{{ route('penulis.posts.create') }}" class="btn btn-penulis-primary">
        <i class="fas fa-plus me-2"></i>Tambah Post Baru
    </a>
</div>

<div class="card card-modern">
    <div class="card-body">
        @if($posts->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Judul</th>
                            <th>Tanggal Post</th>
                            <th>Komentar</th>
                            <th width="200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td>{{ $post->id }}</td>
                                <td>
                                    <strong>{{ Str::limit($post->judul, 50) }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($post->konten, 80) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $post->tanggal_post->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') }}</span>
                                    <br>
                                    <small class="text-muted">{{ $post->tanggal_post->setTimezone('Asia/Jakarta')->translatedFormat('H:i') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $post->comments->count() }} komentar</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" style="gap:0.4rem;">
                                        <a href="{{ route('penulis.posts.edit', $post) }}" class="btn btn-sm btn-outline-warning" style="margin-right:2px;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-outline-success" target="_blank" style="margin-right:2px;">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <form method="POST" action="{{ route('penulis.posts.destroy', $post) }}" 
                                              style="display: inline; margin:0;" 
                                              onsubmit="return confirm('Yakin ingin menghapus post ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <style>
                .custom-pagination .pagination {
                    gap: 0.5rem;
                }
                .custom-pagination .page-link {
                    border: none;
                    border-radius: 0.75rem;
                    background: #fff;
                    color: #222;
                    font-weight: 500;
                    min-width: 2.5rem;
                    min-height: 2.5rem;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    box-shadow: none;
                    transition: background 0.2s, color 0.2s;
                }
                .custom-pagination .page-item.active .page-link {
                    background: #2563eb;
                    color: #fff;
                    font-weight: bold;
                }
                .custom-pagination .page-item.disabled .page-link {
                    background: #f5f5f5;
                    color: #bbb;
                }
                .custom-pagination .page-link:hover {
                    background: #2563eb;
                    color: #fff;
                }
            </style>
            <div class="d-flex justify-content-center mt-4">
                {{ $posts->links('vendor.pagination.custom') }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-newspaper text-muted mb-3" style="font-size: 4rem;"></i>
                <h4 class="fw-bold">Belum Ada Posts</h4>
                <p class="text-muted mb-4">Mulai dengan membuat post pertama Anda.</p>
                <a href="{{ route('penulis.posts.create') }}" class="btn btn-penulis-primary">
                    <i class="fas fa-plus me-2"></i>Buat Post Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
