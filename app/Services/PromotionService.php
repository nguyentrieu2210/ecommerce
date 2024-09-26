<?php

namespace App\Services;

use App\Services\Interfaces\PromotionServiceInterface;
use App\Repositories\Interfaces\PromotionRepositoryInterface;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Support\Facades\DB;

/**
 * Class PromotionService
 * @package App\Services
 */
class PromotionService implements PromotionServiceInterface
{

    protected $promotionRepository;

    public function __construct(
        PromotionRepositoryInterface $promotionRepository
    )
    {
        $this->promotionRepository = $promotionRepository;
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
        return $this->promotionRepository->pagination($fieldSelects, 
                                                $condition, 
                                                [], 
                                                $limit, 
                                                ['id', 'DESC'],
                                                ['name']
                                            );
    }

    public function store ($payload = []) {
        DB::beginTransaction();
        try {
            $payload['method'] = 'promotion_product';
            $payload['start_date'] = formatDateTimeToSql($payload['start_date']);
            $payload['discount_value'] = formatNumberToSql($payload['discount_value']);
            if(isset($payload['end_date'])) {
                $payload['end_date'] = formatDateTimeToSql($payload['end_date']);
            }
            $promotion = $this->promotionRepository->create($payload);
            if($promotion->id && $promotion->detail['model'] !== 'all') {
                if($payload['detail']['model'] == 'product') {
                    $payloadModel = $this->setupPayloadModel(['product_id', 'product_variant_id'], $payload['detail']['object']);
                    $promotion->products()->attach($payloadModel);
                }else{
                    $payloadModel = $this->setupPayloadModel(['product_catalogue_id'], $payload['detail']['object']);
                    $promotion->productCatalogues()->attach($payloadModel);
                }
            }
            DB::commit();
            return $promotion;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
    }

    private function setupPayloadModel ($fields = [], $data = []) {
        $count = count($data['name']);
        $payloadModel = [];
        for($i = 0; $i < $count; $i++) {
            $temp = [];
            foreach($fields as $field) {
                $temp[$field] = $data[$field][$i] !== 'null' ? $data[$field][$i] : null;
            }
            $payloadModel[] = $temp;
        }
        return $payloadModel;
    }

    public function update ($id, $payload) {
        DB::beginTransaction();
        try {
            $promotion = $this->promotionRepository->findById($id);
            if($promotion->detail['model'] == 'product') {
                $promotion->products()->detach();
            }else{
                $promotion->productCatalogues()->detach();
            }
            $payload['start_date'] = formatDateTimeToSql($payload['start_date']);
            $payload['discount_value'] = formatNumberToSql($payload['discount_value']);
            if(isset($payload['end_date'])) {
                $payload['end_date'] = formatDateTimeToSql($payload['end_date']);
            }
            if($payload['never_end_date'] == 1) {
                $payload['end_date'] = null;
            }
            $flag = $this->promotionRepository->updateById($id, $payload);
            
            if($flag && $payload['detail']['model'] !== 'all') {
                if($payload['detail']['model'] == 'product') {
                    $payloadModel = $this->setupPayloadModel(['product_id', 'product_variant_id'], $payload['detail']['object']);
                    $promotion->products()->attach($payloadModel);
                }else{
                    $payloadModel = $this->setupPayloadModel(['product_catalogue_id'], $payload['detail']['object']);
                    $promotion->productCatalogues()->sync($payloadModel);
                }
            }
            DB::commit();
            return $flag;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }

    public function destroy($id) {
        DB::beginTransaction();
        try {
            $flag = $this->promotionRepository->destroy($id);
            DB::commit();
            return $flag;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
    }

    private function fieldSelect () {
        return [
            'id',
            'name',
            'start_date',
            'end_date',
            'publish',
            'method',
        ];
    }
}
