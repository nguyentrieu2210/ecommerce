<?php

namespace App\Services;

use App\Services\Interfaces\PostCatalogueServiceInterface;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;

/**
 * Class PostCatalogueService
 * @package App\Services
 */
class PostCatalogueService implements PostCatalogueServiceInterface
{

    protected $postCatalogueRepository;
    protected $routerRepository;

    public function __construct(
        PostCatalogueRepositoryInterface $postCatalogueRepository,
        RouterRepository $routerRepository
    )
    {
        $this->postCatalogueRepository = $postCatalogueRepository;
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
        return $this->postCatalogueRepository->pagination($fieldSelects, 
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
                $parentRecord = $this->postCatalogueRepository->findById($payload['parent_id']);
                if($parentRecord) {
                    $payload['level'] = $parentRecord->level + 1;
                    $payload['parent_id'] = $parentRecord->id;
                    $postCatalogue = $parentRecord->children()->create($payload);
                }
            }else{
                unset($payload['parent_id']);
                $postCatalogue = $this->postCatalogueRepository->create($payload);
            }
            $payloadRouter = $this->setPayloadForRouter($payload['canonical'], $postCatalogue->id);
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
            $postCatalogue = $this->postCatalogueRepository->findById($id);
            $parent_id = (int) $payload['parent_id'];
            unset($payload['parent_id']);
            if($parent_id !== 0) {
                $parentRecord = $this->postCatalogueRepository->findById($parent_id);
                $payload['level'] = $parentRecord->level + 1;
                $postCatalogue->fill($payload);
                $postCatalogue->appendToNode($parentRecord)->save();
            }else{
                // Sử dụng makeRoot() để di chuyển node về node gốc
                $payload['level'] = 0;
                $postCatalogue->makeRoot();
                $postCatalogue->fill($payload);
                $postCatalogue->save();
            }
            $payloadRouter['canonical'] = $payload['canonical'];
            $router = $this->routerRepository->updateByCondition($payloadRouter, [
                ['model', '=', 'PostCatalogue'],
                ['module_id', '=', $id]
            ]);
            DB::commit();
            return $postCatalogue;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }

    public function destroy($id) {
        DB::beginTransaction();
        try {
            $postCatalogue = $this->postCatalogueRepository->findById($id);
            $postCatalogue->posts()->detach();
            $flag = $this->postCatalogueRepository->destroy($id);
            if($flag) {
                $this->routerRepository->destroyByCondition([
                    ['module_id', '=', $id],
                    ['model', '=', 'postCatalogue']
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
            'model' => 'PostCatalogue'
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
            'publish',
            'image',
            'icon',
            'order',
            'user_id',
            'level',
            '_lft',
            '_rgt',
            'parent_id',
            'follow'
        ];
    }
}
