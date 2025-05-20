<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
class PrintController extends Controller
{
    public function cetak($year)
    {
        // Contoh debug
        dd($year);
    }
    
}
