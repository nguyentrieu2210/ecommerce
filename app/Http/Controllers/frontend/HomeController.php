<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\SystemServiceInterface as SystemService;
use App\Repositories\Interfaces\SystemRepositoryInterface as SystemRepository;
use App\Repositories\Interfaces\MenuRepositoryInterface as MenuRepository;
use App\Repositories\Interfaces\SlideRepositoryInterface as SlideRepository;
use App\Repositories\Interfaces\PromotionRepositoryInterface as PromotionRepository;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;

class HomeController extends Controller
{
    //

    protected $systemService;
    protected $systemRepository;
    protected $menuRepository;
    protected $slideRepository;
    protected $promotionRepository;
    protected $postCatalogueRepository;

    public function __construct(
        SystemService $systemService,
        SystemRepository $systemRepository,
        MenuRepository $menuRepository,
        SlideRepository $slideRepository,
        PromotionRepository $promotionRepository,
        PostCatalogueRepository $postCatalogueRepository
    )
    {
        $this->systemService = $systemService;
        $this->systemRepository = $systemRepository;
        $this->systemRepository = $systemRepository;
        $this->menuRepository = $menuRepository;
        $this->slideRepository = $slideRepository;
        $this->promotionRepository = $promotionRepository;
        $this->postCatalogueRepository = $postCatalogueRepository;
    }

    public function index(Request $request) {
        $payload = $request->query();
        $temp = $this->systemRepository->getAll();
        foreach($temp as $key => $val) {
            $system[$val['keyword']] = $val['content'];
        }
        $config = $this->config();
        $slides = $this->slideRepository->findByCondition([
            __('config.conditionPublish')
        ]);
        $promotionForPostCatalogues = $this->promotionRepository->findPromotionForProductCatalogue([
            __('config.conditionPublish'),
            ['model', '=', 'productCatalogue'],
        ]);
        $seo = [
            'meta_title' => $system['seo_meta_title'],
            'meta_description' => $system['seo_meta_description'],
            'meta_keyword' => $system['seo_meta_keyword'],
            'canonical' => '/',
            'meta_image' => $system['seo_meta_images']
        ];
        $promotionData = setupPromotionForProductCatalogue($promotionForPostCatalogues);
        return view('frontend.home.index', compact(
            'config',
            'slides',
            'promotionData',
            'seo',
        ));
    }

    private function config () {
        return [
            'js' => [],
            'css' => [], 
            'module' => 'home',
        ];
    }
}