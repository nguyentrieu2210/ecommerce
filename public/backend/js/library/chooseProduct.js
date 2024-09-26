(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');
    var itemChangeQuantityId = 0;

    HT.focusInputSearchProduct = () => {
        $(document).on('focus', '.inputSearchProduct', function() {
            let _this = $(this);
            let option = {
                'keyword': _this.val(),
                'model': 'product',
                'warehouse_id': $('select[name="warehouse_id"]').val(),
                'fieldSearchs': ['code', 'barcode'],
                '_token': _token
            }
            HT.callAjax(option);
        })
    }

    HT.callAjax = (option) => {
        $.ajax({
            url: '/admin/ajax/data/productByWarehouse',
            method: 'POST', 
            dataType: 'json',
            data: option,
            success: function (response) {
                let data = response.data; 
                let html = HT.renderListChooseSingleProduct(data);
                $('.render-list-model').html(html).removeClass('hidden');
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    HT.renderListChooseSingleProduct = (data) => {
        let html = '';
        $.each(data, function(index, item) {
            let length = item.product_variants.length;
            let option = {
                image: item.image,
                name: item.name,
                cost: item.cost,
                available_quantity: item.quantity - item.stock,
                id: item.id
            }
            if(length > 0) {
                $.each(item.product_variants, function(key, variant) {
                    if(item.product_variant_id == variant.id) {
                        option.name_variant = variant.name;
                        option.sku = variant.sku;
                        option.product_variant_id = variant.id;
                        html += HT.renderChooseSingleProductHtml(option);
                    }
                })
            }else {
                option.name_variant = null;
                option.sku = item.code;
                option.product_variant_id = null;
                html += HT.renderChooseSingleProductHtml(option);
            }
            
        })
        return html;
    }

    HT.renderChooseSingleProductHtml = (option) => {
        let image = option.image !== null ? option.image : '/backend/img/empty-image.png';
        let html = `<div class="render-item-model flex-space-between" data-id="${option.id}" 
            data-productVariantId="${option.product_variant_id}" data-image="${option.image}" 
            data-name="${option.name}" data-nameVariant="${option.name_variant}" 
            data-sku="${option.sku}" data-cost="${option.cost}">
                    <div class="render-item-model-l flex">
                        <div class="style-avatar-product text-center">
                            <img src="${image}" alt="">
                        </div>
                        <div class="render-item-model-info-r">
                            <span class="text-primary fs14 fw550 text-left">${option.name}</span>`;
                            if(option.name_variant !== null) {
                                html += `<br><span class="name-variant fs14">${option.name_variant}</span>`;
                            }
                            html += `<br><span class="sku-product fs12">SKU:${option.sku}</span>
                        </div>
                    </div>
                    <div class="render-item-model-r h50 text-right">
                        <p class="fw510">${option.cost}<span class="currency">đ</span></p>
                        <span>Có thể bán: <span class="text-primary fw510">${option.available_quantity}</span></span>
                    </div>
                </div>`
        return html;
    }

    HT.blurInputSearchProduct = () => {
        $(document).on('blur', '.inputSearchProduct', function() {
            setTimeout(function() {
                $('.render-list-model').addClass('hidden');
            }, 150);
            
        })
    }

    HT.clickBtnAddProduct = () => {
        $(document).on('click', '.btnAddProduct', function() {
            $('.inputSearchProduct').focus();
        })
    }

    HT.chooseSingleProduct = () => {
        $(document).on('click', '.render-item-model', function() {
            let _this = $(this);
            let arrId = [];
            let id = _this.data('id') + (_this.attr('data-productVariantId') !== 'null' ? ('-' + _this.attr('data-productVariantId')) : '');
            if($('.item-model').length == 0) {
                $('.empty-model').addClass('hidden');
                $('.list-model').removeClass('hidden');
            }else{
                $('.item-model').each(function (){
                    let variantId = $(this).attr('data-variantId');
                    let itemId = $(this).data('id') + ((variantId !== 'null' && variantId !== '') ? ('-' + variantId) : ''); 
                    arrId.push(itemId);
                })
            }
            if($.inArray(id, arrId) == -1) {
                let option = {
                    image: _this.data('image'),
                    product_id: _this.data('id'),
                    name: _this.data('name'),
                    name_variant: _this.attr('data-nameVariant'),
                    sku: _this.data('sku'),
                    cost: _this.data('cost'),
                    product_variant_id: _this.attr('data-productVariantId')
                }
                let html = HT.renderProductHtml(option);
                $('.list-model-choosed').append(html);
                if($('.inputSearchProduct').hasClass('input-error')) {
                    $('.inputSearchProduct').removeClass('input-error').next().remove();
                }
                HT.caculateTotalItem();
                HT.caculateTotalPrice();
                HT.caculateFinalTotalPrice();
                HT.setActiveDiscountPayment();
                HT.setupActiveFeePayment();
            }
        })
    }

    HT.renderProductHtml = (data) => {
        for(let key in data) {
            if(data[key] === 'null') {
                data[key] = null;
            }
        }
        let image = data.image !== null ? data.image : '/backend/img/empty-image.png';
        let canonical = '/admin/product/' + data.product_id + '/edit';
        let cost = HT.formatNumber(data.cost);
        delete data.cost;
        let detail = JSON.stringify(data);
        let variantId = data.product_variant_id;
        let itemId = data.product_id + (variantId !== null ? ('-' + variantId) : ''); 
        let html = `<div class="item-model flex" data-itemId="${itemId}" data-id="${data.product_id}" data-variantId="${data.product_variant_id}">
            <div class="item-model-info flex w40">
                <input type="hidden" class="cost_price" name="products[${itemId}][cost_price]" value="${HT.extractNumber(cost)}">
                <input type="hidden" class="discount_value" name="products[${itemId}][discount_value]" value="">
                <input type="hidden" class="discount_type" name="products[${itemId}][discount_type]" value="">
                <textarea name="products[${itemId}][detail]" class="hidden">${detail}</textarea>
                <div class="style-avatar-product text-center">
                    <img src="${image}" alt="">
                </div>
                <div class="item-model-info-r text-left">
                    <a href="${canonical}" class="text-primary confirmLink">${data.name}</a>`
                    if(data.name_variant !== null) {
                        html += `<span class="name-variant fs14">${data.name_variant}</span>`;
                    }
                    html+= `<span class="sku-product fs12">${data.sku}</span>
                </div>
            </div>
            <input type="number" name="products[${itemId}][quantity]" value="1" min="1" placeholder="" class="form-control item-model-quantity w15">
            <span class="cost-price w15 text-right text-primary addDiscount">
                <span class="costPrice">${cost}</span><span class="currency">đ</span><br>
                <span class="oldCostPrice hidden">${cost}</span>
            </span>
            <span class=" w20 text-right"><span class="subTotal">${cost}</span><span class="currency">đ</span></span>
            <span class="w10 text-center delete-item-model"><i class="fa fa-trash"></i></span>
        </div>`;
        return html;
    }

    HT.changeInputModelQuantity = () => {
        $(document).on('input', '.item-model-quantity', function () {
            let _this = $(this);
            let value = _this.val();
            if(value <= 0) {
                _this.val(1);
            }
            let parent = _this.closest('.item-model');
            HT.reCalculate(parent, value);
        })
    }
    
    HT.reCalculate = (parent, value) => {
        let costPrice = HT.extractNumber(parent.find('.costPrice').text());
        let subTotal = HT.formatNumber(value * costPrice);
        parent.find('.subTotal').text(subTotal);
        HT.caculateTotalPrice();
        HT.caculateTotalItem();
        HT.caculateFinalTotalPrice();
    }
    
    HT.changeCostPrice = () => {
        $(document).on('click', '.addDiscount', function () {
            let _this = $(this);
            let parent = _this.closest('.item-model');
            let costPrice = _this.find('.costPrice').text();
            let modal = $('.changeCostPrice');
            modal.data('target', _this.closest('.item-model').attr('data-itemId'));
            modal.find('.discountCostPrice').text(costPrice);
            if(parent.find('.oldCostPrice').hasClass('hidden')) {
                modal.find('.costPriceModal').val(costPrice);
                modal.find('.amountDiscount').val(0);
            }else {
                let oldCostPrice = parent.find('.oldCostPrice').text();
                let typeDiscount = parent.find('.discount_type').val();
                let amountDiscount = HT.formatNumber(parent.find('.discount_value').val());
                modal.find('.costPriceModal').val(oldCostPrice);
                modal.find('.amountDiscount').val(amountDiscount);
                if(typeDiscount == 'percent') {
                    modal.find('.type-discount span.percent').trigger('click');
                }else{
                    modal.find('.type-discount span.value').trigger('click');
                }
            }
            if(!modal.find('.alert-error').hasClass('hidden')) {
                modal.find('.alert-error').addClass('hidden');
            }
            modal.modal('show');
        })
    }

    // Modal điều chỉnh giá
    HT.chooseTypeDiscount = () => {
        $(document).on('click', '.type-discount span', function () {
            let oldType = $('.type-discount span.active-discount').data('type');
            let _this = $(this);
            $('.type-discount span').removeClass('active-discount');
            let type = _this.attr('class');
            _this.addClass('active-discount');
            let parent = _this.closest('.chooseTypeDiscount');
            if(oldType !== type) {
                if(type == 'percent') {
                    parent.find('.type-unit').text('%');
                }else{
                    parent.find('.type-unit').text('đ');
                }
                $('.amountDiscount').trigger('input');
            }
        })
    }

    HT.changeInputAmountDiscount = () => {
        $(document).on('input', '.amountDiscount', function () {
            let type = $('.active-discount').data('type');
            let originalCostPrice = HT.extractNumber($('.costPriceModal').val());
            let modal = $('.changeCostPrice');
            if(type == 'percent') {
                if(parseFloat(HT.extractNumber($(this).val())) > 100) {
                    $(this).val(100);
                }
                originalCostPrice  = originalCostPrice - ($(this).val() * 0.01 * originalCostPrice);
            }else{
                if(parseFloat(HT.extractNumber($(this).val())) > originalCostPrice) {
                    modal.find('.alert-error').removeClass('hidden');
                    modal.find('.error').text('Chiết khấu sản phẩm không được vượt quá đơn giá');
                    modal.find('.btnApplyDiscount').addClass('no-click');
                }else{
                    modal.find('.alert-error').addClass('hidden');
                    modal.find('.btnApplyDiscount').removeClass('no-click');
                }
                originalCostPrice = originalCostPrice - HT.extractNumber($(this).val());
            }   
            $('.discountCostPrice').text(HT.formatNumber(originalCostPrice));
        })
    }

    HT.submitDiscountModal = () => {
        $(document).on('click', '.btnApplyDiscount', function () {
            let modal = $(this).closest('.changeCostPrice');
            let target = $('div[data-itemId="' + modal.data('target') + '"]');
            let oldCostPrice = modal.find('.costPriceModal').val();
            let newCostPrice = modal.find('.discountCostPrice').text();
            let discountType = $('.active-discount').data('type');
            let discountValue = $('.amountDiscount').val();
            target.find('.costPrice').text(newCostPrice);
            target.find('.oldCostPrice').text(oldCostPrice);
            target.find('.cost_price').val(HT.extractNumber(newCostPrice));
            if($('.amountDiscount').val() !== '0') {
                target.find('.discount_type').val(discountType);
                target.find('.discount_value').val(HT.extractNumber(discountValue));
                target.find('.oldCostPrice').removeClass('hidden');
            }else{
                target.find('.discount_type').val('');
                target.find('.discount_value').val('');
                target.find('.oldCostPrice').addClass('hidden');
            }
            target.find('.item-model-quantity').trigger('input');
            HT.caculateTotalPrice();
            modal.modal('hide');
        })
    }

    HT.deleteItemModel = () => {
        $(document).on('click', '.delete-item-model', function () {
            let _this = $(this);
            _this.closest('.item-model').remove();
            HT.caculateTotalItem();
            HT.caculateTotalPrice();
            HT.setActiveDiscountPayment();
            HT.setupActiveFeePayment();
            if($('.item-model').length == 0) {
                $('.empty-model').removeClass('hidden');
                $('.list-model').addClass('hidden');
            }
            HT.caculateFinalTotalPrice();
        })
    }

    HT.setupActiveFeePayment = () => {
        let target = $('.fee-payment');
        if($('.item-model').length) {
            if(!target.hasClass('feeActive')) {
                target.addClass('feeActive');
            }
        }else{
            if(target.hasClass('feeActive')) {
                target.removeClass('feeActive');
            }
        }
    }

    //Payment
    HT.caculateTotalPrice = () => {
        let totalPrice = 0;
        $('.item-model').each(function () {
            totalPrice += parseFloat(HT.extractNumber($(this).find('.subTotal').text()));
        })
        $('.price-total-payment').val(totalPrice);
        $('.totalPricePayment').text(HT.formatNumber(totalPrice));
    }

    HT.caculateTotalItem = () => {
        let totalItem = 0;
        $('.item-model').each(function () {
            let target = $(this).find('.item-model-quantity');
            totalItem += parseInt(target.val()) || parseInt(target.text());
        })
        $('.totalItemPayment').text(HT.formatNumber(totalItem));
        $('.quantity-total-payment').val(totalItem);
    }

    HT.caculateFinalTotalPrice = () => {
        let totalPrice = HT.extractNumber($('.totalPricePayment').text());
        let amountDiscountPayment = HT.extractNumber($('.amountDiscountPayment').text());
        let totalFee = HT.calculateTotalFeePayment();
        let finalTotalPrice  = totalPrice - amountDiscountPayment + totalFee;
        $('.finalTotalPayment').text(HT.formatNumber(finalTotalPrice));
    }

    HT.calculateTotalFeePayment = () => {
        let totalFee = 0;
        if($('.valueFeePayment').length) {
            $.each($('.valueFeePayment'), function(index, elem) {
                totalFee += HT.extractNumber($(elem).text());
            })
        }
        return totalFee;
    }

    HT.setActiveDiscountPayment = () => {
        let target = $('.discount-payment');
        if($('.item-model').length == 0) {
            if(target.hasClass('discountActive')) {
                target.removeClass('discountActive');
            }
        }else {
            if(!target.hasClass('discountActive')) {
                target.addClass('discountActive');
            }
        }
    }

    HT.clickBtnConfirmReceiveInventory = () => {
        $(document).on('click', '.btnConfirmReceiveInventory span', function () {
            let products = HT.getAndSetupProducts();
            let option = {
                "_token": _token,
                'model': 'ReceiveInventory',
                'id': $('.receiveInventoryId').val(),
                'user_id': $('select[name="user_id"]').val(),
                'warehouse_id': $('select[name="warehouse_id"]').val(),
                'method': 'received',
                'message': 'Xác nhận nhập kho thành công.',
                'products': products
            }
            HT.callAjaxToConfirm(option);
        })
    }

    HT.callAjaxToConfirm = (option) => {
        $.ajax({
            url: '/admin/ajax/call/method/service',
            method: 'POST', 
            dataType: 'json',
            data: option,
            success: function (response) {
                location.reload();
                toastr.clear();
                toastr.success(response.message);
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
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

    HT.clickChangeQuantityReceive = () => {
        $(document).on('click', '.changeQuantityReceive', function () {
            let _this = $(this);
            let parent = _this.closest('.item-model');
            let quantity = _this.text();
            let quantityReject = parent.find('.item-model-quantity-reject').text();
            let reasonReject = parent.find('.rejection_reason').val();
            itemChangeQuantityId = _this.closest('.item-model').attr('data-itemId');
            $('.quantityReceive').val(quantity);
            $('.quantityReject').val(quantityReject);
            $('.rejectReason').val(reasonReject);
            let modal = $('.changeQuantityReceiveModal');
            modal.modal('show');
        })
    }

    HT.changeInputQuantityModal = () => {
        $(document).on('input', '.inputQuantityModal', function() {
            let target = $('.changeQuantityReceiveModal .alert-error');
            if($(this).val() == '0' && $('.quantityReject').val() == '0') {
                target.removeClass('hidden');
            }else{
                if(!target.hasClass('hidden')){
                    target.addClass('hidden');
                }
            }
        })
    }

    HT.clickBtnApplyChangeQuantityModal = () => {
        $(document).on('click', '.btnApplyChangeQuantityModal', function() {
            let qtyReceive = $('.quantityReceive').val();
            let qtyReject = $('.quantityReject').val();
            let reasonReject = $('.rejectReason').val();
            let target = $('.item-model[data-itemId="'+ itemChangeQuantityId +'"]');
            if(qtyReceive == '0' && qtyReject == '0') {
                target.remove();
            }else{
                target.find('.quantity_receive').val(HT.extractNumber(qtyReceive));
                target.find('.changeQuantityReceive').text(qtyReceive);
                if(parseInt(HT.extractNumber(qtyReject)) > 0) {
                    target.find('.quantity_reject').val(HT.extractNumber(qtyReject));
                    target.find('.item-model-quantity-reject').text(qtyReject).removeClass('hidden');
                }
                if(reasonReject == '') {
                    target.find('.rejection_reason').val('');
                }else{
                    target.find('.rejection_reason').val(reasonReject);
                }
            }
            
            HT.reCalculate(target, HT.extractNumber(qtyReceive));
            itemChangeQuantityId = 0;
            $('.changeQuantityReceiveModal').modal('hide');
        })
    }

    HT.formatNumber = (string) => {
        return Number(string).toLocaleString('en').replace(/,/g, '.');
    }

    HT.extractNumber = (string) => {
        return parseInt(string.replace(/[^0-9-]/g, ''), 10); 
    }

    $(document).ready(function () {
        HT.focusInputSearchProduct();
        HT.blurInputSearchProduct();
        HT.clickBtnAddProduct();
        HT.chooseSingleProduct();
        HT.changeInputModelQuantity(); 
        HT.changeCostPrice();
        HT.chooseTypeDiscount();
        HT.changeInputAmountDiscount();
        HT.submitDiscountModal();
        HT.deleteItemModel();
        HT.clickBtnConfirmReceiveInventory();
        HT.clickChangeQuantityReceive();
        HT.changeInputQuantityModal();
        HT.clickBtnApplyChangeQuantityModal();
    });
})(jQuery);