@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Dashboard</h2>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-0 bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Total Surat Masuk</h6>
                            <h2 class="mb-0">{{ $totalSuratMasuk }}</h2>
                        </div>
                        <i class="bi bi-inbox-fill" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card border-0 bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Total Surat Keluar</h6>
                            <h2 class="mb-0">{{ $totalSuratKeluar }}</h2>
                        </div>
                        <i class="bi bi-send-fill" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card border-0 bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Belum Dibaca</h6>
                            <h2 class="mb-0">{{ $suratBelumDibaca }}</h2>
                        </div>
                        <i class="bi bi-envelope-exclamation-fill" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Letters -->
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Surat Terbaru</h5>
                <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                    <i class="bi bi-funnel"></i> Filter
                </button>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="collapse" id="filterCollapse">
            <div class="card-body border-bottom bg-light">
                <form method="GET" action="{{ route('dashboard') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="jenis_surat" class="form-label">Jenis Surat</label>
                        <select class="form-select form-select-sm" name="jenis_surat" id="jenis_surat">
                            <option value="">Semua Jenis</option>
                            <option value="informasi" {{ request('jenis_surat') == 'informasi' ? 'selected' : '' }}>Informasi</option>
                            <option value="pertanyaan" {{ request('jenis_surat') == 'pertanyaan' ? 'selected' : '' }}>Pertanyaan</option>
                            <option value="permintaan" {{ request('jenis_surat') == 'permintaan' ? 'selected' : '' }}>Permintaan</option>
                            <option value="lainnya" {{ request('jenis_surat') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="sifat_surat" class="form-label">Sifat Surat</label>
                        <select class="form-select form-select-sm" name="sifat_surat" id="sifat_surat">
                            <option value="">Semua Sifat</option>
                            <option value="biasa" {{ request('sifat_surat') == 'biasa' ? 'selected' : '' }}>Biasa</option>
                            <option value="rahasia" {{ request('sifat_surat') == 'rahasia' ? 'selected' : '' }}>Rahasia</option>
                            <option value="sangat_rahasia" {{ request('sifat_surat') == 'sangat_rahasia' ? 'selected' : '' }}>Sangat Rahasia</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select form-select-sm" name="status" id="status">
                            <option value="">Semua Status</option>
                            <option value="terkirim" {{ request('status') == 'terkirim' ? 'selected' : '' }}>Terkirim</option>
                            <option value="dibaca" {{ request('status') == 'dibaca' ? 'selected' : '' }}>Dibaca</option>
                            <option value="belum_dibaca" {{ request('status') == 'belum_dibaca' ? 'selected' : '' }}>Belum Dibaca</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="tanggal_dari" class="form-label">Tanggal Dari</label>
                        <input type="date" class="form-control form-control-sm" name="tanggal_dari" id="tanggal_dari" value="{{ request('tanggal_dari') }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="tanggal_sampai" class="form-label">Tanggal Sampai</label>
                        <input type="date" class="form-control form-control-sm" name="tanggal_sampai" id="tanggal_sampai" value="{{ request('tanggal_sampai') }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="pengirim" class="form-label">Pengirim</label>
                        <select class="form-select form-select-sm" name="pengirim" id="pengirim">
                            <option value="">Semua Pengirim</option>
                            @foreach($kantorList as $kantor)
                                <option value="{{ $kantor->id }}" {{ request('pengirim') == $kantor->id ? 'selected' : '' }}>
                                    {{ $kantor->nama_kantor }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="search" class="form-label">Cari Perihal</label>
                        <input type="text" class="form-control form-control-sm" name="search" id="search" placeholder="Masukkan kata kunci..." value="{{ request('search') }}">
                    </div>
                    
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="btn-group w-100">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-search"></i> Filter
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-x-circle"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card-body">
            <!-- Filter Info -->
            @if(request()->hasAny(['jenis_surat', 'sifat_surat', 'status', 'tanggal_dari', 'tanggal_sampai', 'pengirim', 'search']))
                <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-info-circle"></i> 
                    Filter aktif: 
                    @if(request('jenis_surat'))
                        <span class="badge bg-primary me-1">Jenis: {{ ucfirst(request('jenis_surat')) }}</span>
                    @endif
                    @if(request('sifat_surat'))
                        <span class="badge bg-warning me-1">Sifat: {{ ucfirst(str_replace('_', ' ', request('sifat_surat'))) }}</span>
                    @endif
                    @if(request('status'))
                        <span class="badge bg-success me-1">Status: {{ ucfirst(str_replace('_', ' ', request('status'))) }}</span>
                    @endif
                    @if(request('tanggal_dari') || request('tanggal_sampai'))
                        <span class="badge bg-info me-1">
                            Tanggal: {{ request('tanggal_dari') ? date('d/m/Y', strtotime(request('tanggal_dari'))) : '...' }} 
                            - {{ request('tanggal_sampai') ? date('d/m/Y', strtotime(request('tanggal_sampai'))) : '...' }}
                        </span>
                    @endif
                    @if(request('search'))
                        <span class="badge bg-secondary me-1">Pencarian: "{{ request('search') }}"</span>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No. Surat</th>
                            <th>Perihal</th>
                            <th>Pengirim</th>
                            <th>Jenis</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suratTerbaru as $surat)
                        <tr>
                            <td>{{ $surat->nomor_surat }}</td>
                            <td>
                                {{ Str::limit($surat->perihal, 50) }}
                                @if($surat->sifat_surat == 'rahasia')
                                    <span class="badge bg-warning ms-2"><i class="bi bi-shield-lock"></i> Rahasia</span>
                                @elseif($surat->sifat_surat == 'sangat_rahasia')
                                    <span class="badge bg-danger ms-2"><i class="bi bi-shield-fill-exclamation"></i> Sangat Rahasia</span>
                                @endif
                            </td>
                            <td>{{ $surat->pengirim->nama_kantor }}</td>
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
                            <td>{{ $surat->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($surat->pengirim_id == auth()->user()->kantor_cabang_id)
                                    <span class="badge bg-success">Terkirim</span>
                                @elseif($surat->isReadByKantor(auth()->user()->kantor_cabang_id))
                                    <span class="badge bg-secondary">Dibaca</span>
                                @else
                                    <span class="badge bg-danger">Belum Dibaca</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('surat.show', $surat->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Lihat
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                @if(request()->hasAny(['jenis_surat', 'sifat_surat', 'status', 'tanggal_dari', 'tanggal_sampai', 'pengirim', 'search']))
                                    Tidak ada surat yang sesuai dengan filter
                                @else
                                    Belum ada surat
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($suratTerbaru->hasPages())
                <div class="mt-3">
                    {{ $suratTerbaru->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto submit form when filter changes (optional)
    const filterInputs = document.querySelectorAll('#filterCollapse select, #filterCollapse input[type="date"]');
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Uncomment the line below if you want auto-submit on change
            // this.form.submit();
        });
    });
    
    // Search on Enter key
    document.getElementById('search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            this.form.submit();
        }
    });
});
</script>
@endsection