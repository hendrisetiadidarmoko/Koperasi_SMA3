<?php

namespace App\Livewire\MonthlyReport;

use Livewire\Component;

class Index extends Component
{


    public $year;
    public $month;

    public function mount($year, $month)
{
    $this->year = $year;
    $this->month = $month;
}


    public function render()
    {
        return view('livewire.monthly-report.index')->layoutData([
            'title' => 'Koprasi - SMA N 3 Purwokerto',
        ]);
    }
    
}
