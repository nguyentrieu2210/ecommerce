<?php

namespace App\Repositories;

use App\Models\Slide;
use App\Repositories\Interfaces\SlideRepositoryInterface;

class SlideRepository extends BaseRepository implements SlideRepositoryInterface
{
    protected $model;

    public function __construct(Slide $model)
    {
        $this->model = $model;
    }
}
