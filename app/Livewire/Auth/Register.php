<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class Register extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public function render()
    {
        return view('livewire.auth.register')->layout('livewire.auth.app',[
            'title' => 'Koprasi - SMA N 3 Purwokerto',
        ]);
    }

    public function save()
    {
        $this->resetErrorBag();

        // Validasi semua field termasuk nomor telepon dan konfirmasi password
        $validated = $this->validate([
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users,email|max:50',
            'password' => 'required|min:8|max:20|confirmed',
        ]);

        // Hashing password sebelum menyimpan
        $validated['password'] = Hash::make($validated['password']);

        // Membuat akun baru
        $user = User::create($validated);

        Auth::login($user);
        

        return redirect()->route('admin.dashboard');
        
        
        
    }
}
