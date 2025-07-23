@extends('layouts.app')

@section('title', 'Surat Keluar')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-send"></i> Surat Keluar</h2>
        <a href="{{ route('surat.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Kirim Surat Baru
        </a>
    </div>
    
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
                            <th width="15%">Tanggal</th>
                            <th width="15%">Tujuan</th>
                            <th width="10%">Dibaca</th>
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
                            <td>{{ $surat->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @php
                                    $jumlahTujuan = $surat->penerimas->count();
                                @endphp
                                @if($jumlahTujuan == 42)
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
                                <span class="badge bg-success">{{ $surat->reads->count() }}/{{ $jumlahTujuan }}</span>
                            </td>
                            <td>
                                <a href="{{ route('surat.show', $surat->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada surat keluar</td>
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
@endsection
