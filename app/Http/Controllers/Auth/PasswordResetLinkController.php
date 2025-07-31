<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     * Menampilkan halaman form forgot password
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     * Menangani permintaan pengiriman link reset password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input email
        $request->validate([
            'email' => [
                'required',
                'email:rfc,dns', // Validasi email yang lebih ketat
                'exists:users,email' // Pastikan email ada di database
            ],
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar dalam sistem.',
        ]);

        // Kirim link reset password ke email user
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Cek status pengiriman email dan berikan response yang sesuai
        if ($status == Password::RESET_LINK_SENT) {
            return back()->with([
                'status' => 'Link reset password telah dikirim ke email Anda. Silakan cek inbox atau folder spam.',
                'success' => true
            ]);
        }

        // Jika gagal kirim email
        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => $this->getErrorMessage($status)
            ]);
    }

    /**
     * Get error message berdasarkan status
     *
     * @param string $status
     * @return string
     */
    private function getErrorMessage(string $status): string
    {
        return match($status) {
            Password::INVALID_USER => 'Email tidak ditemukan dalam sistem.',
            Password::RESET_THROTTLED => 'Terlalu banyak percobaan. Silakan coba lagi dalam beberapa menit.',
            default => 'Terjadi kesalahan saat mengirim email. Silakan coba lagi.'
        };
    }
}