@extends('layouts.app')

@section('title', 'Surat Keluar')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-send"></i> Surat Keluar</h2>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <i class="bi bi-funnel"></i> Filter
            </button>
            <a href="{{ route('surat.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Kirim Surat Baru
            </a>
        </div>
    </div>
    
    <!-- Filter Section (sama seperti sebelumnya) -->
    <div class="collapse {{ request()->hasAny(['jenis_surat', 'sifat_surat', 'status', 'penerima_id', 'tanggal_dari', 'tanggal_sampai', 'search']) ? 'show' : '' }}" id="filterCollapse">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="bi bi-funnel"></i> Filter Surat Keluar</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('surat.keluar') }}">
                    <div class="row">
                        <!-- Search -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cari Surat</label>
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Cari berdasarkan perihal atau nomor surat..." 
                                   value="{{ request('search') }}">
                        </div>
                        
                        <!-- Jenis Surat -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Jenis Surat</label>
                            <select class="form-select" name="jenis_surat">
                                <option value="">Semua Jenis</option>
                                <option value="informasi" {{ request('jenis_surat') == 'informasi' ? 'selected' : '' }}>
                                    Informasi
                                </option>
                                <option value="pertanyaan" {{ request('jenis_surat') == 'pertanyaan' ? 'selected' : '' }}>
                                    Pertanyaan
                                </option>
                                <option value="permintaan" {{ request('jenis_surat') == 'permintaan' ? 'selected' : '' }}>
                                    Permintaan
                                </option>
                                <option value="lainnya" {{ request('jenis_surat') == 'lainnya' ? 'selected' : '' }}>
                                    Lainnya
                                </option>
                            </select>
                        </div>
                        
                        <!-- Sifat Surat -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Sifat Surat</label>
                            <select class="form-select" name="sifat_surat">
                                <option value="">Semua Sifat</option>
                                <option value="biasa" {{ request('sifat_surat') == 'biasa' ? 'selected' : '' }}>
                                    Biasa
                                </option>
                                <option value="rahasia" {{ request('sifat_surat') == 'rahasia' ? 'selected' : '' }}>
                                    Rahasia
                                </option>
                                <option value="sangat_rahasia" {{ request('sifat_surat') == 'sangat_rahasia' ? 'selected' : '' }}>
                                    Sangat Rahasia
                                </option>
                            </select>
                        </div>
                        
                        <!-- Status Pembacaan -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Status Pembacaan</label>
                            <select class="form-select" name="status">
                                <option value="">Semua Status</option>
                                <option value="belum_dibaca" {{ request('status') == 'belum_dibaca' ? 'selected' : '' }}>
                                    Belum Dibaca Sama Sekali
                                </option>
                                <option value="sebagian_dibaca" {{ request('status') == 'sebagian_dibaca' ? 'selected' : '' }}>
                                    Sebagian Sudah Dibaca
                                </option>
                                <option value="semua_dibaca" {{ request('status') == 'semua_dibaca' ? 'selected' : '' }}>
                                    Semua Sudah Dibaca
                                </option>
                            </select>
                        </div>
                        
                        <!-- Penerima -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Penerima</label>
                            <select class="form-select" name="penerima_id">
                                <option value="">Semua Penerima</option>
                                @foreach($penerimaList as $penerima)
                                    <option value="{{ $penerima->id }}" {{ request('penerima_id') == $penerima->id ? 'selected' : '' }}>
                                        {{ $penerima->nama_kantor }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Tanggal Dari -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tanggal Dari</label>
                            <input type="date" class="form-control" name="tanggal_dari" 
                                   value="{{ request('tanggal_dari') }}">
                        </div>
                        
                        <!-- Tanggal Sampai -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tanggal Sampai</label>
                            <input type="date" class="form-control" name="tanggal_sampai" 
                                   value="{{ request('tanggal_sampai') }}">
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('surat.keluar') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Result Count -->
    @if(request()->hasAny(['jenis_surat', 'sifat_surat', 'status', 'penerima_id', 'tanggal_dari', 'tanggal_sampai', 'search']))
    <div class="alert alert-info d-flex justify-content-between align-items-center">
        <span>Menampilkan {{ $suratKeluar->total() }} surat keluar dengan filter aktif</span>
        <a href="{{ route('surat.keluar') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-x-circle"></i> Hapus Filter
        </a>
    </div>
    @endif
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">No. Surat</th>
                            <th width="20%">Perihal</th>
                            <th width="10%">Jenis</th>
                            <th width="10%">Sifat</th>
                            <th width="15%">Tanggal</th>
                            <th width="15%">Tujuan</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suratKeluar as $index => $surat)
                        <tr>
                            <td>{{ $suratKeluar->firstItem() + $index }}</td>
                            <td>{{ $surat->nomor_surat }}</td>
                            <td>{{ Str::limit($surat->perihal, 50) }}</td>
                            <td>
                                @if($surat->jenis_surat == 'informasi')
                                    <span class="badge bg-info">Informasi</span>
                                @elseif($surat->jenis_surat == 'pertanyaan')
                                    <span class="badge bg-warning">Pertanyaan</span>
                                @elseif($surat->jenis_surat == 'permintaan')
                                    <span class="badge bg-primary">Permintaan</span>
                                @else
                                    <span class="badge bg-secondary">Lainnya</span>
                                @endif
                            </td>
                            <td>
                                @if($surat->sifat_surat == 'biasa')
                                    <span class="badge bg-success">Biasa</span>
                                @elseif($surat->sifat_surat == 'rahasia')
                                    <span class="badge bg-warning"><i class="bi bi-shield-lock"></i> Rahasia</span>
                                @elseif($surat->sifat_surat == 'sangat_rahasia')
                                    <span class="badge bg-danger"><i class="bi bi-shield-fill-exclamation"></i> Sangat Rahasia</span>
                                @endif
                            </td>
                            <td>{{ $surat->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @php
                                    $jumlahTujuan = $surat->penerimas->count();
                                @endphp
                                @if($jumlahTujuan >= 40)
                                    <span class="badge bg-primary">Semua Kantor</span>
                                @elseif($jumlahTujuan > 5)
                                    <span class="badge bg-info">{{ $jumlahTujuan }} Kantor</span>
                                @else
                                    @foreach($surat->penerimas->take(3) as $penerima)
                                        <small>{{ $penerima->kantorCabang->kode_kantor }}</small>@if(!$loop->last), @endif
                                    @endforeach
                                    @if($jumlahTujuan > 3)
                                        <small>...</small>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('surat.show', $surat->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete({{ $surat->id }}, '{{ $surat->nomor_surat }}')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                @if(request()->hasAny(['jenis_surat', 'sifat_surat', 'status', 'penerima_id', 'tanggal_dari', 'tanggal_sampai', 'search']))
                                    Tidak ada surat keluar yang sesuai dengan filter
                                @else
                                    Belum ada surat keluar
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $suratKeluar->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Delete -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Surat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle-fill"></i> <strong>Perhatian!</strong>
                    <p class="mb-0 mt-2">Anda akan menghapus surat dengan nomor: <strong id="deleteNomorSurat"></strong></p>
                    <p class="mb-0 mt-2">Tindakan ini akan:</p>
                    <ul class="mb-0">
                        <li>Menghapus surat dari sistem secara permanen</li>
                        <li>Menghapus surat dari surat masuk semua penerima</li>
                        <li>Menghapus semua balasan terkait (jika ada)</li>
                        <li>Menghapus file lampiran (jika ada)</li>
                    </ul>
                    <p class="mb-0 mt-2"><strong>Tindakan ini tidak dapat dibatalkan!</strong></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="bi bi-trash"></i> Ya, Hapus Surat
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let suratIdToDelete = null;

function confirmDelete(id, nomorSurat) {
    suratIdToDelete = id;
    document.getElementById('deleteNomorSurat').textContent = nomorSurat;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (suratIdToDelete) {
        // Disable button dan show loading
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghapus...';
        
        // Kirim request delete
        fetch(`/surat/${suratIdToDelete}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Tampilkan pesan sukses
                showAlert('success', data.message);
                
                // Tutup modal
                const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                deleteModal.hide();
                
                // Reload halaman setelah 1.5 detik
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                // Tampilkan pesan error
                showAlert('danger', data.message);
                
                // Reset button
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-trash"></i> Ya, Hapus Surat';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Terjadi kesalahan. Silakan coba lagi.');
            
            // Reset button
            this.disabled = false;
            this.innerHTML = '<i class="bi bi-trash"></i> Ya, Hapus Surat';
        });
    }
});

function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Insert alert at the top of the page
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}
</script>
@endsection