<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use Illuminate\Http\Request;
use App\Services\Interfaces\SupplierServiceInterface as SupplierService;
use App\Repositories\Interfaces\SupplierRepositoryInterface as SupplierRepository;
use Illuminate\Support\Facades\Gate;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;

class SupplierController extends Controller
{
    //

    protected $supplierService;
    protected $supplierRepository;
    protected $provinceRepository;
    protected $userRepository;

    public function __construct(
        SupplierService $supplierService,
        SupplierRepository $supplierRepository,
        ProvinceRepository $provinceRepository,
        UserRepository $userRepository
    )
    {
        $this->supplierService = $supplierService;
        $this->supplierRepository = $supplierRepository;
        $this->provinceRepository = $provinceRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'supplier.index')) {
            $payload = $request->query();
            $suppliers = $this->supplierService->paginate($payload);
            $config = $this->config();
            $config['method'] = 'index';
            return view('backend.warehouse.supplier.index', compact(
                'config',
                'suppliers'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function create() {
        if(Gate::allows('modules', 'supplier.create')) {
            $config = $this->config();
            $config['method'] = 'create';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/location.js';
            $provinces = $this->provinceRepository->selectByField(['code', 'name']);
            $users = $this->userRepository->findByCondition([
                ['publish', '=', 2]
            ], [], 'user_catalogue_id', [1, 6]);
            return view('backend.warehouse.supplier.store', compact(
                'config',
                'provinces',
                'users'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function store(StoreSupplierRequest $request) {
        $supplier = $this->supplierService->store($request->except('_token'));
        if($supplier->id) {
            return redirect()->route('supplier.index')->with('success', 'Thêm mới bản ghi thành công');
        }else{
            return redirect()->route('supplier.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function edit($id) {
        if(Gate::allows('modules', 'supplier.edit')) {
            $config = $this->config();
            $config['method'] = 'edit';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/location.js';
            $config['js'][] = '/backend/js/library/supplier.js';
            $provinces = $this->provinceRepository->selectByField(['code', 'name']);
            $supplier = $this->supplierRepository->findByIdAndMultipleRelation($id, ['receiveInventories']);
            $receiveInventories = $supplier->receiveInventories()->orderBy('id', 'DESC')->paginate(10);
            $receiveInventoryInfo = [
                'totalQuantity' => count($supplier->receiveInventories),
                'totalPrice' => 0,
                'totalPriceUnpaid' => 0,
                'totalQuantityUnpaid' => 0
            ];
            if(count($supplier->receiveInventories)) {
                $totalPrice = 0;
                $totalPriceUnpaid = 0;
                $totalQuantityUnpaid = 0;
                foreach($supplier->receiveInventories as $item) {
                    $price = calculateFinalTotalCost($item);
                    $totalPrice += $price;
                    if($item->status_payment == 'pending' && $item->status_receive_inventory == 'received') {
                        $totalPriceUnpaid += $price;
                        $totalQuantityUnpaid += 1;
                    }
                }
                $receiveInventoryInfo['totalPrice'] = $totalPrice;
                $receiveInventoryInfo['totalPriceUnpaid'] = $totalPriceUnpaid;
                $receiveInventoryInfo['totalQuantityUnpaid'] = $totalQuantityUnpaid;
            }
            $users = $this->userRepository->findByCondition([
                ['publish', '=', 2]
            ], [], 'user_catalogue_id', [1, 6]);
            // dd($supplier);
            return view('backend.warehouse.supplier.update', compact(
                'config',
                'supplier',
                'receiveInventories',
                'provinces',
                'users',
                'receiveInventoryInfo'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    } 

    public function update (UpdateSupplierRequest $request, $id) {
        $payload = $request->except('_token');
        $flag = $this->supplierService->update($id, $payload);
        if($flag) {
            return redirect()->back()->with('success', 'Cập nhật thông tin nhà cung cấp thành công');
        }else{
            return redirect()->route('supplier.index')->with('error', 'Cập nhật thông tin nhà cung cấp không thành công. Hãy thử lại!');
        }
    }

    public function delete ($id) {
        if(Gate::allows('modules', 'supplier.create')) {
            $config = $this->config();
            $config['method'] = 'delete';
            $supplier = $this->supplierRepository->findById($id);
            return view('backend.warehouse.supplier.delete', compact(
                'config',
                'supplier'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function destroy ($id) {
        $flag = $this->supplierService->destroy($id);
        if($flag) {
            return redirect()->route('supplier.index')->with('success', 'Xóa bản ghi thành công');
        }else{
            return redirect()->route('supplier.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại!');
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
            'module' => 'supplier',
        ];
    }
}
