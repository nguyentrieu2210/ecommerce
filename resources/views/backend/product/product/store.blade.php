@extends('backend.layouts.layout')
@section('title', __('breadcrump.' . $config['module'] . '.' . $config['method'] . '.title'))
@section('content')
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="wrapper wrapper-content animated fadeInRight">
        @include('backend.layouts.components.errors')
        @php
            $route =
                $config['method'] == 'create'
                    ? route('product.store')
                    : route('product.update', ['id' => $product->id]);
        @endphp
        <form method="POST" action="{{ $route }}">
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
                        <div class="ibox-content" style="">
                            <div class="form-group"><label>Tên sản phẩm <span class="text-danger">(*)</span></label>
                                <input type="text" value="{{ old('name') ?? (isset($product) ? $product->name : '') }}"
                                    placeholder="Nhập tên sản phẩm..." name="name" class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group"><label>Mã sản phẩm <span class="text-danger">(*)</span></label>
                                        <input type="text"
                                            value="{{ old('code') ?? (isset($product) ? $product->code : '') }}"
                                            placeholder="Nhập mã sản phẩm..." name="code" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group"><label>Mã vạch/Barcode</label>
                                        <input type="text"
                                            value="{{ old('barcode') ?? (isset($product) ? $product->barcode : '') }}"
                                            placeholder="Nhập tay mã vạch hoặc dùng máy quét mã vạch..." name="barcode"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="">
                                        <label>Khối lượng</label>
                                        <div class="" style="display: flex">
                                            <input style="margin-right: 10px" type="text"
                                                value="{{ old('weight') ?? (isset($product) ? formatNumberFromSql($product->weight) : "0") }}"
                                                placeholder="" name="weight" class="form-control text-right inputMoney">
                                            <select name="mass" class="ml setupSelect2">
                                                <option value="0">g</option>
                                                <option value="1">kg</option>
                                                {{-- @foreach ($productCatalogues as $key => $val)
                                                        <option {{(old('mass') ?? (isset($product) ? $product->mass : 0)) == $val->id ? 'selected' : '' }}
                                                            value="{{ $val->id }}">{{ $val->name }}</option>
                                                    @endforeach --}}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group"><label>Đơn vị tính</label>
                                        <input type="text"
                                            value="{{ old('measure') ?? (isset($product) ? $product->measure : '') }}"
                                            placeholder="Nhập đơn vị tính..." name="measure" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Mô tả sản phẩm</label>
                                <textarea data-height="150" class="form-control setupCkeditor" name="description" id="" cols="30"
                                    rows="10">{{ old('description') ?? (isset($product) && $product->description !== null ? $product->description : '') }}</textarea>
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
                                        cols="30" rows="10">{{ old('content') ?? (isset($product) && $product->content !== null ? $product->content : '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>GIÁ SẢN PHẨM</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group"><label>Giá bán lẻ <span class="underline">(đ)</span></label>
                                                <input type="text"
                                                    value="{{ old('price') ?? (isset($product) ? formatNumberFromSql($product->price) : "0") }}"
                                                    placeholder="" name="price"
                                                    class="inputMoney text-right form-control">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group"><label>Giá nhập <span class="underline">(đ)</span></label>
                                                <input type="text"
                                                    value="{{ old('cost') ?? (isset($product) ? formatNumberFromSql($product->cost) : "0") }}"
                                                    placeholder="" name="cost"
                                                    class="inputMoney text-right form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- ALBUM ẢNH CỦA SẢN PHẨM --}}
                    @include('backend.layouts.components.album', [
                        'model' => isset($product) ? $product : null,
                    ])
                    {{-- CHỌN LOẠI SẢN PHẨM --}}
                    <div class="ibox float-e-margins {{$config['method'] == 'edit' ? 'hidden' : ''}}">
                        <div class="ibox-title">
                            <h5>LỰA CHỌN KIỂU SẢN PHẨM</h5>
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
                                        <select name="type_product" style="width:100%" class="ml setupSelect2">
                                            <option {{(old('type_product') ?? (isset($product) ? count($product->product_variants) : 0)) == 0 ? 'selected' : ''}} value="0">Sản phẩm có 1 phiên bản</option>
                                            <option {{(old('type_product') ?? ((isset($product) && count($product->product_variants) > 0) ? 1 : 0)) == 1 ? 'selected' : ''}} value="1">Sản phẩm có nhiều phiên bản</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="typeProduct">
                        <div class="singleVariant {{(old('type_product') == '0' || (isset($product) && count($product->product_variants) == 0)) ? '' : 'hidden'}}">
                            {{-- KHỞI TẠO GIÁ TRỊ KHO HÀNG --}}
                            @include('backend.product.product.components.init_warehouse')
                        </div>
                        <div class="multipleVariant {{(old('type_product') == '1' || (isset($product) && count($product->product_variants) > 0)) ? '' : 'hidden'}}">
                            {{-- sẢN PHẨM CÓ NHIỀU PHIÊN BẢN --}}
                            @include('backend.product.product.components.product_variant')
                        </div>
                        {{-- @include('backend.product.product.components.product_variant') --}}
                    </div>
                    @include('backend.layouts.components.seo', [
                        'model' => isset($product) ? $product : null,
                    ])
                </div>
                <div class="col-lg-4">
                    {{-- CẤU HÌNH CHUNG --}}
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
                                        <label for="">Chọn nhóm sản phẩm (chính) <span
                                                class="text-danger">(*)</span></label>
                                        <select style="width:100%" name="product_catalogue_id" class="ml setupSelect2">
                                            <option value="0">[Chọn nhóm sản phẩm]</option>
                                            @foreach ($productCatalogues as $key => $val)
                                                <option
                                                    {{ (old('product_catalogue_id') ?? (isset($product) ? $product->product_catalogue_id : 0)) == $val->id ? 'selected' : '' }}
                                                    value="{{ $val->id }}">{{ $val->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @php
                                        //loại bỏ nhóm sản phẩm chính
                                        if (isset($product)) {
                                            $productCatalogueIds = $product->product_catalogues->pluck('id')->toArray();
                                            unset(
                                                $productCatalogueIds[
                                                    array_search($product->product_catalogue_id, $productCatalogueIds)
                                                ],
                                            );
                                        }
                                    @endphp
                                    <div class="form-group">
                                        <label for="">Chọn nhóm sản phẩm (phụ)</label>
                                        <select style="width:100%;" name="product_catalogue_id_extra[]"
                                            multiple="multiple" class="ml setupSelect2">
                                            @foreach ($productCatalogues as $key => $val)
                                                <option
                                                    {{ in_array($val->id, old('product_catalogue_id_extra') ?? (isset($product) ? $productCatalogueIds : [])) ? 'selected' : '' }}
                                                    value="{{ $val->id }}">{{ $val->name }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- AVATAR --}}
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>ẢNH ĐẠI DIỆN</h5>
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
                                        <p>Click vào hình ảnh bên dưới để chọn</p>
                                        <img data-type="Images" class="upload-image image-style "
                                            src="{{ old('image') ?? (isset($product) && $product->image !== null ? $product->image : asset('/backend/img/empty-image.png')) }}"
                                            alt="">
                                        <input type="hidden" name="image"
                                            value="{{ old('image') ?? (isset($product) ? $product->image : '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- THÔNG TIN BỔ SUNG --}}
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>THÔNG TIN BỔ SUNG</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <select style="width:100%" name="publish" class="setupSelect2 ml">
                                            @foreach (__('filter.publish') as $key => $val)
                                                <option
                                                    {{ (old('publish') ?? (isset($product) ? $product->publish : 0)) == $key ? 'selected' : '' }}
                                                    value="{{ $key }}">{{ $val }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <select style="width:100%" name="follow" class="setupSelect2 ml">
                                            @foreach (__('filter.follow') as $key => $val)
                                                <option
                                                    {{ (old('follow') ?? (isset($product) ? $product->follow : 0)) == $key ? 'selected' : '' }}
                                                    value="{{ $key }}">{{ $val }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-space-between box-allow-sell">
                                <div class="allow-to-sell">
                                    <label class="auto-check" for="allow-to-sell">Trạng thái</label>
                                    <span style="display:block">Cho phép bán</span>
                                </div>
                                <input id="allow-to-sell" {{(old('allow_to_sell') ?? (isset($product) && $product->allow_to_sell == 1 ? "on" : "")) == "on" ? 'checked' : ''}} type="checkbox" name="allow_to_sell">
                            </div>
                            <div class="">
                                <div class="flex-space-between" style="padding: 15px 0">
                                    <div class="status_tax">
                                        <label class="auto-check" for="status-tax">Tình trạng thuế</label>
                                        <span style="display:block">Áp dụng thuế</span>
                                    </div>
                                    <input id="status-tax" {{(old('status_tax') ?? (isset($product) && $product->tax_status == 1 ? "on" : "")) == "on" ? 'checked' : ''}} type="checkbox" name="status_tax">
                                </div>
                                <div class="">
                                    <div class="form-group {{(old('status_tax') ?? (isset($product) && $product->tax_status == 1 ? "on" : "")) == "on" ? '' : 'hidden'}} selectStatusTax">
                                        <label for="">Giá của sản phẩm</label>
                                        <select style="width:100%;" name="tax_status" class="ml setupSelect2">
                                            <option {{(old('tax_status') == "0" || (isset($product) && $product->tax_status == 0)) ? 'selected' : ''}} value="0">Giá sản phẩm đã bao gồm thuế</option>
                                            <option {{(old('tax_status') == "1"  || (isset($product) && $product->tax_status == 1)) ? 'selected' : ''}} value="1">Giá sản phẩm chưa bao gồm thuế</option>
                                        </select>
                                    </div>
                                    <div id="applyTax" class="{{(old('tax_status') == "1" || (isset($product) && $product->tax_status == 1)) ? '' : 'hidden'}}">
                                        <div class="form-group">
                                            <label for="">Giá trị thuế đầu vào</label>
                                            <div class="flex">
                                                <select style="width:100%" name="input_tax" class="ml setupSelect2 inputTax">
                                                    @foreach($taxes as $tax)
                                                        <option {{(old('input_tax') ?? (isset($product) ? $product->input_tax : "")) == $tax->code ? 'selected' : ''}} value="{{$tax->code}}">{{$tax->name}}</option>
                                                    @endforeach
                                                </select>
                                                <span data-toggle="modal" href="#modalTax" class="btn btn-success addTax"><i
                                                        class="fa fa-plus lh25"></i></span>
                                                <div id="modalTax" class="modal fade" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="titleModal">
                                                                        <h5>THÊM THÔNG TIN THUẾ</h5>
                                                                    </div>
                                                                    <div class="contentModal">
                                                                        <div class="form-group"><label>Tên thuế <span class="text-danger">(*)</span></label>
                                                                            <input type="text"
                                                                                value=""
                                                                                placeholder=""
                                                                                class="nameTax form-control br20 checkEmpty">
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-lg-6">
                                                                                <div class="form-group"><label>Mã thuế <span class="text-danger">(*)</span></label>
                                                                                    <input type="text"
                                                                                        value=""
                                                                                        placeholder="" 
                                                                                        class="codeTax form-control br20 checkEmpty">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-6">
                                                                                <div class="form-group"><label>Gía trị thuế suất <span class="text-danger">(*)</span></label>
                                                                                    <input type="text"
                                                                                        value="0"
                                                                                        placeholder=""
                                                                                        class="valueTax inputMoney form-control br20 checkEmpty">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="footerModal">
                                                                        <span data-toggle="modal" href="#modalTax" class="btn btn-success submitTax">Xác nhận</span>
                                                                        <span data-toggle="modal" href="#modalTax" class="btn btn-danger cancelTax">Thoát</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
    
                                        </div>
                                        <div class="form-group">
                                            <label for="">Giá trị thuế đầu ra</label>
                                            <div class="flex">
                                                <select style="width:100%" name="output_tax" class="ml setupSelect2 inputTax">
                                                    @foreach($taxes as $tax)
                                                        <option {{(old('output_tax') ?? (isset($product) ? $product->output_tax : '')) == $tax->code ? 'selected' : ''}} value="{{$tax->code}}">{{$tax->name}}</option>
                                                    @endforeach
                                                </select>
                                                <span data-toggle="modal" href="#modalTax" class="btn btn-success addTax"><i class="fa fa-plus lh25"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- THÔNG SỐ KĨ THUẬT --}}
                    @include('backend.product.product.components.configuration')
                    {{-- CÂU HỎI THƯỜNG GẶP --}}
                    @include('backend.product.product.components.answer_question')
                    {{-- TIN TỨC VỀ SẢN PHẨM --}}
                    @php 
                        $models = isset($product) ? $product->posts : [];
                    @endphp
                    @include('backend.product.product.components.search_item', ['models' => $models])
                    {{-- THÊM, CHỈNH SỬA ẢNH CHO EDITOR --}}
                    @include('backend.layouts.components.modalEditor')
                </div>
            </div>
            <div class="ibox-button">
                <button class="btn btn-primary mr10" type="submit">Lưu lại</button>
                <a href="{{ route('product.index') }}" class="btn btn-white">Quay lại</a>
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
