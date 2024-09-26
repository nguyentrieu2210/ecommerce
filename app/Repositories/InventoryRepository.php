<?php

namespace App\Repositories;

use App\Models\Warehouse;
use App\Repositories\Interfaces\InventoryRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class InventoryRepository extends BaseRepository implements InventoryRepositoryInterface
{
    protected $model;
    public $method = 'whereDate';

    public function __construct(Warehouse $model)
    {
        $this->model = $model;
    }

    public function pagination($fieldSelect, $condition, $relations = [], $limit = 20, $orderBy = ['id', 'DESC'], $searchFields = ['name'])
    {
        $query = $this->model->select($fieldSelect);
        if(count($relations)) {
            foreach($relations as $relation) {
                $query = $query->with($relation);
            }
        }
        $query->orderBy($orderBy[0], $orderBy[1]);
        $rawWhere = '';
        if(isset($condition['inventoryStatus'])) {
            $rawWhere = $condition['inventoryStatus'];
            unset($condition['inventoryStatus']);
        }
        $keyword = '';
        if(isset($condition['keyword'])) {
            $keyword = $condition['keyword'];
            unset($condition['keyword']);
        }
        $createdDay = [];
        if(isset($condition['createdDay'])) {
            $createdDay = $condition['createdDay'];
            unset($condition['createdDay']);
        }
        $conditionPivot = [];
        if(isset($condition['startQuantity'])) {
            $conditionPivot[] = $condition['startQuantity'];
            unset($condition['startQuantity']);
        }
        if(isset($condition['endQuantity'])) {
            $conditionPivot[] = $condition['endQuantity'];
            unset($condition['endQuantity']);
        }
        $query = $query->with(['products' => function($query) use($rawWhere, $searchFields, $keyword, $limit, $createdDay, $conditionPivot) {
            if($rawWhere !== '') {
                $query->whereRaw($rawWhere);
            }
            if($keyword !== '') {
                foreach($searchFields as $key => $val) {
                    if($key == 0) {
                        $query->where($val, 'LIKE', '%'. $keyword . '%');
                    }else{
                        $query->orWhere($val, 'LIKE', '%'. $keyword . '%');
                    }
                }
            }
            if(count($createdDay)) {
                foreach($createdDay as $key => $val) {
                    if($key == 'whereDate') {
                        $query->whereDate($val[0], $val[1], $val[2]);
                    }
                    if($key == 'whereBetween') {
                        $query->whereBetween($val[0], $val[1]);
                    }
                    if($key == 'whereMonth') {
                        $query->whereMonth($val[0], $val[1]);
                    }
                    if($key == 'whereYear') {
                        $query->whereYear($val[0], $val[1]);
                    }
                }
            }
            if(count($conditionPivot)) {
                foreach($conditionPivot as $item) {
                    $query->wherePivot($item[0], $item[1], $item[2]);
                }
            }
            $query->paginate($limit);
        }]);
        if(count($condition)) {
            foreach($condition as $item) {
                $query = $query->where($item[0], $item[1], $item[2]);
            }
        }
        return $query->first();
    }

}
