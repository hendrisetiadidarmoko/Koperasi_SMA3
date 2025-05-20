
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid mx-5">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}"><img src="{{asset('assets/images/logo.png')}}" alt=""></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class=" collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link fw-semibold" aria-current="page" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" aria-current="page" href="{{ route(name: 'listItem') }}">Daftar Barang</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold" aria-current="page" href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                
                {{-- <li class="nav-item">
                    <a class="nav-link fw-semibold" href="#">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="#">Keunggulan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="#">Daftar Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="#">Kontak</a>
                </li> --}}
            </ul>
            <!-- Tombol Login dan Register -->
            
                @guest
                    <div class="d-flex ms-3">
                        <a href="{{ route('auth.login') }}" class="btn btn-outline-login me-2">Login</a>
                        <a href="{{ route('auth.register') }}" class="btn btn-register">Register</a>
                    </div>
                @endguest
        </div>
    </div>
</nav>
