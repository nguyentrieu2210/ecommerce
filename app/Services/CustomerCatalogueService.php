<?php

namespace App\Services;

use App\Services\Interfaces\CustomerCatalogueServiceInterface;
use App\Repositories\Interfaces\CustomerCatalogueRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class CustomerCatalogueService
 * @package App\Services
 */
class CustomerCatalogueService implements CustomerCatalogueServiceInterface
{

    protected $customerCatalogueRepository;

    public function __construct(
        CustomerCatalogueRepositoryInterface $customerCatalogueRepository
    )
    {
        $this->customerCatalogueRepository = $customerCatalogueRepository;
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
        return $this->customerCatalogueRepository->pagination($fieldSelects, 
                                                $condition, 
                                                ['customers'], 
                                                $limit, 
                                                ['id', 'DESC'],
                                                ['name', 'description']
                                            );
    }

    public function store ($payload = []) {
        DB::beginTransaction();
        try {
            $customerCatalogue = $this->customerCatalogueRepository->create($payload);
            DB::commit();
            return $customerCatalogue;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
    }

    public function update ($id, $payload) {
        DB::beginTransaction();
        try {
            $flag = $this->customerCatalogueRepository->updateById($id, $payload);
            DB::commit();
            return $flag;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
    }

    public function destroy($id) {
        DB::beginTransaction();
        try {
            $flag = $this->customerCatalogueRepository->destroy($id);
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
            $customerCatalogues = $this->customerCatalogueRepository->getAll();

            if(count($customerCatalogues)) {
                foreach($customerCatalogues as $key => $val){
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
            'name',
            'image',
            'description',
            'publish',
        ];
    }
}
