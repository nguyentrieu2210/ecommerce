<?php

namespace App\Services;

use App\Services\Interfaces\AttributeCatalogueServiceInterface;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Support\Facades\Session;

/**
 * Class AttributeCatalogueService
 * @package App\Services
 */
class AttributeCatalogueService implements AttributeCatalogueServiceInterface
{

    protected $attributeCatalogueRepository;
    protected $routerRepository;

    public function __construct(
        AttributeCatalogueRepositoryInterface $attributeCatalogueRepository,
        RouterRepository $routerRepository
    )
    {
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
        $this->routerRepository = $routerRepository;
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
        return $this->attributeCatalogueRepository->pagination($fieldSelects, 
                                                $condition, 
                                                [], 
                                                $limit, 
                                                ['_lft', 'ASC'],
                                                ['name', 'description']
                                            );
    }

    public function store ($payload = []) {
        DB::beginTransaction();
        try {
            $payload['user_id'] = Auth::id();
            if((int) $payload['parent_id'] !== 0) {
                $parentRecord = $this->attributeCatalogueRepository->findById($payload['parent_id']);
                if($parentRecord) {
                    $payload['level'] = $parentRecord->level + 1;
                    $payload['parent_id'] = $parentRecord->id;
                    $attributeCatalogue = $parentRecord->children()->create($payload);
                }
            }else{
                unset($payload['parent_id']);
                $attributeCatalogue = $this->attributeCatalogueRepository->create($payload);
            }
            $payloadRouter = $this->setPayloadForRouter($payload['canonical'], $attributeCatalogue->id);
            $router = $this->routerRepository->create($payloadRouter);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
    }

    public function update ($id, $payload) {
        DB::beginTransaction();
        try {
            $attributeCatalogue = $this->attributeCatalogueRepository->findById($id);
            $parent_id = (int) $payload['parent_id'];
            unset($payload['parent_id']);
            if($parent_id !== 0) {
                $parentRecord = $this->attributeCatalogueRepository->findById($parent_id);
                $payload['level'] = $parentRecord->level + 1;
                $attributeCatalogue->fill($payload);
                $attributeCatalogue->appendToNode($parentRecord)->save();
            }else{
                // Sử dụng makeRoot() để di chuyển node về node gốc
                $payload['level'] = 0;
                $attributeCatalogue->makeRoot();
                $attributeCatalogue->fill($payload);
                $attributeCatalogue->save();
            }
            $payloadRouter['canonical'] = $payload['canonical'];
            $router = $this->routerRepository->updateByCondition($payloadRouter, [
                ['model', '=', 'AttributeCatalogue'],
                ['module_id', '=', $id]
            ]);
            DB::commit();
            return $attributeCatalogue;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
    }

    public function destroy($id) {
        DB::beginTransaction();
        try {
            $attributeCatalogue = $this->attributeCatalogueRepository->findById($id);
            $attributeCatalogue->attributes()->detach();
            $flag = $this->attributeCatalogueRepository->destroy($id);
            if($flag) {
                $this->routerRepository->destroyByCondition([
                    ['module_id', '=', $id],
                    ['model', '=', 'AttributeCatalogue']
                ]);
                DB::commit();
                return true;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
    }

    private function setPayloadForRouter ($canonical, $module_id) {
        return [
            'canonical' => $canonical,
            'module_id' => $module_id,
            'model' => 'AttributeCatalogue'
        ];
    }

    private function fieldSelect () {
        return [
            'id',
            'name',
            'description',
            'canonical',
            'publish',
            'image',
            'icon',
            'order',
            'user_id',
            'level',
            '_lft',
            '_rgt',
            'parent_id'
        ];
    }
}
