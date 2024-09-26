<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\SystemServiceInterface as SystemService;
use App\Repositories\Interfaces\SystemRepositoryInterface as SystemRepository;
use App\Repositories\Interfaces\MenuRepositoryInterface as MenuRepository;
use App\Repositories\Interfaces\WidgetRepositoryInterface as WidgetRepository;
use App\Repositories\Interfaces\SlideRepositoryInterface as SlideRepository;
use App\Repositories\Interfaces\PromotionRepositoryInterface as PromotionRepository;
use App\Services\Interfaces\WidgetServiceInterface as WidgetService;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use Illuminate\Support\Facades\Gate;

class SystemController extends Controller
{
    //

    protected $systemService;
    protected $systemRepository;
    protected $menuRepository;
    protected $widgetRepository;
    protected $slideRepository;
    protected $promotionRepository;
    protected $widgetService;
    protected $productCatalogueRepository;

    public function __construct(
        SystemService $systemService,
        SystemRepository $systemRepository,
        MenuRepository $menuRepository,
        WidgetRepository $widgetRepository,
        SlideRepository $slideRepository,
        PromotionRepository $promotionRepository,
        WidgetService $widgetService,
        ProductCatalogueRepository $productCatalogueRepository
    )
    {
        $this->systemService = $systemService;
        $this->systemRepository = $systemRepository;
        $this->systemRepository = $systemRepository;
        $this->menuRepository = $menuRepository;
        $this->widgetRepository = $widgetRepository;
        $this->slideRepository = $slideRepository;
        $this->promotionRepository = $promotionRepository;
        $this->widgetService = $widgetService;
        $this->productCatalogueRepository = $productCatalogueRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'system.index')) {
            $payload = $request->query();
            $temp = $this->systemRepository->getAll();
            foreach($temp as $key => $val) {
                $system[$val['keyword']] = $val['content'];
            }
            // dd($system);
            $config = $this->config();
            $config['method'] = 'index';
            return view('backend.configuration.system.index', compact(
                'config',
                'system'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function store(Request $request) {
        $flag = $this->systemService->store($request->except('_token'));
        if($flag) {
            return redirect()->route('system.index')->with('success', 'Cập nhật thành công');
        }else{
            return redirect()->route('system.index')->with('error', 'Cập nhật không thành công. Hãy thử lại!');
        }
    }

    public function theme(Request $request) {
        if(Gate::allows('modules', 'system.theme')) {
            $payload = $request->query();
            $temp = $this->systemRepository->getAll();
            foreach($temp as $key => $val) {
                $system[$val['keyword']] = $val['content'];
            }
            $cssFrontend = [
                "frontend/css/bootstrap.css",
                "frontend/font/css/all.css",
                "frontend/css/owl.carousel.min.css",
                "frontend/css/owl.theme.default.min.css",
                "frontend/css/header.css",
                "frontend/css/footer.css",
                "frontend/css/style_frontend.css",
                "https://unpkg.com/swiper/swiper-bundle.min.css",
                "frontend/css/product.css",
                "backend/css/theme.css"
            ];
            $jsFrontend = [
                "frontend/js/owl.carousel.min.js",
                "https://unpkg.com/swiper/swiper-bundle.min.js",
                "frontend/js/library/frontend.js"
            ];
            $config = $this->config();
            
            $config['css'] = $config['css'] + $cssFrontend;
            $config['js'][] = '/backend/js/library/theme.js';
            $config['js'] = array_merge($config['js'], $jsFrontend);
            $config['method'] = 'theme';
            $menus = $this->menuRepository->findByCondition([
                __('config.conditionPublish')
            ], [], '', [], '', [], ['links']);
            $widgets = $this->widgetRepository->findByCondition([
                __('config.conditionPublish')
            ]);
            $slides = $this->slideRepository->findByCondition([
                __('config.conditionPublish')
            ]);
            $promotionForPostCatalogues = $this->promotionRepository->findPromotionForProductCatalogue([
                __('config.conditionPublish'),
                ['model', '=', 'productCatalogue'],
            ]);
            $promotionData = setupPromotionForProductCatalogue($promotionForPostCatalogues);
            $dataByWidgets = $this->widgetService->getDataByWidgets($widgets);
            $categories = $this->productCatalogueRepository->findByCondition([
                __('config.conditionPublish')
            ], ['_lft', 'ASC'], 'level', [0, 1]);
            // dd($promotionData);
            return view('backend.configuration.system.theme', compact(
                'config',
                'system',
                'menus',
                'widgets',
                'slides',
                'dataByWidgets',
                'promotionData',
                'categories'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function editor(Request $request) {
        $flag = $this->systemService->editor($request->except('_token'));
        if($flag) {
            return redirect()->route('system.theme')->with('success', 'Cập nhật thành công');
        }else{
            return redirect()->route('system.theme')->with('error', 'Cập nhật không thành công. Hãy thử lại!');
        }
    }

    private function config () {
        return [
            'js' => [
                '/backend/js/plugins/toastr/toastr.min.js',
                '/backend/plugins/ckfinder_2/ckfinder.js',
                '/backend/plugins/ckeditor/ckeditor.js',
                '/backend/js/library/library.js',
            ],
            'css' => [
                '/backend/css/plugins/toastr/toastr.min.css',
            ], 
            'module' => 'system',
        ];
    }
}