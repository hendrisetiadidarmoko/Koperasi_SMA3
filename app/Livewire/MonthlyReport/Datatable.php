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
            
                    $targetMonth = (int)$this->month-1;
                    $targetYear = (int)$this->year;

                    $lastStock = 0;

                    // Loop dari Januari 2024 sampai bulan dan tahun saat ini
                    for ($year = 2024; $year <= $targetYear; $year++) {
                        $startMonth = ($year === 2024) ? 1 : 1;
                        $endMonth = ($year === $targetYear) ? $targetMonth : 12;

                        for ($month = $startMonth; $month <= $endMonth; $month++) {
                            // Hitung pembelian bulan ini
                            $monthPurchases = $row->itemPurchases->filter(function ($purchase) use ($month, $year) {
                                $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                                return $purchaseDate->month === $month && $purchaseDate->year === $year;
                            })->sum('count') ?? 0;

                            // Hitung penjualan bulan ini
                            $monthSells = $row->itemSells->filter(function ($sell) use ($month, $year) {
                                $sellDate = \Carbon\Carbon::parse($sell->created_at);
                                return $sellDate->month === $month && $sellDate->year === $year;
                            })->sum('count') ?? 0;

                            // Tambahkan ke lastStock
                            $lastStock += $monthPurchases - $monthSells;
                        }
                    }
            
            
                    // Return the stock for the current month, considering the previous month's stock
                    return $lastStock;
                }),
            

            Column::make('Harga Persediaan Awal', 'price')
                ->sortable()
                ->label(function($row){
                    $targetMonth = (int)$this->month-1;
                    $targetYear = (int)$this->year;

                    $allPurchases = collect();

                    // Loop dari Januari 2024 sampai bulan dan tahun saat ini
                    for ($year = 2024; $year <= $targetYear; $year++) {
                        $startMonth = ($year === 2024) ? 1 : 1;
                        $endMonth = ($year === $targetYear) ? $targetMonth : 12;

                        for ($month = $startMonth; $month <= $endMonth; $month++) {
                            $monthlyPurchases = $row->itemPurchases->filter(function ($purchase) use ($month, $year) {
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
                })
                ->format(function ($value) {
                    return number_format($value ?? 0, 0, '.', ','); 
                }),
            
            Column::make('Total Persediaan Awal')
                ->label(function ($row){
                    $targetMonth = (int)$this->month-1;
                    $targetYear = (int)$this->year;

                    $total = 0;

                    // Loop dari Januari 2024 sampai bulan dan tahun saat ini
                    for ($year = 2024; $year <= $targetYear; $year++) {
                        $startMonth = ($year === 2024) ? 1 : 1;
                        $endMonth = ($year === $targetYear) ? $targetMonth : 12;

                        for ($month = $startMonth; $month <= $endMonth; $month++) {
                            // Hitung pembelian bulan ini
                            $monthPurchases = $row->itemPurchases->filter(function ($purchase) use ($month, $year) {
                                $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                                return $purchaseDate->month === $month && $purchaseDate->year === $year;
                            });

                            $monthSells = $row->itemSells->filter(function ($sell) use ($month, $year) {
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

                    return  number_format($total ?? 0, 0, '.', ',');

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
                    // Pembelian bulan ini
                    $monthPurchasesStock = $row->itemPurchases->filter(function ($purchase) {
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
                            $monthPurchases = $row->itemPurchases->filter(function ($purchase) use ($month, $year) {
                                $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                                return $purchaseDate->month === $month && $purchaseDate->year === $year;
                            })->sum('count') ?? 0;

                            $monthSells = $row->itemSells->filter(function ($sell) use ($month, $year) {
                                $sellDate = \Carbon\Carbon::parse($sell->created_at);
                                return $sellDate->month === $month && $sellDate->year === $year;
                            })->sum('count') ?? 0;

                            $lastStock += $monthPurchases - $monthSells;
                        }
                    }

                    // Total stok bulan ini = persediaan awal + pembelian bulan ini
                    $totalStock = $lastStock + $monthPurchasesStock;

                    return $totalStock;
                }),
                Column::make('Harga Keadaan Barang')
                ->label(function ($row) {
                    // Pembelian bulan ini
                    $monthPurchases = $row->itemPurchases->filter(function ($purchase) {
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
                            $monthlyPurchases = $row->itemPurchases->filter(function ($purchase) use ($month, $year) {
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
                }),
                Column::make('Total Keadaan Barang')
                ->label(function ($row) {
                    // Pembelian bulan ini
                    $monthPurchasesStock = $row->itemPurchases->filter(function ($purchase) {
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
                            $monthPurchases = $row->itemPurchases->filter(function ($purchase) use ($month, $year) {
                                $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                                return $purchaseDate->month === $month && $purchaseDate->year === $year;
                            });

                            $totalPurchase = $monthPurchases->sum(function ($purchase) {
                                return ($purchase->count ?? 0) * ($purchase->price ?? 0);
                            });

                            $monthSells = $row->itemSells->filter(function ($sell) use ($month, $year) {
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
                    $this->grandTotalKeadaanBarang += $totalStock;

                    return  number_format($totalStock ?? 0, 0, '.', ',');
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
            Column::make('Sisa Barang Akhir Bulan')
                ->label(function($row){
                    $targetMonth = (int)$this->month;
                    $targetYear = (int)$this->year;

                    $lastStock = 0;

                    // Loop dari Januari 2024 sampai bulan dan tahun saat ini
                    for ($year = 2024; $year <= $targetYear; $year++) {
                        $startMonth = ($year === 2024) ? 1 : 1;
                        $endMonth = ($year === $targetYear) ? $targetMonth : 12;

                        for ($month = $startMonth; $month <= $endMonth; $month++) {
                            // Hitung pembelian bulan ini
                            $monthPurchases = $row->itemPurchases->filter(function ($purchase) use ($month, $year) {
                                $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                                return $purchaseDate->month === $month && $purchaseDate->year === $year;
                            })->sum('count') ?? 0;

                            // Hitung penjualan bulan ini
                            $monthSells = $row->itemSells->filter(function ($sell) use ($month, $year) {
                                $sellDate = \Carbon\Carbon::parse($sell->created_at);
                                return $sellDate->month === $month && $sellDate->year === $year;
                            })->sum('count') ?? 0;

                            // Tambahkan ke lastStock
                            $lastStock += $monthPurchases - $monthSells;
                        }
                    }

                    return number_format($lastStock, 0, '.', ',');
                }),
            Column::make('Harga Barang', 'price')
                ->sortable()
                ->label(function($row){
                    $targetMonth = (int)$this->month;
                    $targetYear = (int)$this->year;

                    $allPurchases = collect();

                    // Loop dari Januari 2024 sampai bulan dan tahun saat ini
                    for ($year = 2024; $year <= $targetYear; $year++) {
                        $startMonth = ($year === 2024) ? 1 : 1;
                        $endMonth = ($year === $targetYear) ? $targetMonth : 12;

                        for ($month = $startMonth; $month <= $endMonth; $month++) {
                            $monthlyPurchases = $row->itemPurchases->filter(function ($purchase) use ($month, $year) {
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
                }),
                Column::make('Total Harga Barang')
                ->label(function ($row) {
                    $targetMonth = (int)$this->month;
                    $targetYear = (int)$this->year;

                    $total = 0;

                    // Loop dari Januari 2024 sampai bulan dan tahun saat ini
                    for ($year = 2024; $year <= $targetYear; $year++) {
                        $startMonth = ($year === 2024) ? 1 : 1;
                        $endMonth = ($year === $targetYear) ? $targetMonth : 12;

                        for ($month = $startMonth; $month <= $endMonth; $month++) {
                            // Hitung pembelian bulan ini
                            $monthPurchases = $row->itemPurchases->filter(function ($purchase) use ($month, $year) {
                                $purchaseDate = \Carbon\Carbon::parse($purchase->created_at);
                                return $purchaseDate->month === $month && $purchaseDate->year === $year;
                            });

                            $monthSells = $row->itemSells->filter(function ($sell) use ($month, $year) {
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
                    return  number_format($total ?? 0, 0, '.', ',');
                })
                ->footer(function() {
                    return number_format($this->grandTotalLastMonth, 0, '.', ',');
                }),
            
        ];
    }
}
