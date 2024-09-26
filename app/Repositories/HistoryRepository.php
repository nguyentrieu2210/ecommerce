<?php

namespace App\Repositories;

use App\Models\History;
use App\Repositories\Interfaces\HistoryRepositoryInterface;

class HistoryRepository extends BaseRepository implements HistoryRepositoryInterface
{
    protected $model;

    public function __construct(History $model)
    {
        $this->model = $model;
    }
}
