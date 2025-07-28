<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\KantorCabang;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $kantorId = $user->kantor_cabang_id;
        
        // Stats yang tetap sama
        $totalSuratMasuk = Surat::whereHas('penerimas', function($q) use ($kantorId) {
            $q->where('kantor_cabang_id', $kantorId);
        })->count();
        
        $totalSuratKeluar = Surat::where('pengirim_id', $kantorId)->count();
        
        $suratBelumDibaca = Surat::whereHas('penerimas', function($q) use ($kantorId) {
                $q->where('kantor_cabang_id', $kantorId);
            })
            ->whereDoesntHave('reads', function($q) use ($kantorId) {
                $q->where('kantor_cabang_id', $kantorId);
            })->count();
        
        // Query untuk surat terbaru dengan filter
        $query = Surat::with('pengirim')
            ->where(function($q) use ($kantorId) {
                $q->where('pengirim_id', $kantorId)
                  ->orWhereHas('penerimas', function($subQ) use ($kantorId) {
                      $subQ->where('kantor_cabang_id', $kantorId);
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

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $status = $request->status;
            
            if ($status == 'terkirim') {
                $query->where('pengirim_id', $kantorId);
            } elseif ($status == 'dibaca') {
                $query->whereHas('penerimas', function($q) use ($kantorId) {
                    $q->where('kantor_cabang_id', $kantorId);
                })->whereHas('reads', function($q) use ($kantorId) {
                    $q->where('kantor_cabang_id', $kantorId);
                });
            } elseif ($status == 'belum_dibaca') {
                $query->whereHas('penerimas', function($q) use ($kantorId) {
                    $q->where('kantor_cabang_id', $kantorId);
                })->whereDoesntHave('reads', function($q) use ($kantorId) {
                    $q->where('kantor_cabang_id', $kantorId);
                });
            }
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        // Filter berdasarkan pengirim
        if ($request->filled('pengirim')) {
            $query->where('pengirim_id', $request->pengirim);
        }

        // Filter berdasarkan pencarian perihal
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('perihal', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_surat', 'LIKE', "%{$search}%");
            });
        }

        // Ambil data surat dengan pagination
        $suratTerbaru = $query->latest()->paginate(10);

        // List kantor untuk dropdown filter
        $kantorList = KantorCabang::orderBy('nama_kantor')->get();

        return view('dashboard', compact(
            'totalSuratMasuk', 
            'totalSuratKeluar', 
            'suratBelumDibaca', 
            'suratTerbaru',
            'kantorList'
        ));
    }
}