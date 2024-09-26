(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.uploadAlbum = () => {
        $(document).on('click', '.empty-image', function () {
            let _this = $(this);
            if (_this.hasClass('forEditor')) {
                let ischeckAdd = _this.hasClass('add-item-album');
                let html = '<img data-id="content" class="empty-image forEditor add-item-album" src="/backend/img/image-add1.png" alt="">';
                if(!ischeckAdd) {
                    $('.listImage').html(html);
                }
                $('.configuration').addClass('hidden');
                HT.browserServerEditor(ischeckAdd);
            } else {
                if(_this.attr('data-variant') == "1") {
                    HT.browserServerAlbum(true, _this);
                }else{
                    HT.browserServerAlbum(false, _this);
                }
                
            }
        })
    }
    
    HT.browserServerEditor = async (ischeckAdd) => {
        let type = 'Images';
        var finder = new CKFinder();
        if (typeof finder !== 'undefined') {
            finder.resourceType = type;
            finder.selectActionFunction = async function (fileUrl, data, allFiles) {
                let html = '';
                for (let i = 0; i < allFiles.length; i++) {
                    let image = allFiles[i].url;
                    //Tạo thẻ img ảo để lấy kích thước của ảnh 
                    let ratio = await HT.getImageRatio(image);
                    html += '<span class="item-album">'
                        html += '<img class="configurationItem" src="'+image+'">'
                        html += '<input type="hidden" name="item-title" value="">'
                        html += '<input type="hidden" name="item-width" value="">'
                        html += '<input type="hidden" name="item-height" value="">'
                        html += '<input type="hidden" name="item-link" value="">'
                        html += '<input type="hidden" name="item-ratio" value="'+ ratio +'">'
                    html += '<button class="btn-primary delete-image"><i class="fa fa-trash"></i></button></span>'
                }
                $('.listImage').append(html);
                if(ischeckAdd) {
                    $('.item-album').removeClass('item-album-active');
                }else {
                    $('.clickModal').trigger('click');
                }
            }
            finder.popup();
        }
    }

    HT.getImageRatio = (imageUrl) => {
        return new Promise((resolve, reject) => {
            let img = new Image();
            img.onload = function() {
                let width = img.naturalWidth;
                let height = img.naturalHeight;
                let ratio = width / height;
                resolve(ratio);
            };
            img.onerror = function() {
                reject(new Error('Failed to load image.'));
            };
            img.src = imageUrl;
        });
    }
    
    HT.demoConfigurationImageForEditor = () => {
        $(document).on('click', '.configurationItem', function () {
            let _this = $(this);
            if(_this.parent('.item-album-active').length > 0) {
                _this.parent('.item-album').removeClass('item-album-active');
                $('.configuration').addClass('hidden');
                return;
            }
            $('.configuration').removeClass('hidden');
            $('.item-album').removeClass('item-album-active');
            _this.closest('.item-album').addClass('item-album-active');
            //gán dữ liệu vào cho phần DEMO
            $('.imageDemoEmpty').addClass('hidden');
            let image = _this.attr('src');
            let height = _this.closest('.item-album').find('input[name="item-height"]').val();
            $('input[name="height"]').val(height);
            let width = _this.closest('.item-album').find('input[name="item-width"]').val();
            $('input[name="width"]').val(width);
            let title = _this.closest('.item-album').find('input[name="item-title"]').val();
            $('input[name="title"]').val(title);
            let link = _this.closest('.item-album').find('input[name="item-link"]').val();
            $('input[name="link"]').val(link);

            let html = '<figure class="image" style = "display:inline-block">'
                html += '<img alt="" height="auto" src="'+image+'" width="100%" max-width="100%">'
                html += '<figcaption class="text-center"></figcaption></figure>'
            $('.imageDemo').html(html);
        })  
    }
    
    HT.setConfigurationImageForEditorByKeyup = () => {
        $(document).on('keyup', '.configurationEditor', function () {
            let _this = $(this);
            let nameTarget = _this.attr('name');
            let ratio = $('.item-album-active').find("input[name='item-ratio']").val();
            let maxWidth = $('.configuration').find('.iboxImageDemo').width();
            let maxHeight = maxWidth / ratio;
            switch(nameTarget) {
                case "title":
                    $('.imageDemo').find('figcaption').html(_this.val());
                    $('.item-album-active').find("input[name='item-title']").val(_this.val());
                    break;
                case "width":
                    HT.setImageWhenChangeWidth(_this, maxWidth, maxHeight, ratio);
                    break;
                case "height":
                    HT.setImageWhenChangeHeight(_this, maxWidth, maxHeight, ratio);
                    break;
                default:
                    $('.item-album-active').find("input[name='item-link']").val(_this.val());
            }
        });
    }

    HT.setConfigurationImageForEditorByChange = () => {
        $(document).on('change', '.onChange', function () {
            let _this = $(this);
            let nameTarget = _this.attr('name');
            let ratio = $('.item-album-active').find("input[name='item-ratio']").val();
            let maxWidth = $('.configuration').find('.iboxImageDemo').width();
            let maxHeight = maxWidth / ratio;
            switch(nameTarget) {
                case "width":
                    HT.setImageWhenChangeWidth(_this, maxWidth, maxHeight, ratio);
                    break;
                case "height":
                    HT.setImageWhenChangeHeight(_this, maxWidth, maxHeight, ratio);
                    break;
            }
        })
    }

    HT.setImageWhenChangeWidth = (object, maxWidth, maxHeight, ratio) => {
        let height = $('input[name="height"]').val();
        let width;
        if(object.val() == "") {
            if(height == "") {
                width = maxWidth;
                height = maxHeight;
            }else {
                width = maxWidth / (height / maxHeight);
                height = maxHeight;
            }
        }else{
            width = parseFloat(object.val()) < maxWidth ? parseFloat(object.val()) : maxWidth;
            let percent = object.val() / width;
            height = height !== "auto" ? parseFloat(height) / percent : "auto";
        }
        $('.imageDemo').find('img').attr('width', width);
        $('.imageDemo').find('img').attr('height', height);
        $('.item-album-active').find("input[name='item-width']").val(object.val());
    }

    HT.setImageWhenChangeHeight = (object, maxWidth, maxHeight, ratio) => {
        let width1 = $('input[name="width"]').val();
        let height1 = object.val();
        if(width1 == '') {
            if(height1 == "") {
                width1 = maxWidth;
                height1 = maxHeight;
            }else{
                height1 = parseFloat(height1);
                width1 = height1 * ratio < maxWidth ? height1 * ratio : maxWidth; 
                height1 = width1 / ratio;
            }
        }else{
            if(height1 == "") {
                width1 = width1 < maxWidth ? width1 : maxWidth;
                height1 = width1 / ratio;
            }else{
                let percent = width1 / maxWidth;
                width1 = width1 < maxWidth ? width1 : maxWidth;
                height1 = percent < 1 ? height1 : (height1 / percent);
            }
        }
        $('.imageDemo').find('img').attr('height', height1);
        $('.imageDemo').find('img').attr('width', width1);
        $('.item-album-active').find("input[name='item-height']").val(object.val());
    }

    HT.completeConfigurationImageForEditor = () => {
        $(document).on('click', '.completeSetupImageForEditor', function () {
            let html = '<div style="text-align:center">';
            $('.listImage').find('.item-album').each(function(index, element) {
                let _this = $(element);
                let title = _this.find('input[name="item-title"]').val();
                let width = _this.find('input[name="item-width"]').val();
                let height = _this.find('input[name="item-height"]').val();
                let link = _this.find('input[name="item-link"]').val();
                let image = _this.find('.configurationItem').attr('src');
                //render html for editor
                html += '<figure class="image" style = "display:inline-block">'
                    if(link == '') {
                        html += '<img alt="'+ title +'" height="'+ height +'" src="'+image+'" width="'+ width +'" max-width="100%">'
                    }else{
                        html += '<a href="'+ link +'"><img alt="" height="'+ height +'" src="'+image+'" width="'+ width +'" max-width="100%"></a>'
                    }
                    html += '<figcaption>'+ title +'</figcaption>'
                html += '</figure>'
            })
            html += '</div>'
            CKEDITOR.instances['content'].insertHtml(html);
            $('.clickModal').trigger('click');
            $('.configuration').addClass('hidden');
            $('.listImage').html('');
        })
    }

    HT.cancelConfigurationImageForEditor = () => {
        $(document).on('click', '.cancelSetupImageForEditor', function () {
            let result = confirm('Bạn có chắc muốn quay lại? Nếu quay lại bạn sẽ mất cấu hình cho danh sách hình ảnh.');
            if(result) {
                $('.clickModal').trigger('click');
            }
        })
    }

    HT.triggerAlbum = () => {
        $(document).on('click', '.uploadAlbum', function() {
            if($(this).attr('data-variant') == "1") {
                $(this).closest('.variantAlbumInfor').next('tr').find('.triggerImage').trigger('click');
                $(this).closest('.variantAlbumInfor').next('tr').find('.variantAlbumDetail').removeClass('hidden');
            }else{
                $(this).closest('.album').find('.triggerImage').trigger('click');
            }
        })
    }
    
    HT.browserServerAlbum = (isVariant, object) => {
        let type = 'Images';
        var finder = new CKFinder();
        let attribute_id;
        if(isVariant) {
            attribute_id = object.closest('.emptyAlbum').attr('data-attributeId');
        }
        if (typeof finder !== 'undefined') {
            finder.resourceType = type;
            finder.selectActionFunction = function (fileUrl, data, allFiles) {
                let html = '';
                for (let i = 0; i < allFiles.length; i++) {
                    let image = allFiles[i].url;
                    html += '<span class="item-album"><a href="'+image+'" title="Album" data-gallery="">'
                        html += '<img src="'+image+'">'
                        html += '<input type="hidden" name="'+ (isVariant ? ('album_variant[album]['+ attribute_id +'][]') : 'album[]') +'" value="' + image + '">'
                    html += '</a>'
                    html += `<button data-variant="${isVariant == true ? 1 : 0}" class="btn-primary delete-image"><i class="fa fa-trash"></i></button></span>`
                }
                let target = object.closest('.emptyAlbum').prev('.listAlbum');
                    target.removeClass('hidden');
                    target.find('.sortable').append(html);
                    object.closest('.emptyAlbum').addClass('hidden');
            }
            finder.popup();
        }
    }
    
    HT.deleteImage = () => {
        $(document).on('click', '.delete-image', function () {
            let _this = $(this);
            let divAlbum = _this.attr('data-variant') == "1" ? _this.closest('tr') : _this.closest('.album');
            let parent = _this.closest('.sortable').attr('class').split(' ')[1];
            _this.parent('.item-album').remove();
            //album
            if (divAlbum.find('.listAlbum').find('.item-album').length == 0 && parent == 'lightBoxGallery') {
                divAlbum.find('.listAlbum').addClass('hidden');
                divAlbum.find('.emptyAlbum').removeClass('hidden');
                return;
            }
            //modal
            if ($('.listImage').find('.item-album').length == 0 && parent == 'listImage') {
                $('.imageDemoEmpty').removeClass('hidden');
                $('.imageDemo').html('');
                $('.configuration').addClass('hidden');
                $('.clickModal').trigger('click');
                return;
            }
        })
    }

    $(document).ready(function () {
        HT.uploadAlbum();
        HT.deleteImage();
        HT.demoConfigurationImageForEditor();
        HT.setConfigurationImageForEditorByKeyup();
        HT.setConfigurationImageForEditorByChange();
        HT.completeConfigurationImageForEditor();
        HT.cancelConfigurationImageForEditor();
        HT.triggerAlbum();
    });
})(jQuery);