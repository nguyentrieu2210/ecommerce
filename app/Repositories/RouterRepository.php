<?php

namespace App\Repositories;

use App\Models\Router;
use App\Repositories\Interfaces\RouterRepositoryInterface;

class RouterRepository extends BaseRepository implements RouterRepositoryInterface
{
    protected $model;

    public function __construct(Router $model)
    {
        $this->model = $model;
    }
}
