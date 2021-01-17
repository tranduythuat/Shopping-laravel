<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admins\UserController;
use App\Http\Controllers\Admins\ColorController;
use App\Http\Controllers\Admins\SizeController;
use App\Http\Controllers\Admins\ProductController;
use App\Http\Controllers\Admins\CategoryController;
use App\Http\Controllers\Admins\DashboardController;
use App\Http\Controllers\Admins\SupplierController;
use App\Http\Controllers\Admins\SliderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductDetailController;

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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/product', [HomeController::class, 'loadJsonProduct']);
Route::post('/product-overview', [HomeController::class, 'loadJsonProductOverview']);
Route::post('/product-quanity', [HomeController::class, 'loadJsonProductQuanity']);
Route::post('/product-addcart', [HomeController::class, 'jsonAddCartProduct']);
Route::post('/product-filter', [HomeController::class, 'jsonFilterProduct']);
Route::post('/product-search', [HomeController::class, 'jsonSearchProduct']);
Route::get('/delete-cart-item/{id}', [HomeController::class, 'jsonDeleteCartItem']);

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/transaction', [CheckoutController::class, 'transaction'])->name('transaction');
Route::get('/checkout-success', [CheckoutController::class, 'checkoutSuccess'])->name('checkout_success');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/update-cart-item', [CartController::class, 'jsonUpdateCartItem']);
Route::get('/product-detail/{productId}', [HomeController::class, 'productDetailView'])->name('product.detail');
Route::post('/num-product-item', [HomeController::class, 'jsonNumProductItem']);
Route::post('/product-add-to-cart', [ProductDetailController::class, 'jsonProductAddToCart']);

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin')->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('.dashboard');
    Route::post('/dashboard', [DashboardController::class, 'jsonTransactionDatatable'])->name('.transaction.list');
    Route::get('/transaction-pending-info/{id}', [DashboardController::class, 'jsonTransactionPendingInfo']);
    Route::get('/transaction-update-status/{id}/{status}', [DashboardController::class, 'jsonUpdateTransactionStatus']);
    Route::get('/transaction-delete/{id}', [DashboardController::class, 'jsonDeleteTransaction']);

    Route::prefix('product')->name('.product')->group(function(){
        Route::get('/', [ProductController::class, 'index'])->name('.list');
        Route::post('/all', [ProductController::class, 'getAll']);
        Route::post('/number', [ProductController::class, 'getNumber']);
        Route::get('/add', [ProductController::class, 'store'])->name('.add');
        Route::post('/add', [ProductController::class, 'add']);
        Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('.edit');
        Route::post('/update/{id}', [ProductController::class, 'update'])->name('.update');
        Route::post('/show/{id}', [ProductController::class, 'getDetail'])->name('.show');
        Route::post('/delete', [ProductController::class, 'delete']);
        Route::get('/trash', [ProductController::class, 'trashIndex'])->name('.trash');
        Route::post('/trash', [ProductController::class, 'trashAll'])->name('.trash');
        Route::post('/trash-number-product', [ProductController::class, 'getNumberTrashProduct']);
        Route::post('/destroy-trash', [ProductController::class, 'destroy']);
        Route::post('/restore-trash', [ProductController::class, 'restore']);
        Route::post('/delete-rows', [ProductController::class, 'deleteRows']);
        Route::post('/destroy-rows', [ProductController::class, 'destroyRows']);
        Route::post('/restore-rows', [ProductController::class, 'restoreRows']);
        Route::post('/update-status', [ProductController::class, 'updateStatus']);

        Route::get('/item/{id}', [ProductController::class, 'indexItem'])->name('.item');
        Route::post('/item/{id}', [ProductController::class, 'allProductItem'])->name('.item');
        Route::post('/item/{id}/number', [ProductController::class, 'numberProductItem']);
        Route::post('/item/{id}/color-by-product', [ProductController::class, 'getColorByProductItem']);
        Route::post('/item/{id}/add', [ProductController::class, 'storeProductItem']);
        Route::post('/item/{productColorId}/color/{colorId}/size/{SizeId}', [ProductController::class, 'showProductItem']);
        Route::post('/item/{productColorId}/color/{colorId}/size/{SizeId}/delete', [ProductController::class, 'deleteProductItem']);
        Route::post('/item/{producId}/product-color/{producColorId}/delete', [ProductController::class, 'deleteProductColor']);
        Route::get('/item/{producId}/product-color/{colorId}/list-image', [ProductController::class, 'listImageProductColor'])->name('.product-color-item');
        Route::post('/item/{producId}/product-color/{colorId}/upload-image', [ProductController::class, 'uploadImageProductColor']);
    });

    Route::prefix('color-product')->name('.color_product')->group(function(){
        Route::get('/', [ColorController::class, 'index'])->name('.list');
        Route::post('/all', [ColorController::class, 'getAll']);
        Route::post('/store', [ColorController::class, 'store']);
        Route::post('/number', [ColorController::class, 'getNumber']);
        Route::post('/edit', [ColorController::class, 'edit']);
        Route::post('/delete', [ColorController::class, 'delete']);
        Route::post('/update-status', [ColorController::class, 'updateStatus']);
        Route::post('/delete-rows', [ColorController::class, 'deleteRows']);
    });

    Route::prefix('size-product')->name('.size_product')->group(function(){
        Route::get('/', [SizeController::class, 'index'])->name('.list');
        Route::post('/all', [SizeController::class, 'getAll']);
        Route::post('/store', [SizeController::class, 'store']);
        Route::post('/number', [SizeController::class, 'getNumber']);
        Route::post('/edit', [SizeController::class, 'edit']);
        Route::post('/delete', [SizeController::class, 'delete']);
        Route::post('/update-status', [SizeController::class, 'updateStatus']);
        Route::post('/delete-rows', [SizeController::class, 'deleteRows']);
    });

    Route::prefix('category')->name('.category')->group(function(){
        Route::get('/', [CategoryController::class, 'index'])->name('.list');
        Route::post('/all', [CategoryController::class, 'getAll']);
        Route::post('/store', [CategoryController::class, 'store']);
        Route::post('/number', [CategoryController::class, 'getNumber']);
        Route::post('/edit', [CategoryController::class, 'edit']);
        Route::post('/delete', [CategoryController::class, 'delete']);
        Route::post('/update-status', [CategoryController::class, 'updateStatus']);
    });

    Route::prefix('supplier')->name('.supplier')->group(function(){
        Route::get('/', [SupplierController::class, 'index'])->name('.list');
        Route::post('/all', [SupplierController::class, 'getAll']);
        Route::post('/store', [SupplierController::class, 'store']);
        Route::post('/number', [SupplierController::class, 'getNumber']);
        Route::post('/edit', [SupplierController::class, 'edit']);
        Route::post('/delete', [SupplierController::class, 'delete']);
        Route::post('/update-status', [SupplierController::class, 'updateStatus']);
    });

    Route::prefix('slider')->name('.slider')->group(function(){
        Route::get('/', [SliderController::class, 'index'])->name('.list');
        Route::post('/all', [SliderController::class, 'getAll']);
        Route::post('/store', [SliderController::class, 'store']);
        Route::post('/number', [SliderController::class, 'getNumber']);
        Route::post('/edit', [SliderController::class, 'edit']);
        Route::post('/delete', [SliderController::class, 'delete']);
        Route::post('/update-status', [SliderController::class, 'updateStatus']);
    });
});
