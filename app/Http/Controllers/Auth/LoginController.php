<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/admin/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Override username method - menggunakan username sebagai default
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Override credentials untuk support email atau username
     */
    protected function credentials(Request $request)
    {
        $login = $request->input('username');

        // Cek apakah input berupa email atau username
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return [
            $field => $login,
            'password' => $request->input('password')
        ];
    }

    /**
     * Override authenticated untuk redirect berdasarkan role
     */
    protected function authenticated(Request $request, $user)
    {
        // Update last login
        $user->updateLastLogin();

        // Redirect berdasarkan role
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'ustadz':
                return redirect()->route('ustadz.dashboard');
            case 'santri':
                return redirect()->route('santri.dashboard');
            case 'bendahara':
                return redirect()->route('bendahara.dashboard');
            case 'pemimpin':
                return redirect()->route('pemimpin.dashboard');
            default:
                Auth::logout();
                return redirect()->route('login')->with('error', 'Role tidak valid');
        }
    }

    /**
     * Override untuk custom validation messages
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username atau email harus diisi',
            'password.required' => 'Password harus diisi',
        ]);
    }

    /**
     * Override untuk custom failed login message
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'username' => ['Username/email atau password salah. Silakan coba lagi.'],
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);
        // DEBUG - hapus setelah berhasil
        Log::info('Login attempt:', $credentials);

        $attempt = $this->guard()->attempt(
            $credentials,
            $request->filled('remember')
        );

        // DEBUG
        Log::info('Login result:', ['success' => $attempt]);

        return $attempt;
    }
}
