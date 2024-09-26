<?php

namespace App\Providers;

use App\Models\System;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use App\Repositories\Interfaces\MenuRepositoryInterface as MenuRepository;
use App\Repositories\Interfaces\WidgetRepositoryInterface as WidgetRepository;
use App\Services\Interfaces\WidgetServiceInterface as WidgetService;

class AppServiceProvider extends ServiceProvider
{
    protected $productCatalogueRepository;
    protected $menuRepository;
    protected $widgetRepository;
    protected $widgetService;
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->registerRepositories();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(
        ProductCatalogueRepository $productCatalogueRepository,
        MenuRepository $menuRepository,
        WidgetRepository $widgetRepository,
        WidgetService $widgetService
    ){
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->menuRepository = $menuRepository;
        $this->widgetRepository = $widgetRepository;
        $this->widgetService = $widgetService;
        View::composer('frontend.*', function ($view) {
            $temp = System::get();
            foreach($temp as $key => $val) {
                $system[$val['keyword']] = $val['content'];
            }
            $generalSetting = json_decode($system['general_setting'], true);
            $header = json_decode($system['header'], true);
            $footer = json_decode($system['footer'], true);
            $categories = $this->productCatalogueRepository->findByCondition([
                __('config.conditionPublish')
            ], ['_lft', 'ASC'], 'level', [0, 1]);
            $menus = $this->menuRepository->findByCondition([
                __('config.conditionPublish')
            ], [], '', [], '', [], ['links']);
            $widgets = $this->widgetRepository->findByCondition([
                __('config.conditionPublish')
            ]);
            $dataByWidgets = $this->widgetService->getDataByWidgets($widgets);
            $view->with([
                'system' => $system,
                'generalSetting' => $generalSetting,
                'header' => $header,
                'footer' => $footer,
                'categories' => $categories,
                'menus' => $menus,
                'widgets' => $widgets, 
                'dataByWidgets' => $dataByWidgets   
            ]);
        });
    }

    private function registerRepositories () {
        $bindings = [   
            'App\Repositories\Interfaces\BaseRepositoryInterface' => 'App\Repositories\BaseRepository',

            'App\Repositories\Interfaces\RouterRepositoryInterface' => 'App\Repositories\RouterRepository',

            'App\Repositories\Interfaces\ProductPurchaseOrderRepositoryInterface' => 'App\Repositories\ProductPurchaseOrderRepository',

            'App\Repositories\Interfaces\HistoryRepositoryInterface' => 'App\Repositories\HistoryRepository',

            'App\Repositories\Interfaces\PaymentRecordRepositoryInterface' => 'App\Repositories\PaymentRecordRepository',

            'App\Repositories\Interfaces\ProductWarehouseRepositoryInterface' => 'App\Repositories\ProductWarehouseRepository',

            'App\Repositories\Interfaces\InventoryRepositoryInterface' => 'App\Repositories\InventoryRepository',
            'App\Services\Interfaces\InventoryServiceInterface' => 'App\Services\InventoryService',

            'App\Repositories\Interfaces\ReceiveInventoryRepositoryInterface' => 'App\Repositories\ReceiveInventoryRepository',
            'App\Services\Interfaces\ReceiveInventoryServiceInterface' => 'App\Services\ReceiveInventoryService',

            'App\Repositories\Interfaces\PromotionRepositoryInterface' => 'App\Repositories\PromotionRepository',
            'App\Services\Interfaces\PromotionServiceInterface' => 'App\Services\PromotionService',

            'App\Repositories\Interfaces\CommentRepositoryInterface' => 'App\Repositories\CommentRepository',
            'App\Services\Interfaces\CommentServiceInterface' => 'App\Services\CommentService',

            'App\Repositories\Interfaces\CouponRepositoryInterface' => 'App\Repositories\CouponRepository',
            'App\Services\Interfaces\CouponServiceInterface' => 'App\Services\CouponService',

            'App\Repositories\Interfaces\PurchaseOrderRepositoryInterface' => 'App\Repositories\PurchaseOrderRepository',
            'App\Services\Interfaces\PurchaseOrderServiceInterface' => 'App\Services\PurchaseOrderService',

            'App\Repositories\Interfaces\SupplierRepositoryInterface' => 'App\Repositories\SupplierRepository',
            'App\Services\Interfaces\SupplierServiceInterface' => 'App\Services\SupplierService',

            'App\Repositories\Interfaces\AlbumRepositoryInterface' => 'App\Repositories\AlbumRepository',

            'App\Repositories\Interfaces\ProductVariantAttributeRepositoryInterface' => 'App\Repositories\ProductVariantAttributeRepository',

            'App\Repositories\Interfaces\ProductVariantRepositoryInterface' => 'App\Repositories\ProductVariantRepository',

            'App\Repositories\Interfaces\AttributeCatalogueAttributeRepositoryInterface' => 'App\Repositories\AttributeCatalogueAttributeRepository',

            'App\Repositories\Interfaces\TaxRepositoryInterface' => 'App\Repositories\TaxRepository',

            'App\Repositories\Interfaces\PostCataloguePostRepositoryInterface' => 'App\Repositories\PostCataloguePostRepository',

            'App\Repositories\Interfaces\ProductCatalogueProductRepositoryInterface' => 'App\Repositories\ProductCatalogueProductRepository',

            'App\Services\Interfaces\UserServiceInterface' => 'App\Services\UserService',
            'App\Repositories\Interfaces\UserRepositoryInterface' => 'App\Repositories\UserRepository',

            'App\Services\Interfaces\SystemServiceInterface' => 'App\Services\SystemService',
            'App\Repositories\Interfaces\SystemRepositoryInterface' => 'App\Repositories\SystemRepository',

            'App\Services\Interfaces\WidgetServiceInterface' => 'App\Services\WidgetService',
            'App\Repositories\Interfaces\WidgetRepositoryInterface' => 'App\Repositories\WidgetRepository',

            'App\Services\Interfaces\CustomerServiceInterface' => 'App\Services\CustomerService',
            'App\Repositories\Interfaces\CustomerRepositoryInterface' => 'App\Repositories\CustomerRepository',

            'App\Services\Interfaces\MenuServiceInterface' => 'App\Services\MenuService',
            'App\Repositories\Interfaces\MenuRepositoryInterface' => 'App\Repositories\MenuRepository',

            'App\Services\Interfaces\CustomerCatalogueServiceInterface' => 'App\Services\CustomerCatalogueService',
            'App\Repositories\Interfaces\CustomerCatalogueRepositoryInterface' => 'App\Repositories\CustomerCatalogueRepository',

            'App\Services\Interfaces\SourceServiceInterface' => 'App\Services\SourceService',
            'App\Repositories\Interfaces\SourceRepositoryInterface' => 'App\Repositories\SourceRepository',

            'App\Services\Interfaces\SlideServiceInterface' => 'App\Services\SlideService',
            'App\Repositories\Interfaces\SlideRepositoryInterface' => 'App\Repositories\SlideRepository',

            'App\Services\Interfaces\UserCatalogueServiceInterface' => 'App\Services\UserCatalogueService',
            'App\Repositories\Interfaces\UserCatalogueRepositoryInterface' => 'App\Repositories\UserCatalogueRepository',

            'App\Services\Interfaces\AttributeServiceInterface' => 'App\Services\AttributeService',
            'App\Repositories\Interfaces\AttributeRepositoryInterface' => 'App\Repositories\AttributeRepository',

            'App\Services\Interfaces\AttributeCatalogueServiceInterface' => 'App\Services\AttributeCatalogueService',
            'App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface' => 'App\Repositories\AttributeCatalogueRepository',

            'App\Services\Interfaces\PostCatalogueServiceInterface' => 'App\Services\PostCatalogueService',
            'App\Repositories\Interfaces\PostCatalogueRepositoryInterface' => 'App\Repositories\PostCatalogueRepository',

            'App\Services\Interfaces\PostServiceInterface' => 'App\Services\PostService',
            'App\Repositories\Interfaces\PostRepositoryInterface' => 'App\Repositories\PostRepository',

            'App\Services\Interfaces\WarehouseServiceInterface' => 'App\Services\WarehouseService',
            'App\Repositories\Interfaces\WarehouseRepositoryInterface' => 'App\Repositories\WarehouseRepository',

            'App\Services\Interfaces\ProductServiceInterface' => 'App\Services\ProductService',
            'App\Repositories\Interfaces\ProductRepositoryInterface' => 'App\Repositories\ProductRepository',

            'App\Services\Interfaces\ProductCatalogueServiceInterface' => 'App\Services\ProductCatalogueService',
            'App\Repositories\Interfaces\ProductCatalogueRepositoryInterface' => 'App\Repositories\ProductCatalogueRepository',

            'App\Repositories\Interfaces\ProvinceRepositoryInterface' => 'App\Repositories\ProvinceRepository',
            'App\Repositories\Interfaces\DistrictRepositoryInterface' => 'App\Repositories\DistrictRepository',
            'App\Repositories\Interfaces\WardRepositoryInterface' => 'App\Repositories\WardRepository',

            'App\Services\Interfaces\PermissionServiceInterface' => 'App\Services\PermissionService',
            'App\Repositories\Interfaces\PermissionRepositoryInterface' => 'App\Repositories\PermissionRepository',
        ];
        foreach($bindings as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }
}
