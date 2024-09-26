<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Illuminate\Http\Request;
use App\Services\Interfaces\PermissionServiceInterface as PermissionService;
use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{
    //

    protected $permissionService;
    protected $permissionRepository;

    public function __construct(
        PermissionService $permissionService,
        PermissionRepository $permissionRepository
    )
    {
        $this->permissionService = $permissionService;
        $this->permissionRepository = $permissionRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'permission.index')) {
            $payload = $request->query();
            $permissions = $this->permissionService->paginate($payload);
            $config = $this->config();
            $config['method'] = 'index';
            // dd($permissions[0]->users->count());
            return view('backend.user.permission.index', compact(
                'config',
                'permissions'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function create() {
        if(Gate::allows('modules', 'permission.create')) {
            $config = $this->config();
            $config['method'] = 'create';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            return view('backend.user.permission.store', compact(
                'config',
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function store(StorePermissionRequest $request) {
        $permission = $this->permissionService->store($request->except('_token'));
        if($permission->id) {
            return redirect()->route('permission.index')->with('success', 'Thêm mới bản ghi thành công');
        }else{
            return redirect()->route('permission.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function edit($id) {
        if(Gate::allows('modules', 'permission.edit')) {
            $config = $this->config();
            $config['method'] = 'edit';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $permission = $this->permissionRepository->findById($id);
            return view('backend.user.permission.store', compact(
                'config',
                'permission',
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    } 

    public function update (UpdatePermissionRequest $request, $id) {
        $payload = $request->except('_token');
        $flag = $this->permissionService->update($id, $payload);
        if($flag) {
            return redirect()->route('permission.index')->with('success', 'Cập nhật bản ghi thành công');
        }else{
            return redirect()->route('permission.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function delete ($id) {
        if(Gate::allows('modules', 'permission.delete')) {
            $config = $this->config();
            $config['method'] = 'delete';
            $permission = $this->permissionRepository->findById($id);
            return view('backend.user.permission.delete', compact(
                'config',
                'permission'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function destroy ($id) {
        $flag = $this->permissionService->destroy($id);
        if($flag) {
            return redirect()->route('permission.index')->with('success', 'Xóa bản ghi thành công');
        }else{
            return redirect()->route('permission.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại!');
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
            'module' => 'permission',
        ];
    }
}
