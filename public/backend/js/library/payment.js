(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.clickTypeDiscountPayment = () => {
        $(document).on('click', '.type-discount-payment span', function() {
            let oldType = $('.type-discount-payment span.active-discount-payment').data('type');
            $('.type-discount-payment span').removeClass('active-discount-payment');
            let _this = $(this);
            let newType = _this.data('type');
            _this.addClass('active-discount-payment');
            if(oldType !== newType) {
                if(newType == 'percent') {
                    $('.type-unit-payment').text('%');
                }else{
                    $('.type-unit-payment').text('đ');
                }
                $('.amountDiscountPaymentModal').trigger('input');
            }
        })
    }

    HT.changeInputAmountDiscountPayment = () => {
        $(document).on('input', '.amountDiscountPaymentModal', function () {
            let type = $('.active-discount-payment').data('type');
            let totalPrice = HT.extractNumber($('.totalPricePayment').text());
            let modal = $('.addDiscountPayment');
            if(type == 'percent') {
                if(parseFloat($(this).val()) > 100) {
                    $(this).val(100);
                }
            }else {
                if(parseFloat($(this).val()) > totalPrice) {
                    modal.find('.alert-error').removeClass('hidden');
                    modal.find('.error').text('Chiết khấu đơn không được vượt quá tổng tiền');
                    modal.find('.btnApplyDiscountPayment').addClass('no-click');
                }else{
                    modal.find('.alert-error').addClass('hidden');
                    modal.find('.btnApplyDiscountPayment').removeClass('no-click');
                }
            }
        })
    }

    HT.setupDiscountActivePayment = () => {
        let status = $('.statusModel').val();
        if($('.item-model').length && (status == '' || status == 'pending' || status == 'pending_processing')) {
            $('.discount-payment').addClass('discountActive');
        }
    }

    HT.clickDiscountActivePayment = () => {
        $(document).on('click', '.discountActive', function () {
            let modal = $('.addDiscountPayment');
            let type = $('.discount-type-payment').val();
            let value = $('.discount-value-payment').val();
            console.log(type);
            if(type !== '') {
                let target = '.type-discount-payment span.' + type;
                modal.find(target).trigger('click');
            }
            if(value !== '') {
                modal.find('.amountDiscountPaymentModal').val(value);

            }else {
                modal.find('.amountDiscountPaymentModal').val(0);
            }
            modal.modal('show');
        })
    }

    HT.submitModalDiscountPayment = () => {
        $(document).on('click', '.btnApplyDiscountPayment', function () {
            let type = $('.active-discount-payment').data('type');
            let totalPrice = HT.extractNumber($('.totalPricePayment').text());
            let valueDiscountPayment = $('.amountDiscountPaymentModal').val();
            let finalTotalPrice  = 0;
            let amountDiscountPayment = 0;
            let totalFee = HT.calculateTotalFeePayment();
            if(type == 'percent') {
                amountDiscountPayment = (totalPrice / 100 * valueDiscountPayment);
                finalTotalPrice = totalPrice - amountDiscountPayment + totalFee;
                $('.displayDiscountPayment').text(valueDiscountPayment + '%');
            }else{
                amountDiscountPayment = valueDiscountPayment;
                finalTotalPrice = totalPrice - amountDiscountPayment + totalFee;
                $('.displayDiscountPayment').text('-----');
            }
            $('.discount-type-payment').val(type);
            $('.discount-value-payment').val(valueDiscountPayment);
            $('.amountDiscountPayment').text(HT.formatNumber(amountDiscountPayment));
            $('.finalTotalPayment').text(HT.formatNumber(finalTotalPrice));
            $('input[name="payment[amount]"]').val(HT.formatNumber(finalTotalPrice) + 'đ');
            if(valueDiscountPayment == '' || parseFloat(valueDiscountPayment) == 0) {
                HT.resetDiscountPayment();
            }
            $('.addDiscountPayment').modal('hide');
        })
    }

    HT.deleteDiscountPayment = () => [
        $(document).on('click', '.btnDeleteDiscountPayment', function () {
            HT.resetDiscountPayment();
            let totalPrice = HT.extractNumber($('.totalPricePayment').text());
            let totalFee = HT.calculateTotalFeePayment();
            $('.finalTotalPayment').text(HT.formatNumber(totalPrice+totalFee));
            $('input[name="payment[amount]"]').val(HT.formatNumber(totalPrice+totalFee) + 'đ');
            $('.addDiscountPayment').modal('hide');
        })
    ]

    HT.calculateTotalFeePayment = () => {
        let totalFee = 0;
        if($('.valueFeePayment').length) {
            $.each($('.valueFeePayment'), function(index, elem) {
                totalFee += HT.extractNumber($(elem).text());
            })
        }
        return totalFee;
    }

    HT.resetDiscountPayment = () => {
        $('.displayDiscountPayment').text('-----');
        $('.discount-type-payment').val('');
        $('.discount-value-payment').val('');
        $('.amountDiscountPaymentModal').val(0);
        $('.amountDiscountPayment').text(0);
    }

    HT.chooseTypePayment = () => {
        $(document).on('input', 'input[name="status_payment"]', function () {
            let _this = $(this);
            let target = $('.choose-status-payment .box-paid');
            target.toggleClass('hidden');
        })
    }

    //FEE PAYMENT
    HT.setupActiveFeePayment = () => {
        let target = $('.fee-payment');
        if($('.item-model').length && $('.statusDisabled').val() == '') {
            if(!target.hasClass('feeActive')) {
                target.addClass('feeActive');
            }
        }else{
            if(target.hasClass('feeActive')) {
                target.removeClass('feeActive');
            }
        }
    }

    HT.clickFeePayment = () => {
        $(document).on('click', '.feeActive', function () {
            let modal = $('.addFeeReceivePayment');
            let data = $('input[name="import_fee"]').val();
            let firstNameItem = $('.item-fee-modal.first').find('.name-fee-item');
            let firstValueItem = $('.item-fee-modal.first').find('.value-fee-item');
            HT.clearInputError(firstNameItem);
            HT.clearInputError(firstValueItem);
            $('.list-fee-modal-extra').html('');
            if(data !== '') {
                data = JSON.parse(data);
                firstNameItem.val(data[0].name);
                firstValueItem.val(data[0].value);
                let html = '';
                let total = data[0].value;
                for(let i = 1; i < data.length; i++) {
                    html += HT.renderItemFeeModal(data[i]);
                    total += data[i].value;
                }
                $('.list-fee-modal-extra').html(html);
                $('.total-fee-modal-display').text(HT.formatNumber(total));
            }else{
                firstNameItem.val('');
                firstValueItem.val(0);
            }
            modal.modal('show');
        })
    }

    HT.renderItemFeeModal = (item) => {
        return `<div class="item-fee-modal flex mt10">
            <div class="form-group w60 pd-r20">
                <input type="text" value="${item.name}" placeholder="Nhập tên chi phí" class="name-fee-item form-control br5">
            </div>
            <div class="form-group w32 relative">
                <input type="text"  value="${HT.formatNumber(item.value)}" placeholder="" class="value-fee-item form-control pd-r20 br5 text-right inputMoney">
                <span class="type-fee-modal">đ</span>
            </div>
            <span class="w8 text-right fs20"><i class="fa fa-trash deleteItemFeeModal"></i></span>
        </div>`;
    }

    HT.addItemFeeModal = () => {
        $(document).on('click', '.add-fee-modal', function () {
            let html = HT.renderNewItemFeeModal();
            $('.list-fee-modal-extra').append(html);
            HT.setupInputMoney();
        })
    }

    HT.setupInputMoney = () => {
        $('.inputMoney').on('input', function() {
            HT.customerInputNumber($(this));
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

    HT.renderNewItemFeeModal = () => {
        return `
            <div class="item-fee-modal flex mt10">
                <div class="form-group w60 pd-r20">
                    <input type="text" value="" placeholder="Nhập tên chi phí" class="name-fee-item form-control br5">
                </div>
                <div class="form-group w32 relative">
                    <input type="text" value="0" placeholder=""  class="value-fee-item form-control pd-r20 br5 text-right inputMoney">
                    <span class="type-fee-modal">đ</span>
                </div>
                <span class="w8 text-right fs20"><i class="fa fa-trash deleteItemFeeModal"></i></span>
            </div>`;
    }

    HT.deleteItemFeeModal = () => {
        $(document).on('click', '.deleteItemFeeModal', function() {
            let _this = $(this);
            let parent = _this.closest('.item-fee-modal');
            if(parent.hasClass('first')) {
                parent.find('.name-fee-item').val('');
                parent.find('.value-fee-item').val(0);
            }else{
                parent.remove();
            }
        })
    }

    HT.changeInputNameFeeItem = () => {
        $(document).on('input', '.name-fee-item', function () {
            let _this = $(this);
            if(_this.val() !== '' && _this.hasClass('input-error')) {
                _this.removeClass('input-error').next().remove();
            }
        })
    }

    HT.changeInputValueFeeItem = () => {
        $(document).on('input', '.value-fee-item', function () {
            let _this = $(this);
            if(_this.val() !== 0 && _this.hasClass('input-error')) {
                _this.removeClass('input-error').next().remove();
            }
            let totalFee = HT.calculateTotalFee();
            $('.total-fee-modal-display').text(HT.formatNumber(totalFee.toString()));
        })
    }

    HT.calculateTotalFee = () => {
        let totalFee = 0;
        $.each($('.value-fee-item'), function (index, elem) {
            totalFee += parseFloat(HT.extractNumber($(elem).val()));
        })
        return totalFee;
    }

    //submit modal fee
    HT.clickBtnApplyFeePayment = () => {
        $(document).on('click', '.btnApplyFeePayment', function () {
            let temp = [];
            if(HT.checkValidateFeeModal()) {
                let target = $('.item-fee-modal');
                if(target.length == 1 && target.find('.name-fee-item').val() == '' && target.find('.value-fee-item').val() == 0) {
                    $('input[name="import_fee"]').val('');
                    $('.list-fee-payment').html('<span>0<span class="currency">đ</span></span>');
                }else {
                    let html = '';
                    let arr = [];
                    if(target.length == 1) {
                        arr.push(target);
                    }else{
                        arr = target;
                    }
                    $.each(arr, function(index, elem) {
                        let _elem = $(elem);
                        let name = _elem.find('.name-fee-item').val();
                        let value = HT.extractNumber(_elem.find('.value-fee-item').val());
                        temp.push({'name': name, 'value': value});
                        html += HT.renderItemFeePayment(name, value);
                    })
                    $('input[name="import_fee"]').val(JSON.stringify(temp));
                    $('.list-fee-payment').html(html);
                    
                }
                let finalTotalPayment = HT.extractNumber($('.totalPricePayment').text()) - HT.extractNumber($('.amountDiscountPayment').text()) + HT.calculateTotalFee();
                $('.finalTotalPayment').text(HT.formatNumber(finalTotalPayment));
                $('input[name="payment[amount]"]').val(HT.formatNumber(finalTotalPayment) + 'đ');
                $('.addFeeReceivePayment').modal('hide');
            }
        })
    }

    HT.renderItemFeePayment = (name, value) => {
        return `<div class="flex-space-between">
                    <span>${name}</span>
                    <span><span class="valueFeePayment">${HT.formatNumber(value)}</span><span class="currency">đ</span></span>
                </div>`;
    }

    HT.checkValidateFeeModal = () => {
        let isCheck = true;
        let item = $('.item-fee-modal');
        if(item.length > 1) {
            $.each($('.name-fee-item'), function (key, elem) {
                let _elem = $(elem);
                if(_elem.val() == '' && !_elem.hasClass('input-error')) {
                    isCheck = false;
                    HT.setupInputError(_elem, 'Tên chi phí không được để trống');
                }
            })
            $.each($('.value-fee-item'), function(key, elem) {
                let _elem = $(elem);
                if(_elem.val() == 0 && !_elem.hasClass('input-error')) {
                    isCheck = false;
                    HT.setupInputError(_elem, 'Chi phí phải khác 0');
                }
            })
        
        }else{
            let itemFirst = $('.item-fee-modal.first');
            let inputNameItemFirst = itemFirst.find('.name-fee-item');
            let inputValueItemFirst = itemFirst.find('.value-fee-item');
            if(inputNameItemFirst.val() !== '' && inputValueItemFirst.val() == 0 && !inputNameItemFirst.hasClass('input-error')) {
                isCheck = false;
                HT.setupInputError(inputValueItemFirst, 'Chi phí phải khác 0');
            }
            if(parseInt(inputValueItemFirst.val()) !== 0 && inputNameItemFirst.val() == '' && !inputNameItemFirst.hasClass('input-error')) {
                isCheck = false;
                HT.setupInputError(inputNameItemFirst, 'Tên chi phí không được để trống');
            }
        }
        return isCheck;
    }

    HT.setupInputError = (input, message) => {
        input.addClass('input-error');
        input.after('<span class="alert-error">'+message+'</span>')
    }

    HT.clearInputError = (input) => {
        if(input.hasClass('input-error')) {
            input.removeClass('input-error').next().remove();
        }
    }

    HT.clickBtnConfirmPaid = () => {
        $(document).on('click', '.btnConfirmPaid', function () {
            let payment = HT.getAndSetupPayment();
            let option = {
                "_token": _token,
                'model': 'receiveInventory',
                'id': $('.receiveInventoryId').val(),
                'user_id': $('select[name="user_id"]').val(),
                'warehouse_id': $('select[name="warehouse_id"]').val(),
                'method': 'paid',
                'message': 'Xác nhận thanh toán thành công.',
                'payment': payment,
            }
            HT.callAjaxToConfirm(option);
            $('.confirmPaymentModal').modal('hide');
            setTimeout(function () {
                location.reload();
            }, 1000);
        })
    }

    HT.getAndSetupPayment = () => {
        let payment = $('[name^="payment"]');
        let paymentObj = {};
        payment.each(function() {
            var name = $(this).attr('name');
            var value = $(this).val();
            paymentObj[name] = value;
        });
        const result = {};
        $.each(paymentObj, (key, value) => {
            const matches = key.match(/\[([A-Za-z0-9_]+)\]/);
            if (matches) {
                const paymentField = matches[1];
                result[paymentField] = value;
            }
        });
        return result;
    }

    HT.callAjaxToConfirm = (option) => {
        $.ajax({
            url: '/admin/ajax/call/method/service',
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

    HT.formatNumber = (string) => {
        return Number(string).toLocaleString('en').replace(/,/g, '.');
    }

    HT.extractNumber = (string) => {
        return parseInt(string.replace(/[^0-9-]/g, ''), 10); 
    }

    $(document).ready(function () {
        HT.clickDiscountActivePayment();
        HT.clickTypeDiscountPayment();
        HT.changeInputAmountDiscountPayment();
        HT.submitModalDiscountPayment();
        HT.deleteDiscountPayment();
        HT.setupDiscountActivePayment();
        HT.chooseTypePayment();
        HT.setupActiveFeePayment();
        HT.clickFeePayment();
        HT.addItemFeeModal();
        HT.deleteItemFeeModal();
        HT.clickBtnApplyFeePayment();
        HT.changeInputNameFeeItem();
        HT.changeInputValueFeeItem();
        HT.clickBtnConfirmPaid();
    });
})(jQuery);