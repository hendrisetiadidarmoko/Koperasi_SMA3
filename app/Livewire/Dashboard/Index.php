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
    public $topSellingItems = [];

    public $selectedMonth = null ;
    public $selectedYear= null;
    public function mount()
    {

        $this->selectedMonth = now()->month;
        $this->selectedYear = now()->year;

        
        $topItemsThisMonth = ItemSell::select('items.name as item_name')
            ->selectRaw('SUM(items_sell.count) as total_sold')
            ->join('items', 'items.id', '=', 'items_sell.id_item')
            ->whereMonth('items_sell.created_at', $this->selectedMonth)
            ->whereYear('items_sell.created_at', $this->selectedYear)
            ->groupBy('items.name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        foreach ($topItemsThisMonth as $item) {
            $this->topSellingItems['labels'][] = $item->item_name;
            $this->topSellingItems['data'][] = $item->total_sold;
        }

        $this->getChart();

        $stocks = Item::select('name', 'count')->get();
        $this->stockData = [
            'labels' => $stocks->pluck('name')->toArray(),
            'data' => $stocks->pluck('count')->toArray(),
        ];

        $this->itemCount = Item::count();
        $this->itemSellCount = ItemSell::count();
        $this->itemBuyCount = ItemPurchase::count();
        $this->userCount = User::count();
        $user = Auth::user();
        $this->name = $user->name;

        $startDate = Carbon::create(2025, 1, 1); // Awal bulan Agustus 2024
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
            ->whereMonth('items_sell.created_at', $this->selectedMonth)
            ->whereYear('items_sell.created_at', $this->selectedYear)
            ->selectRaw('SUM(items_sell.count) as total_sold')
            ->join('items', 'items.id', '=', 'items_sell.id_item')
            ->groupBy('items.name')
            ->get();

        foreach ($sales as $sale) {
            $this->itemSalesData['labels'][] = $sale->item_name; // Menggunakan nama barang
            $this->itemSalesData['data'][] = $sale->total_sold;
        }

        $purchases = ItemPurchase::select('items.name as item_name')
        ->whereMonth('items_purchase.created_at', $this->selectedMonth)
        ->whereYear('items_purchase.created_at', $this->selectedYear)
        ->selectRaw('SUM(items_purchase.count) as total_bought')
        ->join('items', 'items.id', '=', 'items_purchase.id_item')
        ->groupBy('items.name')
        ->get();

        foreach ($purchases as $purchase) {
            $this->itemPurchaseData['labels'][] = $purchase->item_name;
            $this->itemPurchaseData['data'][] = $purchase->total_bought;
        }
    }

    public function getChart()
    {
        $items = ItemSell::select('items.name as item_name')
            ->selectRaw('SUM(items_sell.count) as total_sold')
            ->join('items', 'items.id', '=', 'items_sell.id_item')
            ->whereMonth('items_sell.created_at', $this->selectedMonth)
            ->whereYear('items_sell.created_at', $this->selectedYear)
            ->groupBy('items.name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        $labels = $items->pluck('item_name')->toArray();
        $data = $items->pluck('total_sold')->toArray();

        $sales = ItemSell::select('items.name as item_name')
            ->whereMonth('items_sell.created_at', $this->selectedMonth)
            ->whereYear('items_sell.created_at', $this->selectedYear)
            ->selectRaw('SUM(items_sell.count) as total_sold')
            ->join('items', 'items.id', '=', 'items_sell.id_item')
            ->groupBy('items.name')
            ->get();

        $labelsSale = $sales->pluck('item_name')->toArray();
        $dataSale = $sales->pluck('total_sold')->toArray();
        $totalSoldSum = array_sum($dataSale);

        $purchases = ItemPurchase::select('items.name as item_name')
        ->whereMonth('items_purchase.created_at', $this->selectedMonth)
        ->whereYear('items_purchase.created_at', $this->selectedYear)
        ->selectRaw('SUM(items_purchase.count) as total_bought')
        ->join('items', 'items.id', '=', 'items_purchase.id_item')
        ->groupBy('items.name')
        ->get();

        $labelsPurchase = $purchases->pluck('item_name')->toArray();
        $dataPurchase = $purchases->pluck('total_bought')->toArray();
        $totalPurchaseSum = array_sum($dataPurchase);
        
        $this->dispatch('chartUpdated', $labels, $data, $labelsSale, $totalSoldSum, $labelsPurchase, $totalPurchaseSum);
    }



    public function render()
    {
        return view('livewire.dashboard.index')->layoutData([
            'title' => 'Koprasi - SMA N 3 Purwokerto',
        ]);
    }
}
