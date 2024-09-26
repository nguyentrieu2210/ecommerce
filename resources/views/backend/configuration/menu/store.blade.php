@extends('backend.layouts.layout')
@section('title', __('breadcrump.' . $config['module'] . '.' . $config['method'] . '.title'))
@section('content')
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="wrapper wrapper-content animated fadeInRight">
        @include('backend.layouts.components.errors')
        @php
            $route = $config['method'] == 'create' ? route('menu.store') : route('menu.update', $menu->id);
        @endphp
        <form id="form" class="formSubmitMenu" method="post" action="{{ $route }}">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>THÔNG TIN CHUNG</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <input type="hidden" name="links" value="{{old('links')}}">
                        <div class="ibox-content" style="">
                            <div class="form-group"><label>Tên <span class="text-danger">(*)</span></label>
                                <input type="text" placeholder="vd: Menu chính, Footer,..."
                                    value="{{ old('name') ?? (isset($menu) ? $menu->name : '') }}" name="name"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>LIÊN KẾT (<span style="display:inline-block" class="fw400 fs14 mb10">Các liên kết có thể di chuyển tùy ý bằng cách nhấn, giữ chuột và di chuyển đến nơi mong muốn</span>)</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>

                        </div>

                        @php
                            $links = json_decode(old('links')) ?? ((isset($menu) && count($menu->links)) ? setupPayloadLinks($menu->links) : []);
                            $htmlLinks = setupLinkForMenu($links);
                        @endphp
                        <div class="ibox-content" style="">
                            <div class="empty-link {{count($links) ? 'hidden' : ''}}">
                                <span>Menu này chưa có liên kết nào.</span>
                            </div>
                            <div class="dd" id="nestable2">
                                <ol class="dd-list parentList">
                                    {!! $htmlLinks !!}
                                </ol>
                            </div>
                        </div>
                        <div data-toggle="modal" href="#addLink" class="add-link">
                            <i class="fa fa-plus-circle"></i> <span>Thêm liên kết</span>
                        </div>
                        <div id="addLink" class="modal fade" aria-hidden="true">
                            <input type="hidden" class="inputCanonical" value="" >
                            <input type="hidden" class="targetId" value="">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header flex modal-header-customize">
                                        <h5 class="modal-title fs20">Thêm liên kết</h5>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert-error" style="margin:0; padding: 0; width:100%">
                                            
                                        </div>
                                        <div class="form-group"><label class="fw550">Tên</label>
                                            <input type="text" placeholder="Nhập tên liên kết" value=""
                                                class="nameLinkModal form-control">
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                                <h4>Ảnh</h4>
                                                <img data-type="Images"
                                                    class="upload-image image-style br5 mb15 modalItemImageLink"
                                                    src="{{ asset('/backend/img/empty-image.png') }}" alt="">
                                                <input type="hidden" class="imageLinkModal" value="">
                                            </div>
                                        </div>
                                        <label for="" class="fw550">Liên kết</label>
                                        <div class="choose-link flex">
                                            <div class="form-group w32 mr10">
                                                <select style="width: 100%;" class="changeLinkModal ml setupSelect2 w50">
                                                    @foreach (__('filter.linkMenu') as $item)
                                                        <option data-canonical="{{ $item['canonical'] }}"
                                                            data-model="{{ $item['model'] }}"
                                                            data-nameTarget="{{ $item['nameTarget'] }}"
                                                            data-htmlTarget="{{ $item['htmlTarget'] }}" value="{{ $item['keyword'] }}">
                                                            {{ $item['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="html-target">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                        <button type="button" class="addLinkComplete btn btn-primary">Hoàn thành</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>ALIAS</h5>
                        </div>
                        <div class="ibox-content" style="">
                            <span style="display:inline-block" class="fw400 fs14 mb10">Alias này dùng để truy cập các
                                thuộc tính của Menu trong Theme. <a target="_blank"
                                    href="https://support.sapo.vn/dinh-danh-alias">Tìm hiểu thêm</a></span>
                            <div class="form-group">
                                <input type="text" placeholder=""
                                    value="{{ old('keyword') ?? (isset($menu) ? $menu->keyword : '') }}" name="keyword"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox-button">
                <button class="btn btn-primary mr10 submitMenu" type="submit">Lưu lại</button>
                <a href="{{ route('menu.index') }}" class="btn btn-white">Quay lại</a>
            </div>
        </form>
    </div>

@endsection

