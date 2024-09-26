(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.clickBtnSubmit = () => {
        $(document).on('submit', '#myForm', function (e) {
            e.preventDefault();
            if(HT.checkSubmit()) {
                this.submit();
            }
        })
    }

    HT.checkSubmit = () => {
        let isSubmit = true;
        let inputSearchProduct = $('.inputSearchProduct');
        let inputSearchSupplier = $('.inputSearchSupplier');
        if($('.item-model').length == 0) {
            isSubmit = false;
            HT.setupError(inputSearchProduct, 'Vui lòng thêm sản phẩm vào đơn đặt hàng nhập!');
            return 0;
        }
        if(!$('.search-supplier').hasClass('hidden')) {
            isSubmit = false;
            HT.setupError(inputSearchSupplier, 'Nhà cung cấp không được để trống!');
        }
        return isSubmit;
    }

    HT.setupError = (target, message) => {
        if(!target.hasClass('input-error')) {
            target.addClass('input-error').after('<span class="alert-error">'+message+'</span>');
        }
        target.focus();
        toastr.clear();
        toastr.error('Nhà cung cấp không được để trống!');
    }

    $(document).ready(function () {
        HT.clickBtnSubmit();
    });
})(jQuery);