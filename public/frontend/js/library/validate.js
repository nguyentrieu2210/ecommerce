(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.setupError = (object, message) => {
        if(!$(object).hasClass('input-error')) {
            $(object).addClass('input-error').after('<span class="alert-error">'+ message +'</span>')
        }
    }

    //Name
    HT.checkInputName = () => {
        const name = $('input[name="name"]');
        const nameValue = name.val().trim();
        if(nameValue == '') {
            HT.setupError(name, 'Họ tên không được để trống');
            name.focus();
            return false;
        }
        if(!/^[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểẾỀỂỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s]+$/.test(nameValue)) {
            HT.setupError(name, 'Họ tên không được chứa số và các kí tự đặc biệt');
            name.focus();
            return false;
        }
        return true;
    }

    //Email 
    HT.checkInputEmail = () => {
        const email = $('input[name="email"]');
        const emailValue = email.val().trim();
        if(emailValue == '') {
            HT.setupError(email, 'Email không được để trống');
            email.focus();
            return false;
        }
        if(!/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(emailValue)) {
            HT.setupError(email, 'Email không hợp lệ');
            email.focus();
            return false;
        }
        return true;
    }

    //Password
    HT.checkInputPassword = (name) => {
        const password = $('input[name="'+ name +'"]');
        const passwordValue = password.val().trim();
        if(passwordValue == '') {
            HT.setupError(password, 'Mật khẩu không được để trống');
            password.focus();
            return false;
        }
        if(passwordValue.length < 6 || passwordValue.length > 12) {
            HT.setupError(password, 'Mật khẩu không hợp lệ (mật khẩu chứa từ 6 đến 12 kí tự)');
            password.focus();
            return false;
        }
        return true;
    }

    //RePassword
    HT.checkInputRePassword = (inputCheck) => {
        const password = $('input[name="re_password"]');
        const passwordValue = password.val().trim();
        if(passwordValue == '') {
            HT.setupError(password, 'Mật khẩu nhập lại không được để trống');
            password.focus();
            return false;
        }
        if(passwordValue !== $('input[name="'+ inputCheck +'"]').val()) {
            HT.setupError(password, 'Mật khẩu nhập lại không khớp');
            password.focus();
            return false;
        }
        return true;
    }
    
    //Phone 
    HT.checkInputPhone = () => {
        const phone = $('input[name="phone"]');
        const phoneValue = phone.val().trim();
        if(phoneValue == '') {
            HT.setupError(phone, 'Số điện thoại không được để trống');
            phone.focus();
            return false;
        }
        if(!/^(0|\+84)(3|5|7|8|9)\d{8}$/.test(phoneValue)) {
            HT.setupError(phone, 'Số điện thoại không hợp lệ');
            phone.focus();
            return false;
        }
        return true;
    }

    //Check Form Login
    HT.checkSubmitLogin = () => {
        $(document).on('submit', '.formLogin', function(e) {
            e.preventDefault();
            let isCheck = true;
            if(!HT.checkInputEmail()) isCheck = false;
            if(!HT.checkInputPassword('password')) isCheck = false;
            if(isCheck) this.submit();
        })
    }

    //Check Form Update Info
    HT.checkSubmitUpdateInfo = () => {
        $(document).on('submit', '.updateInfoAccount', function (e) {
            e.preventDefault();
            let isCheck = true;
            if(!HT.checkInputName()) isCheck = false;
            if(!HT.checkInputEmail()) isCheck = false;
            if(!HT.checkInputPhone()) isCheck = false;
            if(isCheck) this.submit();
        })
    }

    //Check Form RePassword 
    HT.checkFormRepassword = () => {
        $(document).on('submit', '.formRePassword', function (e) {
            e.preventDefault();
            let isCheck = true;
            if(!HT.checkInputPassword('password')) isCheck = false;
            if(!HT.checkInputPassword('new_password')) isCheck = false;
            if(!HT.checkInputRePassword('new_password')) isCheck = false;
            if(isCheck) this.submit();
        })
    }

    //Check Form Register 
    HT.checkFormRegister = () => {
        $(document).on('submit', '.formRegister', function (e) {
            e.preventDefault();
            let isCheck = true;
            if(!HT.checkInputName()) isCheck = false;
            if(!HT.checkInputPhone()) isCheck = false;
            if(!HT.checkInputEmail()) isCheck = false;
            if(!HT.checkInputPassword('password')) isCheck = false;
            if(!HT.checkInputRePassword('password')) isCheck = false;
            if(isCheck) this.submit();
        })
    }

    HT.changeInput = () => {
        $(document).on('input', '.inputValidate', function () {
            if($(this).hasClass('input-error')) {
                $(this).removeClass('input-error').next().remove();
            }
        })
    }

    HT.changeDisplayPassword = () => {
        $(document).on('click', '.box-input-password i', function () {
            if($(this).hasClass('fa-eye')) {
                $(this).removeClass('fa-eye').addClass('fa-eye-slash');
                $(this).closest('.box-input-password').find('input').attr('type', 'text');
            }else{
                $(this).removeClass('fa-eye-slash').addClass('fa-eye');
                $(this).closest('.box-input-password').find('input').attr('type', 'password');
            }
        })
    }

    $(document).ready(function () {
        HT.checkSubmitLogin();
        HT.changeInput();
        HT.checkSubmitUpdateInfo();
        HT.checkFormRepassword();
        HT.changeDisplayPassword();
        HT.checkFormRegister();
    });
})(jQuery);