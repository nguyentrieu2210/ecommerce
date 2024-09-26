<?php

namespace App\Services;

use App\Services\Interfaces\SourceServiceInterface;
use App\Repositories\Interfaces\SourceRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class SourceService
 * @package App\Services
 */
class SourceService implements SourceServiceInterface
{

    protected $sourceRepository;

    public function __construct(
        SourceRepositoryInterface $sourceRepository
    )
    {
        $this->sourceRepository = $sourceRepository;
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
        return $this->sourceRepository->pagination($fieldSelects, 
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
            $source = $this->sourceRepository->create($payload);
            DB::commit();
            return $source;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
    }

    public function update ($id, $payload) {
        DB::beginTransaction();
        try {
            $flag = $this->sourceRepository->updateById($id, $payload);
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
            $flag = $this->sourceRepository->destroy($id);
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
            'image',
            'description',
            'publish',
        ];
    }
}
