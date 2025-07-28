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
    public function index(Request $request)
    {
        $user = auth()->user();
        $kantorId = $user->kantor_cabang_id;
        
        $query = Surat::with('pengirim')
            ->where(function($q) use ($kantorId) {
                // Surat biasa yang ditujukan ke kantor ini
                $q->whereHas('penerimas', function($penerima) use ($kantorId) {
                    $penerima->where('kantor_cabang_id', $kantorId);
                })
                // ATAU surat balasan yang parent-nya dari kantor ini
                ->orWhere(function($balasan) use ($kantorId) {
                    $balasan->whereNotNull('parent_id')
                          ->whereHas('parent', function($parent) use ($kantorId) {
                              $parent->where('pengirim_id', $kantorId);
                          });
                });
            });

        // Filter berdasarkan jenis surat
        if ($request->filled('jenis_surat')) {
            $query->where('jenis_surat', $request->jenis_surat);
        }

        // Filter berdasarkan sifat surat
        if ($request->filled('sifat_surat')) {
            $query->where('sifat_surat', $request->sifat_surat);
        }

        // Filter berdasarkan status baca
        if ($request->filled('status')) {
            if ($request->status == 'belum_dibaca') {
                $query->whereDoesntHave('reads', function($q) use ($kantorId) {
                    $q->where('kantor_cabang_id', $kantorId);
                });
            } elseif ($request->status == 'sudah_dibaca') {
                $query->whereHas('reads', function($q) use ($kantorId) {
                    $q->where('kantor_cabang_id', $kantorId);
                });
            }
        }

        // Filter berdasarkan pengirim
        if ($request->filled('pengirim_id')) {
            $query->where('pengirim_id', $request->pengirim_id);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        // Pencarian berdasarkan perihal atau nomor surat
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('perihal', 'like', '%' . $request->search . '%')
                  ->orWhere('nomor_surat', 'like', '%' . $request->search . '%');
            });
        }

        $suratMasuk = $query->latest()->paginate(10)->withQueryString();
        
        // Get list pengirim untuk dropdown filter
        $pengirimList = KantorCabang::where('id', '!=', $kantorId)
            ->orderBy('nama_kantor')
            ->get();

        return view('surat.index', compact('suratMasuk', 'pengirimList'));
    }

    public function suratKeluar(Request $request)
{
    $user = auth()->user();
    $kantorId = $user->kantor_cabang_id;
    
    $query = Surat::with(['reads.kantorCabang', 'penerimas.kantorCabang'])
        ->where('pengirim_id', $kantorId);

    // Filter berdasarkan jenis surat
    if ($request->filled('jenis_surat')) {
        $query->where('jenis_surat', $request->jenis_surat);
    }

    // Filter berdasarkan sifat surat
    if ($request->filled('sifat_surat')) {
        $query->where('sifat_surat', $request->sifat_surat);
    }

    // Filter berdasarkan status pembacaan
    if ($request->filled('status')) {
        if ($request->status == 'semua_dibaca') {
            $query->whereHas('penerimas', function($q) {
                $q->whereRaw('(SELECT COUNT(*) FROM surat_reads WHERE surat_reads.surat_id = surats.id) = (SELECT COUNT(*) FROM surat_penerimas WHERE surat_penerimas.surat_id = surats.id)');
            });
        } elseif ($request->status == 'sebagian_dibaca') {
            $query->whereHas('reads')
                  ->whereRaw('(SELECT COUNT(*) FROM surat_reads WHERE surat_reads.surat_id = surats.id) < (SELECT COUNT(*) FROM surat_penerimas WHERE surat_penerimas.surat_id = surats.id)');
        } elseif ($request->status == 'belum_dibaca') {
            $query->doesntHave('reads');
        }
    }

    // Filter berdasarkan penerima
    if ($request->filled('penerima_id')) {
        $query->whereHas('penerimas', function($q) use ($request) {
            $q->where('kantor_cabang_id', $request->penerima_id);
        });
    }

    // Filter berdasarkan tanggal
    if ($request->filled('tanggal_dari')) {
        $query->whereDate('created_at', '>=', $request->tanggal_dari);
    }
    if ($request->filled('tanggal_sampai')) {
        $query->whereDate('created_at', '<=', $request->tanggal_sampai);
    }

    // Pencarian berdasarkan perihal atau nomor surat
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('perihal', 'like', '%' . $request->search . '%')
              ->orWhere('nomor_surat', 'like', '%' . $request->search . '%');
        });
    }

    $suratKeluar = $query->latest()->paginate(10)->withQueryString();
    
    // Get list penerima untuk dropdown filter
    $penerimaList = KantorCabang::where('id', '!=', $kantorId)
        ->orderBy('nama_kantor')
        ->get();

    return view('surat.keluar', compact('suratKeluar', 'penerimaList'));
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
        // Custom validation rules
        $rules = [
            'perihal' => 'required|string|max:255',
            'isi_surat' => 'required|string',
            'jenis_surat' => 'required|in:informasi,pertanyaan,permintaan,lainnya',
            'sifat_surat' => 'required|in:biasa,rahasia,sangat_rahasia', 
            'file_lampiran' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip,rar'
        ];
        
        // Tambah validasi penerima hanya jika tidak kirim ke semua
        if (!$request->has('kirim_semua') || !$request->kirim_semua) {
            $rules['penerima'] = 'required|array|min:1';
            $rules['penerima.*'] = 'exists:kantor_cabangs,id';
        }
        
        $request->validate($rules);

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
        
        // Jika ini surat balasan, hanya pengirim balasan dan penerima asli yang bisa lihat
        if ($surat->isPrivateReply()) {
            $originalSender = $surat->getOriginalRecipient();
            if ($surat->pengirim_id != $kantorId && $originalSender != $kantorId) {
                abort(403, 'Anda tidak berhak melihat surat balasan ini.');
            }
        } else {
            // Surat biasa
            if ($surat->pengirim_id != $kantorId && !$surat->isForKantor($kantorId)) {
                abort(403, 'Anda tidak berhak melihat surat ini.');
            }
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

    public function reply($id)
    {
        $suratAsli = Surat::with(['pengirim', 'penerimas'])->findOrFail($id);
        
        // Cek apakah user berhak membalas surat ini
        $kantorId = auth()->user()->kantor_cabang_id;
        if (!$suratAsli->isForKantor($kantorId)) {
            abort(403, 'Anda tidak berhak membalas surat ini.');
        }
        
        return view('surat.reply', compact('suratAsli'));
    }

    public function storeReply(Request $request, $id)
    {
        $suratAsli = Surat::findOrFail($id);
        
        // Validasi hak akses
        $kantorId = auth()->user()->kantor_cabang_id;
        if (!$suratAsli->isForKantor($kantorId)) {
            abort(403, 'Anda tidak berhak membalas surat ini.');
        }
        
        $request->validate([
            'perihal' => 'required|string|max:255',
            'isi_surat' => 'required|string',
            'jenis_surat' => 'required|in:informasi,pertanyaan,permintaan,lainnya',
            'sifat_surat' => 'required|in:biasa,rahasia,sangat_rahasia', 
            'file_lampiran' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip,rar'
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'parent_id' => $suratAsli->id,
                'nomor_surat' => $this->generateNomorSurat(),
                'perihal' => $request->perihal,
                'isi_surat' => $request->isi_surat,
                'jenis_surat' => $request->jenis_surat,
                'sifat_surat' => $request->sifat_surat,
                'pengirim_id' => $kantorId,
                'status' => 'terkirim'
            ];

            if ($request->hasFile('file_lampiran')) {
                $file = $request->file('file_lampiran');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('lampiran', $filename, 'public');
                $data['file_lampiran'] = $path;
            }

            $balasan = Surat::create($data);

            // Kirim balasan HANYA ke pengirim asli
            SuratPenerima::create([
                'surat_id' => $balasan->id,
                'kantor_cabang_id' => $suratAsli->pengirim_id
            ]);

            DB::commit();
            
            return redirect()->route('surat.show', $balasan->id)
                ->with('success', 'Balasan surat berhasil dikirim!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal mengirim balasan. Silakan coba lagi.');
        }
    }

    public function showThread($id)
    {
        $surat = Surat::with(['pengirim', 'penerimas.kantorCabang', 'parent', 'replies.pengirim'])->findOrFail($id);
        
        // Cek hak akses
        $kantorId = auth()->user()->kantor_cabang_id;
        if ($surat->pengirim_id != $kantorId && !$surat->isForKantor($kantorId)) {
            abort(403, 'Anda tidak berhak melihat surat ini.');
        }
        
        // Get all thread (parent and all replies)
        if ($surat->parent_id) {
            // If this is a reply, get the parent
            $threadParent = $surat->parent;
        } else {
            // This is already the parent
            $threadParent = $surat;
        }
        
        // Get all messages in thread
        $thread = collect([$threadParent])->merge($threadParent->replies()->with('pengirim')->get());
        
        return view('surat.thread', compact('thread', 'surat'));
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