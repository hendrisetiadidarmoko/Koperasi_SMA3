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
        <h2 class="text-center">Laporan Bulanan</h2>
        <div>
            <table class="table table-striped table-bordered">
                <thead>
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
                <tbody>
                    @forelse ($items as $item)
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
                            <td colspan="5" class="text-center">Tidak ada data tersedia</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="fw-bold">Grand Total</td>
                        <td>{{ number_format($grandTotalSupply ?? 0, 0, '.', ',') }}</td>
                        <td colspan="3" class="text-end">{{ number_format($grandTotalPurchase ?? 0, 0, '.', ',') }}</td>
                        <td colspan="3" class="text-end">{{ number_format($grandTotal ?? 0, 0, '.', ',') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div>
            <table class="table table-striped table-bordered">
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
                    @forelse ($items as $item)
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
                            <td colspan="5" class="text-center">Tidak ada data tersedia</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light border-dark">
                    <tr>
                        <td colspan="3" class="fw-bold">Grand Total</td>
                        <td>{{ number_format($grandTotalSell ?? 0, 0, '.', ',') }}</td>
                        <td colspan="2" class="text-end">{{ number_format($grandTotalBuyRL ?? 0, 0, '.', ',') }}</td>
                        <td  class="text-end">{{ number_format($grandTotalSellRL ?? 0, 0, '.', ',') }}</td>
                        <td  class="text-end">{{ number_format($grandTotalRL ?? 0, 0, '.', ',') }}</td>
                        <td colspan="3" class="text-end">{{ number_format($grandTotalLastMonth ?? 0, 0, '.', ',') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
</body>
</html>