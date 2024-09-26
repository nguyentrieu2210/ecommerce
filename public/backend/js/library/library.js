(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.setupSelect2 = () => {
        if($('.setupSelect2').length) {
            $('.setupSelect2').select2();
        }
    }

    HT.switchChery = () => {
        let inputs = $('.js-switch');
        if (inputs.length) {
            inputs.each(function (index, element) {
                var switchery = new Switchery(element, { color: '#1AB394' });
            })
        }
    }

    HT.callAjax = (option = [], url, method) => {
        $.ajax({
            url: url,
            method: method,
            data: option,
            dataType: 'json',
            success: function (response) {
                toastr.clear();
                toastr.success(response.message);
            },
            error: function (xhr, status, error) {
                toastr.error(response.message);
                console.error('Error:', error);
            }
        });
    }

    HT.changeStatusPublish = () => {
        $(document).on('click', '.switchery', function () {
            let _this = $(this);
            let tr = _this.closest('tr');
            let itemId = tr.find('.check-id').val();
            let isChecked = tr.find('.js-switch').prop('checked');
            let option = {
                'id': itemId,
                'data': isChecked ? 2 : 1,
                'model': $('.name-model').val(),
                '_token': _token
            }
            HT.callAjax(option, '/admin/ajax/change/publish', 'POST');
        })
    }

    HT.chooseItem = () => {
        $(document).on('click', '.check-id', function () {
            let _this = $(this);
            let trChooseItem = _this.closest('tr');
            if (trChooseItem.hasClass('item-active')) {
                trChooseItem.removeClass('item-active');
            } else {
                trChooseItem.addClass('item-active');
            }
            if ($('.item-active').length == $('tbody').find('tr').length) {
                $('.checkAll').prop('checked', true);
            } else {
                $('.checkAll').prop('checked', false);
            }
        })
    }

    HT.changeStatusMultiplePublish = () => {
        $(document).on('click', '.actionPublish', function (e) {
            e.preventDefault();
            let _this = $(this);
            let publishValue = _this.attr('data-publish');
            let arrChangePublish = [];
            if ($('tbody').find('tr').length) {
                $('tbody').find('tr').each(function (index, element) {
                    let _elem = $(element);
                    if (_elem.hasClass('item-active')) {
                        let itemCheckId = _elem.find('.check-id');
                        itemCheckId.prop('checked', false);
                        _elem.removeClass('item-active');
                        $('.checkAll').prop('checked', false);
                        let inputItem = _elem.find('.js-switch');
                        let isCheckedItem = inputItem.prop('checked') ? 2 : 1;
                        if (isCheckedItem != publishValue) {
                            inputItem.trigger('click');
                        }
                        arrChangePublish.push(itemCheckId.val());
                    }
                });
                let option = {
                    'arrId': arrChangePublish,
                    'data': publishValue,
                    'model': $('.name-model').val(),
                    '_token': _token
                }
                HT.callAjax(option, "/admin/ajax/change/multiple/publish", 'POST');
            }
        })
    }

    HT.onClickCheckAll = () => {
        $(document).on('click', '.checkAll', function () {
            let _this = $(this);
            let arrTr = $('tbody').find('tr');
            let isCheckedAll = _this.prop('checked');
            if (arrTr.length) {
                arrTr.find('.check-id').prop('checked', isCheckedAll ? true : false);
                if (isCheckedAll) {
                    arrTr.addClass('item-active');
                } else {
                    arrTr.removeClass('item-active');
                }
            }
        })
    }

    HT.datePicker = () => {
        if($('.input-group.date').length) {
            $('.input-group.date').datepicker({
                todayBtn: "linked",
                dateFormat: 'dd/mm/yy',
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
            });
        }
    }

    HT.uploadImageToInput = () => {
        $(document).on('click', '.upload-image', function(e) {
            e.preventDefault();
            let _this = $(this);
            let type = _this.attr('data-type');
            HT.setupCkFinder2(_this, type);
        })
    }

    HT.setupCkFinder2 = (object, type) => {
        if (typeof (type) == 'undefined') {
            type = 'Images';
        }
        var finder = new CKFinder();
        if (typeof finder !== 'undefined') {
            finder.resourceType = type;
            finder.selectActionFunction = function (fileUrl, data) {
                if(object.attr('data-click') == 'noImage') {
                    let parent = object.closest('.box-choose-image');
                    parent.find('img').attr('src', fileUrl);
                    parent.find('input').val(fileUrl);
                }else{
                    object.attr("src", fileUrl);
                    object.next('input').val(fileUrl);
                }
            }
            finder.popup();
        }
    }

    HT.setupCkeditor = () => {
        if (typeof CKEDITOR !== 'undefined') {
            $('.setupCkeditor').each(function () {
                let height = $(this).attr('data-height') || 500;
                CKEDITOR.replace(this, {
                    height: height
                });
            })
        }
    }

    HT.setupDatetimePicker = () => {
        if ($('.setupDatetimePicker').length) {
            $('.setupDatetimePicker').each(function() {
                const $this = $(this);
                const defaultDate = $this.val() !== '' ? $this.val() : new Date(); 
                
                $this.datetimepicker({
                    format: 'd/m/Y H:i',
                    minDate: 0, 
                    step: 30, 
                    defaultDate: defaultDate 
                });
            });
        }
    };
    

    HT.setDatetimePickerNow = () => {
        if ($('.setupDatetimePickerNow').length) {
            $('.setupDatetimePickerNow').datetimepicker({
                format: 'd/m/Y H:i',
                minDate: 0, // Không cho phép chọn ngày trước ngày hiện tại
                step: 30, // Khoảng cách 30 phút giữa các lần chọn thời gian
                defaultDate: new Date(), // Ngày mặc định là ngày hiện tại
                value: new Date() // Giá trị mặc định là thời gian hiện tại
            });
        }
    };
    

    HT.setupSortui = () => {
        if($('.sortable').length) {
            $('.sortable').sortable();
            $('.sortable').disableSelection();
        }
    }

    HT.initValueInputNumber = () => {
        if($('.inputMoney').val() == "") {
            $('.inputMoney').val(0);
        }
    }

    HT.setupInputMoney = () => {
        $('.inputMoney').on('input', function() {
            HT.customerInputNumber($(this));
        });
    }

    HT.setupInputNumber = () => {
        $('.inputNumber').on('input', function() {
            let _this = $(this);
            let value = _this.val().replace(/\D/g, '');
            
            if (value === '') {
                _this.val('');
            } else {
                // Định dạng số với dấu phân cách hàng ngàn
                value = Number(value).toLocaleString('en').replace(/,/g, '.');
                // Cập nhật giá trị của thẻ input
                _this.val(value);
            }
        });
    }

    HT.customerInputNumber = (_this) => {
        // Loại bỏ các ký tự không phải số
        let value = _this.val().replace(/\D/g, '');
        // Định dạng số với dấu phân cách hàng ngàn
        value = Number(value).toLocaleString('en').replace(/,/g, '.');
        // Cập nhật giá trị của thẻ input
        _this.val(value);
    }

    HT.setupErrorModel = () => {
        if($('.errorModal').val()) {
            $('.'+$('.errorModal').data('target')).trigger('click');
        }
    }
    $(document).ready(function () {
        HT.setupSelect2();
        HT.changeStatusPublish();
        HT.switchChery();
        HT.chooseItem();
        HT.changeStatusMultiplePublish();
        HT.onClickCheckAll();
        HT.datePicker();
        HT.uploadImageToInput();
        HT.setupCkeditor();
        HT.setupSortui();
        HT.setupInputMoney();
        HT.setupInputNumber();
        HT.initValueInputNumber();
        HT.setupErrorModel();
        HT.setupDatetimePicker();
        HT.setDatetimePickerNow();
    });
})(jQuery);