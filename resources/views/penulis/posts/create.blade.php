@extends('layouts.penulis')

@section('title', 'Tambah Post Baru - Penulis BlogSpace')
@section('page-title', 'Tambah Post Baru')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('penulis.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('penulis.posts') }}">Kelola Posts</a></li>
        <li class="breadcrumb-item active">Tambah Post Baru</li>
    </ol>
</nav>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-modern">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-plus-circle text-primary me-2"></i>Form Tambah Post
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('penulis.posts.store') }}" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-image me-2 text-primary"></i>Foto Artikel
                        </label>
                        <div class="mb-2">
                            <input 
                                type="file" 
                                name="foto" 
                                accept="image/*" 
                                class="form-control @error('foto') is-invalid @enderror"
                                id="foto-input"
                            >
                            <div id="foto-preview-container" class="mt-2" style="display:none">
                                <img id="foto-preview" src="#" alt="Preview" style="max-width:200px; max-height:200px; border-radius:8px; border:1px solid #e5e7eb; box-shadow:0 2px 8px rgba(0,0,0,0.05);" />
                            </div>
                        </div>
                        @error('foto')
                            <div class="invalid-feedback d-block">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                    @csrf
                    
                    <div class="mb-4">
                        <label for="judul" class="form-label fw-semibold">
                            <i class="fas fa-heading me-2 text-primary"></i>Judul Post
                        </label>
                        <input type="text" 
                               class="form-control form-control-lg @error('judul') is-invalid @enderror" 
                               id="judul" 
                               name="judul" 
                               value="{{ old('judul') }}" 
                               placeholder="Masukkan judul yang menarik..."
                               required>
                        @error('judul')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">Gunakan judul yang menarik dan SEO-friendly.</div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="konten" class="form-label fw-semibold">
                            <i class="fas fa-align-left me-2 text-primary"></i>Konten Post
                        </label>
                        <textarea class="form-control @error('konten') is-invalid @enderror" 
                                  id="konten" 
                                  name="konten" 
                                  rows="12" 
                                  placeholder="Tulis konten post yang menarik dan informatif..."
                                  required>{{ old('konten') }}</textarea>
                        @error('konten')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">Gunakan format markdown untuk styling yang lebih baik.</div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('penulis.posts') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <div>
                            <button type="reset" class="btn btn-outline-warning me-2">
                                <i class="fas fa-undo me-2"></i>Reset
                            </button>
                            <button type="submit" class="btn btn-penulis-primary">
                                <i class="fas fa-save me-2"></i>Simpan Post
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card card-modern mb-4">
            <div class="card-header bg-transparent border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-info-circle text-info me-2"></i>Informasi
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-start mb-3">
                    <i class="fas fa-clock text-primary me-3 mt-1"></i>
                    <div>
                        <h6 class="mb-1">Tanggal Publikasi</h6>
                        <p class="text-muted mb-0 small">Akan otomatis diisi saat post disimpan</p>
                    </div>
                </div>
                <div class="d-flex align-items-start mb-3">
                    <i class="fas fa-user text-success me-3 mt-1"></i>
                    <div>
                        <h6 class="mb-1">Author</h6>
                        <p class="text-muted mb-0 small">{{ Auth::user()->name }}</p>
                    </div>
                </div>
                <div class="d-flex align-items-start">
                    <i class="fas fa-eye text-warning me-3 mt-1"></i>
                    <div>
                        <h6 class="mb-1">Status</h6>
                        <p class="text-muted mb-0 small">Akan langsung dipublikasikan</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-modern">
            <div class="card-header bg-transparent border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-lightbulb text-warning me-2"></i>Tips Menulis
                </h6>
            </div>
            <div class="card-body">
                <div class="small">
                    <div class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Gunakan judul yang menarik
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Tulis pembukaan yang engaging
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Gunakan paragraf pendek
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Sertakan call-to-action
                    </div>
                    <div>
                        <i class="fas fa-check text-success me-2"></i>
                        Proofread sebelum publish
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('foto-input');
        const previewContainer = document.getElementById('foto-preview-container');
        const preview = document.getElementById('foto-preview');
        if(input) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if(file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        preview.src = ev.target.result;
                        previewContainer.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.src = '#';
                    previewContainer.style.display = 'none';
                }
            });
        }
    });
</script>
@endpush
