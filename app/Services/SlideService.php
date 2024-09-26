<?php

namespace App\Services;

use App\Services\Interfaces\SlideServiceInterface;
use App\Repositories\Interfaces\SlideRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class SlideService
 * @package App\Services
 */
class SlideService implements SlideServiceInterface
{

    protected $slideRepository;

    public function __construct(
        SlideRepositoryInterface $slideRepository
    )
    {
        $this->slideRepository = $slideRepository;
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
        return $this->slideRepository->pagination($fieldSelects, 
                                                $condition, 
                                                [], 
                                                $limit, 
                                                ['id', 'DESC'],
                                                ['name', 'keyword']
                                            );
    }

    public function store ($payload = []) {
        DB::beginTransaction();
        try {
            $payload = $this->setupPayloadSlide($payload);
            $slide = $this->slideRepository->create($payload);
            DB::commit();
            return $slide;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
    }

    private function setupPayloadSlide($payload) {
        foreach($payload['setting'] as $key => $val) {
            if(in_array($key, ['width', 'height', 'delay', 'speed'])) {
                $payload['setting'][$key] = ($payload['setting'][$key] !== "0" && $payload['setting'][$key] !== null) ? formatNumberToSql($val) : 0;
            }
        }
        if($payload['setting']['delay'] == 0) $payload['setting']['delay'] = 3000;
        if($payload['setting']['speed'] == 0) $payload['setting']['speed'] = 500;
        $payloadSlide = [];
        foreach($payload['item'] as $keyword => $item) {
            foreach($item as $key => $val) {
                $payloadSlide[$key][$keyword] = $val;
            }
        }
        $payload['item'] = $payloadSlide;
        return $payload;
    }

    public function update ($id, $payload) {
        DB::beginTransaction();
        try {
            $payload = $this->setupPayloadSlide($payload);
            $flag = $this->slideRepository->updateById($id, $payload);
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
            $flag = $this->slideRepository->destroy($id);
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
            $slides = $this->slideRepository->getAll();

            if(count($slides)) {
                foreach($slides as $key => $val){
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
            'keyword',
            'item',
            'setting',
        ];
    }
}
