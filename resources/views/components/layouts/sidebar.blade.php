
            <div class="bg-white">
                <ul class="nav nav-pills flex-column mx-0 d-flex flex-column justify-content-between min-vh-100 pt-5">
                    <!-- Nav items yang lain -->
                    <div class=" nav-group  py-4">
                        <li class="nav-item py-2 py-sm-0">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                                <i class="fa-solid fa-house fs-4"></i>
                                <span class="fs-4 d-none d-sm-inline ms-1">Dashboard</span>
                            </a>
                        </li>
                
                        @if (Auth::user()->role === 'admin' || Auth::user()->role === 'pegawai')
                            <li class="nav-item py-2 py-sm-0">
                                <a href="{{ route('admin.items') }}" class="nav-link {{ Route::is('admin.items') ? 'active' : '' }}">
                                    <i class="fas fa-box fs-4"></i>
                                    <span class="fs-4 d-none d-sm-inline ms-1">Barang</span>
                                </a>
                            </li>

                            <li class="nav-item py-2 py-sm-0">
                                <a href="{{ route('admin.transaction') }}" class="nav-link {{ Route::is('admin.transaction') ? 'active' : '' }}">
                                    <i class="fas fa-shopping-cart fs-4"></i>
                                    <span class="fs-4 d-none d-sm-inline ms-1">Transaksi</span>
                                </a>
                            </li>

                            <li class="nav-item py-2 py-sm-0">
                                <a href="{{ route('admin.print') }}" class="nav-link {{ Route::is('admin.print') ? 'active' : '' }}">
                                    <i class="fas fa-print fs-4"></i>
                                    <span class="fs-4 d-none d-sm-inline ms-1">Print</span>
                                </a>
                            </li>

                            
                        @endif
                        @if (Auth::user()->role === 'admin')
                            <li class="nav-item py-2 py-sm-0">
                                <a href="{{ route('admin.user') }}" class="nav-link {{ Route::is('admin.user') ? 'active' : '' }}">
                                    <i class="fa-solid fa-user fs-4"></i>
                                    <span class="fs-4 d-none d-sm-inline ms-1">User</span>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item py-2 py-sm-0">
                            <a href="{{ route('admin.profile') }}" class="nav-link {{ Route::is('admin.profile') ? 'active' : '' }}">
                                <i class="fa-solid fa-user-circle fs-4"></i>
                                <span class="fs-4 d-none d-sm-inline ms-1">Profile</span>
                            </a>
                        </li>
                    </div>
                    <!-- Keluar -->
                    <li class="nav-item py-2 py-sm-0">
                        <a href="{{ route('auth.logout') }}" class="nav-link {{ Route::is('auth.logout') ? 'active' : '' }}">
                            <i class="fas fa-sign-out-alt fs-4"></i>
                            <span class="fs-4 d-none d-sm-inline ms-1">Keluar</span>
                        </a>
                    </li>
                </ul>
            </div>
            