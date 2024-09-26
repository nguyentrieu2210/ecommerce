<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\InventoryServiceInterface as InventoryService;
use Illuminate\Support\Facades\Gate;
use App\Repositories\Interfaces\ProductVariantRepositoryInterface as ProductVariantRepository;
use App\Repositories\Interfaces\WarehouseRepositoryInterface as WarehouseRepository;

class InventoryController extends Controller
{
    //

    protected $inventoryService;
    protected $productVariantRepository;
    protected $warehouseRepository;

    public function __construct(
        InventoryService $inventoryService,
        ProductVariantRepository $productVariantRepository,
        WarehouseRepository $warehouseRepository
    )
    {
        $this->inventoryService = $inventoryService;
        $this->productVariantRepository = $productVariantRepository;
        $this->warehouseRepository = $warehouseRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'inventory.index')) {
            $payload = $request->query();
            $warehouse = $this->inventoryService->paginate($payload);
            $config = $this->config();
            $config['method'] = 'index';
            $warehouses = $this->warehouseRepository->findByCondition([
                ['publish', '=', 2]
            ], ['id', 'DESC']);
            // dd($warehouse);
            return view('backend.warehouse.inventory.index', compact(
                'config',
                'warehouse',
                'warehouses'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    private function config () {
        return [
            'js' => [
                '/backend/js/library/library.js',
            ],
            'module' => 'inventory',
        ];
    }
}
