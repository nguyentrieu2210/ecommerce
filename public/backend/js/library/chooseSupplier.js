(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.focusInputSupplier = () => {
        $(document).on('focus', '.inputSearchSupplier', function () {
            $(this).val('').trigger('input');
            $('.render-list-supplier').removeClass('hidden');
        })
    }

    HT.blurInputSupplier = () => {
        $(document).on('blur', '.search-supplier', function() {
            setTimeout(function() {
                $('.render-list-supplier').addClass('hidden');
            }, 150);
        })
    }

    HT.changeInputSupplier = () => {
        $(document).on('input', '.inputSearchSupplier', function() {
            let _this = $(this);
            let option = {
                'model': 'supplier',
                'keyword': _this.val(),
                '_token': _token
            }
            HT.callAjax(option);
        })
    }

    HT.callAjax = (option) => {
        $.ajax({
            url: '/admin/ajax/search/model',
            method: 'POST', 
            dataType: 'json',
            data: option,
            success: function (response) {
                let data = response.data; 
                let html = '';
                $.each(data, function(index, item) {
                    html += `
                        <div class="render-item-supplier flex" data-id="${item.id}" data-name="${item.name}" data-email="${item.email !== null ? item.email: ''}" 
                        data-phone="${item.phone !== null ? item.phone : ''}" data-address="${item.address !== null ? item.address : ''}">
                            <img src="/backend/img/empty-avatar-supplier.png" alt="">
                            <div class="">
                                <span>${item.name}</span><br>
                                <span>${(item.phone !== null ? '+' +item.phone : '') }</span>
                            </div>
                        </div>`
                })
                $('.render-list-supplier').html(html);
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    HT.chooseSupplier = () => {
        $(document).on('click', '.render-item-supplier', function () {
            let _this = $(this);
            $('.search-supplier').addClass('hidden');
            $('.render-list-supplier').addClass('hidden');
            let target = $('.info-supplier-detail');
            target.removeClass('hidden');
            let html = '';
            let email = _this.data('email');
            if(email !== '') {
                html += `<span class="text-primary text-normal block mt5">${email}</span>`;
            }else {
                html += `<span class="text-none block mt5">Không có email</span>`;
            }
            let phone = _this.data('phone');
            if(phone !== '') {
                html += `<span class="text-normal block mt5">+${phone}</span>`;
            }else {
                html += `<span class="text-none block mt5">Không có số điện thoại</span>`;
            }
            let address = _this.data('address');
            if(address !== '') {
                html += `<span class="text-normal block mt5">${address}</span>`;
            }else {
                html += `<span class="text-none block mt5">Không có địa chỉ</span>`;
            }
            $('.info-supplier-detail').removeClass('hidden');
            if($('.inputSearchSupplier').hasClass('input-error')) {
                $('.inputSearchSupplier').removeClass('input-error').next().remove();
            }
            let canonical = '/admin/supplier/' + _this.data('id') + '/edit';
            let name = _this.data('name');
            $('.info-supplier span a').text(name).attr('href', canonical);
            $('.info-supplier-b').html(html);
            $('input[name="supplier_id"]').val(_this.data('id'));
        })
    }

    HT.deleteSupplier = () => {
        $(document).on('click', '.deleteSupplier', function() {
            $('.info-supplier-detail').addClass('hidden');
            $('.search-supplier').removeClass('hidden');
        })
    }

    $(document).ready(function () {
        HT.focusInputSupplier();
        HT.blurInputSupplier();
        HT.changeInputSupplier();
        HT.chooseSupplier();
        HT.deleteSupplier();
    });
})(jQuery);