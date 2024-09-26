<?php

namespace App\Services;

use App\Services\Interfaces\CouponServiceInterface;
use App\Repositories\Interfaces\CouponRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
 * Class CouponService
 * @package App\Services
 */
class CouponService implements CouponServiceInterface
{

    protected $couponRepository;

    public function __construct(
        CouponRepositoryInterface $couponRepository
    )
    {
        $this->couponRepository = $couponRepository;
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
        return $this->couponRepository->pagination($fieldSelects, 
                                                $condition, 
                                                [], 
                                                $limit, 
                                                ['id', 'DESC'],
                                                ['code']
                                            );
    }

    public function store ($payload = []) {
        DB::beginTransaction();
        try {
            $payload = $this->setupPayload($payload);
            $coupon = $this->couponRepository->create($payload);
            DB::commit();
            return $coupon;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }

    public function update ($id, $payload) {
        DB::beginTransaction();
        try {
            $payload = $this->setupPayload($payload);
            if($payload['never_end_date'] == 1) {
                $payload['end_date'] = null;
            }
            $flag = $this->couponRepository->updateById($id, $payload);
            DB::commit();
            return $flag;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }

    private function setupPayload ($payload) {
        $payload['start_date'] = formatDateTimeToSql($payload['start_date']);
        if(isset($payload['end_date'])) {
            $payload['end_date'] = formatDateTimeToSql($payload['end_date']);
        }
        $payload['discount_value'] = formatNumberToSql($payload['discount_value']);
        $payload['max_discount'] = formatNumberToSql($payload['max_discount']);
        if($payload['method'] == 'coupon_order') {
            unset($payload['detail']['model']);
            if(isset($payload['detail']['object'])) {
                unset($payload['detail']['object']);
            }
        }
        $payload['detail']['condition']['minimumValue'] = formatNumberToSql($payload['detail']['condition']['minimumValue']);
        $payload['detail']['limit']['arrCustomerId'] = [];
        $payload['detail']['limit']['usedQuantity'] = 0;
        return $payload;
    }

    public function destroy($id) {
        DB::beginTransaction();
        try {
            $flag = $this->couponRepository->destroy($id);
            DB::commit();
            return $flag;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
    }

    public function permission ($payload) {
        DB::beginTransaction();
        try {
            $coupons = $this->couponRepository->getAll();

            if(count($coupons)) {
                foreach($coupons as $key => $val){
                    if(isset($payload['permission'][$val->id])) {
                        $val->permissions()->sync($payload['permission'][$val->id]);
                    }else {
                        $val->permissions()->detach();
                    }
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
    }

    private function fieldSelect () {
        return [
            'id',
            'code',
            'start_date',
            'end_date',
            'publish',
            'method',
        ];
    }
}
