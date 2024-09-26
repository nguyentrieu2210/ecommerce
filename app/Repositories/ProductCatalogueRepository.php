<?php

namespace App\Repositories;

use App\Models\ProductCatalogue;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface;

class ProductCatalogueRepository extends BaseRepository implements ProductCatalogueRepositoryInterface
{
    protected $model;

    public function __construct(ProductCatalogue $model)
    {
        $this->model = $model;
    }
}
