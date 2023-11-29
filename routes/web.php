<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SampleController;

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

Route::get('/', function () {
    return view('welcome');
});

route::get('sample',[SampleController::class,'index'])->name('sample.index');
route::post('sample',[SampleController::class,'store'])->name('sample.store');
// // route::post('sample/update',[SampleController::class,'update'])->name('sample.update');
Route::post('sample/update', [SampleController::class,'update'])->name('sample.update');
route::get('/sample/{id}/edit',[SampleController::class,'edit']);
route::get('sample/destroy/{id}',[SampleController::class,'destroy']);

// route::get('/anggotas',[AnggotaController::class,'destroy'])->name('anggotas.destroy');

// tes