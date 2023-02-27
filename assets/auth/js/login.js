
(function ($) {
    "use strict";

     /*==================================================================
    [ Focus input ]*/
    $('.input100').each(function(){
        $(this).on('blur', function(){
            if($(this).val().trim() != "") {
                $(this).addClass('has-val');
            } else {
                $(this).removeClass('has-val');
            }
        })    
    });

    // Show / Hide Password
    // -----------------------------------------------
    $("body").delegate(".show-hide-password button", "click", function( e ) {
        e.preventDefault();
        if ($('.show-hide-password input').attr("type") == "text") {
            $('.show-hide-password input').attr('type', 'password');
            $('.show-hide-password i.icon-eye').addClass("fa-eye-slash");
            $('.show-hide-password i.icon-eye').removeClass("fa-eye");
        } else if ($('.show-hide-password input').attr("type") == "password") {
            $('.show-hide-password input').attr('type', 'text');
            $('.show-hide-password i.icon-eye').removeClass("fa-eye-slash");
            $('.show-hide-password i.icon-eye').addClass("fa-eye");
        }
    });
  
  
    /*==================================================================
    [ Validate ]*/
    $('#forget-password').click(function () {
        $( '.login-form' )[0].reset();
        $( '.forget-form' )[0].reset();
        $('#content-login').hide();
        $('#content-forget').show();
    });

    $('#login-member').click(function () {
        $( '.login-form' )[0].reset();
        $( '.forget-form' )[0].reset();
        $('#content-forget').hide();
        $('#content-login').show();
    });
  
  
    /*==================================================================
    [ Validate ]*/
    var input = $('input.form-control');


    function run_waitMe(loader){
        $('body').waitMe({
            effect: loader,
            text: 'Please wait...',
            bg: 'rgba(255,255,255,0.9)',
            color: '#000',
            maxSize: '',
            source: 'img.svg',
            onClose: function() {}
        });
    }

    var showError = function(errorMsg) {
        var errorValidate = $('div.error-validate');
        if ( errorMsg ) {
            errorValidate.find('span').html(errorMsg);
        }
        errorValidate.show();
        setTimeout(function(){ errorValidate.hide(); }, 5000);
    };

    var showErrorForget = function(errorMsg) {
        var errorValidate = $('div.error-forget');
        errorValidate.find('span').html(errorMsg);
        errorValidate.show();
    };

    var showSuccessForget = function(Msg) {
        var alertMsg = $('div.success-forget');
        alertMsg.find('span').html(Msg);
        alertMsg.show();
    };

    function showValidate(input) {
        var thisAlert = $(input).parent().parent();
        $(thisAlert).addClass('error');
    }

    function hideValidate(input) {
        var thisAlert = $(input).parent();
        $(thisAlert).removeClass('error');
    }


    $('.validate-form .input100').each(function(){
        $(this).focus(function(){
           hideValidate(this);
        });
    });

    $('.login-form').validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: true, // do not focus the last invalid input
        rules: {
            username: {
                required: true,
            },
            password: {
                required: true,
            },
        },
        messages: {
            username: {
                required: "Username harus di isi",
            },
            password: {
                required: "Password harus di isi",
                minlength: "Minimal harus 6 karakter"
            }
        },
        invalidHandler: function (event, validator) { //display error alert on form submit   
            $('.alert-danger', $(this)).show();
        },
        highlight: function (element) { // hightlight error inputs
            showValidate(element);
        },
        success: function (label) {
            hideValidate(label);
            label.closest('.wrap-input100').removeClass('has-error');
            label.remove();
        },
        errorPlacement: function (error, element) {
            var thisElement = $(element).parent();
            error.insertAfter(thisElement);
        },
        submitHandler: function (form) {
            return validateLogin( form );
        }
    });

    $('.login-form input').keypress(function (e) {
        if (e.which == 13) {
            var form = $('.login-form');
            if ( $( form ).validate().form() ) {
                return validateLogin( form );
            }
            
            return false;
        }
    });


    $('.forget-form').validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: true, // do not focus the last invalid input
        rules: {
            forget_username: {
                required: true,
            },
            email: {
                required: true,
                email: true
            },
            capt_forget: {
                required: true,
                minlength: 6,
            },
        },
        messages: {
            forget_username: {
                required: "Username harus di isi",
            },
            email: {
                required: "Email harus di isi",
            },
            capt_forget: {
                required: "Captcha harus di isi",
                minlength: "Minimal harus 6 karakter"
            }
        },
        invalidHandler: function (event, validator) { //display error alert on form submit   
            showError('Ada beberapa kesalahan, silahkan cek formulir di bawah !');
        },
        highlight: function (element) { // hightlight error inputs
            showValidate(element);
        },
        success: function (label) {
            hideValidate(label)
            label.closest('.wrap-input100').removeClass('has-error');
            label.remove();
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element);
        },
        submitHandler: function (form) {
            return validateForget( form );
        }
    });

    $("body").delegate( ".close", "click", function( event ) {
        event.preventDefault();
        $('.alert').hide();
    });

    var validateLogin = function( form ) {
        var url = $( form ).attr( 'action' );
        var data = $( form ).serializeArray(); // convert form to array
        
        $.ajax({
            type : "POST",
            url  : url,
            data : $.param( data ),
            beforeSend: function(){
                run_waitMe('timer');
                $(".alert").hide();
            },
            success: function(response) {
                response = $.parseJSON( response );
                if ( response.success ){
                    return $( location ).attr( 'href', response.msg );
                }
                $('body').waitMe('hide');
                if(response.msg == 'Failed') { 
                    showError('<strong>FAILED!</strong><br /> Silahkan cek username atau password Anda.');
                }else if(response.msg == 'Not Active'){
                    showError('<strong>AKUN BELUM AKTIF!</strong><br /> Silakan hubungi Administrator.');
                    return $( location ).attr( 'href', response.url );
                }else if(response.msg == 'Banned'){
                    showError('<strong>AKUN TELAH DI BANNED!</strong><br /> Info lebih lengkap, hubungi manajemen.');
                }else if(response.msg == 'Deleted'){
                    showError('<strong>AKUN TIDAK DITEMUKAN!</strong><br /> Silakan hubungi Administrator.');
                }
            },
            error: function( jqXHR, textStatus, errorThrown ) {
                $('body').waitMe('hide');
                showError('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.');
            }
        });
    };
        
    var validateForget = function( form ) {
        var url = $( form ).attr( 'action' );
        var data = $( form ).serializeArray(); // convert form to array
        
        $.ajax({
            type : "POST",
            url  : url,
            data : $.param( data ),
            beforeSend: function(){
                run_waitMe('stretch');
                $(".alert").hide();
            },
            success: function(response) {
                $('body').waitMe('hide');
                response = $.parseJSON( response );
                if ( response.success ) {
                    $( form )[0].reset();
                    showSuccessForget(response.msg);
                    $("html, body").animate({ scrollTop: 0 }, "slow");   
                    return false;
                }
                if(response.msg == 'validate') { 
                    showErrorForget('<strong>Reset Password Gagal.</strong><br /> Nomor HP dan Email harus di isi!');
                }else if(response.msg == 'captcha') { 
                    showErrorForget('<strong>Captcha.</strong><br /> Pastikan captcha sudah benar.');
                }else if(response.msg == 'not_found'){
                    showErrorForget('<strong>Username belum terdaftar</strong><br /> Silakan registrasi dengan Username tersebut.');
                }else if(response.msg == 'not_active'){
                    showErrorForget('<strong>AKUN BELUM AKTIF!</strong><br /> Silakan hubungi Administrator.');
                }else if(response.msg == 'banned'){
                    showErrorForget('<strong>AKUN TELAH DI BANNED!</strong><br /> Info lebih lengkap, hubungi manajemen.');
                }else if(response.msg == 'deleted'){
                    showErrorForget('<strong>AKUN TIDAK DITEMUKAN!</strong><br /> Silakan hubungi Administrator.');
                }else if(response.msg == 'email_not_match'){
                    showErrorForget('<strong>EMAIL TIDAK SAMA.</strong><br /> Terjadi kesalahan data.');
                }else if(response.msg == 'failed'){
                    showErrorForget('<strong>Reset Password Gagal.</strong><br /> Terjadi kesalahan data.');
                }                
            },
            error: function( jqXHR, textStatus, errorThrown ) {
                $('body').waitMe('hide');
                showErrorForget('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.');
                
            }
        });
    };

    function validate (input) {
        if($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
            if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
                return false;
            }
        } else if($(input).attr('name') == 'username') {
            var len_user = $(input).val().length;
            if ( len_user < 5 ) {
                if (len_user !== 0) {
                    $(input).attr('data-validate', 'Minimal harus 5 karakter')
                    return false;
                } else {
                    return false;
                }
            }
        } else {
            if($(input).val().trim() == ''){
                return false;
            }
        }
    }
    
})(jQuery);