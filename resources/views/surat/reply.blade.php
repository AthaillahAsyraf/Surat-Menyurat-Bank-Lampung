@extends('layouts.app')

@section('title', 'Balas Surat')

@section('content')
<div class="container-fluid px-0">
    <div class="row">
        <div class="col-lg-8 col-12 mb-4 mb-lg-0">
            <!-- Form Balasan -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fs-6 fs-md-5"><i class="bi bi-reply"></i> Balas Surat</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('surat.storeReply', $suratAsli->id) }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Balasan ini hanya akan dikirim ke: <strong>{{ $suratAsli->pengirim->nama_kantor }}</strong>
                        </div>
                        
                        <div class="mb-3">
                            <label for="perihal" class="form-label">Perihal Balasan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('perihal') is-invalid @enderror" 
                                   id="perihal" name="perihal" value="{{ old('perihal', 'Re: ' . $suratAsli->perihal) }}" required>
                            @error('perihal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="jenis_surat" class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_surat') is-invalid @enderror" 
                                    id="jenis_surat" name="jenis_surat" required>
                                <option value="">-- Pilih Jenis Surat --</option>
                                <option value="informasi" {{ old('jenis_surat', $suratAsli->jenis_surat) == 'informasi' ? 'selected' : '' }}>
                                    Informasi
                                </option>
                                <option value="pertanyaan" {{ old('jenis_surat', $suratAsli->jenis_surat) == 'pertanyaan' ? 'selected' : '' }}>
                                    Pertanyaan
                                </option>
                                <option value="permintaan" {{ old('jenis_surat', $suratAsli->jenis_surat) == 'permintaan' ? 'selected' : '' }}>
                                    Permintaan
                                </option>
                                <option value="lainnya" {{ old('jenis_surat', $suratAsli->jenis_surat) == 'lainnya' ? 'selected' : '' }}>
                                    Lainnya
                                </option>
                            </select>
                            @error('jenis_surat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
    <label for="sifat_surat" class="form-label">Sifat Surat <span class="text-danger">*</span></label>
    <select class="form-select @error('sifat_surat') is-invalid @enderror" 
            id="sifat_surat" name="sifat_surat" required>
        <option value="">-- Pilih Sifat Surat --</option>
        <option value="biasa" {{ old('sifat_surat', 'biasa') == 'biasa' ? 'selected' : '' }}>
            Biasa
        </option>
        <option value="rahasia" {{ old('sifat_surat') == 'rahasia' ? 'selected' : '' }}>
            Rahasia
        </option>
        <option value="sangat_rahasia" {{ old('sifat_surat') == 'sangat_rahasia' ? 'selected' : '' }}>
            Sangat Rahasia
        </option>
    </select>
    @error('sifat_surat')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
                        
                        <div class="mb-3">
                            <label for="isi_surat" class="form-label">Isi Balasan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('isi_surat') is-invalid @enderror" 
                                      id="isi_surat" name="isi_surat" rows="8" 
                                      placeholder="Tulis balasan Anda disini..." required>{{ old('isi_surat') }}</textarea>
                            @error('isi_surat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="file_lampiran" class="form-label">Lampiran (Opsional)</label>
                            <input type="file" class="form-control @error('file_lampiran') is-invalid @enderror" 
                                   id="file_lampiran" name="file_lampiran" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar">
                            <small class="text-muted">Format: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, ZIP, RAR. Max: 10MB</small>
                            @error('file_lampiran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('surat.show', $suratAsli->id) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Kirim Balasan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-12">
            <!-- Surat Asli -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="bi bi-envelope"></i> Surat yang Dibalas</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><strong>No. Surat:</strong></td>
                            <td>{{ $suratAsli->nomor_surat }}</td>
                        </tr>
                        <tr>
                            <td><strong>Pengirim:</strong></td>
                            <td>{{ $suratAsli->pengirim->nama_kantor }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal:</strong></td>
                            <td>{{ $suratAsli->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Perihal:</strong></td>
                            <td>{{ $suratAsli->perihal }}</td>
                        </tr>
                    </table>
                    
                    <hr>
                    
                    <strong>Isi Surat:</strong>
                    <div class="mt-2 p-2 bg-light rounded small" style="max-height: 300px; overflow-y: auto;">
                        {!! nl2br(e($suratAsli->isi_surat)) !!}
                    </div>
                    
                    @if($suratAsli->file_lampiran)
                    <div class="mt-3">
                        <a href="{{ route('surat.download', $suratAsli->id) }}" class="btn btn-sm btn-success">
                            <i class="bi bi-download"></i> Download Lampiran
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
