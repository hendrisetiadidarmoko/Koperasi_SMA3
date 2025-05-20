<div class="container" data-aos="fade-left">
    <div class="w-100 border-content rounded my-5 bg-light">
        <div class="page-heading my-5">
            <div class="page-title">
                <div class="row">
                    <div class="order-md-1 order-last">
                        <h3 class="text-center">Transaksi</h3>
                    </div>
                </div>
            </div>
        </div>
        <section class="section mt-4 p-3">
            <a href="{{ route('admin.purchase.items') }}"
               class="fs-5 d-block rounded mb-2 text-light p-2 link-content"
               >
                Pembelian Barang
            </a>
            <a href="{{ route('admin.sell.items') }}"
               class="fs-5 d-block rounded mb-2 text-light p-2 link-content"
               >
                Penjualan Barang
            </a>
            <a href="{{ route('admin.transaction.years') }}"
               class="fs-5 d-block rounded text-light p-2 link-content"
               >
                Laporan
            </a>
        </section>
        
    </div>

</div>
