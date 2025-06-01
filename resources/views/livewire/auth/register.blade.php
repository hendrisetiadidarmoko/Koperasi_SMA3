<div class="container" >
    <div class="row justify-content-center align-content-center vh-100">
        <div class="col-xl-4 col-md-6 col-sm-8">
            <div class="card rounded-4 p-3">
                <div class="card-body text-left">
                    <h1 class="card-title mb- text-center">Daftar</h1>
                    <x-alert.notification/>
                    <form wire:submit.prevent="save">
                        @csrf <!-- Token CSRF untuk keamanan -->
                        
                        <!-- Nama Lengkap Input -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold" >Nama Lengkap</label>
                            <input type="text" class="form-control rounded-4" id="name" name="name" placeholder="Masukkan nama lengkap" wire:model="name" required>
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email Input -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold"" >Email</label>
                            <input type="email" class="form-control rounded-4" id="email" name="email" placeholder="Masukkan email" wire:model="email" required>
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Password Input -->
                        <div class="mb-3">
                            <label for="password" class="form-label text-left fw-semibold" >Password</label>
                            <input type="password" class="form-control rounded-4" id="password" name="password" placeholder="Password" wire:model="password" required>
                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Password Confirmation Input -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label text-left fw-semibold" >Konfirmasi Password</label>
                            <input type="password" class="form-control rounded-4" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password" wire:model="password_confirmation" required>
                            @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary text-light rounded-4 fw-bold">Daftar</button>
                        </div>
                    </form>

                    <!-- Already have an account? Link -->
                    <div class="text-center mt-3">
                        <p>Sudah Punya Akun? <span><a href="{{ route('auth.login') }}" class="text-decoration-none fw-semibold">Login di sini</a></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
