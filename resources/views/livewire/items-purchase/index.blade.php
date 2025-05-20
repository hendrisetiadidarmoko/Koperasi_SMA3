<div class="container" >
    <div class="page-heading my-5" data-aos="fade-left">
        <div class="page-title">
            <div class="row">
                <div class="order-md-1 order-last">
                    <h3 class="text-center">Pembelian Barang</h3>
                </div>
            </div>
        </div>
        <section class="section mt-3">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-create mb-3" data-bs-toggle="modal" data-bs-target="#form-modal">
                                    Tambah Data
                                </button>
                            </div>
                            <div>
                                <x-alert.notification/>
                                <livewire:itemsPurchase.datatable />
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
                    <h1 class="modal-title fs-5" id="{{ $modalId }}Label"> {{ $itemId ? 'Edit Barang' : 'Tambah Barang' }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="item">Nama Barang :</label>
                            <select wire:model="id_item" class="form-control" id="item" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $id_item ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('id_item') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                
                        <div class="mb-3">
                            <label for="harga">Harga Barang :</label>
                            <input type="number" wire:model="price" class="form-control" id="harga" required min="0" step="0.01">
                            @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                
                        <div class="mb-3">
                            <label for="count">Jumlah Barang :</label>
                            <input type="number" wire:model="count" class="form-control" id="count" required min="1">
                            @error('count') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="created_at">Tanggal & Waktu Pembelian:</label>
                            <input type="datetime-local" wire:model="created_at" class="form-control" id="created_at" required>
                            @error('created_at') <span class="text-danger">{{ $message }}</span> @enderror
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