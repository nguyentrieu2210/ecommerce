<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\ReceiveInventoryServiceInterface as ReceiveInventoryService;
use App\Repositories\Interfaces\ReceiveInventoryRepositoryInterface as ReceiveInventoryRepository;
use Illuminate\Support\Facades\Gate;
use App\Repositories\Interfaces\WarehouseRepositoryInterface as WarehouseRepository;
use App\Repositories\Interfaces\SupplierRepositoryInterface as SupplierRepository;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use App\Repositories\Interfaces\HistoryRepositoryInterface as HistoryRepository;
use App\Repositories\Interfaces\PurchaseOrderRepositoryInterface as PurchaseOrderRepository;

class ReceiveInventoryController extends Controller
{
    //

    protected $receiveInventoryService;
    protected $receiveInventoryRepository;
    protected $warehouseRepository;
    protected $supplierRepository;
    protected $userRepository;
    protected $historyRepository;
    protected $purchaseOrderRepository;

    public function __construct(
        ReceiveInventoryService $receiveInventoryService,
        ReceiveInventoryRepository $receiveInventoryRepository,
        WarehouseRepository $warehouseRepository,
        SupplierRepository $supplierRepository,
        UserRepository $userRepository,
        HistoryRepository $historyRepository,
        PurchaseOrderRepository $purchaseOrderRepository
    )
    {
        $this->receiveInventoryService = $receiveInventoryService;
        $this->receiveInventoryRepository = $receiveInventoryRepository;
        $this->warehouseRepository = $warehouseRepository;
        $this->supplierRepository = $supplierRepository;
        $this->userRepository = $userRepository;
        $this->historyRepository = $historyRepository;
        $this->purchaseOrderRepository = $purchaseOrderRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'receiveInventory.index')) {
            $payload = $request->query();
            $receiveInventories = $this->receiveInventoryService->paginate($payload);
            $config = $this->config();
            // dd($receiveInventories);
            $config['method'] = 'index';
            return view('backend.warehouse.receiveInventory.index', compact(
                'config',
                'receiveInventories'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function create(Request $request) {
        if(Gate::allows('modules', 'receiveInventory.create')) {
            $purchaseOrderId = $request->query('purchase_order_id', null);
            $config = $this->config();
            if($purchaseOrderId) {
                $receiveInventory = $this->purchaseOrderRepository->findByIdAndMultipleRelation($purchaseOrderId, ['products', 'suppliers']);
                $config['type'] = 'createByPurchaseOrder';
            }else{
                $receiveInventory = null;
            }
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
            $config['js'][] = '/backend/js/library/receiveInventory.js';
            return view('backend.warehouse.receiveInventory.store', compact(
                'config',
                'warehouses',
                'suppliers',
                'users',
                'receiveInventory'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function store(Request $request) {
        $receiveInventory = $this->receiveInventoryService->store($request->except('_token'));
        if($receiveInventory->id) {
            return redirect()->route('receiveInventory.edit', $receiveInventory->id)->with('success', 'Thêm mới bản ghi thành công');
        }else{
            return redirect()->route('receiveInventory.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function edit($id) {
        if(Gate::allows('modules', 'receiveInventory.edit')) {
            $config = $this->config();
            $config['method'] = 'edit';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/chooseSupplier.js';
            $config['js'][] = '/backend/js/library/chooseProduct.js';
            $config['js'][] = '/backend/js/library/payment.js';
            $config['js'][] = '/backend/js/library/checkSubmit.js';
            $config['js'][] = '/backend/js/library/receiveInventory.js';
            $receiveInventory = $this->receiveInventoryRepository->findByIdAndMultipleRelation($id, ['products', 'suppliers']);
            // dd($receiveInventory);
            $warehouses = $this->warehouseRepository->findByCondition([
                ['publish', '=', 2]
            ], ['id', 'DESC']);
            $suppliers = $this->supplierRepository->findByCondition([
                ['publish', '=', 2]
            ], ['id', 'DESC']);
            $users = $this->userRepository->findByCondition([
                ['publish', '=', 2]
            ], ['id', 'DESC']);
            // dd($receiveInventory);
            $histories = $this->historyRepository->findByCondition([
                ['model_id', '=', $id],
                ['model', '=', 'receiveInventory']
            ], ['id', 'DESC'], '', [], '', [], ['payment_record']);
            // dd($histories);
            return view('backend.warehouse.receiveInventory.store', compact(
                'config',
                'receiveInventory',
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
        $flag = $this->receiveInventoryService->update($id, $payload);
        if($flag) {
            return redirect()->route('receiveInventory.edit', $id)->with('success', 'Cập nhật bản ghi thành công');
        }else{
            return redirect()->route('receiveInventory.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại!');
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
            'module' => 'receiveInventory',
        ];
    }
}
