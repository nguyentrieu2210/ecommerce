<?php

namespace App\Repositories;

use App\Models\Tax;
use App\Repositories\Interfaces\TaxRepositoryInterface;

class TaxRepository extends BaseRepository implements TaxRepositoryInterface
{
    protected $model;

    public function __construct(Tax $model)
    {
        $this->model = $model;
    }
}
