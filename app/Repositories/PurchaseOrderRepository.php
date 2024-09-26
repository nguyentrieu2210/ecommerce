<?php

namespace App\Repositories;

use App\Models\PurchaseOrder;
use App\Repositories\Interfaces\PurchaseOrderRepositoryInterface;

class PurchaseOrderRepository extends BaseRepository implements PurchaseOrderRepositoryInterface
{
    protected $model;

    public function __construct(PurchaseOrder $model)
    {
        $this->model = $model;
    }
}
