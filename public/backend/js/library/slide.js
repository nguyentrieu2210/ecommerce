(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.addItemSlide = () => {
        $(document).on('click', '.addSliceItem', function() {
            let html = HT.renderItemSlide();
            $('.slideList').append(html);
            $('.emptyImage').addClass('hidden');
        })
    }

    HT.renderItemSlide = () => {
        let count = $('.slide-item').length;
        let html = `<div class="slide-item col-sm-12">
                        <div class="col-sm-3 slide-item-l">
                            <img data-type="Images" class="upload-image image-style "
                            src="/backend/img/empty-image.png" alt="">
                            <input type="hidden" name="item[image][]" value="">
                        </div>
                        <div class="col-sm-9 slide-item-r">
                            <span class="btn btn-danger item-slide-trash"><i class="fa fa-trash"></i>  Xóa ảnh</span>
                            <div class="tabs-container">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href=".general-info-${count}">THÔNG TIN CHUNG</a></li>
                                    <li class=""><a data-toggle="tab" href=".seo-info-${count}">SEO</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active general-info-${count}">
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <label>Mô tả</label> 
                                                <textarea type="text" name="item[alt][]" class="form-control"></textarea>
                                            </div>
                                            <div class="flex-space-between">
                                                <div class="form-group"> 
                                                    <input type="text" name="item[canonical][]" placeholder="Url" class="form-control br5">
                                                </div>
                                                <div class="form-group flex"> 
                                                    <label class="label-checkbox-slide" for="">Mở trong tab mới</label>
                                                    <input type="hidden" name="item[window][${count}]" value="off">
                                                    <input type="checkbox" name="item[window][${count}]" placeholder="Url" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane seo-info-${count}">
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <label>Tiêu đề ảnh</label> 
                                                <input type="text" name="item[name][]" placeholder="Nhập tiêu đề ảnh..." class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Mô tả ảnh</label> 
                                                <input type="text" name="item[description][]" placeholder="Nhập mô tả ảnh" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`
        return html;
    }

    HT.deleteItemSlide = () => {
        $(document).on('click', '.item-slide-trash', function () {
            let _this = $(this);
            _this.closest('.slide-item').remove();
            if($('.slide-item').length == 0) {
                $('.emptyImage').removeClass('hidden');
            }
        })
    }

    HT.setupInputNumber = () => {
        $('.inputNumber').on('input', function() {
            // Loại bỏ các ký tự không phải số
            var value = $(this).val().replace(/\D/g, '');
            // Định dạng số với dấu phân cách hàng ngàn
            value = Number(value).toLocaleString('en').replace(/,/g, '.');
            // Cập nhật giá trị của thẻ input
            $(this).val(value);
        });
    }

    $(document).ready(function () {
        HT.setupInputNumber();
        HT.addItemSlide();
        HT.deleteItemSlide();
    });
})(jQuery);