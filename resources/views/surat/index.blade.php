@extends('layouts.app')

@section('title', 'Surat Masuk')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-inbox"></i> Surat Masuk</h2>
        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
            <i class="bi bi-funnel"></i> Filter
        </button>
    </div>
    
    <!-- Filter Section -->
    <div class="collapse {{ request()->hasAny(['jenis_surat', 'sifat_surat', 'status', 'pengirim_id', 'tanggal_dari', 'tanggal_sampai', 'search']) ? 'show' : '' }}" id="filterCollapse">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="bi bi-funnel"></i> Filter Surat</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('surat.index') }}">
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
                        
                        <!-- Status Baca -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="">Semua Status</option>
                                <option value="belum_dibaca" {{ request('status') == 'belum_dibaca' ? 'selected' : '' }}>
                                    Belum Dibaca
                                </option>
                                <option value="sudah_dibaca" {{ request('status') == 'sudah_dibaca' ? 'selected' : '' }}>
                                    Sudah Dibaca
                                </option>
                            </select>
                        </div>
                        
                        <!-- Pengirim -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Pengirim</label>
                            <select class="form-select" name="pengirim_id">
                                <option value="">Semua Pengirim</option>
                                @foreach($pengirimList as $pengirim)
                                    <option value="{{ $pengirim->id }}" {{ request('pengirim_id') == $pengirim->id ? 'selected' : '' }}>
                                        {{ $pengirim->nama_kantor }}
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
                        <a href="{{ route('surat.index') }}" class="btn btn-secondary">
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
    @if(request()->hasAny(['jenis_surat', 'sifat_surat', 'status', 'pengirim_id', 'tanggal_dari', 'tanggal_sampai', 'search']))
    <div class="alert alert-info d-flex justify-content-between align-items-center">
        <span>Menampilkan {{ $suratMasuk->total() }} surat dengan filter aktif</span>
        <a href="{{ route('surat.index') }}" class="btn btn-sm btn-outline-secondary">
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
                            <th width="25%">Perihal</th>
                            <th width="15%">Pengirim</th>
                            <th width="10%">Jenis</th>
                            <th width="10%">Sifat</th>
                            <th width="10%">Tanggal</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suratMasuk as $index => $surat)
                        <tr class="{{ !$surat->isReadByKantor(auth()->user()->kantor_cabang_id) ? 'table-warning' : '' }}">
                            <td>{{ $suratMasuk->firstItem() + $index }}</td>
                            <td>{{ $surat->nomor_surat }}</td>
                            <td>
                                {{ Str::limit($surat->perihal, 50) }}
                                @if(!$surat->isReadByKantor(auth()->user()->kantor_cabang_id))
                                    <span class="badge bg-danger ms-2">Baru</span>
                                @endif
                                @if($surat->isPrivateReply())
                                    <span class="badge bg-warning ms-2"><i class="bi bi-lock"></i> Balasan Private</span>
                                @endif
                                @if($surat->hasReplies())
                                    <span class="badge bg-success ms-2">{{ $surat->getRepliesCount() }} Balasan</span>
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
                                <a href="{{ route('surat.show', $surat->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Baca
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                @if(request()->hasAny(['jenis_surat', 'sifat_surat', 'status', 'pengirim_id', 'tanggal_dari', 'tanggal_sampai', 'search']))
                                    Tidak ada surat yang sesuai dengan filter
                                @else
                                    Belum ada surat masuk
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $suratMasuk->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection