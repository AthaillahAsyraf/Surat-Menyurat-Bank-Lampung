<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\KantorCabang;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $kantorId = $user->kantor_cabang_id;
        
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
        
        $suratTerbaru = Surat::with('pengirim')
            ->where(function($query) use ($kantorId) {
                $query->where('pengirim_id', $kantorId)
                      ->orWhereHas('penerimas', function($q) use ($kantorId) {
                          $q->where('kantor_cabang_id', $kantorId);
                      });
            })
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact('totalSuratMasuk', 'totalSuratKeluar', 'suratBelumDibaca', 'suratTerbaru'));
    }
}
