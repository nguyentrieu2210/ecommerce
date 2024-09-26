<?php

namespace App\Repositories\Interfaces;

interface BaseRepositoryInterface
{
    public function getCount($conditions = []);
    public function getAll($relation = []);
    public function pagination($fieldSelect, $condition, $relations = [], $limit, $orderBy = ['id', 'DESC']);
    public function findById ($id, $relation = "");
    public function findByIdAndMultipleRelation ($id, $relations = []);
    public function findByConditionPivot ($condition = [], $relation = '', $whereInRelation = [], $whereInPivot = [], $conditionRelation = [], $conditionPivot = [], $orderBy = ['id', 'DESC'], $subRelation = [], $paginateRelation = null);
    public function create($payload);
    public function createMany($payload = []);
    public function update($data = []);
    public function updateById($id = 0, $data = []);
    public function updateByWhereIn ($field = "", $arrField = [], $dataUpdate = []);
    public function updateByCondition ($data = [], $condition = []);
    public function multipleUpdate ($payload = [], $nameTable = '', $fieldUpdates = [], $fieldId = '');
    public function updateOrCreate($condition = [], $data = []);
    public function upsert($payload, $fieldKey = ['id'], $fields = []);
    public function selectByField ($field = []);
    public function findByCondition ($condition = [], $order = [], $fieldWhereIn = '', $arrWhereIn = [], $keyword = '', $fieldSearchs = [], $relations = [], $limit = null);
    public function findAncestorsAndSelfByWhereIn ($fieldWhereIn = '', $arrWhereIn = []);
    public function destroy ($id);
    public function delete ();
    public function destroyByCondition ($condition = []);
}
