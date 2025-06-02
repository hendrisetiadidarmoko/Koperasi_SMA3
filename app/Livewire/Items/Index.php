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

        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'price_buy' => 'required|numeric|min:0',
            'barcode' => 'nullable|regex:/^\d{12}$/',
        ]);

        // Buat atau lengkapi barcode 13 digit
        if (empty($validated['barcode'])) {
            $validated['barcode'] = $this->generateEAN13();
        } else {
            $validated['barcode'] = $this->completeEAN13($validated['barcode']);
        }

        // Cek duplikat barcode
        $exists = Item::where('barcode', $validated['barcode'])
            ->when($this->itemId, fn($q) => $q->where('id', '!=', $this->itemId))
            ->exists();

        if ($exists) {
            session()->flash('message', 'Barcode sudah digunakan!');
            $this->dispatch('re_render_table');
            return;
        }

        // Validasi harga beli lebih kecil dari harga jual
        if ($validated['price_buy'] >= $validated['price']) {
            session()->flash('message', 'Harga beli harus lebih rendah dari harga jual.');
            $this->dispatch('re_render_table');
            $this->closeModal();
            return;
        }

        // Simpan data
        if ($this->itemId) {
            $item = Item::find($this->itemId);
            if ($item) {
                $item->update($validated);
                session()->flash('message', 'Barang berhasil diperbarui!');
            }
        } else {
            $validated['user_id'] = Auth::id();
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
            $this->barcode = substr($item->barcode, 0, 12);
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
    private function generateEAN13()
    {
        $code = '';
        for ($i = 0; $i < 12; $i++) {
            $code .= mt_rand(0, 9);
        }
        return $this->completeEAN13($code);
    }

    private function completeEAN13($code12)
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $digit = (int) $code12[$i];
            $sum += $i % 2 === 0 ? $digit : $digit * 3;
        }
        $checkDigit = (10 - ($sum % 10)) % 10;
        return $code12 . $checkDigit;
    }



}
