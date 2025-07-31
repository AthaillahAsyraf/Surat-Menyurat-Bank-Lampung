<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password as PasswordRule;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     * Menampilkan halaman form reset password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', [
            'request' => $request,
            'token' => $request->route('token'),
            'email' => $request->query('email')
        ]);
    }

    /**
     * Handle an incoming new password request.
     * Menangani permintaan reset password baru
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input
        $request->validate([
            'token' => ['required'],
            'email' => [
                'required', 
                'email:rfc,dns',
                'exists:users,email'
            ],
            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
        ], [
            'token.required' => 'Token reset password tidak valid.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar dalam sistem.',
            'password.required' => 'Password baru harus diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.letters' => 'Password harus mengandung huruf.',
            'password.mixed_case' => 'Password harus mengandung huruf besar dan kecil.',
            'password.numbers' => 'Password harus mengandung angka.',
            'password.symbols' => 'Password harus mengandung karakter khusus.',
            'password.uncompromised' => 'Password yang dipilih tidak aman. Silakan pilih password lain.',
        ]);

        // Attempt to reset the user's password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Update password user
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                // Trigger event password reset
                event(new PasswordReset($user));
            }
        );

        // Cek status reset password dan berikan response yang sesuai
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with([
                'status' => 'Password berhasil direset! Silakan login dengan password baru Anda.',
                'success' => true
            ]);
        }

        // Jika gagal reset password
        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => $this->getResetErrorMessage($status)
            ]);
    }

    /**
     * Get error message untuk reset password berdasarkan status
     *
     * @param string $status
     * @return string
     */
    private function getResetErrorMessage(string $status): string
    {
        return match($status) {
            Password::INVALID_USER => 'Email tidak ditemukan dalam sistem.',
            Password::INVALID_TOKEN => 'Token reset password tidak valid atau sudah kedaluwarsa.',
            Password::RESET_THROTTLED => 'Terlalu banyak percobaan reset. Silakan coba lagi nanti.',
            default => 'Terjadi kesalahan saat reset password. Silakan coba lagi.'
        };
    }
}