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

    HT.clickBtnActionConfirm = () => {
        $(document).on('click', '.btn-action-confirm', function() {
            let code = $('input[name="code"]').val();
            let products = HT.getAndSetupProducts();
            let option = {
                'model': 'purchaseOrder',
                'id': $('.purchaseOrderId').val(),
                'user_id': $('select[name="user_id"]').val(),
                'content': '<span>Duyệt đơn đặt hàng nhập <a class="text-primary" href="#">'+ code +'</a></span>',
                'status': 'pending_processing',
                'type': 'confirm',
                'message': 'Đơn đặt hàng nhập đã được duyệt thành công.',
                'warehouse_id': $('select[name="warehouse_id"]').val(),
                'products': products,
                '_token': _token
            }
            HT.callAjax(option);
            location.reload();
        })
    }

    HT.getAndSetupProducts = () => {
        let products = $('[name^="products"], textarea[name^="products"]');
        let productObj = {};
        products.each(function() {
            var name = $(this).attr('name');
            var value = $(this).val();
            productObj[name] = value;
        });
        // console.log(productObj);
        const result = {};
        $.each(productObj, (key, value) => {
            const matches = key.match(/products\[(\d+(-\d+)?)\]\[(\w+)\]/);
            if (matches) {
                const productId = matches[1];
                const field = matches[3];

                if (!result[productId]) {
                    result[productId] = {
                        cost_price: null,
                        discount_value: null,
                        discount_type: null,
                        detail: null,
                        quantity: null
                    };
                }
                result[productId][field] = value;
            }
        });

        return result;
    }

    HT.clickBtnActionCancel = () => {
        $(document).on('click', '.btn-action-cancel', function() {
            let code = $('input[name="code"]').val();
            let products = HT.getAndSetupProducts();
            let option = {
                'model': 'purchaseOrder',
                'id': $('.purchaseOrderId').val(),
                'user_id': $('select[name="user_id"]').val(),
                'content': '<span>Hủy đơn đặt hàng nhập <a class="text-primary" href="#">'+ code +'</a></span>',
                'status': 'cancelled',
                'current_status': $('input.statusModel').val(),
                'type': 'cancel',
                'message': 'Đơn đặt hàng nhập đã hủy thành công.',
                'warehouse_id': $('select[name="warehouse_id"]').val(),
                'products': products,
                '_token': _token
            }
            console.log(option);
            HT.callAjax(option);
            location.reload();
        })
    }

    HT.callAjax = (option) => {
        $.ajax({
            url: '/admin/ajax/change/status',
            method: 'POST', 
            dataType: 'json',
            data: option,
            success: function (response) {
               toastr.clear();
               toastr.success(response.message);
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    $(document).ready(function () {
        HT.clickBtnSubmit();
        HT.clickBtnActionConfirm();
        HT.clickBtnActionCancel();
    });
})(jQuery);