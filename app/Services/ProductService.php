<?php

namespace App\Services;

use App\Services\Interfaces\ProductServiceInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use App\Repositories\Interfaces\ProductCatalogueProductRepositoryInterface as ProductCatalogueProductRepository;
use App\Repositories\Interfaces\ProductVariantAttributeRepositoryInterface as ProductVariantAttributeRepository;
use App\Repositories\Interfaces\ProductVariantRepositoryInterface as ProductVariantRepository;
use App\Repositories\Interfaces\AlbumRepositoryInterface as AlbumRepository;
use App\Repositories\Interfaces\ProductWarehouseRepositoryInterface as ProductWarehouseRepository;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface as AttributeCatalogueRepository;
use App\Repositories\Interfaces\PromotionRepositoryInterface as PromotionRepository;

/**a
 * Class ProductService
 * @package App\Services
 */
class ProductService implements ProductServiceInterface
{

    protected $productRepository;
    protected $routerRepository;
    protected $productCatalogueRepository;
    protected $productCatalogueproductRepository;
    protected $productVariantAttributeRepository;
    protected $productVariantRepository;
    protected $albumRepository;
    protected $productWarehouseRepository;
    protected $attributeCatalogueRepository;
    protected $promotionRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        RouterRepository $routerRepository,
        ProductCatalogueRepository $productCatalogueRepository,
        ProductCatalogueProductRepository $productCatalogueproductRepository,
        ProductVariantAttributeRepository $productVariantAttributeRepository,
        ProductVariantRepository $productVariantRepository,
        AlbumRepository $albumRepository,
        ProductWarehouseRepository $productWarehouseRepository,
        AttributeCatalogueRepository $attributeCatalogueRepository,
        PromotionRepository $promotionRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->routerRepository = $routerRepository;
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->productCatalogueproductRepository = $productCatalogueproductRepository;
        $this->productVariantAttributeRepository = $productVariantAttributeRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->albumRepository = $albumRepository;
        $this->productWarehouseRepository = $productWarehouseRepository;
        $this->promotionRepository = $promotionRepository;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
    }

    public function paginate($payload = []) {
        $condition = [];
        $limit = 20;
        if(isset($payload['limit'])) {
            $limit = $payload['limit'];
        }
        $fieldSelects = $this->fieldSelect();
        if(isset($payload['publish']) && (int) $payload['publish'] !== 0) {
            $condition[] = ['publish', '=', $payload['publish']];
        }
        if(isset($payload['keyword']) && $payload['keyword'] !== "") {
            $condition['keyword'] = $payload['keyword'];
        }
        if(isset($payload['product_catalogue_id']) && (int) $payload['product_catalogue_id'] !== 0) {
            $productCatalogueId = $payload['product_catalogue_id'];
            $productCatalogue = $this->productCatalogueRepository->findById($productCatalogueId);
            $childrenIds = $productCatalogue->descendantsAndSelf($productCatalogueId)->pluck('id');
            $productIds = array_unique($this->productCatalogueproductRepository->findByCondition([], [], 'product_catalogue_id', $childrenIds)->pluck('product_id')->toArray());
            $condition['searchByCatalogue'] = $productIds;
        }

        return $this->productRepository->pagination($fieldSelects, 
                                                $condition, 
                                                ['product_catalogues', 'product_variants'], 
                                                $limit, 
                                                ['id', 'DESC'],
                                                ['name', 'description']
                                            );
    }

    public function store ($payload = []) {
        DB::beginTransaction();
        try {
            // dd($payload);
            $payload['user_id'] = Auth::id();
            $productCatalogueIds = isset($payload['product_catalogue_id_extra']) ? $payload['product_catalogue_id_extra'] : [];
            $productCatalogueIds[] = $payload['product_catalogue_id'];
            unset($payload['product_catalogue_id_extra']);
            $payload['weight'] = formatNumberToSql($payload['weight']);
            //products
            $payloadProduct = $this->setupPayloadProduct($payload);
            $product = $this->productRepository->create($payloadProduct);
            if($product->id) {
                //routers
                $router = $this->createPayloadRouter($payload['canonical'], $product->id);
                //product_post
                $product->posts()->sync(json_decode($payload['post_id']));
                //product_catalogue_product
                $product->product_catalogues()->sync($productCatalogueIds);
                //NHIỀU PHIÊN BẢN
                if($payload['type_product'] == "1") {
                    //product_variants
                    $payloadVariant = $this->setupPayloadVariant($payload['variant']);
                    $variants = $product->product_variants()->createMany($payloadVariant);
                    //product_variant_attribute
                    $productVariantAttributes = $this->createPayloadProductVariantAttribute($variants);
                    //product_warehouse
                    $payloadProductWarehouse = $this->setupProductWarehouse($payload, $variants);
                    $productWarehouse = $product->warehouses()->sync($payloadProductWarehouse);
                    //albums
                    $payloadAlbums = $this->setupAlbum($payload['album_variant']);
                    $albums = $product->albums()->createMany($payloadAlbums);
                }else{
                    //1 phiên bản
                    $payloadWarehouse = $this->setupSingleProductWarehouse($payload);
                    $productWarehouses = $product->warehouses()->sync($payloadWarehouse);
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }

    private function setupSingleProductWarehouse($payload) {
        $payloadWarehouse = [];
        foreach($payload['quantity'] as $key => $val) {
            $payloadWarehouse[] = [
                'warehouse_id' => $key,
                'quantity' => formatNumberToSql($val),
                'cost_price' => formatNumberToSql($payload['cost_price'][$key])
            ];
        }
        return $payloadWarehouse;
    }

    private function setupAlbum($payload) {
        $payloadAlbums = [];
        foreach($payload['attribute_id'] as $index => $val) {
            $payloadAlbums[] = [
                'attribute_catalogue_id' => $payload['attribute_catalogue_id'],
                'attribute_id' => $payload['attribute_id'][$index],
                'image' => $payload['image'][$index],
                'album' => $payload['album'][$val] ?? null,
                'attribute_name' => $payload['name'][$index]
            ];
        }
        return $payloadAlbums;
    }

    private function setupProductWarehouse($payload, $variants) {
        $productVariantIds = $variants->pluck('id');
        $payloadProductWarehouse = [];
        foreach($productVariantIds as $index => $val) {
            foreach($payload['variant']['quantity'][$index] as $key => $item) {
                $payloadProductWarehouse[] = [
                    'warehouse_id' => $key,
                    'product_variant_id' => $val,
                    'quantity' => (int)formatNumberToSql($item ?? 0),
                    'cost_price' => formatNumberToSql($payload['variant']['cost_price'][$index][$key] ?? 0) ,
                ];
            }
        }
        return $payloadProductWarehouse;
    }

    private function createPayloadProductVariantAttribute($variants) {
        $variantIds = $variants->pluck('id');
        $variantCodes = $variants->pluck('code');
        $variantAttributes = [];
        foreach($variantCodes as $key => $item) {
            $temp = explode(" ", trim($item));
            $variantAttributes[] = $temp;
        }
        $payloadProductVariantAttribute = [];
        foreach($variantIds as $index => $id) {
            foreach($variantAttributes[$index] as $attribute) {
                $payloadProductVariantAttribute[] = [
                    'product_variant_id' => $id,
                    'attribute_id' => $attribute
                ];
            }
        }
        return $this->productVariantAttributeRepository->createMany($payloadProductVariantAttribute);
    }

    private function setupPayloadVariant($payload) {
        unset($payload['quantity']);
        unset($payload['cost_price']);
        $code = $payload['code'];
        $payload['code'] = [];
        foreach($code as $index => $value) {
            $payload['code'][] = setupCodeFromSkuVariant($value);
        }
        $payloadVariant = [];
        foreach ($payload as $key => $values) {
            foreach ($values as $index => $value) {
                if ($key === 'price' || $key === 'cost' || $key === 'weight') {
                    $value = formatNumberToSql($value);
                }
                if (!isset($payloadVariant[$index])) {
                    $payloadVariant[$index] = [];
                }
                $payloadVariant[$index][$key] = $value;
            }
        }
        return $payloadVariant;
    }

    private function setupPayloadProduct($payload) {
        $payload['price'] = formatNumberToSql($payload['price']);
        $payload['cost'] = formatNumberToSql($payload['cost']);
        if(isset($payload['attribute_catalogue_id']) && $payload['attribute_catalogue_id'][0] !== "0") {
            $payload['attribute_catalogue'] = $payload['attribute_catalogue_id'];
        }
        if(isset($payload['attribute_id']) && isset($payload['attribute_catalogue_id']) && count($payload['attribute_id']) == count($payload['attribute_catalogue_id'])) {
            foreach($payload['attribute_catalogue_id'] as $key => $val) {
                $payload['attribute'][$val] = $payload['attribute_id'][$key];
            }
        }
        if(isset($payload['questions']) && isset($payload['answers'])) {
            $payload['questions_answers']['questions'] = $payload['questions'];
            $payload['questions_answers']['answers'] = $payload['answers'];
        }else {
            $payload['questions_answers'] = null;
        }
        if(isset($payload['name_configuration']) && isset($payload['value_configuration'])) {
            $payload['specifications']['name'] = $payload['name_configuration'];
            $payload['specifications']['value'] = $payload['value_configuration'];
        }else{
            $payload['specifications'] = null;
        }
        if($payload['tax_status'] == "0") {
            unset($payload['input_tax']);
            unset($payload['output_tax']);
        }
        $keyRemove = ['quantity', 'cost_price', 'type_product',
                        'attribute_catalogue_id', 'attribute_id', 'post_id', 
                        'album_variant', 'name_configuration', 
                        'value_configuration', 'questions', 'answers'];
        foreach($keyRemove as $key) {
            unset($payload[$key]);
        }
        if(isset($payload['allow_to_sell']) && $payload['allow_to_sell']  == "on") {
            $payload['allow_to_sell'] = 1;
        }else{
            $payload['allow_to_sell'] = 0;
        }
        return $payload;
    }

    public function update ($id, $payload) {
        DB::beginTransaction();
        try {
            if(!isset($payload['album'])) {
                $payload['album'] = null;
            }
            $productCatalogueIds = isset($payload['product_catalogue_id_extra']) ? $payload['product_catalogue_id_extra'] : [];
            $productCatalogueIds[] = $payload['product_catalogue_id'];
            unset($payload['product_catalogue_id_extra']);
            $payload['weight'] = formatNumberToSql($payload['weight']);
            //products 
            $payloadProduct = $this->setupPayloadProduct($payload);
            $flag = $this->productRepository->updateById($id, $payloadProduct);
            if($flag) {
                //routers
                $payloadRouter['canonical'] = $payload['canonical'];
                $router = $this->routerRepository->updateByCondition($payloadRouter, [
                    ['model', '=', 'Product'],
                    ['module_id', '=', $id]
                ]);
                //product_post
                $product = $this->productRepository->findByIdAndMultipleRelation($id, ['product_variants']);
                $productPost = $product->posts()->sync(json_decode($payload['post_id']));
                //product_catalogue_product
                $productCatalogueProduct = $product->product_catalogues()->sync($productCatalogueIds);
                //MULTIPLE VARIANT
                if($payload['type_product'] == "1") {
                    //product_variants
                    $variants = $this->updateProductVariant($product, $payload['variant']);
                    //product_warehouse
                    $payloadWarehouse = $this->setupProductWarehouse($payload, $product->product_variants);
                    foreach($payloadWarehouse as $key => $item) {
                        $item['product_id'] = (int) $id;
                        $this->productWarehouseRepository->updateByCondition($item, [
                            ['product_id', '=', $item['product_id']],
                            ['product_variant_id', '=', $item['product_variant_id']],
                            ['warehouse_id', '=', $item['warehouse_id']],
                        ]);
                    }
                    //albums
                    $payloadAlbums = $this->setupAlbum($payload['album_variant']);
                    $flagAlbum = $this->albumRepository->destroyByCondition([['product_id', '=', $id]]);
                    if($flagAlbum) {
                        $albums = $product->albums()->createMany($payloadAlbums);
                    }
                }else{
                    //1 phiên bản
                    $payloadWarehouse = $this->setupSingleProductWarehouse($payload);
                    $payloadWarehouseNew = [];
                    foreach($payloadWarehouse as $key => $item) {
                        $warehouseId = $item['warehouse_id'];
                        unset($item['warehouse_id']);
                        $payloadWarehouseNew[$warehouseId] = $item;
                    }
                    $productWarehouses = $product->warehouses()->sync($payloadWarehouseNew);
                }
                DB::commit();
                return true;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }

    private function updateProductVariant($product, $payload) {
        $productVariantIds = $product->product_variants->pluck('id');
        $payloadVariant = $this->setupPayloadVariant($payload);
        if(count($productVariantIds)) {
            foreach($productVariantIds as $index => $val) {
                $payloadVariant[$index]['id'] = $val;
                $payloadVariant[$index]['product_id'] = $product->id;
            }
        }
        return $this->productVariantRepository->upsert($payloadVariant, ['id'], ['name', 'sku', 'code', 'barcode', 'price', 'cost', 'weight', 'mass', 'id']);
    }

    public function destroy($id) {
        DB::beginTransaction();
        try {
            //Xóa mềm bản ghi trong product, các bảng còn lại giữ nguyên (sản phẩm có thể được khôi phục trong tương lai)
            $product = $this->productRepository->findById($id);
            // $product->product_catalogues()->detach();
            $flag = $this->productRepository->destroy($id);
            // if($flag) {
            //     $this->routerRepository->destroyByCondition([
            //         ['module_id', '=', $id],
            //         ['model', '=', 'Product']
            //     ]);
                
            // }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
    }

    private function createPayloadRouter ($canonical, $module_id) {
        $payload = [
            'canonical' => $canonical,
            'module_id' => $module_id,
            'model' => 'Product'
        ];
        return $this->routerRepository->create($payload);
    }

    private function fieldSelect () {
        return [
            'id',
            'name',
            'code',
            'barcode',
            'weight',
            'mass',
            'measure',
            'specifications',
            'questions_answers',
            'description',
            'content',
            'meta_title',
            'meta_keyword',
            'meta_description',
            'canonical',
            'allow_to_sell',
            'product_catalogue_id',
            'attribute_catalogue',
            'attribute',
            'variant',
            'cost',
            'price',
            'input_tax',
            'output_tax',
            'tax_status',
            'publish',
            'image',
            'album',
            'icon',
            'follow',
            'order',
            'user_id',
            'created_at',
        ];
    }

    //FRONTEND 
    public function setupDataProduct ($product) {
        if(count($product->product_variants)) {
            $attributeCatalogues = $this->attributeCatalogueRepository->findByCondition([], [], 'id', $product->attribute_catalogue, '', [], []);
            $product->attribute = $this->setupDataVariant($product, $attributeCatalogues);
        }else {
            $newWarehouses = [];
            foreach($product->warehouses as $item) {
                $newWarehouses[$item->id] = $item->pivot->toArray();
            }
            $product->new_warehouses = $newWarehouses;
        }
        //Khuyến mại cho nhóm sản phẩm
        $product->promotion_catalogues = $this->setupPromotionForCatalogue($product);
        return $product;
    }

    private function setupPromotionForCatalogue ($product) {
        $productCataloguePromotions = $this->productCatalogueRepository->findAncestorsAndSelfByWhereIn('id', $product->product_catalogues->pluck('id')->toArray());
        $productCatalogueIds = [];
        foreach($productCataloguePromotions as $item) {
            $productCatalogueIds[] = $item->id;
            $productCatalogueIds = array_merge($productCatalogueIds, $item->ancestors->pluck('id')->toArray());
        }
        $promotions = $this->promotionRepository->findByCondition([
            __('config.conditionPublish'),
            ['model', '=', 'productCatalogue']  
        ]);
        $newPromotionCatalogues = [];
        foreach($promotions as $promotion) {
            $arrCheck = array_intersect($promotion->detail['object']['product_catalogue_id'], array_unique($productCatalogueIds));
            if(($promotion->end_date == null || (compareCurrentTime($promotion->end_date) !== 'earlier' && compareCurrentTime($promotion->start_date) !== 'later')) && !empty($arrCheck)) {
                $newPromotionCatalogues[] = $promotion->toArray();
            }
        }
        return $newPromotionCatalogues;
    }

    private function setupDataVariant ($product, $attributeCatalogues) {
        $temp = [];
        $newProductVariants = [];
        foreach($product->product_variants as $variant) {
            $temp = array_merge($temp, $variant->attributes->toArray());
            $newProductVariants[trim($variant['code'])] = $variant->toArray();
        }
        $product->new_product_variants = $newProductVariants;
        $dataAttribute = [];
        foreach($temp as $item) {
            $dataAttribute[$item['id']] = $item['name'];
        }
        $newAttribute = [];
        foreach($product->attribute as $key => $item) {
            foreach($item as $index => $val) {
                $newAttribute[$key][$val] = $dataAttribute[$val]; 
            }
        }
        $newAttributeCatalogue = [];
        foreach($attributeCatalogues as $item) {
            $newAttributeCatalogue[$item->id] = $item->name;
        }
        $product->attribute_catalogue = $newAttributeCatalogue;
        $newAlbums = [];
        foreach($product->albums as $index => $item) {
            if($index == 0) {
                $product->album_by_attribute_catalogue = $item->attribute_catalogue_id;
            }
            $newAlbums[$item->attribute_id] = $item->toArray();
        }
        $product->new_albums = $newAlbums;
        $newWarehouses = [];
        foreach($product->warehouses as $item) {
            $newWarehouses[$item->pivot->product_variant_id . '-' . $item->id] = $item->pivot->toArray();
        }
        $product->new_warehouses = $newWarehouses;
        $newPromotions = [];
        foreach($product->promotions as $item) {
            if(($item->never_end_date == 1 || (compareCurrentTime($item->end_date) !== 'earlier' && compareCurrentTime($item->start_date) !== 'later')) && $item->publish == __('config')['activePublish']) {
                $key = $item->pivot->promotion_id . ($item->pivot->product_variant_id !== null ? ('-' . $item->pivot->product_variant_id) : '');
                $newPromotions[$key] = $item->toArray();
            }
        }
        $product->new_promotions = $newPromotions;
        return $newAttribute;
    }
}
