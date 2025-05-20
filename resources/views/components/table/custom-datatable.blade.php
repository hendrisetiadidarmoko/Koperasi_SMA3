<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Barang</th>
            <th colspan="3">Persediaan Awal</th> <!-- Grouping header -->
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($this->rows as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ $row->count }}</td>
                <td>{{ number_format($row->price, 0, '.', ',') }}</td>
                <td>{{ number_format($row->count * $row->price, 0, '.', ',') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>