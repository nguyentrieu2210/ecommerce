<?php

namespace App\Services;

use App\Services\Interfaces\PurchaseOrderServiceInterface;
use App\Repositories\Interfaces\PurchaseOrderRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\HistoryRepositoryInterface as HistoryRepository;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use App\Repositories\Interfaces\WarehouseRepositoryInterface as WarehouseRepository;
use App\Repositories\Interfaces\ProductWarehouseRepositoryInterface as ProductWarehouseRepository;

/**
 * Class PurchaseOrderService
 * @package App\Services
 */
class PurchaseOrderService implements PurchaseOrderServiceInterface
{

    protected $purchaseOrderRepository;
    protected $historyRepository;
    protected $userRepository;
    protected $warehouseRepository;
    protected $productWarehouseRepository;

    public function __construct(
        PurchaseOrderRepositoryInterface $purchaseOrderRepository,
        HistoryRepository $historyRepository,
        UserRepository $userRepository,
        WarehouseRepository $warehouseRepository,
        ProductWarehouseRepository $productWarehouseRepository
    )
    {
        $this->purchaseOrderRepository = $purchaseOrderRepository;
        $this->historyRepository = $historyRepository;
        $this->userRepository = $userRepository;
        $this->warehouseRepository = $warehouseRepository;
        $this->productWarehouseRepository = $productWarehouseRepository;
    }

    public function paginate($payload = []) {
        $condition = [];
        $limit = 20;
        if(isset($payload['limit'])) {
            $limit = $payload['limit'];
        }
        $fieldSelects = $this->fieldSelect();
        if(isset($payload['status']) && $payload['status'] !== 'none') {
            $condition[] = ['status', '=', $payload['status']];
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
        return $this->purchaseOrderRepository->pagination($fieldSelects, 
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
            $payloadProducts = $payload['products'];
            unset($payload['products']);
            $payload['code'] = createCode('PO');
            $payload['expected_day'] = $payload['expected_day'] ? formatDateTimeToSql($payload['expected_day']) : null;
            $purchaseOrder = $this->purchaseOrderRepository->create($payload);
            if($purchaseOrder->id) {
                $payloadProductPurchaseOrder = $this->setupPayloadProductPurchaseOrder($payloadProducts);
                $purchaseOrder->products()->attach($payloadProductPurchaseOrder);
                $payload['id'] = $purchaseOrder->id;
                $payload['content'] = '<span>Thêm mới đơn đặt hàng nhập <a class="text-primary" href="#">'. $payload['code'] .'</a></span>';
                $payloadHistory = $this->setupPayloadHistory($payload);
                $this->historyRepository->create($payloadHistory);
            }
            DB::commit();
            return $purchaseOrder;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }

    private function setupPayloadHistory($payload) {
        $userName = $this->userRepository->findById($payload['user_id'])->name;
        $payloadHistory = [
            'model' => 'PurchaseOrder',
            'model_id' => $payload['id'],
            'user_id' => $payload['user_id'],
            'content' => $payload['content'],
            'user_name' => $userName
        ];
        return $payloadHistory;
    }

    private function setupPayloadProductPurchaseOrder($payload) {
        $temp = [];
        foreach($payload as $index => $item) {
            $newItem = $item;
            $detail = json_decode($item['detail'], true);
            unset($newItem['detail']);
            foreach($detail as $key => $val) {
                $newItem[$key] = $val;
            }
            $temp[] = $newItem;
        }
        return $temp;
    }

    public function update ($id, $payload) {
        DB::beginTransaction();
        try {
            $payloadProducts = $payload['products'];
            unset($payload['products']);
            $payload['expected_day'] = $payload['expected_day'] ? formatDateTimeToSql($payload['expected_day']) : null;
            $flag = $this->purchaseOrderRepository->updateById($id, $payload);
            if($flag) {
                $payloadProductPurchaseOrder = $this->setupPayloadProductPurchaseOrder($payloadProducts);
                $purchaseOrder = $this->purchaseOrderRepository->findById($id);
                $purchaseOrder->products()->sync($payloadProductPurchaseOrder);
                $payload['id'] = $id;
                $payload['content'] = '<span>Cập nhật đơn đặt hàng nhập <a class="text-primary" href="#">'. $payload['code'] .'</a></span>';
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

    public function confirm($id, $payload) {
        DB::beginTransaction();
        try {
            //purchase_orders -> Cập nhật trạng thái 
            $this->purchaseOrderRepository->updateById($id, ['status' => $payload['status']]);
            //histories -> Lưu lại lịch sử duyệt đơn đặt hàng nhập
            $payloadHistory = $this->setupPayloadHistory($payload);
            $this->historyRepository->create($payloadHistory);
            //product_warehouse -> Cập nhật lại số lượng hàng đang về cho mỗi sản phẩm cho kho được chọn
            $payloadProducts = $payload['products'];
            $payloadProductReceiveInventory = $this->setupPayloadProductReceiveInventory($payloadProducts);
            $payloadProductWarehouse = $this->setupPayloadProductWarehouse($payloadProductReceiveInventory, $payloadProducts, $payload);
            $this->productWarehouseRepository->multipleUpdate($payloadProductWarehouse, 'product_warehouse', ['incoming'], 'warehouse_id');
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
        foreach($data->products->toArray() as $index => $item) {
            $variantId = $item['pivot']['product_variant_id'];
            if($variantId == null || in_array($variantId, $productVariantIds)) {
                $temp = $item['pivot'];
                $key = $item['id'] . ($variantId !== null ? ('-'.$variantId) : '');
                if(isset($payload['current_status']) && $payload['current_status'] == 'pending_processing') {
                    $temp['incoming'] = $item['pivot']['incoming'] - $payloadProducts[$key]['quantity'];
                }else{
                    $temp['incoming'] = $item['pivot']['incoming'] + $payloadProducts[$key]['quantity'];
                }
                $payloadProductWarehouse[] = $temp;
            }
        }
        return $payloadProductWarehouse;
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

    public function cancel($id, $payload) {
        DB::beginTransaction();
        try {
            //purchase_orders -> Cập nhật trạng thái (trạng thái đơn đã bị hủy)
            $this->purchaseOrderRepository->updateById($id, ['status' => $payload['status']]);
            //histories -> Lưu lại lịch sử hủy đơn
            $payloadHistory = $this->setupPayloadHistory($payload);
            $this->historyRepository->create($payloadHistory);
            //product_warehouse -> Nếu đơn đặt ở trạng thái chờ nhập, 
            //khi hủy đơn ta cần giảm số lượng hàng đang về tương ứng của mỗi sản phẩm trong đơn đặt
            if($payload['current_status'] == 'pending_processing') {
                $payloadProducts = $payload['products'];
                $payloadProductReceiveInventory = $this->setupPayloadProductReceiveInventory($payloadProducts);
                $payloadProductWarehouse = $this->setupPayloadProductWarehouse($payloadProductReceiveInventory, $payloadProducts, $payload);
                $this->productWarehouseRepository->multipleUpdate($payloadProductWarehouse, 'product_warehouse', ['incoming'], 'warehouse_id');
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
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
            'status',
            'user_id',
            'supplier_id',
            'warehouse_id',
            'created_at',
        ];
    }
}
