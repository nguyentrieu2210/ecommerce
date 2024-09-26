<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePromotionRequest;
use App\Http\Requests\UpdatePromotionRequest;
use Illuminate\Http\Request;
use App\Services\Interfaces\PromotionServiceInterface as PromotionService;
use App\Repositories\Interfaces\PromotionRepositoryInterface as PromotionRepository;
use Illuminate\Support\Facades\Gate;

class PromotionController extends Controller
{
    //

    protected $promotionService;
    protected $promotionRepository;

    public function __construct(
        PromotionService $promotionService,
        PromotionRepository $promotionRepository,
    )
    {
        $this->promotionService = $promotionService;
        $this->promotionRepository = $promotionRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'promotion.index')) {
            $payload = $request->query();
            $promotions = $this->promotionService->paginate($payload);
            $config = $this->config();
            $config['method'] = 'index';
            return view('backend.discount.promotion.index', compact(
                'config',
                'promotions'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function create() {
        if(Gate::allows('modules', 'promotion.create')) {
            $config = $this->config();
            $config['method'] = 'create';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/discount.js';
            $config['js'][] = '/backend/js/library/promotion.js';
            return view('backend.discount.promotion.store', compact(
                'config',
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function store(StorePromotionRequest $request) {
        $promotion = $this->promotionService->store($request->except('_token'));
        if($promotion->id) {
            return redirect()->route('promotion.index')->with('success', 'Thêm mới bản ghi thành công');
        }else{
            return redirect()->route('promotion.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function edit($id) {
        if(Gate::allows('modules', 'promotion.edit')) {
            $config = $this->config();
            $config['method'] = 'edit';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/discount.js';
            $config['js'][] = '/backend/js/library/promotion.js';
            $promotion = $this->promotionRepository->findById($id);
            return view('backend.discount.promotion.store', compact(
                'config',
                'promotion',
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    } 

    public function update (UpdatePromotionRequest $request, $id) {
        $payload = $request->except('_token');
        $flag = $this->promotionService->update($id, $payload);
        if($flag) {
            return redirect()->route('promotion.index')->with('success', 'Cập nhật bản ghi thành công');
        }else{
            return redirect()->route('promotion.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function delete ($id) {
        if(Gate::allows('modules', 'promotion.create')) {
            $config = $this->config();
            $config['method'] = 'delete';
            $promotion = $this->promotionRepository->findById($id);
            return view('backend.discount.promotion.delete', compact(
                'config',
                'promotion'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function destroy ($id) {
        $flag = $this->promotionService->destroy($id);
        if($flag) {
            return redirect()->route('promotion.index')->with('success', 'Xóa bản ghi thành công');
        }else{
            return redirect()->route('promotion.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại!');
        }
    }

    private function config () {
        return [
            'js' => [
                '/backend/js/plugins/switchery/switchery.js',
                '/backend/js/plugins/toastr/toastr.min.js',
                '/backend/js/plugins/datapicker/bootstrap-datepicker.js',
                'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js',
                '/backend/js/library/library.js',
            ],
            'css' => [
                '/backend/css/plugins/switchery/switchery.css',
                '/backend/css/plugins/toastr/toastr.min.css',
                'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css'
            ], 
            'module' => 'promotion',
        ];
    }
}
