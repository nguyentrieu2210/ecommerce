(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.setSlug = () => {
        $(document).on('keyup', '.canonicalOriginal', function () {
            let _this = $(this);
            let text = _this.val();
            let canonicalCustomize = $('.canonicalCustomize');
            let slug = HT.createSlug(text);
            canonicalCustomize.html('=>' + slug);
            $('.canonical').val(slug);
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

    HT.changeMetaSeo = () => {
        $(document).on('keyup', '.meta-seo', function () {
            let _this = $(this);
            let value = _this.val();
            let counter = _this.closest('.form-group').find('.characterCounter');
            counter.text(value.length);
            let targetClass = _this.attr('data-type').split('_')[1]+ '-seo';
            $('.'+targetClass).text(value);
            if(value.length == 0) {
                let textTitle = 'Bạn chưa có ' + _this.closest('.form-group').find('.label-title').text();
                if(targetClass == 'canonical-seo') {
                    textTitle = HT.createSlug(textTitle);
                }
                $('.'+targetClass).text(textTitle);
            }
            switch(targetClass) {
                case "title-seo":
                    HT.setStatusMetaSeo(_this, value, 60, 'Tiêu đề SEO ');
                    break;
                case "keyword-seo":
                    HT.setStatusMetaSeo(_this, value, 60, 'Từ khóa SEO ');
                    break;
                case "description-seo":
                    HT.setStatusMetaSeo(_this, value, 160, 'Mô tả SEO ');
                    break;
            }
        })
    }

    HT.setStatusMetaSeo = (object, value, limit, text) => {
        let counter = object.closest('.form-group').find('.characterCounter');
        if(value.length > limit) {
            counter.addClass('text-danger');
            object.css({
                "border": "1px solid red"
            });
            if(object.closest('.form-group').find('.error-seo').length == 0) {
                let newSpan = $('<span>', {
                    text: text + 'chỉ nên chứa tối đa '+limit+' kí tự',
                    class: 'error-seo',
                    css: {
                        color: 'red',
                        fontSize: '14px',
                        fontStyle: 'italic'
                    }
                });
                object.closest('.form-group').append(newSpan);
            }
        }else {
            counter.removeClass('text-danger');
            object.css({
                "border": "1px solid #F4F4F5"
            })
            object.closest('.form-group').find('.error-seo').remove();
        }
    }

    HT.onClickSubmit = () => {
        $(document).on('click', '.ibox-button', function (e) {
            if($('.error-seo').length > 0) {
                e.preventDefault();
                console.log($('.error-seo').parent('div').find('input').focus());
                $('.error-seo').parent('div').find('.form-control').focus()
            }

        })
    }

    HT.setupSeo = () => {
        $('.characterCounter').each(function(index, element) {
            let elem = $(element);
            let stringSeo = elem.closest('.seo').find('.meta-seo').val();
            let count = stringSeo.length;
            let target= elem.attr('data-target');
            if(count > 0) {
                $('.'+target).html(stringSeo);
            }
            elem.html(count);
            let limit = elem.attr('data-limit');
            count > limit ? elem.addClass('text-danger') : elem.removeClass('text-danger');
            let canonical = $('input[name="canonical"]').val();
            if(canonical !== "") {
                $('.canonical-seo').html(canonical);
            }
        })
    }

    HT.setValueForCanonical = () => {
        $(document).on('keyup', '.originalCanonical', function () {
            let _this = $(this);
            let canonicalSlug = HT.createSlug(_this.val());
            $('input[name="canonical"]').val(canonicalSlug);
            console.log(canonicalSlug);
        })
    }

    $(document).ready(function () {
        HT.setSlug();
        HT.changeMetaSeo();
        HT.onClickSubmit();
        HT.setupSeo();
        HT.setValueForCanonical();
    });
})(jQuery);