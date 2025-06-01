<div class="container-fluid" data-aos="fade-left">
    <div class="w-100 border-content rounded my-5 bg-light">
        <div class="page-heading mt-5">
            <div class="page-title">
                <div class="row">
                    <div class="order-md-1 order-last">
                        <h3 class="text-center">Print</h3>
                    </div>
                </div>
            </div>
        </div>
        <section class="section mt-4 p-3">
            <div id="yearAlert" class="alert alert-danger d-none" role="alert"></div>

            <div class="card-body">
                <div class="row justify-content-between align-content-center p-5">
                    <div class="col-md-5">
                        <label for="years">Tahun</label>
                        <input type="number" placeholder="YYYY" class="form-control rounded-4 w-100 mb-3 mt-2" name="years" id="years" min="2020" required>
                        <div class="input-group mb-3 w-100 ">
                            <a href="#"
                                onclick="
                                    const tahun = document.getElementById('years').value;
                                    const alertBox = document.getElementById('yearAlert');

                                    if (!tahun) {
                                        alertBox.textContent = 'Tolong isi tahun terlebih dahulu.';
                                        alertBox.classList.remove('d-none');
                                        return false;
                                    } else if (parseInt(tahun) < 2025) {
                                        alertBox.textContent = 'Tahun minimal adalah 2025.';
                                        alertBox.classList.remove('d-none');
                                        return false;
                                    } else {
                                        alertBox.classList.add('d-none');
                                        this.href = '/report-pdf/' + tahun;
                                    }
                                "
                                target="_blank"
                                class="btn btn-custom col-md-12 w-100">
                                Cetak <i class="fa fa-print"></i>
                                </a>

                        </div>
                    </div>
                    <div class="col-md-5">
                        <label for="year">Tahun</label>
                        <input type="number" placeholder="YYYY" class="form-control rounded-4 w-100 mb-3 mt-2" name="year" id="year" min="2020" required>

                        <label for="month">Bulan</label>
                        <select class="form-control rounded-4 w-100 mb-3 mt-2" name="month" id="month" required>
                            <option value="">-- Pilih Bulan --</option>
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>

                        <div class="input-group mb-3 w-100 ">
                            <a href="#"
                                onclick="
                                    const tahun = document.getElementById('year').value;
                                    const bulan = document.getElementById('month').value;
                                    const alertBox = document.getElementById('yearAlert');

                                    if (!tahun || !bulan) {
                                        alertBox.textContent = 'Tolong isi tahun dan pilih bulan terlebih dahulu.';
                                        alertBox.classList.remove('d-none');
                                        return false;
                                    } else if (parseInt(tahun) < 2025) {
                                        alertBox.textContent = 'Tahun minimal adalah 2025.';
                                        alertBox.classList.remove('d-none');
                                        return false;
                                    } else {
                                        alertBox.classList.add('d-none');
                                        this.href = '/report-pdf/' + tahun + '/' + bulan;
                                    }
                                "
                                target="_blank"
                                class="btn btn-custom col-md-12 w-100">
                                Cetak <i class="fa fa-print"></i>
                            </a>
                        </div>
                </div>
                
            </div>
        </section>
        
    </div>
    <script>
        document.querySelector("input[type=number]")
        .oninput = e => console.log(new Date(e.target.valueAsNumber, 0, 1))
        </script>
</div>
