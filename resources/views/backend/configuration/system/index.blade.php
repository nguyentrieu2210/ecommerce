@extends('backend.layouts.layout')
@section('title', __('breadcrump.' . $config['module'] . '.' . $config['method'] . '.title'))
@section('content')

    @include('backend.layouts.components.breadcrump', ['config' => $config])
    <div class="wrapper wrapper-content animated fadeInRight">
        <form method="post" action="{{route('system.store')}}">
            @csrf
            <div class="row">
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title flex-space-between">
                            <div>
                                <h5>Thông tin chung </h5>
                                <a class="more-info" data-toggle="modal" href="#common-infor"><i class="fa fa-info-circle"></i></a>
                            </div>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div id="common-infor" class="modal fade" aria-hidden="true" style="display: none; color: #333">
                            <div class="modal-dialog">
                                <div class="ibox-title">
                                    <h5>THÔNG TIN BỔ SUNG</h5>
                                </div>
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <p>
                                            - Cài đặt đầy đủ thông tin chung của Website. Tên thương hiệu Website, Logo,
                                            Favicon,vv..
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ibox-content" style="">
                            <div class="form-group"><label>Tên công ty</span></label> <input type="text"
                                    value="{{ old('homepage_company') ?? (isset($system) ? $system['homepage_company'] : '') }}"
                                    placeholder="Nhập tên công ty..." name="homepage_company" class="form-control">
                            </div>
                            <div class="form-group"><label>Tên thương hiệu</span></label> <input type="text"
                                    value="{{ old('homepage_brand') ?? (isset($system) ? $system['homepage_brand'] : '') }}"
                                    placeholder="Nhập tên thương hiệu..." name="homepage_brand" class="form-control">
                            </div>
                            <div class="form-group"><label>Slogan</span></label> <input type="text"
                                    value="{{ old('homepage_slogan') ?? (isset($system) ? $system['homepage_slogan'] : '') }}"
                                    placeholder="Nhập tên slogan..." name="homepage_slogan" class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                    <h4>Logo</h4>
                                    <img data-type="Images" class="upload-image image-style "
                                        src="{{ old('homepage_logo') ?? (isset($system) && $system['homepage_logo'] !== null ? $system['homepage_logo'] : asset('/backend/img/empty-image.png')) }}"
                                        alt="">
                                    <input type="hidden" name="homepage_logo"
                                        value="{{ old('homepage_logo') ?? (isset($system) ? $system['homepage_logo'] : '') }}">
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                    <h4>Favicon</h4>
                                    <img data-type="Images" class="upload-image image-style "
                                        src="{{ old('homepage_favicon') ?? (isset($system) && $system['homepage_favicon'] !== null ? $system['homepage_favicon'] : asset('/backend/img/empty-image.png')) }}"
                                        alt="">
                                    <input type="hidden" name="homepage_favicon"
                                        value="{{ old('homepage_favicon') ?? (isset($system) ? $system['homepage_favicon'] : '') }}">
                                </div>
                            </div>
                            <div class="form-group mt20"><label>Copyright</span></label> <input type="text"
                                    value="{{ old('homepage_copyright') ?? (isset($system) ? $system['homepage_copyright'] : '') }}"
                                    placeholder="" name="homepage_copyright" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Tình trạng Website</label>
                                <select style="width: 100%" name="homepage_website" class="ml setupSelect2">
                                    <option {{old('homepage_website') ?? ((isset($system) && (int) $system['homepage_website'] == 1) ? 'selected' : '')}} value="1">Mở</option>
                                    <option {{old('homepage_website') ?? ((isset($system) && (int) $system['homepage_website'] == 0) ? 'selected' : '')}} value="0">Đóng</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Giới thiệu ngắn</label>
                                <textarea data-height="100" class="form-control setupCkeditor ck-editor" name="homepage_short_intro" id="homepage_short_intro" cols="30"
                                    rows="10">{{ old('homepage_short_intro') ?? (isset($system) && $system['homepage_short_intro'] !== null ? $system['homepage_short_intro'] : '') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="ibox float-e-margins">
                        <div class="ibox-title flex-space-between">
                            <div>
                                <h5>Cấu hình Mạng xã hội dành cho trang chủ</h5>
                                <a class="more-info" data-toggle="modal" href="#social-infor"><i class="fa fa-info-circle"></i></a>
                            </div>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div id="social-infor" class="modal fade" aria-hidden="true" style="display: none; color: #333">
                            <div class="modal-dialog">
                                <div class="ibox-title">
                                    <h5>THÔNG TIN BỔ SUNG</h5>
                                </div>
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <p>- Cài đặt đầy đủ thông tin đầy đủ về Mạng xã hội của trang chủ Website. Bao gồm tiêu đề SEO,
                                            từ khóa SEO, Mô tả SEO, Meta images</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <div class="form-group"><label>Facebook</span></label> <input type="text"
                                    value="{{ old('social_facebook') ?? (isset($system) ? $system['social_facebook'] : '') }}"
                                    placeholder="Liên kết..." name="social_facebook" class="form-control">
                            </div>
                            <div class="form-group"><label>Youtube</span></label> <input type="text"
                                    value="{{ old('social_youtube') ?? (isset($system) ? $system['social_youtube'] : '') }}"
                                    placeholder="Liên kết..." name="social_youtube" class="form-control">
                            </div>
                            <div class="form-group"><label>Twitter</span></label> <input type="text"
                                    value="{{ old('social_twitter') ?? (isset($system) ? $system['social_twitter'] : '') }}"
                                    placeholder="Liên kết..." name="social_twitter" class="form-control">
                            </div>
                            <div class="form-group"><label>Tiktok</span></label> <input type="text"
                                    value="{{ old('social_tiktok') ?? (isset($system) ? $system['social_tiktok'] : '') }}"
                                    placeholder="Liên kết..." name="social_tiktok" class="form-control">
                            </div>
                            <div class="form-group"><label>Instagram</span></label> <input type="text"
                                    value="{{ old('social_instagram') ?? (isset($system) ? $system['social_instagram'] : '') }}"
                                    placeholder="Liên kết..." name="social_instagram" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title flex-space-between">
                            <div>
                                <h5>Thông tin liên hệ</h5>
                                <a class="more-info" data-toggle="modal" href="#contact-infor"><i class="fa fa-info-circle"></i></a>
                            </div>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div id="contact-infor" class="modal fade" aria-hidden="true" style="display: none; color: #333">
                            <div class="modal-dialog">
                                <div class="ibox-title">
                                    <h5>THÔNG TIN BỔ SUNG</h5>
                                </div>
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <p>- Cài đặt thông tin liên hệ của Website ví dụ: Địa chỉ công ty, văn phòng giao dịch, Hotline,
                                            Bản đồ,vv...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <div class="form-group"><label>Địa chỉ công ty</span></label> <input type="text"
                                    value="{{ old('contact_office') ?? (isset($system) ? $system['contact_office'] : '') }}"
                                    placeholder="Nhập địa chỉ công ty..." name="contact_office" class="form-control">
                            </div>
                            <div class="form-group"><label>Văn phòng giao dịch</span></label> <input type="text"
                                    value="{{ old('contact_address') ?? (isset($system) ? $system['contact_address'] : '') }}"
                                    placeholder="Nhập văn phòng giao dịch..." name="contact_address" class="form-control">
                            </div>
                            <div class="form-group"><label>Hotline</span></label> <input type="text"
                                    value="{{ old('contact_hotline') ?? (isset($system) ? $system['contact_hotline'] : '') }}"
                                    placeholder="Nhập hotline..." name="contact_hotline" class="form-control">
                            </div>
                            <div class="form-group"><label>Hotline kĩ thuật</span></label> <input type="text"
                                    value="{{ old('contact_technical_phone') ?? (isset($system) ? $system['contact_technical_phone'] : '') }}"
                                    placeholder="Nhập hotline kĩ thuật..." name="contact_technical_phone"
                                    class="form-control">
                            </div>
                            <div class="form-group"><label>Hotline kinh doanh</span></label> <input type="text"
                                    value="{{ old('contact_sell_phone') ?? (isset($system) ? $system['contact_sell_phone'] : '') }}"
                                    placeholder="Nhập hotline kinh doanh..." name="contact_sell_phone"
                                    class="form-control">
                            </div>
                            <div class="form-group"><label>Số cố định</span></label> <input type="text"
                                    value="{{ old('contact_phone') ?? (isset($system) ? $system['contact_phone'] : '') }}"
                                    placeholder="Nhập số cố định..." name="contact_phone" class="form-control">
                            </div>
                            <div class="form-group"><label>Fax</span></label> <input type="text"
                                    value="{{ old('contact_fax') ?? (isset($system) ? $system['contact_fax'] : '') }}"
                                    placeholder="Nhập fax..." name="contact_fax" class="form-control">
                            </div>
                            <div class="form-group"><label>Email</span></label> <input type="text"
                                    value="{{ old('contact_email') ?? (isset($system) ? $system['contact_email'] : '') }}"
                                    placeholder="Nhập email..." name="contact_email" class="form-control">
                            </div>
                            <div class="form-group"><label>Mã số thuế</span></label> <input type="text"
                                    value="{{ old('contact_tax') ?? (isset($system) ? $system['contact_tax'] : '') }}"
                                    placeholder="Nhập mã số thuế..." name="contact_tax" class="form-control">
                            </div>
                            <div class="form-group"><label>Website</span></label> <input type="text"
                                    value="{{ old('contact_website') ?? (isset($system) ? $system['contact_website'] : '') }}"
                                    placeholder="" name="contact_website" class="form-control">
                            </div>
                            <div class="form-group"><label>Bản đồ</span></label> <textarea type="text"
                                placeholder="" name="contact_map" class="form-control">{{ old('contact_map') ?? (isset($system) ? $system['contact_map'] : '') }}</textarea>
                            </div>
                        </div>  
                    </div>
                    <div class="ibox float-e-margins">
                        <div class="ibox-title flex-space-between">
                            <div>
                                <h5>Cấu hình SEO dành cho trang chủ</h5>
                                <a class="more-info" data-toggle="modal" href="#seo-infor"><i class="fa fa-info-circle"></i></a>
                            </div>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div id="seo-infor" class="modal fade" aria-hidden="true" style="display: none; color: #333">
                            <div class="modal-dialog">
                                <div class="ibox-title">
                                    <h5>THÔNG TIN BỔ SUNG</h5>
                                </div>
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <p>- Cài đặt đầy đủ thông tin SEO của trang chủ Website. Bao gồm tiêu đề SEO, từ khóa SEO, Mô tả
                                            SEO, Meta images</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <div class="form-group"><label>Tiêu đề SEO</span></label> <input type="text"
                                    value="{{ old('seo_meta_title') ?? (isset($system) ? $system['seo_meta_title'] : '') }}"
                                    placeholder="Nhập tiêu đề SEO..." name="seo_meta_title" class="form-control">
                            </div>
                            <div class="form-group"><label>Từ khóa SEO</span></label> <input type="text"
                                    value="{{ old('seo_meta_keyword') ?? (isset($system) ? $system['seo_meta_keyword'] : '') }}"
                                    placeholder="Nhập từ khóa SEO..." name="seo_meta_keyword" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Mô tả SEO</label>
                                <textarea style="height: 105px" class="form-control" name="seo_meta_description" id="" cols="30"
                                    rows="10">{{ old('seo_meta_description') ?? (isset($system) && $system['seo_meta_description'] !== null ? $system['seo_meta_description'] : '') }}</textarea>
                            </div>
                            {{-- <div class="form-group"><label>Ảnh SEO</span></label> <input type="text"
                                    value="{{ old('seo_meta_images') ?? (isset($system) ? $system['seo_meta_images'] : '') }}"
                                    placeholder="Nhập từ khóa SEO..." name="seo_meta_images" class="form-control">
                            </div> --}}
                            <div style="width: 32%">
                                <h4>Ảnh SEO</h4>
                                <img data-type="Images" class="upload-image image-style "
                                    src="{{ old('seo_meta_images') ?? (isset($system) && $system['seo_meta_images'] !== null ? $system['seo_meta_images'] : asset('/backend/img/empty-image.png')) }}"
                                    alt="">
                                <input type="hidden" name="seo_meta_images"
                                    value="{{ old('seo_meta_images') ?? (isset($system) ? $system['seo_meta_images'] : '') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="row">
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title flex-space-between">
                            <div>
                                <h5>Cấu hình SEO dành cho trang chủ</h5>
                                <a class="more-info" data-toggle="modal" href="#seo-infor"><i class="fa fa-info-circle"></i></a>
                            </div>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div id="seo-infor" class="modal fade" aria-hidden="true" style="display: none; color: #333">
                            <div class="modal-dialog">
                                <div class="ibox-title">
                                    <h5>THÔNG TIN BỔ SUNG</h5>
                                </div>
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <p>- Cài đặt đầy đủ thông tin SEO của trang chủ Website. Bao gồm tiêu đề SEO, từ khóa SEO, Mô tả
                                            SEO, Meta images</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <div class="form-group"><label>Tiêu đề SEO</span></label> <input type="text"
                                    value="{{ old('seo_meta_title') ?? (isset($system) ? $system['seo_meta_title'] : '') }}"
                                    placeholder="Nhập tiêu đề SEO..." name="seo_meta_title" class="form-control">
                            </div>
                            <div class="form-group"><label>Từ khóa SEO</span></label> <input type="text"
                                    value="{{ old('seo_meta_keyword') ?? (isset($system) ? $system['seo_meta_keyword'] : '') }}"
                                    placeholder="Nhập từ khóa SEO..." name="seo_meta_keyword" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Mô tả SEO</label>
                                <textarea style="height: 105px" class="form-control" name="seo_meta_description" id="" cols="30"
                                    rows="10">{{ old('seo_meta_description') ?? (isset($system) && $system['seo_meta_description'] !== null ? $system['seo_meta_description'] : '') }}</textarea>
                            </div>
                            <div class="form-group"><label>Ảnh SEO</span></label> <input type="text"
                                    value="{{ old('seo_meta_images') ?? (isset($system) ? $system['seo_meta_images'] : '') }}"
                                    placeholder="Nhập từ khóa SEO..." name="seo_meta_images" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title flex-space-between">
                            <div>
                                <h5>Cấu hình Mạng xã hội dành cho trang chủ</h5>
                                <a class="more-info" data-toggle="modal" href="#social-infor"><i class="fa fa-info-circle"></i></a>
                            </div>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div id="social-infor" class="modal fade" aria-hidden="true" style="display: none; color: #333">
                            <div class="modal-dialog">
                                <div class="ibox-title">
                                    <h5>THÔNG TIN BỔ SUNG</h5>
                                </div>
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <p>- Cài đặt đầy đủ thông tin đầy đủ về Mạng xã hội của trang chủ Website. Bao gồm tiêu đề SEO,
                                            từ khóa SEO, Mô tả SEO, Meta images</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <div class="form-group"><label>Facebook</span></label> <input type="text"
                                    value="{{ old('social_facebook') ?? (isset($system) ? $system['social_facebook'] : '') }}"
                                    placeholder="Liên kết..." name="social_facebook" class="form-control">
                            </div>
                            <div class="form-group"><label>Youtube</span></label> <input type="text"
                                    value="{{ old('social_youtube') ?? (isset($system) ? $system['social_youtube'] : '') }}"
                                    placeholder="Liên kết..." name="social_youtube" class="form-control">
                            </div>
                            <div class="form-group"><label>Twitter</span></label> <input type="text"
                                    value="{{ old('social_twitter') ?? (isset($system) ? $system['social_twitter'] : '') }}"
                                    placeholder="Liên kết..." name="social_twitter" class="form-control">
                            </div>
                            <div class="form-group"><label>Tiktok</span></label> <input type="text"
                                    value="{{ old('social_tiktok') ?? (isset($system) ? $system['social_tiktok'] : '') }}"
                                    placeholder="Liên kết..." name="social_tiktok" class="form-control">
                            </div>
                            <div class="form-group"><label>Instagram</span></label> <input type="text"
                                    value="{{ old('social_instagram') ?? (isset($system) ? $system['social_instagram'] : '') }}"
                                    placeholder="Liên kết..." name="social_instagram" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="ibox-button">
                <button class="btn btn-primary mr10" type="submit">Lưu lại</button>
                <a href="{{ route('system.index') }}" class="btn btn-white">Quay lại</a>
            </div>

        </form>
    </div>
    <input type="hidden" class="name-model" value="{{ $config['module'] }}">
@endsection
