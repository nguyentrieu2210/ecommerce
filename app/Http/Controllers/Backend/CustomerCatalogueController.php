<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerCatalogueRequest;
use App\Http\Requests\UpdateCustomerCatalogueRequest;
use Illuminate\Http\Request;
use App\Services\Interfaces\CustomerCatalogueServiceInterface as CustomerCatalogueService;
use App\Repositories\Interfaces\CustomerCatalogueRepositoryInterface as CustomerCatalogueRepository;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CustomerCatalogueController extends Controller
{
    //

    protected $customerCatalogueService;
    protected $customerCatalogueRepository;
    protected $provinceRepository;

    public function __construct(
        CustomerCatalogueService $customerCatalogueService,
        CustomerCatalogueRepository $customerCatalogueRepository,
        ProvinceRepository $provinceRepository
    )
    {
        $this->customerCatalogueService = $customerCatalogueService;
        $this->customerCatalogueRepository = $customerCatalogueRepository;
        $this->provinceRepository = $provinceRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'customerCatalogue.index')) {
            $payload = $request->query();
            $customerCatalogues = $this->customerCatalogueService->paginate($payload);
            $config = $this->config();
            $config['method'] = 'index';
            return view('backend.customer.customerCatalogue.index', compact(
                'config',
                'customerCatalogues'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function create() {
        if(Gate::allows('modules', 'customerCatalogue.create')) {
            $config = $this->config();
            $config['method'] = 'create';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            return view('backend.customer.customerCatalogue.store', compact(
                'config',
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function store(StoreCustomerCatalogueRequest $request) {
        $customerCatalogue = $this->customerCatalogueService->store($request->except('_token'));
        if($customerCatalogue->id) {
            return redirect()->route('customerCatalogue.index')->with('success', 'Thêm mới bản ghi thành công');
        }else{
            return redirect()->route('customerCatalogue.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function edit($id) {
        if(Gate::allows('modules', 'customerCatalogue.edit')) {
            $config = $this->config();
            $config['method'] = 'edit';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $customerCatalogue = $this->customerCatalogueRepository->findById($id);
            return view('backend.customer.customerCatalogue.store', compact(
                'config',
                'customerCatalogue',
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    } 

    public function update (UpdateCustomerCatalogueRequest $request, $id) {
        $payload = $request->except('_token');
        $flag = $this->customerCatalogueService->update($id, $payload);
        if($flag) {
            return redirect()->route('customerCatalogue.index')->with('success', 'Cập nhật bản ghi thành công');
        }else{
            return redirect()->route('customerCatalogue.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function delete ($id) {
        if(Gate::allows('modules', 'customerCatalogue.create')) {
            $config = $this->config();
            $config['method'] = 'delete';
            $customerCatalogue = $this->customerCatalogueRepository->findById($id);
            return view('backend.customer.customerCatalogue.delete', compact(
                'config',
                'customerCatalogue'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function destroy ($id) {
        $flag = $this->customerCatalogueService->destroy($id);
        if($flag) {
            return redirect()->route('customerCatalogue.index')->with('success', 'Xóa bản ghi thành công');
        }else{
            return redirect()->route('customerCatalogue.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại!');
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
            ], 
            'module' => 'customerCatalogue',
        ];
    }
}
