(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.addToCart = () => {
        $(document).on('click', '.buttonCart', function(e) {
            e.preventDefault();
            let _this = $(this);
            let productData = JSON.parse($('.product').val());
            console.log(JSON.parse($('.product').val()));
            let option = {
                'name': productData.name,
                detail: {
                    'product_id': productData.id,
                    'quantity_total': $('.quantitySell').val(),
                    'original_price': $('.originalPrice').val()
                },
                'price': $('.finalPrice').val(),
                
            }
            console.log(option);
            if(_this.hasClass('buyNow')) {
                console.log('buyNow');
            }else{
                console.log('addCart');
            }
        })
    }

    $(document).ready(function () {
        HT.addToCart();
    });
})(jQuery);