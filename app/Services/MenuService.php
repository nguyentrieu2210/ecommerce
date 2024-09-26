<?php

namespace App\Services;

use App\Models\Link;
use App\Services\Interfaces\MenuServiceInterface;
use App\Repositories\Interfaces\MenuRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
 * Class MenuService
 * @package App\Services
 */
class MenuService implements MenuServiceInterface
{

    protected $menuRepository;

    public function __construct(
        MenuRepositoryInterface $menuRepository
    )
    {
        $this->menuRepository = $menuRepository;
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
        return $this->menuRepository->pagination($fieldSelects, 
                                                $condition, 
                                                ['links'], 
                                                $limit, 
                                                ['id', 'DESC'],
                                                ['name', 'keyword']
                                            );
    }

    public function store ($payload = []) {
        DB::beginTransaction();
        try {
            $links = json_decode($payload['links']);
            unset($payload['links']);
            $menu = $this->menuRepository->create($payload);
            if($menu->id && count($links)) {
                foreach($links as $key => $item) {
                    $link = $menu->links()->create($this->setupPayloadLink($item));
                    if(isset($item->children) && count($item->children)) {
                        $this->storeLink($item->children, $link, $menu->id);
                    }
                }
            }
            DB::commit();
            return $menu;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }

    private function storeLink($links, $parentNode, $menuId) {
        foreach($links as $key => $item) {
            $childNode = $this->setupPayloadLink($item);
            $level = $parentNode->level !== null ? $parentNode->level : 0;
            $childNode['level'] = $level + 1;
            $childNode['menu_id'] = $menuId;
            $child = new Link($childNode);
            $child->appendToNode($parentNode)->save();
            if(isset($item->children) && count($item->children) > 0) {
                $this->storeLink($item->children, $child, $menuId);
            }
        }
    }

    private function setupPayloadLink($link) {
        return [
            'name' => $link->name,
            'image' => $link->image,
            'canonical' => $link->canonical,
            'detail' => [
                'keyword' => $link->keyword,
                'htmltarget' => $link->htmltarget,
                'model' => $link->model
            ]
        ];
    }

    public function update ($id, $payload) {
        DB::beginTransaction();
        try {
            $menu = $this->menuRepository->findById($id);
            $menu->links()->delete();
            $links = json_decode($payload['links']);
            unset($payload['links']);
            if($menu->id && count($links)) {
                foreach($links as $key => $item) {
                    $link = $menu->links()->create($this->setupPayloadLink($item));
                    if(isset($item->children) && count($item->children)) {
                        $this->storeLink($item->children, $link, $menu->id);
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

    public function destroy($id) {
        DB::beginTransaction();
        try {
            $flag = $this->menuRepository->destroy($id);
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
            $menus = $this->menuRepository->getAll();

            if(count($menus)) {
                foreach($menus as $key => $val){
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
            'publish',
        ];
    }
}
