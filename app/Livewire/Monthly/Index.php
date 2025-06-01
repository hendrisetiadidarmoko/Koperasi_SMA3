<?php

namespace App\Livewire\Monthly;

use Livewire\Component;
use App\Models\ItemSell;
use App\Models\ItemPurchase;

class Index extends Component
{
    public $monthsForYear; // Menyimpan bulan untuk tahun tertentu
    public $year; // Menyimpan tahun yang dipilih

    public function render()
    {
        return view('livewire.monthly.index')->layoutData([
            'title' => 'Koprasi - SMA N 3 Purwokerto',
        ]);
    }

    public function mount($year)
    {
        $this->year = $year;

        // Array untuk nama bulan
        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        // Ambil bulan unik dari transaksi di ItemSell dan ItemPurchase untuk tahun yang dipilih
        $sellMonths = ItemSell::selectRaw('MONTH(created_at) as month')
            ->whereYear('created_at', $year)
            ->distinct()
            ->pluck('month');

        $purchaseMonths = ItemPurchase::selectRaw('MONTH(created_at) as month')
            ->whereYear('created_at', $year)
            ->distinct()
            ->pluck('month');

        // Gabungkan bulan dari kedua tabel dan pastikan hanya bulan yang unik
        $mergedMonths = $sellMonths->merge($purchaseMonths)->unique()->sort()->values();

        // Ganti bulan numerik dengan nama bulan
        $this->monthsForYear = $mergedMonths->map(function ($month) use ($monthNames) {
            return $monthNames[$month];
        });
    }
}
