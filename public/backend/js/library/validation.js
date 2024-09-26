(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.validate = () => {
        $("#form").validate({
            rules: {
                name: {
                    required: true
                },
                keyword: {
                    required: true
                },
                password: {
                    required: true,
                    minlength: 3
                },
                url: {
                    required: true,
                    url: true
                },
                number: {
                    required: true,
                    number: true
                },
                min: {
                    required: true,
                    minlength: 6
                },
                max: {
                    required: true,
                    maxlength: 4
                }
            },
            messages: {
                name: {
                    required: 'Tên không được để trống.'
                },
                keyword: {
                    required: 'Bạn cần nhập từ khóa.'
                }
            }
        });
    }

    $(document).ready(function () {
        HT.validate();
    });
})(jQuery);