(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.openForm = () => {
        $(document).on('click', '.module-item', function () {
            let target = $(this).data('target');
            $(this).closest('.list-module').addClass('hidden');
            $('#' + target).addClass('active');
            // let newQueryString = '?page=' + page;
            // let page = $(this).data('page');
            // let currentUrl = window.location.href.split('?')[0]; //đường dẫn hiện tại đã được loại bỏ phần query string
            // history.pushState(null, '', currentUrl + newQueryString);
        })
    }

    HT.closeForm = () => {
        $(document).on('click', '.btnCloseForm', function () {
            let _this = $(this);
            _this.closest('.overlay-content').removeClass('active');
            $('.list-module').removeClass('hidden');
        })
    }

    $(document).ready(function () {
        HT.openForm();
        HT.closeForm();
    });
})(jQuery);