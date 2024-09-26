(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.changeSelectModel = () => {
        $(document).on('change', '.selectModel', function () {
            $('.search-model-render').html('');
            $('.list-search').html('<p>Danh sách đã chọn</p>');
            $('.search-model').find('input[name="keyword_search"]').val('');
            $('input[name="model_id"]').val('');
        })
    }

    HT.searchDataModel = () => {
        $(document).on('input', '.searchItem', function () {
            let _this = $(this);
            let keyword = _this.val();
            let model = $('.selectModel').val();
            console.log(model);
            if(keyword.length >= 2) {
                let option = {
                    'keyword': keyword,
                    'model': model,
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
                $('input[name="model_id"]').val(JSON.stringify(arrId));
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
                        html += '<input type="hidden" name="model_name[]" value="'+ name +'">'
                        html += '<input type="hidden" name="model_image[]" value="'+ image +'">'
                        html += '<i class="fa fa-times icon-removed"></i>'
                    html += '</div>'
                $('.list-search').append(html);
                let arrId = HT.getArrModelId();
                $('input[name="model_id"]').val(JSON.stringify(arrId));
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
            $('input[name="model_id"]').val(JSON.stringify(arrId));
        })
    }

    $(document).ready(function () {
        HT.changeSelectModel();
        HT.searchDataModel();
        HT.chooseItemSearch();
        HT.deleteItemSearch();
    });
})(jQuery);