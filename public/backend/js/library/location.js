(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.changeLocation = () => {
        $(document).on('change', '.changeLocation',async function () {
            let _this = $(this);
            let arrClass = _this.attr('class').split(' ');
            let targetLocation = (_this.attr('data-target'));
            let option = {
                'code': _this.val(),
                'target': _this.attr('data-target'),
                'location': arrClass[0]
            };
            if(_this.val() != 0) {
                try {
                    let response = await $.ajax({
                        type: 'GET',
                        url: '/admin/ajax/change/location',
                        data: option,
                        dataType: 'json'
                    });
                    let html = HT.renderDataLocation(response.data, targetLocation);
                    let selectTarget = $('.' + targetLocation);
                    selectTarget.html(html);
                    selectTarget.select2();
                } catch (error) {
                    console.error('Error fetching location data:', error);
                }
            }
        })
    }

    HT.setLocation = async () => {
        await $('.Province').trigger('change');
        setTimeout(function() {
            $('.District').trigger('change').select2();
        }, 500);
    }

    HT.setLocationItem = ($target = "") => {
        let dataModule = $('.' + $target.toLowerCase()).val();
        if(dataModule !== "") {
            let html = HT.renderDataLocation(JSON.parse(dataModule), $target)
            $('.' + $target).html(html);
            $('.' + $target).select2();
        } 
    }

    HT.renderDataLocation = (data, target) => {
        let textTarget = target == 'District' ? 'Chọn quận / huyện' : 'Chọn phường / xã';
        let oldValue = target == 'District' ? $('.oldDistrictId').val() : $('.oldWardId').val();
        let html = '<option value="0">'+ textTarget +'</option>';
        $.each(data, function(index, item) {
            html += '<option '+ (oldValue == item.code ? 'selected' : '') +' value="'+ item.code +'">'+ item.name +'</option>'
        })
        return html;
    }

    $(document).ready(function () {
        HT.changeLocation();
        HT.setLocation();
    });
})(jQuery);