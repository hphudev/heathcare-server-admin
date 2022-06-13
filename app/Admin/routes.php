<?php

//Chỉ use 2 cái ở dưới thôi, còn lại thì xóa

use Facade\FlareClient\Http\Client;
use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('users', UserController::class);
    $router->resource('unit/product-units', ProductUnitController::class);
    $router->resource('unit/producer-units', ProducerUnitController::class);
    $router->resource('unit/transport-units', TransportUnitController::class);
    $router->resource('drug/drug-list', DrugController::class);
    $router->resource('drug/drug-groups', DrugGroupController::class);
    $router->resource('drug/receipts', ReceiptController::class);
    $router->resource('drug/receipt-details', ReceiptDetailController::class);
    $router->resource('order/bills', BillController::class);
    $router->resource('order/detail-bills', DetailBillController::class);
    $router->resource('order/shipping-details', ShippingDetailController::class);
    // $router->get('order/bills/autoGeneratePrice', [BillController::class, 'autoGeneratePrice']);

});
