<?php

use App\Admin\Controllers\BillController;
use App\Http\Controllers\API\TestController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/order/bills/autoGeneratePrice', [TestController::class, 'autoGeneratePrice']);
Route::get('/order/bills/autoGenerateDiscount', [TestController::class, 'autoGenerateDiscount']);
Route::post('/user-admin/get-users-and-admins', [TestController:: class, 'getUsersAndAdmins']);
Route::post('/bill/get-bills', [TestController::class, 'getBills']);
Route::post('/drugs/get-all-drugs', [TestController::class, 'getAllDrugs']);
Route::post('/drugs/get-drugs-with-drug-groups', [TestController::class, 'getDrugsWithDrugGroup']);
Route::post('/drugs/get-top-four-new-drugs', [TestController::class, 'getTopFourNewDrugs']);
Route::post('/user/get-user', [TestController::class, 'getUser']);
