<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\WidgetRepositoryInterface as WidgetRepository;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository;

class PostCatalogueController extends Controller
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

    public function index($id, $request, $page) {
        $payload = $request->query();
        $postCatalogue = $this->postCatalogueRepository->findByConditionPivot([
            ['id', '=', $id]
        ], 'posts', [], [], [
            __('config.conditionPublish')
        ], [], ['id', 'DESC'], ['users'], 5);
        $config = $this->config();
        $seo = [
            'meta_title' => $postCatalogue->meta_title,
            'meta_description' => $postCatalogue->meta_description,
            'meta_keyword' => $postCatalogue->meta_keyword,
            'canonical' => '/' . $postCatalogue->canonical,
        ];
        return view('frontend.postCatalogue.index', compact(
            'config',
            'seo',
            'postCatalogue'
        ));
    }


    private function config () {
        return [
            'js' => [
                "frontend/js/library/blog.js",
            ],
            'css' => [
                "frontend/css/blog.css",
            ], 
            'module' => 'postCatalogue',
        ];
    }
}