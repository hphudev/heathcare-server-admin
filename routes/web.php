<?php

use App\Admin\Controllers\BillController;
use App\Admin\Controllers\ProductUnitController;
use App\Http\Controllers\API\TestController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/admin/order/bills/autoGeneratePrice', [BillController::class, 'autoGeneratePrice']);
// Route::get('/autoGeneratePrice', [BillController::class, 'autoGeneratePrice']);

