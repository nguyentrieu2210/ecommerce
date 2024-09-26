<?php

namespace App\Repositories;

use App\Models\Coupon;
use App\Repositories\Interfaces\CouponRepositoryInterface;

class CouponRepository extends BaseRepository implements CouponRepositoryInterface
{
    protected $model;

    public function __construct(Coupon $model)
    {
        $this->model = $model;
    }
}
