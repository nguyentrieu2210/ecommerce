<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use Illuminate\Http\Request;
use App\Services\Interfaces\WarehouseServiceInterface as WarehouseService;
use App\Repositories\Interfaces\WarehouseRepositoryInterface as WarehouseRepository;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use Illuminate\Support\Facades\Gate;

class WarehouseController extends Controller
{
    //

    protected $warehouseService;
    protected $warehouseRepository;
    protected $userRepository;

    public function __construct(
        WarehouseService $warehouseService,
        WarehouseRepository $warehouseRepository,
        UserRepository $userRepository
    )
    {
        $this->warehouseService = $warehouseService;
        $this->warehouseRepository = $warehouseRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'warehouse.index')) {
            $payload = $request->query();
            $warehouses = $this->warehouseService->paginate($payload);
            $config = $this->config();
            $config['method'] = 'index';
            return view('backend.warehouse.warehouse.index', compact(
                'config',
                'warehouses'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function create() {
        if(Gate::allows('modules', 'warehouse.create')) {
            $config = $this->config();
            $config['method'] = 'create';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $supervisors = $this->userRepository->findByCondition([
                ['user_catalogue_id', '=', 6]
            ]);
            return view('backend.warehouse.warehouse.store', compact(
                'config',
                'supervisors'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function store(StoreWarehouseRequest $request) {
        $warehouse = $this->warehouseService->store($request->except('_token'));
        if($warehouse->id) {
            return redirect()->route('warehouse.index')->with('success', 'Thêm mới bản ghi thành công');
        }else{
            return redirect()->route('warehouse.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function edit($id) {
        if(Gate::allows('modules', 'warehouse.edit')) {
            $config = $this->config();
            $config['method'] = 'edit';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $warehouse = $this->warehouseRepository->findById($id);
            $supervisors = $this->userRepository->findByCondition([
                ['user_catalogue_id', '=', 6]
            ]);
            return view('backend.warehouse.warehouse.store', compact(
                'config',
                'warehouse',
                'supervisors'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    } 

    public function update (UpdateWarehouseRequest $request, $id) {
        $payload = $request->except('_token');
        $flag = $this->warehouseService->update($id, $payload);
        if($flag) {
            return redirect()->route('warehouse.index')->with('success', 'Cập nhật bản ghi thành công');
        }else{
            return redirect()->route('warehouse.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function delete ($id) {
        if(Gate::allows('modules', 'warehouse.create')) {
            $config = $this->config();
            $config['method'] = 'delete';
            $warehouse = $this->warehouseRepository->findById($id);
            return view('backend.warehouse.warehouse.delete', compact(
                'config',
                'warehouse'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function destroy ($id) {
        $flag = $this->warehouseService->destroy($id);
        if($flag) {
            return redirect()->route('warehouse.index')->with('success', 'Xóa bản ghi thành công');
        }else{
            return redirect()->route('warehouse.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại!');
        }
    }

    private function config () {
        return [
            'js' => [
                '/backend/js/plugins/switchery/switchery.js',
                '/backend/js/plugins/toastr/toastr.min.js',
                '/backend/js/library/library.js',
            ],
            'css' => [
                '/backend/css/plugins/switchery/switchery.css',
                '/backend/css/plugins/toastr/toastr.min.css',
            ], 
            'module' => 'warehouse',
        ];
    }
}
