<?php

namespace App\Livewire\YearPdf;

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

    public $lastStock = 0;

    public $year, $month;

    public function mount($year)
    {
        $this->year = $year;

        $this->items = collect();

        

        for ($month = 1; $month <= 12; $month++) {
            $this->month = $month;
            $this->grandTotalSupply = 0;
            $this->grandTotalPurchase = 0;
            $this->grandTotalSell = 0;
            $this->grandTotalLastMonth = 0;
            $this->grandTotalRL = 0;
            $this->grandTotalSellRL = 0;
            $this->grandTotalBuyRL = 0;
            $this->grandTotal=0;
            $monthlyData = Item::all()
                ->map(function ($item) use ($month) {
                    // Hitung stok bulan sebelumnya
            $priceBefore = $this ->getPriceBuyBefore($item);
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
            $remainingPriceStock = $this->getRemainingPriceStock($item);
            $remainingStockPriceTotal = $this->getRemainingStockPriceTotal($item);

            // Tambahkan previous_stock ke item
            $item->previous_price = $priceBefore;
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
            $item->total_remaining_price_stock = $remainingPriceStock;
            $item->price_total_remaining_stock = $remainingStockPriceTotal;
            return $item;
                });
    
            // Group the data by month
            $this->items[$month] = $monthlyData;
        }
    }

    public function render()
    {
        return view('livewire.pdf.year')->layout('livewire.pdf.app');
    }

    // public function generatePdf($year)
    // {
    //     $this->year = $year;

    //     $this->items = collect();

        

    //     for ($month = 1; $month <= 12; $month++) {
    //         $this->month = $month;
    //         $this->grandTotalSupply = 0;
    //         $this->grandTotalPurchase = 0;
    //         $this->grandTotalSell = 0;
    //         $this->grandTotalLastMonth = 0;
    //         $this->grandTotalRL = 0;
    //         $this->grandTotalSellRL = 0;
    //         $this->grandTotalBuyRL = 0;
    //         $this->grandTotal=0;
    //         $monthlyData = Item::all()
    //             ->map(function ($item) use ($month) {
    //                 // Hitung stok bulan sebelumnya
    //         $previousMonthStock = $this->getPreviousMonthStock($item);
    //         $previousMonthStockTotal = $this->getPreviousMonthStockTotal($item);
    //         $totalBuy = $this->getTotalBuy($item);
    //         $priceBuy = $this->getPriceBuy($item);
    //         $priceTotal = $this->getPriceTotalBuy($item);

    //         $totalItem = $this->getTotalItem($item);
    //         $priceItem = $this->getPriceItem($item);
    //         $priceTotalItem = $this->getPriceTotalItem($item);

    //         $totalSell = $this->getTotalSell($item);
    //         $priceSell = $this->getPriceSell($item);
    //         $priceTotalSell = $this->getPriceTotalSell($item);

    //         $totalRL = $this->getTotalRL($item);
    //         $priceBuyRL = $this->getPriceBuyRL($item);
    //         $priceSellRL = $this->getPriceSellRL($item);
    //         $priceTotalRL = $this->getPriceTotalRL($item);

    //         $remainingStock = $this->getRemainingStock($item);
    //         $remainingStockPriceTotal = $this->getRemainingStockPriceTotal($item);

    //         // Tambahkan previous_stock ke item
    //         $item->previous_stock = $previousMonthStock;
    //         $item->previous_stock_total = $previousMonthStockTotal;
    //         $item->total_buy = $totalBuy;
    //         $item->price_buy = $priceBuy;
    //         $item->price_total = $priceTotal;
    //         $item->total_item = $totalItem;
    //         $item->price_item = $priceItem;
    //         $item->price_total_item = $priceTotalItem;
    //         $item->total_sell = $totalSell;
    //         $item->price_sell = $priceSell;
    //         $item->price_total_sell = $priceTotalSell;
    //         $item->total_RL = $totalRL;
    //         $item->price_sell_RL = $priceSellRL;
    //         $item->price_buy_Rl = $priceBuyRL;
    //         $item->price_total_RL = $priceTotalRL;
    //         $item->total_remaining_stock = $remainingStock;
    //         $item->price_total_remaining_stock = $remainingStockPriceTotal;
    //         return $item;
    //             });
    
    //         // Group the data by month
    //         $this->items[$month] = $monthlyData;
    //     }
    //     $data = [
    //         'items' => $this->items,
    //         'grandTotalSupply' => $this->grandTotalSupply,
    //         'grandTotalPurchase' => $this->grandTotalPurchase,
    //         'grandTotalSell' => $this->grandTotalSell,
    //         'grandTotalRL' => $this->grandTotalRL,
    //         'grandTotalLastMonth' => $this->grandTotalLastMonth,
    //         'grandTotalBuyRL' => $this->grandTotalBuyRL,
    //         'grandTotalSellRL' => $this->grandTotalSellRL,
    //         'grandTotal' =>$this->grandTotal,
    //     ];

    //     // Render PDF dari tampilan view
    //     $pdf = Pdf::loadView('report-pdf.year', $data);

    //     // Unduh file PDF
    //     return response()->streamDownload(function () use ($pdf) {
    //         echo $pdf->stream();
    //     }, 'laporan-bulanan.pdf');
    // }

    /**
     * Menghitung stok bulan sebelumnya untuk sebuah item
     */

    public function getRemainingPriceStock($item){
        $targetMonth = (int)$this->month;
        $targetYear = (int)$this->year;

        $allPurchases = collect();

        // Loop dari Januari 2024 sampai bulan dan tahun saat ini
        for ($year = 2024; $year <= $targetYear; $year++) {
            $startMonth = ($year === 2024) ? 1 : 1;
            $endMonth = ($year === $targetYear) ? $targetMonth : 12;

            for ($month = $startMonth; $month <= $endMonth; $month++) {
                $monthlyPurchases = $item->itemPurchases->filter(function ($purchase) use ($month, $year) {
                    $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                    return $purchaseDate->month === $month && $purchaseDate->year === $year;
                });

                $allPurchases = $allPurchases->merge($monthlyPurchases);
            }
        }

        // Mengambil harga-harga unik dari semua pembelian dalam periode tersebut
        $uniquePrices = $allPurchases->pluck('price')->unique();

        // Jika tidak ada harga ditemukan, kembalikan 0
        if ($uniquePrices->isEmpty()) {
            return '0';
        }

        // Format harga dan gabungkan dengan koma
        return $uniquePrices->map(function ($price) {
            return number_format($price ?? 0, 0, '.', ',');
        })->implode(', ');
    }
    public function getRemainingStock($item){

        $targetMonth = (int)$this->month;
        $targetYear = (int)$this->year;

        $lastStock = 0;

        // Loop dari Januari 2024 sampai bulan dan tahun saat ini
        for ($year = 2024; $year <= $targetYear; $year++) {
            $startMonth = ($year === 2024) ? 1 : 1;
            $endMonth = ($year === $targetYear) ? $targetMonth : 12;

            for ($month = $startMonth; $month <= $endMonth; $month++) {
                // Hitung pembelian bulan ini
                $monthPurchases = $item->itemPurchases->filter(function ($purchase) use ($month, $year) {
                    $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                    return $purchaseDate->month === $month && $purchaseDate->year === $year;
                })->sum('count') ?? 0;

                // Hitung penjualan bulan ini
                $monthSells = $item->itemSells->filter(function ($sell) use ($month, $year) {
                    $sellDate = \Carbon\Carbon::parse($sell->created_at);
                    return $sellDate->month === $month && $sellDate->year === $year;
                })->sum('count') ?? 0;

                // Tambahkan ke lastStock
                $lastStock += $monthPurchases - $monthSells;
            }
        }

        return  $lastStock;
    }

    public function getRemainingStockPriceTotal($item){
        $targetMonth = (int)$this->month;
        $targetYear = (int)$this->year;

        $total = 0;

        // Loop dari Januari 2024 sampai bulan dan tahun saat ini
        for ($year = 2024; $year <= $targetYear; $year++) {
            $startMonth = ($year === 2024) ? 1 : 1;
            $endMonth = ($year === $targetYear) ? $targetMonth : 12;

            for ($month = $startMonth; $month <= $endMonth; $month++) {
                // Hitung pembelian bulan ini
                $monthPurchases = $item->itemPurchases->filter(function ($purchase) use ($month, $year) {
                    $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                    return $purchaseDate->month === $month && $purchaseDate->year === $year;
                });

                $monthSells = $item->itemSells->filter(function ($sell) use ($month, $year) {
                    $sellDate = \Carbon\Carbon::parse($sell->created_at);
                    return $sellDate->month === $month && $sellDate->year === $year;
                });

                $totalPurchase = $monthPurchases->sum(function ($purchase) {
                    return ($purchase->count ?? 0) * ($purchase->price ?? 0);
                });

                $totalSell = $monthSells->sum(function ($sell) {
                    return ($sell->count ?? 0) * ($sell->price ?? 0);
                });

                // Tambahkan ke lastStock
                $total += $totalPurchase - $totalSell;
            }
        }


        $this->grandTotalLastMonth+=$total;
        return  $total;

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
        $targetMonth = (int)$this->month-1;
        $targetYear = (int)$this->year;

        $lastStock = 0;

        // Loop dari Januari 2024 sampai bulan dan tahun saat ini
        for ($year = 2024; $year <= $targetYear; $year++) {
            $startMonth = ($year === 2024) ? 1 : 1;
            $endMonth = ($year === $targetYear) ? $targetMonth : 12;

            for ($month = $startMonth; $month <= $endMonth; $month++) {
                // Hitung pembelian bulan ini
                $monthPurchases = $item->itemPurchases->filter(function ($purchase) use ($month, $year) {
                    $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                    return $purchaseDate->month === $month && $purchaseDate->year === $year;
                })->sum('count') ?? 0;

                // Hitung penjualan bulan ini
                $monthSells = $item->itemSells->filter(function ($sell) use ($month, $year) {
                    $sellDate = \Carbon\Carbon::parse($sell->created_at);
                    return $sellDate->month === $month && $sellDate->year === $year;
                })->sum('count') ?? 0;

                // Tambahkan ke lastStock
                $lastStock += $monthPurchases - $monthSells;
            }
        }

        return  $lastStock;
    }

    public function getPreviousMonthPriceStock($item){
        $targetMonth = (int)$this->month-1;
        $targetYear = (int)$this->year;

        $allPurchases = collect();

        // Loop dari Januari 2024 sampai bulan dan tahun saat ini
        for ($year = 2024; $year <= $targetYear; $year++) {
            $startMonth = ($year === 2024) ? 1 : 1;
            $endMonth = ($year === $targetYear) ? $targetMonth : 12;

            for ($month = $startMonth; $month <= $endMonth; $month++) {
                $monthlyPurchases = $item->itemPurchases->filter(function ($purchase) use ($month, $year) {
                    $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                    return $purchaseDate->month === $month && $purchaseDate->year === $year;
                });

                $allPurchases = $allPurchases->merge($monthlyPurchases);
            }
        }

        // Mengambil harga-harga unik dari semua pembelian dalam periode tersebut
        $uniquePrices = $allPurchases->pluck('price')->unique();

        // Jika tidak ada harga ditemukan, kembalikan 0
        if ($uniquePrices->isEmpty()) {
            return '0';
        }

        // Format harga dan gabungkan dengan koma
        return $uniquePrices->map(function ($price) {
            return number_format($price ?? 0, 0, '.', ',');
        })->implode(', ');
    }

    public function getPreviousMonthStockTotal($item){
        $targetMonth = (int)$this->month-1;
        $targetYear = (int)$this->year;

        $total = 0;

        // Loop dari Januari 2024 sampai bulan dan tahun saat ini
        for ($year = 2024; $year <= $targetYear; $year++) {
            $startMonth = ($year === 2024) ? 1 : 1;
            $endMonth = ($year === $targetYear) ? $targetMonth : 12;

            for ($month = $startMonth; $month <= $endMonth; $month++) {
                // Hitung pembelian bulan ini
                $monthPurchases = $item->itemPurchases->filter(function ($purchase) use ($month, $year) {
                    $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                    return $purchaseDate->month === $month && $purchaseDate->year === $year;
                });

                $monthSells = $item->itemSells->filter(function ($sell) use ($month, $year) {
                    $sellDate = \Carbon\Carbon::parse($sell->created_at);
                    return $sellDate->month === $month && $sellDate->year === $year;
                });

                $totalPurchase = $monthPurchases->sum(function ($purchase) {
                    return ($purchase->count ?? 0) * ($purchase->price ?? 0);
                });

                $totalSell = $monthSells->sum(function ($sell) {
                    return ($sell->count ?? 0) * ($sell->price ?? 0);
                });

                // Tambahkan ke lastStock
                $total += $totalPurchase - $totalSell;
            }
        }
         // Add to the grand total
         $this->grandTotalSupply += $total;

         return $total;
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

    public function getPriceBuyBefore($item){
        $originalMonth = (int)$this->month;
        $originalYear = (int)$this->year;

        if ($originalMonth == 1) {
            $previousMonth = 12;
            $previousYear = $originalYear - 1;
        } else {
            $previousMonth = $originalMonth - 1;
            $previousYear = $originalYear;
        }

        $monthPurchases = $item->itemPurchases->filter(function ($purchase) use ($previousMonth, $previousYear) {
            $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
            return $purchaseDate->month === $previousMonth && $purchaseDate->year === $previousYear;
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
        // Pembelian bulan ini
        $monthPurchasesStock = $item->itemPurchases->filter(function ($purchase) {
            $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
            return $purchaseDate->month === (int)$this->month && $purchaseDate->year === (int)$this->year;
        })->sum('count') ?? 0;

        // Hitung persediaan awal (sampai bulan sebelumnya)
        $targetMonth = (int)$this->month - 1;
        $targetYear = (int)$this->year;

        $lastStock = 0;

        for ($year = 2024; $year <= $targetYear; $year++) {
            $endMonth = ($year === $targetYear) ? $targetMonth : 12;

            for ($month = 1; $month <= $endMonth; $month++) {
                $monthPurchases = $item->itemPurchases->filter(function ($purchase) use ($month, $year) {
                    $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                    return $purchaseDate->month === $month && $purchaseDate->year === $year;
                })->sum('count') ?? 0;

                $monthSells = $item->itemSells->filter(function ($sell) use ($month, $year) {
                    $sellDate = \Carbon\Carbon::parse($sell->created_at);
                    return $sellDate->month === $month && $sellDate->year === $year;
                })->sum('count') ?? 0;

                $lastStock += $monthPurchases - $monthSells;
            }
        }

        // Total stok bulan ini = persediaan awal + pembelian bulan ini
        $totalStock = $lastStock + $monthPurchasesStock;

        return $totalStock;
    }

    public function getPriceItem($item)
    {
        // Pembelian bulan ini
        $monthPurchases = $item->itemPurchases->filter(function ($purchase) {
            $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
            return $purchaseDate->month === (int)$this->month && $purchaseDate->year === (int)$this->year;
        });

        // Keadaan awal (sebelum bulan ini)
        $targetMonth = (int)$this->month - 1;
        $targetYear = (int)$this->year;

        $allPurchases = collect();

        for ($year = 2024; $year <= $targetYear; $year++) {
            $endMonth = ($year === $targetYear) ? $targetMonth : 12;

            for ($month = 1; $month <= $endMonth; $month++) {
                $monthlyPurchases = $item->itemPurchases->filter(function ($purchase) use ($month, $year) {
                    $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                    return $purchaseDate->month === $month && $purchaseDate->year === $year;
                });

                $allPurchases = $allPurchases->merge($monthlyPurchases);
            }
        }

        // Gabungkan pembelian bulan ini dengan keadaan awal
        $combinedPurchases = $allPurchases->merge($monthPurchases);

        // Ambil harga-harga unik
        $uniquePrices = $combinedPurchases->pluck('price')->unique();

        // Jika tidak ada harga ditemukan, kembalikan 0
        if ($uniquePrices->isEmpty()) {
            return '0';
        }

        // Format harga dan gabungkan dengan koma
        return $uniquePrices->map(function ($price) {
            return number_format($price ?? 0, 0, '.', ',');
        })->implode(', ');
        // $originalMonth = (int)$this->month;
        // $originalYear = (int)$this->year;

        // // Hitung bulan dan tahun sebelumnya
        // if ($originalMonth == 1) {
        //     $previousMonth = 12;
        //     $previousYear = $originalYear - 1;
        // } else {
        //     $previousMonth = $originalMonth - 1;
        //     $previousYear = $originalYear;
        // }

        // // Filter pembelian bulan sebelumnya
        // $monthPurchasesBefore = $item->itemPurchases->filter(function ($purchase) use ($previousMonth, $previousYear) {
        //     $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
        //     return $purchaseDate->month === $previousMonth && $purchaseDate->year === $previousYear;
        // });

        // // Ambil harga unik pembelian bulan sebelumnya
        // $uniquePrices = $monthPurchasesBefore->pluck('price')->unique();

        // // Jika tidak ada harga bulan sebelumnya, set ke collection kosong
        // if ($uniquePrices->isEmpty()) {
        //     $uniquePrices = collect();
        // }

        // // Ambil pembelian bulan saat ini
        // $monthPurchases = $item->itemPurchases->filter(function ($purchase) use ($originalMonth, $originalYear) {
        //     $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
        //     return $purchaseDate->month === $originalMonth && $purchaseDate->year === $originalYear;
        // });

        // // Ambil harga unik pembelian bulan ini
        // $uniquePurchasePrices = $monthPurchases->pluck('price')->unique();

        // // Jika tidak ada harga pembelian bulan ini, set 0
        // if ($uniquePurchasePrices->isEmpty()) {
        //     $uniquePurchasePrices = collect([0]);
        // }

        // // Gabungkan harga bulan sebelumnya dan bulan ini, hapus duplikat
        // $allPrices = $uniquePrices->merge($uniquePurchasePrices)->unique();

        // // Jika semua harga kosong, kembalikan '0'
        // if ($allPrices->isEmpty()) {
        //     return '0';
        // }

        // // Format dan gabungkan harga jadi string dipisah koma
        // return $allPrices->map(function ($price) {
        //     return number_format($price ?? 0, 0, '.', ',');
        // })->implode(', ');
    }


    public function getPriceTotalItem($item){
        // Pembelian bulan ini
        $monthPurchasesStock = $item->itemPurchases->filter(function ($purchase) {
            $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
            return $purchaseDate->month === (int)$this->month && $purchaseDate->year === (int)$this->year;
        });

        $totalBuy = $monthPurchasesStock->sum(function ($purchase) {
            return ($purchase->count ?? 0) * ($purchase->price ?? 0);
        });

        // Hitung persediaan awal (sampai bulan sebelumnya)
        $targetMonth = (int)$this->month - 1;
        $targetYear = (int)$this->year;

        $total = 0;

        for ($year = 2024; $year <= $targetYear; $year++) {
            $endMonth = ($year === $targetYear) ? $targetMonth : 12;

            for ($month = 1; $month <= $endMonth; $month++) {
                $monthPurchases = $item->itemPurchases->filter(function ($purchase) use ($month, $year) {
                    $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                    return $purchaseDate->month === $month && $purchaseDate->year === $year;
                });

                $totalPurchase = $monthPurchases->sum(function ($purchase) {
                    return ($purchase->count ?? 0) * ($purchase->price ?? 0);
                });

                $monthSells = $item->itemSells->filter(function ($sell) use ($month, $year) {
                    $sellDate = \Carbon\Carbon::parse($sell->created_at);
                    return $sellDate->month === $month && $sellDate->year === $year;
                });

                $totalSell = $monthSells->sum(function ($purchase) {
                    return ($purchase->count ?? 0) * ($purchase->price ?? 0);
                });

                $total += $totalPurchase - $totalSell;
            }
        }

        // Total stok bulan ini = persediaan awal + pembelian bulan ini
        $totalStock = $total + $totalBuy;

        return $totalStock;
    }
}
