<?php

namespace App\Livewire\Transaction;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.transaction.index')->layoutData([
            'title' => 'Koprasi - SMA N 3 Purwokerto',
        ]);
    }
}
