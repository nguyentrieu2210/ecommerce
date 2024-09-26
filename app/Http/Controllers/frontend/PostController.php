<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PostCatalogue;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\WidgetRepositoryInterface as WidgetRepository;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository;

class PostController extends Controller
{
    //

    protected $widgetRepository;
    protected $postCatalogueRepository;
    protected $postRepository;

    public function __construct(
        WidgetRepository $widgetRepository,
        PostCatalogueRepository $postCatalogueRepository,
        PostRepository $postRepository
    )
    {
        $this->widgetRepository = $widgetRepository;
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->postRepository = $postRepository;
    }

    public function index($id, Request $request, $page = 1) {
        $payload = $request->query();
        $config = $this->config();

        $post = $this->postRepository->findByIdAndMultipleRelation($id, ['users', 'post_catalogues']);
        $postCatalogue = $this->postCatalogueRepository->findById($post->post_catalogue_id);
        $postCatalogues = $postCatalogue->ancestors()->get()->push($postCatalogue)->toTree()->first();
        // dd($postCatalogues);
        $seo = [
            'meta_title' => $post->meta_title,
            'meta_description' => $post->meta_description,
            'meta_keyword' => $post->meta_keyword,
            'canonical' => '/' . $post->canonical,
        ];
        return view('frontend.post.index', compact(
            'config',
            'seo',
            'post',
            'postCatalogues'
        ));
    }


    private function config () {
        return [
            'js' => [],
            'css' => [
                "frontend/css/blog.css"
            ], 
            'module' => 'post',
        ];
    }
}