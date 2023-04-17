<?php

use App\Http\Controllers\API\Category\CategoryController;
use App\Http\Controllers\API\Customer\AddressCustomerController;
use App\Http\Controllers\API\Customer\AuthController;
use App\Http\Controllers\API\Product\ProductController;
use App\Http\Controllers\API\Transaction\CheckoutController;
use App\Http\Controllers\API\Transaction\TransactionController;
use App\Http\Controllers\API\Banner\BannerController;
use App\Http\Controllers\Api\Xendit\XenditPaymentController;
use App\Http\Controllers\API\Voucher\VoucherController;
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

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'otp'], function () {
        Route::post('send',[AuthController::class,'send']);
        Route::post('verify',[AuthController::class,'verify']);
    });
    Route::get('list-payment',[CheckoutController::class, 'listPaymentChannels']);

    
    Route::group(['prefix' => 'banner'], function () {
        Route::get('',[BannerController::class, 'index']);
    });
    Route::group(['prefix' => 'categories'], function () {
        Route::get('',[CategoryController::class, 'index']);
    });
    Route::group(['prefix' => 'products'], function () {
        Route::get('{categoryId}',[ProductController::class, 'index']);
        Route::get('{categoryId}/all',[ProductController::class, 'all']);
        Route::get('detail/{productId}',[ProductController::class, 'detail']);
        Route::get('',[ProductController::class, 'search']);
    });
    
    Route::post('bulk/product',[ProductController::class, 'getBulk']);
    /* XENDIT */
    Route::group(['prefix' => 'xendit'], function () {
        Route::post('ewallet-callback', [XenditPaymentController::class, 'eWalletCallback']);
        Route::post('va-callback', [XenditPaymentController::class, 'virtualAccountCallback']);
        Route::post('retail-callback', [XenditPaymentController::class, 'retailCallback']);
        Route::post('invoice-callback', [CheckoutController::class, 'invoiceCallback']);
    });

    Route::group(['middleware' => 'customer_auth'], function () {
        /** CUSTOMER ADDRESS */
        Route::get('alamat',[AddressCustomerController::class, 'index']);
        Route::post('alamat',[AddressCustomerController::class, 'store']);
        Route::post('update-alamat',[AddressCustomerController::class, 'update']);

        /* CHECKOUT */
        Route::post('checkout',[CheckoutController::class, 'checkout']);

        /* TRANSACTION */
        Route::get('transaction',[TransactionController::class, 'index']);
        Route::get('transaction/all',[TransactionController::class, 'getTransaction']);
        Route::get('transaction/detail',[TransactionController::class, 'getDetail']);
        Route::get('transaction/track',[TransactionController::class, 'getTracking']);
        Route::post('callback/invoice', [CheckoutController::class, 'invoiceCallback']);
        Route::get('transaction/invoice/download', [TransactionController::class, 'downloadInvoice']);
        
        /* VOUCHER */
        Route::get('voucher', [VoucherController::class, 'index']);
        
        /* CUSTOMER */
        Route::get('customer/refresh', [AuthController::class, 'refresh']);
    });
    Route::get('transaction/items',[TransactionController::class, 'getTransactionItem']);
    


});
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
