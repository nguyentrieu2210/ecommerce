<?php

namespace App\Repositories;

use App\Models\ProductVariantAttribute;
use App\Repositories\Interfaces\ProductPurchaseOrderRepositoryInterface;

class ProductPurchaseOrderRepository extends BaseRepository implements ProductPurchaseOrderRepositoryInterface
{
    protected $model;

    public function __construct(ProductVariantAttribute $model)
    {
        $this->model = $model;
    }
}
