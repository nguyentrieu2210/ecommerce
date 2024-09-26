<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostCatalogueRequest;
use App\Http\Requests\UpdatePostCatalogueRequest;
use Illuminate\Http\Request;
use App\Services\Interfaces\PostCatalogueServiceInterface as PostCatalogueService;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
use Illuminate\Support\Facades\Gate;

class PostCatalogueController extends Controller
{
    //

    protected $postCatalogueService;
    protected $postCatalogueRepository;

    public function __construct(
        PostCatalogueService $postCatalogueService,
        PostCatalogueRepository $postCatalogueRepository
    )
    {
        $this->postCatalogueService = $postCatalogueService;
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->postCatalogueRepository = $postCatalogueRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'postCatalogue.index')) {
            $payload = $request->query();
            $postCatalogues = customizeNestedset($this->postCatalogueService->paginate($payload));
            $config = $this->config();
            $config['method'] = 'index';
            return view('backend.post.postCatalogue.index', compact(
                'config',
                'postCatalogues'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function create() {
        if(Gate::allows('modules', 'postCatalogue.create')) {
            $config = $this->config();
            $config['method'] = 'create';
            $config['js'][] = '/backend/js/library/seo.js';
            $postCatalogues = customizeNestedset($this->postCatalogueRepository->findByCondition([], ['_lft', 'ASC']));
            return view('backend.post.postCatalogue.store', compact(
                'config',
                'postCatalogues'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function store(StorePostCatalogueRequest $request) {
        $flag = $this->postCatalogueService->store($request->except('_token', 'title', 'width', 'height', 'link'));
        if($flag) {
            return redirect()->route('postCatalogue.index')->with('success', 'Thêm mới bản ghi thành công');
        }else{
            return redirect()->route('postCatalogue.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function edit($id) {
        if(Gate::allows('modules', 'postCatalogue.edit')) {
            $config = $this->config();
            $config['method'] = 'edit';
            $config['js'][] = '/backend/plugins/sortui/jquery-ui.js';
            $config['js'][] = '/backend/js/library/seo.js';
            $postCatalogue = $this->postCatalogueRepository->findById($id);
            $postCatalogues = customizeNestedset($this->postCatalogueRepository->findByCondition([
                ['id', '<>', $id]
            ], ['_lft', 'ASC']));
            return view('backend.post.postCatalogue.store', compact(
                'config',
                'postCatalogue',
                'postCatalogues'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    } 

    public function update (UpdatePostCatalogueRequest $request, $id) {
        $payload = $request->except('_token', 'title', 'width', 'height', 'link');
        $flag = $this->postCatalogueService->update($id, $payload);
        if($flag) {
            return redirect()->route('postCatalogue.index')->with('success', 'Cập nhật bản ghi thành công');
        }else{
            return redirect()->route('postCatalogue.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function delete ($id) {
        if(Gate::allows('modules', 'postCatalogue.delete')) {
            $config = $this->config();
            $config['method'] = 'delete';
            $postCatalogue = $this->postCatalogueRepository->findById($id);
            return view('backend.post.postCatalogue.delete', compact(
                'config',
                'postCatalogue'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function destroy ($id) {
        $flag = $this->postCatalogueService->destroy($id);
        if($flag) {
            return redirect()->route('postCatalogue.index')->with('success', 'Xóa bản ghi thành công');
        }else{
            return redirect()->route('postCatalogue.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại!');
        }
    }

    private function config () {
        return [
            'js' => [
                '/backend/js/plugins/switchery/switchery.js',
                '/backend/js/plugins/toastr/toastr.min.js',
                '/backend/js/plugins/datapicker/bootstrap-datepicker.js',
                '/backend/plugins/ckfinder_2/ckfinder.js',
                '/backend/plugins/ckeditor/ckeditor.js',
                '/backend/js/plugins/blueimp/jquery.blueimp-gallery.min.js',
                '/backend/js/library/library.js',
                '/backend/js/library/album.js',
            ],
            'css' => [
                '/backend/css/plugins/switchery/switchery.css',
                '/backend/css/plugins/toastr/toastr.min.css',
                '/backend/css/plugins/blueimp/css/blueimp-gallery.min.css',
            ], 
            'module' => 'postCatalogue',
        ];
    }
}
