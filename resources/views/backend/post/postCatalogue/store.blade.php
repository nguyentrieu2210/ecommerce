@extends('backend.layouts.layout')
@section('title', __('breadcrump.' . $config['module'] . '.' . $config['method'] . '.title'))
@section('content')
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="wrapper wrapper-content animated fadeInRight">
        @include('backend.layouts.components.errors')
        @php
            $route =
                $config['method'] == 'create'
                    ? route('postCatalogue.store')
                    : route('postCatalogue.update', ['id' => $postCatalogue->id]);
        @endphp
        <form method="post" action="{{ $route }}">
            @csrf
            <div class="row">
                <div class="col-lg-9">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>THÔNG TIN CHUNG</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content" style="">
                            <div class="form-group"><label>Tiêu đề <span class="text-danger">(*)</span></label>
                                <input type="text"
                                    value="{{ old('name') ?? (isset($postCatalogue) ? $postCatalogue->name : '') }}"
                                    placeholder="Nhập tên nhóm bài viết..." name="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Mô tả ngắn</label>
                                <textarea data-height="150" class="form-control setupCkeditor" name="description" id="" cols="30"
                                    rows="10">{{ old('description') ?? (isset($postCatalogue) && $postCatalogue->description !== null ? $postCatalogue->description : '') }}</textarea>
                            </div>
                            <div class="content">
                                <div class="form-group">
                                    <div class="flex-space-between">
                                        <label for="">Nội dung <small class="text-guide">(Để thêm ảnh cho Editor thì
                                                bạn click vào nút chọn ảnh ở góc bên phải)</small></label>
                                        <label data-id="content" class="empty-image forEditor btn btn-primary">Chọn
                                            ảnh</label>
                                    </div>
                                    <textarea id="content" data-height="500" class="form-control setupCkeditor" name="content" id=""
                                        cols="30" rows="10">{{ old('content') ?? (isset($postCatalogue) && $postCatalogue->content !== null ? $postCatalogue->content : '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('backend.layouts.components.seo', ['model' => isset($postCatalogue) ? $postCatalogue : null])
                </div>
                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>CẤU HÌNH CHUNG</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="">Chọn danh mục cha</label>
                                        <select style="width:100%" name="parent_id" class="ml setupSelect2">
                                            <option value="0">[Root]</option>
                                            @foreach ($postCatalogues as $key => $val)
                                                <option
                                                    {{ (old('parent_id') ?? (isset($postCatalogue) ? $postCatalogue->parent_id : 0)) == $val->id ? 'selected' : '' }}
                                                    value="{{ $val->id }}">{{ $val->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Ảnh ĐẠI DIỆN</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div style="text-align:center">
                            <div class="ibox-content">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <p>Click vào hình ảnh bên dưới để chọn</p>
                                            <img data-type="Images" class="upload-image image-style "
                                                src="{{ old('image') ?? (isset($postCatalogue) && $postCatalogue->image !== null ? $postCatalogue->image : asset('/backend/img/empty-image.png')) }}"
                                                alt="">
                                            <input type="hidden" name="image"
                                                value="{{ old('image') ?? (isset($postCatalogue) ? $postCatalogue->image : '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>CẤU HÌNH NÂNG CAO</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <select style="width:100%" name="publish" class="setupSelect2 ml">
                                            @foreach (__('filter.publish') as $key => $val)
                                                <option
                                                    {{ old('publish') ?? (isset($postCatalogue) ? $postCatalogue->publish : 0) == $key ? 'selected' : '' }}
                                                    value="{{ $key }}">{{ $val }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <select style="width:100%" name="follow" class="setupSelect2 ml">
                                            @foreach (__('filter.follow') as $key => $val)
                                                <option
                                                    {{ old('follow') ?? (isset($postCatalogue) ? $postCatalogue->follow : 0) == $key ? 'selected' : '' }}
                                                    value="{{ $key }}">{{ $val }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('backend.layouts.components.modalEditor')
                </div>
            </div>
            <div class="ibox-button">
                <button class="btn btn-primary mr10" type="submit">Lưu lại</button>
                <a href="{{ route('postCatalogue.index') }}" class="btn btn-white">Quay lại</a>
            </div>
        </form>
    </div>
@endsection
<!-- The Gallery as lightbox dialog, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery" style="display: none;">
    <div class="slides" style="width: 107424px;"></div>
    <h3 class="title">Image from Unsplash</h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>
