<?php

namespace App\Livewire\Items;

use App\Models\Item;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $modalId = 'items_modal';
    public $name;
    public $price;
    public $price_buy;
    public $barcode;
    public $itemId = null;
    

    public function render()
    {
        return view('livewire.items.index')
            ->layoutData([
                'title' => 'Koprasi - SMA N 3 Purwokerto',
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
            'barcode' => 'nullable|string|size:13|regex:/^\d{13}$/|unique:items,barcode,' . $this->itemId,
        ]);

        if (empty($validated['barcode'])) {
            $validated['barcode'] = $this->generateEAN13();
        }

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
            $this->price =   $item->price;
            $this->price_buy =   $item->price_buy;
            $this->barcode = $item ->barcode;
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
    public function generateEAN13()
    {
        $code = str_pad(mt_rand(100000000000, 999999999999), 12, '0', STR_PAD_LEFT);
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int)$code[$i] * ($i % 2 === 0 ? 1 : 3);
        }
        $checksum = (10 - ($sum % 10)) % 10;
        return $code . $checksum;
    }

}
