<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;
use Illuminate\Http\Request;
use App\Services\Interfaces\AttributeServiceInterface as AttributeService;
use App\Repositories\Interfaces\AttributeRepositoryInterface as AttributeRepository;
use Illuminate\Support\Facades\Gate;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface as AttributeCatalogueRepository;

class AttributeController extends Controller
{
    //

    protected $attributeService;
    protected $attributeRepository;
    protected $attributeCatalogueRepository;

    public function __construct(
        AttributeService $attributeService,
        AttributeCatalogueRepository $attributeCatalogueRepository,
        AttributeRepository $attributeRepository
    )
    {
        $this->attributeService = $attributeService;
        $this->attributeRepository = $attributeRepository;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'attribute.index')) {
            $payload = $request->query();
            $attributes = $this->attributeService->paginate($payload);  
            // dd($attributes); 
            $config = $this->config();
            $config['method'] = 'index';
            $attributeCatalogues = customizeNestedset($this->attributeCatalogueRepository->findByCondition([], ['_lft', 'ASC']));
            return view('backend.attribute.attribute.index', compact(
                'config',
                'attributes',
                'attributeCatalogues'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function create() {
        if(Gate::allows('modules', 'attribute.create')) {
            $config = $this->config();
            $config['method'] = 'create';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/seo.js';
            $attributeCatalogues = customizeNestedset($this->attributeCatalogueRepository->findByCondition([], ['_lft', 'ASC']));
            return view('backend.attribute.attribute.store', compact(
                'config',
                'attributeCatalogues'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function store(StoreAttributeRequest $request) {
        $flag = $this->attributeService->store($request->except('_token', 'canonical_original'));
        if($flag) {
            return redirect()->route('attribute.index')->with('success', 'Thêm mới bản ghi thành công');
        }else{
            return redirect()->route('attribute.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function edit($id) {
        if(Gate::allows('modules', 'attribute.edit')) {
            $config = $this->config();
            $config['method'] = 'edit';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/seo.js';
            $attribute = $this->attributeRepository->findById($id, "attribute_catalogues");
            // $attributeCatalogueIds = $attribute->attribute_catalogues->pluck('id')->toArray();
            $attributeCatalogues = customizeNestedset($this->attributeCatalogueRepository->findByCondition([], ['_lft', 'ASC']));
            return view('backend.attribute.attribute.store', compact(
                'config',
                'attribute',
                'attributeCatalogues',
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    } 

    public function update (UpdateAttributeRequest $request, $id) {
        $payload = $request->except('_token', 'canonical_original');
        $flag = $this->attributeService->update($id, $payload);
        if($flag) {
            return redirect()->route('attribute.index')->with('success', 'Cập nhật bản ghi thành công');
        }else{
            return redirect()->route('attribute.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function delete ($id) {
        if(Gate::allows('modules', 'attribute.delete')) {
            $config = $this->config();
            $config['method'] = 'delete';
            $attribute = $this->attributeRepository->findById($id);
            return view('backend.attribute.attribute.delete', compact(
                'config',
                'attribute'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function destroy ($id) {
        $flag = $this->attributeService->destroy($id);
        if($flag) {
            return redirect()->route('attribute.index')->with('success', 'Xóa bản ghi thành công');
        }else{
            return redirect()->route('attribute.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại!');
        }
    }

    private function config () {
        return [
            'js' => [
                '/backend/js/plugins/switchery/switchery.js',
                '/backend/js/plugins/toastr/toastr.min.js',
                '/backend/js/plugins/datapicker/bootstrap-datepicker.js',
                '/backend/js/library/library.js',
            ],
            'css' => [
                '/backend/css/plugins/switchery/switchery.css',
                '/backend/css/plugins/toastr/toastr.min.css',
            ], 
            'module' => 'attribute',
        ];
    }
}
