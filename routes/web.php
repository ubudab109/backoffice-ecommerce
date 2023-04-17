<?php

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

Route::prefix('s')->group(function () {
	Route::post('/', 'Security\EncryptController@getPass')->name('s');
});

Route::get('/login', 'LoginController@loginView')->name('get.login.view');
Route::post('/post-login', 'LoginController@postLogin')->name('post.login');
Route::post('/reload-captcha', 'LoginController@reloadCaptcha')->name('reload.captcha');

Route::prefix('forget-password')->group(function (){
    Route::get('/', 'LoginController@viewForgetPassword')->name('get.forget.password');
    Route::post('/post-forget', 'LoginController@forgetPassword')->name('post.forget.password');
});
Route::prefix('user-new-password')->group(function () {
    Route::get('/view-new-password/{id}', 'LoginController@viewResetPassword')->name('get.reset.password');
    Route::post('/reset-password', 'LoginController@resetPassword')->name('post.reset.password');
});

Route::prefix('logout')->group(function () {
    Route::get('/', 'LoginController@logout')->name('get.logout');
});

Route::post('webhook/qiscus', 'WebhookController@receive')->name('post.webhook.receive');

Route::get('/new-password/{token}', 'LoginController@newPasswordView')->name('new.password');
Route::post('/post-new-password', 'LoginController@newPassword')->name('post.new.password');
Route::middleware('authuser')->group(function(){

    Route::get('/', 'HomeController@welcomeView')->name('home');

    Route::group(['prefix'=>'role'], function(){
        Route::get('/', 'RoleController@viewRole')->name('get.role')->middleware('permission:role_management_list');
        Route::get('/list-role','RoleController@listRole')->name('post.role.list')->middleware('permission:role_management_list');
        Route::get('/add-role', 'RoleController@viewRoleAdd')->name('add.role')->middleware('permission:role_management_add');
        Route::post('/post-add-role','RoleController@addRole')->name('post.add.role')->middleware('permission:role_management_add');
        Route::get('/detail-role/{id}', 'RoleController@viewRoleDetail')->name('get.detail.role')->middleware('permission:role_management_detail');
        Route::get('/detail-edit-role/{id}', 'RoleController@viewRoleEdit')->name('get.edit-detail.role')->middleware('permission:role_management_edit');
        Route::post('/edit-role', 'RoleController@editRole')->name('post.edit.role')->middleware('permission:role_management_edit');
        Route::post('/delete-role', 'RoleController@deleteRole')->name('post.delete.role')->middleware('permission:role_management_delete');
    });

    Route::group(['prefix'=>'user'], function(){
        Route::get('/', 'UserController@viewUser')->name('get.user')->middleware('permission:user_management_list');
        Route::get('/list-user','UserController@listUser')->name('post.user.list')->middleware('permission:user_management_list');
        Route::get('/add-user', 'UserController@viewUserAdd')->name('add.user')->middleware('permission:user_management_add');
        Route::post('/post-add-user','UserController@addUser')->name('post.add.user')->middleware('permission:user_management_add');
        Route::get('/detail-user/{id}', 'UserController@viewUserDetail')->name('get.detail.user')->middleware('permission:user_management_detail');
        Route::get('/detail-edit-user/{id}', 'UserController@viewUserEdit')->name('get.edit-detail.user')->middleware('permission:user_management_edit');
        Route::post('/edit-user', 'UserController@editUser')->name('post.edit.user')->middleware('permission:user_management_edit');
        Route::post('/status-user','UserController@changeStatusUser')->name('post.status.user')->middleware('permission:user_management_edit');
        Route::post('/delete-user', 'UserController@deleteUser')->name('post.delete.user')->middleware('permission:user_management_delete');
        Route::post('/search-permission', 'UserController@searchPermission')->name('post.search.permission');
    });

    Route::group(['prefix' => 'category'], function () {
        Route::get('/','CategoryController@viewCategory')->name('get.category')->middleware('permission:category_management_list');
        Route::get('/list-category','CategoryController@listCategory')->name('post.category.list')->middleware('permission:category_management_list');
        Route::get('/add-category','CategoryController@viewAddCategory')->name('add.category')->middleware('permission:category_management_add');
        Route::post('/post-add-category','CategoryController@addCategory')->name('post.add.category')->middleware('permission:category_management_add');
        Route::get('/detail-category/{id}','CategoryController@viewCategoryDetail')->name('get.detail.category')->middleware('permission:category_management_detail');
        Route::post('/edit-category','CategoryController@editCategory')->name('post.edit.category')->middleware('permission:category_management_edit');
        Route::post('/status-category','CategoryController@changeStatusCategory')->name('post.status.category')->middleware('permission:category_management_edit');
        Route::post('/delete-category','CategoryController@deleteCategory')->name('post.delete.category')->middleware('permission:category_management_delete');
    });

    Route::group(['prefix' => 'banner'], function () {
        Route::get('/','BannerController@viewBanner')->name('get.banner')->middleware('permission:banner_management_list');
        Route::get('/list-banner','BannerController@listBanner')->name('post.banner.list')->middleware('permission:banner_management_list');
        Route::get('/add-banner','BannerController@viewAddBanner')->name('add.banner')->middleware('permission:banner_management_add');
        Route::post('/post-add-banner','BannerController@addBanner')->name('post.add.banner')->middleware('permission:banner_management_add');
        Route::get('/detail-banner/{id}','BannerController@viewBannerDetail')->name('get.detail.banner')->middleware('permission:banner_management_detail');
        Route::post('/edit-banner','BannerController@editBanner')->name('post.edit.banner')->middleware('permission:banner_management_edit');
        Route::post('/status-banner','BannerController@changeStatusBanner')->name('post.status.banner')->middleware('permission:banner_management_edit');
        Route::post('/delete-banner','BannerController@deleteBanner')->name('post.delete.banner')->middleware('permission:banner_management_delete');
    });

    Route::group(['prefix' => 'product'], function () {
        Route::get('/','ProductController@viewProduct')->name('get.product')->middleware('permission:product_management_list');
        Route::get('/list-product','ProductController@listProduct')->name('post.product.list')->middleware('permission:product_management_list');
        Route::get('/add-product','ProductController@viewAddProduct')->name('add.product')->middleware('permission:product_management_add');
        Route::post('/post-add-product','ProductController@addProduct')->name('post.add.product')->middleware('permission:product_management_add');
        Route::get('/detail-product/{id}','ProductController@viewProductDetail')->name('get.detail.product')->middleware('permission:product_management_detail');
        Route::get('/edit-detail-product/{id}','ProductController@viewProductEdit')->name('get.edit-detail.product')->middleware('permission:product_management_edit');
        Route::get('/inventory-detail-product','ProductController@viewProductDetailInventory')->name('get.inventory-detail.product')->middleware('permission:product_management_inventory');
        Route::post('/edit-product','ProductController@editProduct')->name('post.edit.product')->middleware('permission:product_management_edit');
        Route::post('/status-promo-product','ProductController@changePromoStatus')->name('post.status-promo.product')->middleware('permission:product_management_edit');
        Route::post('/upload-files/{productId}','ProductController@uploadFile')->name('post.file.product');
        Route::delete('/delete-files/{imageId}','ProductController@deleteImage')->name('delete.file.product');
        Route::post('/status-product','ProductController@changeStatusProduct')->name('post.status.product')->middleware('permission:product_management_edit');
        Route::post('/delete-product','ProductController@deleteProduct')->name('post.delete.product')->middleware('permission:product_management_delete');
    });

    Route::group(['prefix' => 'inventory'], function () {
        Route::get('/', 'ProductInventoryController@viewInventory')->name('get.inventory')->middleware('permission:inventory_management_list');
        Route::get('/list-inventory', 'ProductInventoryController@listInventory')->name('post.inventory.list')->middleware('permission:inventory_management_list');
        Route::get('/detail-inventory/{id}', 'ProductInventoryController@viewDetailInventory')->name('get.detail.inventory')->middleware('permission:inventory_management_detail');
        Route::get('/edit-detail-inventory/{id}', 'ProductInventoryController@viewEditInventory')->name('get.edit-detail.inventory')->middleware('permission:inventory_management_edit');
        Route::get('/add-inventory','ProductInventoryController@viewAddInventory')->name('add.inventory')->middleware('permission:inventory_management_add');
        Route::post('/post-add-inventory','ProductInventoryController@addInventory')->name('post.add.inventory')->middleware('permission:inventory_management_add');
        Route::post('/edit-inventory','ProductInventoryController@editInventory')->name('post.edit.inventory')->middleware('permission:inventory_management_edit');
        Route::post('/delete-inventory','ProductInventoryController@deleteInventory')->name('post.delete.inventory')->middleware('permission:inventory_management_delete');
    });

    Route::group(['prefix' => 'voucher'], function () {
        Route::get('/','VoucherController@viewVoucher')->name('get.voucher')->middleware('permission:voucher_management_list');
        Route::get('/list-voucher','VoucherController@listVoucher')->name('post.voucher.list')->middleware('permission:voucher_management_list');
        Route::get('/add-voucher','VoucherController@viewAddVoucher')->name('add.voucher')->middleware('permission:voucher_management_add');
        Route::get('/detail-voucher/{id}','VoucherController@viewVoucherDetail')->name('get.detail.voucher')->middleware('permission:voucher_management_detail');
        Route::get('/edit-detail-voucher/{id}','VoucherController@viewEditVoucher')->name('get.edit-detail.voucher')->middleware('permission:voucher_management_edit');
        Route::post('/post-add-voucher','VoucherController@addVoucher')->name('post.add.voucher')->middleware('permission:voucher_management_add');
        Route::post('/post-edit-voucher','VoucherController@editVoucher')->name('post.edit.voucher')->middleware('permission:voucher_management_edit');
        Route::post('/status-voucher','VoucherController@changeStatusVoucher')->name('post.status.voucher')->middleware('permission:voucher_management_edit');
        Route::post('/delete-inventory','VoucherController@deleteVoucher')->name('post.delete.voucher')->middleware('permission:voucher_management_delete');
    });

    Route::group(['prefix' => 'customer'], function () {
        Route::get('/','CustomerController@viewCustomer')->name('get.customer')->middleware('permission:customer_management_list');
        Route::get('/list-customer','CustomerController@listCustomer')->name('post.customer.list')->middleware('permission:customer_management_list');
        Route::get('/detail-customer','CustomerController@viewDetailCustomer')->name('get.detail.customer')->middleware('permission:customer_management_detail');
        Route::get('/detail-customer/transaction','CustomerController@historyTransactionCustomer')->name('get.detail-transaction.customer')->middleware('permission:customer_management_detail');
    });

    Route::group(['prefix' => 'transaction'], function () {
        Route::get('/','TransactionController@viewTransaction')->name('get.transaction')->middleware('permission:transaction_management_list');
        Route::get('/list-transaction', 'TransactionController@listTransactions')->name('post.transaction.list')->middleware('permission:transaction_management_list');
        Route::get('/detail-transaction/{uuid}', 'TransactionController@viewDetailTransaction')->name('get.transaction.detail')->middleware('permission:transaction_management_detail');
        Route::post('/update-status-transaction', 'TransactionController@updateStatusTransaction')->name('post.status.transaction')->middleware('permission:transaction_management_edit');
        Route::get('/download-invoice', 'TransactionController@downloadInvoice')->name('get.download.invoice')->middleware('permission:transaction_management_detail');
    });



});
