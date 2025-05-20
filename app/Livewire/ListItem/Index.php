<?php

namespace App\Livewire\ListItem;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.list-item.index')->layout('livewire.landing-page.app');
    }
}
