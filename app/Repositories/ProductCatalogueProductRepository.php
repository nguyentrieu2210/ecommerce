<?php

namespace App\Repositories;

use App\Models\ProductCatalogueProduct;
use App\Repositories\Interfaces\ProductCatalogueProductRepositoryInterface;

class ProductCatalogueProductRepository extends BaseRepository implements ProductCatalogueProductRepositoryInterface
{
    protected $model;

    public function __construct(ProductCatalogueProduct $model)
    {
        $this->model = $model;
    }
}
