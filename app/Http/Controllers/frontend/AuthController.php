<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
use App\Services\Interfaces\CustomerServiceInterface as CustomerService;

class AuthController extends Controller
{
    //
    protected $provinceRepository;
    protected $customerService;

    public function __construct(
        ProvinceRepository $provinceRepository,
        CustomerService $customerService
    )
    {
        $this->provinceRepository = $provinceRepository;
        $this->customerService = $customerService;
    }

    public function index(Request $request) {
        $config['css'][] = "frontend/css/login.css";
        $config['js'][] = "frontend/js/library/validate.js";
        $seo = $this->seo();
        $seo['meta_title'] = 'Đăng nhập tài khoản';
        return view('frontend.auth.login', compact(
            'config',
            'seo',
        ));
    }

    public function login(Request $request) {
        $account = $request->only('email', 'password');

        if(Auth::guard('customer')->attempt($account)) {
            if(Auth::guard('customer')->user()->publish !== 2) {
                Auth::guard('customer')->logout();
                return redirect()->back()->with('error', 'Tài khoản của của đã bị khóa. Hãy thử đăng nhập bằng tài khoản khác hoặc thử lại sau');
            }
            return redirect('/')->with('success', 'Đăng nhập thành công');
        }

        return redirect()->back()->with('error', 'Email hoặc mật khẩu không chính xác');
    }

    public function logout() {
        Auth::guard('customer')->logout();
        return redirect()->route('index');
    }

    public function register (Request $request) {
        $config = $this->config();
        $config['css'][] = 'frontend/css/login.css';
        $provinces = $this->provinceRepository->selectByField(['code', 'name']);
        $seo = $this->seo();
        $seo['meta_title'] = 'Đăng kí tài khoản';
        return view('frontend.auth.register', compact(
            'config',
            'seo',
            'provinces'
        ));
    }

    public function store (Request $request) {
        $payload = $request->except('_token', 're_password');
        $payload['customer_catalogue_id'] = __('config')['newCustomer'];
        $payload['source_id'] = __('config')['websiteSource'];
        $customer = $this->customerService->store($payload);

        if($customer->id) {
            return redirect()->route('index')->with('success', 'Đăng kí tài khoản thành công');
        }else{
            return redirect()->route('index')->with('error', 'Đăng kí tài khoản không thành công. Hãy thử lại!');
        }
    }

    private function seo () {
        return [
            'meta_title' => '',
            'meta_description' => '',
            'meta_keyword' => '',
            'canonical' => '/',
            'meta_image' => ''
        ];
    }

    public function config () {
        return [
            'css' => [
                'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css',
                '/backend/css/plugins/select2/select2.min.css'
            ],
            'js' => [
                'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js',
                '/backend/js/plugins/select2/select2.full.min.js',
                '/backend/js/library/location.js',
                'frontend/js/library/validate.js'
            ]
        ];
    }
}