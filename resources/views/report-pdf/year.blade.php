<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Laporan Bulanan</title>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Laporan Tahunan</h2>
        @foreach (range(1, 12) as $month)
            <div class="mb-5">
                <h3 class="text-center">Bulan {{ DateTime::createFromFormat('!m', $month)->format('F') }}</h3>
                <div >
                    <table class="table table-striped table-bordered border-dark rounded-2">
                        <thead class="table-light border-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nama Barang</th>
                                <th>Jumlah Persediaan Awal</th>
                                <th>Harga Persediaan Awal</th>
                                <th>Total Persediaan Awal</th>
                                <th>Jumlah Pembelian</th>
                                <th>Harga Pembelian</th>
                                <th>Total Harga Pembelian</th>
                                <th>Jumlah Keadaan Barang</th>
                                <th>Harga Keadaan Barang</th>
                                <th>Total Harga Keadaan Barang</th>
                            </tr>
                        </thead>
                        <tbody class="table-light border-dark">
                            @php
                                $totalPreviousStock = 0;
                                $totalBuyItem = 0;
                                $totalItem = 0;
                                $totalSellItem = 0;
                                $totalSellRL = 0;
                                $totalBuyRL = 0;
                                $totalRL = 0;
                                $totalRemainingStock = 0;
                            @endphp
                            @forelse ($items[$month] as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->previous_stock }}</td> <!-- Menampilkan stok bulan sebelumnya -->
                                    <td>{{ number_format($item->price ?? 0, 0, '.', ',') }}</td>
                                    <td>{{ number_format($item->previous_stock_total ?? 0, 0, '.', ',') }}</td> 
                                    <td>{{$item->total_buy}}</td>
                                    <td>{{ $item->price_buy ?? 0, 0, '.', ',' }}</td> 
                                    <td>{{ number_format($item->price_total ?? 0, 0, '.', ',') }}</td> 
                                    <td>{{$item->total_item}}</td>
                                    <td>{{ $item->price_item ?? 0, 0, '.', ','}}</td>
                                    <td>{{ number_format($item->price_total_item ?? 0, 0, '.', ',') }}</td> 
                                    
                                    
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="21" class="text-center">Tidak ada data untuk bulan ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($items[$month]->isNotEmpty())
                            <tfoot class="table-light border-dark">
                                <tr>
                                    <td colspan="4" class="fw-bold">Grand Total</td>
                                    @foreach ($items[$month] as $item)
                                        @php
                                            $totalPreviousStock += $item->previous_stock_total ?? 0;
    
                                            $totalBuyItem += $item->price_total ?? 0;
    
                                            $totalItem += $item->price_total_item ?? 0;
                                            
                                            $totalSellItem += $item->price_total_sell ?? 0;
                                            $totalBuyRL += $item->price_buy_Rl ?? 0;
                                            $totalSellRL += $item->price_sell_RL ?? 0;
                                            $totalRL += $item->price_total_RL ?? 0;
                                            $totalRemainingStock += $item->price_total_remaining_stock ?? 0;
                                            
                                        @endphp
                                    @endforeach
                                    <td><strong>{{ number_format($totalPreviousStock, 0, '.', ',') }}</strong></td>
                                    
                                    <td colspan="3" class="text-end">{{ number_format($totalBuyItem ?? 0, 0, '.', ',') }}</td>
                                    <td colspan="3" class="text-end">{{ number_format($totalItem ?? 0, 0, '.', ',') }}</td>
                                    <td colspan="3" class="text-end">{{ number_format($totalSellItem ?? 0, 0, '.', ',') }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
                <div >
                    <table class="table table-striped table-bordered border-dark rounded-2">
                        <thead class="table-light border-dark">
                            <tr>
                                <th>ID</th>
                                <th>Jumlah Penjualan</th>
                                <th>Harga Penjualan</th>
                                <th>Total Harga Penjualan</th>
                                <th>Jumlah R/L</th>
                                <th>Beli R/L</th>
                                <th>Jual R/L</th>
                                <th>Total R/L</th>
                                <th>Sisa Barang Akhir Bulan</th>
                                <th>Harga Barang</th>
                                <th>Total Harga Barang Sisa</th>
                            </tr>
                        </thead>
                        <tbody class="table-light border-dark">
                            @php
                                $totalPreviousStock = 0;
                                $totalBuyItem = 0;
                                $totalItem = 0;
                                $totalSellItem = 0;
                                $totalSellRL = 0;
                                $totalBuyRL = 0;
                                $totalRL = 0;
                                $totalRemainingStock = 0;
                            @endphp
                            @forelse ($items[$month] as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{$item->total_sell}}</td>
                                    <td>{{ $item->price_sell ?? 0, 0, '.', ','}}</td>
                                    <td>{{ number_format($item->price_total_sell ?? 0, 0, '.', ',') }}</td>
                                    <td>{{$item->total_RL}}</td>
                                    <td>{{ number_format($item->price_buy_Rl ?? 0, 0, '.', ',') }}</td>
                                    <td>{{ number_format($item->price_sell_RL ?? 0, 0, '.', ',') }}</td>
                                    <td>{{ number_format($item->price_total_RL ?? 0, 0, '.', ',') }}</td>
                                    <td>{{$item->total_remaining_stock}}</td>
                                    <td>{{ number_format($item->price ?? 0, 0, '.', ',') }}</td>
                                    <td>{{ number_format($item->price_total_remaining_stock ?? 0, 0, '.', ',') }}</td>
                                    
                                    
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="21" class="text-center">Tidak ada data untuk bulan ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($items[$month]->isNotEmpty())
                            <tfoot class="table-light border-dark">
                                <tr>
                                    <td colspan="4" class="fw-bold">Grand Total</td>
                                    @foreach ($items[$month] as $item)
                                        @php
                                            $totalPreviousStock += $item->previous_stock_total ?? 0;
    
                                            $totalBuyItem += $item->price_total ?? 0;
    
                                            $totalItem += $item->price_total_item ?? 0;
                                            
                                            $totalSellItem += $item->price_total_sell ?? 0;
                                            $totalBuyRL += $item->price_buy_Rl ?? 0;
                                            $totalSellRL += $item->price_sell_RL ?? 0;
                                            $totalRL += $item->price_total_RL ?? 0;
                                            $totalRemainingStock += $item->price_total_remaining_stock ?? 0;
                                            
                                        @endphp
                                    @endforeach
                                    <td colspan="3" class="text-end">{{ number_format($totalSellItem ?? 0, 0, '.', ',') }}</td>
                                    <td colspan="2" class="text-end">{{ number_format($totalBuyRL ?? 0, 0, '.', ',') }}</td>
                                    <td  class="text-end">{{ number_format($totalSellRL ?? 0, 0, '.', ',') }}</td>
                                    <td  class="text-end">{{ number_format($totalRL ?? 0, 0, '.', ',') }}</td>
                                    <td colspan="3" class="text-end">{{ number_format($totalRemainingStock ?? 0, 0, '.', ',') }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        @endforeach
    </div>
    
    
</body>
</html>