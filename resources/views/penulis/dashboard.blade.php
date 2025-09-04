@extends('layouts.penulis')

@section('title', 'Dashboard - Penulis BlogSpace')
@section('page-title', 'Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-modern border-0 shadow-sm" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-left: 4px solid #2563eb !important;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="fw-bold mb-2 text-primary">Selamat Datang, {{ auth()->user()->name }}!</h2>
                        <p class="mb-0 text-muted">Tulis dan kelola artikel Anda dengan mudah di Penulis BlogSpace</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <i class="fas fa-chart-line text-primary" style="font-size: 3rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card card-modern border-0 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h6 class="text-muted mb-1">Total Posts</h6>
                        <h2 class="fw-bold text-primary mb-0">{{ $totalPosts }}</h2>
                        <small class="text-success">
                            <i class="fas fa-arrow-up me-1"></i>Artikel Anda
                        </small>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon-circle bg-primary bg-opacity-10">
                            <i class="fas fa-newspaper text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-3">
        <div class="card card-modern border-0 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h6 class="text-muted mb-1">Total Komentar</h6>
                        <h2 class="fw-bold text-success mb-0">{{ $totalComments }}</h2>
                        <small class="text-info">
                            <i class="fas fa-comments me-1"></i>Engagement aktif
                        </small>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon-circle bg-success bg-opacity-10">
                            <i class="fas fa-comments text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-modern">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0 fw-bold">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('penulis.posts.create') }}" class="btn btn-penulis-primary w-100 py-3">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                            <div>Tulis Post Baru</div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('penulis.posts') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-list fa-2x mb-2"></i>
                            <div>Kelola Posts</div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('posts.index') }}" class="btn btn-outline-success w-100 py-3" target="_blank">
                            <i class="fas fa-globe fa-2x mb-2"></i>
                            <div>Lihat Website</div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <form method="POST" action="{{ route('admin.logout') }}" class="w-100">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100 py-3">
                                <i class="fas fa-sign-out-alt fa-2x mb-2"></i>
                                <div>Logout</div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Posts -->
<div class="row">
    <div class="col-12">
        <div class="card card-modern">
            <div class="card-header bg-transparent border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Posts Terbaru</h5>
                    <a href="{{ route('penulis.posts') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($recentPosts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Judul</th>
                                    <th>Tanggal Post</th>
                                    <th>Komentar</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPosts as $post)
                                    <tr>
                                        <td>
                                            <strong>{{ Str::limit($post->judul, 50) }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($post->konten, 70) }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $post->tanggal_post->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') }}</span>
                                            <br>
                                            <small class="text-muted">{{ $post->tanggal_post->setTimezone('Asia/Jakarta')->translatedFormat('H:i') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $post->comments->count() }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('penulis.posts.edit', $post) }}" class="btn btn-outline-warning me-2">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-success" target="_blank">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-newspaper text-muted mb-3" style="font-size: 4rem;"></i>
                        <h5 class="fw-bold">Belum Ada Posts</h5>
                        <p class="text-muted mb-4">Mulai dengan menulis post pertama Anda.</p>
                        <a href="{{ route('penulis.posts.create') }}" class="btn btn-penulis-primary">
                            <i class="fas fa-plus me-2"></i>Tulis Post Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
