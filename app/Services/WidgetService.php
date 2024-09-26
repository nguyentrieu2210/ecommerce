<?php

namespace App\Services;

use App\Services\Interfaces\WidgetServiceInterface;
use App\Repositories\Interfaces\WidgetRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class WidgetService
 * @package App\Services
 */
class WidgetService implements WidgetServiceInterface
{

    protected $widgetRepository;

    public function __construct(
        WidgetRepositoryInterface $widgetRepository
    )
    {
        $this->widgetRepository = $widgetRepository;
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
        return $this->widgetRepository->pagination($fieldSelects, 
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
            $widget = $this->widgetRepository->create($payload);
            DB::commit();
            return $widget;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }

    public function update ($id, $payload) {
        DB::beginTransaction();
        try {
            // dd($payload);
            $payload['model_id'] = json_encode($payload['model_id']);
            $flag = $this->widgetRepository->updateById($id, $payload);
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
            $flag = $this->widgetRepository->destroy($id);
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
            $widgets = $this->widgetRepository->getAll();

            if(count($widgets)) {
                foreach($widgets as $key => $val){
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

    //FRONTEND
    public function getDataByWidgets ($widgets = []) {
        $temp = [];
        foreach($widgets as $key => $item) {
            $instanceRepository = setupModelRepository($item->model);
            $whereIn = json_decode($item->model_id);
            $temp[$item->keyword] = app($instanceRepository)->findByCondition([
                __('config.conditionPublish')
            ], ['id', 'DESC'], 'id', $whereIn, '', [], $item->model == 'product' ? ['promotions', 'product_catalogues.promotions', 'product_variants'] : []);
        }
        return $temp;
    }

    private function fieldSelect () {
        return [
            'id',
            'name',
            'keyword',
            'description',
            'model',
            'model_id',
            'publish',
        ];
    }
}
