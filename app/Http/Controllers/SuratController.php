<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\SuratRead;
use App\Models\SuratPenerima;
use App\Models\KantorCabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SuratController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $kantorId = $user->kantor_cabang_id;
        
        $suratMasuk = Surat::with('pengirim')
            ->whereHas('penerimas', function($q) use ($kantorId) {
                $q->where('kantor_cabang_id', $kantorId);
            })
            ->latest()
            ->paginate(20);

        return view('surat.index', compact('suratMasuk'));
    }

    public function suratKeluar()
    {
        $user = auth()->user();
        $kantorId = $user->kantor_cabang_id;
        
        $suratKeluar = Surat::with(['reads.kantorCabang', 'penerimas.kantorCabang'])
            ->where('pengirim_id', $kantorId)
            ->latest()
            ->paginate(20);

        return view('surat.keluar', compact('suratKeluar'));
    }

    public function create()
    {
        $kantorCabangs = KantorCabang::where('id', '!=', auth()->user()->kantor_cabang_id)
            ->orderBy('nama_kantor')
            ->get();
            
        return view('surat.create', compact('kantorCabangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'perihal' => 'required|string|max:255',
            'isi_surat' => 'required|string',
            'jenis_surat' => 'required|in:informasi,pertanyaan,permintaan,lainnya',
            'file_lampiran' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip,rar',
            'penerima' => 'required|array|min:1',
            'penerima.*' => 'exists:kantor_cabangs,id'
        ]);

        DB::beginTransaction();
        try {
            $data = $request->except(['penerima', 'kirim_semua']);
            $data['pengirim_id'] = auth()->user()->kantor_cabang_id;
            $data['nomor_surat'] = $this->generateNomorSurat();

            if ($request->hasFile('file_lampiran')) {
                $file = $request->file('file_lampiran');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('lampiran', $filename, 'public');
                $data['file_lampiran'] = $path;
            }

            $surat = Surat::create($data);

            // Simpan penerima
            if ($request->has('kirim_semua') && $request->kirim_semua) {
                // Kirim ke semua kantor cabang kecuali pengirim
                $allKantor = KantorCabang::where('id', '!=', auth()->user()->kantor_cabang_id)->pluck('id');
                foreach ($allKantor as $kantorId) {
                    SuratPenerima::create([
                        'surat_id' => $surat->id,
                        'kantor_cabang_id' => $kantorId
                    ]);
                }
            } else {
                // Kirim ke kantor yang dipilih
                foreach ($request->penerima as $kantorId) {
                    SuratPenerima::create([
                        'surat_id' => $surat->id,
                        'kantor_cabang_id' => $kantorId
                    ]);
                }
            }

            DB::commit();
            
            $jumlahPenerima = $surat->penerimas()->count();
            return redirect()->route('surat.keluar')
                ->with('success', "Surat berhasil dikirim ke $jumlahPenerima kantor cabang!");

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal mengirim surat. Silakan coba lagi.');
        }
    }

    public function show($id)
    {
        $surat = Surat::with(['pengirim', 'penerimas.kantorCabang'])->findOrFail($id);
        
        // Cek apakah user berhak melihat surat ini
        $kantorId = auth()->user()->kantor_cabang_id;
        if ($surat->pengirim_id != $kantorId && !$surat->isForKantor($kantorId)) {
            abort(403, 'Anda tidak berhak melihat surat ini.');
        }
        
        // Tandai sebagai sudah dibaca jika bukan pengirim
        if ($surat->pengirim_id != $kantorId) {
            SuratRead::firstOrCreate([
                'surat_id' => $surat->id,
                'kantor_cabang_id' => $kantorId,
            ], [
                'read_at' => now()
            ]);
        }

        // Get list pembaca
        $pembaca = SuratRead::with('kantorCabang')
            ->where('surat_id', $surat->id)
            ->get();

        return view('surat.show', compact('surat', 'pembaca'));
    }

    public function download($id)
    {
        $surat = Surat::findOrFail($id);
        
        // Cek hak akses
        $kantorId = auth()->user()->kantor_cabang_id;
        if ($surat->pengirim_id != $kantorId && !$surat->isForKantor($kantorId)) {
            abort(403, 'Anda tidak berhak mengunduh lampiran ini.');
        }
        
        if (!$surat->file_lampiran) {
            return back()->with('error', 'Tidak ada file lampiran.');
        }

        return Storage::disk('public')->download($surat->file_lampiran);
    }

    private function generateNomorSurat()
    {
        $kodeKantor = auth()->user()->kantorCabang->kode_kantor;
        $tahun = date('Y');
        $bulan = date('m');
        
        $lastSurat = Surat::where('nomor_surat', 'like', $kodeKantor . '/' . $tahun . '/' . $bulan . '/%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastSurat) {
            $lastNumber = intval(substr($lastSurat->nomor_surat, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $kodeKantor . '/' . $tahun . '/' . $bulan . '/' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}