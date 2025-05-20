<?php

namespace App\Livewire\Pdf;

use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Item;
use Carbon\Carbon;

class Index extends Component
{
    public $items = [];

    public $grandTotalSupply = 0;

    public $grandTotalPurchase = 0;
    public $grandTotalSell = 0;

    public $grandTotalLastMonth = 0;

    public $grandTotalSellRL = 0;
    public $grandTotalBuyRL = 0;
    public $grandTotalRL = 0;
    public $grandTotal=0;

    public $year, $month;

    public function mount($year, $month)
    {
        $this->year = $year;
        switch(strtolower($month)) {
            case 'januari':
                $this->month = '01';
                break;
            case 'februari':
                $this->month = '02';
                break;
            case 'maret':
                $this->month = '03';
                break;
            case 'april':
                $this->month = '04';
                break;
            case 'mei':
                $this->month = '05';
                break;
            case 'juni':
                $this->month = '06';
                break;
            case 'juli':
                $this->month = '07';
                break;
            case 'agustus':
                $this->month = '08';
                break;
            case 'september':
                $this->month = '09';
                break;
            case 'oktober':
                $this->month = '10';
                break;
            case 'november':
                $this->month = '11';
                break;
            case 'desember':
                $this->month = '12';
                break;
            default:
                $this->month = '01';
                break;
        }

        // Query data untuk tabel
        $this->items = Item::all()->map(function ($item) {
            // Hitung stok bulan sebelumnya
            $previousMonthStock = $this->getPreviousMonthStock($item);
            $previousMonthStockTotal = $this->getPreviousMonthStockTotal($item);
            $totalBuy = $this->getTotalBuy($item);
            $priceBuy = $this->getPriceBuy($item);
            $priceTotal = $this->getPriceTotalBuy($item);

            $totalItem = $this->getTotalItem($item);
            $priceItem = $this->getPriceItem($item);
            $priceTotalItem = $this->getPriceTotalItem($item);

            $totalSell = $this->getTotalSell($item);
            $priceSell = $this->getPriceSell($item);
            $priceTotalSell = $this->getPriceTotalSell($item);

            $totalRL = $this->getTotalRL($item);
            $priceBuyRL = $this->getPriceBuyRL($item);
            $priceSellRL = $this->getPriceSellRL($item);
            $priceTotalRL = $this->getPriceTotalRL($item);

            $remainingStock = $this->getRemainingStock($item);
            $remainingStockPriceTotal = $this->getRemainingStockPriceTotal($item);

            // Tambahkan previous_stock ke item
            $item->previous_stock = $previousMonthStock;
            $item->previous_stock_total = $previousMonthStockTotal;
            $item->total_buy = $totalBuy;
            $item->price_buy = $priceBuy;
            $item->price_total = $priceTotal;
            $item->total_item = $totalItem;
            $item->price_item = $priceItem;
            $item->price_total_item = $priceTotalItem;
            $item->total_sell = $totalSell;
            $item->price_sell = $priceSell;
            $item->price_total_sell = $priceTotalSell;
            $item->total_RL = $totalRL;
            $item->price_sell_RL = $priceSellRL;
            $item->price_buy_Rl = $priceBuyRL;
            $item->price_total_RL = $priceTotalRL;
            $item->total_remaining_stock = $remainingStock;
            $item->price_total_remaining_stock = $remainingStockPriceTotal;
            return $item;
        });
    }

    public function render()
    {
        return view('livewire.pdf.index')->layout('livewire.pdf.app');
    }

    public function generatePdf($year, $month)
    {
        $this->year = $year;
        
        $this->month = $month;
        // Query data untuk tabel

        $this->grandTotalSupply = 0;
        $this->grandTotalPurchase = 0;
        $this->grandTotalSell = 0;
        $this->grandTotalLastMonth = 0;
        $this->grandTotalRL = 0;
        $this->grandTotalSellRL = 0;
        $this->grandTotalBuyRL = 0;
        $this->grandTotal=0;
        
        $this->items = Item::all()->map(function ($item) {
            // Hitung stok bulan sebelumnya
            $previousMonthStock = $this->getPreviousMonthStock($item);
            $previousMonthStockTotal = $this->getPreviousMonthStockTotal($item);
            $totalBuy = $this->getTotalBuy($item);
            $priceBuy = $this->getPriceBuy($item);
            $priceTotal = $this->getPriceTotalBuy($item);

            $totalItem = $this->getTotalItem($item);
            $priceItem = $this->getPriceItem($item);
            $priceTotalItem = $this->getPriceTotalItem($item);

            $totalSell = $this->getTotalSell($item);
            $priceSell = $this->getPriceSell($item);
            $priceTotalSell = $this->getPriceTotalSell($item);

            $totalRL = $this->getTotalRL($item);
            $priceSellRL = $this->getPriceBuyRL($item);
            $priceBuyRL = $this->getPriceSellRL($item);
            $priceTotalRL = $this->getPriceTotalRL($item);

            $remainingStock = $this->getRemainingStock($item);
            $remainingStockPriceTotal = $this->getRemainingStockPriceTotal($item);

            // Tambahkan previous_stock ke item
            $item->previous_stock = $previousMonthStock;
            $item->previous_stock_total = $previousMonthStockTotal;
            $item->total_buy = $totalBuy;
            $item->price_buy = $priceBuy;
            $item->price_total = $priceTotal;
            $item->total_item = $totalItem;
            $item->price_item = $priceItem;
            $item->price_total_item = $priceTotalItem;
            $item->total_sell = $totalSell;
            $item->price_sell = $priceSell;
            $item->price_total_sell = $priceTotalSell;
            $item->total_RL = $totalRL;
            $item->price_sell_RL = $priceSellRL;
            $item->price_buy_Rl = $priceBuyRL;
            $item->price_total_RL = $priceTotalRL;
            $item->total_remaining_stock = $remainingStock;
            $item->price_total_remaining_stock = $remainingStockPriceTotal;
            return $item;
        });
        $data = [
            'items' => $this->items,
            'grandTotalSupply' => $this->grandTotalSupply,
            'grandTotalPurchase' => $this->grandTotalPurchase,
            'grandTotalSell' => $this->grandTotalSell,
            'grandTotalRL' => $this->grandTotalRL,
            'grandTotalLastMonth' => $this->grandTotalLastMonth,
            'grandTotalBuyRL' => $this->grandTotalBuyRL,
            'grandTotalSellRL' => $this->grandTotalSellRL,
            'grandTotal' =>$this->grandTotal,
        ];

        // Render PDF dari tampilan view
        $pdf = Pdf::loadView('report-pdf.index', $data);

        // Unduh file PDF
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'laporan-bulanan.pdf');
    }

    /**
     * Menghitung stok bulan sebelumnya untuk sebuah item
     */

     public function getRemainingStock($item){
        // Calculate Jumlah Persediaan Awal (previous month's stock + sales - purchases)
        $originalMonth = (int)$this->month;
        $originalYear = (int)$this->year;

        if ($originalMonth == 1) {
            $previousMonth = 12;
            $previousYear = $originalYear - 1;
        } else {
            $previousMonth = $originalMonth - 1;
            $previousYear = $originalYear;
        }

        // Get the total stock (initial inventory) for the previous month
        $previousMonthStock = $item->itemPurchases
            ->filter(function ($purchase) use ($previousMonth, $previousYear) {
                $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                return $purchaseDate->month === $previousMonth && $purchaseDate->year === $previousYear;
            })
            ->sum('count') ?? 0;

        // Calculate Jumlah Persediaan Awal (Initial stock)
        $initialStock = $previousMonthStock;

        // Calculate Jumlah Pembelian
        $monthPurchasesCurrent = $item->itemPurchases->filter(function ($purchase) {
            $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
            return $purchaseDate->month === (int)$this->month && $purchaseDate->year === (int)$this->year;
        });
        $totalPurchaseCurrent = $monthPurchasesCurrent->sum('count') ?? 0;

        $monthSells = $item->itemSells->filter(function ($sell) {
            $sellDate = \Carbon\Carbon::parse($sell->created_at);
            return $sellDate->month === (int)$this->month && $sellDate->year === (int)$this->year;
        });

        $totalSellCurrent = $monthSells->sum('count') ?? 0;

        // Calculate Jumlah Keadaan Barang (Initial stock + current month purchases)
        return $initialStock + $totalPurchaseCurrent - $totalSellCurrent;
    }

    public function getRemainingStockPriceTotal($item){
        // Calculate Sisa Barang Akhir Bulan
        $originalMonth = (int)$this->month;
        $originalYear = (int)$this->year;

        if ($originalMonth == 1) {
            $previousMonth = 12;
            $previousYear = $originalYear - 1;
        } else {
            $previousMonth = $originalMonth - 1;
            $previousYear = $originalYear;
        }

        // Get the total stock (initial inventory) for the previous month
        $previousMonthStock = $item->itemPurchases
            ->filter(function ($purchase) use ($previousMonth, $previousYear) {
                $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                return $purchaseDate->month === $previousMonth && $purchaseDate->year === $previousYear;
            })
            ->sum('count') ?? 0;

        // Calculate Initial Stock
        $initialStock = $previousMonthStock;

        // Calculate Purchases for the Current Month
        $monthPurchasesCurrent = $item->itemPurchases->filter(function ($purchase) {
            $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
            return $purchaseDate->month === (int)$this->month && $purchaseDate->year === (int)$this->year;
        });
        $totalPurchaseCurrent = $monthPurchasesCurrent->sum('count') ?? 0;

        // Calculate Sales for the Current Month
        $monthSells = $item->itemSells->filter(function ($sell) {
            $sellDate = \Carbon\Carbon::parse($sell->created_at);
            return $sellDate->month === (int)$this->month && $sellDate->year === (int)$this->year;
        });
        $totalSellCurrent = $monthSells->sum('count') ?? 0;

        // Calculate Sisa Barang Akhir Bulan
        $sisaBarangAkhir = $initialStock + $totalPurchaseCurrent - $totalSellCurrent;

        // Get Harga Barang
        $hargaBarang = $item->price ?? 0;

        // Calculate Total Harga Barang
        $totalHargaBarang = $sisaBarangAkhir * $hargaBarang;

        $this->grandTotalLastMonth+=$totalHargaBarang;

        // Format the result
        return $totalHargaBarang;
    }
    public function getTotalRL($item){
        $monthSells = $item->itemSells->filter(function ($sell) {
            $sellDate = \Carbon\Carbon::parse($sell->created_at);
            return $sellDate->month === (int)$this->month && $sellDate->year === (int)$this->year;
        });
        return $monthSells->sum('count') ?? 0;
    }
    public function getPriceBuyRL($item){
        // Memfilter pembelian yang terjadi di bulan November  $this->year
        $monthPurchases = $item->itemPurchases->filter(function ($purchase) {
            $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
            return $purchaseDate->month === (int)$this->month && $purchaseDate->year === (int)$this->year;
        });
        $total = $monthPurchases->sum(function ($purchase) {
            return ($purchase->count ?? 0) * ($purchase->price ?? 0);
        });

        $this->grandTotalBuyRL += $total;
        
        return $total;
    }
    
    public function getPriceSellRL($item){
        $monthSells = $item->itemSells->filter(function ($sell) {
            $sellDate = \Carbon\Carbon::parse($sell->created_at);
            return $sellDate->month === (int)$this->month && $sellDate->year === (int)$this->year;
        });
        $total = $monthSells->sum(function ($sell) {
            return ($sell->count ?? 0) * ($sell->price ?? 0);
        });

        $this->grandTotalSellRL += $total;
        return $total;
    }

    public function getPriceTotalRL($item){
        $monthPurchases = $item->itemPurchases->filter(function ($purchase) {
            $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
            return $purchaseDate->month === (int)$this->month && $purchaseDate->year === (int)$this->year;
        });

        $monthSells = $item->itemSells->filter(function ($sell) {
            $sellDate = \Carbon\Carbon::parse($sell->created_at);
            return $sellDate->month === (int)$this->month && $sellDate->year === (int)$this->year;
        });

        $totalPurchases = $monthPurchases->sum(function ($purchase) {
            return ($purchase->count ?? 0) * ($purchase->price ?? 0);
        });

        $totalSells = $monthSells->sum(function ($sell) {
            return ($sell->count ?? 0) * ($sell->price ?? 0);
        });

        $total = $totalPurchases - $totalSells;
        
        $this -> grandTotalRL += $total;
        // Mengembalikan total harga dengan format yang sesuai
        return $total;
    }

    public function getTotalSell($item){
        $monthSells = $item->itemSells->filter(function ($sell) {
            $sellDate = \Carbon\Carbon::parse($sell->created_at);
            return $sellDate->month === (int)$this->month && $sellDate->year === (int)$this->year;
        });
        return $monthSells->sum('count') ?? 0;
    }

    public function getPriceSell($item){
        // Memfilter pembelian yang terjadi di bulan November  $this->year
        $monthSells = $item->itemSells->filter(function ($sell) {
            $sellDate = \Carbon\Carbon::parse($sell->created_at);
            return $sellDate->month === (int)$this->month && $sellDate->year === (int)$this->year;
        });

        // Mendapatkan semua harga yang unik (tidak duplikat)
        $uniquePrices = $monthSells->pluck('price')->unique();

        // Jika tidak ada harga ditemukan, kembalikan 0
        if ($uniquePrices->isEmpty()) {
            return '0';
        }

        // Menggabungkan harga yang berbeda dengan koma sebagai pemisah
        return $uniquePrices->map(function ($price) {
            return number_format($price ?? 0, 0, '.', ',');
        })->implode(', ');
    }

    public function getPriceTotalSell($item){
        // Memfilter pembelian yang terjadi di bulan November  $this->year
        $monthSells = $item->itemSells->filter(function ($sell) {
            $sellDate = \Carbon\Carbon::parse($sell->created_at);
            return $sellDate->month === (int)$this->month && $sellDate->year === (int)$this->year;
        });
        $total = $monthSells->sum(function ($sell) {
            return ($sell->count ?? 0) * ($sell->price ?? 0);
        });
        
        // Mengembalikan total harga dengan format yang sesuai
        $this -> grandTotalSell += $total;
        return $total; // Format total
    }
    public function getPreviousMonthStock($item)
    {
        // Store original month and year to avoid global changes
        $originalMonth = (int)$this->month;
        $originalYear = (int)$this->year;

        // Determine the previous month and year
        if ($originalMonth == 1) {
            $previousMonth = 12; // December
            $previousYear = $originalYear - 1; // Previous year
        } else {
            $previousMonth = $originalMonth - 1; // Previous month
            $previousYear = $originalYear; // Same year
        }

        // Get the total stock (initial inventory) for the previous month
        $previousMonthStock = $item->itemPurchases
            ->filter(function ($purchase) use ($previousMonth, $previousYear) {
                $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                return $purchaseDate->month === $previousMonth && $purchaseDate->year === $previousYear;
            })
            ->sum('count') ?? 0;


        // Return the stock for the current month, considering the previous month's stock
        return $previousMonthStock;
    }

    public function getPreviousMonthStockTotal($item){
         // Store original month and year to avoid global changes
        $originalMonth = (int)$this->month;
        $originalYear = (int)$this->year;

         // Determine the previous month and year
        if ($originalMonth == 1) {
             $previousMonth = 12; // December
             $previousYear = $originalYear - 1; // Previous year
        } else {
             $previousMonth = $originalMonth - 1; // Previous month
             $previousYear = $originalYear; // Same year
        }

         // Get the total stock (initial inventory) for the previous month
        $previousMonthStock = $item->itemPurchases
            ->filter(function ($purchase) use ($previousMonth, $previousYear) {
                $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                return $purchaseDate->month === $previousMonth && $purchaseDate->year === $previousYear;
            })
            ->sum('count') ?? 0;

        $price = $item->price ?? 0;
        $result = $previousMonthStock * $price;

         // Add to the grand total
         $this->grandTotalSupply += $result;

         return $result;
    }
    public function getTotalBuy($item){
        $monthPurchases = $item->itemPurchases->filter(function ($purchase) {
            $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
            return $purchaseDate->month === (int)$this->month && $purchaseDate->year === (int)$this->year;
        });
        return $monthPurchases->sum('count') ?? 0;
    }

    public function getPriceBuy($item){
        // Memfilter pembelian yang terjadi di bulan November  $this->year
        $monthPurchases = $item->itemPurchases->filter(function ($purchase) {
            $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
            return $purchaseDate->month === (int)$this->month && $purchaseDate->year === (int)$this->year;
        });

        // Mendapatkan semua harga yang unik (tidak duplikat)
        $uniquePrices = $monthPurchases->pluck('price')->unique();

        // Jika tidak ada harga ditemukan, kembalikan 0
        if ($uniquePrices->isEmpty()) {
            return '0';
        }

        // Menggabungkan harga yang berbeda dengan koma sebagai pemisah
        return $uniquePrices->map(function ($price) {
            return number_format($price ?? 0, 0, '.', ',');
        })->implode(', ');
    }
    public function getPriceTotalBuy($item){
        // Memfilter pembelian yang terjadi di bulan November  $this->year
        $monthPurchases = $item->itemPurchases->filter(function ($purchase) {
            $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
            return $purchaseDate->month === (int)$this->month && $purchaseDate->year === (int)$this->year;
        });
        $total = $monthPurchases->sum(function ($purchase) {
            return ($purchase->count ?? 0) * ($purchase->price ?? 0);
        });

        
        $this->grandTotalPurchase += $total;
        // Mengembalikan total harga dengan format yang sesuai
        return $total;
        
    }

    public function getTotalItem($item){
        // Calculate Jumlah Persediaan Awal (previous month's stock + sales - purchases)
        $originalMonth = (int)$this->month;
        $originalYear = (int)$this->year;

        if ($originalMonth == 1) {
            $previousMonth = 12;
            $previousYear = $originalYear - 1;
        } else {
            $previousMonth = $originalMonth - 1;
            $previousYear = $originalYear;
        }

        // Get the total stock (initial inventory) for the previous month
        $previousMonthStock = $item->itemPurchases
            ->filter(function ($purchase) use ($previousMonth, $previousYear) {
                $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                return $purchaseDate->month === $previousMonth && $purchaseDate->year === $previousYear;
            })
            ->sum('count') ?? 0;

        // Calculate Jumlah Persediaan Awal (Initial stock)
        $initialStock = $previousMonthStock;

        // Calculate Jumlah Pembelian
        $monthPurchasesCurrent = $item->itemPurchases->filter(function ($purchase) {
            $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
            return $purchaseDate->month === (int)$this->month && $purchaseDate->year === (int)$this->year;
        });
        $totalPurchaseCurrent = $monthPurchasesCurrent->sum('count') ?? 0;

        // Calculate Jumlah Keadaan Barang (Initial stock + current month purchases)
        return $initialStock + $totalPurchaseCurrent;
    }

    public function getPriceItem($item){
        // Get the "Harga Persediaan Awal" (initial stock price)
        $initialStockPrice = $item->price ?? 0;
            
        // Memfilter pembelian yang terjadi di bulan yang ditentukan
        $monthPurchases = $item->itemPurchases->filter(function ($purchase) {
            $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
            return $purchaseDate->month === (int)$this->month && $purchaseDate->year === (int)$this->year;
        });

        // Mendapatkan semua harga yang unik (tidak duplikat) dari pembelian
        $uniquePurchasePrices = $monthPurchases->pluck('price')->unique();

        // Jika tidak ada harga ditemukan dari pembelian, set ke 0
        if ($uniquePurchasePrices->isEmpty()) {
            $uniquePurchasePrices = collect([0]);
        }

        // Combine the "Harga Persediaan Awal" and purchase prices, remove duplicates
        $allPrices = $uniquePurchasePrices->push($initialStockPrice)->unique();

        // Return the combined prices as a comma-separated string
        return $allPrices->map(function ($price) {
            return number_format($price ?? 0, 0, '.', ',');
        })->implode(', ');
    }

    public function getPriceTotalItem($item){
        // Calculate Jumlah Keadaan Barang
        $originalMonth = (int)$this->month;
        $originalYear = (int)$this->year;

        if ($originalMonth == 1) {
            $previousMonth = 12;
            $previousYear = $originalYear - 1;
        } else {
            $previousMonth = $originalMonth - 1;
            $previousYear = $originalYear;
        }

        // Get the total stock (initial inventory) for the previous month
        $previousMonthStock = $item->itemPurchases
            ->filter(function ($purchase) use ($previousMonth, $previousYear) {
                $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                return $purchaseDate->month === $previousMonth && $purchaseDate->year === $previousYear;
            })
            ->sum('count') ?? 0;

        $initialStock = $previousMonthStock;

        // Calculate Jumlah Pembelian for the current month
        $monthPurchasesCurrent = $item->itemPurchases->filter(function ($purchase) {
            $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
            return $purchaseDate->month === (int)$this->month && $purchaseDate->year === (int)$this->year;
        });

        $totalPurchaseCurrent = $monthPurchasesCurrent->sum('count') ?? 0;

        $jumlahKeadaanBarang = $initialStock + $totalPurchaseCurrent;

        // Calculate Harga Keadaan Barang
        $initialStockPrice = $item->price ?? 0;

        $monthPurchases = $item->itemPurchases->filter(function ($purchase) {
            $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
            return $purchaseDate->month === (int)$this->month && $purchaseDate->year === (int)$this->year;
        });

        $uniquePurchasePrices = $monthPurchases->pluck('price')->unique();

        if ($uniquePurchasePrices->isEmpty()) {
            $uniquePurchasePrices = collect([0]);
        }

        $allPrices = $uniquePurchasePrices->push($initialStockPrice)->unique();

        // Average Price (assuming total value calculation uses average of all prices)
        $averagePrice = $allPrices->average();

        // Calculate Total Keadaan Barang (Jumlah Keadaan Barang * Average Price)
        $totalKeadaanBarang = $jumlahKeadaanBarang * ($averagePrice ?? 0);

        $this->grandTotal += $totalKeadaanBarang;
        // Format the result
        return $totalKeadaanBarang;
    }
}
