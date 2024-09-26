<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Requests\UpdateInfoRequest;
use Illuminate\Http\Request;
use App\Services\Interfaces\CustomerServiceInterface as CustomerService;
use App\Repositories\Interfaces\CustomerRepositoryInterface as CustomerRepository;
use App\Repositories\Interfaces\CustomerCatalogueRepositoryInterface as CustomerCatalogueRepository;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
use App\Repositories\Interfaces\SourceRepositoryInterface as SourceRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    //

    protected $customerService;
    protected $customerRepository;
    protected $customerCatalogueRepository;
    protected $provinceRepository;
    protected $sourceRepository;

    public function __construct(
        CustomerService $customerService,
        CustomerRepository $customerRepository,
        CustomerCatalogueRepository $customerCatalogueRepository,
        ProvinceRepository $provinceRepository,
        SourceRepository $sourceRepository
    )
    {
        $this->customerService = $customerService;
        $this->customerRepository = $customerRepository;
        $this->customerCatalogueRepository = $customerCatalogueRepository;
        $this->provinceRepository = $provinceRepository;
        $this->sourceRepository = $sourceRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'customer.index')) {
            $payload = $request->query();
            $customers = $this->customerService->paginate($payload);
            $customerCatalogues = $this->customerCatalogueRepository->selectByField(['id', 'name']);
            $config = $this->config();
            $config['method'] = 'index';
            return view('backend.customer.customer.index', compact(
                'customers',
                'config',
                'customerCatalogues'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function create() {
        if(Gate::allows('modules', 'customer.create')) {
            $config = $this->config();
            $config['method'] = 'create';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/location.js';
            $provinces = $this->provinceRepository->selectByField(['code', 'name']);
            $customerCatalogues = $this->customerCatalogueRepository->selectByField(['id', 'name']);
            $sources = $this->sourceRepository->selectByField(['id', 'name']);
            return view('backend.customer.customer.store', compact(
                'config',
                'customerCatalogues',
                'provinces',
                'sources'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function store(StoreCustomerRequest $request) {
        $customer = $this->customerService->store($request->except('_token', 'password_confirmation', 'district', 'ward'));
        if($customer->id) {
            return redirect()->route('customer.index')->with('success', 'Thêm mới bản ghi thành công');
        }else{
            return redirect()->route('customer.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function edit($id) {
        if(Gate::allows('modules', 'customer.edit')) {
            $config = $this->config();
            $config['method'] = 'edit';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/location.js';
            $provinces = $this->provinceRepository->selectByField(['code', 'name']);
            $customerCatalogues = $this->customerCatalogueRepository->selectByField(['id', 'name']);
            $customer = $this->customerRepository->findById($id);
            $sources = $this->sourceRepository->selectByField(['id', 'name']);
            return view('backend.customer.customer.store', compact(
                'config',
                'customerCatalogues',
                'provinces',
                'customer',
                'sources'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    } 

    public function update (UpdateCustomerRequest $request, $id) {
        $payload = $request->except('_token', 'district', 'ward');
        $flag = $this->customerService->update($id, $payload);
        if($flag) {
            return redirect()->route('customer.index')->with('success', 'Cập nhật bản ghi thành công');
        }else{
            return redirect()->route('customer.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function delete ($id) {
        if(Gate::allows('modules', 'customer.delete')) {
            $config = $this->config();
            $config['method'] = 'delete';
            $customer = $this->customerRepository->findById($id);
            return view('backend.customer.customer.delete', compact(
                'config',
                'customer'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function destroy ($id) {
        $flag = $this->customerService->destroy($id);
        if($flag) {
            return redirect()->route('customer.index')->with('success', 'Xóa bản ghi thành công');
        }else{
            return redirect()->route('customer.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại!');
        }
    }

    private function config () {
        return [
            'js' => [
                '/backend/js/plugins/switchery/switchery.js',
                '/backend/js/plugins/toastr/toastr.min.js',
                '/backend/js/plugins/datapicker/bootstrap-datepicker.js',
                '/backend/js/library/library.js',
            ],
            'css' => [
                '/backend/css/plugins/switchery/switchery.css',
                '/backend/css/plugins/toastr/toastr.min.css',
                '/backend/css/plugins/datapicker/datepicker3.css',
            ], 
            'module' => 'customer',
        ];
    }

    //FRONTEND
    public function updateInfo (UpdateInfoRequest $request, $id) {
        $payload = $request->except('_token');
        $flag = $this->customerService->update($id, $payload);
        if($flag) {
            return redirect()->route('account.info')->with('success', 'Cập nhật thông tin tài khoản thành công');
        }else{
            return redirect()->route('account.info')->with('error', 'Cập nhật thông tin tài khoản không thành công. Hãy thử lại!');
        }
    }

    public function updatePassword (Request $request, $id) {
        $payload = $request->except('_token');
        $password = Auth::guard('customer')->user()->password;
        if(!Hash::check($payload['password'], $password)) {
            return redirect()->back()->with('error', 'Mật khẩu cũ không đúng. Vui lòng thử lại');
        }
        $temp['password'] = Hash::make($payload['new_password']);
        $flag = $this->customerService->update($id, $temp);
        if($flag) {
            return redirect()->route('account.repassword')->with('success', 'Cập nhật mật khẩu cho tài khoản thành công');
        }else{
            return redirect()->route('account.repassword')->with('error', 'Cập nhật mật khẩu cho tài khoản không thành công. Hãy thử lại!');
        }
    }
}
