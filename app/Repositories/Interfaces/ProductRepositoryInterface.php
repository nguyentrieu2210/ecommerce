<?php

namespace App\Repositories\Interfaces;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function getProductByWarehouse($warehouse_id = 0, $keyword = '', $fieldSearchs = [], $order = ['id', 'DESC']);
}
