<?php

namespace App\Services;

use App\Services\Interfaces\PermissionServiceInterface;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class PermissionService
 * @package App\Services
 */
class PermissionService implements PermissionServiceInterface
{

    protected $permissionRepository;

    public function __construct(
        PermissionRepositoryInterface $permissionRepository
    )
    {
        $this->permissionRepository = $permissionRepository;
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
        return $this->permissionRepository->pagination($fieldSelects, 
                                                $condition, 
                                                ['user_catalogues'], 
                                                $limit, 
                                                ['id', 'DESC'],
                                                ['name', 'canonical']
                                            );
    }

    public function store ($payload = []) {
        DB::beginTransaction();
        try {
            $permission = $this->permissionRepository->create($payload);
            DB::commit();
            return $permission;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
    }

    public function update ($id, $payload) {
        DB::beginTransaction();
        try {
            $flag = $this->permissionRepository->updateById($id, $payload);
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
            $flag = $this->permissionRepository->destroy($id);
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
            'canonical'
        ];
    }
}
