<?php

namespace App\Services;

use App\Services\Interfaces\UserServiceInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * Class UserService
 * @package App\Services
 */
class UserService implements UserServiceInterface
{

    protected $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository
    )
    {
        $this->userRepository = $userRepository;
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
        if(isset($payload['user_catalogue_id']) && (int) $payload['user_catalogue_id'] !== 0) {
            $condition[] = ['user_catalogue_id', '=', $payload['user_catalogue_id']];
        }
        if(isset($payload['keyword']) && $payload['keyword'] !== "") {
            $condition['keyword'] = $payload['keyword'];
        }
        return $this->userRepository->pagination($fieldSelects, 
                                                $condition, 
                                                ['user_catalogues'], 
                                                $limit, 
                                                ['id', 'DESC'],
                                                ['name', 'email', 'phone', 'address']
                                            );
    }

    public function store ($payload = []) {
        DB::beginTransaction();
        try {
            if($payload['birthday'] !== null) {
                $payload['birthday'] = formatDateToSql($payload['birthday']);
            } 
            $payload['password'] = Hash::make($payload['password']);
            $user = $this->userRepository->create($payload);
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
    }

    public function update ($id, $payload) {
        DB::beginTransaction();
        try {
            if($payload['birthday'] !== null) {
                $payload['birthday'] = formatDateToSql($payload['birthday']);
            } 
            $flag = $this->userRepository->updateById($id, $payload);
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
            $flag = $this->userRepository->destroy($id);
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
            'email',
            'phone',
            'birthday',
            'image',
            'address',
            'description',
            'password',
            'publish',
            'user_catalogue_id',
            'deleted_at',
        ];
    }
}
