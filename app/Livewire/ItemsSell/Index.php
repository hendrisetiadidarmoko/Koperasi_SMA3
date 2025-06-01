<?php

namespace App\Livewire\ItemsSell;

use App\Models\Item;
use Livewire\Component;
use App\Models\ItemSell;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class Index extends Component
{
    public $modalId = 'items_sellmodal';
    public $price;
    public $name;
    public $itemId = null;
    public $items = [];
    public $count = 1, $id_item, $created_at;
    public $barcode;
    public function mount() 
    {
        $this->items = Item::all(); 
    }
    
    
    public function render()
    {
        return view('livewire.items-sell.index', [
            'items' => $this->items
        ])->layoutData([
            'title' => 'Koprasi - SMA N 3 Purwokerto',
        ]);
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
        ]);

        $item = Item::find($this->id_item);
        
        if ($this->itemId) {
            // Update existing ItemPurchase
            $itemPurchase = ItemSell::find($this->itemId);
            if ($itemPurchase) {
                $item->count -= ($this->count - $itemPurchase->count);
                $itemPurchase->update($validated);
                session()->flash('message', 'Barang berhasil diperbarui!');
            }
        } else {
            // Create a new ItemPurchase
            if ($item->count < $this->count) {
                session()->flash('message', sprintf('Jumlah barang tidak mencukupi, hanya tersisa %d!', $item->count));
                $this->resetExcept('items');
                $this->dispatch('re_render_table');
                $this->closeModal();
                return;
            }
            $validated['user_id'] = Auth::id(); 
            ItemSell::create($validated);
            $item->count -= $this->count;
            session()->flash('message', 'Barang berhasil ditambahkan!');
        }

        $item->save();

        $this->resetExcept('items');
        $this->dispatch('re_render_table');
        $this->closeModal();
    }

    public function editRow($id)
    {
        $itemPurchase = ItemSell::find($id);
        if ($itemPurchase) {
            $this->itemId = $itemPurchase->id;
            $this->id_item = $itemPurchase->id_item;
            $this->price = $itemPurchase->price;
            $this->count = $itemPurchase->count;
            $this->dispatch('show-modal');
        }
    }

    public function deleteRow($id)
    {
        $itemPurchase = ItemSell::find($id);
        if ($itemPurchase) {
            $item = Item::find($itemPurchase->id_item);
            
            if ($item) {
                $item->count += $itemPurchase->count;
                $item->save(); 
            }

            $itemPurchase->delete(); // Hapus ItemPurchase
            session()->flash('message', 'Barang berhasil dihapus!');
        } else {
            session()->flash('message', 'Barang tidak ditemukan!');
        }

        $this->dispatch('re_render_table');
    }
    public function updatedIdItem($value)
    {
        $item = Item::find($value);
        if ($item) {
            $this->price = $item->price;
        } else {
            $this->price = null;
        }
    }



    public function scan()
    {
        $this->validate([
            'barcode' => 'required|digits:13',
        ]);


        $item = Item::where('barcode', $this->barcode)->first();

        if (!$item) {
            session()->flash('error', 'Produk tidak ditemukan.');
            return;
        }

        if ($item->count <= 0) {
            session()->flash('error', 'Stok barang habis.');
            return;
        }

        $data = [
            'id_item' => $item->id,
            'price' => $item->price,
            'count' => 1,
            'user_id' => Auth::id(),
        ];

        ItemSell::create($data);

        $item->count -= 1;
        $item->save();

        session()->flash('message', 'Barang berhasil ditambahkan melalui scan.');

        // Kosongkan input setelah submit
        $this->reset('barcode');
        $this->dispatch('re_render_table');
    }
}

