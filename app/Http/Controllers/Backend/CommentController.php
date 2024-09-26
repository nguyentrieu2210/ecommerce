<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\CommentServiceInterface as CommentService;
use App\Repositories\Interfaces\CommentRepositoryInterface as CommentRepository;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    //

    protected $commentService;
    protected $commentRepository;

    public function __construct(
        CommentService $commentService,
        CommentRepository $commentRepository,
    )
    {
        $this->commentService = $commentService;
        $this->commentRepository = $commentRepository;
    }

    public function index(Request $request) {
        if(Gate::allows('modules', 'comment.index')) {
            $payload = $request->query();
            $comments = $this->commentService->paginate($payload);
            $config = $this->config();
            $config['method'] = 'index';
            return view('backend.comment.productComment.index', compact(
                'config',
                'comments'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function edit($id) {
        if(Gate::allows('modules', 'comment.edit')) {
            $config = $this->config();
            $config['method'] = 'edit';
            $config['js'][] = '/backend/plugins/ckfinder_2/ckfinder.js';
            $comment = $this->commentRepository->findById($id);
            return view('backend.comment.productComment.store', compact(
                'config',
                'comment',
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    } 

    public function update (Request $request, $id) {
        $payload = $request->except('_token');
        $flag = $this->commentService->update($id, $payload);
        if($flag) {
            return redirect()->route('comment.index')->with('success', 'Cập nhật bản ghi thành công');
        }else{
            return redirect()->route('comment.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại!');
        }
    }

    public function delete ($id) {
        if(Gate::allows('modules', 'comment.delete')) {
            $config = $this->config();
            $config['method'] = 'delete';
            $comment = $this->commentRepository->findById($id);
            return view('backend.comment.productComment.delete', compact(
                'config',
                'comment'
            ));
        }else{
            return redirect()->back()->with('error', 'Bạn không đủ thẩm quyền để thực hiện chức năng này!');
        }
    }

    public function destroy ($id) {
        $flag = $this->commentService->destroy($id);
        if($flag) {
            return redirect()->route('comment.index')->with('success', 'Xóa bản ghi thành công');
        }else{
            return redirect()->route('comment.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại!');
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
            'module' => 'comment',
        ];
    }
}
