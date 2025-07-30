@extends('layouts.app')

@section('title', 'Profil')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4"><i class="bi bi-person-gear"></i> Profil Pengguna</h2>
    
    @if(!auth()->user()->password_changed)
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle"></i> <strong>Perhatian!</strong> Anda harus mengubah password default untuk melanjutkan menggunakan sistem.
    </div>
    @endif
    
    <div class="row">
        <!-- Update Profil -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person"></i> Informasi Profil</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Kantor Cabang</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->kantorCabang->nama_kantor }}" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Kode Kantor</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->kantorCabang->kode_kantor }}" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Update Password -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header {{ !auth()->user()->password_changed ? 'bg-danger' : 'bg-warning' }} text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-key"></i> Ubah Password
                        @if(!auth()->user()->password_changed)
                            <span class="badge bg-white text-danger ms-2">Wajib Diubah</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Lama <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(!auth()->user()->password_changed)
                                <small class="text-muted">Password default: password123</small>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Password harus:
                                <ul class="mb-0">
                                    <li>Minimal 8 karakter</li>
                                    <li>Mengandung huruf besar dan kecil</li>
                                    <li>Mengandung angka</li>
                                    <li>Mengandung simbol (!@#$%^&*)</li>
                                </ul>
                            </small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>
                        
                        <button type="submit" class="btn {{ !auth()->user()->password_changed ? 'btn-danger' : 'btn-warning' }}">
                            <i class="bi bi-key"></i> {{ !auth()->user()->password_changed ? 'Ubah Password Sekarang' : 'Update Password' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Info Akun -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informasi Akun</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Tanggal Registrasi:</strong> {{ auth()->user()->created_at->format('d F Y H:i') }}</p>
                    <p><strong>Terakhir Update:</strong> {{ auth()->user()->updated_at->format('d F Y H:i') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Status Password:</strong> 
                        @if(auth()->user()->password_changed)
                            <span class="badge bg-success">Sudah Diubah</span>
                        @else
                            <span class="badge bg-danger">Belum Diubah (Default)</span>
                        @endif
                    </p>
                    <p><strong>Alamat Kantor:</strong> {{ auth()->user()->kantorCabang->alamat }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection