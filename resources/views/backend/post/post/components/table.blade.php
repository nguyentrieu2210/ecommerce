<div class="col-lg-12" style="margin-top: -25px">
    <div class="ibox-content">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th class="text-center"><input type="checkbox" class="checkAll " name="id" id=""></th>
                <th style="width: 30%">Tên bài viết</th>
                <th>Tác giả</th>
                <th>Nhóm bài viết (chính)</th>
                <th>Nhóm bài viết (phụ)</th>
                <th class="text-center">Ngày đăng</th>
                <th class="text-center">Ngày cập nhật</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach($posts as $post)
            <tr>
                <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$post->id}}"></td>
                <td class="flex post-info">
                    <span>
                        <img class="avatar-style mr10" src="{{$post->image !== null ? asset($post->image) : asset('/backend/img/empty-image.png')}}" alt="">
                    </span>
                    <span class="text-link fs14">
                        {{$post->name}}
                    </span>
                </td>
                <td>
                    <div class="flex">
                        <span class="mr10">
                            <img class="avatar-author" src="{{$post->users->image !== null ? $post->users->image : asset('/backend/img/empty-avatar.png')}}" alt="">
                        </span>
                        <div class="author-r">
                            <span class="name-author">{{$post->users->name}}</span>
                            <span>Số bài viết: <strong>{{$post->users->posts->count()}}</strong></span>
                        </div>
                    </div>
                </td>
                @php 
                    $post_catalogues = $post->post_catalogues;
                    $post_catalogue = null;
                    for($i = 0; $i < count($post_catalogues); $i++) {
                        if($post_catalogues[$i]->id == $post->post_catalogue_id) {
                            $post_catalogue = $post_catalogues[$i];
                        }
                    }
                @endphp
                <td>
                    <span class="style-text">{{$post_catalogue->name}}</span>
                </td>
                <td>
                    @if(count($post_catalogues))
                        @for($i = 0; $i < count($post_catalogues); $i++)
                            @if($post_catalogues[$i]->id == $post->post_catalogue_id)
                                @continue
                            @endif
                            <span class="style-text inline-block mb5">{{$post_catalogues[$i]->name}}</span>
                        @endfor
                    @endif
                </td>
                <td class="text-center">{{formatDateFromSql($post->created_at)}}</td>
                <td class="text-center">{{formatDateFromSql($post->updated_at)}}</td>
                <td class="text-center">
                    <input type="checkbox" {{$post->publish == 1 ? '' : 'checked'}} class="js-switch" style="display: none;">
                </td>
                <td class="text-center">
                    <a href="{{ route('post.edit', ['id' => $post->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                    <a href="{{route('post.delete', ['id' => $post->id])}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{$posts->appends(request()->query())->links("pagination::bootstrap-4")}}
    </div>
</div>
