<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use Illuminate\Http\Request;
use App\Services\Interfaces\CouponServiceInterface as CouponService;
use App\Repositories\Interfaces\CouponRepositoryInterface as CouponRepository;
use Illuminate\Support\Facades\Gate;
use App\Repositories\Interfaces\CustomerCatalogueRepositoryInterface as CustomerCatalogueRepository;

class CouponController extends Controller
{
    //

    protected $couponService;
    protected $couponRepository;
    protected $customerCatalogueRepository;

    public function __construct(
        CouponService $couponService,
        CouponRepository $couponRepository,
        CustomerCatalogueRepository $customerCatalogueRepository
    )
    {
        $this->couponService = $couponService;
        $this->couponRepository = $couponRepository;
        $this->customerCatalogueRepository = $customerCatalogueRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'coupon.index')) {
            $payload = $request->query();
            $coupons = $this->couponService->paginate($payload);
            $config = $this->config();
            $config['method'] = 'index';
            return view('backend.discount.coupon.index', compact(
                'config',
                'coupons'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function create() {
        if(Gate::allows('modules', 'coupon.create')) {
            $config = $this->config();
            $customerCatalogues = $this->customerCatalogueRepository->findByCondition([
                ['publish', '=', 2]
            ]);
            $config['method'] = 'create';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/discount.js';
            $config['js'][] = '/backend/js/library/coupon.js';
            return view('backend.discount.coupon.store', compact(
                'config',
                'customerCatalogues'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function store(StoreCouponRequest $request) {
        $coupon = $this->couponService->store($request->except('_token'));
        if($coupon->id) {
            return redirect()->route('coupon.index')->with('success', 'Thêm mới bản ghi thành công');
        }else{
            return redirect()->route('coupon.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function edit($id) {
        if(Gate::allows('modules', 'coupon.edit')) {
            $customerCatalogues = $this->customerCatalogueRepository->findByCondition([
                ['publish', '=', 2]
            ]);
            $config = $this->config();
            $config['method'] = 'edit';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/discount.js';
            $config['js'][] = '/backend/js/library/coupon.js';
            $coupon = $this->couponRepository->findById($id);
            return view('backend.discount.coupon.store', compact(
                'config',
                'coupon',
                'customerCatalogues'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    } 

    public function update (UpdateCouponRequest $request, $id) {
        $payload = $request->except('_token');
        $flag = $this->couponService->update($id, $payload);
        if($flag) {
            return redirect()->route('coupon.index')->with('success', 'Cập nhật bản ghi thành công');
        }else{
            return redirect()->route('coupon.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function delete ($id) {
        if(Gate::allows('modules', 'coupon.create')) {
            $config = $this->config();
            $config['method'] = 'delete';
            $coupon = $this->couponRepository->findById($id);
            return view('backend.discount.coupon.delete', compact(
                'config',
                'coupon'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function destroy ($id) {
        $flag = $this->couponService->destroy($id);
        if($flag) {
            return redirect()->route('coupon.index')->with('success', 'Xóa bản ghi thành công');
        }else{
            return redirect()->route('coupon.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại!');
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
                'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css',
                '/backend/css/plugins/toastr/toastr.min.css',
            ], 
            'module' => 'coupon',
        ];
    }
}
