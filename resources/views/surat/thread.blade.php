@extends('layouts.app')

@section('title', 'Thread Surat')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-chat-dots"></i> Thread Percakapan Surat</h2>
        <a href="{{ route('surat.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            @foreach($thread as $pesan)
            <div class="card mb-3 {{ $pesan->pengirim_id == auth()->user()->kantor_cabang_id ? 'border-primary' : '' }}">
                <div class="card-header {{ $pesan->pengirim_id == auth()->user()->kantor_cabang_id ? 'bg-primary text-white' : 'bg-light' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $pesan->pengirim->nama_kantor }}</strong>
                            @if($pesan->isReply())
                                <span class="badge bg-info ms-2">Balasan</span>
                            @else
                                <span class="badge bg-success ms-2">Surat Asli</span>
                            @endif
                        </div>
                        <small>{{ $pesan->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                <div class="card-body">
                    <h6>{{ $pesan->perihal }}</h6>
                    <p class="mb-2">{!! nl2br(e($pesan->isi_surat)) !!}</p>
                    
                    @if($pesan->file_lampiran)
                    <div class="mt-2">
                        <a href="{{ route('surat.download', $pesan->id) }}" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-paperclip"></i> Lampiran
                        </a>
                    </div>
                    @endif
                    
                    <div class="mt-3 text-muted small">
                        <i class="bi bi-calendar"></i> {{ $pesan->created_at->format('d F Y H:i') }} | 
                        No. Surat: {{ $pesan->nomor_surat }}
                    </div>
                </div>
            </div>
            @endforeach
            
            
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Informasi Thread</h6>
                </div>
                <div class="card-body">
                    <p><strong>Total Pesan:</strong> {{ $thread->count() }}</p>
                    <p><strong>Dimulai:</strong> {{ $thread->first()->created_at->format('d F Y') }}</p>
                    <p><strong>Peserta:</strong></p>
                    <ul class="list-unstyled">
                        @foreach($thread->pluck('pengirim')->unique('id') as $pengirim)
                        <li><i class="bi bi-building"></i> {{ $pengirim->nama_kantor }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection