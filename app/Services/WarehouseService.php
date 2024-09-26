<?php

namespace App\Services;

use App\Services\Interfaces\WarehouseServiceInterface;
use App\Repositories\Interfaces\WarehouseRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
 * Class WarehouseService
 * @package App\Services
 */
class WarehouseService implements WarehouseServiceInterface
{

    protected $warehouseRepository;

    public function __construct(
        WarehouseRepositoryInterface $warehouseRepository
    )
    {
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
        return $this->warehouseRepository->pagination($fieldSelects, 
                                                $condition, 
                                                ['products', 'users'], 
                                                $limit, 
                                                ['id', 'DESC'],
                                                ['name','code', 'address', 'phone']
                                            );
    }

    public function store ($payload = []) {
        DB::beginTransaction();
        try {
            $warehouse = $this->warehouseRepository->create($payload);
            DB::commit();
            return $warehouse;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }

    public function update ($id, $payload) {
        DB::beginTransaction();
        try {
            $flag = $this->warehouseRepository->updateById($id, $payload);
            DB::commit();
            return $flag;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
    }

    public function destroy($id) {
        DB::beginTransaction();
        try {
            $flag = $this->warehouseRepository->destroy($id);
            DB::commit();
            return $flag;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
    }

    private function fieldSelect () {
        return [
            'id',
            'name',
            'code',
            'phone',
            'user_id',
            'description',
            'address',
            'publish',
        ];
    }
}
