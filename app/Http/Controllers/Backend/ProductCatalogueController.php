<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductCatalogueRequest;
use App\Http\Requests\UpdateProductCatalogueRequest;
use Illuminate\Http\Request;
use App\Services\Interfaces\ProductCatalogueServiceInterface as ProductCatalogueService;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use Illuminate\Support\Facades\Gate;

class ProductCatalogueController extends Controller
{
    //

    protected $productCatalogueService;
    protected $productCatalogueRepository;

    public function __construct(
        ProductCatalogueService $productCatalogueService,
        ProductCatalogueRepository $productCatalogueRepository
    )
    {
        $this->productCatalogueService = $productCatalogueService;
        $this->productCatalogueRepository = $productCatalogueRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'productCatalogue.index')) {
            $payload = $request->query();
            $productCatalogues = customizeNestedset($this->productCatalogueService->paginate($payload));  
            // dd($productCatalogues);
            $config = $this->config();
            $config['method'] = 'index';
            return view('backend.product.productCatalogue.index', compact(
                'config',
                'productCatalogues',
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function create() {
        if(Gate::allows('modules', 'productCatalogue.create')) {
            $config = $this->config();
            $config['method'] = 'create';
            $config['js'][] = '/backend/js/library/seo.js';
            $productCatalogues = customizeNestedset($this->productCatalogueRepository->findByCondition([], ['_lft', 'ASC']));
            return view('backend.product.productCatalogue.store', compact(
                'config',
                'productCatalogues'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function store(StoreProductCatalogueRequest $request) {
        $flag = $this->productCatalogueService->store($request->except('_token', 'title', 'width', 'height', 'link'));
        if($flag) {
            return redirect()->route('productCatalogue.index')->with('success', 'Thêm mới bản ghi thành công');
        }else{
            return redirect()->route('productCatalogue.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function edit($id) {
        if(Gate::allows('modules', 'productCatalogue.edit')) {
            $config = $this->config();
            $config['method'] = 'edit';
            $config['js'][] = '/backend/js/library/seo.js';
            $productCatalogue = $this->productCatalogueRepository->findById($id, "products");
            $productCatalogues = customizeNestedset($this->productCatalogueRepository->findByCondition([
                ['id', '<>', $id]
            ], ['_lft', 'ASC']));
            return view('backend.product.productCatalogue.store', compact(
                'config',
                'productCatalogue',
                'productCatalogues',
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    } 

    public function update (UpdateProductCatalogueRequest $request, $id) {
        $payload = $request->except('_token', 'title', 'width', 'height', 'link');
        $flag = $this->productCatalogueService->update($id, $payload);
        if($flag) {
            return redirect()->route('productCatalogue.index')->with('success', 'Cập nhật bản ghi thành công');
        }else{
            return redirect()->route('productCatalogue.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function delete ($id) {
        if(Gate::allows('modules', 'productCatalogue.delete')) {
            $config = $this->config();
            $config['method'] = 'delete';
            $productCatalogue = $this->productCatalogueRepository->findById($id);
            return view('backend.product.productCatalogue.delete', compact(
                'config',
                'productCatalogue'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function destroy ($id) {
        $flag = $this->productCatalogueService->destroy($id);
        if($flag) {
            return redirect()->route('productCatalogue.index')->with('success', 'Xóa bản ghi thành công');
        }else{
            return redirect()->route('productCatalogue.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại!');
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
            'module' => 'productCatalogue',
        ];
    }
}
