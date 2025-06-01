<div class="container">
    <div class="page-heading my-5">
        <div class="page-title">
            <div class="row">
                <div class="order-md-1 order-last">
                    <h3 class="text-center">Penjualan Barang</h3>
                </div>
            </div>
        </div>

        <section class="section mt-3">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="my-2">
                                <form wire:submit.prevent="scan">
                                    <div class="d-flex">
                                        <input type="text" wire:model.lazy="barcode" class="form-control" autofocus autocomplete="off" placeholder="Input atau scan code"/>
                                        <button type="submit" class="btn btn-primary ms-2">Submit</button>
                                    </div>
                                </form>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-create mb-3" data-bs-toggle="modal" data-bs-target="#form-modal">
                                    Tambah Data
                                </button>
                            </div>
                            <div>
                                <x-alert.notification />
                                <livewire:items-sell.datatable />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Modal Form -->
    <div class="modal fade {{ $modalId }}" id="form-modal" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true" wire:ignore>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="{{ $modalId }}Label"> {{ $itemId ? ' Penjualan Barang' : ' Penjualan Barang' }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="mb-3" >
                            <label for="item">Nama Barang :</label>
                            <select wire:model="id_item" class="form-control" id="item" required onchange="updatePrice()" wire:ignore>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($items as $item)
                                <option value="{{ $item->id }}" data-price="{{ $item->price }}" {{ $item->id == $id_item ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('id_item') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                        
                        <div class="mb-3" >
                            <label for="harga">Harga Barang:</label>
                            <input type="number"  wire:model="price" class="form-control" id="harga" value="{{$price}}" required min="0" step="0.01" readonly style="background-color: #f8f9fa; opacity: 0.65;">
                            @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                
                        <div class="mb-3">
                            <label for="count">Jumlah Barang :</label>
                            <input type="number" wire:model="count" class="form-control" id="count" required min="1" >
                            @error('count') <span class="text-danger">{{ $message }}</span> @enderror
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

    <!-- Script -->
    <script>
        $(document).ready(function () {
            $('#item').select2({
                dropdownParent: $('#form-modal')
            });
            $('#item').on('change', function (e) {
                var data = $('#item').select2("val");
            @this.set('id_item', data);
            });
        });

    
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
        function updatePrice() {
            const select = document.getElementById("item");
            const selectedOption = select.options[select.selectedIndex];
            const price = selectedOption.getAttribute("data-price");
            document.getElementById("harga").value = price || '';
        }
        function showScanner() {
            document.getElementById("scanner-section").style.display = "block";
        }
    </script>
    
</div>
