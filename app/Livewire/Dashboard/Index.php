<?php

namespace App\Livewire\Dashboard;

use App\Models\Item;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ItemPurchase;
use App\Models\ItemSell;
use App\Models\User;
use Carbon\Carbon;
class Index extends Component
{
    public $name;
    public $itemCount;
    public $itemSellCount;
    public $itemBuyCount;
    public $userCount;
    public $monthlyProfits = [];
    public $itemSalesData = [];
    public $itemPurchaseData = [];
    public $stockData = [];
    public function mount()
    {
        $stocks = Item::select('name', 'count')->get();
        $this->stockData = [
            'labels' => $stocks->pluck('name')->toArray(),
            'data' => $stocks->pluck('count')->toArray(),
        ];

        $this->itemCount = Item::count();
        $this->itemSellCount = ItemPurchase::count();
        $this->itemBuyCount = ItemSell::count();
        $this->userCount = User::count();
        $user = Auth::user();
        $this->name = $user->name;

        $startDate = Carbon::create(2024, 8, 1); // Awal bulan Agustus 2024
        $endDate = Carbon::now(); // Sampai bulan saat ini

        // Iterasi tiap bulan untuk menghitung keuntungan
        while ($startDate <= $endDate) {
            $monthPurchases = ItemPurchase::whereMonth('created_at', $startDate->month)
                ->whereYear('created_at', $startDate->year)
                ->get();

            $monthSells = ItemSell::whereMonth('created_at', $startDate->month)
                ->whereYear('created_at', $startDate->year)
                ->get();

            $totalPurchases = $monthPurchases->sum(function ($purchase) {
                return ($purchase->count ?? 0) * ($purchase->price ?? 0);
            });

            $totalSells = $monthSells->sum(function ($sell) {
                return ($sell->count ?? 0) * ($sell->price ?? 0);
            });

            $total = $totalSells; 
            $this->monthlyProfits[$startDate->format('Y-m')] = $total;

            $startDate->addMonth();
        }

        $sales = ItemSell::select('items.name as item_name')
            ->selectRaw('SUM(items_sell.count) as total_sold')
            ->join('items', 'items.id', '=', 'items_sell.id_item')
            ->groupBy('items.name')
            ->get();

        foreach ($sales as $sale) {
            $this->itemSalesData['labels'][] = $sale->item_name; // Menggunakan nama barang
            $this->itemSalesData['data'][] = $sale->total_sold;
        }

        $purchases = ItemPurchase::select('items.name as item_name')
        ->selectRaw('SUM(items_purchase.count) as total_bought')
        ->join('items', 'items.id', '=', 'items_purchase.id_item')
        ->groupBy('items.name')
        ->get();

        foreach ($purchases as $purchase) {
            $this->itemPurchaseData['labels'][] = $purchase->item_name;
            $this->itemPurchaseData['data'][] = $purchase->total_bought;
        }
    }
    public function render()
    {
        return view('livewire.dashboard.index');
    }
}
