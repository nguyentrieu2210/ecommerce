<?php

namespace App\Repositories;

use App\Models\AttributeCatalogueAttribute;
use App\Repositories\Interfaces\AttributeCatalogueAttributeRepositoryInterface;

class AttributeCatalogueAttributeRepository extends BaseRepository implements AttributeCatalogueAttributeRepositoryInterface
{
    protected $model;

    public function __construct(AttributeCatalogueAttribute $model)
    {
        $this->model = $model;
    }
}
