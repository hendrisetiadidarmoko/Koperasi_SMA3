<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class Index extends Component
{
    public function render()
    {
        return view('livewire.profile.index');
    }

    public $name;
    public $email;
    public $phone_number;
    public $password;
    public $password_confirmation;

    use WithFileUploads;
    public $photo;
    public function mount()
    {
        // Initialize fields with the current user's data
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone_number = $user->phone_number;
    }
    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:1024', // Maksimum ukuran file 1MB
        ]);
    }

    public function update()
    {
        // Validate the input data
        $this->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:50',
            'phone_number' => 'required|digits_between:10,15',
        ]);

        // Update the user's profile information
        $user = Auth::user();
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
        ]);

        session()->flash('message', 'Profile updated successfully.');
    }

    // public function updatePhoto()
    // {
    //     $this->validate([
    //         'photo' => 'required|image|max:1024',
    //     ]);

    //     $user = Auth::user();
    //     $path = $this->photo->store('profile-photos', 'public');

    //     // Hapus foto lama jika ada
    //     if ($user->profile_photo) {
    //         \Storage::disk('public')->delete($user->profile_photo);
    //     }

    //     $user->profile_photo = $path;
    //     $user->save();

    //     session()->flash('message', 'Foto profil berhasil diubah!');
    // }

    public function updatePass()
    {
        // Validate the password fields
        $this->validate([
            'password' => 'required|string|min:8|confirmed|max:60',
        ]);

        // Update the user's password
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($this->password),
        ]);

        session()->flash('message', 'Password updated successfully.');
    }
}
