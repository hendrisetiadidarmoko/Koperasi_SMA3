<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;

class Login extends Component
{
    public $email;
    public $password, $remember;

    /**
     * login
     *
     * @return void
     * 
     */


    public function login()
    {
        // Validasi input email dan password
        $this->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $throttleKey = strtolower($this->email) . '|' . request()->ip();

        // Cek jika sudah terlalu banyak percobaan login
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('email', __('auth.throttle', ['seconds' => $seconds]));
            return null;
        }

        // Proses login dengan remember option
        if (Auth::attempt(
            ['email' => $this->email, 'password' => $this->password],
            (bool) $this->remember
        )) {
            RateLimiter::clear($throttleKey);
            return redirect()->route('admin.dashboard');
        } else {
            RateLimiter::hit($throttleKey);
            session()->flash('error', 'Alamat Email atau Password Anda salah.');
            return null;
        }
    }


    /**
     * render
     *
     * @return void
     */
    public function render()
    {
        return view('livewire.auth.login')->layout('livewire.auth.app');
    }
}
