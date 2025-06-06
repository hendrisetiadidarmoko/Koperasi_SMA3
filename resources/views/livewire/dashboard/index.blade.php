<div class="container my-5">
    <div class="header">
        <h1 class=" fs-2">DASHBOARD</h1>


        <hr>
    </div>

    <div class="my-3">
        <div class="row" data-aos="fade-left">
            <div class="col-12 col-xl-3 col-md-6 col-sm-12 my-2">
                <div class="card content-dashboard">
                    <div class="card-body text-center">
                        <h4 class="card-text fs-1">{{ $itemCount }}</h4>
                        <h5 class="card-title">Barang</h5>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-3 col-md-6 col-sm-12 my-2">
                <div class="card content-dashboard">
                    <div class="card-body text-center">
                        <h4 class="card-text fs-1">{{ $itemBuyCount }}</h4>
                        <h5 class="card-title">Transaksi Beli Barang</h5>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-3 col-md-6 col-sm-12 my-2">
                <div class="card content-dashboard">
                    <div class="card-body text-center">
                        <h4 class="card-text fs-1">{{ $itemSellCount }}</h4>
                        <h5 class="card-title">Transaksi Jual Barang</h5>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-3 col-md-6 col-sm-12 my-2 ">
                <div class="card content-dashboard">
                    <div class="card-body text-center">
                        <h4 class="card-text fs-1">{{ $userCount }}</h4>
                        <h5 class="card-title">User</h5>
                    </div>
                </div>
            </div>
        </div>
        <form wire:submit.prevent="getChart">
            <div class="row justify-content-end text-end mt-2">
                <div class="col-xl-3 my-1">
                    <select wire:model="selectedMonth" id="monthSelect" class="form-select form-control">
                        @foreach(range(1,12) as $month)
                            <option value="{{ $month }}">{{ \Carbon\Carbon::create()->month($month)->format('F') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-3 my-1">
                    <select wire:model="selectedYear" id="yearSelect" class="form-select form-control">
                        @foreach(range(now()->year - 5, now()->year) as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-3 my-1">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>
        
        <div class="row my-0" data-aos="fade-left" id="chart-filter">
            <div class="col-12 col-xl-8 col-md-12 my-2">
                <div class="card mt-2 h-100">
                    <div class="card-header content-dashboard-chart">
                        
                        <h3 class="mb-0" >Top 10 Barang Terlaris<span id="monthThis"></span> </h3>
                    </div>
                    <div class="card-body">
                        <canvas id="topSellingChart" ></canvas>
                        
                    </div>
                </div>
            </div>


            <div class="col-12 col-xl-4 col-md-12 my-2 mx-auto">
                <div class="card mt-2 w-100 h-100 ">
                    <div class="card-header content-dashboard-chart">
                        <h3 class="mb-0">Penjualan dan Pembelian</h3>
                    </div>
                    <div class="card-body d-flex justify-content-center align-items-center">
                        <canvas id="itemComparisonChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        
        
        <div class="card mt-3 h-100" data-aos="fade-left">
            <div class="card-header content-dashboard-chart">
                <h3 class="mb-0">Stok Barang</h3>
            </div>
            <div class="card-body">
                <canvas id="stockChart" class="w-100 h-100"></canvas>
            </div>
        </div>
        <div class="card mt-3 h-100" data-aos="fade-left">
            <div class="card-header content-dashboard-chart">
                <h3 class="mb-0">Terjual Per Bulan</h3>
            </div>
            <div class="card-body">
                <canvas id="profitChart"></canvas>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.addEventListener('load', () => {
            setTimeout(() => {
                if (chartTopSelling) chartTopSelling.resize();
                if (chartBuySell) chartBuySell.resize();
                if (stockChart) stockChart.resize();
                if (profitChart) profitChart.resize();
            }, 500);
        });

        document.addEventListener('DOMContentLoaded', function () {
            const topSellingLabels = @json($topSellingItems['labels'] ?? []);
            const topSellingData = @json($topSellingItems['data'] ?? []);



            const monthNames = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            Livewire.on('chartUpdated', (labels, data) => {
                    if (chartTopSelling) {
                        chartTopSelling.data.labels = labels[0];
                        chartTopSelling.data.datasets[0].data = labels[1];
                        chartTopSelling.update();
                    }

                    if (chartBuySell) {
                        chartBuySell.data.datasets[0].data[0] = labels[3];
                        chartBuySell.data.datasets[0].data[1] = labels[5];
                        chartBuySell.update();
                    }

                    setTimeout(() => {
                        if (chartTopSelling) chartTopSelling.resize();
                        if (chartBuySell) chartBuySell.resize();
                        if (stockChart) stockChart.resize();
                        if (profitChart) profitChart.resize();
                    }, 300);
                    window.dispatchEvent(new Event('resize'));

                });
            // Inisialisasi chart dan simpan dalam variabel global
            const topSellingChartCanvas = document.getElementById('topSellingChart');
            let chartTopSelling = null;

            if (topSellingChartCanvas) {
                const ctxTopSelling = topSellingChartCanvas.getContext('2d');
                chartTopSelling = new Chart(ctxTopSelling, {
                    type: 'bar',
                    data: {
                        labels: topSellingLabels,
                        datasets: [{
                            label: 'Jumlah Terjual',
                            data: topSellingData,
                            borderColor: '#FFA500',
                            backgroundColor: 'rgba(255, 165, 0, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        indexAxis: 'y', // horizontal bar
                        responsive: true,
                        scales: {
                            x: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Jumlah Terjual'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Nama Barang'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }

            // Update chart data saat Livewire emit event 'chartUpdated'



            const backgroundColors = [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
            ];

            // Chart untuk keuntungan bulanan
            const monthlyProfitLabels = @json(array_keys($monthlyProfits ?? []));
            const monthlyProfitData = @json(array_values($monthlyProfits ?? []));
            const profitChartCanvas = document.getElementById('profitChart');
            let profitChart = null;
            if (profitChartCanvas) {
                const ctxProfit = profitChartCanvas.getContext('2d');
                profitChart = new Chart(ctxProfit, {
                    type: 'line',
                    data: {
                        labels: monthlyProfitLabels,
                        datasets: [{
                            label: 'Terjual',
                            data: monthlyProfitData,
                            borderColor: '#FFA500',
                            backgroundColor: 'rgba(255, 165, 0, 0.2)',
                            tension: 0.3,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                            }
                        }
                    }
                });
            }

            const totalSold = @json(array_sum($itemSalesData['data'] ?? [])); // Total semua item terjual
            const totalBought = @json(array_sum($itemPurchaseData['data'] ?? [])); // Total semua item terbeli

            const itemComparisonChartCanvas = document.getElementById('itemComparisonChart');
            let chartBuySell = null;
            if (itemComparisonChartCanvas) {
                const ctxItemComparison = itemComparisonChartCanvas.getContext('2d');
                chartBuySell = new Chart(ctxItemComparison, {
                    type: 'pie',
                    data: {
                        labels: ['Item Terjual', 'Item Terbeli'],
                        datasets: [{
                            data: [totalSold, totalBought],
                            backgroundColor: ['#36A2EB', '#FF6384'],
                            hoverBackgroundColor: ['#36A2EBAA', '#FF6384AA']
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        }
                    }
                });
            }
            const ctx = document.getElementById('stockChart').getContext('2d');
            const stockChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($stockData['labels']),
                    datasets: [{
                        label: 'Jumlah Stok',
                        data: @json($stockData['data']),
                        borderColor: '#FFA500',
                        backgroundColor: 'rgba(255, 165, 0, 0.2)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Stok'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Nama Barang'
                            }
                        }
                    }
                }
            });
        });
    </script>
</div>