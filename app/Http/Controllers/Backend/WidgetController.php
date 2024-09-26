<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWidgetRequest;
use App\Http\Requests\UpdateWidgetRequest;
use Illuminate\Http\Request;
use App\Services\Interfaces\WidgetServiceInterface as WidgetService;
use App\Repositories\Interfaces\WidgetRepositoryInterface as WidgetRepository;
use Illuminate\Support\Facades\Gate;

class WidgetController extends Controller
{
    //

    protected $widgetService;
    protected $widgetRepository;

    public function __construct(
        WidgetService $widgetService,
        WidgetRepository $widgetRepository,
    )
    {
        $this->widgetService = $widgetService;
        $this->widgetRepository = $widgetRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'widget.index')) {
            $payload = $request->query();
            $widgets = $this->widgetService->paginate($payload);
            $config = $this->config();
            $config['method'] = 'index';
            return view('backend.widget.index', compact(
                'config',
                'widgets'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function create() {
        if(Gate::allows('modules', 'widget.create')) {
            $config = $this->config();
            $config['method'] = 'create';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/album.js';
            $config['js'][] = '/backend/js/library/searchModel.js';
            return view('backend.widget.store', compact(
                'config',
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function store(StoreWidgetRequest $request) {
        $widget = $this->widgetService->store($request->except('_token', 'model_name', 'model_image', 'keyword_search'));
        if($widget->id) {
            return redirect()->route('widget.index')->with('success', 'Thêm mới bản ghi thành công');
        }else{
            return redirect()->route('widget.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function edit($id) {
        if(Gate::allows('modules', 'widget.edit')) {
            $config = $this->config();
            $config['method'] = 'edit';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/album.js';
            $config['js'][] = '/backend/js/library/searchModel.js';
            $widget = $this->widgetRepository->findById($id);
            $instanceRepository = setupModelRepository($widget->model);
            $models = app($instanceRepository)->findByCondition([
                ['publish', '=', 2]
            ], [], 'id', json_decode($widget->model_id));
            // dd($models);
            return view('backend.widget.store', compact(
                'config',
                'widget',
                'models'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    } 

    public function update (UpdateWidgetRequest $request, $id) {
        $payload = $request->except('_token', 'model_name', 'model_image', 'keyword_search');
        $flag = $this->widgetService->update($id, $payload);
        if($flag) {
            return redirect()->route('widget.index')->with('success', 'Cập nhật bản ghi thành công');
        }else{
            return redirect()->route('widget.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function delete ($id) {
        if(Gate::allows('modules', 'widget.create')) {
            $config = $this->config();
            $config['method'] = 'delete';
            $widget = $this->widgetRepository->findById($id);
            return view('backend.widget.delete', compact(
                'config',
                'widget'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function destroy ($id) {
        $flag = $this->widgetService->destroy($id);
        if($flag) {
            return redirect()->route('widget.index')->with('success', 'Xóa bản ghi thành công');
        }else{
            return redirect()->route('widget.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại!');
        }
    }

    private function config () {
        return [
            'js' => [
                '/backend/js/plugins/switchery/switchery.js',
                '/backend/js/plugins/toastr/toastr.min.js',
                '/backend/js/plugins/datapicker/bootstrap-datepicker.js',
                '/backend/plugins/ckeditor/ckeditor.js',
                '/backend/js/library/library.js',
            ],
            'css' => [
                '/backend/css/plugins/switchery/switchery.css',
                '/backend/css/plugins/toastr/toastr.min.css',
            ], 
            'module' => 'widget',
        ];
    }
}
