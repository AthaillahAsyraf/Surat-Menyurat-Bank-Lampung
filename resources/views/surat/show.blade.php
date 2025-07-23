@extends('layouts.app')

@section('title', 'Detail Surat')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-envelope-open"></i> Detail Surat</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="20%"><strong>Nomor Surat</strong></td>
                            <td>: {{ $surat->nomor_surat }}</td>
                        </tr>
                        <tr>
                            <td><strong>Pengirim</strong></td>
                            <td>: {{ $surat->pengirim->nama_kantor }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal</strong></td>
                            <td>: {{ $surat->created_at->format('d F Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Perihal</strong></td>
                            <td>: {{ $surat->perihal }}</td>
                        </tr>
                        <tr>
                            <td><strong>Jenis Surat</strong></td>
                            <td>: 
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
                        </tr>
                        @if($surat->file_lampiran)
                        <tr>
                            <td><strong>Lampiran</strong></td>
                            <td>: 
                                <a href="{{ route('surat.download', $surat->id) }}" class="btn btn-sm btn-success">
                                    <i class="bi bi-download"></i> Download Lampiran
                                </a>
                            </td>
                        </tr>
                        @endif
                    </table>
                    
                    <hr>
                    
                    <h6><strong>Isi Surat:</strong></h6>
                    <div class="p-3 bg-light rounded">
                        {!! nl2br(e($surat->isi_surat)) !!}
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Daftar Penerima -->
            <div class="card mb-3">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="bi bi-people"></i> Daftar Penerima</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Total Penerima:</strong> 
                        <span class="badge bg-primary">{{ $surat->penerimas->count() }} kantor</span>
                    </p>
                    
                    @if($surat->penerimas->count() == 42)
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Surat ini dikirim ke semua kantor cabang
                        </div>
                    @else
                        <div style="max-height: 200px; overflow-y: auto;">
                            <ul class="list-unstyled small">
                                @foreach($surat->penerimas as $penerima)
                                    <li class="mb-1">
                                        <i class="bi bi-building"></i> {{ $penerima->kantorCabang->nama_kantor }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Status Pembacaan -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-eye"></i> Status Pembacaan</h6>
                </div>
                <div class="card-body">
                    <p class="mb-3">
                        <strong>Total Dibaca:</strong> 
                        <span class="badge bg-success">{{ $pembaca->count() }} dari {{ $surat->penerimas->count() }} kantor</span>
                    </p>
                    
                    <div style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Kantor Cabang</th>
                                    <th>Waktu Baca</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pembaca as $read)
                                <tr>
                                    <td>{{ $read->kantorCabang->nama_kantor }}</td>
                                    <td>{{ $read->read_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center">Belum ada yang membaca</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection