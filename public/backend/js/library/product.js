(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');

    //CONFIGURATION
    HT.addConfigurationItem = () => {
        $(document).on('click', '.addConfigItem', function () {
            let _this = $(this);
            let parent = _this.closest('.configuration');
            let html = '<div class="configuration-item flex">'
                html += '<div style="width: 30%" class="form-group">'
                    html += '<input type="text" value="" placeholder="Nhập tên..." name="name_configuration[]" class="form-control">'
                html += '</div>'
                html += '<div style="width: 60%" class="form-group">'
                    html += '<input type="text" value="" placeholder="Nhập thông số..." name="value_configuration[]" class="form-control">'
                html += '</div>'
                html += '<span style="width: 10%" class="btn btn-danger config-delete"><i class="fa fa-times"></i></span>'
            html += '</div>'
            parent.append(html);
        })
    }

    HT.deleteConfigItem = () => {
        $(document).on('click', '.config-delete', function () {
            let _this = $(this);
            _this.closest('.configuration-item').remove();
        })
    }

    //ANSWERS AND QUESTIONS
    HT.addQuestionItem = () => {
        $(document).on('click', '.addQuestion', function() {
            let _this = $(this);
            let parent = _this.closest('.listQuestion');
            let html = '<div class="question-item">'
                html += '<div class="form-group">'
                    html += '<input type="text" value="" placeholder="Nhập câu hỏi..." name="questions[]" class="form-control">'
                html += '</div>'
                html += '<div class="form-group">'
                    html += '<input type="text" value="" placeholder="Nhập câu trả lời..." name="answers[]" class="form-control">'
                html += '</div>'
                html += '<span class="deleteQuestion btn btn-danger"><i class="fa fa-times"></i></span>'
            html += '</div>'
            parent.append(html);

        })
    }

    HT.deleteQuestionItem = () => {
        $(document).on('click', '.deleteQuestion', function () {
            let _this = $(this);
            _this.closest('.question-item').remove();
        })
    }

    //POST RELATIONSHIP
    HT.searchDataModel = () => {
        $(document).on('input', '.searchItem', function () {
            let _this = $(this);
            let keyword = _this.val();
            if(keyword.length >= 2) {
                let option = {
                    'keyword': keyword,
                    'model': 'post',
                    '_token': _token
                }
                HT.getData(option);
            }else{
                $('.search-model-render').html('');
            }
        })
    }

    HT.getData = (option) => {
        $.ajax({
            url: '/admin/ajax/search/model',
            method: 'POST',
            data: option,
            dataType: 'json',
            success: function (response) {
                let arrId = HT.getArrModelId();
                let data = response.data;
                let html = '';
                $.each(data ,function (index, elem) {
                    let inputCheck = $.inArray(elem.id, arrId) !== -1 ? '<i class="fa fa-check icon-checked"></i>' : '';
                    html += '<div class="search-model-render-item flex-space-between" data-image="'+ elem.image +'" data-id="'+ elem.id +'" data-name="'+ elem.name +'">'
                        html += '<span>'+ elem.name +'</span>' + inputCheck
                    html += '</div>'
                })
                $('.search-model-render').html(html);
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    HT.getArrModelId = () => {
        let arrId = [];
        $('.list-search').find('div[data-id]').each(function () {
            arrId.push(parseInt($(this).attr('data-id')));
        })
        return arrId;
    }

    HT.chooseItemSearch = () => {
        $(document).on('click', '.search-model-render-item', function () {
            let _this = $(this);
            let isCheck = _this.find('i').length;
            let id = _this.attr('data-id');
            if(isCheck) {
                _this.find('i').remove();
                $('.list-search').find('div[data-id="' + id + '"]').remove();
                let arrId = HT.getArrModelId();
                $('input[name="post_id"]').val(JSON.stringify(arrId));
            }else{
                _this.append('<i class="fa fa-check icon-checked"></i>');
                let image = _this.attr('data-image') !== null ? _this.attr('data-image') : "/backend/img/empty-image.png";
                let name = _this.attr('data-name')
                let html = '<div class="list-search-item flex-space-between" data-id="'+ id +'">'
                        html += '<div class="list-search-item-l flex">'
                            html += '<div class="box-image">'
                                html += '<img src="'+ image +'" alt="">'
                            html += '</div>'
                            html += '<span>'+ name +'</span>'
                        html += '</div>'
                        html += '<input type="hidden" name="post_name[]" value="'+ name +'">'
                        html += '<input type="hidden" name="post_image[]" value="'+ image +'">'
                        html += '<i class="fa fa-times icon-removed"></i>'
                    html += '</div>'
                $('.list-search').append(html);
                let arrId = HT.getArrModelId();
                $('input[name="post_id"]').val(JSON.stringify(arrId));
            }
        })
    }

    HT.deleteItemSearch = () => {
        $(document).on('click', '.icon-removed', function() {
            let _this = $(this);
            let parent = _this.closest('.list-search-item');
            let id = parent.attr('data-id');
            $('.search-model-render').find('div[data-id="'+ id + '"]').find('i').remove();
            parent.remove();
            let arrId = HT.getArrModelId();
            $('input[name="post_id"]').val(JSON.stringify(arrId));
        })
    }

    //TAX
    HT.chooseStatusTax = () => {
        $(document).on('click', '#status-tax', function() {
            let _this = $(this);
            if(_this.prop('checked')) {
                $('.selectStatusTax').removeClass('hidden');
            }else{
                $('.selectStatusTax select').val(0).select2();
                $('.selectStatusTax').addClass('hidden');
                if(!$('#applyTax').hasClass('hidden')) {
                    $('#applyTax').addClass('hidden');
                }
            }
        })
    }

    HT.checkEmptyInput = () => {
        $('.checkEmpty').each(function(index, element) {
            let _this = $(element);
            let value = _this.val();
            let parent = _this.parent('.form-group');
            if(value == "" || value == "0") {
                if(!_this.hasClass('input-error')) {
                    _this.addClass('input-error');
                    parent.append('<span class="alert-error">Trường này không được để trống!</span>')
                }
            }else {
                _this.removeClass('input-error');
                parent.find('.alert-error').remove();
            }
        });
    }

    HT.changeInputTax = () => {
        $(document).on('input', '.checkEmpty', function () {
            let _this = $(this);
            if(_this.val() !== "" || _this.val() !== "0") {
                _this.removeClass('input-error');
                _this.closest('.form-group').find('.alert-error').remove();
            }
        })
    }

    HT.addTax = () => {
        $(document).on('click', '.submitTax', function() {
            HT.checkEmptyInput();
            if($('.contentModal').find('.alert-error').length == 0) {
                let option = {
                    'name': $('.nameTax').val(),
                    'code': $('.codeTax').val(),
                    'value': $('.valueTax').val(),
                    '_token': _token,
                    'model': 'tax'
                }
                $('.nameTax').val('');
                $('.codeTax').val('');
                $('.valueTax').val(0);
                $.ajax({
                    url: '/admin/ajax/create/model',
                    method: 'POST',
                    data: option,
                    dataType: 'json',
                    success: function (response) {
                        let html = `<option value="${response.new.code}">${response.new.name}</option>`;
                        // let taxes = response.data;
                        // $.each(taxes, function(index, item) {
                        //     html += `<option ${item.code == response.new.code ? 'selected' : ''} value="${item.code}">${item.name}</option>`
                        // })
                        $('.inputTax').append(html).select2();
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }
        })
    }

    HT.changeStatusTax = () => {
        $(document).on('change', 'select[name="tax_status"]', function () {
            let _this = $(this);
            if(_this.val() == "1") {
                $('#applyTax').removeClass('hidden');
            }else {
                $('#applyTax').addClass('hidden');
            }
        })
    }

    // PRODUCT MULTIPLE VARIANT

    HT.setTypeProduct = () => {
        if($('select[name="type_product"]').val() == "0") {
            if($('.singleVariant').hasClass('hidden')) {
                $('.singleVariant').removeClass('hidden');
            }
            if(!$('.multipleVariant').hasClass('hidden')) {
                $('.multipleVariant').addClass('hidden');
            }
        }else{
            if(!$('.singleVariant').hasClass('hidden')) {
                $('.singleVariant').addClass('hidden');
            }
            if($('.multipleVariant').hasClass('hidden')) {
                $('.multipleVariant').removeClass('hidden');
            }
        }
    }

    HT.changeTypeProduct = () => {
        $(document).on('change', 'select[name="type_product"]', function () {
            let _this = $(this);
            if(_this.val() == "0") {
                $('.multipleVariant').addClass('hidden');
                $('.singleVariant').removeClass('hidden');
            }else{
                if($('input[name="code"]').val() == "") {
                    $('select[name="type_product"]').val("0");
                    $('select[name="type_product"]').select2();
                    $('input[name="code"]').focus();
                    alert('Bạn cần nhập Mã sản phẩm trước khi khởi tạo các phiên bản!');
                    return;
                }
                $('.multipleVariant').removeClass('hidden');
                $('.singleVariant').addClass('hidden');
            }
        })
    }

    HT.addAttribute = () => {
        $(document).on('click', '.addAttribute', function () {
            $('.listVariantRender').html('');
            let selects = $('.attributeCatalogueItem');
            let arrId = HT.getArrAttributeCatalogueSelected(selects);
            let data = JSON.parse($('.attributecatalogues').val());
            let html = '<div class="product-variant-item flex" data-order="'+ selects.length +'">'
                        html += '<div class="variant-item-1 form-group mr25">'
                            html += '<select style="width:100%" name="attribute_catalogue_id[]" class="ml setupSelect2 attributeCatalogueItem">'
                                html += '<option value="0">[Chọn nhóm thuộc tính]</option>'
                                $.each(data, function(index, item) {
                                    html += `<option ${arrId.includes(item.id) ? 'disabled' : ''} value="${item.id}">${item.name}</option>`
                                })
                            html += '</select>'
                        html += '</div>'
                        html += '<div class="variant-item-2 form-group mr10">'
                            html += '<select disabled style="width:100%" multiple="multiple" name="attribute_id['+ selects.length +'][]" class="ml setupSelect2 attributeItem">'
                            html += '</select>'
                            html += '<input type="hidden" name="data_attribute['+ selects.length +']" value="">'
                        html += '</div>'
                        html += '<div class="variant-item-3">'
                            html += '<button style="height: 35px" class="deleteAttributeCatalogue btn btn-danger w100"><i class="fa fa-trash"></i></button>'
                        html += '</div>'
                    html += '</div>'
            $('.product-variant-middle').append(html);
            $('.setupSelect2').select2();
        })
    }

    HT.changeAttributeCatalogue = () => {
        $(document).on('change', '.attributeCatalogueItem', function () {
            let _this = $(this);
            let targetInput = _this.closest('.product-variant-item').find('input');
            let target = _this.closest('.product-variant-item').find('.attributeItem');
            if(_this.val() == "0") {
                target.prop('disabled', true);
                target.html('');
                target.select2();
            }else{
                target.prop('disabled', false);
            }
            let option = {
                'model': 'attributeCatalogue',
                'relation': 'attributes',
                'id': _this.val(),
                '_token': _token
            }
            $.ajax({
                url: '/admin/ajax/get/relationById',
                method: 'POST',
                data: option,
                dataType: 'json',
                success: function (response) {
                    let html = '';
                    let data = response.data;
                    $.each(data, function (index, item) {
                        html += '<option value="'+ item.id +'">'+ item.name +'</option>'
                    })
                    target.html(html);
                    target.select2();
                    targetInput.val(JSON.stringify(data));
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                }
            });
            HT.refreshAttributeCatalogue();
        })
    }

    HT.deleteAttributeCatalogue = () => {
        $(document).on('click', '.deleteAttributeCatalogue', function () {
            let _this = $(this);
            _this.closest('.product-variant-item').remove();
            HT.refreshAttributeCatalogue();
            HT.renderTableVariant();
        })
    }

    HT.refreshAttributeCatalogue = () => {
        let selects = $('.attributeCatalogueItem');
        let arrId = HT.getArrAttributeCatalogueSelected(selects);
        $('.attributeCatalogueItem option').removeAttr('disabled');
        $('.attributeCatalogueItem').each(function(index, elem) {
            let options = $(elem).find('option');
            options.each(function(key, option) {
                if($(option).is(':selected')) {
                    return true;
                }
               if(arrId.includes(parseInt($(option).val()))) {
                $(option).attr('disabled', 'disabled');
               } 
            })
        })
        $('.attributeCatalogueItem').select2();
    }

    HT.getArrAttributeCatalogueSelected = (selects) => {
        let arrId = [];
        selects.each(function(index, elem) {
            let id = $(elem).val();
            if(id !== '0') {
                arrId.push(parseInt(id));
            }
        })
        return arrId;
    }

    HT.changeAttributeVariant = () => {
        $(document).on('change', '.attributeItem', function () {
            HT.renderTableVariant();
        })
    }

    HT.changeAttributeCatalogueVariant = () => {
        $(document).on('change', '.attributeCatalogueItem', function () {
            $(this).closest('.product-variant-item').find('.attributeItem').val([]);
            HT.renderTableVariant();
        })
    }

    HT.renderTableVariant = () => {
        let isCheckAttributeCatalogue = HT.checkAttributeCatalogue();
        let isCheckAttribute = HT.checkAttribute();
        if(isCheckAttributeCatalogue && isCheckAttribute) {
            HT.renderTableVariantProduct();
            HT.setAttributeCatalogueVariantAlbum();
        }else{
            $('.listVariantRender').html('');
            $('.album-variant tbody').html('');
        }
    }

    HT.setAttributeCatalogueVariantAlbum = () => {
        let attributeCatalogues = [];
        $('.attributeCatalogueItem').each(function(index, elem) {
            attributeCatalogues.push(parseInt($(elem).val()));
        })
        let data = JSON.parse($('.attributecatalogues').val());
        let attributeCatalogueAlbumId = $('select.attributeCatalogueAlbum').val();
        let html = '<option value="0">[Chọn nhóm thuộc tính]</option>';
        let isSelected = false;
        $.each(data, function (index, item) {
            if(attributeCatalogues.includes(item.id)) {
                if(attributeCatalogueAlbumId == item.id) {
                    isSelected = true;
                }
                html += '<option '+ (attributeCatalogueAlbumId == item.id ? 'selected' : '') +'  value="'+ item.id +'">'+ item.name +'</option>'
            }
        })
        $('select.attributeCatalogueAlbum').html(html);
        if(!isSelected) {
            $('select.attributeCatalogueAlbum').val(0).select2();
        }
        if($('select.attributeCatalogueAlbum').val() !== "0") {
            HT.renderTableVariantAlbum($('select.attributeCatalogueAlbum').val());
        }
        if($('select.attributeCatalogueAlbum').val() == '0') {
            $('.album-variant tbody').html('');
        }

    }

    HT.changeAttributeCatalogueAlbum = () => {
        $(document).on('change', 'select.attributeCatalogueAlbum', function () {
            let _this = $(this);
            let id = _this.val();
            HT.renderTableVariantAlbum(id);
        })
    }

    HT.renderTableVariantAlbum = (id) => {
        let attributeIds = [];
            let attibuteNames;
            $('.attributeCatalogueItem').each(function(index, elem) {
                if(id == $(elem).val()) {
                    let selectAttribute = $(elem).closest('.product-variant-item').find('.attributeItem');
                    attributeIds = selectAttribute.val();
                    attibuteNames = selectAttribute.find('option:selected').map(function() {
                        return $(this).text();
                    }).get();
                }
            })
            let html = HT.renderVariantAlbumHtml(attributeIds, attibuteNames);
            $('.album-variant tbody').html(html);
            $('.sortable').sortable();
            $('.sortable').disableSelection();
    }

    HT.renderVariantAlbumHtml = (attributeIds, attibuteNames) => {
        let html = '';
        for(let i = 0; i < attibuteNames.length; i++) {
            html += `<div class="variantItemAlbum album" data-attributeId="${attributeIds[i]}">
                    <tr class="variantAlbumInfor">
                        <td>
                            <span class="actionAlbum"><i class="fa fa-plus"></i></span>
                            <img data-type="Images" class="image-variant upload-image" src="/backend/img/empty-image.png"
                                alt="">
                                <input type="hidden" name="album_variant[image][]"
                                    value="">
                        </td>
                        <td>${attibuteNames[i]}</td>
                        <input type="hidden" name="album_variant[name][]" value="${attibuteNames[i]}">
                        
                    </tr>
                    <tr class="">
                        <td class="variantAlbumDetail hidden" colspan="3">
                            <input type="hidden" name="album_variant[attribute_id][]" value="${attributeIds[i]}">
                            <div class="listAlbum hidden">
                                <div class="sortable lightBoxGallery album-style">
                                    
                                </div>
                            </div>
                            <div class="emptyAlbum" data-attributeId="${attributeIds[i]}">
                                <div class="form-group">
                                    <span class="triggerImage empty-image hidden">click</span>
                                    <div class="empty-image" data-variant="1">
                                        <img data-type="Images" class="image-style "
                                            src="/userfiles/image/general/empty-image.png"
                                            alt="">
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </div>`
        }
        return html;
    }

    HT.renderTableVariantProduct = () => {
        let attributeNames = [];
        let attributeIds = [];
        $('.attributeItem').each(function (index, elem) {
            let temp = $(elem).find('option:selected').map(function() {
                return $(this).text();
            }).get();
            attributeIds.push($(elem).val());
            attributeNames.push(temp);
        })
        attributeNames = attributeNames.reduce(
            (a, b) => a.flatMap(d => b.map(e => `${d}-${e}`))
        ).map(item => `-${item}`);
        attributeIds = attributeIds.reduce(
            (a, b) => a.flatMap(d => b.map(e => `${d}-${e}`))
        ).map(item => `-${item}`);
        let html = HT.renderVariantHtml(attributeNames, attributeIds);
        $('.listVariantRender').html(html);
        $('.massVariant').select2();
        HT.setupInputMoney();
        HT.initValueInputNumber();
    }

    HT.renderVariantHtml = (arrName, arrId) => {
        let html = '';
        let price = $('input[name="price"]').val();
        let cost = $('input[name="cost"]').val();
        let code = $('input[name="code"]').val();
        let weight = $('input[name="weight"]').val();
        let mass = $('select[name="mass"]').val();
        let warehouses = JSON.parse($('.warehouses').val());
        for(let i = 0; i < arrId.length; i++) {
            html += `<div class="variantItem">
                <tr class="variant-item-render">
                    <td><span class="actionAlbum" style="display:inline-block;margin-right:10px"><i class="fa fa-plus"></i></span>${arrName[i]}</td>
                    <input type="hidden" name="variant[name][]" value="${arrName[i]}">
                    <input class="codeVariant" type="hidden" name="variant[code][]" value="${arrId[i]}">
                    <td class="infoSku">${code+arrId[i]}</td>
                    <td class="infoPrice">${price}</td>
                    <td class="infoCost">${cost}</td>
                </tr>
                <tr>
                <td colspan="4" class="variant-item-detail hidden">
                    <div class="configuration-variant-t flex-space-between">
                        <h5>CẬP NHẬT THÔNG TIN PHIÊN BẢN</h5>
                    </div>
                    <div class="flex-space-between">
                        <div class="form-group w46">
                            <label class="fw550 fs14" for="">SKU</label>
                            <input readonly data-target="infoSku" type="text" value="${code+arrId[i]}"
                                placeholder="" name="variant[sku][]" class="changeInfo form-control br20">
                        </div>
                        <div class="form-group w46">
                            <label class="fw550 fs14" for="">Barcode</label>
                            <input type="text"
                                value=""
                                placeholder="Nhập tay mã vạch hoặc dùng máy quét mã vạch..." name="variant[barcode][]"
                                class="form-control br20">
                        </div>
                    </div>
                    <div class="flex-space-between">
                        <div class="form-group w32">
                            <label class="fw550 fs14" for="">Giá bán lẻ <span class="underline">(đ)</span></label>
                            <input data-target="infoPrice" type="text" value="${price}" placeholder="" name="variant[price][]"
                                class="changeInfo inputMoney text-right form-control br20">
                        </div>
                        <div class="form-group w32">
                            <label class="fw550 fs14" for="">Giá nhập <span class="underline">(đ)</span></label>
                            <input data-target="infoCost" type="text" value="${cost}" placeholder="" name="variant[cost][]"
                                class="changeInfo inputMoney text-right form-control br20">
                        </div>
                        <div class="w32 variant-weight">
                            <label class="fw550 fs14" for="">Khối lượng</label>
                            <div class="flex">
                                <input style="border-radius: 20px 0 0 20px" type="text"
                                    value="${weight}" placeholder="" name="variant[weight][]"
                                    class="form-control mr10 text-right inputMoney">
                                <select style="width:30%" name="variant[mass][]" class="ml setupSelect2 massVariant">
                                    <option ${mass == "0" ? 'selected' : ''} value="0">g</option>
                                    <option ${mass == "1" ? 'selected' : ''}  value="1">kg</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="config-warehouse-variant">
                        <div class="config-warehouse-variant-t flex-space-between fw550 fs14">
                            <span class="w15"></span>
                            <span class="w36">Tồn ban đầu</span>
                            <span class="w36 mr10">Giá vốn <span class="underline">(đ)</span></span>
                        </div>`
                        $.each(warehouses, function(index, item) {
                            html += `<div class="config-warehouse-variant-item flex-space-between">
                                <span class=" fw550 fs14 w15">${item.name}</span>
                                <div class="form-group w36">
                                    <input type="text" value="" placeholder=""
                                        name="variant[quantity][${i}][${item.id}]" class="form-control br5 text-right inputMoney">
                                </div>
                                <div class="form-group w36 mr10">
                                    <input type="text" value="" placeholder=""
                                        name="variant[cost_price][${i}][${item.id}]" class="form-control br5 text-right inputMoney">
                                </div>
                            </div>`
                        })
                    html += `</div>
                </td>
                </tr>
            </div>`
            }
        return html;
    }

    HT.clickItemVariantRender = () => {
        $(document).on('click', '.variant-item-render', function () {
            let _this = $(this);
            let target = _this.next('tr').find('.variant-item-detail');
            if(_this.next('tr').find('.variant-item-detail').hasClass('hidden')) {
                target.removeClass('hidden');
                $(this).find('i').attr('class', 'fa fa-minus');
            }else{
                target.addClass('hidden');
                $(this).find('i').attr('class', 'fa fa-plus');
            }
        })
    }

    HT.clickItemVariantAlbum = () => {
        $(document).on('click', '.actionAlbum', function () {
            let _this = $(this).closest('.variantAlbumInfor');
            let target = _this.next('tr').find('.variantAlbumDetail');
            if(_this.next('tr').find('.variantAlbumDetail').hasClass('hidden')) {
                target.removeClass('hidden');
                $(this).find('i').attr('class', 'fa fa-minus');
            }else{
                target.addClass('hidden');
                $(this).find('i').attr('class', 'fa fa-plus');
            }
        })
    }

    HT.checkAttribute = () => {
        let isCheck = true;
        $('.attributeItem').each(function(index, elem) {
            if($(elem).val().length == 0) {
                isCheck = false;
            }
        })
        return isCheck;
    }

    HT.checkAttributeCatalogue = () => {
        let isCheck = true;
        $('.attributeCatalogueItem').each(function(index, elem) {
            if($(elem).val() == "0") {
                isCheck = false;
            }
        })
        return isCheck;
    }

    HT.changeInputVariant = () => {
        $(document).on('input', '.changeInfo', function() {
            let _this = $(this);
            let classTarget = _this.attr('data-target');
            let target = _this.closest('tr').prev('tr').find('.'+classTarget);
            target.html(_this.val());
        })
    }

    HT.changeInputCodeProduct = () => {
        $(document).on('input', 'input[name="code"]', function () {
            let _this = $(this);
            $.each($('.variantItem'), function(index, item) {
                let elem = $(item);
                let code = elem.find('.codeVariant').val();
                console.log(elem.find('.variant-item-render'));
                let sku = _this.val() + code;
                elem.find('.infoSku').html(sku);
                elem.find('input[name="variant[sku][]"]').val(sku);
            })

        })
    }

    
    HT.setupInputMoney = () => {
        $('.inputMoney').on('input', function() {
            // Loại bỏ các ký tự không phải số
            var value = $(this).val().replace(/\D/g, '');
            // Định dạng số với dấu phân cách hàng ngàn
            value = Number(value).toLocaleString('en').replace(/,/g, '.');
            // Cập nhật giá trị của thẻ input
            $(this).val(value);
        });
    }

    HT.initValueInputNumber = () => {
        if($('.inputMoney').val() == "") {
            $('.inputMoney').val(0);
        }
    }

    $(document).ready(function () {
        HT.addConfigurationItem();
        HT.deleteConfigItem();
        HT.addQuestionItem();
        HT.deleteQuestionItem();
        HT.searchDataModel();
        HT.chooseItemSearch();
        HT.deleteItemSearch();
        HT.chooseStatusTax();
        HT.addTax();
        HT.changeInputTax();
        HT.changeStatusTax();
        HT.setTypeProduct();
        HT.changeTypeProduct();
        HT.addAttribute();
        HT.changeAttributeCatalogue();
        HT.deleteAttributeCatalogue();
        HT.changeAttributeVariant();
        HT.changeAttributeCatalogueVariant();
        HT.clickItemVariantRender();
        HT.clickItemVariantAlbum();
        HT.changeAttributeCatalogueAlbum();
        HT.changeInputVariant();
        HT.changeInputCodeProduct();
    });
})(jQuery);