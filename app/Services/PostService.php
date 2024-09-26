<?php

namespace App\Services;

use App\Services\Interfaces\PostServiceInterface;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
use App\Repositories\Interfaces\PostCataloguePostRepositoryInterface as PostCataloguePostRepository;

/**a
 * Class PostService
 * @package App\Services
 */
class PostService implements PostServiceInterface
{

    protected $postRepository;
    protected $routerRepository;
    protected $postCatalogueRepository;
    protected $postCataloguePostRepository;

    public function __construct(
        PostRepositoryInterface $postRepository,
        RouterRepository $routerRepository,
        PostCatalogueRepository $postCatalogueRepository,
        PostCataloguePostRepository $postCataloguePostRepository

    )
    {
        $this->postRepository = $postRepository;
        $this->routerRepository = $routerRepository;
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->postCataloguePostRepository = $postCataloguePostRepository;
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
        if(isset($payload['post_catalogue_id']) && (int) $payload['post_catalogue_id'] !== 0) {
            $postCatalogueId = $payload['post_catalogue_id'];
            $postCatalogue = $this->postCatalogueRepository->findById($postCatalogueId);
            $childrenIds = $postCatalogue->descendantsAndSelf($postCatalogueId)->pluck('id');
            $postIds = array_unique($this->postCataloguePostRepository->findByCondition([], [], 'post_catalogue_id', $childrenIds)->pluck('post_id')->toArray());
            $condition['searchByCatalogue'] = $postIds;
        }

        return $this->postRepository->pagination($fieldSelects, 
                                                $condition, 
                                                ['post_catalogues', 'users'], 
                                                $limit, 
                                                ['id', 'DESC'],
                                                ['name', 'description']
                                            );
    }

    public function store ($payload = []) {
        DB::beginTransaction();
        try {
            $payload['user_id'] = Auth::id();
            $postCatalogueIds = isset($payload['post_catalogue_id_extra']) ? $payload['post_catalogue_id_extra'] : [];
            $postCatalogueIds[] = $payload['post_catalogue_id'];
            unset($payload['post_catalogue_id_extra']);
            $post = $this->postRepository->create($payload);
            if($post->id) {
                $payloadRouter = $this->setPayloadForRouter($payload['canonical'], $post->id);
                $router = $this->routerRepository->create($payloadRouter);
                $post->post_catalogues()->sync($postCatalogueIds);
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
            if(!isset($payload['album'])) {
                $payload['album'] = null;
            }
            $postCatalogueIds = isset($payload['post_catalogue_id_extra']) ? $payload['post_catalogue_id_extra'] : [];
            $postCatalogueIds[] = $payload['post_catalogue_id'];
            unset($payload['post_catalogue_id_extra']);
            $flag = $this->postRepository->updateById($id, $payload);
            if($flag) {
                $payloadRouter['canonical'] = $payload['canonical'];
                $router = $this->routerRepository->updateByCondition($payloadRouter, [
                    ['model', '=', 'post'],
                    ['module_id', '=', $id]
                ]);
                $post = $this->postRepository->findById($id);
                $post->post_catalogues()->sync($postCatalogueIds);
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
            $post = $this->postRepository->findById($id);
            $post->post_catalogues()->detach();
            $flag = $this->postRepository->destroy($id);
            if($flag) {
                $this->routerRepository->destroyByCondition([
                    ['module_id', '=', $id],
                    ['model', '=', 'Post']
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
            'model' => 'Post'
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
            'post_catalogue_id',
            'model_id',
            'publish',
            'image',
            'album',
            'like_counter',
            'icon',
            'follow',
            'order',
            'user_id',
            'created_at',
            'updated_at'
        ];
    }
}
