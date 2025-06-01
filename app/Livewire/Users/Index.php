<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    public $modalId = 'user_modal';
    public $name;
    public $role, $userId;

    public function render()
    {
        return view('livewire.users.index', [
            
        ])->layoutData([
            'title' => 'Koprasi - SMA N 3 Purwokerto',
        ]);
    }

    protected $listeners = [
        'delete' => 'deleteRow',
        'edit' => 'editRow',
        'editPassword' => 'editPassword'
    ];

    public function closeModal()
    {
        $this->reset();
        $this->resetErrorBag();
        $this->dispatch('close-modal');
    }

    public function save()
    {
        $this->resetErrorBag();

        $validated = $this->validate([
            'name' => 'required',
            'role' => 'required',
        ]);

        if ($this->userId) {
            $user = User::find($this->userId);
            if ($user) {
                $user->update([
                    'name' => $validated['name'],
                    'role' => $validated['role'],
                ]);
                session()->flash('message', 'User role berhasil diperbarui!');
            }
        }

        $this->reset(['userId', 'name', 'role']);
        $this->dispatch('re_render_table');
        $this->closeModal();
    }


    public function editRow($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id; 
        $this->role = $user->role;
        $this->name = $user->name;

        $this->dispatch('show-modal'); // Show the modal
    }

    public function editPassword($id)
    {
        $this->userId = $id;

        $user = User::find($id);

        if ($user) {
            $randomPassword = Str::random(8);

            $user->update([
                'password' => Hash::make($randomPassword),
            ]);

            session()->flash('message', 'Password berhasil diperbarui. Password baru: ' . $randomPassword);
        } else {
            session()->flash('error', 'User tidak ditemukan.');
        }
    }

    public function deleteRow($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            session()->flash('message', 'User berhasil dihapus!');
        } else {
            session()->flash('message', 'User tidak ditemukan!');
        }

        $this->dispatch('re_render_table');
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login');
    }
}
