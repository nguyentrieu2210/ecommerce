@extends('backend.layouts.layout')
@section('title', __("breadcrump.".$config['module'].".".$config['method'].".title"))
@section('content')
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="wrapper wrapper-content animated fadeInRight">
        @include('backend.layouts.components.errors')
        @php
            $route = $config['method'] == 'create' ? route('slide.store') : route('slide.update', $slide->id);   
        @endphp
        <form method="post" action="{{$route}}">
            @csrf
            <div class="row">
                <div class="col-lg-9">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <div class="flex-space-between">
                                <h5>DANH SÁCH SLIDE <i class="fa fa-info-circle icon-info-detail" data-toggle="popover" data-placement="auto top" data-content="Click vào ảnh bên dưới để chọn ảnh cho từng Slide. Bạn cũng có thể thay đổi vị trí của Slide tùy ý bằng cách giữ chuột rồi kéo thả Slide tới vị trí mong muốn."></i></h5>
                                <span class="btn btn-primary addSliceItem"><i class="fa fa-plus"></i> Thêm ảnh</span>
                            </div>
                        </div>
                        @php
                            $listSlide = old('item') ?? (isset($slide) ? $slide->item : null);
                        @endphp
                        <div class="ibox-content" style="">
                            <div class="slideList row sortable">
                                @if($listSlide !== null)
                                    @foreach($listSlide['image'] as $key => $val)
                                    <div class="slide-item col-sm-12">
                                        <div class="col-sm-3 slide-item-l">
                                            <img data-type="Images" class="upload-image image-style "
                                                src="{{$val ?? asset('/backend/img/empty-image.png') }}" alt="">
                                            <input type="hidden" name="item[image][]" value="{{$listSlide['image'][$key] ?? ''}}">
                                        </div>
                                        <div class="col-sm-9 slide-item-r">
                                            <span class="btn btn-danger item-slide-trash"><i class="fa fa-trash"></i>  Xóa ảnh</span>
                                            <div class="tabs-container">
                                                <ul class="nav nav-tabs">
                                                    <li class="active"><a data-toggle="tab" href=".general-info-{{$key}}">THÔNG TIN CHUNG</a></li>
                                                    <li class=""><a data-toggle="tab" href=".seo-info-{{$key}}">SEO</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane active general-info-{{$key}}">
                                                        <div class="panel-body">
                                                            <div class="form-group">
                                                                <label>Mô tả</label> 
                                                                <textarea type="text" name="item[alt][]" class="form-control">{{$listSlide['alt'][$key] ?? ''}}</textarea>
                                                            </div>
                                                            <div class="flex-space-between">
                                                                <div class="form-group"> 
                                                                    <input type="text" name="item[canonical][]" value="{{$listSlide['canonical'][$key] ?? ''}}" placeholder="Url" class="form-control br5">
                                                                </div>
                                                                <div class="form-group flex"> 
                                                                    <label class="label-checkbox-slide" for="">Mở trong tab mới</label>
                                                                    <input type="hidden" name="item[window][{{$key}}]" value="off">
                                                                    <input type="checkbox" name="item[window][{{$key}}]" {{$listSlide['window'][$key] == 'on' ? 'checked' : ''}} placeholder="Url" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane seo-info-{{$key}}">
                                                        <div class="panel-body">
                                                            <div class="form-group">
                                                                <label>Tiêu đề ảnh</label> 
                                                                <input type="text" name="item[name][]" value="{{$listSlide['name'][$key] ?? ''}}" placeholder="Nhập tiêu đề ảnh..." class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Mô tả ảnh</label> 
                                                                <input type="text" name="item[description][]" value="{{$listSlide['description'][$key] ?? ''}}" placeholder="Nhập mô tả ảnh" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="empty-image emptyImage {{$listSlide !== null ? 'hidden' : ''}}">
                                <img data-type="Images" class="image-style "
                                    src="{{ asset('/userfiles/image/general/empty-image.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>CÀI ĐẶT CƠ BẢN <i class="fa fa-info-circle icon-info-detail" data-toggle="popover" data-placement="auto top" data-content="Ở đây, nếu bạn không đặt chiều rộng và chiều cao cho Slide. Hệ thống sẽ lấy chiều rộng và chiều cao phù hợp dựa trên khung hiển thị."></i></h5>
                        </div>
                        @php
                            $setting = old('setting') ?? (isset($slide) ? $slide->setting : []);
                        @endphp
                        <div class="ibox-content config-basic" style="">
                            <div class="form-group">
                                <label>Tên Slide <span class="text-danger">(*)</span></label> 
                                <input type="text" name="name" value="{{old('name') ?? (isset($slide) ? $slide->name : '')}}" placeholder="Nhập tên Slide..." class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Từ khóa Slide <span class="text-danger">(*)</span></label> 
                                <input type="text" name="keyword" value="{{old('keyword') ?? (isset($slide) ? $slide->keyword : '')}}" placeholder="Nhập từ khóa Slide..." class="form-control">
                            </div>
                            <div class="form-group flex-space-between">
                                <label class="p-r">Chiều rộng (px) </i></label> 
                                <input type="text" name="setting[width]" value="{{count($setting) ? formatNumberFromSql($setting['width']) : ''}}" placeholder="" class="form-control text-right w50 br5 inputNumber">
                            </div>
                            <div class="form-group flex-space-between">
                                <label>Chiều cao (px)</label>
                                <input type="text" name="setting[height]" value="{{count($setting) ? formatNumberFromSql($setting['height']) : ''}}" placeholder="" class="form-control text-right w50 br5 inputNumber">
                            </div>
                            <div class="form-group">
                                <label>Hiệu ứng</label> 
                                <select style="width: 100%;" name="setting[animation]" class="ml setupSelect2 w50">
                                    @foreach(__('filter.animations') as $key => $item)
                                        <option {{$key == (count($setting) ? $setting['animation'] : '') ? 'selected' : ''}} value="{{$key}}">{{$item}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group flex-space-between mb10">
                                <label for="arrow">Mũi tên</label> 
                                <input type="hidden" name="setting[arrow]" value="off">
                                <input type="checkbox" id="arrow" name="setting[arrow]" {{ ((count($setting) ? $setting['arrow'] : 'on') == 'on') ? 'checked' : '' }} class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>CÀI ĐẶT NÂNG CAO
                                <i class="fa fa-info-circle icon-info-detail" data-toggle="popover" data-placement="auto top" data-content="Ở đây, nếu bạn không đặt chuyển ảnh và tốc độ hiệu ứng cho Slide. Hệ thống sẽ đặt mặc định chuyển ảnh là 3000ms và tốc độ hiệu ứng là 500ms."></i>
                            </h5>
                        </div>
                        <div class="ibox-content config-advanced" style="">
                            <div class="form-group flex-space-between mb0">
                                <label for="autoplay">Tự động chạy</label> 
                                <input type="hidden" name="setting[autoplay]" value="off">
                                <input id="autoplay" type="checkbox" name="setting[autoplay]" {{((count($setting) ? $setting['autoplay'] : 'on') == 'on') ? 'checked' : ''}} class="form-control">
                            </div>
                            <div class="form-group flex-space-between mb10">
                                <label for="pauseOnMouseEnter">Dừng khi di chuột</label> 
                                <input type="hidden" name="setting[pauseOnMouseEnter]" value="off">
                                <input type="checkbox" id="pauseOnMouseEnter" name="setting[pauseOnMouseEnter]" {{((count($setting) ? $setting['pauseOnMouseEnter'] : 'on') == 'on') ? 'checked' : ''}} class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Chuyển ảnh (ms)</label> 
                                <input type="text" name="setting[delay]" value="{{count($setting) ? formatNumberFromSql($setting['delay']) : ''}}" placeholder="vd:3000" class="form-control inputNumber">
                            </div>
                            <div class="form-group">
                                <label>Tốc độ hiệu ứng (ms)</label>
                                <input type="text" name="setting[speed]" value="{{count($setting) ? formatNumberFromSql($setting['speed']) : ''}}" placeholder="vd:500" class="form-control inputNumber">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox-button">
                <button class="btn btn-primary mr10" type="submit">Lưu lại</button>
                <a href="{{route('slide.index')}}" class="btn btn-white" >Quay lại</a>
            </div>
        </form>
    </div>
@endsection
