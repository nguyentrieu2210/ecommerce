<?php

namespace App\Services;

use App\Services\Interfaces\SystemServiceInterface;
use App\Repositories\Interfaces\SystemRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class SystemService
 * @package App\Services
 */
class SystemService implements SystemServiceInterface
{

    protected $systemRepository;

    public function __construct(
        SystemRepositoryInterface $systemRepository
    )
    {
        $this->systemRepository = $systemRepository;
    }

    public function store ($payload = []) {
        DB::beginTransaction();
        try {
            foreach($payload as $key => $val) {
                $temp = [
                    'keyword' => $key,
                    'content' => $val,
                ];
                $condition['keyword'] = $key;
                $this->systemRepository->updateOrCreate($condition, $temp);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }

    public function editor ($payload = []) {
        DB::beginTransaction();
        try {
            $keyword = $payload['keyword'];
            unset($payload['keyword']);
            $condition['keyword'] = $keyword;
            $temp = [
                'keyword' => $keyword,
                'content' => json_encode($payload)
            ];
            $this->systemRepository->updateOrCreate($condition, $temp);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }
}
