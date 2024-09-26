<?php

namespace App\Repositories;

use App\Models\PostCataloguePost;
use App\Repositories\Interfaces\PostCataloguePostRepositoryInterface;

class PostCataloguePostRepository extends BaseRepository implements PostCataloguePostRepositoryInterface
{
    protected $model;

    public function __construct(PostCataloguePost $model)
    {
        $this->model = $model;
    }
}
