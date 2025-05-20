<?php

namespace App\Livewire\LandingPage;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.landing-page.index')->layout('livewire.landing-page.app');
    }
}
