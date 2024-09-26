<?php

namespace App\Repositories;

use App\Models\Album;
use App\Repositories\Interfaces\AlbumRepositoryInterface;

class AlbumRepository extends BaseRepository implements AlbumRepositoryInterface
{
    protected $model;

    public function __construct(Album $model)
    {
        $this->model = $model;
    }
}
