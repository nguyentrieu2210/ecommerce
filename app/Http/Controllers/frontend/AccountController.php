<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\CustomerRepositoryInterface as CustomerRepository;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;

class AccountController extends Controller
{
    //

    protected $customerRepository;
    protected $provinceRepository;

    public function __construct(
        CustomerRepository $customerRepository,
        ProvinceRepository $provinceRepository
    )
    {
        $this->customerRepository = $customerRepository;
        $this->provinceRepository = $provinceRepository;
    }

    public function info(Request $request) {
        $config = [
            'css' => [
                'frontend/css/account.css',
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
        $seo = $this->seo();
        $seo['meta_title'] = 'Thông tin tài khoản';
        $provinces = $this->provinceRepository->selectByField(['code', 'name']);
        return view('frontend.account.info', compact(
            'config',
            'seo',
            'provinces'
        ));
    }

    public function order(Request $request) {
        $config['css'][] = "frontend/css/account.css";
        $seo = $this->seo();
        $seo['meta_title'] = 'Quản lý đơn hàng';
        return view('frontend.account.order', compact(
            'config',
            'seo',
        ));
    }

    public function repassword(Request $request) {
        $config['css'][] = "frontend/css/account.css";
        $config['js'][] = "frontend/js/library/validate.js";
        $seo = $this->seo();
        $seo['meta_title'] = 'Cập nhật mật khẩu';
        return view('frontend.account.re_password', compact(
            'config',
            'seo',
        ));
    }

    private function seo () {
        return [
            'meta_title' => 'Thông tin tài khoản',
            'meta_description' => '',
            'meta_keyword' => '',
            'canonical' => '/',
            'meta_image' => ''
        ];
    }
}