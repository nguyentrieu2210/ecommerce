<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use App\Services\Interfaces\ProductServiceInterface as ProductService;
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;
use Illuminate\Support\Facades\Gate;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use App\Repositories\Interfaces\WarehouseRepositoryInterface as WarehouseRepository;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface as AttributeCatalogueRepository;
use App\Repositories\Interfaces\TaxRepositoryInterface as TaxRepository;

class ProductController extends Controller
{
    //

    protected $productService;
    protected $productRepository;
    protected $productCatalogueRepository;
    protected $warehouseRepository;
    protected $attributeCatalogueRepository;
    protected $taxRepository;

    public function __construct(
        ProductService $productService,
        ProductCatalogueRepository $productCatalogueRepository,
        ProductRepository $productRepository,
        WarehouseRepository $warehouseRepository,
        AttributeCatalogueRepository $attributeCatalogueRepository,
        TaxRepository $taxRepository
    )
    {
        $this->productService = $productService;
        $this->productRepository = $productRepository;
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->warehouseRepository = $warehouseRepository;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
        $this->taxRepository = $taxRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'product.index')) {
            $payload = $request->query();
            $products = $this->productService->paginate($payload);  
            // dd($products);
            $config = $this->config();
            $config['method'] = 'index';
            $productCatalogues = customizeNestedset($this->productCatalogueRepository->findByCondition([], ['_lft', 'ASC']));
            return view('backend.product.product.index', compact(
                'config',
                'products',
                'productCatalogues'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function create() {
        if(Gate::allows('modules', 'product.create')) {
            $config = $this->config();
            $config['method'] = 'create';
            $config['js'][] = '/backend/js/library/seo.js';
            $config['js'][] = '/backend/js/library/product.js';
            $productCatalogues = customizeNestedset($this->productCatalogueRepository->findByCondition([['publish', '=', 2]], ['_lft', 'ASC']));
            $warehouses = $this->warehouseRepository->findByCondition([
                ['publish', '=', 2]
            ]);
            $attributeCatalogues = $this->attributeCatalogueRepository->findByCondition([
                ['publish', '=', 2],
            ]);
            $taxes = $this->taxRepository->getAll();
            return view('backend.product.product.store', compact(
                'config',
                'productCatalogues',
                'warehouses',
                'attributeCatalogues',
                'taxes'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function store(StoreProductRequest $request) {
        $flag = $this->productService->store($request->except('_token', 'title', 'width', 'height', 'link', 'data_attribute', 'status_tax', 'model_id', 'post_name', 'post_image'));
        if($flag) {
            return redirect()->route('product.index')->with('success', 'Thêm mới bản ghi thành công');
        }else{
            return redirect()->route('product.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function edit($id) {
        if(Gate::allows('modules', 'product.edit')) {
            $config = $this->config();
            $config['method'] = 'edit';
            $config['js'][] = '/backend/js/library/seo.js';
            $config['js'][] = '/backend/js/library/product.js';
            $product = $this->productRepository->findByIdAndMultipleRelation($id, ["product_catalogues", "product_variants", 'albums', 'posts', 'warehouses']);
            $warehouses = $this->warehouseRepository->findByCondition([
                ['publish', '=', 2]
            ]);
            $taxes = $this->taxRepository->getAll();
            $attributeCatalogues = $this->attributeCatalogueRepository->findByCondition([
                ['publish', '=', 2],
            ]);
            // dd($product->albums);
            $productCatalogues = customizeNestedset($this->productCatalogueRepository->findByCondition([], ['_lft', 'ASC']));
            return view('backend.product.product.store', compact(
                'config',
                'product',
                'warehouses',
                'productCatalogues',
                'taxes',
                'attributeCatalogues'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    } 

    public function update (UpdateProductRequest $request, $id) {
        $payload = $request->except('_token', 'title', 'width', 'height', 'link', 'data_attribute', 'status_tax', 'model_id', 'post_name', 'post_image');
        $flag = $this->productService->update($id, $payload);
        if($flag) {
            return redirect()->route('product.index')->with('success', 'Cập nhật bản ghi thành công');
        }else{
            return redirect()->route('product.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function delete ($id) {
        if(Gate::allows('modules', 'product.delete')) {
            $config = $this->config();
            $config['method'] = 'delete';
            $product = $this->productRepository->findById($id);
            return view('backend.product.product.delete', compact(
                'config',
                'product'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function destroy ($id) {
        $flag = $this->productService->destroy($id);
        if($flag) {
            return redirect()->route('product.index')->with('success', 'Xóa bản ghi thành công');
        }else{
            return redirect()->route('product.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại!');
        }
    }

    private function config () {
        return [
            'js' => [
                '/backend/js/plugins/switchery/switchery.js',
                '/backend/js/plugins/toastr/toastr.min.js',
                '/backend/js/plugins/datapicker/bootstrap-datepicker.js',
                '/backend/plugins/ckfinder_2/ckfinder.js',
                '/backend/plugins/ckeditor/ckeditor.js',
                '/backend/js/plugins/blueimp/jquery.blueimp-gallery.min.js',
                '/backend/js/library/library.js',
                '/backend/js/library/album.js',
            ],
            'css' => [
                '/backend/css/plugins/switchery/switchery.css',
                '/backend/css/plugins/toastr/toastr.min.css',
                '/backend/css/plugins/blueimp/css/blueimp-gallery.min.css',
            ], 
            'module' => 'product',
        ];
    }
}
