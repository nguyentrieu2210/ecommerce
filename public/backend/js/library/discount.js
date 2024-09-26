(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');
    var searchModel = null;
    var listIdModel = [];

    HT.clickTypeDiscountPayment = () => {
        $(document).on('click', '.type-discount span', function() {
            let oldType = $('.type-discount span.active-discount').data('type');
            $('.type-discount span').removeClass('active-discount');
            let _this = $(this);
            let newType = _this.data('type');
            _this.addClass('active-discount');
            if(oldType !== newType) {
                if(newType == 'percent') {
                    $('.type-unit').text('%');
                    $('input[name="discount_type"]').val('percent');
                }else{
                    $('.type-unit').text('đ');
                    $('input[name="discount_type"]').val('value');
                }
            }
        })
    }

    HT.chooseModel = () => {
        $(document).on('input', '.applyObject', function () {
            $('.box-search-model').remove();
            let model = $(this).closest('label').attr('data-model');
            searchModel = model;
            $('input[name="model"]').val(model);
            listIdModel = [];
            if(model !== 'all') {
                let target = $(this).closest('label');
                let html = HT.renderBoxSearchHtml(model);
                target.after(html);
            }
        })
    }

    HT.renderBoxSearchHtml = (model) => {
        return `<div class="box-search-model">
            <div class="form-group search-model ml40 mb0">
                <i class="fa fa-search"></i>
                <input type="text" data-model="${model}" class="inputSearchModel form-control w95" value="" placeholder="Tìm kiếm theo tên">
            </div>
            <div class="list-search ml40">
            </div>
        </div>`;
    }

    HT.clickInputSearchModel = () => {
        $(document).on('click', '.inputSearchModel', function () {
            let oldSearchModel = searchModel;
            searchModel = $(this).attr('data-model');
            let option = {
                '_token': _token,
                'model': searchModel,
                'keyword': '',
                'oldSearchModel': oldSearchModel
            }
            if(searchModel == 'product') {
                option['relation'] = ['product_variants'];
            }else{
                option['relation'] = null;
            }
            HT.callAjax(option);
       
            $('.chooseMultiple').modal('show');
        })
    }

    HT.callAjax = (option) => {
        $.ajax({
            url: '/admin/ajax/search/model',
            method: 'POST', 
            dataType: 'json',
            data: option,
            success: function (response) {
                let data = response.data; 
                let html = '';
                if(searchModel == 'product') {
                    html = HT.renderListMultipleProductModal(data, option.oldSearchModel);
                }else if(searchModel == 'productCatalogue') {
                    html = HT.renderListMultipleProductCatalogueModal(data, option.oldSearchModel);
                }else{
                    html = HT.renderListMultipleProvinceModal(data, option.oldSearchModel);
                }
                $('.render-list-multiple-model').html(html);
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    HT.renderListMultipleProductModal = (data, oldSearchModel) => {
        let html = '';
        $.each(data, function (index, item) {
            if(item.product_variants.length) {
                $.each(item.product_variants, function(key, variant) {
                    html += HT.renderMultipleProductModalHtml(item,oldSearchModel, variant);
                })
            }else{
                html += HT.renderMultipleProductModalHtml(item, oldSearchModel);
            }
      
        })
        return html;
    }

    HT.renderMultipleProductModalHtml = (dataProduct, oldSearchModel, dataVariant = null) => {
        let nameVariant = dataVariant !== null ? dataVariant.name : '';
        let variantId = dataVariant !== null ? dataVariant.id : '';
        let image = dataProduct.image !== null ? dataProduct.image : '/backend/img/empty-image.png';
        let itemId = dataProduct.id + (dataVariant !== null ? ('-' + dataVariant.id) : '');
        let isCheck = '';
        if(oldSearchModel == searchModel && $.inArray(itemId, listIdModel) !== -1) {
            isCheck = 'checked';
        } 
        let html = `<div class="item-multiple-model" data-itemId=${itemId} data-name="${dataProduct.name}" data-nameVariant="${nameVariant}" data-productId="${dataProduct.id}" data-productVariantId="${variantId}" data-image="${dataProduct.image}">
            <div class="render-item-multiple-model flex-space-between">
                <div class="render-item-model-l flex">
                    <div class="check-multiple-model"><input type="checkbox" ${isCheck} class="check-id" value="${itemId}"></div>
                    <div class="style-avatar-product text-center">
                        <img src="${image}" alt="">
                    </div>
                    <div class="render-item-model-info-r">
                        <span class=" fs14 fw510">${dataProduct.name}</span><br>`
                        if(dataVariant !== null) {
                            html += `<span class=" fs14 fw500 text-theme">${nameVariant}</span>`;
                        }
                        html += `
                    </div>
                </div>
            </div>
        </div>`;
        return html;
    }

    HT.renderListMultipleProductCatalogueModal = (data, oldSearchModel) => {
        let html = '';
        $.each(data, function(index, item) {
            let isCheck = '';
            let itemId = item.id
            if(oldSearchModel == searchModel && $.inArray(itemId.toString(), listIdModel) !== -1) {
                isCheck = 'checked';
            } 
            let image = item.image !== null ? item.image : '/backend/img/empty-image.png';
            html += `<div class="item-multiple-model" data-itemId="${item.id}" data-name="${item.name}" data-image="${item.image}" data-productCatalogueId="${item.id}">
                <div class="render-item-multiple-model flex-space-between">
                    <div class="render-item-model-l flex">
                        <div class="check-multiple-model"><input ${isCheck} type="checkbox" class="check-id" value="${item.id}"></div>
                        <div class="style-avatar-product text-center">
                            <img src="${image}" alt="">
                        </div>
                        <div class="render-item-model-info-r">
                            <span class=" fs14 fw510">${item.name}</span><br>
                        </div>
                    </div>
                </div>
            </div>`;
        })
        return html;
    }

    HT.renderListMultipleProvinceModal = (data, oldSearchModel) => {
        let html = '';
        $.each(data, function(index, item) {
            let isCheck = '';
            let itemId = item.code
            if(oldSearchModel == searchModel && $.inArray(itemId.toString(), listIdModel) !== -1) {
                isCheck = 'checked';
            } 
            html += `<div class="item-multiple-model" data-itemId="${itemId}" data-name="${item.name}" data-provinceCode="${itemId}">
                <div class="render-item-multiple-model flex-space-between">
                    <div class="render-item-model-l flex">
                        <div class="check-multiple-model"><input ${isCheck} type="checkbox" class="check-id" value="${item.id}"></div>
                        <div class="render-item-model-info-r">
                            <span class=" fs14 fw510">${item.name}</span><br>
                        </div>
                    </div>
                </div>
            </div>`;
        })
        return html;
    }

    //Tìm kiếm sản phẩm theo tên trên modal chọn
    HT.changeInputSearchModelModal = () => {
        $(document).on('input', '.inputSearchModelModal', function () {
            let _this = $(this);
            let option = {
                '_token': _token,
                'model': searchModel,
                'keyword': _this.val(),
            }
            if(searchModel == 'product') {
                option['relation'] = ['product_variants'];
            }else{
                option['relation'] = null;
            }
            HT.callAjax(option);
        })
    }

    HT.clickItemModelModal = () => {
        $(document).on('click', '.item-multiple-model', function () {
            let target = $(this).find('.check-id')
            target.prop('checked', !target.prop('checked'));
        })
    }

    //xác nhận danh sách đã chọn từ modal
    HT.applyNewListModelModal = () => {
        $(document).on('click', '.btnApplyNewListModel', function() {
            let html = '';
            let data = {};
            listIdModel = [];
            $.each($('.item-multiple-model'), function(index, elem) {
                let _elem = $(elem);
                if(_elem.find('.check-id').prop('checked')) {   
                    listIdModel.push(String(_elem.attr('data-itemId')));
                    if(searchModel == 'product') {
                        data = {
                            'name': _elem.attr('data-name'),
                            'name_variant': _elem.attr('data-nameVariant'),
                            'product_id': _elem.attr('data-productId'),
                            'product_variant_id': _elem.attr('data-productVariantId'),
                            'image': _elem.attr('data-image')
                        }
                    }else if(searchModel == 'productCatalogue'){
                        data = {
                            'name': _elem.attr('data-name'),
                            'image': _elem.attr('data-image'),
                            'product_catalogue_id': _elem.attr('data-productCatalogueId'),
                        }
                    }else{
                        data = {
                            'name': _elem.attr('data-name'),
                            'code': _elem.attr('data-provinceCode'),
                        }
                    }
                    html += HT.renderItemModel(data);
                }
            })
            $('.list-search').html(html);
            HT.clearErrorNoneProduct();
            $('.chooseMultiple').modal('hide');
        })
    }

    HT.clearErrorNoneProduct = () => {
        if($('.list-search-item').length) {
            $('.inputSearchModel').removeClass('input-error').next().remove();
        }
    }

    HT.renderItemModel = (data) => {
        let html = '';
        let itemId = searchModel !== 'province' ? (searchModel == 'product' ? (data.product_id + (data.product_variant_id !== '' ? ('-' + data.product_variant_id) : '')) : data.product_catalogue_id) : data.code;
        let image = data.image !== 'null' ? data.image : '/backend/img/empty-image.png';
        html = `<div data-itemId="${itemId}" class="list-search-item flex-space-between" style="min-height: 50px">
                    <div class="list-search-item-l flex">`
                        if(searchModel !== 'province') {
                            html += `<div class="box-image" >
                                <img style="width: 40px; height:auto" src="${image}" alt="">
                            </div>`;
                        }
                        html += `<div><span>${data.name}</span><br>`
                        if(searchModel == 'product' && data.name_variant !== null) {
                            html += `<span class="text-theme">${data.name_variant}</span>`
                        }
        if(searchModel == 'product') {
            html += `<input type="hidden" name="detail[object][name_variant][]" value="${data.name_variant}">
                    <input type="hidden" name="detail[object][product_id][]" value="${data.product_id}">
                    <input type="hidden" name="detail[object][product_variant_id][]" value="${data.product_variant_id}">
                    <input type="hidden" name="detail[object][image][]" value="${data.image}">`;
        }else if(searchModel == 'productCatalogue'){
            html += `<input type="hidden" name="detail[object][product_catalogue_id][]" value="${data.product_catalogue_id}">
                    <input type="hidden" name="detail[object][image][]" value="${data.image}">`;
        }else {
            html += `<input type="hidden" name="detail[object][code][]" value="${data.code}">`;
        }
        html += `</div><input type="hidden" name="detail[object][name][]" value="${data.name}">
                
                </div>
                <i class="fa fa-times icon-removed"></i>
            </div>`;
        return html;
    }

    //Xóa item được chọn
    HT.deleteItemModelSearch = () => {
        $(document).on('click', '.icon-removed', function() {
            let parent = $(this).closest('.list-search-item');
            let itemId = String(parent.attr('data-itemId'));
            parent.remove();
            listIdModel = listIdModel.filter(function(value) {
                return value !== itemId;
            })
        })
    }

    HT.setupStatusNeverEndDate = () => {
        if($('input#neverEndDate').prop('checked')) {
            $('input[name="end_date"]').prop('disabled', true);

        }else{
            $('input[name="end_date"]').prop('disabled', false);
            let endDate = new Date(new Date().setDate(new Date().getDate() + 1));
            if($('.endDate').length && $('.endDate').val() !== '') {
                endDate = $('endDate').val();
            }
            $('input[name="end_date"]').datetimepicker({
                format: 'd/m/Y H:i',
                minDate: 0, // Không cho phép chọn ngày trước ngày hiện tại
                step: 30, // Khoảng cách 30 phút giữa các lần chọn thời gian
                defaultDate: endDate,// Ngày mặc định là ngày hiện tại cộng thêm 1 ngày
                value: endDate, // Giá trị mặc định là thời gian hiện tại công thêm 1 ngày
            });
        }
    }

    HT.setupStartDate = () => {
        let startDate = new Date();
        if($('.startDate').length) {
            startDate = $('startDate').val();
        }
        if($('.setupStartDate').length) {
            $('.setupStartDate').datetimepicker({
                format: 'd/m/Y H:i',
                minDate: 0, // Không cho phép chọn ngày trước ngày hiện tại
                step: 30, // Khoảng cách 30 phút giữa các lần chọn thời gian
                defaultDate: startDate, // Ngày mặc định là ngày hiện tại
                value: startDate // Giá trị mặc định là thời gian hiện tại
            });
        }
    }

    HT.setupEndDate = () => {
        let endDate = new Date();
        if($('.endDate').length) {
            endDate = $('endDate').val();
        }
        if($('.setupEndDate').length) {
            $('.setupEndDate').datetimepicker({
                format: 'd/m/Y H:i',
                minDate: 0, // Không cho phép chọn ngày trước ngày hiện tại
                step: 30, // Khoảng cách 30 phút giữa các lần chọn thời gian
                defaultDate: endDate, // Ngày mặc định là ngày hiện tại
                value: endDate // Giá trị mặc định là thời gian hiện tại
            });
        }
    }

    HT.clickNeverEndDate = () => {
        $(document).on('click', '.checkbox-never-end-date', function() {
            HT.setupStatusNeverEndDate();
        })
    }

    //Hiển thị lại các item đã được chọn trong trường hợp edit
    HT.setupListModelForEdit = () => {
        let data = null;
        if($('.dataDetail').length) {
            data = JSON.parse($('.dataDetail').val());
        }
        if(data !== null && 'model' in data && data.model !== 'all') {
            searchModel = data.model;
            let target = $('label[data-model="'+ searchModel +'"]');
            target.after(HT.renderBoxSearchHtml(searchModel));
            let count = data.object.name.length;
            if(searchModel == 'product') {
                let arrId = [];
                for(let i = 0; i < count; i++) {
                    let id = data.object.product_id[i] + (data.object.product_variant_id[i] !== null ? '-'+data.object.product_variant_id[i] : '');
                    arrId.push(id);
                }
                listIdModel = arrId;
            }else if(searchModel == 'productCatalogue'){
                listIdModel = data.object.product_catalogue_id;
            }else{
                listIdModel = data.object.code;
            }
            let html = '';
            for(let i = 0; i < count; i++) {
                let temp = [];
                $.each(data.object, function(key, item) {
                    temp[key] = item[i];
                })
                html += HT.renderItemModel(temp);
            }
            $('.list-search').html(html);
        }
    }

    //Chọn hình thức cho mã giảm giá
    HT.changeDiscountCouponMethod = () => {
        $(document).on('change', '.couponMethod', function() {
            let _this = $(this);
            if(_this.val() == 'coupon_order') {
                $('.box-discount-order').removeClass('hidden');
                $('.box-discount-order').find('input[name="max_discount"]').prop('disabled', false);
                $('.box-discount-ship').find('input[name="max_discount"]').prop('disabled', true);
                HT.addClassHidden('box-discount-ship');
                HT.addClassHidden('iboxApplyFor');
            }else if(_this.val() == 'coupon_ship'){
                $('.box-discount-ship').removeClass('hidden');
                $('.box-discount-order').find('input[name="max_discount"]').prop('disabled', true);
                $('.box-discount-ship').find('input[name="max_discount"]').prop('disabled', false);
                $('.iboxApplyFor').removeClass('hidden');
                HT.addClassHidden('box-discount-order');
            }else {
                HT.addClassHidden('box-discount-order');
                HT.addClassHidden('box-discount-ship');
                HT.addClassHidden('iboxApplyFor');
            }
        })
    }

    HT.addClassHidden = (nameClass) => {
        if(!$('.'+nameClass).hasClass('hidden')) {
            $('.'+nameClass).addClass('hidden');
            // $('.'+nameClass).find('input').prop('disabled', true);
        }
    }

    //Chọn nhóm khách hàng
    HT.chooseCustomerCatalogue = () => {
        $(document).on('input', '.applyCustomer', function () {
            if($(this).val() !== 'all') {
                $('.boxChooseCustomerCatalogue').removeClass('hidden');
            }else{
                $('.boxChooseCustomerCatalogue').addClass('hidden');
                $('.boxChooseCustomerCatalogue').find('select').val(null).trigger('change');
            }
        })
    }

    //Chọn điều kiện áp dụng
    HT.chooseConditionDiscount = () => {
        $(document).on('input', '.applyCondition', function() {
            let _this = $(this);
            if(_this.val() == 'minimumValue') {
                $('.boxMinimumValue').removeClass('hidden');
                HT.addClassHidden('boxMinimumQuantity');
            }else if(_this.val() == 'minimumQuantity') {
                $('.boxMinimumQuantity').removeClass('hidden');
                HT.addClassHidden('boxMinimumValue');
            }else{
                HT.addClassHidden('boxMinimumValue');
                HT.addClassHidden('boxMinimumQuantity');
            }
        })
    }

    //Chọn giới hạn sử dụng
    HT.setupUsageLimit = () => {
        $(document).on('input', '#usage-limit', function() {
            if($('#usage-limit').prop('checked')) {
                $('.box-usage-limit').removeClass('hidden');
            }else{
                HT.addClassHidden('box-usage-limit');
            }
        })
    }

    $(document).ready(function () {
        HT.clickTypeDiscountPayment();
        HT.chooseModel();
        HT.clickInputSearchModel();
        HT.changeInputSearchModelModal();
        HT.clickItemModelModal();    
        HT.applyNewListModelModal();
        HT.deleteItemModelSearch();
        HT.setupStatusNeverEndDate();
        HT.clickNeverEndDate();
        HT.setupListModelForEdit();
        HT.setupStartDate();
        HT.setupEndDate();
        HT.changeDiscountCouponMethod();
        HT.chooseCustomerCatalogue();
        HT.chooseConditionDiscount();
        HT.setupUsageLimit();
    });
})(jQuery);