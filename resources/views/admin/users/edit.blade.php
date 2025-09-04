@extends('layouts.admin')

@section('title', 'Edit User - BlogSpace')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-modern">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm me-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h4 class="card-title mb-0 fw-bold text-dark">
                            <i class="fas fa-user-edit me-2 text-primary"></i>Edit User: {{ $user->name }}
                        </h4>
                    </div>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Terdapat kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="name" class="form-label fw-semibold">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $user->name) }}"
                                           class="form-control @error('name') is-invalid @enderror"
                                           placeholder="Masukkan nama lengkap"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="email" class="form-label fw-semibold">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}"
                                           class="form-control @error('email') is-invalid @enderror"
                                           placeholder="Masukkan alamat email"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="password" class="form-label fw-semibold">
                                        Password Baru <span class="text-muted"></span>
                                    </label>
                                    <input type="password" 
                                           id="password" 
                                           name="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="Masukkan password baru">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label fw-semibold">
                                        Konfirmasi Password Baru
                                    </label>
                                    <input type="password" 
                                           id="password_confirmation" 
                                           name="password_confirmation"
                                           class="form-control"
                                           placeholder="Konfirmasi password baru">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="form-label fw-semibold">
                                Role <span class="text-danger">*</span>
                            </label>
                            <select id="role" 
                                    name="role" 
                                    class="form-select @error('role') is-invalid @enderror"
                                    required>
                                <option value="">Pilih Role</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                    <i class="fas fa-user-shield"></i> Admin
                                </option>
                                <option value="penulis" {{ old('role', $user->role) === 'penulis' ? 'selected' : '' }}>
                                    <i class="fas fa-user-edit"></i> Penulis
                                </option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <div class="form-text">
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <div class="p-3 bg-danger bg-opacity-10 rounded">
                                            <strong class="text-danger"><i class="fas fa-user-shield me-1"></i> Admin:</strong><br>
                                            <small class="text-muted">Akses penuh untuk mengelola semua konten dan user</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 bg-info bg-opacity-10 rounded">
                                            <strong class="text-info"><i class="fas fa-user-edit me-1"></i> Penulis:</strong><br>
                                            <small class="text-muted">Dapat menulis dan mengelola post sendiri</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Info User -->
                        <div class="mb-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title fw-bold mb-3">
                                        <i class="fas fa-info-circle me-2 text-info"></i>Informasi User
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">ID User</small>
                                            <strong>#{{ $user->id }}</strong>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Terdaftar</small>
                                            <strong>{{ $user->created_at->format('d M Y H:i') }}</strong>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Terakhir Update</small>
                                            <strong>{{ $user->updated_at->format('d M Y H:i') }}</strong>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Status Email</small>
                                            @if($user->email_verified_at)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Terverifikasi
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>Belum Terverifikasi
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-admin-primary">
                                <i class="fas fa-save me-2"></i>Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
