<div class="col-lg-12" style="margin-top: -25px">
    <div class="ibox-content">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th class="text-center"><input type="checkbox" class="checkAll " name="id" id=""></th>
                <th>Tên</th>
                <th>Bộ ảnh</th>
                <th>Nội dung</th>
                <th class="text-center">Số sao</th>
                <th class="text-center">Lượt thích</th>
                <th>Loại bình luận</th>
                <th class="text-center">Số điện thoại</th>
                <th class="text-center">Ngày đăng</th>
                <th class="text-center">Trạng thái duyệt</th>
                <th class="text-center">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach($comments as $comment)
            <tr>
                <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$comment->id}}"></td>
                <td>{{$comment->name}}</td>
                <td class="text-center flex">
                    @if(isset($comment->album))
                        @foreach($comment->album as $key => $image)
                            <img style="" class="style-avatar image-comment" src="{{asset($image)}}" alt="">
                        @endforeach
                    @endif
                </td>
                <td>{{$comment->content}}</td>
                <td class="text-center">{{$comment->star_rating}}</td>
                <td class="text-center">{{$comment->likes_count}}</td>
                <td>{{$comment->model == 'product' ? 'Bình luận sản phẩm' : 'Bình luận bài viết'}}</td>
                <td class="text-center">{{$comment->phone}}</td>
                <td class="text-center">{{formatDateTimeFromSql(formatStringToCarbon($comment->created_at))}}</td>
                <td class="text-center">
                    <input type="checkbox" {{$comment->publish == 1 ? '' : 'checked'}} class="js-switch" style="display: none;">
                </td>
                <td class="text-center">
                    <a href="{{route('comment.delete', ['id' => $comment->id])}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{$comments->appends(request()->query())->links("pagination::bootstrap-4")}}
    </div>
</div>