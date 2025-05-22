<div>
    @props(['items', 'id_item', 'price', 'count'])
    <div class="mb-3" >
        <label for="item">Nama Barang :</label>
        <select wire:model="id_item" class="form-control" id="item" required onchange="updatePrice()" >
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

        document.addEventListener('livewire:load', function () {
            Livewire.hook('message.processed', (message, component) => {
                $('#item').val(@this.get('id_item')).trigger('change');
            });
        });
        function updatePrice() {
            const select = document.getElementById("item");
            const selectedOption = select.options[select.selectedIndex];
            const price = selectedOption.getAttribute("data-price");
            document.getElementById("harga").value = price || '';
        }
    </script>
</div>