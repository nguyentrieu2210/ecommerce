<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttributeCatalogueRequest;
use App\Http\Requests\UpdateAttributeCatalogueRequest;
use Illuminate\Http\Request;
use App\Services\Interfaces\AttributeCatalogueServiceInterface as AttributeCatalogueService;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface as AttributeCatalogueRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AttributeCatalogueController extends Controller
{
    //

    protected $attributeCatalogueService;
    protected $attributeCatalogueRepository;

    public function __construct(
        AttributeCatalogueService $attributeCatalogueService,
        AttributeCatalogueRepository $attributeCatalogueRepository
    )
    {
        $this->attributeCatalogueService = $attributeCatalogueService;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'attributeCatalogue.index')) {
            $payload = $request->query();
            $attributeCatalogues = customizeNestedset($this->attributeCatalogueService->paginate($payload));
            $config = $this->config();
            $config['method'] = 'index';
            // dd($attributeCatalogues[0]->attributes->count());
            return view('backend.attribute.attributeCatalogue.index', compact(
                'config',
                'attributeCatalogues'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function create() {
        if(Gate::allows('modules', 'attributeCatalogue.create')) {
            $config = $this->config();
            $config['method'] = 'create';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/seo.js';
            $attributeCatalogues = customizeNestedset($this->attributeCatalogueRepository->findByCondition([], ['_lft', 'DESC']));
            return view('backend.attribute.attributeCatalogue.store', compact(
                'config',
                'attributeCatalogues'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function store(StoreAttributeCatalogueRequest $request) {
        $flag = $this->attributeCatalogueService->store($request->except('_token', 'canonical_original'));
        if($flag) {
            return redirect()->route('attributeCatalogue.index')->with('success', 'Thêm mới bản ghi thành công');
        }else{
            return redirect()->route('attributeCatalogue.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function edit($id) {
        if(Gate::allows('modules', 'attributeCatalogue.edit')) {
            $config = $this->config();
            $config['method'] = 'edit';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/seo.js';
            $attributeCatalogue = $this->attributeCatalogueRepository->findById($id);
            $attributeCatalogues = customizeNestedset($this->attributeCatalogueRepository->findByCondition([
                ['id', '<>', $id]
            ], ['_lft', 'DESC']));
            return view('backend.attribute.attributeCatalogue.store', compact(
                'config',
                'attributeCatalogue',
                'attributeCatalogues'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    } 

    public function update (UpdateAttributeCatalogueRequest $request, $id) {
        $payload = $request->except('_token', 'canonical_original');
        $flag = $this->attributeCatalogueService->update($id, $payload);
        if($flag) {
            return redirect()->route('attributeCatalogue.index')->with('success', 'Cập nhật bản ghi thành công');
        }else{
            return redirect()->route('attributeCatalogue.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function delete ($id) {
        if(Gate::allows('modules', 'attributeCatalogue.delete')) {
            $config = $this->config();
            $config['method'] = 'delete';
            $attributeCatalogue = $this->attributeCatalogueRepository->findById($id);
            return view('backend.attribute.attributeCatalogue.delete', compact(
                'config',
                'attributeCatalogue'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function destroy ($id) {
        $flag = $this->attributeCatalogueService->destroy($id);
        if($flag) {
            return redirect()->route('attributeCatalogue.index')->with('success', 'Xóa bản ghi thành công');
        }else{
            return redirect()->route('attributeCatalogue.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại!');
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
            'module' => 'attributeCatalogue',
        ];
    }

    private function getFields () {
        return [
            'id',
            'name',
            'level',
            '_lft',
            '_rgt',
            'parent_id'
        ];
    }
}
