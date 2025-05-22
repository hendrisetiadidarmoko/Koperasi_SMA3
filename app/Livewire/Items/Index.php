<?php

namespace App\Livewire\Items;

use App\Models\Item;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $modalId = 'items_modal';
    public $name, $price, $price_buy;
    public $itemId = null;
    

    public function render()
    {
        return view('livewire.items.index')
            ->layoutData([
                'title' => 'Barang',
            ]);
    }

    protected $listeners = [
        'delete' => 'deleteRow',
        'edit' => 'editRow',
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

        // Validate the name field
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'price_buy' => 'required|numeric|min:0',
        ]);

        if ($validated['price_buy'] >= $validated['price']) {
            Session()->flash('message', 'Barang pembelian harus lebih randah dari harga penjualan');
            $this->dispatch('re_render_table');
            $this->closeModal();
            return;
        }

        if ($this->itemId) {
            // Update existing Barang
            $item = Item::find($this->itemId);
            if ($item) {
                $item->update($validated);
                session()->flash('message', 'Barang berhasil diperbarui!');
            }
        } else {
            $validated['user_id'] = Auth::id(); 
            // Create a new Barang
            Item::create($validated);
            session()->flash('message', 'Barang berhasil ditambahkan!');
        }

        $this->reset();
        $this->dispatch('re_render_table');
        $this->closeModal();
    }

    public function editRow($id)
    {
        $item = Item::find($id);
        if ($item) {
            $this->itemId = $item->id; 
            $this->name = $item->name; 
            $this->price =  $this->price = number_format($item->price, 0, ',', '.');
            $this->price_buy =  $this->price_buy = number_format($item->price_buy, 0, ',', '.');
            $this->dispatch('show-modal'); 
        }
    }


    public function deleteRow($id)
    {
        $item = Item::find($id);
        if ($item) {
            $item->delete();
            session()->flash('message', 'Barang berhasil dihapus!');
        } else {
            session()->flash('message', 'Barang tidak ditemukan!');
        }

        $this->dispatch('re_render_table');
    }
}
