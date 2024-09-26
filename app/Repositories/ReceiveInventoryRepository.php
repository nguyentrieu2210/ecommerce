<?php

namespace App\Repositories;

use App\Models\ReceiveInventory;
use App\Repositories\Interfaces\ReceiveInventoryRepositoryInterface;

class ReceiveInventoryRepository extends BaseRepository implements ReceiveInventoryRepositoryInterface
{
    protected $model;

    public function __construct(ReceiveInventory $model)
    {
        $this->model = $model;
    }
}

