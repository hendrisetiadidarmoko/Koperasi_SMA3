<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- font --}}
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    {{-- bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    @livewireStyles

    <link rel="stylesheet" href="style.css">


    <link rel="stylesheet" href="{{url('assets/css/main.css')}}">
    <title>{{ $title ?? 'Page Title' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        [x-cloak] {
            display: none !important;
        }
        
    </style>
</head>

<body>
    <div id="app">

        <x-layouts.navbar/>

        {{ $slot }}

        <footer class="text-white py-4">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-4 text-center text-md-start">
                        <img src="{{ asset('assets/images/bro.png') }}" alt="Logo" class="mb-3" style="width: 80px;">
                        <p class=" fw-bold">
                            Dusun I, Karangsalam Kidul, Kec. Kedungbanteng, Kabupaten Banyumas, <br>
                            Jawa Tengah 53152
                        </p>
                    </div>
                    <div class="col-md-4 text-center text-md-start">
                        <p>
                            <i class="fas fa-phone-alt me-2"></i>
                            <a href="tel:+6281548795541" class="text-decoration-none text-white">+62 81548795541</a>
                        </p>
                        <p>
                            <i class="fab fa-instagram me-2"></i>
                            <a href="https://instagram.com/smagaofficial_pwt" target="_blank" class="text-decoration-none text-white">
                                @smagaofficial_pwt
                            </a>
                        </p>
                    </div>
                    <div class="col-md-4 text-center  text-md-start">
                        <h2 class=" fw-bold mb-4  fs-3">Tentang</h2>
                        <p>Koperasi SMA N 3 Purwokerto adalah koperasi sekolah yang berperan dalam mendukung kebutuhan siswa dan tenaga pendidik. Koperasi ini menyediakan berbagai keperluan seperti alat tulis, buku pelajaran, seragam, serta makanan dan minuman dengan harga yang terjangkau.
                        </p>
                        {{-- <ul class="list-unstyled mb-0">
                            <li><a href="{{ route('home') }}" class="text-white fw-bold text-decoration-none">Home</a></li>
                            <li><a href="{{ route('listItem') }}" class="text-white fw-bold text-decoration-none">Daftar Barang</a></li>
                            <li><a href="{{ route(name: 'admin.dashboard') }}" class="text-white fw-bold text-decoration-none">Dashboard</a></li>
                        </ul> --}}
                    </div>
                </div>
            </div>
        </footer>
        

    </div>

    @livewireScripts
    <script src="{{url('assets/js/main.js')}}"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>