// =========================================================================
// Global Function
// =========================================================================
var formatCurrency = function( currency = '', rp = false ) {
    if (currency) {
        var number_string = currency.toString();
        sisa   = number_string.length % 3;
        rupiah = number_string.substr(0, sisa);
        ribuan = number_string.substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah   += separator + ribuan.join('.');
        }
        return ( rp ? 'Rp ' : '' ) + rupiah;
    } else {
        return rp ? 'Rp 0 ' : '0';
    }
};

$.fn.digits = function(){
    return this.each(function() {
        $(this).val( $(this).val().replace(/\D/g,"").replace(/\B(?=(\d{3})+(?!\d))/g, ".") );
    });
};

// ============================================================
// Form Validation Ganerate and Order PIN
// ============================================================
var FV_Profile = function () {   
    
    var handleValidationPersonal = function() {
        var form_personal   = $('#personal');
        var wrapper         = $('.content');

        form_personal.validate({
            errorElement: 'div',       // default input error message container
            errorClass: 'invalid-feedback',   // default input error message class
            focusInvalid: false,        // do not focus the last invalid input
            ignore: "",
            rules: {
                member_username: {
                    minlength: 5,
                    required: true,
                    unamecheck: true,
                },
                member_name: {
                    minlength: 3,
                    required: true,
                    lettersonly: true,
                },
                member_email: {
                    required: true,
                },
                member_phone: {
                    required: true,
                },
                member_province: {
                    required: true,
                },
                member_district: {
                    required: true,
                },
                member_subdistrict: {
                    required: true,
                },
                member_address: {
                    required: true,
                },
                member_bank: {
                    required: true,
                },
                member_bill: {
                    required: true,
                },
                member_bill_name: {
                    required: true,
                },
            },
            invalidHandler: function (event, validator) { //display error alert on form submit     
                App.alert({
                    type: 'danger', 
                    icon: 'warning', 
                    message: 'Ada beberapa error, silahkan cek formulir di bawah!', 
                    container: form_personal, 
                    place: 'prepend'
                });
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                $('#save_profile').modal('show');
            }
        });
        
        $.validator.addMethod("unamecheck", function(value) {
            return /^[A-Za-z0-9]{4,16}$/i.test(value);   // consists of only these
        }, "Username tidak memenuhi kriteria" );
        
        $.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
        }, "Silahkan inputkan Nama dengan huruf saja" );
    };
    
    var handleValidationCPassword = function() {
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation
        var form_cpass  = $('#cpassword');
        form_cpass.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                cur_pass: {
                    required: true
                },
                new_pass: {
                    minlength: 6,
                    required: true,
                    pwcheck: true,
                },
                cnew_pass: {
                    minlength: 6,
                    required: true,
                    equalTo : "#new_pass"
                },
            },
            messages: {
                cur_pass: {
                    minlength: "Minimal harus 6 karakter",
                    required: "Password lama harus di isi"
                },
                new_pass: {
                    minlength: "Minimal harus 6 karakter",
                    required: "Password baru harus di isi"
                },
                cnew_pass: {
                    minlength: "Minimal harus 6 karakter",
                    required: "Konfirmasi Password harus di isi",
                    equalTo: "Konfirmasi password tidak sesuai dengan password yang diinputkan"
                },
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                App.alert({
                    type: 'danger', 
                    icon: 'warning', 
                    message: 'Ada beberapa error, silahkan cek formulir di bawah!', 
                    container: form_cpass, 
                    place: 'prepend'
                });
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                $('#save_cpassword').modal('show');
            }
        });
        
        $.validator.addMethod("pwcheck", function(value) {
            return /[a-z].*[0-9]|[0-9].*[a-z]/i.test(value); // consists of only these
        }, "Password harus terdiri dari huruf dan angka" );
    };
    
    return {
        //main function to initiate the module
        init: function () {
            handleValidationPersonal();
            handleValidationCPassword();
        },
    };
}();

// ============================================================
// Form Validation Post
// ============================================================
var FV_Category = function () {    
    // ---------------------------------
    // Handle Validation Generate PIN
    // ---------------------------------
    var handleValidationCategory = function() {
        var form            = $('#form-category');
        var wrapper         = $('.content');

        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                cat_category: {
                    minlength: 3,
                    required: true
                }
            },
            messages: {
                cat_category: {
                    minlength: "Minimal 3 karakter",
                    required: "Title harus di isi !",
                }
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').length > 0) { 
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').length > 0) { 
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').length > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').length > 0) { 
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                App.alert({
                    type: 'danger', 
                    icon: 'warning', 
                    message: 'Ada beberapa error, silahkan cek formulir di bawah!', 
                    container: wrapper, 
                    place: 'prepend'
                });
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url             = $(form).attr('action');
                bootbox.confirm("Apakah anda yakin akan simpan data kategori ini ?", function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   $(form).serialize(),
                            beforeSend: function (){
                                App.run_Loader('timer');
                            },
                            success: function( response ){
                                response = $.parseJSON(response);
                                App.close_Loader();
                                
                                if( response.status == 'login' ){
                                    $(location).attr('href',response.message);
                                }else{
                                    if( response.status == 'success'){
                                        var _type = 'success';
                                        var _icon = 'check';
                                        $(form)[0].reset();
                                        $('#btn_posts_list').trigger('click');
                                    }else{
                                        var _type = 'danger';
                                        var _icon = 'warning';
                                    }
                                    App.alert({
                                        type: _type, 
                                        icon: _icon, 
                                        message: response.message, 
                                        container: wrapper, 
                                        place: 'prepend',
                                        closeInSeconds: 5,
                                    });
                                }
                            }
                        });
                    }
                });
            }
        });
    };
    
    return {
        //main function to initiate the module
        init: function () {
            handleValidationCategory();
        },
    };
}();

// ============================================================
// Form Validation Notification
// ============================================================
var FV_Notification = function () {    
    // ---------------------------------
    // Handle Validation Notification
    // ---------------------------------
    var handleValidationUpdateNotification = function() {
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation

        var form        = $('#form_notif_edit');
        var wrapper     = $('.wrapper_notif_edit');
        
        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                notif_id: {
                    required: true
                },
                notif_type: {
                    required: true
                },
                notif_title: {
                    required: true
                },
                notif_status: {
                    required: true
                },
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').length > 0) { 
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').length > 0) { 
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').length > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').length > 0) { 
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                App.alert({
                    type: 'danger', 
                    icon: 'warning', 
                    message: 'Anda memiliki beberapa kesalahan. Silakan cek di formulir bawah ini!', 
                    container: wrapper, 
                    place: 'prepend',
                    closeInSeconds: 5,
                });
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                $(element).closest('.help-block').remove();
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
                label.closest('.help-block').remove();
            },
            submitHandler: function (form) {
                var url             = $(form).attr('action');
                var notif_id        = $('input[name=notif_id]', $(form)).val();
                var notif_type      = $('input[name=notif_type]', $(form)).val();
                var notif_title     = $('input[name=notif_title]', $(form)).val();
                var notif_status    = $('select[name=notif_status]', $(form)).val();
                var content_plain   = $('textarea[name=notif_content_plain]', $(form)).val();
                var content_email   = CKEDITOR.instances['notif_content_email'].getData();

                var data = {
                    'notif_id'      : notif_id,
                    'notif_type'    : notif_type,
                    'notif_title'   : notif_title,
                    'notif_status'  : notif_status,
                    'content_plain' : content_plain,
                    'content_email' : content_email
                }

                bootbox.confirm('Apakah anda yakin akan edit Notifikasi ini ?', function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   data,
                            beforeSend: function (){
                                App.run_Loader('timer');
                            },
                            success: function( response ){
                                App.close_Loader();
                                response = $.parseJSON(response);
                                if(response.status == 'login'){
                                    $(location).attr('href',response.login);
                                    return false;
                                }else{
                                    if(response.status == 'success'){
                                        var type = 'success';
                                        var icon = 'check';
                                        wrapper  = $('#notification_list').parents('.dataTables_wrapper');
                                        $('#modal-form-notification').modal('hide');
                                        $('#btn_notification_list').trigger('click');
                                    }else{
                                        var type = 'danger';
                                        var icon = 'warning';
                                    }
                                    App.alert({
                                        type: type,
                                        icon: icon,
                                        message: response.message,
                                        container: wrapper,
                                        closeInSeconds: 3,
                                        place: 'prepend'
                                    });
                                    return false;
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ) {
                                App.close_Loader();
                                bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.');
                            }
                        });
                    }
                });
            }
        });
    };
    
    return {
        //main function to initiate the module
        init: function () {
            handleValidationUpdateNotification();
        },
    };
}();

// ============================================================
// Form Validation Setting Withdraw
// ============================================================
var FV_SettingWithdraw = function () {    
    // ---------------------------------
    // Handle Validation Setting Withdraw
    // ---------------------------------
    var handleValidationSettingWithdraw = function() {
        var form        = $( '#form-setting-wd' );
        var wrapper     = $( '.wrapper-setting-withdraw' );

        if ( ! form.length ) {
            return;
        }
        
        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                wd_min: {
                    required: true,
                },
                wd_fee: {
                    required: true,
                },
                wd_tax: {
                    required: true,
                },
                wd_tax_npwp: {
                    required: true,
                },
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit 
                App.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: 'Anda memiliki beberapa kesalahan. Silakan cek di formulir bawah ini!',
                    container: form,
                    place: 'prepend'
                });
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url         = $(form).attr('action');
                bootbox.confirm("Anda yakin akan edit data setting Withdraw ?", function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   $(form).serialize(),
                            beforeSend: function (){
                                App.run_Loader('timer');
                            },
                            success: function( response ){
                                App.close_Loader();
                                response = $.parseJSON(response);
                                var alert_type = 'danger';
                                var alert_icon = 'fa fa-exclamation-triangle';
                                if ( response.status == 'login' ) {
                                    $(location).attr('href', response.url);
                                }
                                if ( response.status == 'success' ) {
                                    alert_type = 'success';
                                    alert_icon = 'fa fa-check';
                                }
                                App.notify({
                                    icon: alert_icon, 
                                    title: '', 
                                    message: response.message, 
                                    type: alert_type
                                });
                            },
                            error: function( jqXHR, textStatus, errorThrown ) {
                                App.close_Loader();
                                App.notify({
                                    icon: 'fa fa-exclamation-triangle', 
                                    title: 'Failed', 
                                    message: 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', 
                                    type: 'danger',
                                });
                            }
                        });
                    }
                });
            }
        });
    };
    
    return {
        //main function to initiate the module
        init: function () {
            handleValidationSettingWithdraw();
        },
    };
}();

// ============================================================
// Form Validation Staff
// ============================================================
var FV_Staff = function () {    
    // ---------------------------------
    // Handle Validation Staff
    // ---------------------------------
    var handleValidationStaff = function() {
        var form            = $('#form-staff');
        var wrapper         = $('.wrapper-form-staff');

        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                staff_username: {
                    minlength: 5,
                    required: true,
                    unamecheck: true,
                    /*
                    remote: {
                        url: $("#staff_username").data('url'),
                        type: "post",
                        data: {
                            [App.kdName()]: function() {
                                return App.kdToken();
                            },
                            username: function() {
                                return $("#staff_username").prop( 'readonly' ) ? '' : $("#staff_username").val();
                            }
                        },
                        dataFilter: function(response) {
                            response = $.parseJSON(response);
                            if ( response.token ) {
                                App.kdToken(response.token);
                            }
                            return response.status;
                        }
                    }
                    */
                },
                staff_password: {
                    minlength: 6,
                    required: true,
                    pwcheck: true,
                },
                staff_password_confirm: {
                    required: true,
                    equalTo: '#staff_password'
                },
                staff_name: {
                    minlength: 3,
                    required: true,
                    lettersonly: true,
                },
                staff_phone: {
                    minlength: 8,
                    required: true,
                    /*
                    remote: {
                        url: $("#staff_phone").data('url'),
                        type: "post",
                        data: {
                            [App.kdName()]: function() {
                                return App.kdToken();
                            },
                            phone: function() {
                                return $("#staff_phone").prop( 'readonly' ) ? '' : $("#staff_phone").val();
                            }
                        },
                        dataFilter: function(response) {
                            response = $.parseJSON(response);
                            if ( response.token ) {
                                App.kdToken(response.token);
                            }
                            return response.status;
                        }
                    }
                    */
                },
                staff_email: {
                    email: true,
                    required: true,
                    /*
                    remote: {
                        url: $("#staff_email").data('url'),
                        type: "post",
                        data: {
                            [App.kdName()]: function() {
                                return App.kdToken();
                            },
                            email: function() {
                                return $("#staff_email").prop( 'readonly' ) ? '' : $("#staff_email").val();
                            }
                        },
                        dataFilter: function(response) {
                            response = $.parseJSON(response);
                            if ( response.token ) {
                                App.kdToken(response.token);
                            }
                            return response.status;
                        }
                    }
                    */
                }
            },
            messages: {
                staff_username: {
                    remote: "Username sudah digunakan. Silahkan gunakan username lain",
                },
                staff_password_confirm: {
                    equalTo: "Password konfirmasi tidak cocok dengan password yang di atas",
                },
                staff_email: {
                    remote: "Email sudah digunakan. Silahkan gunakan email lain",
                },
                staff_phone: {
                    remote: "No. Telp/HP sudah digunakan. Silahkan gunakan No. Telp/HP lain",
                }
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').length > 0) { 
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').length > 0) { 
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').length > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').length > 0) { 
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                App.alert({
                    type: 'danger', 
                    icon: 'bell', 
                    message: 'Ada beberapa error, silahkan cek formulir di bawah!', 
                    container: wrapper, 
                    place: 'prepend'
                });
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url             = $(form).attr('action');
                bootbox.confirm("Apakah anda yakin akan simpan data Staff ini ?", function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   $(form).serialize(),
                            beforeSend: function (){
                                App.run_Loader('timer');
                            },
                            success: function( response ){
                                response = $.parseJSON(response);
                                App.close_Loader();

                                if ( response.token ) {
                                    App.kdToken(response.token);
                                }
                                
                                if( response.status == 'access_denied' ){
                                    $(location).attr('href',response.url);
                                }else{
                                    if( response.status == 'success'){
                                        var _type = 'success';
                                        var _icon = 'check';
                                        $(form)[0].reset();
                                        if ( response.url ) {
                                            setTimeout(function(){ $(location).attr('href', response.url); }, 1000);
                                        }
                                    }else{
                                        var _type = 'danger';
                                        var _icon = 'warning';
                                    }
                                    App.alert({
                                        type: _type, 
                                        icon: _icon, 
                                        message: response.message, 
                                        container: wrapper, 
                                        place: 'prepend',
                                        closeInSeconds: 5,
                                    });
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ) {
                                App.close_Loader();
                                bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.');
                            }
                        });
                    }
                });
            }
        });

        $.validator.addMethod("pwcheck", function(value) {
            return /[a-z].*[0-9]|[0-9].*[a-z]/i.test(value); // consists of only these
        }, "Password harus terdiri dari huruf dan angka" );
        
        $.validator.addMethod("unamecheck", function(value) {
            return /^[A-Za-z0-9]{4,16}$/i.test(value);   // consists of only these
        }, "Username tidak memenuhi kriteria" );
        
        $.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
        }, "Silahkan inputkan Nama dengan huruf saja" );
    };
    
    return {
        //main function to initiate the module
        init: function () {
            handleValidationStaff();
        },
    };
}();