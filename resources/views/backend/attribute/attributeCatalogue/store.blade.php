@extends('backend.layouts.layout')
@section('title', __('breadcrump.' . $config['module'] . '.' . $config['method'] . '.title'))
@section('content')
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="wrapper wrapper-content animated fadeInRight">
        @include('backend.layouts.components.errors')
        @php
            $route =
                $config['method'] == 'create'
                    ? route('attributeCatalogue.store')
                    : route('attributeCatalogue.update', ['id' => $attributeCatalogue->id]);
        @endphp
        <form method="post" action="{{ $route }}">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Thông tin chung</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content" style="">
                            <div class="row">
                                <div class="col-sm-8 b-r">
                                    <div class="form-group"><label>Tên nhóm thuộc tính <span
                                                class="text-danger">(*)</span></label> <input type="text"
                                            value="{{ old('name') ?? (isset($attributeCatalogue) ? $attributeCatalogue->name : '') }}"
                                            placeholder="Nhập tên thuộc tính..." name="name" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Mô tả</label>
                                        <textarea style="height: 105px" class="form-control" name="description" id="" cols="30" rows="10">{{ old('description') ?? (isset($attributeCatalogue) && $attributeCatalogue->description !== null ? $attributeCatalogue->description : '') }}</textarea>
                                    </div>
                                    <div class="form-group"><label>Canonical <a data-toggle="modal" href="#modal-form"><i class="fa fa-info-circle"></i></a> <strong
                                                class="text-link canonicalCustomize"></strong></label>
                                        <input type="text" placeholder="Nhập canonical..."
                                            value="{{ old('canonical') ?? (isset($attributeCatalogue) ? $attributeCatalogue->canonical : '') }}"
                                            name="canonical_original" class="form-control canonicalOriginal">
                                        <input type="hidden" class="canonical" name="canonical"
                                            value="{{ old('canonical') ?? (isset($attributeCatalogue) ? $attributeCatalogue->canonical : '') }}">
                                    </div>
                                        <div id="modal-form" class="modal fade" aria-hidden="true" style="display: none; color: #333">
                                            <div class="modal-dialog">
                                                <div class="ibox-title">
                                                    <h5>THÔNG TIN BỔ SUNG</h5>
                                                </div>
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <p>
                                                            Được sử dụng trên URL để giúp URL trở nên thân thiện với người dùng - điều này sẽ giúp cho hệ thống của bạn SEO tốt hơn (ví dụ: Màu
                                                            sắc => mau-sac)
                                                        </p>
                                                        <span class="text-danger">Lưu ý: Để tránh xung đột URL trong hệ thống, Canonical nhập vào phải là duy nhất</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="col-sm-4">
                                    <h4>Ảnh đại diện</h4>
                                    <p>Click vào hình ảnh bên dưới để chọn</p>
                                    <img data-type="Images" class="upload-image image-style "
                                        src="{{ old('image') ?? (isset($attributeCatalogue) && $attributeCatalogue->image !== null ? $attributeCatalogue->image : asset('/backend/img/empty-image.png')) }}"
                                        alt="">
                                    <input type="hidden" name="image"
                                        value="{{ old('image') ?? (isset($attributeCatalogue) ? $attributeCatalogue->image : '') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Cấu hình chung</h5>
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
                                            @foreach ($attributeCatalogues as $key => $val)
                                                <option {{ (old('parent_id') ?? (isset($attributeCatalogue) ? $attributeCatalogue->parent_id : 0)) == $val->id ? 'selected' : '' }}
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
                            <h5>Cấu hình nâng cao</h5>
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
                                                <option {{ old('publish') ?? (isset($attributeCatalogue) ? $attributeCatalogue->publish : 0) == $key ? 'selected' : '' }}
                                                    value="{{ $key }}">{{ $val }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <select style="width:100%" name="follow" class="setupSelect2 ml">
                                            @foreach (__('filter.follow') as $key => $val)
                                                <option {{ old('follow') ?? (isset($attributeCatalogue) ? $attributeCatalogue->follow : 0) == $key ? 'selected' : '' }}
                                                    value="{{ $key }}">{{ $val }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox-button">
                <button class="btn btn-primary mr10" type="submit">Lưu lại</button>
                <a href="{{ route('attributeCatalogue.index') }}" class="btn btn-white">Quay lại</a>
            </div>
        </form>
    </div>
@endsection

