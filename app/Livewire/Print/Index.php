<?php

namespace App\Livewire\Print;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.print.index')->layoutData([
            'title' => 'Koprasi - SMA N 3 Purwokerto',
        ]);
    }
}
