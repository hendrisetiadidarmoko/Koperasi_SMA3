<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    public function logout()
    {
        // Log out pengguna saat ini
        Auth::logout();

        // Mengarahkan pengguna kembali ke halaman login setelah logout
        return redirect()->route('auth.login');
    }
}
