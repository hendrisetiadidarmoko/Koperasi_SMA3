<div data-aos="fade-left">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card w-100">
            <div class="card-header text-center fw-bold content-header fs-3">Profile</div>
            <div class="card-body ">
                <div class="row justify-content-between align-content-center p-5">
                    <x-alert.notification/>
                    <div class="col-md-5">
                        <form wire:submit.prevent="update">
                            <div class="mb-3">
                                <label for="name">Nama : </label>
                                <input type="text" wire:model="name" class="form-control rounded-4" id="name" required>
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email">Email : </label>
                                <input type="text" wire:model="email" class="form-control rounded-4" id="email" required readonly >
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="phone_number">Nomer HP : </label>
                                <input type="text" wire:model="phone_number" class="form-control rounded-4" id="phone_number" required>
                                @error('phone_number') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-custom text-light rounded-4">Simpan</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-5">
                        <form wire:submit.prevent="updatePass">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru: </label>
                                <input type="text" wire:model="password" class="form-control rounded-4" id="password" required>
                                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label" >Konfirmasi Password Baru</label>
                                <input type="password" class="form-control rounded-4" id="password_confirmation" name="password_confirmation" wire:model="password_confirmation" required>
                                @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-custom text-light rounded-4">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            
        </div>
    </div>
</div>
