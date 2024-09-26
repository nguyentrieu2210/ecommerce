(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.moreDescription = () => {
        $(document).on('click', '.actionContent', function () {
            if($(this).hasClass('moreContent')) {
                $('.descriptionProduct').removeClass('max-h-[800px]');
                $(this).removeClass('moreContent').text('Thu gọn');
            }else{
                $('.descriptionProduct').addClass('max-h-[800px]');
                $(this).addClass('moreContent').text('Xem thêm');
            }
        })
    }

    HT.changeActiveImage = () => {
        $(document).on('click', '.itemImage', function() {
            $('button.image-active').removeClass('image-active');
            let image = $(this).find('img').attr('src');
            $(this).addClass('image-active');
            $('.bigImage').attr('src', image);
        })
    }

    HT.setupVariantDefault = () => {
        if($('.box-attribute').length) {
            $.each($('.attribute-item'), function (index, elem) {
                let target = $(elem).find('.attribute-value-item').first();
                target.addClass('attribute-active');
            })
            let albumById = $('.box-attribute').attr('data-albumById');
            let albumId = $('.box-attribute').find('.attribute-item[data-id="'+ albumById +'"]').find('.attribute-active').data('id');
            let dataAlbums = JSON.parse($('.newAlbums').val());
            let albumVariant = dataAlbums[albumId];
            HT.setupImageVariant(albumVariant);
            HT.setupInfoByVariant();
        }
    }

    HT.setupInfoByVariant = () => {
        let attributes = $('.attribute-active').map(function() {
            return $(this).data('id');
        }).get();
        attributes.sort(function(a, b) {
            return a - b;
        });
        let variantCode = attributes.join(' ');
        let product = JSON.parse($('.product').val());
        let dataProductVariants = product.new_product_variants;
        let variant = dataProductVariants[variantCode];
        let warehouseId = $('.choose-warehouse').val();
        let variantId = variant.id;
        let keyVariantWarehouse = variantId + '-' + warehouseId;
        let variantWarehouse = product.new_warehouses[keyVariantWarehouse];
        let priceAfterTax = product.tax_status !== 0 ? variant.price + (variant.price / 100 * parseInt(product.output_tax.replace('VAT', ''))) : variant.price;
        $('.productName').text(product.name + variant.name);
        $('.productSku').text(variant.sku);
        
        if(variantWarehouse.quantity - variantWarehouse.stock == 0) {
            $('.status-product').text('Hết hàng').removeClass('text-success').addClass('text-danger');
        }else {
            $('.status-product').text('Còn hàng (' + (variantWarehouse.quantity - variantWarehouse.stock) + ')').removeClass('text-danger').addClass('text-success');
        }
        let finalPrice = priceAfterTax;
        //Áp dụng giảm giá lớn nhất theo sản phẩm
        $.each(product.new_promotions, function (key, item) {
            if(key.includes(variantId)) {
                const discountPrice =  item.discount_type == 'percent' ? (priceAfterTax - priceAfterTax / 100 * parseFloat(item.discount_value)) : (priceAfterTax - parseFloat(item.discount_value));
                finalPrice = Math.min(discountPrice, finalPrice);
            }
        })
        //Áp dụng giảm giá lớn nhất theo nhóm sản phẩm
        $.each(product.promotion_catalogues, function (index, item) {
            const discountPrice = item.discount_type == 'percent' ? (priceAfterTax - priceAfterTax / 100 * parseFloat(item.discount_value)) : (priceAfterTax - parseFloat(item.discount_value));
            finalPrice = Math.min(discountPrice, finalPrice);
        })
        $('.finalPrice').text(HT.setupInputNumber(Math.round(finalPrice / 1000) * 1000));
        if(priceAfterTax !== finalPrice) {
            $('.oldPrice').text(HT.setupInputNumber(priceAfterTax) + ' đ');
        }else{
            $('.oldPrice').text('');
        }
    }

    HT.setupInfo = () => {
        let warehouseId = $('.choose-warehouse').val();
        let product = JSON.parse($('.product').val());
        let productWarehouse = product.new_warehouses[warehouseId];
        let quantitySell = productWarehouse.quantity - productWarehouse.stock;
        if(quantitySell == 0) {
            $('.status-product').text('Hết hàng').removeClass('text-success').addClass('text-danger');
        }else{
            $('.status-product').text('Còn hàng (' + (quantitySell) + ')').removeClass('text-danger').addClass('text-success');
        }
    }

    HT.changeWarehouse = () => {
        $(document).on('input', '.choose-warehouse', function () {
            let value = $(this).val();
            if($('.box-attribute').length) {
                HT.setupInfoByVariant();
            }else{
                HT.setupInfo();
            }
        })
    }

    HT.setupImageVariant = (dataAlbum) => {
        if(dataAlbum.album !== null) {
            let html = '';
            $.each(dataAlbum.album, function(index, value) {
                if(index == 0) {
                    html += HT.renderButtonImage(value, true);
                }else{
                    html += HT.renderButtonImage(value);
                }
            })
            $('.img-group').find('ul').html(html);
            $('.bigImage').attr('src', dataAlbum.album[0]);
        }
    }

    HT.renderButtonImage = (image, isActive = false) => {
        return `<li class="nav-item">
                    <button class="itemImage ${isActive ? 'image-active' : ''}">
                        <img src="${image}" alt="">
                    </button>
                </li>`;
    }

    HT.changeVariant = () => {
        $(document).on('click', '.attribute-value-item', function() {
            if($(this).closest('.attribute-item').hasClass('hasAlbum')) {
                let albumId = $(this).data('id');
                let dataAlbums = JSON.parse($('.newAlbums').val());
                let dataAlbum = dataAlbums[albumId];
                HT.setupImageVariant(dataAlbum);
            }
            HT.changeAttributeVariant($(this));
            HT.setupInfoByVariant();
        })
    }

    HT.changeAttributeVariant = (object) => {
        object.closest('.attribute-item').find('.attribute-value-item.attribute-active').removeClass('attribute-active');
        object.addClass('attribute-active');
    }

    
    HT.setupInputNumber = (string) => {
        return Number(string).toLocaleString('en').replace(/,/g, '.');
    }

    $(document).ready(function () {
        HT.moreDescription();
        HT.changeActiveImage();
        HT.setupVariantDefault();
        HT.changeVariant();
        HT.changeWarehouse(); 
    });
})(jQuery);