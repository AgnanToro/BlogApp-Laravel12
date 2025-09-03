@extends('layouts.admin')

@section('title', 'Edit Post: ' . $post->judul)

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Edit Post: {{ $post->judul }}</h1>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                               id="judul" name="judul" value="{{ old('judul', $post->judul) }}" required>
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto Artikel</label>
                        @if($post->foto)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $post->foto) }}" alt="Foto saat ini" class="img-thumbnail" style="max-width: 200px;">
                                <small class="text-muted d-block">Foto saat ini</small>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                               id="foto" name="foto" accept="image/*">
                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Upload foto baru untuk mengganti foto lama (opsional).</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="konten" class="form-label">Konten</label>
                        <textarea class="form-control @error('konten') is-invalid @enderror" 
                                  id="konten" name="konten" rows="10" required>{{ old('konten', $post->konten) }}</textarea>
                        @error('konten')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Update Post</button>
                    </div>
                </form>
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
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5>Action</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.posts.show', $post) }}" class="btn btn-info btn-sm">View Post</a>
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
@endsection
