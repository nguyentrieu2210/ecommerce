<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
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
        
        if(isset($condition['keyword'])) {
            $keyword = $condition['keyword'];
            $query->where(function($query) use ($searchFields, $keyword) {
                foreach($searchFields as $key => $val) {
                    $query->orWhere($val, 'LIKE', '%'. $keyword . '%');
                }
            });
            unset($condition['keyword']);
        }
        if(isset($condition['searchByCatalogue'])) {
            $query->whereIn('id', $condition['searchByCatalogue']); 
            unset($condition['searchByCatalogue']);
        }
        if(isset($condition['createdDay'])) {
            foreach($condition['createdDay'] as $key => $val) {
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
            unset($condition['createdDay']);
        }
        if(count($condition)) {
            $query->where($condition);
        }
        // dd($query->toSql());
        return $query->paginate($limit);
    }

    public function getAll($relation = []) {
        $query = $this->model;
        if(count($relation)) {
            $query = $query->with($relation);
        }
        return $query->get();
    }

    public function getCount($conditions = []) {
        $query = $this->model;
        if(count($conditions)) {
            foreach($conditions as $item) {
                $query = $query->where($item[0], $item[1], $item[2]);
            }
        }
        return $query->count();
    }

    public function create($payload) {
        return $this->model->create($payload);
    }

    public function createMany($payload = []) {
        return $this->model->insert($payload);
    }

    public function findById ($id, $relation = "") {
        $query = $this->model;
        if($relation !== "") {
            $query = $query->with($relation);
        }
        return $query->where('id', $id)->first();
    }

    public function findByIdAndMultipleRelation ($id, $relations = []) {
        $query = $this->model;
        if(count($relations)) {
            foreach($relations as $val) {
                $query = $query->with($val);
            }
        }
        return $query->where('id', $id)->first();
    }

    public function findByCondition ($condition = [], $order = [], $fieldWhereIn = '', $arrWhereIn = [], $keyword = '', $fieldSearchs = [], $relations = [], $limit = null) {
        $query = $this->model;
        if(count($condition)) {
            foreach($condition as $key => $val) {
                $query = $query->where($val[0], $val[1], $val[2]);
            }
        }
        $query->where(function($query) use ($keyword, $fieldSearchs) {
            if(count($fieldSearchs)) {
                foreach($fieldSearchs as $key => $val) {
                    $query->orWhere($val, 'LIKE', '%'. $keyword . '%');
                }
            }
        });
        if(count($arrWhereIn)) {
            $query = $query->whereIn($fieldWhereIn, $arrWhereIn);
        }
        if(!empty($order)) {
            $query = $query->orderBy($order[0], $order[1]);
        }
        if(count($relations)) {
            foreach($relations as $val) {
                $query = $query->with($val);
            }
        }
        return $limit !== null ? $query->limit($limit)->get() : $query->get();
    }

    public function findByConditionPivot ($condition = [], $relation = '', $whereInRelation = [], $whereInPivot = [], $conditionRelation = [], $conditionPivot = [], $orderBy = ['id', 'DESC'], $subRelation = [], $paginateRelation = null) {
        $query = $this->model;
        if(count($condition)) {
            foreach($condition as $key => $val) {
                $query = $query->where($val[0], $val[1], $val[2]);
            }
        }
        $query->with([$relation => function ($query) use ($conditionRelation, $whereInRelation, $conditionPivot, $whereInPivot, $orderBy, $subRelation, $paginateRelation) {
            if(count(($conditionPivot))) {
                foreach($conditionPivot as $key => $val) {
                    $query = $query->wherePivot($val[0], $val[1], $val[2]);
                }
            }
            if(count($whereInPivot)) {
                foreach($whereInPivot as $key => $val) {
                    $query = $query->wherePivotIn($key, $val);
                }
            }
            if(count($conditionRelation)) {
                foreach($conditionRelation as $key => $val) {
                    $query = $query->where($val[0], $val[1], $val[2]);
                }
            }
            if(count($whereInRelation)) {
                foreach($whereInRelation as $key => $val) {
                    $query = $query->whereIn($key, $val);
                }
            }
            if(count($subRelation)) {
                foreach($subRelation as $item) {
                    $query = $query->with($item);
                }
            }
            $query->orderBy($orderBy[0], $orderBy[1]);
            if($paginateRelation !== null) {
                $query->paginate($paginateRelation);
            }
        }]);
        return $query->first();
    }

    public function findAncestorsAndSelfByWhereIn ($fieldWhereIn = '', $arrWhereIn = [], $relation = '', $conditionRelation = []) {

        $query = $this->model->whereIn($fieldWhereIn, $arrWhereIn)->with('ancestors');
        if($relation !== '') {
            $query->with([$relation => function ($query) use ($conditionRelation) {
                if(!empty($conditionRelation)) {
                    foreach($conditionRelation as $key => $item) {
                        $query = $query->where($item[0], $item[1], $item[2]);
                    }
                }
            }]);
        }
        return $query->get();
    }

    public function updateById($id = 0, $data = []) {
        return $this->model->where('id', $id)
            ->update($data);
    }

    public function update($data = []) {
        return $this->model->update($data);
    }

    public function updateByWhereIn ($field = "", $arrField = [], $dataUpdate = []) {
        return $this->model->whereIn($field, $arrField)->update($dataUpdate);
    }

    public function updateByCondition ($data = [], $condition = []) {
        $query = $this->model;
        if(count($condition)) {
            foreach($condition as $key => $val) {
                $query = $query->where($val[0], $val[1], $val[2]);
            }
        }
        return $query->update($data);
    }

    public function updateOrCreate($condition = [], $data = []) {
        return $this->model->updateOrCreate($condition, $data);
    }

    public function upsert($payload, $fieldKey = ['id'], $fields = []) {
        return $this->model->upsert($payload, $fieldKey, $fields);
    }

    public function multipleUpdate ($payload = [], $nameTable = '', $fieldUpdates = [], $fieldId = '') {
        for($i = 0; $i < count($fieldUpdates); $i++) {
            ${"field" . $i} = [];
        }
        $conditions = [];
        foreach($payload as $key => $item) {
            $id = $item[$fieldId];
            $productId = $item['product_id'];
            $productVariantId = $item['product_variant_id'];
            for($i = 0; $i < count($fieldUpdates); $i++) {
                ${"value" . $i} = $item[$fieldUpdates[$i]];
            }
            if(is_null($productVariantId)) {
                for($i = 0; $i <count($fieldUpdates); $i++) {
                    ${"field" . $i}[] = 'WHEN '. $fieldId .' = '.$id .' AND product_id = '.$productId.' AND product_variant_id IS NULL THEN '.${"value" . $i};
                }
                $conditions[] = '('. $fieldId .' = '.$id .' AND product_id = '.$productId.' AND product_variant_id IS NULL)';
            }else{
                for($i = 0; $i < count($fieldUpdates); $i++) {
                    ${"field" . $i}[] = 'WHEN '. $fieldId .' = '.$id .' AND product_id = '.$productId.' AND product_variant_id = '.$productVariantId.' THEN '.${"value" . $i};
                }
                $conditions[] = '('. $fieldId .' = '.$id .' AND product_id = '.$productId.' AND product_variant_id = '.$productVariantId.')';
            }
        }
        $sql = 'UPDATE '.$nameTable.' SET ';
        for($i = 0; $i < count($fieldUpdates); $i++) {
            $sql .= $fieldUpdates[$i] . ' = CASE ' . implode(' ', ${"field" . $i}) . ' END' . (($i < count($fieldUpdates) - 1) ? ', ' : ' ');
        }
        $sql .= 'WHERE ' . implode(' OR ', $conditions);
        DB::statement($sql);
    }

    public function selectByField ($field = []) {
        return $this->model->select($field)->get();
    }

    public function destroy ($id) {
        return $this->model->where('id', $id)->delete();
    }

    public function delete () {
        return $this->model->delete();
    }


    public function destroyByCondition ($condition = []) {
        $query = $this->model;
        if(count($condition)) {
            foreach($condition as $key => $val) {
                $query = $query->where($val[0], $val[1], $val[2]);
            }
        }
        return $query->delete();
    }
}
