<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;
use App\Services\Interfaces\PostServiceInterface as PostService;
use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository;
use Illuminate\Support\Facades\Gate;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;

class PostController extends Controller
{
    //

    protected $postService;
    protected $postRepository;
    protected $postCatalogueRepository;

    public function __construct(
        PostService $postService,
        PostCatalogueRepository $postCatalogueRepository,
        PostRepository $postRepository
    )
    {
        $this->postService = $postService;
        $this->postRepository = $postRepository;
        $this->postCatalogueRepository = $postCatalogueRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'post.index')) {
            $payload = $request->query();
            $posts = $this->postService->paginate($payload);  
            // dd($posts);
            $config = $this->config();
            $config['method'] = 'index';
            $postCatalogues = customizeNestedset($this->postCatalogueRepository->findByCondition([], ['_lft', 'ASC']));
            return view('backend.post.post.index', compact(
                'config',
                'posts',
                'postCatalogues'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function create() {
        if(Gate::allows('modules', 'post.create')) {
            $config = $this->config();
            $config['method'] = 'create';
            $config['js'][] = '/backend/js/library/seo.js';
            $postCatalogues = customizeNestedset($this->postCatalogueRepository->findByCondition([], ['_lft', 'ASC']));
            return view('backend.post.post.store', compact(
                'config',
                'postCatalogues'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function store(StorePostRequest $request) {
        $flag = $this->postService->store($request->except('_token', 'title', 'width', 'height', 'link'));
        if($flag) {
            return redirect()->route('post.index')->with('success', 'Thêm mới bản ghi thành công');
        }else{
            return redirect()->route('post.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function edit($id) {
        if(Gate::allows('modules', 'post.edit')) {
            $config = $this->config();
            $config['method'] = 'edit';
            $config['js'][] = '/backend/js/library/seo.js';
            $post = $this->postRepository->findById($id, "post_catalogues");
            // $postCatalogueIds = $post->post_catalogues->pluck('id')->toArray();
            $postCatalogues = customizeNestedset($this->postCatalogueRepository->findByCondition([], ['_lft', 'ASC']));
            return view('backend.post.post.store', compact(
                'config',
                'post',
                'postCatalogues',
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    } 

    public function update (UpdatePostRequest $request, $id) {
        $payload = $request->except('_token', 'title', 'width', 'height', 'link');
        $flag = $this->postService->update($id, $payload);
        if($flag) {
            return redirect()->route('post.index')->with('success', 'Cập nhật bản ghi thành công');
        }else{
            return redirect()->route('post.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function delete ($id) {
        if(Gate::allows('modules', 'post.delete')) {
            $config = $this->config();
            $config['method'] = 'delete';
            $post = $this->postRepository->findById($id);
            return view('backend.post.post.delete', compact(
                'config',
                'post'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function destroy ($id) {
        $flag = $this->postService->destroy($id);
        if($flag) {
            return redirect()->route('post.index')->with('success', 'Xóa bản ghi thành công');
        }else{
            return redirect()->route('post.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại!');
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
            'module' => 'post',
        ];
    }
}
