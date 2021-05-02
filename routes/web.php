<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Products;
use App\Http\Livewire\Allocations;
use App\Http\Livewire\Plants;
use App\Http\Livewire\AllocationShares;
use App\Http\Livewire\ShareWaybills;
use App\Models\Waybill;
use App\Models\Share;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::group(['middleware' => [
    'auth:sanctum',
    'verified'
]], function(){
    Route::get('/products', Products::class)->name('products');
    Route::get('/allocations', Allocations::class)->name('allocations');
    Route::get('/plants', Plants::class)->name('plants');
    Route::get('/ashares/{allocation}', AllocationShares::class)->name('ashares');
    Route::get('/swaybills/{share}', ShareWaybills::class)->name('swaybills');

    Route::get('/waybill/{id}', function ($id) {
        $waybill = Waybill::find($id);
        PDF::stream('waybill', ['waybill' => $waybill]);
        //return $pdf->stream('waybill.pdf');
    })->name('waybill');

    Route::get('/share/{id}', function ($id) {
        $share = Share::find($id);
        $sum = $share->waybills()->sum('quantity');
        $balance =  $share->quantity - $sum;
        $waybills = $share->waybills()->get();
        $pdf = PDF::loadView('share', ['share' => $share, 'waybills' => $waybills, 'sum' => $sum, 'balance' => $balance]);
        return $pdf->stream('share.pdf');
    })->name('share');
});


