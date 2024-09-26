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
        let target = $('.promotionName');
        if(target.val() == '') {
            isSubmit = false;
            HT.setupError(target, 'Tên chương trình khuyến mại không được để trống');
            return 0;
        }
        if($('.list-search-item').length == 0 && $('.allProduct').prop('checked') == false) {
            isSubmit = false;
            HT.setupError($('.inputSearchModel'), 'Sản phẩm không được để trống');
            return 0;
        }
        if(!$('#neverEndDate').prop('checked')) {
            let startDate = HT.parseDate($('.setupStartDate').val());
            let endDate = HT.parseDate($('.setupEndDate').val());
            if(endDate < startDate) {
                isSubmit = false;
                toastr.clear();
                toastr.error('Ngày kết thúc phải sau ngày bắt đầu');
                $('.setupEndDate').focus();
                return 0;
            }
        }
        return isSubmit;
    }

    // Chuyển đổi chuỗi thời gian thành đối tượng Date
    HT.parseDate = (dateString) => {
        // Định dạng ngày tháng
        var parts = dateString.split(' ');
        var dateParts = parts[0].split('/');
        var timeParts = parts[1].split(':');

        // Tạo đối tượng Date
        return new Date(dateParts[2], dateParts[1] - 1, dateParts[0], timeParts[0], timeParts[1]);
    }

    HT.setupError = (target, message) => {
        if(!target.hasClass('input-error')) {
            target.addClass('input-error').after('<span class="alert-error">'+message+'</span>');
        }
        target.focus();
        toastr.clear();
        toastr.error(message);
    }

    HT.changeInputPromotionName = () => {
        $(document).on('input', '.promotionName', function () {
            $(this).removeClass('input-error').next().remove();
        })
    }

    $(document).ready(function () {
        HT.clickBtnSubmit();
        HT.changeInputPromotionName();
    });
})(jQuery);