<?php

namespace App\Livewire\MonthlyReport;

use App\Models\Item;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class Datatable extends DataTableComponent
{
    protected $model = Item::class;
    public $grandTotalSupply = 0;

    public $grandTotalKeadaanBarang =0 ;
    public $grandTotalBuyRL =0;
    public $grandTotalSellRL =0;
    public $grandTotalHargaBarang = 0;
    public $grandTotalPurchase = 0;
    public $grandTotalSell = 0;

    public $grandTotalLastMonth = 0;
    public $grandTotalRL = 0;

    public $year;
    public $month;

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
    }
    
    
    protected $listeners = [
        're_render_table' => '$refresh'
    ];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSearchVisibilityEnabled();
        $this->setDefaultSort('id', 'desc');
    }

    public function query()
    {
        // Eager load itemPurchases relationship to access its data
        
        return Item::query()->with(['itemPurchases', 'itemSell']);
        

    }
    

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),
            Column::make('Nama Barang', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Jumlah Persediaan Awal')
                ->label(function($row){
            
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
                    $previousMonthStock = $row->itemPurchases
                        ->filter(function ($purchase) use ($previousMonth, $previousYear) {
                            $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                            return $purchaseDate->month === $previousMonth && $purchaseDate->year === $previousYear;
                        })
                        ->sum('count') ?? 0;
            
            
                    // Return the stock for the current month, considering the previous month's stock
                    return $previousMonthStock;
                }),
            

            Column::make('Harga Persediaan Awal', 'price')
                ->sortable()
                ->format(function ($value) {
                    return number_format($value ?? 0, 0, '.', ','); 
                }),
            
            Column::make('Total Persediaan Awal')
                ->label(function ($row){
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
                    $previousMonthStock = $row->itemPurchases
                        ->filter(function ($purchase) use ($previousMonth, $previousYear) {
                            $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                            return $purchaseDate->month === $previousMonth && $purchaseDate->year === $previousYear;
                        })
                        ->sum('count') ?? 0;

                    $price = $row->price ?? 0;
                    $result = $previousMonthStock * $price;

                    // Add to the grand total
                    $this->grandTotalSupply += $result;

                    return number_format($result, 0, '.', ',');

                })
                
                ->footer(function() {
                    return number_format($this->grandTotalSupply, 0, '.', ',');
                }),
            Column::make('Jumlah Pembelian')
                ->label(function ($row) {
                    $monthPurchases = $row->itemPurchases->filter(function ($purchase) {
                        $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                        return $purchaseDate->month === (int)$this->month && $purchaseDate->year === (int)$this->year;
                    });
                    return $monthPurchases->sum('count') ?? 0;
                    
                }),
            
            Column::make('Harga Pembelian')
                ->label(function ($row) {
                    // Memfilter pembelian yang terjadi di bulan November  $this->year
                    $monthPurchases = $row->itemPurchases->filter(function ($purchase) {
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
                }),
            
            
            
            Column::make('Total Harga Pembelian')
                ->label(function ($row) {
                    // Memfilter pembelian yang terjadi di bulan November  $this->year
                    $monthPurchases = $row->itemPurchases->filter(function ($purchase) {
                        $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                        return $purchaseDate->month === (int)$this->month && $purchaseDate->year === (int)$this->year;
                    });
                    $total = $monthPurchases->sum(function ($purchase) {
                        return ($purchase->count ?? 0) * ($purchase->price ?? 0);
                    });
                    
                    // Mengembalikan total harga dengan format yang sesuai

                    $this->grandTotalPurchase += $total;

                    return number_format($total, 0, '.', ','); // Format total
            })
            ->footer(function() {
                return number_format($this->grandTotalPurchase, 0, '.', ',');
            }),

            Column::make('Jumlah Keadaan Barang')
                ->label(function($row) {
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
                    $previousMonthStock = $row->itemPurchases
                        ->filter(function ($purchase) use ($previousMonth, $previousYear) {
                            $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                            return $purchaseDate->month === $previousMonth && $purchaseDate->year === $previousYear;
                        })
                        ->sum('count') ?? 0;

                    // Calculate Jumlah Persediaan Awal (Initial stock)
                    $initialStock = $previousMonthStock;

                    // Calculate Jumlah Pembelian
                    $monthPurchasesCurrent = $row->itemPurchases->filter(function ($purchase) {
                        $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                        return $purchaseDate->month === (int)$this->month && $purchaseDate->year === (int)$this->year;
                    });
                    $totalPurchaseCurrent = $monthPurchasesCurrent->sum('count') ?? 0;

                    // Calculate Jumlah Keadaan Barang (Initial stock + current month purchases)
                    return $initialStock + $totalPurchaseCurrent;
                }),
                Column::make('Harga Keadaan Barang')
                ->label(function ($row) {
                    // Get the "Harga Persediaan Awal" (initial stock price)
                    $initialStockPrice = $row->price ?? 0;
            
                    // Memfilter pembelian yang terjadi di bulan yang ditentukan
                    $monthPurchases = $row->itemPurchases->filter(function ($purchase) {
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
                }),
                Column::make('Total Keadaan Barang')
                ->label(function ($row) {
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
                    $previousMonthStock = $row->itemPurchases
                        ->filter(function ($purchase) use ($previousMonth, $previousYear) {
                            $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                            return $purchaseDate->month === $previousMonth && $purchaseDate->year === $previousYear;
                        })
                        ->sum('count') ?? 0;
            
                    $initialStock = $previousMonthStock;
            
                    // Calculate Jumlah Pembelian for the current month
                    $monthPurchasesCurrent = $row->itemPurchases->filter(function ($purchase) {
                        $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                        return $purchaseDate->month === (int)$this->month && $purchaseDate->year === (int)$this->year;
                    });
            
                    $totalPurchaseCurrent = $monthPurchasesCurrent->sum('count') ?? 0;
            
                    $jumlahKeadaanBarang = $initialStock + $totalPurchaseCurrent;
            
                    // Calculate Harga Keadaan Barang
                    $initialStockPrice = $row->price ?? 0;
            
                    $monthPurchases = $row->itemPurchases->filter(function ($purchase) {
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
            
                    $this -> grandTotalKeadaanBarang += $totalKeadaanBarang;
                    // Format the result
                    return number_format($totalKeadaanBarang, 0, '.', ',');
                })
                ->footer(function() {
                    return number_format($this->grandTotalKeadaanBarang, 0, '.', ',');
                }),
            

            
            Column::make('Jumlah Penjualan')
                ->label(function ($row) {
                    // Mengambil nilai count dari pembelian pertama di bulan November  $this->year atau set ke 0 jika tidak ada data
                    $monthSells = $row->itemSells->filter(function ($sell) {
                        $sellDate = \Carbon\Carbon::parse($sell->created_at);
                        return $sellDate->month === (int)$this->month && $sellDate->year === (int)$this->year;
                    });
                    return $monthSells->sum('count') ?? 0;
                }),
            
            Column::make('Harga Penjualan')
                ->label(function ($row) {
                    // Memfilter pembelian yang terjadi di bulan November  $this->year
                    $monthSells = $row->itemSells->filter(function ($sell) {
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
                }),
            
            
            
            Column::make('Total Harga Penjualan')
                ->label(function ($row) {
                    // Memfilter pembelian yang terjadi di bulan November  $this->year
                    $monthSells = $row->itemSells->filter(function ($sell) {
                        $sellDate = \Carbon\Carbon::parse($sell->created_at);
                        return $sellDate->month === (int)$this->month && $sellDate->year === (int)$this->year;
                    });
                    $total = $monthSells->sum(function ($sell) {
                        return ($sell->count ?? 0) * ($sell->price ?? 0);
                    });
                    
                    // Mengembalikan total harga dengan format yang sesuai
                    $this -> grandTotalSell += $total;
                    return number_format($total, 0, '.', ','); // Format total
            })
            ->footer(function() {
                return number_format($this->grandTotalSell, 0, '.', ',');
            }),
            Column::make('Jumlah R/L')
                ->label(function ($row) {
                    $monthSells = $row->itemSells->filter(function ($sell) {
                        $sellDate = \Carbon\Carbon::parse($sell->created_at);
                        return $sellDate->month === (int)$this->month && $sellDate->year === (int)$this->year;
                    });
                    return $monthSells->sum('count') ?? 0;
                }),
            Column::make('Beli R/L')
                ->label(function ($row) {
                    // Memfilter pembelian yang terjadi di bulan November  $this->year
                    $monthPurchases = $row->itemPurchases->filter(function ($purchase) {
                        $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                        return $purchaseDate->month === (int)$this->month && $purchaseDate->year === (int)$this->year;
                    });
                    $total = $monthPurchases->sum(function ($purchase) {
                        return ($purchase->count ?? 0) * ($purchase->price ?? 0);
                    });

                    $this -> grandTotalBuyRL += $total;
                    
                    return number_format($total, 0, '.', ','); // Format total
            })
            ->footer(function() {
                return number_format($this->grandTotalBuyRL, 0, '.', ',');
            }),

            Column::make('Jual R/L')
                ->label(function ($row) {
                    $monthSells = $row->itemSells->filter(function ($sell) {
                        $sellDate = \Carbon\Carbon::parse($sell->created_at);
                        return $sellDate->month === (int)$this->month && $sellDate->year === (int)$this->year;
                    });
                    $total = $monthSells->sum(function ($sell) {
                        return ($sell->count ?? 0) * ($sell->price ?? 0);
                    });
                    $this -> grandTotalSellRL += $total;
                    return number_format($total, 0, '.', ','); // Format total
            })
            ->footer(function() {
                return number_format($this->grandTotalSellRL, 0, '.', ',');
            }),
            Column::make('Total R/L')
                ->label(function ($row) {
                    $monthPurchases = $row->itemPurchases->filter(function ($purchase) {
                        $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                        return $purchaseDate->month === (int)$this->month && $purchaseDate->year === (int)$this->year;
                    });

                    $monthSells = $row->itemSells->filter(function ($sell) {
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
                    return number_format($total, 0, '.', ','); // Format total
            })
            ->footer(function() {
                return number_format($this->grandTotalRL, 0, '.', ',');
            }),
            Column::make('Sisa Barang Akhir Bualan')
                ->label(function($row){
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
                    $previousMonthStock = $row->itemPurchases
                        ->filter(function ($purchase) use ($previousMonth, $previousYear) {
                            $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                            return $purchaseDate->month === $previousMonth && $purchaseDate->year === $previousYear;
                        })
                        ->sum('count') ?? 0;

                    // Calculate Jumlah Persediaan Awal (Initial stock)
                    $initialStock = $previousMonthStock;

                    // Calculate Jumlah Pembelian
                    $monthPurchasesCurrent = $row->itemPurchases->filter(function ($purchase) {
                        $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                        return $purchaseDate->month === (int)$this->month && $purchaseDate->year === (int)$this->year;
                    });
                    $totalPurchaseCurrent = $monthPurchasesCurrent->sum('count') ?? 0;

                    $monthSells = $row->itemSells->filter(function ($sell) {
                        $sellDate = \Carbon\Carbon::parse($sell->created_at);
                        return $sellDate->month === (int)$this->month && $sellDate->year === (int)$this->year;
                    });

                    $totalSellCurrent = $monthSells->sum('count') ?? 0;

                    // Calculate Jumlah Keadaan Barang (Initial stock + current month purchases)
                    return $initialStock + $totalPurchaseCurrent - $totalSellCurrent;
                }),
            Column::make('Harga Barang', 'price')
                ->sortable()
                ->format(function ($value) {
                    return number_format($value ?? 0, 0, '.', ','); 
                }),
                Column::make('Total Harga Barang')
                ->label(function ($row) {
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
                    $previousMonthStock = $row->itemPurchases
                        ->filter(function ($purchase) use ($previousMonth, $previousYear) {
                            $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                            return $purchaseDate->month === $previousMonth && $purchaseDate->year === $previousYear;
                        })
                        ->sum('count') ?? 0;
            
                    // Calculate Initial Stock
                    $initialStock = $previousMonthStock;
            
                    // Calculate Purchases for the Current Month
                    $monthPurchasesCurrent = $row->itemPurchases->filter(function ($purchase) {
                        $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                        return $purchaseDate->month === (int)$this->month && $purchaseDate->year === (int)$this->year;
                    });
                    $totalPurchaseCurrent = $monthPurchasesCurrent->sum('count') ?? 0;
            
                    // Calculate Sales for the Current Month
                    $monthSells = $row->itemSells->filter(function ($sell) {
                        $sellDate = \Carbon\Carbon::parse($sell->created_at);
                        return $sellDate->month === (int)$this->month && $sellDate->year === (int)$this->year;
                    });
                    $totalSellCurrent = $monthSells->sum('count') ?? 0;
            
                    // Calculate Sisa Barang Akhir Bulan
                    $sisaBarangAkhir = $initialStock + $totalPurchaseCurrent - $totalSellCurrent;
            
                    // Get Harga Barang
                    $hargaBarang = $row->price ?? 0;
            
                    // Calculate Total Harga Barang
                    $totalHargaBarang = $sisaBarangAkhir * $hargaBarang;
                    
                    $this -> grandTotalHargaBarang += $totalHargaBarang;
                    // Format the result
                    return number_format($totalHargaBarang, 0, '.', ',');
                })
                ->footer(function() {
                    return number_format($this->grandTotalHargaBarang, 0, '.', ',');
                }),
            
        ];
    }
}
