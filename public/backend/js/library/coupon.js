(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.clickBtnSubmit = () => {
        $(document).on('submit', '#myForm',async function (e) {
            e.preventDefault();
            if(await HT.checkSubmit()) {
                this.submit();
            }
        })
    }

    HT.checkSubmit = async () => {
        let target = $('.couponCode');
        if(target.val() == '') {
            HT.setupError(target, 'Mã khuyến mại không được để trống');
            return false;
        }
        if(!await HT.checkExistCode(target)) {
            return false;
        }
        await HT.checkExistCode(target);
        if($('.couponMethod').val() == 'none') {
            toastr.clear();
            toastr.error('Bạn cần chọn kiểu giảm giá');
            return false;
        }
        if($('.list-search-item').length == 0 && $('.allProvince').prop('checked') == false && $('.couponMethod').val() == 'coupon_ship') {
            HT.setupError($('.inputSearchModel'), 'Tỉnh thành không được để trống');
            return false;
        }
        if($('.boxChooseCustomerCatalogue select').val().length == 0 && $('.applyCustomer:checked').val() !== 'all') {
            toastr.clear();
            toastr.error('Bạn cần chọn nhóm khách hàng');
            return false;
        }
        if(!$('#neverEndDate').prop('checked')) {
            let startDate = HT.parseDate($('.setupStartDate').val());
            let endDate = HT.parseDate($('.setupEndDate').val());
            if(endDate < startDate) {
                toastr.clear();
                toastr.error('Ngày kết thúc phải sau ngày bắt đầu');
                $('.setupEndDate').focus();
                return false;
            }
        }
        return true;
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

    HT.checkExistCode = async (target) => {
        let option = {
            '_token': _token,
            'isCondition': false,
            'model': 'coupon',
            'code': target.val()
        }
        return await HT.callAjax(option);
    }

    HT.callAjax = async (option = []) => {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '/admin/ajax/data/model',
                method: 'POST',
                data: option,
                dataType: 'json',
                success: function (response) {
                    let arrCode = [];
                    if(response.data.length) {
                        $.each(response.data, function(index, item) {
                            arrCode.push(item.code);
                        })
                    }
                    if($('.oldCode').length) {
                        let oldCode = $('.oldCode').val();
                        let index = $.inArray(oldCode, arrCode);
                        if(index !== -1) {
                            arrCode.splice(index, 1);
                        }
                    }
                    if($.inArray(option.code, arrCode) !== -1)  {
                        toastr.clear();
                        toastr.error('Mã giảm giá đã tồn tại, vui lòng thử mã khác');
                        $('.couponCode').focus();
                        resolve(false);
                    }else{
                        resolve(true);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    reject(false);
                }
            });
        })
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
        $(document).on('input', '.couponCode', function () {
            $(this).removeClass('input-error').next().remove();
        })
    }

    $(document).ready(function () {
        HT.clickBtnSubmit();
        HT.changeInputPromotionName();
    });
})(jQuery);