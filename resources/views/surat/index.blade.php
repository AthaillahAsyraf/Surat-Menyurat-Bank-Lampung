@extends('layouts.app')

@section('title', 'Surat Masuk')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-inbox"></i> Surat Masuk</h2>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">No. Surat</th>
                            <th width="25%">Perihal</th>
                            <th width="20%">Pengirim</th>
                            <th width="10%">Jenis</th>
                            <th width="15%">Tanggal</th>
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
                                <a href="{{ route('surat.show', $surat->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Baca
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada surat masuk</td>
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
