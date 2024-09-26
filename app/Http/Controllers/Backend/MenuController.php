<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use Illuminate\Http\Request;
use App\Services\Interfaces\MenuServiceInterface as MenuService;
use App\Repositories\Interfaces\MenuRepositoryInterface as MenuRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class MenuController extends Controller
{
    //

    protected $menuService;
    protected $menuRepository;

    public function __construct(
        MenuService $menuService,
        MenuRepository $menuRepository,
    )
    {
        $this->menuService = $menuService;
        $this->menuRepository = $menuRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'menu.index')) {
            $payload = $request->query();
            $menus = $this->menuService->paginate($payload);
            $config = $this->config();
            $config['method'] = 'index';
            return view('backend.configuration.menu.index', compact(
                'config',
                'menus'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function create() {
        if(Gate::allows('modules', 'menu.create')) {
            $config = $this->config();
            $config['method'] = 'create';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/menu.js';
            $config['js'][] = '/backend/js/library/nestable.js';
            return view('backend.configuration.menu.store', compact(
                'config',
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function store(StoreMenuRequest $request) {
        $menu = $this->menuService->store($request->except('_token'));
        if($menu->id) {
            return redirect()->route('menu.index')->with('success', 'Thêm mới bản ghi thành công');
        }else{
            return redirect()->route('menu.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function edit($id) {
        if(Gate::allows('modules', 'menu.edit')) {
            $config = $this->config();
            $config['method'] = 'edit';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/menu.js';
            $config['js'][] = '/backend/js/library/nestable.js';
            $menu = $this->menuRepository->findById($id, 'links');
            return view('backend.configuration.menu.store', compact(
                'config',
                'menu',
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    } 

    public function update (UpdateMenuRequest $request, $id) {
        $payload = $request->except('_token');
        $flag = $this->menuService->update($id, $payload);
        if($flag) {
            return redirect()->route('menu.index')->with('success', 'Cập nhật bản ghi thành công');
        }else{
            return redirect()->route('menu.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function delete ($id) {
        if(Gate::allows('modules', 'menu.create')) {
            $config = $this->config();
            $config['method'] = 'delete';
            $menu = $this->menuRepository->findById($id);
            return view('backend.configuration.menu.delete', compact(
                'config',
                'menu'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function destroy ($id) {
        $flag = $this->menuService->destroy($id);
        if($flag) {
            return redirect()->route('menu.index')->with('success', 'Xóa bản ghi thành công');
        }else{
            return redirect()->route('menu.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại!');
        }
    }

    private function config () {
        return [
            'js' => [
                '/backend/js/plugins/switchery/switchery.js',
                '/backend/js/plugins/toastr/toastr.min.js',
                '/backend/js/plugins/datapicker/bootstrap-datepicker.js',
                "/backend/js/plugins/nestable/jquery.nestable.js",
                // '/backend/js/plugins/validate/jquery.validate.min.js',
                // '/backend/js/library/validation.js',
                '/backend/js/library/library.js',
                
            ],
            'css' => [
                '/backend/css/plugins/switchery/switchery.css',
                '/backend/css/plugins/toastr/toastr.min.css',
            ], 
            'module' => 'menu',
        ];
    }
}
