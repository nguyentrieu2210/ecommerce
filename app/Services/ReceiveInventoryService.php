<?php

namespace App\Services;

use App\Services\Interfaces\ReceiveInventoryServiceInterface;
use App\Repositories\Interfaces\ReceiveInventoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\HistoryRepositoryInterface as HistoryRepository;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use App\Repositories\Interfaces\PaymentRecordRepositoryInterface as PaymentRecordRepository;
use App\Repositories\Interfaces\WarehouseRepositoryInterface as WarehouseRepository;
use App\Repositories\Interfaces\ProductWarehouseRepositoryInterface as ProductWarehouseRepository;
use App\Repositories\Interfaces\PurchaseOrderRepositoryInterface as PurchaseOrderRepository;
use App\Repositories\Interfaces\ProductPurchaseOrderRepositoryInterface as ProductPurchaseOrderRepository;
use Carbon\Carbon;

/**
 * Class ReceiveInventoryService
 * @package App\Services
 */
class ReceiveInventoryService implements ReceiveInventoryServiceInterface
{

    protected $receiveInventoryRepository;
    protected $historyRepository;
    protected $userRepository;
    protected $paymentRecordRepository;
    protected $warehouseRepository;
    protected $productWarehouseRepository;
    protected $purchaseOrderRepository;
    protected $productPurchaseOrderRepository;

    public function __construct(
        ReceiveInventoryRepositoryInterface $receiveInventoryRepository,
        HistoryRepository $historyRepository,
        UserRepository $userRepository,
        PaymentRecordRepository $paymentRecordRepository,
        WarehouseRepository $warehouseRepository,
        ProductWarehouseRepository $productWarehouseRepository,
        PurchaseOrderRepository $purchaseOrderRepository,
        ProductPurchaseOrderRepository $productPurchaseOrderRepository
    )
    {
        $this->receiveInventoryRepository = $receiveInventoryRepository;
        $this->historyRepository = $historyRepository;
        $this->userRepository = $userRepository;
        $this->paymentRecordRepository = $paymentRecordRepository;
        $this->warehouseRepository = $warehouseRepository;
        $this->productWarehouseRepository = $productWarehouseRepository;
        $this->purchaseOrderRepository = $purchaseOrderRepository;
        $this->productPurchaseOrderRepository = $productPurchaseOrderRepository;
    }

    public function paginate($payload = []) {
        $condition = [];
        $limit = 20;
        if(isset($payload['limit'])) {
            $limit = $payload['limit'];
        }
        $fieldSelects = $this->fieldSelect();
        if(isset($payload['statusPayment']) && $payload['statusPayment'] !== 'none') {
            $condition[] = ['status_payment', '=', $payload['statusPayment']];
        }
        if(isset($payload['statusReceive']) && $payload['statusReceive'] !== 'none') {
            $condition[] = ['status_receive_inventory', '=', $payload['statusReceive']];
        }
        if(isset($payload['keyword']) && $payload['keyword'] !== "") {
            $condition['keyword'] = $payload['keyword'];
        }
        if(isset($payload['createdDay']) && $payload['createdDay'] !== 'none') {
            switch ($payload['createdDay']) {
                case 'today':
                    $condition['createdDay'] = ['whereDate' => ['created_at', '=', now()->startOfDay()]];
                    break;
                case 'yesterday':
                    $condition['createdDay'] = ['whereDate' => ['created_at', '=', now()->subDay()->startOfDay()]];
                    break;
                case '7_days_ago':
                    $condition['createdDay'] = ['whereDate' => ['created_at', '>=', now()->subDays(7)->startOfDay()]];
                    break;
                case '30_days_ago':
                    $condition['createdDay'] = ['whereDate' => ['created_at', '>=', now()->subDays(30)->startOfDay()]];
                    break;
                case 'week':
                    $condition['createdDay'] = ['whereBetween' => ['created_at', [now()->startOfWeek(), now()->endOfWeek()]]];
                    break;
                case 'week_ago':
                    $condition['createdDay'] = ['whereBetween' => ['created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]]];
                    break;
                case 'month':
                    $condition['createdDay'] = ['whereYear' => ['created_at', now()->year], 'whereMonth' => ['created_at', now()->month]];
                    break;
                case 'month_ago':
                    $condition['createdDay'] = ['whereYear' => ['created_at', now()->subMonth()->year], 'whereMonth' => ['created_at', now()->subMonth()->month]];
                    break;
                case 'year':
                    $condition['createdDay'] = ['whereYear' => ['created_at', now()->year]];
                    break;
                case 'year_ago':
                    $condition['createdDay'] = ['whereYear' => ['created_at', now()->subYear()->year]];
                    break;
                default:
                    $condition['createdDay'] = []; 
                    break;
            }
        }
        return $this->receiveInventoryRepository->pagination($fieldSelects, 
                                                $condition, 
                                                ['users', 'warehouses', 'suppliers'], 
                                                $limit, 
                                                ['id', 'DESC'],
                                                ['code']
                                            );
    }

    public function store ($payload = []) {
        DB::beginTransaction();
        try {
            $payloadPaymentRecord = $payload['payment'];
            unset($payload['payment']);
            $payloadProducts = $payload['products'];
            unset($payload['products']);
            //receive_inventories
            $payload['code'] = createCode('REI');
            $payload['expected_day'] = $payload['expected_day'] ? formatDateTimeToSql($payload['expected_day']) : null;
            $receiveInventory = $this->receiveInventoryRepository->create($payload);
            if($receiveInventory->id) {
                //product_receive_inventory
                $payloadProductReceiveInventory = $this->setupPayloadProductReceiveInventory($payloadProducts);
                $receiveInventory->products()->attach($payloadProductReceiveInventory);
                //histories -> Thêm mới đơn hàng nhập
                $payload['id'] = $receiveInventory->id;
                $payload['model'] = 'ReceiveInventory';
                $payload['content'] = '<span>Thêm mới đơn hàng nhập</span>';
                $payloadHistory = $this->setupPayloadHistory($payload);
                $this->historyRepository->create($payloadHistory);
                
                if($payload['status_receive_inventory'] == 'received') {
                    //TH NHẬP KHO NGAY KHI TẠO ĐƠN
                    $payloadProductWarehouse = $this->setupPayloadProductWarehouse($payloadProductReceiveInventory, $payloadProducts, $payload);
                    $this->productWarehouseRepository->multipleUpdate($payloadProductWarehouse, 'product_warehouse', ['quantity', 'cost_price', 'incoming'], 'warehouse_id');
                    //histories -> Xác nhận nhập kho đơn nhập
                    $payload['content'] = '<span>Xác nhận nhập kho đơn nhập</span>';
                    $payloadHistory = $this->setupPayloadHistory($payload);
                    $this->historyRepository->create($payloadHistory);
                }else{
                    //TH CHƯA NHẬP KHO NGAY KHI TẠO ĐƠN (với trường hợp này số lượng đang về kho của mỗi sản phẩm sẽ được tăng lên tương ứng)
                    $payloadProductWarehouse = $this->setupPayloadProductWarehouse($payloadProductReceiveInventory, $payloadProducts, $payload);
                    $this->productWarehouseRepository->multipleUpdate($payloadProductWarehouse, 'product_warehouse', ['incoming'], 'warehouse_id');
                }
                //TH THANH TOÁN NGAY KHI TẠO ĐƠN
                if($payload['status_payment'] == 'paid') {
                    //histories -> Xác nhận thanh toán cho đơn nhập
                    $payload['content'] = '<span>Thanh toán cho đơn nhập hàng</span>';
                    $payloadHistory = $this->setupPayloadHistory($payload);
                    $history = $this->historyRepository->create($payloadHistory);
                    //payment_records -> Tạo bản ghi để ghi lại giao dịch
                    $payloadPaymentRecord = $this->setupPayloadPaymentRecord($payload, $history->id, $receiveInventory->id, $payloadPaymentRecord);
                    $this->paymentRecordRepository->create($payloadPaymentRecord);
                }
                if(isset($payload['purchase_order_id'])) {
                    //histories -> Thêm lịch sử đã tạo đơn nhập từ đơn đặt
                    $payload['id'] = $payload['purchase_order_id'];
                    $payload['model'] = 'PurchaseOrder';
                    $payload['content'] = '<span>Thêm mới đơn nhập hàng <a class="text-primary" href="/admin/receiveInventory/'.$receiveInventory->id.'/edit">'.$payload['code'].'</a> từ đơn đặt</span>';
                    $payloadHistory = $this->setupPayloadHistory($payload);
                    $this->historyRepository->create($payloadHistory);
                    //product_purchase_order -> Cập nhật lại số lượng đã nhập và đã từ chối
                    $payloadProductPurchaseOrder = $this->setupPayloadProductPurchaseOrder($payloadProductReceiveInventory, $payloadProducts, $payload);
                    if(count($payloadProductPurchaseOrder)) {
                        $this->productPurchaseOrderRepository->multipleUpdate($payloadProductPurchaseOrder, 'product_purchase_order', ['quantity_received', 'quantity_rejected'], 'purchase_order_id');
                        //purchase_orders -> Cập nhật trạng thái đơn đặt hàng nhập sang "Nhập 1 phần" (nếu số lượng đã nhập + số lượng đã từ chối >= số lượng đặt ban đầu => cập nhật thành trạng thái "Nhập toàn bộ")
                        $productIds = array_column($payloadProductReceiveInventory, 'product_id');
                        $data = $this->purchaseOrderRepository->findByConditionPivot([['id', '=', $payload['purchase_order_id']]], 'products', ['id' => $productIds]);
                        $isCheck = true;
                        foreach($data->products->toArray() as $key => $item) {
                            if($item['pivot']['quantity_received'] + $item['pivot']['quantity_rejected'] < $item['pivot']['quantity']) {
                                $isCheck = false;
                            }
                        }
                        if($isCheck) {
                            $this->purchaseOrderRepository->updateById($payload['purchase_order_id'], ['status' => 'fully_processed']);
                        }else{
                            $this->purchaseOrderRepository->updateById($payload['purchase_order_id'], ['status' => 'partial_processed']);
                        }
                    }
                }
            }
            DB::commit();
            return $receiveInventory;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }

    private function setupPayloadProductPurchaseOrder ($payloadProductReceiveInventory, $payloadProducts, $payload) {
        $productIds = array_column($payloadProductReceiveInventory, 'product_id');
        $productVariantIds = array_column($payloadProductReceiveInventory, 'product_variant_id');
        $data = $this->purchaseOrderRepository->findByConditionPivot([['id', '=', $payload['purchase_order_id']]], 'products', ['id' => $productIds]);
        $payloadProductPurchaseOrder = [];
        foreach($data->products->toArray() as $index => $item) {
            $id = $item['pivot']['product_id'];
            $variantId = $item['pivot']['product_variant_id'];
            if(in_array($id, $productIds) && ($variantId == null || in_array($variantId, $productVariantIds))) {
                $temp = $item['pivot'];
                $key = $item['id'] . ($variantId !== null ? ('-'.$variantId) : '');
                $temp['quantity_received'] = $temp['quantity_received'] + $payloadProducts[$key]['quantity'];
                $temp['quantity_rejected'] = $temp['quantity_rejected'] + $payloadProducts[$key]['quantity_rejected'] ?? 0;
                $payloadProductPurchaseOrder[] = $temp;
            }
        }
        return $payloadProductPurchaseOrder;
    }

    private function setupPayloadPaymentRecord ($payload, $historyId, $modelId, $payloadPaymentRecord) {
        if(isset($payloadPaymentRecord['amount'])) {
            $finalTotal = str_replace(['.', 'đ'], '', $payloadPaymentRecord['amount']);
        }else{
            $finalTotal = calculateFinalTotalCost($payload);
        }
        $payloadPaymentRecord['history_id'] = $historyId;
        $payloadPaymentRecord['model'] = 'ReceiveInventory';
        $payloadPaymentRecord['model_id'] = $modelId;
        $payloadPaymentRecord['content'] = 'Thanh toán cho ' . formatNumberFromSql($finalTotal) .'đ đơn nhập hàng';
        $payloadPaymentRecord['amount'] = $finalTotal;
        $payloadPaymentRecord['recorded_at'] = formatDateTimeToSql($payloadPaymentRecord['recorded_at']);
        return $payloadPaymentRecord;
    }

    private function setupPayloadHistory($payload) {
        $userName = $this->userRepository->findById($payload['user_id'])->name;
        $payloadHistory = [
            'model' => $payload['model'],
            'model_id' => $payload['id'],
            'user_id' => $payload['user_id'],
            'content' => $payload['content'],
            'user_name' => $userName
        ];
        return $payloadHistory;
    }

    private function setupPayloadProductReceiveInventory($payload) {
        $temp = [];
        foreach($payload as $index => $item) {
            $newItem = $item;
            $detail = json_decode($item['detail'], true);
            unset($newItem['detail']);
            foreach($detail as $key => $val) {
                $newItem[$key] = $val;
            }
            if(!isset($newItem['quantity_rejected'])) {
                $newItem['quantity_rejected'] = 0;
                $newItem['rejection_reason'] = null;
            }
            $temp[] = $newItem;
        }
        return $temp;
    }

    public function update ($id, $payload) {
        DB::beginTransaction();
        try {
            unset($payload['payment']);
            $payloadProducts = $payload['products'];
            unset($payload['products']);
            //receive_inventories
            $payload['expected_day'] = $payload['expected_day'] ? formatDateTimeToSql($payload['expected_day']) : null;
            $flag = $this->receiveInventoryRepository->updateById($id, $payload);
            if($flag) {
                //product_receive_inventory
                $payloadProductReceiveInventory = $this->setupPayloadProductReceiveInventory($payloadProducts);
                $receiveInventory = $this->receiveInventoryRepository->findById($id);
                $receiveInventory->products()->sync($payloadProductReceiveInventory);
                //histories -> Cập nhật đơn hàng nhập
                $payload['id'] = $id;
                $payload['model'] = 'ReceiveInventory';
                $payload['content'] = '<span>Cập nhật đơn hàng nhập</span>';
                $payloadHistory = $this->setupPayloadHistory($payload);
                $this->historyRepository->create($payloadHistory);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }

    public function received($id, $payload) {
        DB::beginTransaction();
        try {
            $payloadProducts = $payload['products'];
            $payloadProductReceiveInventory = $this->setupPayloadProductReceiveInventory($payloadProducts);
            //Tăng sản phẩm trong kho lên và tính lại giá vốn
            $payloadProductWarehouse = $this->setupPayloadProductWarehouse($payloadProductReceiveInventory, $payloadProducts, $payload);
            $this->productWarehouseRepository->multipleUpdate($payloadProductWarehouse, 'product_warehouse', ['quantity', 'cost_price', 'incoming'], 'warehouse_id');
            //histories -> Xác nhận nhập kho đơn nhập
            $payload['content'] = '<span>Xác nhận nhập kho đơn nhập</span>';
            $payloadHistory = $this->setupPayloadHistory($payload);
            $this->historyRepository->create($payloadHistory);
            $this->receiveInventoryRepository->updateById($payload['id'], ['status_receive_inventory' => 'received']);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }


    private function setupPayloadProductWarehouse ($payloadProductReceiveInventory, $payloadProducts, $payload) {
        $productIds = array_column($payloadProductReceiveInventory, 'product_id');
        $productVariantIds = array_column($payloadProductReceiveInventory, 'product_variant_id');
        $data = $this->warehouseRepository->findByConditionPivot([['id', '=', $payload['warehouse_id']]], 'products', ['id' => $productIds]);
        $payloadProductWarehouse = [];
        $purchaseOrder = null;
        if(isset($payload['purchase_order_id']) && $payload['purchase_order_id'] !== null) {
            $purchaseOrder = $this->purchaseOrderRepository->findByConditionPivot([['id', '=', $payload['purchase_order_id']]], 'products');
        }
        foreach($data->products->toArray() as $index => $item) {
            $variantId = $item['pivot']['product_variant_id'];
            if($variantId == null || in_array($variantId, $productVariantIds)) {
                $temp = $item['pivot'];
                $costPriceTotal = $item['pivot']['quantity'] * $item['pivot']['cost_price'];
                $key = $item['id'] . ($variantId !== null ? ('-'.$variantId) : '');
                if($payload['status_receive_inventory'] == 'pending') {
                    $temp['incoming'] = $item['pivot']['incoming'] + $payloadProducts[$key]['quantity'];
                }else{
                    $costPriceCalculated = ($costPriceTotal + $payloadProducts[$key]['cost_price'] * $payloadProducts[$key]['quantity']) / ($item['pivot']['quantity'] + $payloadProducts[$key]['quantity']);
                    $temp['quantity'] = $item['pivot']['quantity'] + $payloadProducts[$key]['quantity'];
                    $temp['cost_price'] = $costPriceCalculated;
                    if(isset($purchaseOrder)) {
                        foreach($purchaseOrder->products->toArray() as $purchaseOrderItem) {
                            if($purchaseOrderItem['pivot']['product_id'] == $item['pivot']['product_id'] && $purchaseOrderItem['pivot']['product_variant_id'] == $item['pivot']['product_variant_id']) {
                                $incoming = ($payloadProducts[$key]['quantity'] + $payloadProducts[$key]['quantity_rejected']) > $purchaseOrderItem['pivot']['quantity'] ? $purchaseOrderItem['pivot']['quantity'] : ($payloadProducts[$key]['quantity'] + $payloadProducts[$key]['quantity_rejected']);
                                $temp['incoming'] = $item['pivot']['incoming'] - $incoming >= 0 ? $item['pivot']['incoming'] - $incoming : 0;
                            }
                        }
                    }else {
                        $temp['incoming'] = $item['pivot']['incoming'] - $payloadProducts[$key]['quantity'] > 0 ? $item['pivot']['incoming'] - $payloadProducts[$key]['quantity'] : 0;
                    }
                }
                
                $payloadProductWarehouse[] = $temp;
            }
        }
        return $payloadProductWarehouse;
    }

    public function paid($id, $payload) {
        DB::beginTransaction();
        try {
            //histories -> Xác nhận thanh toán cho đơn nhập
            $payload['content'] = '<span>Thanh toán cho đơn nhập hàng</span>';
            $payloadHistory = $this->setupPayloadHistory($payload);
            $history = $this->historyRepository->create($payloadHistory);
            //payment_records -> Tạo bản ghi để ghi lại giao dịch
            $payloadPaymentRecord = $this->setupPayloadPaymentRecord($payload, $history->id, $payload['id'], $payload['payment']);
            $this->paymentRecordRepository->create($payloadPaymentRecord);
            $this->receiveInventoryRepository->updateById($payload['id'], ['status_payment' => 'paid']);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
    }

    private function fieldSelect () {
        return [
            'id',
            'code',
            'price_total',
            'quantity_total',
            'discount_value',
            'discount_type',
            'status_payment',
            'status_receive_inventory',
            'user_id',
            'supplier_id',
            'warehouse_id',
            'created_at',
        ];
    }
}
