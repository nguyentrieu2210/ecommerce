<?php

namespace App\Services;

use App\Services\Interfaces\UserCatalogueServiceInterface;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class UserCatalogueService
 * @package App\Services
 */
class UserCatalogueService implements UserCatalogueServiceInterface
{

    protected $userCatalogueRepository;

    public function __construct(
        UserCatalogueRepositoryInterface $userCatalogueRepository
    )
    {
        $this->userCatalogueRepository = $userCatalogueRepository;
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
        return $this->userCatalogueRepository->pagination($fieldSelects, 
                                                $condition, 
                                                ['users'], 
                                                $limit, 
                                                ['id', 'DESC'],
                                                ['name', 'description']
                                            );
    }

    public function store ($payload = []) {
        DB::beginTransaction();
        try {
            $userCatalogue = $this->userCatalogueRepository->create($payload);
            DB::commit();
            return $userCatalogue;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
    }

    public function update ($id, $payload) {
        DB::beginTransaction();
        try {
            $flag = $this->userCatalogueRepository->updateById($id, $payload);
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
            $flag = $this->userCatalogueRepository->destroy($id);
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
            $userCatalogues = $this->userCatalogueRepository->getAll();

            if(count($userCatalogues)) {
                foreach($userCatalogues as $key => $val){
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
