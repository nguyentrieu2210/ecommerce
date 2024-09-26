<?php

namespace App\Services;

use App\Services\Interfaces\ProductCatalogueServiceInterface;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;

/**
 * Class ProductCatalogueService
 * @package App\Services
 */
class ProductCatalogueService implements ProductCatalogueServiceInterface
{

    protected $productCatalogueRepository;
    protected $routerRepository;

    public function __construct(
        ProductCatalogueRepositoryInterface $productCatalogueRepository,
        RouterRepository $routerRepository
    )
    {
        $this->productCatalogueRepository = $productCatalogueRepository;
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
        return $this->productCatalogueRepository->pagination($fieldSelects, 
                                                $condition, 
                                                ['products'], 
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
                $parentRecord = $this->productCatalogueRepository->findById($payload['parent_id']);
                if($parentRecord) {
                    $payload['level'] = $parentRecord->level + 1;
                    $payload['parent_id'] = $parentRecord->id;
                    $productCatalogue = $parentRecord->children()->create($payload);
                }
            }else{
                unset($payload['parent_id']);
                $productCatalogue = $this->productCatalogueRepository->create($payload);
            }
            $payloadRouter = $this->setPayloadForRouter($payload['canonical'], $productCatalogue->id);
            $router = $this->routerRepository->create($payloadRouter);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }

    public function update ($id, $payload) {
        DB::beginTransaction();
        try {
            if(!isset($payload['album'])) {
                $payload['album'] = null;
            }
            $productCatalogue = $this->productCatalogueRepository->findById($id);
            $parent_id = (int) $payload['parent_id'];
            unset($payload['parent_id']);
            if($parent_id !== 0) {
                $parentRecord = $this->productCatalogueRepository->findById($parent_id);
                $payload['level'] = $parentRecord->level + 1;
                $productCatalogue->fill($payload);
                $productCatalogue->appendToNode($parentRecord)->save();
            }else{
                // Sử dụng makeRoot() để di chuyển node về node gốc
                $payload['level'] = 0;
                $productCatalogue->makeRoot();
                $productCatalogue->fill($payload);
                $productCatalogue->save();
            }
            $payloadRouter['canonical'] = $payload['canonical'];
            $router = $this->routerRepository->updateByCondition($payloadRouter, [
                ['model', '=', 'ProductCatalogue'],
                ['module_id', '=', $id]
            ]);
            DB::commit();
            return $productCatalogue;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }

    public function destroy($id) {
        DB::beginTransaction();
        try {
            $productCatalogue = $this->productCatalogueRepository->findById($id);
            $productCatalogue->products()->detach();
            $flag = $this->productCatalogueRepository->destroy($id);
            if($flag) {
                $this->routerRepository->destroyByCondition([
                    ['module_id', '=', $id],
                    ['model', '=', 'ProductCatalogue']
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
            'model' => 'ProductCatalogue'
        ];
    }

    private function fieldSelect () {
        return [
            'id',
            'name',
            'description',
            'content',
            'meta_title',
            'meta_keyword',
            'meta_description',
            'canonical',
            'level',
            'publish',
            'image',
            'album',
            'icon',
            'follow',
            'order',
        ];
    }
}
