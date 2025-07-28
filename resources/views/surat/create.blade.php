@extends('layouts.app')

@section('title', 'Kirim Surat')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Kirim Surat Baru</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('surat.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="perihal" class="form-label">Perihal Surat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('perihal') is-invalid @enderror" 
                                   id="perihal" name="perihal" value="{{ old('perihal') }}" 
                                   placeholder="Masukkan perihal surat" required>
                            @error('perihal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="jenis_surat" class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_surat') is-invalid @enderror" 
                                    id="jenis_surat" name="jenis_surat" required>
                                <option value="">-- Pilih Jenis Surat --</option>
                                <option value="informasi" {{ old('jenis_surat') == 'informasi' ? 'selected' : '' }}>
                                    Informasi
                                </option>
                                <option value="pertanyaan" {{ old('jenis_surat') == 'pertanyaan' ? 'selected' : '' }}>
                                    Pertanyaan
                                </option>
                                <option value="permintaan" {{ old('jenis_surat') == 'permintaan' ? 'selected' : '' }}>
                                    Permintaan
                                </option>
                                <option value="lainnya" {{ old('jenis_surat') == 'lainnya' ? 'selected' : '' }}>
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
        <option value="biasa" {{ old('sifat_surat') == 'biasa' ? 'selected' : '' }}>
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
                            <label class="form-label">Tujuan Surat <span class="text-danger">*</span></label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="kirim_semua" name="kirim_semua" value="1">
                                <label class="form-check-label" for="kirim_semua">
                                    <strong>Kirim ke Semua Kantor Cabang (39 kantor)</strong>
                                </label>
                            </div>
                            
                            <div id="pilih-kantor">
                                <label for="penerima" class="form-label">Atau Pilih Kantor Cabang Tujuan:</label>
                                <select class="form-select @error('penerima') is-invalid @enderror" 
                                        id="penerima" name="penerima[]" multiple>
                                    @foreach($kantorCabangs as $kantor)
                                        <option value="{{ $kantor->id }}" {{ in_array($kantor->id, old('penerima', [])) ? 'selected' : '' }}>
                                            {{ $kantor->nama_kantor }} ({{ $kantor->kode_kantor }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('penerima')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Tekan Ctrl+Click untuk memilih lebih dari satu kantor</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="isi_surat" class="form-label">Isi Surat <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('isi_surat') is-invalid @enderror" 
                                      id="isi_surat" name="isi_surat" rows="8" 
                                      placeholder="Tulis isi surat disini..." required>{{ old('isi_surat') }}</textarea>
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
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Kirim Surat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-lightbulb"></i> Panduan Penggunaan</h6>
                </div>
                <div class="card-body">
                    <ol class="small">
                        <li class="mb-2">Isi perihal surat dengan jelas dan singkat</li>
                        <li class="mb-2">Pilih jenis surat yang sesuai dengan keperluan</li>
                        <li class="mb-2">Pilih tujuan surat:
                            <ul>
                                <li>Centang "Kirim ke Semua" untuk mengirim ke semua kantor cabang</li>
                                <li>Atau pilih kantor cabang tertentu dari daftar</li>
                            </ul>
                        </li>
                        <li class="mb-2">Tulis isi surat dengan lengkap dan jelas</li>
                        <li class="mb-2">Lampirkan file jika diperlukan (opsional)</li>
                        <li class="mb-2">Klik tombol "Kirim Surat"</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('#penerima').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Pilih kantor cabang tujuan...',
        allowClear: true
    });
    
    // Handle kirim semua checkbox
    $('#kirim_semua').change(function() {
        if($(this).is(':checked')) {
            $('#pilih-kantor').hide();
            $('#penerima').prop('required', false);
            $('#penerima').val(null).trigger('change'); // Clear selection
            $('#penerima').removeAttr('name'); // Remove name attribute
        } else {
            $('#pilih-kantor').show();
            $('#penerima').prop('required', true);
            $('#penerima').attr('name', 'penerima[]'); // Add name attribute back
        }
    });
    
    // Check on page load
    if($('#kirim_semua').is(':checked')) {
        $('#pilih-kantor').hide();
        $('#penerima').prop('required', false);
        $('#penerima').removeAttr('name');
    }
});
</script>
@endsection