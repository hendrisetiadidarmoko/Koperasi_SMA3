<div class="container" data-aos="zoom-in-down">
    <div class="row justify-content-center align-content-center vh-100">
        <div class="col-xl-4 col-md-6 col-sm-8">
            <div class="card rounded-4 p-3">
                <div class="card-body text-left">
                    <h1 class="card-title mb-4 text-center">Masuk</h1>

                    <!-- Flash Message untuk Error -->
                    @if (session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <!-- Form Login -->
                    <form wire:submit.prevent="login">
                        @csrf <!-- Token CSRF untuk keamanan -->
                        
                        <!-- Email Input -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold" style="text-align: left; display: block;">Email</label>
                            <input type="email" class="form-control rounded-4" id="email" wire:model="email" placeholder="Enter email" required>
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Password Input -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold" style="text-align: left; display: block;">Password</label>
                            <input type="password" class="form-control rounded-4" id="password" wire:model="password" placeholder="Password" required>
                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Remember Me Checkbox -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" wire:model="remember">
                            <label class="form-check-label fw-semibold" for="remember" style="text-align: left; display: block;">Remember me</label>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn btn-primary text-light rounded-4 fw-bold">Login</button>
                        </div>
                    </form>

                    <!-- Forgot Password Link -->
                    <div class="text-center mt-3">
                        <p>Belum Punya Akun? <span><a href="{{ route('auth.register') }}" class="text-decoration-none fw-semibold">Daftar Sekarang</a></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
