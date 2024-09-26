<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use App\Repositories\HistoryRepository as RepositoriesHistoryRepository;
use Illuminate\Http\Request;
use App\Services\Interfaces\PurchaseOrderServiceInterface as PurchaseOrderService;
use App\Repositories\Interfaces\PurchaseOrderRepositoryInterface as PurchaseOrderRepository;
use Illuminate\Support\Facades\Gate;
use App\Repositories\Interfaces\WarehouseRepositoryInterface as WarehouseRepository;
use App\Repositories\Interfaces\SupplierRepositoryInterface as SupplierRepository;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use App\Repositories\Interfaces\HistoryRepositoryInterface as HistoryRepository;

class PurchaseOrderController extends Controller
{
    //

    protected $purchaseOrderService;
    protected $purchaseOrderRepository;
    protected $warehouseRepository;
    protected $supplierRepository;
    protected $userRepository;
    protected $historyRepository;

    public function __construct(
        PurchaseOrderService $purchaseOrderService,
        PurchaseOrderRepository $purchaseOrderRepository,
        WarehouseRepository $warehouseRepository,
        SupplierRepository $supplierRepository,
        UserRepository $userRepository,
        HistoryRepository $historyRepository
    )
    {
        $this->purchaseOrderService = $purchaseOrderService;
        $this->purchaseOrderRepository = $purchaseOrderRepository;
        $this->warehouseRepository = $warehouseRepository;
        $this->supplierRepository = $supplierRepository;
        $this->userRepository = $userRepository;
        $this->historyRepository = $historyRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'purchaseOrder.index')) {
            $payload = $request->query();
            $purchaseOrders = $this->purchaseOrderService->paginate($payload);
            $config = $this->config();
            // dd($purchaseOrders);
            $config['method'] = 'index';
            return view('backend.warehouse.purchaseOrder.index', compact(
                'config',
                'purchaseOrders'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function create() {
        if(Gate::allows('modules', 'purchaseOrder.create')) {
            $config = $this->config();
            $warehouses = $this->warehouseRepository->findByCondition([
                ['publish', '=', 2]
            ], ['id', 'DESC']);
            $suppliers = $this->supplierRepository->findByCondition([
                ['publish', '=', 2]
            ], ['id', 'DESC']);
            $users = $this->userRepository->findByCondition([
                ['publish', '=', 2]
            ], ['id', 'DESC']);
            $config['method'] = 'create';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/chooseSupplier.js';
            $config['js'][] = '/backend/js/library/chooseProduct.js';
            $config['js'][] = '/backend/js/library/payment.js';
            $config['js'][] = '/backend/js/library/checkSubmit.js';
            $config['js'][] = '/backend/js/library/purchaseOrder.js';
            return view('backend.warehouse.purchaseOrder.store', compact(
                'config',
                'warehouses',
                'suppliers',
                'users'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function store(Request $request) {
        $purchaseOrder = $this->purchaseOrderService->store($request->except('_token'));
        if($purchaseOrder->id) {
            return redirect()->route('purchaseOrder.edit', $purchaseOrder->id)->with('success', 'Thêm mới bản ghi thành công');
        }else{
            return redirect()->route('purchaseOrder.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function edit($id) {
        if(Gate::allows('modules', 'purchaseOrder.edit')) {
            $config = $this->config();
            $config['method'] = 'edit';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/chooseSupplier.js';
            $config['js'][] = '/backend/js/library/chooseProduct.js';
            $config['js'][] = '/backend/js/library/payment.js';
            $config['js'][] = '/backend/js/library/checkSubmit.js';
            $config['js'][] = '/backend/js/library/purchaseOrder.js';
            $purchaseOrder = $this->purchaseOrderRepository->findByIdAndMultipleRelation($id, ['products', 'suppliers']);
            $warehouses = $this->warehouseRepository->findByCondition([
                ['publish', '=', 2]
            ], ['id', 'DESC']);
            $suppliers = $this->supplierRepository->findByCondition([
                ['publish', '=', 2]
            ], ['id', 'DESC']);
            $users = $this->userRepository->findByCondition([
                ['publish', '=', 2]
            ], ['id', 'DESC']);
            // dd($purchaseOrder);
            $histories = $this->historyRepository->findByCondition([
                ['model_id', '=', $id],
                ['model', '=', 'PurchaseOrder']
            ], ['id', 'DESC']);
            // dd($histories);
            return view('backend.warehouse.purchaseOrder.store', compact(
                'config',
                'purchaseOrder',
                'warehouses',
                'suppliers',
                'users',
                'histories'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    } 

    public function update (Request $request, $id) {
        $payload = $request->except('_token');
        $flag = $this->purchaseOrderService->update($id, $payload);
        if($flag) {
            return redirect()->route('purchaseOrder.edit', $id)->with('success', 'Cập nhật bản ghi thành công');
        }else{
            return redirect()->route('purchaseOrder.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại!');
        }
    }

    private function config () {
        return [
            'js' => [
                '/backend/js/plugins/switchery/switchery.js',
                '/backend/js/plugins/toastr/toastr.min.js',
                '/backend/js/plugins/datapicker/bootstrap-datepicker.js',
                'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js',
                '/backend/js/library/library.js',
                '/backend/js/library/confirmExit.js'
            ],
            'css' => [
                '/backend/css/plugins/switchery/switchery.css',
                '/backend/css/plugins/toastr/toastr.min.css',
                'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css'
            ], 
            'module' => 'purchaseOrder',
        ];
    }
}
