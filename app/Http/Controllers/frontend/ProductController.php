<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\WidgetRepositoryInterface as WidgetRepository;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface as AttributeCatalogueRepository;
use App\Repositories\Interfaces\WarehouseRepositoryInterface as WarehouseRepository;
use App\Repositories\Interfaces\PromotionRepositoryInterface as PromotionRepository;
use App\Services\Interfaces\ProductServiceInterface as ProductService;
use App\Repositories\Interfaces\CommentRepositoryInterface as CommentRepository;

class ProductController extends Controller
{
    //

    protected $widgetRepository;
    protected $productCatalogueRepository;
    protected $productRepository;
    protected $attributeCatalogueRepository;
    protected $warehouseRepository;
    protected $promotionRepository;
    protected $productService;
    protected $commentRepository;

    public function __construct(
        WidgetRepository $widgetRepository,
        ProductCatalogueRepository $productCatalogueRepository,
        ProductRepository $productRepository,
        AttributeCatalogueRepository $attributeCatalogueRepository,
        WarehouseRepository $warehouseRepository,
        PromotionRepository $promotionRepository,
        ProductService $productService,
        CommentRepository $commentRepository
    )
    {
        $this->widgetRepository = $widgetRepository;
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->productRepository = $productRepository;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
        $this->warehouseRepository = $warehouseRepository;
        $this->promotionRepository = $promotionRepository;
        $this->productService = $productService;
        $this->commentRepository = $commentRepository;
    }

    public function index($id, Request $request, $page = 1) {
        $payload = $request->query();
        $config = $this->config();
        $product = $this->productRepository->findByIdAndMultipleRelation($id, ['product_catalogues.promotions', 'product_variants.attributes', 'warehouses', 'posts', 'albums', 'promotions']);
        $productCatalogue = $this->productCatalogueRepository->findById($product->product_catalogue_id);
        $productCatalogues = $productCatalogue->ancestors()->get()->push($productCatalogue)->toTree()->first();
        $product = $this->productService->setupDataProduct($product);
        $warehouses = $this->warehouseRepository->findByCondition([
            __('config.conditionPublish')
        ], ['id', 'ASC']);
        $seo = [
            'meta_title' => $product->meta_title,
            'meta_description' => $product->meta_description,
            'meta_keyword' => $product->meta_keyword,
            'canonical' => '/' . $product->canonical,
        ];
        $limit = __('config')['productCommentPerpage'];
        $comments = $this->commentRepository->findByCondition($this->conditionGetComments($id), ['id', 'DESC'], '', [], '', [], ['customers'], $limit);
        $ratingSummary = $this->commentRepository->getRatingSummary($this->conditionGetComments($id))->toArray();
        $ratingCount = $this->commentRepository->getRatingCount($this->conditionGetComments($id))->toArray();
        return view('frontend.product.index', compact(
            'config',
            'seo',
            'product',
            'productCatalogues',
            'warehouses',
            'comments',
            'ratingSummary',
            'ratingCount'
        ));
    }

    private function conditionGetComments($id) {
        return [
            __('config.conditionPublish'),
            ['model', '=', 'product'],
            ['model_id', '=', $id]
        ];
    }

    private function config () {
        return [
            'js' => [
                "frontend/plugins/rateyo/jquery.rateyo.min.js",
                '/backend/js/plugins/toastr/toastr.min.js',
                "frontend/js/library/product.js",
                "frontend/js/library/comment.js",
                "frontend/js/library/cart.js"
            ],
            'css' => [
                "frontend/plugins/rateyo/rateyo.min.css",
                '/backend/css/plugins/toastr/toastr.min.css',
                "frontend/css/product.css"
            ], 
            'module' => 'product',
        ];
    }
}