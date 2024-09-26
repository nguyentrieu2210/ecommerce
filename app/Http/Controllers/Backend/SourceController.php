<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSourceRequest;
use App\Http\Requests\UpdateSourceRequest;
use Illuminate\Http\Request;
use App\Services\Interfaces\SourceServiceInterface as SourceService;
use App\Repositories\Interfaces\SourceRepositoryInterface as SourceRepository;
use Illuminate\Support\Facades\Gate;

class SourceController extends Controller
{
    //

    protected $sourceService;
    protected $sourceRepository;

    public function __construct(
        SourceService $sourceService,
        SourceRepository $sourceRepository,
    )
    {
        $this->sourceService = $sourceService;
        $this->sourceRepository = $sourceRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'source.index')) {
            $payload = $request->query();
            $sources = $this->sourceService->paginate($payload);
            $config = $this->config();
            $config['method'] = 'index';
            return view('backend.customer.source.index', compact(
                'config',
                'sources'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function create() {
        if(Gate::allows('modules', 'source.create')) {
            $config = $this->config();
            $config['method'] = 'create';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            return view('backend.customer.source.store', compact(
                'config',
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function store(StoreSourceRequest $request) {
        $source = $this->sourceService->store($request->except('_token'));
        if($source->id) {
            return redirect()->route('source.index')->with('success', 'Thêm mới bản ghi thành công');
        }else{
            return redirect()->route('source.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function edit($id) {
        if(Gate::allows('modules', 'source.edit')) {
            $config = $this->config();
            $config['method'] = 'edit';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $source = $this->sourceRepository->findById($id);
            return view('backend.customer.source.store', compact(
                'config',
                'source',
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    } 

    public function update (UpdateSourceRequest $request, $id) {
        $payload = $request->except('_token');
        $flag = $this->sourceService->update($id, $payload);
        if($flag) {
            return redirect()->route('source.index')->with('success', 'Cập nhật bản ghi thành công');
        }else{
            return redirect()->route('source.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function delete ($id) {
        if(Gate::allows('modules', 'source.create')) {
            $config = $this->config();
            $config['method'] = 'delete';
            $source = $this->sourceRepository->findById($id);
            return view('backend.customer.source.delete', compact(
                'config',
                'source'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function destroy ($id) {
        $flag = $this->sourceService->destroy($id);
        if($flag) {
            return redirect()->route('source.index')->with('success', 'Xóa bản ghi thành công');
        }else{
            return redirect()->route('source.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại!');
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
            'module' => 'source',
        ];
    }
}
