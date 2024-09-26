<?php

namespace App\Repositories;

use App\Models\PaymentRecord;
use App\Repositories\Interfaces\PaymentRecordRepositoryInterface;

class PaymentRecordRepository extends BaseRepository implements PaymentRecordRepositoryInterface
{
    protected $model;

    public function __construct(PaymentRecord $model)
    {
        $this->model = $model;
    }
}
