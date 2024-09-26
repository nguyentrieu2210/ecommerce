(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');

    var targetUrl = '';

    HT.clickLink = () => {
        $(document).on('click', '.confirmLink', function (e) {
            e.preventDefault();
            targetUrl = $(this).attr('href');
            $('.confirmExitModal').modal('show');
        } )
    }

    HT.clickBtnConfirmExit = () => {
        $(document).on('click', '.btnConfirmExit', function () {
            window.location.href = targetUrl;
        })
    }

    $(document).ready(function () {
        HT.clickLink();
        HT.clickBtnConfirmExit();
    });
})(jQuery);