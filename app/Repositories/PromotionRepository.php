<?php

namespace App\Repositories;

use App\Models\Promotion;
use App\Repositories\Interfaces\PromotionRepositoryInterface;

class PromotionRepository extends BaseRepository implements PromotionRepositoryInterface
{
    protected $model;

    public function __construct(Promotion $model)
    {
        $this->model = $model;
    }

    public function findPromotionForProductCatalogue ($condition = []) {
        $query = $this->model;
        if(count($condition)) {
            foreach($condition as $key => $val) {
                $query = $query->where($val[0], $val[1], $val[2]);
            }
        }
        $query->with(['productCatalogues' => function ($query) {
            $query->with('descendants');
        }]);
        return $query->get();
    }
}
