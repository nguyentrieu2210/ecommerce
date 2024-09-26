<?php

namespace App\Repositories;

use App\Models\ProductWarehouse;
use App\Repositories\Interfaces\ProductWarehouseRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ProductWarehouseRepository extends BaseRepository implements ProductWarehouseRepositoryInterface
{
    protected $model;

    public function __construct(ProductWarehouse $model)
    {
        $this->model = $model;
    }
}
