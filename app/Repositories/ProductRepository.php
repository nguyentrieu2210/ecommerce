<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function getProductByWarehouse($warehouse_id = 0, $keyword = '', $fieldSearchs = [], $order = ['id', 'DESC']) {
        $query = $this->model->with('product_variants')->join('product_warehouse', 'products.id', '=', 'product_warehouse.product_id')
            ->select('products.name', 
                'products.id', 'products.image', 
                'products.code', 'products.cost', 
                'product_warehouse.warehouse_id',
                'product_warehouse.quantity', 
                'product_warehouse.stock',
                'product_warehouse.product_variant_id')
            ->where('products.publish', 2)
            ->where('product_warehouse.warehouse_id','=',  $warehouse_id)
            ->where(function($query) use ($keyword, $fieldSearchs) {
                $query->where('products.name', 'LIKE', '%' . $keyword . '%');
    
                if (count($fieldSearchs)) {
                    foreach ($fieldSearchs as $val) {
                        $query->orWhere('products.' . $val, 'LIKE', '%' . $keyword . '%');
                    }
                }
            });
        return $query->orderBy($order[0], $order[1])->get();
    }
}
