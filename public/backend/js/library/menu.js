(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.changeLinkMenuItem = () => {
        $(document).on('click', '.add-link', function () {
            HT.clearModal();
            $('.modal-title').text('Thêm liên kết');
            $('#addLink').addClass('createLink');
            let _this = $('.changeLinkModal').find('option:selected');
            $('.inputCanonical').val(_this.attr('data-canonical'));
            $(document).on('change', '.changeLinkModal', function () {
                $('.inputCanonical').val('');
                _this = $(this).find('option:selected');
                if(_this.attr('data-htmlTarget') == 'select') {
                    let option = {
                        'model': _this.data('model'),
                        '_token': _token
                    }
                    let html = HT.renderLinkHtmlSelect(_this.attr('data-nameTarget'));
                    $('.html-target').html(html);
                    HT.getDataModel($('.changeLinkTargetSelect'), option);
                }else if(_this.attr('data-htmlTarget') == 'input') {
                    let html = HT.renderLinkHtmlInput();
                    $('.html-target').html(html);
                }else{
                    $('.html-target').html('');
                    $('.inputCanonical').val(_this.attr('data-canonical'));
                }
            })
        })
    }

    HT.renderLinkHtmlSelect = (nameTarget = "") => {
        let htmlOption = nameTarget !== "" ? `<option value="0">${nameTarget}</option>` : '';
        let html = `
            <select class="changeLinkTargetSelect ml setupSelect2 w50">
                ${htmlOption}
            </select>`
        return html;
    }

    HT.renderLinkHtmlInput = (value = "") => {
        let html = `
            <div class="form-group">
                <input style="height:39px;border-radius:5px" type="text" placeholder=""
                    value="${value}" class="linkTargetInput form-control">
            </div>`
        return html;
    }

    HT.getDataModel = (targetSelect, option, selected = "") => {
        $.ajax({
            url: '/admin/ajax/data/model',
            method: 'POST', 
            dataType: 'json',
            data: option,
            success: function (response) {
                let data = response.data; 
                let html = '';
                $.each(data, function (index, item) {
                    html += `<option ${item.canonical == selected ? 'selected' : ''} value=${item.canonical}>${item.name}</option>`
                });
                targetSelect.append(html).select2();
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    HT.changeLinkTargetSelect = () => {
        $(document).on('change', '.changeLinkTargetSelect', function () {
            $('.inputCanonical').val($(this).val());
            $('.nameLinkModal ').val($(this).find('option:selected').text());
        })
    }

    HT.changeLinkTargetInput = () => {
        $(document).on('input', '.linkTargetInput', function () {
            $('.inputCanonical').val($(this).val());
        })
    }

    HT.completeModalLink = () => {
        $(document).on('click', '.addLinkComplete', function() {
            let _this = $(this);
            let modal = _this.closest('#addLink');
            if(modal.find('.nameLinkModal').val() == '') {
                HT.renderHtmlErrorModal('Tiêu đề không được để trống');
                modal.find('.nameLinkModal').focus();
                return;
            }
            if(modal.find('.changeLinkTargetSelect').val() == "0") {
                let text = modal.find('.changeLinkTargetSelect').find('option:first').text();
                HT.renderHtmlErrorModal(text);
                return;
            }
            if(modal.find('.linkTargetInput').val() == '') {
                HT.renderHtmlErrorModal('Đường dẫn không hợp lệ');
                return;
            }
            let tag = modal.find('.changeLinkModal').find('option:selected');
            if(modal.hasClass('createLink')) {
                let option = {
                    name: modal.find('.nameLinkModal').val(),
                    image: modal.find('.imageLinkModal').val(),
                    canonical: modal.find('.inputCanonical').val(),
                    htmlTarget: tag.attr('data-htmlTarget'),
                    model: tag.data('model'),
                    keyword: tag.attr('value')
                }
                let html = HT.renderItemLinkHtml(option);
                $('.parentList').append(html);
                $('.empty-link').addClass('hidden');
                modal.removeClass('createLink');
                modal.modal('hide');
                HT.clearModal();
            }else{
                let dataId = modal.find('.targetId').val();
                let targetItem = $('.dd-item[data-id="'+ dataId +'"]');
                let name = modal.find('.nameLinkModal').val();
                let image = modal.find('.imageLinkModal').val();
                targetItem.attr('data-name', name);
                targetItem.attr('data-canonical', modal.find('.inputCanonical').val());
                targetItem.attr('data-image', image);
                targetItem.find('.name-item').text(name);
                let targetItemData = targetItem.find('.dataItem');
                targetItemData.attr('data-htmlTarget', tag.attr('data-htmlTarget'));
                targetItemData.attr('data-model', tag.data('model'));
                targetItemData.attr('data-keyword', tag.data('value'));
                targetItemData.val(modal.find('.inputCanonical').val());
                modal.modal('hide');
                HT.clearModal();
            }
        })
    }

    HT.deleteLinkItem = () => {
        $(document).on('click', '.deleteItemLink', function() {
            $(this).closest('.dd-item').remove();
            if($('.dd-item').length == 0) {
                $('.empty-link').removeClass('hidden');
            }
        });
    }

    HT.renderHtmlErrorModal = (text) => {
        let html = `<div class="alert-error-box">
            <i class="fa fa-exclamation-circle"></i>
            <span>${text} </span>
        </div>`;
        $('.alert-error').html(html);
    }

    HT.clearModal = () => {
        let modal = $('#addLink');
        modal.find('.nameLinkModal').val('');
        modal.find('.imageLinkModal').val('');
        modal.find('.inputCanonical').val('');
        modal.find('.targetId').val('');
        modal.find('.modalItemImageLink').attr('src', '/backend/img/empty-image.png');
        modal.find('.changeLinkModal option:first').prop('selected', true);
        modal.find('.alert-error').html('');
        modal.find('.changeLinkModal').select2().trigger('change');
    }

    HT.renderItemLinkHtml = (option) => {
        let count = $('.dd-item').length;
        let arrId = [];
        if(count) {
            $('.dd-item').each(function(index, elem) {
                arrId.push(parseInt($(elem).data('id')));
            })
        }
        let id;
        do {
            id = Math.floor(Math.random() * 1000000);
        } while (arrId.includes(id));
        let detail = {
            'model': option.model,
            'keyword': option.keyword,
            'html_target': option.htmlTarget
        };
        let html = `
        <li class="dd-item" data-id="${id}" data-name="${option.name}" data-canonical="${option.canonical}" data-image="${option.image}" data-htmlTarget="${option.htmlTarget}" data-model="${option.model}" data-keyword="${option.keyword}">
            <span class="dd-handle">
                <span class="label label-info"><i class="fa fa-arrows"></i></span><span class="name-item">${option.name} </span>
            </span>
            <div class="management-submenu">
                <span class="editItemLink btn-warning style-button"><i class="fa fa-edit"></i></span>
                <span class="deleteItemLink btn-danger style-button"><i class="fa fa-trash"></i></span>
                <input type="hidden" value="${option.canonical}" class="dataItem" data-htmlTarget="${option.htmlTarget}" data-model="${option.model}" data-keyword="${option.keyword}" >
            </div>
        </li>`
        return html;
    }

    HT.editItemLink = () => {
        $(document).on('click', '.editItemLink', function () {
            let _this = $(this);
            let item = _this.closest('.dd-item');
            let inputData = item.find('.dataItem');
            HT.clearModal();
            $('.modal-title').text('Sửa liên kết');
            let modal = $('#addLink');
            modal.modal('show');
            modal.find('.nameLinkModal').val(item.data('name'));
            modal.find('inputCanonical').val(item.data('canonical'));
            modal.find('.targetId').val(item.data('id'));
            let isImage = item.attr('data-image') !== '';
            modal.find('img').attr('src', isImage ? item.attr('data-image') : '/backend/img/empty-image.png');
            modal.find('imageLinkModal').val(isImage ? item.attr('data-image') : '');
            modal.find('.changeLinkModal').val(inputData.data('keyword')).select2();
            if(inputData.attr('data-htmlTarget') == 'select') {
                let option = {
                    'model': inputData.data('model'),
                    '_token': _token
                }
                let html = HT.renderLinkHtmlSelect();
                $('.html-target').html(html);
                HT.getDataModel($('.changeLinkTargetSelect'), option, inputData.val());
            }else if(inputData.attr('data-htmlTarget') == 'input') {
                let html = HT.renderLinkHtmlInput(inputData.val());
                $('.html-target').html(html);
            }else {
                $('.html-target').html('');
                $('.inputCanonical').val(_this.attr('data-canonical'));
            }
            $('.inputCanonical').val(inputData.val());

        })  
    }

    HT.changeNameMenu = () => {
        $(document).on('input', 'input[name="name"]', function () {
            $('input[name="keyword"]').val(HT.createSlug($(this).val()));
        })
    }

    HT.submitMenu = () => {
        $(document).on('submit', '.formSubmitMenu', function (e) {
            e.preventDefault();
            var serializedData = $('#nestable2').nestable('serialize');
            console.log(serializedData);
            $('input[name="links"]').val(JSON.stringify(serializedData));
            this.submit();
        })
    }

    HT.createSlug = (text) => {
        return text.toString().toLowerCase()
        .replace(/[àáạảãăắằặẳẵâấầậẩẫ]/g, 'a')
        .replace(/[èéẹẻẽêếềệểễ]/g, 'e')
        .replace(/[ìíịỉĩ]/g, 'i')
        .replace(/[òóọỏõôốồộổỗơớờợởỡ]/g, 'o')
        .replace(/[ùúụủũưứừựửữ]/g, 'u')
        .replace(/[ỳýỵỷỹ]/g, 'y')
        .replace(/đ/g, 'd')
        .replace(/[^a-z0-9]/g, '-')  // Thay thế các ký tự không phải chữ cái hoặc số bằng dấu gạch ngang
        .replace(/-{2,}/g, '-')     // Loại bỏ các dấu gạch ngang kéo dài
        .replace(/^-+/, '')         // Loại bỏ các dấu gạch ngang ở đầu chuỗi
        .replace(/-+$/, ''); 
    } 
    
    $(document).ready(function () {
        HT.changeLinkMenuItem();
        HT.changeLinkTargetSelect();
        HT.completeModalLink();
        HT.changeLinkTargetInput();
        HT.changeNameMenu();
        HT.submitMenu();
        HT.editItemLink();
        HT.deleteLinkItem();
    });
})(jQuery);