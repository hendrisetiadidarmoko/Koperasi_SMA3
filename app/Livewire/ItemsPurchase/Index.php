<?php

namespace App\Livewire\ItemsPurchase;

use App\Models\Item;
use Livewire\Component;
use App\Models\ItemPurchase;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Index extends Component
{
    public $modalId = 'items_purchase_modal';
    public $price;
    public $name;
    public $itemId = null;
    public $items = [];
    public $count, $id_item, $created_at;

    public function mount() 
    {
        $this->items = Item::all(); 
    }

    public function render()
    {
        return view('livewire.items-purchase.index', [
            'items' => $this->items
        ])->layoutData(['title' => 'Pembelian Barang']);
    }

    protected $listeners = [
        'delete' => 'deleteRow',
        'edit' => 'editRow',
    ];

    public function closeModal()
    {
        $this->resetExcept('items');
        $this->resetErrorBag();
        $this->dispatch('close-modal');
    }

    public function save()
    {
        $this->resetErrorBag();

        // Validate the form fields
        $validated = $this->validate([
            'id_item' => 'required|exists:items,id',
            'price' => 'required|numeric|min:0',
            'count' => 'required|numeric|min:1',
            'created_at' => 'required'
        ]);
        
        

        $item = Item::find($this->id_item);
        
        if ($this->itemId) {
            // Update existing ItemPurchase
            $itemPurchase = ItemPurchase::find($this->itemId);
            if ($itemPurchase) {
                $item->count += ($this->count - $itemPurchase->count);
                $itemPurchase->update($validated);
                session()->flash('message', 'Barang berhasil diperbarui!');
            }
        } else {
            // Create a new ItemPurchase
            $validated['user_id'] = Auth::id(); 
            ItemPurchase::create($validated);
            $item->count += $this->count;
            session()->flash('message', 'Barang berhasil ditambahkan!');
        }

        $item->save();

        $this->reset();
        $this->dispatch('re_render_table');
        $this->closeModal();
    }

    public function editRow($id)
    {
        $itemPurchase = ItemPurchase::find($id);
        if ($itemPurchase) {
            $this->itemId = $itemPurchase->id;
            $this->id_item = $itemPurchase->id_item;
            $this->price = $itemPurchase->price;
            $this->count = $itemPurchase->count;
            $this->created_at = Carbon::parse($itemPurchase->created_at)->format('Y-m-d\TH:i');
            $this->dispatch('show-modal');
        }
    }

    public function deleteRow($id)
    {
        $itemPurchase = ItemPurchase::find($id);
        if ($itemPurchase) {
            $item = Item::find($itemPurchase->id_item);
            
            if ($item) {
                $item->count -= $itemPurchase->count;
                $item->save(); 
            }

            $itemPurchase->delete(); // Hapus ItemPurchase
            session()->flash('message', 'Barang berhasil dihapus!');
        } else {
            session()->flash('message', 'Barang tidak ditemukan!');
        }

        $this->dispatch('re_render_table');
    }
}
