<?php

use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Ajax\BaseController as AjaxBaseController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ajax\LocationController as AjaxLocationController;
use App\Http\Controllers\Backend\AttributeCatalogueController;
use App\Http\Controllers\Backend\AttributeController;
use App\Http\Controllers\Backend\PostCatalogueController;
use App\Http\Controllers\Backend\PostController;
use App\Http\Controllers\Backend\CustomerCatalogueController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\SourceController;
use App\Http\Controllers\Backend\SlideController;
use App\Http\Controllers\Backend\PromotionController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Backend\WidgetController;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\CommentController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ProductCatalogueController;
use App\Http\Controllers\Backend\WarehouseController;
use App\Http\Controllers\Backend\SupplierController;
use App\Http\Controllers\Backend\PurchaseOrderController;
use App\Http\Controllers\Backend\ReceiveInventoryController;
use App\Http\Controllers\Backend\InventoryController;
use App\Http\Controllers\Backend\SystemController;
use App\Http\Controllers\Backend\UserCatalogueController;
use App\Http\Controllers\Frontend\RouterController;
use App\Http\Controllers\Frontend\AuthController as FrontendAuthController;
use App\Http\Controllers\Frontend\AccountController;
use App\Http\Controllers\Frontend\SocialAuthController;

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

//FRONTEND
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::middleware(['authCustomer', 'checkCustomerPublish'])->group(function() {
    Route::get('/thong-tin-tai-khoan.html', [AccountController::class, 'info'])->name('account.info');
    Route::post('/{id}/update/info', [CustomerController::class, 'updateInfo'])->name('update.info');
    Route::get('/quan-ly-don-hang.html', [AccountController::class, 'order'])->name('account.order');
    Route::get('/cap-nhat-mat-khau.html', [AccountController::class, 'repassword'])->name('account.repassword');
    Route::post('/{id}/update/password', [CustomerController::class, 'updatePassword'])->name('update.password');
});
Route::middleware(['guestCustomer'])->group(function() {
    Route::get('/dang-nhap.html', [FrontendAuthController::class, 'index'])->name('index');
    Route::post('/login', [FrontendAuthController::class, 'login'])->name('login');
    Route::get('/dang-ki.html', [FrontendAuthController::class, 'register'])->name('register');
    Route::post('/register', [FrontendAuthController::class, 'store'])->name('register.store');
    Route::get('auth/facebook', [SocialAuthController::class, 'redirectToFacebook']);
    Route::get('auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback']);
    Route::get('auth/google', [SocialAuthController::class, 'redirectToGoogle']);
    Route::get('auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);
    Route::get('/delete/account', [SocialAuthController::class, 'deleteAccount']);
    
});
Route::get('/logout', [FrontendAuthController::class, 'logout'])->name('logout');
Route::get('/{canonical}.html', [RouterController::class, 'index'])->name('router.index')->where(['canonical' => '[a-zA-Z0-9-]+']);
// Route::get('/{canonical}/trang-{page}', [RouterController::class, 'index'])->name('router.index')->where(['canonical' => '[a-zA-Z0-9-]+']);


//AJAX FRONTEND
Route::prefix('/ajax')->group(function () {
    Route::post('/view/more', [AjaxBaseController::class, 'viewMore'])->name('ajax.view.more');
    Route::post('/comment/store', [AjaxBaseController::class, 'createComment'])->name('ajax.comment.store');
    Route::get('/get/comment', [AjaxBaseController::class, 'getComment'])->name('ajax.get.comment');
    Route::post('/update/model', [AjaxBaseController::class, 'updateModel'])->name('ajax.update.model');
});

//BACKEND
Route::middleware(['guest'])->group(function() {
    Route::get('/admin/login', [AuthController::class, 'index'])->name('auth.index');
    Route::post('/admin/login', [AuthController::class, 'login'])->name('auth.login');
});

Route::prefix('/admin')->middleware(['auth', 'checkUserPublish'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    //USER
    Route::prefix('/user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::get('/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::post('/{id}/update', [UserController::class, 'update'])->name('user.update');
        Route::get('/{id}/delete', [UserController::class, 'delete'])->name('user.delete');
        Route::get('/{id}/destroy', [UserController::class, 'destroy'])->name('user.destroy');
    });

    //USER CATALOGUE
    Route::prefix('/userCatalogue')->group(function () {
        Route::get('/', [UserCatalogueController::class, 'index'])->name('userCatalogue.index');
        Route::get('/create', [UserCatalogueController::class, 'create'])->name('userCatalogue.create');
        Route::post('/store', [UserCatalogueController::class, 'store'])->name('userCatalogue.store');
        Route::get('/{id}/edit', [UserCatalogueController::class, 'edit'])->name('userCatalogue.edit');
        Route::post('/{id}/update', [UserCatalogueController::class, 'update'])->name('userCatalogue.update');
        Route::get('/{id}/delete', [UserCatalogueController::class, 'delete'])->name('userCatalogue.delete');
        Route::get('/{id}/destroy', [UserCatalogueController::class, 'destroy'])->name('userCatalogue.destroy');
        Route::get('/permission', [UserCatalogueController::class, 'permission'])->name('userCatalogue.permission');
        Route::post('/permissionUpdate', [UserCatalogueController::class, 'permissionUpdate'])->name('userCatalogue.permission.update');
    });

    //PERMISSION
    Route::prefix('/permission')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('permission.index');
        Route::get('/create', [PermissionController::class, 'create'])->name('permission.create');
        Route::post('/store', [PermissionController::class, 'store'])->name('permission.store');
        Route::get('/{id}/edit', [PermissionController::class, 'edit'])->name('permission.edit');
        Route::post('/{id}/update', [PermissionController::class, 'update'])->name('permission.update');
        Route::get('/{id}/delete', [PermissionController::class, 'delete'])->name('permission.delete');
        Route::get('/{id}/destroy', [PermissionController::class, 'destroy'])->name('permission.destroy');
    });

    //ATTRIBUTE
    Route::prefix('/attribute')->group(function () {
        Route::get('/', [AttributeController::class, 'index'])->name('attribute.index');
        Route::get('/create', [AttributeController::class, 'create'])->name('attribute.create');
        Route::post('/store', [AttributeController::class, 'store'])->name('attribute.store');
        Route::get('/{id}/edit', [AttributeController::class, 'edit'])->name('attribute.edit');
        Route::post('/{id}/update', [AttributeController::class, 'update'])->name('attribute.update');
        Route::get('/{id}/delete', [AttributeController::class, 'delete'])->name('attribute.delete');
        Route::get('/{id}/destroy', [AttributeController::class, 'destroy'])->name('attribute.destroy');
    });

    //ATTRIBUTE CATALOGUE
    Route::prefix('/attributeCatalogue')->group(function () {
        Route::get('/', [AttributeCatalogueController::class, 'index'])->name('attributeCatalogue.index');
        Route::get('/create', [AttributeCatalogueController::class, 'create'])->name('attributeCatalogue.create');
        Route::post('/store', [AttributeCatalogueController::class, 'store'])->name('attributeCatalogue.store');
        Route::get('/{id}/edit', [AttributeCatalogueController::class, 'edit'])->name('attributeCatalogue.edit');
        Route::post('/{id}/update', [AttributeCatalogueController::class, 'update'])->name('attributeCatalogue.update');
        Route::get('/{id}/delete', [AttributeCatalogueController::class, 'delete'])->name('attributeCatalogue.delete');
        Route::get('/{id}/destroy', [AttributeCatalogueController::class, 'destroy'])->name('attributeCatalogue.destroy');
    });

    //POST
    Route::prefix('/post')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('post.index');
        Route::get('/create', [PostController::class, 'create'])->name('post.create');
        Route::post('/store', [PostController::class, 'store'])->name('post.store');
        Route::get('/{id}/edit', [PostController::class, 'edit'])->name('post.edit');
        Route::post('/{id}/update', [PostController::class, 'update'])->name('post.update');
        Route::get('/{id}/delete', [PostController::class, 'delete'])->name('post.delete');
        Route::get('/{id}/destroy', [PostController::class, 'destroy'])->name('post.destroy');
    });

    //POST CATALOGUE
    Route::prefix('/postCatalogue')->group(function () {
        Route::get('/', [PostCatalogueController::class, 'index'])->name('postCatalogue.index');
        Route::get('/create', [PostCatalogueController::class, 'create'])->name('postCatalogue.create');
        Route::post('/store', [PostCatalogueController::class, 'store'])->name('postCatalogue.store');
        Route::get('/{id}/edit', [PostCatalogueController::class, 'edit'])->name('postCatalogue.edit');
        Route::post('/{id}/update', [PostCatalogueController::class, 'update'])->name('postCatalogue.update');
        Route::get('/{id}/delete', [PostCatalogueController::class, 'delete'])->name('postCatalogue.delete');
        Route::get('/{id}/destroy', [PostCatalogueController::class, 'destroy'])->name('postCatalogue.destroy');
    });

    //CUSTOMER
    Route::prefix('/customer')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customer.index');
        Route::get('/create', [CustomerController::class, 'create'])->name('customer.create');
        Route::post('/store', [CustomerController::class, 'store'])->name('customer.store');
        Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
        Route::post('/{id}/update', [CustomerController::class, 'update'])->name('customer.update');
        Route::get('/{id}/delete', [CustomerController::class, 'delete'])->name('customer.delete');
        Route::get('/{id}/destroy', [CustomerController::class, 'destroy'])->name('customer.destroy');
    });

    //CUSTOMER CATALOGUE
    Route::prefix('/customerCatalogue')->group(function () {
        Route::get('/', [CustomerCatalogueController::class, 'index'])->name('customerCatalogue.index');
        Route::get('/create', [CustomerCatalogueController::class, 'create'])->name('customerCatalogue.create');
        Route::post('/store', [CustomerCatalogueController::class, 'store'])->name('customerCatalogue.store');
        Route::get('/{id}/edit', [CustomerCatalogueController::class, 'edit'])->name('customerCatalogue.edit');
        Route::post('/{id}/update', [CustomerCatalogueController::class, 'update'])->name('customerCatalogue.update');
        Route::get('/{id}/delete', [CustomerCatalogueController::class, 'delete'])->name('customerCatalogue.delete');
        Route::get('/{id}/destroy', [CustomerCatalogueController::class, 'destroy'])->name('customerCatalogue.destroy');
    });

    //SOURCE
    Route::prefix('/source')->group(function () {
        Route::get('/', [SourceController::class, 'index'])->name('source.index');
        Route::get('/create', [SourceController::class, 'create'])->name('source.create');
        Route::post('/store', [SourceController::class, 'store'])->name('source.store');
        Route::get('/{id}/edit', [SourceController::class, 'edit'])->name('source.edit');
        Route::post('/{id}/update', [SourceController::class, 'update'])->name('source.update');
        Route::get('/{id}/delete', [SourceController::class, 'delete'])->name('source.delete');
        Route::get('/{id}/destroy', [SourceController::class, 'destroy'])->name('source.destroy');
    });
    
    //PRODUCT
    Route::prefix('/product')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('product.index');
        Route::get('/create', [ProductController::class, 'create'])->name('product.create');
        Route::post('/store', [ProductController::class, 'store'])->name('product.store');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
        Route::post('/{id}/update', [ProductController::class, 'update'])->name('product.update');
        Route::get('/{id}/delete', [ProductController::class, 'delete'])->name('product.delete');
        Route::get('/{id}/destroy', [ProductController::class, 'destroy'])->name('product.destroy');
    });

    //PRODUCT CATALOGUE
    Route::prefix('/productCatalogue')->group(function () {
        Route::get('/', [ProductCatalogueController::class, 'index'])->name('productCatalogue.index');
        Route::get('/create', [ProductCatalogueController::class, 'create'])->name('productCatalogue.create');
        Route::post('/store', [ProductCatalogueController::class, 'store'])->name('productCatalogue.store');
        Route::get('/{id}/edit', [ProductCatalogueController::class, 'edit'])->name('productCatalogue.edit');
        Route::post('/{id}/update', [ProductCatalogueController::class, 'update'])->name('productCatalogue.update');
        Route::get('/{id}/delete', [ProductCatalogueController::class, 'delete'])->name('productCatalogue.delete');
        Route::get('/{id}/destroy', [ProductCatalogueController::class, 'destroy'])->name('productCatalogue.destroy');
    });

    //WAREHOUSE
    Route::prefix('/warehouse')->group(function () {
        Route::get('/', [WarehouseController::class, 'index'])->name('warehouse.index');
        Route::get('/create', [WarehouseController::class, 'create'])->name('warehouse.create');
        Route::post('/store', [WarehouseController::class, 'store'])->name('warehouse.store');
        Route::get('/{id}/edit', [WarehouseController::class, 'edit'])->name('warehouse.edit');
        Route::post('/{id}/update', [WarehouseController::class, 'update'])->name('warehouse.update');
        Route::get('/{id}/delete', [WarehouseController::class, 'delete'])->name('warehouse.delete');
        Route::get('/{id}/destroy', [WarehouseController::class, 'destroy'])->name('warehouse.destroy');
    });

    //SUPPLIER
    Route::prefix('/supplier')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('supplier.index');
        Route::get('/create', [SupplierController::class, 'create'])->name('supplier.create');
        Route::post('/store', [SupplierController::class, 'store'])->name('supplier.store');
        Route::get('/{id}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
        Route::post('/{id}/update', [SupplierController::class, 'update'])->name('supplier.update');
        Route::get('/{id}/delete', [SupplierController::class, 'delete'])->name('supplier.delete');
        Route::get('/{id}/destroy', [SupplierController::class, 'destroy'])->name('supplier.destroy');
    });

    //INVENTORY
    Route::prefix('/inventory')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
    });

    //PURCHASE ORDER
    Route::prefix('/purchaseOrder')->group(function () {
        Route::get('/', [PurchaseOrderController::class, 'index'])->name('purchaseOrder.index');
        Route::get('/create', [PurchaseOrderController::class, 'create'])->name('purchaseOrder.create');
        Route::post('/store', [PurchaseOrderController::class, 'store'])->name('purchaseOrder.store');
        Route::get('/{id}/edit', [PurchaseOrderController::class, 'edit'])->name('purchaseOrder.edit');
        Route::post('/{id}/update', [PurchaseOrderController::class, 'update'])->name('purchaseOrder.update');
        Route::get('/{id}/confirm', [PurchaseOrderController::class, 'confirm'])->name('purchaseOrder.confirm');
        Route::get('/{id}/cancel', [PurchaseOrderController::class, 'cancel'])->name('purchaseOrder.cancel');
    });

    //RECEIVE INVENTORY
    Route::prefix('/receiveInventory')->group(function () {
        Route::get('/', [ReceiveInventoryController::class, 'index'])->name('receiveInventory.index');
        Route::get('/create', [ReceiveInventoryController::class, 'create'])->name('receiveInventory.create');
        Route::post('/store', [ReceiveInventoryController::class, 'store'])->name('receiveInventory.store');
        Route::get('/{id}/edit', [ReceiveInventoryController::class, 'edit'])->name('receiveInventory.edit');
        Route::post('/{id}/update', [ReceiveInventoryController::class, 'update'])->name('receiveInventory.update');
        Route::get('/{id}/received', [ReceiveInventoryController::class, 'received'])->name('receiveInventory.received');
        Route::get('/{id}/paid', [ReceiveInventoryController::class, 'paid'])->name('receiveInventory.paid');
    });

    //PROMOTION
    Route::prefix('/promotion')->group(function () {
        Route::get('/', [PromotionController::class, 'index'])->name('promotion.index');
        Route::get('/create', [PromotionController::class, 'create'])->name('promotion.create');
        Route::post('/store', [PromotionController::class, 'store'])->name('promotion.store');
        Route::get('/{id}/edit', [PromotionController::class, 'edit'])->name('promotion.edit');
        Route::post('/{id}/update', [PromotionController::class, 'update'])->name('promotion.update');
        Route::get('/{id}/delete', [PromotionController::class, 'delete'])->name('promotion.delete');
        Route::get('/{id}/destroy', [PromotionController::class, 'destroy'])->name('promotion.destroy');
    });

    //COUPON
    Route::prefix('/coupon')->group(function () {
        Route::get('/', [CouponController::class, 'index'])->name('coupon.index');
        Route::get('/create', [CouponController::class, 'create'])->name('coupon.create');
        Route::post('/store', [CouponController::class, 'store'])->name('coupon.store');
        Route::get('/{id}/edit', [CouponController::class, 'edit'])->name('coupon.edit');
        Route::post('/{id}/update', [CouponController::class, 'update'])->name('coupon.update');
        Route::get('/{id}/delete', [CouponController::class, 'delete'])->name('coupon.delete');
        Route::get('/{id}/destroy', [CouponController::class, 'destroy'])->name('coupon.destroy');
    });

    //SLIDE
    Route::prefix('/slide')->group(function () {
        Route::get('/', [SlideController::class, 'index'])->name('slide.index');
        Route::get('/create', [SlideController::class, 'create'])->name('slide.create');
        Route::post('/store', [SlideController::class, 'store'])->name('slide.store');
        Route::get('/{id}/edit', [SlideController::class, 'edit'])->name('slide.edit');
        Route::post('/{id}/update', [SlideController::class, 'update'])->name('slide.update');
        Route::get('/{id}/delete', [SlideController::class, 'delete'])->name('slide.delete');
        Route::get('/{id}/destroy', [SlideController::class, 'destroy'])->name('slide.destroy');
    });

    //WIDGET
    Route::prefix('/widget')->group(function () {
        Route::get('/', [WidgetController::class, 'index'])->name('widget.index');
        Route::get('/create', [WidgetController::class, 'create'])->name('widget.create');
        Route::post('/store', [WidgetController::class, 'store'])->name('widget.store');
        Route::get('/{id}/edit', [WidgetController::class, 'edit'])->name('widget.edit');
        Route::post('/{id}/update', [WidgetController::class, 'update'])->name('widget.update');
        Route::get('/{id}/delete', [WidgetController::class, 'delete'])->name('widget.delete');
        Route::get('/{id}/destroy', [WidgetController::class, 'destroy'])->name('widget.destroy');
    });

    //MENU
    Route::prefix('/menu')->group(function () {
        Route::get('/', [MenuController::class, 'index'])->name('menu.index');
        Route::get('/create', [MenuController::class, 'create'])->name('menu.create');
        Route::post('/store', [MenuController::class, 'store'])->name('menu.store');
        Route::get('/{id}/edit', [MenuController::class, 'edit'])->name('menu.edit');
        Route::post('/{id}/update', [MenuController::class, 'update'])->name('menu.update');
        Route::get('/{id}/delete', [MenuController::class, 'delete'])->name('menu.delete');
        Route::get('/{id}/destroy', [MenuController::class, 'destroy'])->name('menu.destroy');
    });

    //COMMENT
    Route::prefix('/comment')->group(function () {
        Route::get('/', [CommentController::class, 'index'])->name('comment.index');
        Route::get('/create', [CommentController::class, 'create'])->name('comment.create');
        Route::get('/{id}/edit', [CommentController::class, 'edit'])->name('comment.edit');
        Route::post('/{id}/update', [CommentController::class, 'update'])->name('comment.update');
        Route::get('/{id}/delete', [CommentController::class, 'delete'])->name('comment.delete');
        Route::get('/{id}/destroy', [CommentController::class, 'destroy'])->name('comment.destroy');
    });

    //SYSTEM
    Route::prefix('/system')->group(function () {
        Route::get('/', [SystemController::class, 'index'])->name('system.index');
        Route::post('/store', [SystemController::class, 'store'])->name('system.store');
        Route::get('/theme', [SystemController::class, 'theme'])->name('system.theme');
        Route::post('/theme/editor', [SystemController::class, 'editor'])->name('system.theme.editor');
    }); 

    //AJAX
    Route::prefix('/ajax')->group(function () {
        Route::post('/change/publish', [AjaxBaseController::class, 'changePublish'])->name('ajax.change.publish');
        Route::post('/change/status', [AjaxBaseController::class, 'changeStatus'])->name('ajax.change.status');
        Route::post('/call/method/service', [AjaxBaseController::class, 'callMethodService'])->name('ajax.call.method.service');
        Route::post('/change/supervisor', [AjaxBaseController::class, 'changeSupervisor'])->name('ajax.change.supervisor');
        Route::post('/change/multiple/publish', [AjaxBaseController::class, 'changeMultiplePublish'])->name('ajax.change.multiple.publish');
        Route::get('/change/location', [AjaxLocationController::class, 'changeLocation'])->name('ajax.change.location');
        Route::post('/search/model', [AjaxBaseController::class, 'searchModel'])->name('ajax.search.model');
        Route::post('/data/model', [AjaxBaseController::class, 'getDataModel'])->name('ajax.data.model');
        Route::post('/data/productByWarehouse', [AjaxBaseController::class, 'getDataProductByWarehouse'])->name('ajax.data.productByWarehouse');
        Route::post('/create/model', [AjaxBaseController::class, 'createModel'])->name('ajax.create.model');
        Route::post('/get/relationById', [AjaxBaseController::class, 'getRelationById'])->name('ajax.get.relationById');
    }); 
}); 
