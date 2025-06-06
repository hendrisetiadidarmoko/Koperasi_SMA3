<div class="container" >
    <div class="page-heading my-5">
        <div class="page-title">
            <div class="row">
                <div class="order-md-1 order-last">
                    <h3 class="text-center">Data User</h3>
                </div>
            </div>
        </div>
        <section class="section mt-3">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0">
                        <div class="card-body">
                            <div>
                                <x-alert.notification/>
                                <livewire:users.datatable />
                            </div>
                        </div>
                        
                        
                    </div>
                </div>
            </div>
        </section>
        
    </div>

    <!-- Modal -->
    <div class="modal fade {{ $modalId }}" id="form-modal" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="{{ $modalId }}Label">Edit User Role</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name">Nama User:</label>
                            <input type="text" wire:model="name" class="form-control" required>
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                
                        <div class="mb-3">
                            <label for="role">Pilih Role:</label>
                            <select wire:model="role" class="form-control" required>
                                <option value="">Pilih Role</option>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                                <option value="pegawai">Pegawai</option>
                            </select>
                            @error('role') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="closeModal">Keluar</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
                
                
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.addEventListener('show-modal', event => {
                var myModal = new bootstrap.Modal(document.getElementById('form-modal'), {
                    keyboard: false
                });
                myModal.show();
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            window.addEventListener('close-modal', event => {
                var myModalEl = document.getElementById('form-modal');
                var modal = bootstrap.Modal.getInstance(myModalEl);
                if (modal) {
                    modal.hide();
                }
            });
        });

    </script>
    
</div>