<?php

namespace App\Services;

use App\Services\Interfaces\AttributeServiceInterface;
use App\Repositories\Interfaces\AttributeRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface as AttributeCatalogueRepository;
use App\Repositories\Interfaces\AttributeCatalogueAttributeRepositoryInterface as AttributeCatalogueAttributeRepository;

/**a
 * Class AttributeService
 * @package App\Services
 */
class AttributeService implements AttributeServiceInterface
{

    protected $attributeRepository;
    protected $routerRepository;
    protected $attributeCatalogueRepository;
    protected $attributeCatalogueAttributeRepository;

    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        RouterRepository $routerRepository,
        AttributeCatalogueRepository $attributeCatalogueRepository,
        AttributeCatalogueAttributeRepository $attributeCatalogueAttributeRepository

    )
    {
        $this->attributeRepository = $attributeRepository;
        $this->routerRepository = $routerRepository;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
        $this->attributeCatalogueAttributeRepository = $attributeCatalogueAttributeRepository;
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
        if(isset($payload['attribute_catalogue_id']) && (int) $payload['attribute_catalogue_id'] !== 0) {
            $attributeCatalogueId = $payload['attribute_catalogue_id'];
            $attributeCatalogue = $this->attributeCatalogueRepository->findById($attributeCatalogueId);
            $childrenIds = $attributeCatalogue->descendantsAndSelf($attributeCatalogueId)->pluck('id');
            $attributeIds = array_unique($this->attributeCatalogueAttributeRepository->findByCondition([], [], 'attribute_catalogue_id', $childrenIds)->pluck('attribute_id')->toArray());
            $condition['searchByCatalogue'] = $attributeIds;
        }

        return $this->attributeRepository->pagination($fieldSelects, 
                                                $condition, 
                                                ['attribute_catalogues'], 
                                                $limit, 
                                                ['id', 'DESC'],
                                                ['name', 'description']
                                            );
    }

    public function store ($payload = []) {
        DB::beginTransaction();
        try {
            $payload['user_id'] = Auth::id();
            $attributeCatalogueIds = isset($payload['attribute_catalogue_id_extra']) ? $payload['attribute_catalogue_id_extra'] : [];
            $attributeCatalogueIds[] = $payload['attribute_catalogue_id'];
            unset($payload['attribute_catalogue_id_extra']);
            $attribute = $this->attributeRepository->create($payload);
            if($attribute->id) {
                $payloadRouter = $this->setPayloadForRouter($payload['canonical'], $attribute->id);
                $router = $this->routerRepository->create($payloadRouter);
                $attribute->attribute_catalogues()->sync($attributeCatalogueIds);
            }
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
            $attributeCatalogueIds = isset($payload['attribute_catalogue_id_extra']) ? $payload['attribute_catalogue_id_extra'] : [];
            $attributeCatalogueIds[] = $payload['attribute_catalogue_id'];
            unset($payload['attribute_catalogue_id_extra']);
            $flag = $this->attributeRepository->updateById($id, $payload);
            if($flag) {
                $payloadRouter['canonical'] = $payload['canonical'];
                $router = $this->routerRepository->updateByCondition($payloadRouter, [
                    ['model', '=', 'Attribute'],
                    ['module_id', '=', $id]
                ]);
                $attribute = $this->attributeRepository->findById($id);
                $attribute->attribute_catalogues()->sync($attributeCatalogueIds);
                DB::commit();
                return true;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
    }

    public function destroy($id) {
        DB::beginTransaction();
        try {
            $attribute = $this->attributeRepository->findById($id);
            $attribute->attribute_catalogues()->detach();
            $flag = $this->attributeRepository->destroy($id);
            if($flag) {
                $this->routerRepository->destroyByCondition([
                    ['module_id', '=', $id],
                    ['model', '=', 'Attribute']
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
            'model' => 'Attribute'
        ];
    }

    private function fieldSelect () {
        return [
            'id',
            'name',
            'description',
            'canonical',
            'attribute_catalogue_id',
            'publish',
            'image',
            'icon',
            'follow',
            'order',
            'user_id'
        ];
    }
}
