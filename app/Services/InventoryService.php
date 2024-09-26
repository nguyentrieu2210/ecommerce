<?php

namespace App\Services;

use App\Services\Interfaces\InventoryServiceInterface;
use App\Repositories\Interfaces\InventoryRepositoryInterface as InventoryRepository;
use App\Repositories\Interfaces\WarehouseRepositoryInterface as WarehouseRepository;

/**
 * Class InventoryService
 * @package App\Services
 */
class InventoryService implements InventoryServiceInterface
{

    protected $inventoryRepository;
    protected $warehouseRepository;

    public function __construct(
        InventoryRepository $inventoryRepository,
        WarehouseRepository $warehouseRepository
    )
    {
        $this->inventoryRepository = $inventoryRepository;
        $this->warehouseRepository = $warehouseRepository;
    }

    public function paginate($payload = []) {
        $condition = [];
        $limit = 20;
        if(isset($payload['limit'])) {
            $limit = $payload['limit'];
        }
        $fieldSelects = $this->fieldSelect();
        if(isset($payload['publish']) && (int) $payload['publish'] !== 0) {
            $condition[] = ['publish', '=', $payload['publish']];
        }
        if(isset($payload['keyword']) && $payload['keyword'] !== "") {
            $condition['keyword'] = $payload['keyword'];
        }
        if(isset($payload['warehouse'])) {
            $condition['warehouse'] = ['id', '=', $payload['warehouse']];
        }else{
            $condition['warehouse'] = ['id', '=', $this->inventoryRepository->findByCondition([['publish', '=', 2]], ['id', 'DESC'])->first()->id] ;
        }
        if(isset($payload['inventoryStatus']) && $payload['inventoryStatus'] !== "0") {
            if($payload['inventoryStatus'] == 1) {
                $condition['inventoryStatus'] = 'quantity - stock > 0';
            }else{
                $condition['inventoryStatus'] = 'quantity - stock = 0';
            }
        }
        if(isset($payload['startQuantity']) && $payload['startQuantity'] !== '') {
            $condition['startQuantity'] = ['quantity', '>=', formatNumberToSql($payload['startQuantity'])];
        }
        if(isset($payload['endQuantity']) && $payload['endQuantity'] !== '') {
            $condition['endQuantity'] = ['quantity', '<=', formatNumberToSql($payload['endQuantity'])];
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
        return $this->inventoryRepository->pagination($fieldSelects, 
                                                $condition, 
                                                [], 
                                                $limit, 
                                                ['id', 'DESC'],
                                                ['name','barcode']
                                            );
    }
    private function fieldSelect () {
        return [
            'id',
            'name',
            'code',
        ];
    }
}
