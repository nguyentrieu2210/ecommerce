(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.changeSupervisor = () => {
        $(document).on('change', 'select[name="user_id"]', function () {
            let option = {
                'id': $(this).data('id'),
                'data': $(this).val(),
                'model': 'supplier',
                '_token': _token
            }
            HT.callAjax(option, "/admin/ajax/change/supervisor", 'POST');
        })
    }

    HT.callAjax = (option = [], url, method) => {
        $.ajax({
            url: url,
            method: method,
            data: option,
            dataType: 'json',
            success: function (response) {
                toastr.clear();
                toastr.success(response.message);
            },
            error: function (xhr, status, error) {
                toastr.error(response.message);
                console.error('Error:', error);
            }
        });
    }

    HT.changeIconDropdown = () => {
        $(document).on('click', '.filter-history', function () {
            let target = $(this).find('i');
            if(target.hasClass('fa-caret-down')) {
                target.removeClass('fa-caret-down').addClass('fa-caret-up')
            }else{
                target.removeClass('fa-caret-up').addClass('fa-caret-down')
            }
        })
    }

    $(document).ready(function () {
        HT.changeSupervisor();
        HT.changeIconDropdown();
    });
})(jQuery);