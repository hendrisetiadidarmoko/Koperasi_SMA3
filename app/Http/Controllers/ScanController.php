<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemSell;
use Illuminate\Support\Facades\Auth;

class ScanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $item = Item::where('barcode', $request->barcode)->first();

        if (!$item) {
            return response()->json(['error' => 'Item tidak ditemukan dengan barcode tersebut.']);
        }

        if ($item->count < 1) {
            return response()->json(['error' => 'Stok item habis.']);
        }

        $data = [
            'id_item' => $item->id,
            'price' => $item->price,
            'count' => 1,
            'user_id' => Auth::id(),
        ];

        ItemSell::create($data);

        // Update stok
        $item->count -= 1;
        $item->save();

        session()->flash('message', 'Barang berhasil ditambahkan melalui scan.');

        return response()->json([
            'message' => 'Barang berhasil ditambahkan melalui scan.',
            'data' => $data,
        ]);
    }

    public function scan(Request $request)
    {
        $barcode = $request->input('barcode');

        // Validasi format barcode EAN-13 (harus 13 digit angka)
        if (!preg_match('/^\d{13}$/', $barcode)) {
            return response()->json([
                'error' => 'Format barcode tidak valid.'
            ], 422);
        }

        // Cari produk berdasarkan barcode
        $item = Item::where('ean13', $barcode)->first();

        if (!$item) {
            return response()->json([
                'error' => 'Produk tidak ditemukan.'
            ], 404);
        }

        // Cek stok barang
        if ($item->count <= 0) {
            return response()->json([
                'error' => 'Stok barang habis.'
            ], 400);
        }

        // Siapkan data transaksi penjualan
        $data = [
            'id_item' => $item->id,
            'price' => $item->price,
            'count' => 1,
            'user_id' => Auth::id(),
        ];

        // Simpan penjualan
        ItemSell::create($data);

        // Update stok
        $item->count -= 1;
        $item->save();

        // Response sukses
        return response()->json([
            'message' => 'Barang berhasil ditambahkan melalui scan.',
            'data' => $data,
        ]);
    }

}

