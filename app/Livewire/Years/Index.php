<?php

namespace App\Livewire\Years;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.years.index');
    }

    public $years;

    public function mount()
    {
        $sellYears = \App\Models\ItemSell::selectRaw('YEAR(created_at) as year');
        $purchaseYears = \App\Models\ItemPurchase::selectRaw('YEAR(created_at) as year');

        $this->years = $sellYears
            ->union($purchaseYears) // Menggabungkan kedua query
            ->distinct() // Menghilangkan duplikasi
            ->orderBy('year', 'desc')
            ->pluck('year');
    }


}
