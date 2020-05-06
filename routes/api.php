<?php

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
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
/*
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');*/

Route::group(['middleware' => ['auth:api', 'cors']], function () {
    Route::get('/products/transaction/{id}', 'Stock\ProductController@transaction');
    Route::resource('/products', 'Stock\ProductController');
    Route::resource('/stock/adjustment', 'Stock\AdjustmentController');
    Route::resource('/brands', 'Stock\BrandController');
    Route::resource('/units', 'Stock\UnitController');
    Route::resource('/product-category', 'Stock\ProductCategoryController');
    Route::resource('/company', 'Stock\CompanyController');


    Route::resource('/customers', 'Customer\CustomerController');
    Route::resource('/customer-category', 'Customer\CustomerCategoryController');


    Route::resource('/suppliers', 'Supplier\SupplierController');
    Route::resource('/supplier-category', 'Supplier\SupplierCategoryController');


    Route::resource('/expense', 'Expense\ExpenseController');
    Route::resource('/expense-category', 'Expense\ExpenseCategoryController');

    Route::resource('/accounts', 'Accounts\AccountsController');


    Route::post('/settings/warehouses/update', 'Settings\WareHouseController@update');
    Route::resource('/settings/warehouses', 'Settings\WareHouseController');
    Route::resource('/settings/discount', 'Settings\DiscountController');
    Route::resource('/settings/shipment', 'Settings\ShipmentController');
    Route::resource('/settings/vat-tax', 'Settings\VatTaxController');
    Route::resource('/settings/division', 'Settings\DivisionController');
    Route::resource('/settings/zilla', 'Settings\ZillaController');
    Route::resource('/settings/upazilla', 'Settings\UpaZillaController');
    Route::resource('/settings/union', 'Settings\UnionController');
    Route::resource('/settings/zone', 'Settings\ZoneController');


    Route::post('/business/update', 'BusinessController@update');
    Route::resource('/business', 'BusinessController');
});

Route::resource('/users', 'User\UserController')->middleware('cors');

Route::post('/auth/login', 'AuthController@login')->middleware('cors');
Route::post('/auth/logout', 'AuthController@logout')->middleware('cors');
Route::post('/auth/refresh', 'AuthController@refresh')->middleware('cors');
Route::post('/auth/me', 'AuthController@me')->middleware('cors');



/**
 *
 *
Route::group([

    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

*/
