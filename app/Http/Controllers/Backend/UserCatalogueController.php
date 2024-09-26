<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserCatalogueRequest;
use App\Http\Requests\UpdateUserCatalogueRequest;
use Illuminate\Http\Request;
use App\Services\Interfaces\UserCatalogueServiceInterface as UserCatalogueService;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface as UserCatalogueRepository;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;
use Illuminate\Support\Facades\Gate;

class UserCatalogueController extends Controller
{
    //

    protected $userCatalogueService;
    protected $userCatalogueRepository;
    protected $userCatalogueCatalogueRepository;
    protected $provinceRepository;
    protected $permissionRepository;

    public function __construct(
        UserCatalogueService $userCatalogueService,
        UserCatalogueRepository $userCatalogueRepository,
        UserCatalogueRepository $userCatalogueCatalogueRepository,
        ProvinceRepository $provinceRepository,
        PermissionRepository $permissionRepository
    )
    {
        $this->userCatalogueService = $userCatalogueService;
        $this->userCatalogueRepository = $userCatalogueRepository;
        $this->userCatalogueRepository = $userCatalogueCatalogueRepository;
        $this->provinceRepository = $provinceRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'userCatalogue.index')) {
            $payload = $request->query();
            $userCatalogues = $this->userCatalogueService->paginate($payload);
            $config = $this->config();
            $config['method'] = 'index';
            return view('backend.user.userCatalogue.index', compact(
                'config',
                'userCatalogues'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function create() {
        if(Gate::allows('modules', 'userCatalogue.create')) {
            $config = $this->config();
            $config['method'] = 'create';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            return view('backend.user.userCatalogue.store', compact(
                'config',
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function store(StoreUserCatalogueRequest $request) {
        $userCatalogue = $this->userCatalogueService->store($request->except('_token'));
        if($userCatalogue->id) {
            return redirect()->route('userCatalogue.index')->with('success', 'Thêm mới bản ghi thành công');
        }else{
            return redirect()->route('userCatalogue.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function edit($id) {
        if(Gate::allows('modules', 'userCatalogue.edit')) {
            $config = $this->config();
            $config['method'] = 'edit';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $userCatalogue = $this->userCatalogueRepository->findById($id);
            return view('backend.user.userCatalogue.store', compact(
                'config',
                'userCatalogue',
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    } 

    public function update (UpdateUserCatalogueRequest $request, $id) {
        $payload = $request->except('_token');
        $flag = $this->userCatalogueService->update($id, $payload);
        if($flag) {
            return redirect()->route('userCatalogue.index')->with('success', 'Cập nhật bản ghi thành công');
        }else{
            return redirect()->route('userCatalogue.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function delete ($id) {
        if(Gate::allows('modules', 'userCatalogue.create')) {
            $config = $this->config();
            $config['method'] = 'delete';
            $userCatalogue = $this->userCatalogueRepository->findById($id);
            return view('backend.user.userCatalogue.delete', compact(
                'config',
                'userCatalogue'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function destroy ($id) {
        $flag = $this->userCatalogueService->destroy($id);
        if($flag) {
            return redirect()->route('userCatalogue.index')->with('success', 'Xóa bản ghi thành công');
        }else{
            return redirect()->route('userCatalogue.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function permission () {
        if(Gate::allows('modules', 'userCatalogue.permission')) {
            $config = $this->config();
            $config['method'] = 'index';
            $userCatalogues = $this->userCatalogueRepository->getAll(['permissions']);
            // dd($userCatalogues[0]->permissions);
            $permissions = $this->permissionRepository->getAll();
            return view('backend.user.userCatalogue.permission', compact(
                'config',
                'userCatalogues',
                'permissions'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function permissionUpdate (Request $request) {
        $payload = $request->except('_token');
        $flag = $this->userCatalogueService->permission($payload);
        if($flag) {
            return redirect()->route('userCatalogue.permission')->with('success', 'Cập nhật quyền thành công');
        }else{
            return redirect()->route('userCatalogue.permission')->with('error', 'Cập nhật quyền không thành công. Hãy thử lại!');
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
            'module' => 'userCatalogue',
        ];
    }
}
