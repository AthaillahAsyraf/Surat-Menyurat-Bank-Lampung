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
            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Surat Terbaru</h5>
        </div>
        <div class="card-body">
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
                            <td>{{ Str::limit($surat->perihal, 50) }}</td>
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
                            <td colspan="7" class="text-center">Belum ada surat</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection