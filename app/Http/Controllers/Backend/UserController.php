<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Services\Interfaces\UserServiceInterface as UserService;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface as UserCatalogueRepository;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    //

    protected $userService;
    protected $userRepository;
    protected $userCatalogueRepository;
    protected $provinceRepository;

    public function __construct(
        UserService $userService,
        UserRepository $userRepository,
        UserCatalogueRepository $userCatalogueRepository,
        ProvinceRepository $provinceRepository
    )
    {
        $this->userService = $userService;
        $this->userRepository = $userRepository;
        $this->userCatalogueRepository = $userCatalogueRepository;
        $this->provinceRepository = $provinceRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'user.index')) {
            $payload = $request->query();
            $users = $this->userService->paginate($payload);
            $userCatalogues = $this->userCatalogueRepository->selectByField(['id', 'name']);
            $config = $this->config();
            $config['method'] = 'index';
            return view('backend.user.user.index', compact(
                'users',
                'config',
                'userCatalogues'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function create() {
        if(Gate::allows('modules', 'user.create')) {
            $config = $this->config();
            $config['method'] = 'create';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/location.js';
            $provinces = $this->provinceRepository->selectByField(['code', 'name']);
            $userCatalogues = $this->userCatalogueRepository->selectByField(['id', 'name']);
            return view('backend.user.user.store', compact(
                'config',
                'userCatalogues',
                'provinces'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function store(StoreUserRequest $request) {
        $user = $this->userService->store($request->except('_token', 'password_confirmation', 'district', 'ward'));
        if($user->id) {
            return redirect()->route('user.index')->with('success', 'Thêm mới bản ghi thành công');
        }else{
            return redirect()->route('user.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function edit($id) {
        if(Gate::allows('modules', 'user.edit')) {
            $config = $this->config();
            $config['method'] = 'edit';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/location.js';
            $provinces = $this->provinceRepository->selectByField(['code', 'name']);
            $userCatalogues = $this->userCatalogueRepository->selectByField(['id', 'name']);
            $user = $this->userRepository->findById($id);
            return view('backend.user.user.store', compact(
                'config',
                'userCatalogues',
                'provinces',
                'user'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    } 

    public function update (UpdateUserRequest $request, $id) {
        $payload = $request->except('_token', 'district', 'ward');
        $flag = $this->userService->update($id, $payload);
        if($flag) {
            return redirect()->route('user.index')->with('success', 'Cập nhật bản ghi thành công');
        }else{
            return redirect()->route('user.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function delete ($id) {
        if(Gate::allows('modules', 'user.delete')) {
            $config = $this->config();
            $config['method'] = 'delete';
            $user = $this->userRepository->findById($id);
            return view('backend.user.user.delete', compact(
                'config',
                'user'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function destroy ($id) {
        $flag = $this->userService->destroy($id);
        if($flag) {
            return redirect()->route('user.index')->with('success', 'Xóa bản ghi thành công');
        }else{
            return redirect()->route('user.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại!');
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
                '/backend/css/plugins/datapicker/datepicker3.css',
            ], 
            'module' => 'user',
        ];
    }
}
