<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Register extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation; 
    public function render()
    {
        return view('livewire.auth.register')->layout('livewire.auth.app');
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
        User::create($validated);

        $this->reset();
        session()->flash('message', 'Akun telah dibuat');
        

        return redirect()->route('auth.login');
        
        
        
    }
}
