@extends('layouts.penulis')

@section('title', 'Profile - Penulis BlogSpace')
@section('page-title', 'Profile')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Profile Penulis</h1>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Edit Profile</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('penulis.profile.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru (Opsional)</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation">
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('penulis.dashboard') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Informasi Account</h5>
            </div>
            <div class="card-body">
                <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                <p><strong>Bergabung:</strong> {{ $user->created_at->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') }}</p>
                <p><strong>Last Update:</strong> {{ $user->updated_at->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }}</p>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5>Statistik</h5>
            </div>
            <div class="card-body">
                <p><strong>Total Posts:</strong> {{ $user->posts->count() }}</p>
                <p><strong>Total Comments:</strong> 
                    {{ $user->posts->sum(function($post) { return $post->comments->count(); }) }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
