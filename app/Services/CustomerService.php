<?php

namespace App\Services;

use App\Services\Interfaces\CustomerServiceInterface;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * Class CustomerService
 * @package App\Services
 */
class CustomerService implements CustomerServiceInterface
{

    protected $customerRepository;

    public function __construct(
        CustomerRepositoryInterface $customerRepository
    )
    {
        $this->customerRepository = $customerRepository;
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
        if(isset($payload['customer_catalogue_id']) && (int) $payload['customer_catalogue_id'] !== 0) {
            $condition[] = ['customer_catalogue_id', '=', $payload['customer_catalogue_id']];
        }
        if(isset($payload['keyword']) && $payload['keyword'] !== "") {
            $condition['keyword'] = $payload['keyword'];
        }
        return $this->customerRepository->pagination($fieldSelects, 
                                                $condition, 
                                                ['customer_catalogues'], 
                                                $limit, 
                                                ['id', 'DESC'],
                                                ['name', 'email', 'phone', 'address']
                                            );
    }

    public function store ($payload = []) {
        DB::beginTransaction();
        try {
            if(isset($payload['birthday']) && $payload['birthday'] !== null) {
                $payload['birthday'] = formatDateToSql($payload['birthday']);
            } 
            $payload['password'] = Hash::make($payload['password']);
            $customer = $this->customerRepository->create($payload);
            DB::commit();
            return $customer;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }

    public function update ($id, $payload) {
        DB::beginTransaction();
        try {
            if(isset($payload['birthday']) && $payload['birthday'] !== null) {
                $payload['birthday'] = formatDateToSql($payload['birthday']);
            } 
            $flag = $this->customerRepository->updateById($id, $payload);
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
            $flag = $this->customerRepository->destroy($id);
            DB::commit();
            return $flag;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }

    private function fieldSelect () {
        return [
            'id',
            'name',
            'email',
            'phone',
            'birthday',
            'image',
            'address',
            'description',
            'password',
            'publish',
            'customer_catalogue_id',
            'deleted_at',
        ];
    }
}
