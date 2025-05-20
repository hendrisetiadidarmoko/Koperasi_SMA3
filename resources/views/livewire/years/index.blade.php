<div class="container">
    <div class="w-100 border-content rounded my-5 bg-light">
        <div class="page-heading mt-5">
            <div class="page-title">
                <div class="row">
                    <div class="order-md-1 order-last">
                        <h3 class="text-center">Transaksi Tahunan</h3>
                    </div>
                </div>
            </div>
        </div>
        <section class="section mt-4 p-3">
            @foreach ($years as $year)
                <a href="{{ route('admin.transaction.months', ['year' => $year]) }}"
                    class="fs-5 d-block rounded text-light p-2 link-content">
                    Tahun <span>{{ $year }}
                </a>
            @endforeach
        </section>
    </div>
</div>
