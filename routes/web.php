<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PrintController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::group(['middleware' => 'guest'], function(){

    //login
    Route::get('/login', App\Livewire\Auth\Login::class)->name('auth.login');


    Route::get('/Register', App\Livewire\Auth\Register::class)->name('auth.register');
    

    
});

Route::group(['middleware' => 'role:admin,pegawai,user'], function() {
    Route::get('/admin/profile', App\Livewire\Profile\Index::class)->name('admin.profile');
    Route::get('/admin/dashboard', App\Livewire\Dashboard\Index::class)->name('admin.dashboard');
});


Route::group(['middleware' => 'role:admin'], function() {
    Route::get('/admin/user', App\Livewire\Users\Index::class)->name('admin.user');
});

Route::get('/',App\Livewire\LandingPage\Index::class)->name('home');
Route::get('/daftar-barang',App\Livewire\ListItem\Index::class)->name('listItem');
Route::get('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::group(['middleware' => 'role:admin,pegawai'], function() {

    Route::get('/admin/items', App\Livewire\Items\Index::class)->name('admin.items');

    Route::get('/admin/items-purchase', App\Livewire\ItemsPurchase\Index::class)->name('admin.purchase.items');

    Route::get('/admin/items-sell', App\Livewire\ItemsSell\Index::class)->name('admin.sell.items');

    Route::get('/admin/monthly-report/{year}/{month}', App\Livewire\MonthlyReport\Index::class)->name('admin.report.monthly');

    Route::get('/admin/transaction', App\Livewire\Transaction\Index::class)->name('admin.transaction');
    Route::get('/admin/print', App\Livewire\Print\Index::class)->name('admin.print');

    Route::get('/admin/print/{year}', [PrintController::class, 'cetak'])->name('admin.print.month');


    Route::get('/admin/transactionYears', App\Livewire\Years\Index::class)->name('admin.transaction.years');

    Route::get('/admin/transactions/{year}', App\Livewire\Monthly\Index::class)->name('admin.transaction.months');

    Route::get('/report-pdf/{year}/{month}', App\Livewire\MonthPdf\Index::class)->name('report.month.pdf');

    Route::get('/report-pdf/{year}', App\Livewire\YearPdf\Index::class)->name('report.year.pdf');
        // routes/web.php
    Route::post('/scan-store', [\App\Http\Controllers\ScanController::class, 'scan'])->name('scan.store');

});