@extends('layouts.admin')

@section('title', 'Kelola User - BlogSpace')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-modern">
                <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                    <h4 class="card-title mb-0 fw-bold text-dark">
                        <i class="fas fa-users me-2 text-primary"></i>Kelola User
                    </h4>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-admin-primary">
                        <i class="fas fa-plus me-2"></i>Tambah User
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Terdaftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td class="fw-bold text-muted">#{{ $user->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                                <span class="fw-semibold">{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-muted">{{ $user->email }}</td>
                                        <td>
                                            <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-info' }}">
                                                <i class="fas {{ $user->role === 'admin' ? 'fa-user-shield' : 'fa-user-edit' }} me-1"></i>
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="text-muted">
                                            <small>{{ $user->created_at->format('d M Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.users.edit', $user) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($user->id !== auth()->id())
                                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                                            onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="fas fa-users fa-3x mb-3 text-secondary opacity-50"></i>
                                            <br>Tidak ada user yang ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($users, 'links'))
                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus user <strong id="userName"></strong>?</p>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus User</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteUser(userId, userName) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('deleteForm').action = `/admin/users/${userId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush
