<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSlideRequest;
use App\Http\Requests\UpdateSlideRequest;
use Illuminate\Http\Request;
use App\Services\Interfaces\SlideServiceInterface as SlideService;
use App\Repositories\Interfaces\SlideRepositoryInterface as SlideRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SlideController extends Controller
{
    //

    protected $slideService;
    protected $slideRepository;

    public function __construct(
        SlideService $slideService,
        SlideRepository $slideRepository,
    )
    {
        $this->slideService = $slideService;
        $this->slideRepository = $slideRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'slide.index')) {
            $payload = $request->query();
            $slides = $this->slideService->paginate($payload);
            $config = $this->config();
            $config['method'] = 'index';
            return view('backend.slide.index', compact(
                'config',
                'slides'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function create() {
        if(Gate::allows('modules', 'slide.create')) {
            $config = $this->config();
            $config['method'] = 'create';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/slide.js';
            return view('backend.slide.store', compact(
                'config',
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function store(StoreSlideRequest $request) {
        $slide = $this->slideService->store($request->except('_token'));
        if($slide->id) {
            return redirect()->route('slide.index')->with('success', 'Thêm mới bản ghi thành công');
        }else{
            return redirect()->route('slide.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function edit($id) {
        if(Gate::allows('modules', 'slide.edit')) {
            $config = $this->config();
            $config['method'] = 'edit';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $config['js'][] = '/backend/js/library/slide.js';
            $slide = $this->slideRepository->findById($id);
            $customiseSlide = [];
            foreach($slide->item as $index => $item) {
                foreach($item as $key => $val) {
                    $customiseSlide[$key][$index] = $val;
                }
            }
            $slide->item = $customiseSlide;
            // dd($slide->item);
            return view('backend.slide.store', compact(
                'config',
                'slide',
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    } 

    public function update (UpdateSlideRequest $request, $id) {
        $payload = $request->except('_token');
        $flag = $this->slideService->update($id, $payload);
        if($flag) {
            return redirect()->route('slide.index')->with('success', 'Cập nhật bản ghi thành công');
        }else{
            return redirect()->route('slide.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function delete ($id) {
        if(Gate::allows('modules', 'slide.create')) {
            $config = $this->config();
            $config['method'] = 'delete';
            $slide = $this->slideRepository->findById($id);
            return view('backend.slide.delete', compact(
                'config',
                'slide'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function destroy ($id) {
        $flag = $this->slideService->destroy($id);
        if($flag) {
            return redirect()->route('slide.index')->with('success', 'Xóa bản ghi thành công');
        }else{
            return redirect()->route('slide.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại!');
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
            'module' => 'slide',
        ];
    }
}
