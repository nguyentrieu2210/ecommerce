(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.openModalComment = () => {
        $(document).on('click', '.btn-write', function () {
            $('.popup-rating-topzone').removeClass('hidden');
            $('.bg-coverrate').removeClass('hidden');
            $('body').addClass('no-scroll');
            HT.resetModalComment();
            HT.setupDisplayStarRating(5);
        })
    }

    HT.resetModalComment = () => {
        $('input#commentAlbum').val('');
        $('.upload__img-wrap').html('');
        $('input[name="customer_id"]').val('');
        $('.fRName').removeClass('input-error').next().remove();
        $('.fRPhone').removeClass('input-error').next().remove();
        $('.fRContent').text('').removeClass('input-error').next().remove();
    }

    HT.closeModalComment = () => {
        $(document).on('click', '.closeModalComment', function () {
            $('.popup-rating-topzone').addClass('hidden');
            $('.bg-coverrate').addClass('hidden');
            $('body').removeClass('no-scroll');
        })
    }

    HT.openModalSuccess = () => {
        $('.bgcover-success').removeClass('hidden');
        $('.popup-success').removeClass('hidden');
    }

    HT.closeModalSuccess = () => {
        $(document).on('click', '.close-popup-success', function() {
            $('.bgcover-success').addClass('hidden');
            $('.popup-success').addClass('hidden');
        })
    }

    HT.chooseStarRating = () => {
        $(document).on('click', '.choose-star', function () {
            let starRating = $(this).data('val');
            $('input[name="star_rating"]').val(starRating);
            HT.setupDisplayStarRating(starRating);
        })
    }

    HT.setupDisplayStarRating = (starRating) => {
        $.each($('.choose-star'), function(index, elem) {
            if($(elem).attr('data-val') <= starRating) {
                $(elem).find('i.iconcmt-unstarlist').addClass('active');
            }else{
                $(elem).find('i').removeClass('active');
            }
        })
    }

    HT.clickSubmitModalComment = () => {
        $(document).on('click', '.send-rate', function(e) {
            e.preventDefault();
            let isCheck = true;
            if($('.fRName').val() == '') {
                isCheck = false;
                HT.setupErrorEmpty($('.fRName'), 'Bạn cần nhập họ tên');
            }
            if($('.fRPhone').val() == '') {
                isCheck = false;
                HT.setupErrorEmpty($('.fRPhone'), 'Bạn cần nhập số điện thoại');
            }
            let regex = /^(03|05|07|08|09)\d{8}$/;
            if($('.fRPhone').val() !== '' && !regex.test($('.fRPhone').val())) {
                isCheck = false;
                HT.setupErrorEmpty($('.fRPhone'), 'Số điện thoại không đúng định dạng');
            }
            if($('.fRContent').val() == '') {
                isCheck = false;
                HT.setupErrorEmpty($('.fRContent'), 'Bạn cần nhập nội dung cho đánh giá');
            }
            if(isCheck) {
                var formData = new FormData();
                var files = $('input[type="file"]')[0].files;
                Array.from(files).forEach(function(file) {
                    formData.append('files[]', file);
                })
                formData.append('star_rating', $('input[name="star_rating"]').val());
                formData.append('content', $('.fRContent').val());
                formData.append('customer_id', $('input[name="customer_id"]').val());
                formData.append('model', $('input[name="model"]').val());
                formData.append('model_id', $('input[name="model_id"]').val());
                formData.append('name', $('.fRName').val());
                formData.append('phone', $('.fRPhone').val());
                formData.append('_token', _token);
                HT.callAjax(formData, '/ajax/comment/store', 'POST');
            }
        })
    }

    HT.seeMoreComment = () => {
        $(document).on('click', '.btn-view-all', function(e) {
            e.preventDefault();
            const perpage = $('.fRQuantityPerpage').val();
            let option = {
                'model': $('input[name="model"]').val(),
                'model_id': $('input[name="model_id"]').val(),
                'limit': parseInt($('.fRLimit').val()) + parseInt(perpage),
            }
            HT.callAjax(option, 'ajax/get/comment', 'GET');
        })
    }

    HT.callAjax = (option = [], url, method) => {
        let ajaxOptions = {
            url: url,
            method: method,
            data: option,
            dataType: 'json',
            success: function (response) {
                if(response.message == 'success') {
                    if(method == 'POST') {
                        $('.popup-rating-topzone').addClass('hidden');
                        $('.bg-coverrate').addClass('hidden');
                        HT.openModalSuccess();
                    }else {
                        let html = HT.renderListComment(response.data);
                        $('.comment-list').html(html);
                        let totalRatings = $('.fRQuantityTotal').val();
                        if(typeof(option.limit) !== 'undefined' && parseInt(totalRatings) !== option.limit) {
                            $('.fRLimit').val(option.limit);
                            $('.btn-view-all').text('Xem ' + (parseInt(totalRatings) - option.limit) + ' đánh giá');
                        }else {
                            $('.btn-view-all').addClass('hidden');
                        }
                    }
                }
            },
            error: function (xhr, status, error) {
                toastr.error(response.message);
                console.error('Error:', error);
            }
        }
        if(method == 'POST') {
            ajaxOptions.contentType = false;
            ajaxOptions.processData = false;
        }
        $.ajax(ajaxOptions);
    }

    HT.renderListComment = (data) => {
        let html = '';
        if(data.length) {
            $.each(data, function(index, item) {
                html += HT.renderCommentItemHtml(item);
            })
        }else{
            html += '<span class="block w100 text-center">Chưa có đánh giá nào</span>'
        }
        return html;
    }

    HT.renderCommentItemHtml = (data) => {
        let liked = localStorage.getItem('comment_' + data.id);
        let html = `<li class="par">
            <div class="cmt-top">
                <p class="cmt-top-name">${data.name}</p>`
                if(data.customers !== null) {
                    html += 
                    `<div class="confirm-buy">
                        <i class="iconcmt-confirm"></i>
                        Khách hàng tại E+
                    </div>`;
                }
            html += `</div>
            <div class="cmt-intro">
                <div class="cmt-top-star">`;
                    for(let i = 1; i <= data.star_rating; i++) {
                        html += '<i class="iconcmt-starbuy"></i>';
                    }
                    if(5 - data.star_rating) {
                        for(let i = 1; i <= (5 - data.star_rating); i++){
                            html += '<i class="iconcmt-unstarbuy"></i>';
                        }
                    }
                html += `</div>
            </div>
            <div class="cmt-content ">
                <p class="cmt-txt">${data.content}</p>
            </div>`
            if(data.album !== null) {
                html += `<div class="album-comment-customer flex">`
                    $.each(data.album, function(index, item) {
                        html += `<p class="it-img mt10">
                            <img class="lazyloaded" src="${item}" alt="img">
                        </p>`
                    })
                html += `</div>`
            }
            html += `<div class="cmt-command">
                <a data-id="${data.id}" class="cmtl dot-circle-ava likeComment"
                    data-like="${data.likes_count}">
                    <i class="${liked ? 'fas' : 'far'} fa-thumbs-up"></i>Hữu ích (<span class="countLiked">${data.likes_count}</span>)
                </a>
                <span class="cmtd dot-line">${HT.formatTimestamp(data.created_at)}</span>
            </div>
        </li>`;
        return html;
    }

    HT.setupErrorEmpty = (object, message) => {
        if(!object.hasClass('input-error')) {
            object.addClass('input-error').after('<span class="alert-error">' + message + '</span>')
        }
    }

    HT.changeInputData = () => {
        $(document).on('input', '.input-data', function() {
            if($(this).val() !== '') {
                $(this).removeClass('input-error').next().remove();
            }
        })
    }
    
    HT.formatTimestamp = (timestamp) => {
        moment.locale('vi');
        return moment(timestamp).fromNow();
    }

    
    HT.filterComment = () => {
        $(document).on('click', '.filter-comment-item', function () {
            $('.filter-comment-item.active-filter-comment').removeClass('active-filter-comment');
            $(this).addClass('active-filter-comment');
            const perpage = $('.fRQuantityPerpage').val();
            let option = {
                'model': $('input[name="model"]').val(),
                'model_id': $('input[name="model_id"]').val(),
            }
            const filter = $(this).data('filter');
            switch(filter){
                case "latest": 
                    $('.btn-view-all').removeClass('hidden');
                    option.limit = parseInt($('.fRLimit').val());
                    break;
                case "album":
                    option.album = 1;
                    break;
                default:
                    option.star_rating = $(this).data('star');
                    break;
            }
            HT.callAjax(option, 'ajax/get/comment', 'GET');
        })
    }

    HT.chooseAlbum = () => {
        $(document).on('change', '#commentAlbum', function(e) {
            var files = e.target.files;
            var maxfiles = Math.min(files.length, 3);
            Array.from(files).slice(0, maxfiles).forEach(function(file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    let html = HT.renderImageCommentPopupHtml(e.target.result);
                    if($('.image-comment-item').length < 3) {
                        $('.upload__img-wrap').append(html);
                    }else {
                        toastr.error('Bạn chỉ có thể gửi tối đa 3 ảnh');
                    }
                }
                reader.readAsDataURL(file);
            })
        })
    }

    HT.deleteImageComment = () => {
        $(document).on('click', '.deleteImageComment', function() {
            $(this).closest('.image-comment-item').remove();
        })
    }

    HT.renderImageCommentPopupHtml = (image) => {
        return `<div class="image-comment-item">
            <img src="${image}" alt="">
            <i class="fas fa-times-circle deleteImageComment"></i>
        </div>`;
    }

    //Sử dụng Local Storage
    HT.likeComment = () => {
        $(document).on('click', '.likeComment', function () {
            let idComment = $(this).data('id');
            let likesCount = parseInt($(this).find('.countLiked').text());
            let option = {
                'id': idComment,
                'model': 'comment',
                'data': {
                    'likes_count': 0
                },
                '_token': _token
            }
            if($(this).find('i').hasClass('far')) {
                $(this).find('i').removeClass('far').addClass('fas');
                localStorage.setItem('comment_' + idComment, true);
                option.data.likes_count = likesCount + 1;
                HT.updateLikesCount(option);
                $(this).find('.countLiked').text(likesCount + 1);
            }else{
                $(this).find('i').removeClass('fas').addClass('far');
                localStorage.removeItem('comment_' + idComment, true);
                option.data.likes_count = likesCount - 1;
                HT.updateLikesCount(option);
                $(this).find('.countLiked').text(likesCount - 1);
            }
        })
    }

    HT.updateLikesCount = (option) => {
        console.log(option);
        $.ajax({
            url: '/ajax/update/model', 
            type: 'POST',
            data: option, 
            success: function(response) { 
                console.log('Success:', response);
            },
            error: function(xhr, status, error) { 
                console.error('Error:', error);
            }
        });
    }

    HT.setupStatusLike = () => {
        $.each($('.likeComment'), function(index, elem) {
            let id = $(elem).data('id');
            let statusLiked = localStorage.getItem('comment_' + id);
            if(statusLiked) {
                $(elem).find('i').removeClass('far').addClass('fas');
            }
        })
    }

    $(document).ready(function () {
        HT.openModalComment();
        HT.closeModalComment();
        HT.chooseStarRating();
        HT.clickSubmitModalComment();
        HT.changeInputData();
        HT.seeMoreComment();
        HT.closeModalSuccess();
        HT.filterComment();
        HT.chooseAlbum();
        HT.deleteImageComment();
        HT.likeComment();
        HT.setupStatusLike();
    });
})(jQuery);