<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;

class ProductCatalogueController extends Controller
{
    //

    protected $productCatalogueRepository;
    protected $productRepository;

    public function __construct(
        ProductCatalogueRepository $productCatalogueRepository,
        ProductRepository $productRepository
    )
    {
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->productRepository = $productRepository;
    }

    public function index($id, Request $request, $page = 1) {
        $payload = $request->query();
        $config = $this->config();

        $productCatalogue = $this->productCatalogueRepository->findById($id);
        $productCatalogues = $productCatalogue->ancestors()->get()->toTree()->first();

        // dd($productCatalogue);
        $seo = [
            'meta_title' => $productCatalogue->meta_title,
            'meta_description' => $productCatalogue->meta_description,
            'meta_keyword' => $productCatalogue->meta_keyword,
            'canonical' => '/' . $productCatalogue->canonical,
        ];
        return view('frontend.productCatalogue.index', compact(
            'config',
            'seo',
            'productCatalogues',
            'productCatalogue'
        ));
    }


    private function config () {
        return [
            'js' => [],
            'css' => [
                "frontend/css/search.css",
            ], 
        ];
    }
}