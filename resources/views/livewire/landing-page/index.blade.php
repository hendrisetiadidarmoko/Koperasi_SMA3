<div>
    <section class="hero container " >
        <div class="row align-items-center">
            <div class="col-md-6 order-2 order-md-1" data-aos="fade-right">
                <div class="hero-text text-center text-md-start">
                    <h2 class="fw-bold">Koperasi <br> 
                        <span style="color: #FFA500;" class="fs-1">SMA N 3 Purwokerto</span>
                    </h2>
                    <p class="fw-semibold fs-4">Belanja Cerdas, Harga Bersahabat, Manfaat untuk Semua!</p>
                    <a href="{{ route(name: 'listItem') }}" class="btn btn-custom">Lihat barang</a>
                </div>
            </div>
            <div class="col-md-6 text-center order-1 order-md-2" data-aos="fade-left">
                <img src="{{ asset('assets/images/hero-image3.png') }}" alt="Koperasi Image" class="img-fluid">
            </div>
        </div>
    </section>
    <section class="container my-5" data-aos="zoom-in-down">
        <h2 class="text-center fw-bold text-custom mb-4 ">Tentang</h2>
        <div class="row align-items-center">
            <div class="col-md-4 text-center">
                <img src="{{ asset('assets/images/bro.png') }}" class="img-fluid w-75" alt="Toko Sekolah">
            </div>
            <div class="col-md-8 my-3">
                <p class=" fs-6 text-justify">
                    Koperasi SMA N 3 Purwokerto adalah koperasi sekolah yang berperan dalam mendukung kebutuhan siswa dan tenaga pendidik. 
                    Koperasi ini menyediakan berbagai keperluan seperti alat tulis, buku pelajaran, seragam, serta makanan dan minuman dengan harga yang terjangkau.
                </p>
            </div>
        </div>
    </section>
    <section class="container-md" data-aos="zoom-in-down">
        <h2 class="text-center fw-bold text-custom mb-4">Keunggulan</h2>
        <div class="row gap-3 justify-content-center">
            <!-- Harga Terjangkau -->
            <div class="card col-xl-3 col-md-5 col-sm-12 border border-3 rounded text-center p-3">
                <img src="{{asset('assets/images/entypo_price-tag.png')}}" class="card-img-top w-50 mx-auto" alt="Harga Terjangkau">
                <div class="card-body">
                    <h5 class="card-title text-custom fw-bold">Harga Terjangkau</h5>
                    <p class="card-text">Produk memiliki harga terjangkau bagi siswa</p>
                </div>
            </div>
    
            <!-- Mudah & Praktis -->
            <div class="card col-xl-3 col-md-5 col-sm-12 border border-3 rounded text-center p-3">
                <img src="{{asset('assets/images/map_shopping-mall.png')}}" class="card-img-top w-50 mx-auto" alt="Mudah & Praktis">
                <div class="card-body">
                    <h5 class="card-title text-custom fw-bold">Mudah & Praktis</h5>
                    <p class="card-text">Siswa dapat berbelanja langsung di sekolah tanpa perlu pergi jauh</p>
                </div>
            </div>
    
            <!-- Mendukung Pendidikan -->
            <div class="card col-xl-3 col-md-5 col-sm-12 border border-3 rounded text-center p-3">
                <img src="{{asset('assets/images/emojione-monotone_school.png')}}" class="card-img-top w-50 mx-auto" alt="Mendukung Pendidikan">
                <div class="card-body">
                    <h5 class="card-title text-custom fw-bold">Mendukung Pendidikan</h5>
                    <p class="card-text">Keuntungan koperasi digunakan untuk pengembangan sekolah dan kegiatan siswa.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="container my-5 px-3" data-aos="zoom-in-down">
        <h3 class="text-center mb-4 text-custom">Lokasi dan Kontak </h3>
        <div class="row">
            <div class="col-md-8 col-sm-12 map my-3">
                <div class="ratio ratio-16x9">
            
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.4993826027776!2d109.20894127476204!3d-7.4098486926003675!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e655f8c8d1b53d7%3A0xd4c91761bdfe2720!2sSMA%20Negeri%203%20Purwokerto!5e0!3m2!1sid!2sid!4v1745170349946!5m2!1sid!2sid" 
                        width="600" 
                        height="450" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
            <div class="col-md-4 col-sm-12 my-3">
                <div class="contact p-3 rounded" >
                    <p>
                        <i class="fas fa-phone-alt me-2"></i>
                        <a href="tel:+6281548795541" class="text-decoration-none text-dark">+62 81548795541</a>
                    </p>
                    <p>
                        <i class="fab fa-instagram me-2"></i>
                        <a href="https://instagram.com/smagaofficial_pwt" target="_blank" class="text-decoration-none text-dark">
                            @smagaofficial_pwt
                        </a>
                    </p>
                </div>
            </div>
            
            
            

        </div>
        
    </section>
    
    
    
    
    
</div>
