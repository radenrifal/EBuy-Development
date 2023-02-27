// ===========================================================
// GLOBAL FUNTION
// ===========================================================
$(function(){    
    $('.btn-tooltip').tooltip({
        html:true
    });

    // Show / Hide Password
    // -----------------------------------------------
    $("body").delegate( ".pass-show-hide", "click", function( event ) {
        event.preventDefault();
        var parent  = $(this).parent().parent();
        var icon    = $(this).children();
        if ( ! parent.length ) { return; }
        if ( ! icon.length ) { return; }
        var input   = parent.children('input');
        if ( ! input.length ) { return; }
        var type    = input.attr('type');
        if (type === "password") {
            type = "text";
            icon.removeClass('fa-eye-slash');
            icon.addClass('fa-eye');
        } else {
            type = "password";
            icon.removeClass('fa-eye');
            icon.addClass('fa-eye-slash');
        }
        input.attr('type',type);
        return;
    });
});

var popupManager = function  (url) {
    var w = 880;
    var h = 570;
    var l = Math.floor((screen.width-w)/2);
    var t = Math.floor((screen.height-h)/2);
    window.open(url, 'Media', "scrollbars=1,width=" + w + ",height=" + h + ",top=" + t + ",left=" + l);
}

// ===========================================================
// Check Json
// ===========================================================
var isJson = function(str) {
    try {
        $.parseJSON(str);
    } catch (e) {
        return false;
    }
    return true;
};

// ===========================================================
// Read Url File
// ===========================================================
var readURL = function(input, img_id, video_id = '') {
    if (input[0].files && input[0].files[0]) {
        var typeFile    = input[0].files[0].type;
        var sizeFile    = input[0].files[0].size;
        var _size       = Math.round(sizeFile/1024);
        var _type       = 'image';
        if ( typeFile ) {
            _type       = typeFile.substr(0, typeFile.indexOf('/')); 
        }
        $('.img-information').show();

        var reader = new FileReader();
        reader.onload = function (e) {
            if ( _type == 'video' && video_id ) {
                video_id.attr('src', e.target.result);
                video_id.show();
                img_id.hide();
                img_id.attr('src', '');
            } else {
                img_id.attr('src', e.target.result);
                img_id.show();
                if ( video_id ) {
                    video_id.hide();
                    video_id.attr('src', '');
                }
            }

            if ( $('#size_img_thumbnail').length ) {
                if ( _size > 1024 ) {
                    _size = Math.round(_size/1024);
                    _size = _size + ' MB';
                } else {
                    _size = _size + ' KB';
                }
                $('#size_img_thumbnail').text(_size);
            }
        }

        reader.readAsDataURL(input[0].files[0]);
    }
};

var imageReadURL = function(img_url = '', img_id = '') {
    if ( img_url && img_id ) {
        img_id.attr('src', img_url);
    }
};

// CUrrency Format Function
// --------------------------------------------------------------------------
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

// ===========================================================
// Tinymce
// ===========================================================
var TinymceText = function() {
    return {
        init: function() {
            tinymce.init({
                selector: '#tinymce',
                plugins: [
                     "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                     "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
                     "table contextmenu directionality paste textcolor responsivefilemanager code"
                 ],
                 toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | responsivefilemanager image media | link unlink anchor  | forecolor backcolor  | print preview code ",
                 // toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
                 image_advtab: true,
                 external_filemanager_path:"http://web.local/filemanager/",
                 filemanager_title:"Responsive Filemanager",
                 external_plugins: { "filemanager" : "http://web.local/filemanager/plugin.min.js"}
            });
        }
    };
}();

// ===========================================================
// Input Mask
// ===========================================================
var InputMask = function() {
    var handleInputMask = function() {
        $(".numbermask").inputmask({
            "mask": "9",
            "repeat": 30,
            "greedy": false
        });
        $( '.numbercurrency').inputmask("currency", {
            prefix: "",
            radixPoint: ",",
            groupSeparator: ".",
            placeholder: "0",
            digits: 0,
            rightAlign: 0
        });
        $( '.numberdecimal').inputmask("decimal", {
            prefix: "",
            radixPoint: ",",
            digits: 2,
            groupSeparator: ".",
            rightAlign: 0
        });
        $( '.numberpercent').inputmask("percentage", {
            radixPoint: ",",
            digits: 2,
            groupSeparator: ".",
            rightAlign: 0
        });
        $(".npwp").inputmask("99\.999\.999\.9-999\.999");

        $("input.phonenumber").keyup(function () {
            if (this.value.substring(0, 1) == "0") {
                this.value = this.value.replace(/^0+/g, "");
            }
        });
    };

    var handleInputClick = function() {
        // Edit Link
        $("body").delegate( ".btn-edit-link", "click", function( event ) {
            event.preventDefault();
            if ( $('.slug-link').length ) {
                $('.slug-link').removeAttr('disabled');
                $('.slug-link').focus();
            }
        });

        // Remove Image
        $("body").delegate( ".btn-remove-image", "click", function( event ) {
            event.preventDefault();
            var src = $(this).data('url');
            if ( src ) {
                imageReadURL(src, $('#view_image'));
                $(this).hide();
                if ( $('#post_image').length ) {
                    $('#post_image').val('');
                }
            }
        });
    };

    return {
        init: function() {
            handleInputMask();
            handleInputClick();
            if ( $("input.phonenumber").length ) {
                $( ':input.phonenumber' ).each( function() {
                    if (this.value.substring(0, 1) == "0") {
                        this.value = this.value.replace(/^0+/g, "");
                    }
                });
            }
        }
    };
}();

// ===========================================================
// iCheck Input
// ===========================================================
var iCheckInput = function() {
    var handleiCheckInput = function() {
        $('input[type="checkbox"].icheck-min, input[type="radio"].icheck-min').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass   : 'iradio_flat-blue'
        });
    };

    return {
        init: function() {
            handleiCheckInput();
        }
    };
}();

// ===========================================================
// Button Action
// ===========================================================
var ButtonAction = function() {
    // Handle Button Delete 
    var handleActionDelete = function() {
        // Delete List Data
        $("body").delegate( ".btn-delete-data", "click", function( event ) {
            event.preventDefault();
            var url         = $(this).attr('href');
            var btn_list    = $(this).data('btn-list');
            var msg_list    = $(this).data('message');
            var wrapper     = $('.content');
            var msg         = 'Anda yakin akan hapus data ini ?';
            if ( msg_list ) {
                msg         = msg_list;
            }

            bootbox.confirm(msg, function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('timer');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);

                            if( response.status == 'login' ){
                                $(location).attr('href',response.message);
                            }else{
                                if( response.status == 'success'){
                                    var _type = 'success';
                                    var _icon = 'check';
                                    if ( btn_list ) {
                                        $('#' + btn_list).trigger('click');
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
                                    closeInSeconds: 3,
                                });
                            }
                            return false;
                        },
                        error: function( jqXHR, textStatus, errorThrown ) {
                            App.close_Loader();
                            bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.');
                        }
                    });
                }
            });

            return false;
        });
    };

    // Handle Button Confirm
    var handleActionConfirm = function() {

        // Register Member Confirm
        $("body").delegate( "a.btn-member-confirm", "click", function( event ) {
            event.preventDefault();
            var url             = $(this).data('url');
            var username        = $(this).data('username');
            var name            = $(this).data('name');
            var nominal         = $(this).data('nominal');

            var msg_body    = `
                <h4 class="pt-4 pb-3 text-center">Apakah anda yakin akan Konfirmasi Pendaftaran Agen ini ?</h4>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Username :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Username :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${username} </small></div>
                </div>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Nama :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Nama :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${name} </small></div>
                </div>
                <hr class="my-3">
                <div class="row align-items-center">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Jumlah Transfer :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Jumlah Transfer :</small></div>
                    <div class="col-sm-6"><small class="heading-title text-warning font-weight-bold"> ${nominal} </small></div>
                </div>
                <hr class="mt-2 mb-3">
                <div class="row justify-content-center">
                    <form class="form-horizontal" id="form-member-confirm">
                        <div class="form-group mb-1">
                            <div class="col-md-12">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Password Konfirmasi" autocomplete="off" value="" />
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>`;

            bootbox.confirm({
                title: "",
                message: msg_body,
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn-danger'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Confirm',
                        className: 'btn-primary'
                    }
                },
                callback: function (result) {
                    if( result == true ){

                        var data = {};
                        var password = '';

                        if ($('#password_confirm', '#form-member-confirm').length) {
                            password = $('#password_confirm', '#form-member-confirm').val();
                            data.password = password;
                        }

                        if (password == "" || password == undefined) {
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                title: 'Failed', 
                                message: 'Password harus diisi !', 
                                type: 'warning',
                            });
                            $('#password_confirm').focus();
                            return false;
                        }

                        $.ajax({
                            type:   "POST",
                            data:   data,
                            url:    url,
                            beforeSend: function (){
                                App.run_Loader('timer');
                            },
                            success: function( response ){  
                                App.close_Loader();
                                response    = $.parseJSON(response);
                                if( response.status == 'login'){
                                    $(location).attr('href',response.url);
                                } else {
                                    if( response.status == 'success'){
                                        var _type = 'success';
                                        var _icon = 'fa fa-check';
                                        $('#btn_list_table_member').trigger('click');
                                    }else{
                                        var _type = 'danger';
                                        var _icon = 'fa fa-exclamation-circle';
                                    }

                                    App.notify({
                                        icon: _icon, 
                                        message: response.message, 
                                        type: _type,
                                    });
                                }
                                return false;
                            },
                            error: function( jqXHR, textStatus, errorThrown ) {
                                App.close_Loader();
                                App.notify({
                                    icon: 'fa fa-exclamation-circle', 
                                    message: 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', 
                                    type: 'warning',
                                });
                            }
                        });
                    }
                }
            });
        });

        // As Member Confirm  
        $("body").delegate( "a.asconfirm", "click", function( event ) {
            event.preventDefault();
            var url             = $(this).attr('href');
            var name            = $(this).data('name');
            var username        = $(this).data('username');
            var table_container = $('#registration_list').parents('.dataTables_wrapper');

            var msg  = 'Anda yakin akan konfirmasi member [<b>'+username+'</b>] ' + name + ' ?';
            bootbox.confirm(msg, function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('timer');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);

                            if( response.status == 'login' ){
                                $(location).attr('href',response.message);
                            }else{
                                if( response.status == 'success'){
                                    App.alert({
                                        type: 'success',
                                        icon: 'check',
                                        message: response.message,
                                        container: table_container,
                                        place: 'prepend',
                                        closeInSeconds: 3,
                                    });
                                    $('button#btn_registration_list').trigger('click');
                                }else{
                                    App.alert({
                                        type: 'danger',
                                        icon: 'warning',
                                        message: response.message,
                                        container: table_container,
                                        place: 'prepend',
                                        closeInSeconds: 3,
                                    });
                                }
                            }
                            return false;
                        }
                    });
                }
            });
        });

        // As Stockist
        $("body").delegate( "a.asstockist", "click", function( event ) {
            event.preventDefault();
            App.run_Loader('timer');
            var url         = $(this).attr('href');
            var id          = $(this).data('id');
            var name        = $(this).data('name');
            var username    = $(this).data('username');
            var type        = $(this).data('type');
            var sponsor     = $(this).data('sponsor');
            var province    = $(this).data('province');
            var city        = $(this).data('city');
            var container   = $(this).data('container');

            $('.change-stockist', '#modal_select_stockist').val('');
            $('.change-stockist-name').val(name);
            $('.change-stockist-username').val(username);
            $('.change-stockist-sponsor').val(sponsor);
            $('.change-stockist-container').val(container);
            $('select[name="select_stockist"]').val(type);
            $('select[name="stockist_province"]').val(province);
            $('#stockist_province').trigger('change');
            setTimeout(function(){ 
                $('select[name="stockist_city"]').val(city);
                App.close_Loader(); 
            }, 1500);
            $('#asmember').val(id);
            $('#alert_form_stockist').hide();
            $('#modal_select_stockist').modal('show');
        });

        // As Stockist
        $("body").delegate( "#do_save_asstockist", "click", function( event ) {
            event.preventDefault();
            var form        = $('#form_select_stockist');
            var url         = $(form).attr('action');
            var id          = $('input[name="asmember"]', form).val();
            var container   = $('input[name="ascontainer"]', form).val();
            var province    = $('select[name="stockist_province"]', form).val();
            var city        = $('select[name="stockist_city"]', form).val();
            var type        = $('select[name="select_stockist"]', form).val();
            var tProv       = $('select[name="stockist_province"] option:selected').text();
            var tCity       = $('select[name="stockist_city"] option:selected').text();
            var tStockist   = $('select[name="select_stockist"] option:selected').text();
            var table_container = $('#'+container).parents('.dataTables_wrapper');
            var msg         = $('#alert_form_stockist');

            if ( !province ) {
                alert('Silahkan Pilih Provinsi');
                return true;
            }

            if ( !city ) {
                alert('Silahkan Pilih Kota/Kabupaten');
                return true;
            }

            url             = url+id;
            $('#modal_select_stockist').modal('hide');

            var tconfim = 'Anda yakin akan merubah member ini menjadi '+tStockist+' di PROVINSI ' + tProv + ' ' + tCity + ' ?';
            bootbox.confirm(tconfim, function(result) {
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

                            if( response.status == 'login' ){
                                $(location).attr('href',response.message);
                            }else{
                                if( response.status == 'error'){
                                    $('#modal_select_stockist').modal('show');
                                    msg.html('<button class="close" data-close="alert" type="button"><i class="fa fa-times"></i></button>'+response.message);
                                    msg.removeClass('alert-success').addClass('alert-danger').fadeIn('fast');
                                }else{
                                    App.alert({
                                        type: 'success',
                                        icon: 'check',
                                        message: response.message,
                                        container: table_container,
                                        place: 'prepend',
                                        closeInSeconds: 3,
                                    });

                                    $('#btn_member_lists').trigger('click');
                                    $('#btn_member_stockist_list').trigger('click');
                                }
                            }
                            return false;
                            
                        }
                    });
                } else {
                    $('#modal_select_stockist').modal('show');
                }
            });
        });

        // As Member
        $("body").delegate( "a.asmember", "click", function( event ) {
            event.preventDefault();
            var url = $(this).attr('href');
            var container   = $(this).data('container');

            bootbox.confirm("Anda yakin akan merubah member ini menjadi member biasa?", function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('timer');
                        },
                        success: function( response ){
                            App.close_Loader();
                            if ( container == 'member_stockist_list') { $('#btn_member_stockist_list').trigger('click'); }
                            $('#btn_member_lists').trigger('click');
                        }
                    });
                }
            });
        });

        // As Banned
        $("body").delegate( "a.asbanned", "click", function( event ) {
            event.preventDefault();
            var url = $(this).attr('href');
            var container   = $(this).data('container');

            bootbox.confirm("Anda yakin akan Banned member ini?", function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('timer');
                        },
                        success: function( response ){
                            App.close_Loader();
                            if ( container == 'registration_list')    { $('#btn_member_lists').trigger('click'); }
                            if ( container == 'member_stockist_list') { $('#btn_member_stockist_list').trigger('click'); }
                            $('#btn_member_banned_list').trigger('click');
                        }
                    });
                }
            });
        });

        // As Active
        $("body").delegate( "a.asactive", "click", function( event ) {
            event.preventDefault();
            var url = $(this).attr('href');
            
            bootbox.confirm("Anda yakin akan aktifkan status member ini?", function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('timer');
                        },
                        success: function( response ){
                            App.close_Loader();
                            $('#btn_member_banned_list').trigger('click'); 
                            $('#btn_member_lists').trigger('click');
                        }
                    });
                }
            });
        });

        // Topup Ewallet Confirm
        $("body").delegate( "a.ewallettopupconfirm", "click", function( event ) {
            event.preventDefault();
            var url         = $(this).attr('href');
            var username    = $(this).data('username');
            var name        = $(this).data('name');
            var transfer    = $(this).data('transfer');
            var nominal     = $(this).data('nominal');
            var unique      = $(this).data('unique');
            var container   = $(this).data('container');
            var message     = $(this).data('message');

            var table_container = $('#'+container).parents('.dataTables_wrapper');

            var msg_body    = `<form class="form-horizontal" id="form-topup-saldo-confirm">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">ID Anggota</label>
                                    <div class="col-md-6">
                                        <input type="text" value="`+username+`" class="form-control" readonly="readonly" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Nama</label>
                                    <div class="col-md-6">
                                        <input type="text" value="`+name+`" class="form-control" readonly="readonly" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Nominal Topup</label>
                                    <div class="col-md-6">
                                        <input type="text" value="`+nominal+`" class="form-control" readonly="readonly" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Kode Unik</label>
                                    <div class="col-md-6">
                                        <input type="text" value="`+unique+`" class="form-control" readonly="readonly" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Jumlah Transfer</label>
                                    <div class="col-md-6">
                                        <input type="text" value="`+transfer+`" class="form-control" readonly="readonly" />
                                    </div>
                                </div>
                                <br><br><div class="form-group">
                                    <label class="col-md-4 control-label">Password</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="password" name="pwd" id="pwd" class="form-control" placeholder="Password Konfirmasi" autocomplete="off">
                                            <span class="input-group-btn">
                                                <button class="btn btn-flat btn-white pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                </form>`;

            bootbox.confirm({
                title: message,
                message: msg_body,
                buttons: {
                    cancel: {
                        label: 'Kembali'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Konfirmasi'
                    }
                },
                callback: function (result) {
                    if( result == true ){
                        var password = '';

                        if ( $('#pwd', '#form-topup-saldo-confirm').length ) {
                            password = $('#pwd').val();
                        }

                        if ( password == "" || password == undefined ) {
                            App.alert({
                                type: 'danger', 
                                icon: 'warning', 
                                message: 'Password harus di isi !', 
                                container: $('#form-topup-saldo-confirm'), 
                                place: 'prepend'
                            });
                            $('#pwd').focus();
                            return false;
                        }

                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   { password: password },
                            beforeSend: function (){
                                App.run_Loader('timer');
                            },
                            success: function( response ){
                                App.close_Loader();
                                response    = $.parseJSON(response);

                                if( response.status == 'login' ){
                                    $(location).attr('href',response.login);
                                }else{
                                    if( response.status == 'success'){
                                        var type = 'success';
                                        var icon = 'check';
                                    }else{
                                        var type = 'danger';
                                        var icon = 'warning';
                                    }

                                    App.alert({
                                        type: type,
                                        icon: icon,
                                        message: response.message,
                                        container: table_container,
                                        place: 'prepend'
                                    });

                                    if( response.status == 'success'){
                                        $('#btn_'+container).trigger('click');
                                    }
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ) {
                                App.close_Loader();
                                bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.');
                            }
                        });
                    }
                }
            });
        });

        // Withdraw Confirm Transfer
        $("body").delegate( "a.withdrawaltransfer", "click", function( event ) {
            event.preventDefault();
            var url             = $(this).attr('href');
            var username        = $(this).attr('username');
            var name            = $(this).attr('name');
            var bank            = $(this).attr('bank');
            var bill            = $(this).attr('bill');
            var billnama        = $(this).attr('billnama');
            var nominal         = $(this).attr('nominal');

            var msg_body    = `
                <h4 class="pt-4 pb-3 text-center">Apakah anda yakin akan konfirmasi withdraw ini ?</h4>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Username :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Username :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${username} </small></div>
                </div>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Nama :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Nama :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${name} </small></div>
                </div>
                <hr class="my-1">
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Bank :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Bank :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${bank} </small></div>
                </div>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">No. Rekening :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">No. Rekening :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${bill} </small></div>
                </div>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Pemilik Rek. :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Pemilik Rek. :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${billnama} </small></div>
                </div>
                <hr class="my-1">
                <div class="row align-items-center">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Jumlah Transfer :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Jumlah Transfer :</small></div>
                    <div class="col-sm-6"><small class="heading-title text-warning font-weight-bold"> ${nominal} </small></div>
                </div>
                <hr class="mt-2 mb-3">
                <div class="row justify-content-center">
                    <form class="form-horizontal" id="form-withdraw-confirm">
                        <div class="form-group mb-1">
                            <div class="col-md-12">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Password Konfirmasi" autocomplete="off" value="" />
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>`;

            bootbox.confirm({
                title: "",
                message: msg_body,
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn-danger'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Confirm',
                        className: 'btn-primary'
                    }
                },
                callback: function (result) {
                    if( result == true ){

                        var data = {};
                        var password = '';

                        if ($('#password_confirm', '#form-withdraw-confirm').length) {
                            password = $('#password_confirm', '#form-withdraw-confirm').val();
                            data.password = password;
                        }

                        if (password == "" || password == undefined) {
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                title: 'Failed', 
                                message: 'Password harus diisi !', 
                                type: 'warning',
                            });
                            $('#password_confirm').focus();
                            return false;
                        }

                        $.ajax({
                            type:   "POST",
                            data:   data,
                            url:    url,
                            beforeSend: function (){
                                App.run_Loader('timer');
                            },
                            success: function( response ){  
                                App.close_Loader();
                                response    = $.parseJSON(response);
                                if( response.status == 'login'){
                                    $(location).attr('href',response.url);
                                } else {
                                    if( response.status == 'success'){
                                        var _type = 'success';
                                        var _icon = 'fa fa-check';
                                        $('#btn_list_table_withdraw').trigger('click');
                                    }else{
                                        var _type = 'danger';
                                        var _icon = 'fa fa-exclamation-circle';
                                    }

                                    App.notify({
                                        icon: _icon, 
                                        message: response.message, 
                                        type: _type,
                                    });
                                }
                                return false;
                            },
                            error: function( jqXHR, textStatus, errorThrown ) {
                                App.close_Loader();
                                App.notify({
                                    icon: 'fa fa-exclamation-circle', 
                                    message: 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', 
                                    type: 'warning',
                                });
                            }
                        });
                    }
                }
            });
        });

        // Reward Confirm
        $("body").delegate( "a.rewardconfirm", "click", function( event ) {
            event.preventDefault();
            var url             = $(this).data('url');
            var username        = $(this).data('username');
            var name            = $(this).data('name');
            var nominal         = $(this).data('nominal');
            var reward          = $(this).data('reward');

            var msg_body    = `
                <h4 class="pt-4 pb-3 text-center">Apakah anda yakin akan konfirmasi reward ini ?</h4>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Username :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Username :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${username} </small></div>
                </div>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Nama :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Nama :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${name} </small></div>
                </div>
                <hr class="my-2">
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Reward :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Reward :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${reward} </small></div>
                </div>
                <div class="row align-items-center">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Nominal :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Nominal :</small></div>
                    <div class="col-sm-6"><small class="heading-small text-warning font-weight-bold"> ${nominal} </small></div>
                </div>
                <hr class="mt-2 mb-3">
                <div class="row justify-content-center">
                    <form class="form-horizontal" id="form-reward-confirm">
                        <div class="form-group mb-1">
                            <div class="col-md-12">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Password Konfirmasi" autocomplete="off" value="" />
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>`;

            bootbox.confirm({
                title: "",
                message: msg_body,
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn-danger'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Confirm',
                        className: 'btn-primary'
                    }
                },
                callback: function (result) {
                    if( result == true ){

                        var data = {};
                        var password = '';

                        if ($('#password_confirm', '#form-reward-confirm').length) {
                            password = $('#password_confirm', '#form-reward-confirm').val();
                            data.password = password;
                        }

                        if (password == "" || password == undefined) {
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                title: 'Failed', 
                                message: 'Password harus diisi !', 
                                type: 'warning',
                            });
                            $('#password_confirm').focus();
                            return false;
                        }

                        $.ajax({
                            type:   "POST",
                            data:   data,
                            url:    url,
                            beforeSend: function (){
                                App.run_Loader('timer');
                            },
                            success: function( response ){  
                                App.close_Loader();
                                response    = $.parseJSON(response);
                                if( response.status == 'login'){
                                    $(location).attr('href',response.url);
                                } else {
                                    if( response.status == 'success'){
                                        var _type = 'success';
                                        var _icon = 'fa fa-check';
                                        $('#btn_list_table_reward').trigger('click');
                                    }else{
                                        var _type = 'danger';
                                        var _icon = 'fa fa-exclamation-circle';
                                    }

                                    App.notify({
                                        icon: _icon, 
                                        message: response.message, 
                                        type: _type,
                                    });
                                }
                                return false;
                            },
                            error: function( jqXHR, textStatus, errorThrown ) {
                                App.close_Loader();
                                App.notify({
                                    icon: 'fa fa-exclamation-circle', 
                                    message: 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', 
                                    type: 'warning',
                                });
                            }
                        });
                    }
                }
            });
        });
    };

    // Handle Button Confirm
    var handleActionDetail = function() {
        // Daily Omzet Posting Detail 
        $("body").delegate( "a.omzetpostingdailydetail", "click", function( event ) {
            event.preventDefault();

            var url         = $(this).attr('href');
            var id          = $(this).data('id');
            var parentrow   = $(this).parent().parent().parent();
            var el_tr       = $('tr.posting_daily_id_' +  id);
            var wrapper     = $('#omzet_posting_daily_list');

            if( $(el_tr).length ){
                if( $(el_tr).is(':visible') ){
                    $(el_tr).hide();
                }else{
                    $(el_tr).show();
                }
            }else{
                $.ajax({
                    type:   "POST",
                    url:    url,
                    beforeSend: function (){
                        App.run_Loader('timer');
                    },
                    success: function( response ){
                        App.close_Loader();
                        response = $.parseJSON(response);

                        if( response.status == 'login' ){
                            $(location).attr('href',response.message);
                        }else{
                            if( response.status == 'error'){
                                App.alert({
                                    type: 'danger',
                                    icon: 'warning',
                                    message: response.message,
                                    container: wrapper,
                                    place: 'prepend'
                                });
                            }else{
                                parentrow.after(response.detail);
                            }
                        }
                    }
                });
            }

            if( $(this).hasClass('bg-blue') ){
                $(this).removeClass('bg-blue').addClass('btn-danger');
                $(this).find("i").removeClass('fa-plus').addClass('fa-minus');
            }else{
                $(this).removeClass('btn-danger').addClass('bg-blue');
                $(this).find("i").removeClass('fa-minus').addClass('fa-plus');
            }
            return false;
        });
        
        // Monthly Omzet Posting Detail 
        $("body").delegate( "a.omzetpostingmonthlydetail", "click", function( event ) {
            event.preventDefault();

            var url         = $(this).attr('href');
            var id          = $(this).data('id');
            var parentrow   = $(this).parent().parent().parent();
            var el_tr       = $('tr.posting_monthly_id_' +  id);
            var wrapper     = $('#omzet_posting_monthly_list');

            if( $(el_tr).length ){
                if( $(el_tr).is(':visible') ){
                    $(el_tr).hide();
                }else{
                    $(el_tr).show();
                }
            }else{
                $.ajax({
                    type:   "POST",
                    url:    url,
                    beforeSend: function (){
                        App.run_Loader('timer');
                    },
                    success: function( response ){
                        App.close_Loader();
                        response = $.parseJSON(response);

                        if( response.status == 'login' ){
                            $(location).attr('href',response.message);
                        }else{
                            if( response.status == 'error'){
                                App.alert({
                                    type: 'danger',
                                    icon: 'warning',
                                    message: response.message,
                                    container: wrapper,
                                    place: 'prepend'
                                });
                            }else{
                                parentrow.after(response.detail);
                            }
                        }
                    }
                });
            }

            if( $(this).hasClass('bg-blue') ){
                $(this).removeClass('bg-blue').addClass('btn-danger');
                $(this).find("i").removeClass('fa-plus').addClass('fa-minus');
            }else{
                $(this).removeClass('btn-danger').addClass('bg-blue');
                $(this).find("i").removeClass('fa-minus').addClass('fa-plus');
            }
            return false;
        });
    };

    return {
        init: function() {
            handleActionDelete();
            handleActionConfirm();
            handleActionDetail();
        },
    };
}();

// ===========================================================
// Select Change Action
// ===========================================================
var SelectChange = function() {
    // Handle Province Change Function
    // --------------------------------------------------------------------------
    var handleProvinceChange = function() {
        // Province Change
        $('.select_province').change(function(e){
            var val         = $(this).val();
            var url         = $(this).data('url');
            var form        = $(this).data('form');
            var el_dist     = $('.select_district');
            var el_subdist  = $('.select_subdistrict');
            var el_village  = $('.select_village');
            var el_courier  = $('#select_courier');
            var el_service  = $('#select_service');
            var el_propack  = $('#select_product_package');
            var el_packdet  = $('#prod_package_details');

            if ( el_courier.length ) {
                el_courier.val('');
            }

            if ( el_service.length ) {
                el_service.empty();
            }

            if ( url ) {
                $.ajax({
                    type: "POST",
                    data: { 'province' : val },
                    url: url,
                    beforeSend: function (){},
                    success: function( response ){
                        response = $.parseJSON(response);
                        
                        if ( el_dist.length ) {
                            el_dist.empty();
                            if ( response.district != "district") {
                                el_dist.attr('disabled','disabled');
                            }else{
                                el_dist.removeAttr('disabled');
                            }
                            el_dist.html(response.data);
                            el_dist.parent().removeClass('has-danger');
                            el_dist.parent().find('.invalid-feedback').empty().hide();

                        }

                        if ( el_subdist.length ) {
                            el_subdist.empty();
                            if ( response.subdistrict != "" || response.subdistrict != undefined) {
                                el_subdist.html(response.subdistrict);
                            }
                        }

                        if ( el_village.length ) {
                            el_village.empty();
                            if ( response.village != "" || response.village != undefined) {
                                el_village.html(response.village);
                            }
                        }
                        
                        if ( el_propack.length ) {
                            if ( response.district != "district") {
                                el_propack.val("");
                                el_propack.attr('disabled','disabled');
                            }else{
                                el_propack.removeAttr('disabled');
                            }
                        }
                        
                        if( el_packdet.length ){
                            if ( response.district != "district") {
                                el_packdet.empty().hide();
                            }
                        }
                    }
                });
            }
            return false;
        });
    };

    // Handle District Change Function
    // --------------------------------------------------------------------------
    var handleDistrictChange = function() {
        // District Change
        $('.select_district').change(function(e){
            var val         = $(this).val();
            var url         = $(this).data('url');
            var form        = $(this).data('form');
            var el_subdist  = $('.select_subdistrict');
            var el_village  = $('.select_village');
            var el_courier  = $('#select_courier');
            var el_service  = $('#select_service');

            if ( el_courier.length ) {
                el_courier.val('');
            }

            if ( el_service.length ) {
                el_service.empty();
            }

            if ( url ) {
                $.ajax({
                    type: "POST",
                    data: { 'district' : val },
                    url: url,
                    beforeSend: function (){},
                    success: function( response ){
                        response = $.parseJSON(response);
                        if ( el_subdist.length ) {
                            el_subdist.empty();
                            el_subdist.removeAttr('disabled');
                            el_subdist.parent().removeClass('has-danger');
                            el_subdist.parent().find('.invalid-feedback').empty().hide();
                            el_subdist.html(response.data);
                        }

                        if ( el_village.length ) {
                            el_village.empty();
                            if ( response.village != "" || response.village != undefined) {
                                el_village.html(response.village);
                            }
                        }
                    }
                });
            }
            return false;
        });
    };

    // Handle Subdistrict Change Function
    // --------------------------------------------------------------------------
    var handleSubdistrictChange = function() {
        // Subdistrict Change
        $('.select_subdistrict').change(function(e){
            var val         = $(this).val();
            var url         = $(this).data('url');
            var form        = $(this).data('form');
            var el_village  = $('.select_village');
            var el_courier  = $('#select_courier');
            var el_service  = $('#select_service');

            if ( el_courier.length ) {
                el_courier.val('');
            }

            if ( el_service.length ) {
                el_service.empty();
            }

            if ( url ) {
                $.ajax({
                    type: "POST",
                    data: { 'subdistrict' : val },
                    url: url,
                    beforeSend: function (){},
                    success: function( response ){
                        response = $.parseJSON(response);
                        if ( el_village.length ) {
                            el_village.empty();
                            el_village.removeAttr('disabled');
                            el_village.parent().removeClass('has-danger');
                            el_village.parent().find('.invalid-feedback').empty().hide();
                            el_village.html(response.data);
                        }
                    }
                });
            }
            return false;
        });
    };

    // Handle Village Change Function
    // --------------------------------------------------------------------------
    var handleVillageChange = function() {
        // Village Change
        $('#village').change(function(e){
            var val         = $(this).val();
            var url         = $(this).data('url');
            var form        = $(this).data('form');
            var el_housing  = $('#housing');
            var el_newhousing = $('#new_housing');

            $.ajax({
                type: "POST",
                data: { 'village' : val },
                url: url,
                beforeSend: function (){},
                success: function( response ){
                    response = $.parseJSON(response);
                    if ( el_housing.length ) {
                        el_housing.empty();
                        el_housing.parent().removeClass('has-danger');
                        el_housing.parent().find('.invalid-feedback').empty().hide();
                        el_housing.html(response.data);
                        el_housing.trigger('change');
                    }
                    
                    if ( el_newhousing.length ) {
                        el_newhousing.val('');
                    }
                }
            });
            return false;
        });
    };

    // Handle Select Change Group Page
    var handlePageGroupChange = function() {
        // Housing Change
        $('#group_type').change(function(e){
            var val         = $(this).val();
            var el          = $('#group-your-address');
            var input_addr  = $('.your-address');
            if ( val == 'perumahan' ) {
                el.show();
            } else {
                el.hide();
            }

            $('.title-group-address').text('Informasi Alamat ' + val);
            if ( $('.your-address', input_addr).length ) {
                $('.your-address', input_addr).val('');
            }
            return false;
        });
    };

    // Handle Select Change Type iuran
    var handlePageGroupChange = function() {
        // Housing Change
        $('#iuran_type').change(function(e){
            var val         = $(this).val();
            var el          = $('#group-your-address');
            var input_addr  = $('.your-address');
            if ( val == 'perumahan' ) {
                el.show();
            } else {
                el.hide();
            }

            $('.title-group-address').text('Informasi Alamat ' + val);
            if ( $('.your-address', input_addr).length ) {
                $('.your-address', input_addr).val('');
            }
            return false;
        });
    };

    // Handle Select Change Home Page
    var handlePageHomeChange = function() {
        // Housing Change
        $('#housing').change(function(e){
            var val         = $(this).val();
            var el          = $('#input-new_housing');
            var new_housing = $('#new_housing');
            if ( val == 'new' ) {
                el.show();
                new_housing.focus();
            } else {
                el.hide();
            }
            return false;
        });
    };

    return {
        init: function() {
            handleProvinceChange();
            handleDistrictChange();
            handleSubdistrictChange();
            handleVillageChange();
            handlePageGroupChange();
            handlePageHomeChange();
        },
        initGroup: function() {
            handlePageGroupChange();
        },
        initHome: function() {
            handlePageHomeChange();
        }
    };
}();

// ===========================================================
// Search Action
// ===========================================================
var SearchAction = function() {
    // Handle Search Upline Function
    // --------------------------------------------------------------------------
    var handleSearchSponsor = function() {
        // Search Sponsor
        $('#reg_member_sponsor').bind('blur', function(){
            $('#btn_search_sponsor').trigger('click');
        });

        $("body").delegate( "#btn_search_sponsor", "click", function( e ) {
            e.preventDefault();
            var sponsor     = $('#reg_member_sponsor').val();
            var url         = $(this).data('url');
            var el          = $('#sponsor_info');
            var wrapper     = $('.register_body_wrapper');
            var search      = true;

            if ( sponsor == '' ) {
                search      = false;
                $(el).empty().hide();
                $('#reg_member_sponsor').val('');
            }

            if ( $('input[name="reg_member_sponsor_username"]').length ) {
                if ( $('input[name="reg_member_sponsor_username"]').val() == sponsor ) {
                    search  = false;
                }
            }

            if ( search ) {
                $.ajax({
                    type:   "POST",
                    data:   { 'username' : sponsor },
                    url:    url,
                    beforeSend: function (){
                        App.run_Loader('timer');
                    },
                    success: function( response ){
                        App.close_Loader();
                        response = $.parseJSON(response);

                        if( response.status == 'login' ){
                            $(location).attr('href',response.message);
                        }else{
                            if( response.status == 'error'){
                                App.notify({
                                    icon: 'fa fa-exclamation-triangle', 
                                    title: 'Failed', 
                                    message: response.message, 
                                    type: 'danger',
                                });
                                $(el).empty().hide();
                                $('#reg_member_sponsor').val('');
                            }else{
                                App.notify({
                                    icon: 'fa fa-check-circle', 
                                    title: 'Success', 
                                    message: response.message, 
                                    type: 'success',
                                });
                                $(el).html(response.info).fadeIn('fast');
                            }
                        }
                    }
                });
            }
            return false;
        });
    };

    // Handle Search Upline Function
    // --------------------------------------------------------------------------
    var handleSearchGenerationMember = function() {
        // Search Upline
        var form_search = $('#form-search-generation-member');

        if ( ! form_search.length )
            return;

        var url_search  = form_search.data('url');

        $('#search_generation_member').bind('blur', function(){
            var username    = $(this).val();
            var direct      = url_search +'/'+ username;
            if ( username != "" ) {
                $(location).attr('href', direct);
            }
            return false;
        });

        $("body").delegate( form_search, "submit", function( e ) {
            e.preventDefault();
            var username    = $('#search_generation_member').val();
            var direct      = url_search +'/'+ username;
            if ( username != "" ) {
                $(location).attr('href', direct);
            }
            return false;
        });
    };

    return {
        init: function() {
            handleSearchSponsor();
            handleSearchGenerationMember();
        }
    };
}();

// ===========================================================
// Manage Product Function
// ===========================================================
var ProductManage = function() {

    var product_img;

    var total_qty       = 0;
    var total_price     = 0;

    var _trEmpty        = `<tr class="data-empty"><td colspan="5" class="text-center">Produk belum ada yang di pilih.</td></tr>`;

    // ---------------------------------
    // Quill Editor Load
    // ---------------------------------
    var text_editor         = $('#editor');
    var placeholder_editor  = $('#editor').data("quill-placeholder");
    if ( text_editor.length ) {
        var quill_editor    = new Quill('#editor', {
            modules: {
                toolbar: [
                    ["bold", "italic"],
                    ["link", "blockquote", "code"],
                    [{list: "ordered"}, {list: "bullet"}]
                ]
            },
            placeholder: placeholder_editor,
            theme: "snow"
        });
    }

    // ---------------------------------
    // Handle General Product Manage
    // ---------------------------------
    var handleGeneralProductManage = function() {
        $('#product_img_thumbnail').on('click', function(e) {
            $('.file-image').trigger('click');
        });

        $('#product_file, .file-image').on('change', function(e) {
            readURL( $(this), $('#product_img_thumbnail') );
            product_img = e.target.files;
        });

        $('#btn-modal-category').on('click', function(e) {
            $('#modal-add-category').modal('show');
        });
        
        $('#discount_agent_type').on('change', function(e) {
            var val = $(this).val();
            if ( val == 'percent' ) {
                $('#discount_agent').removeClass('numbercurrency');
                $('#discount_agent').addClass('numberpercent');
                $('.label_discount_agent').text('Jumlah (%)');
            } else {
                $('#discount_agent').removeClass('numberpercent');
                $('#discount_agent').addClass('numbercurrency');
                $('.label_discount_agent').text('Jumlah (Rp)');
            }
            InputMask.init();
        });

        $('#discount_customer_type').on('change', function(e) {
            var val = $(this).val();
            if ( val == 'percent' ) {
                $('#discount_customer').removeClass('numbercurrency');
                $('#discount_customer').addClass('numberpercent');
                $('.label_discount_customer').text('Jumlah (%)');
            } else {
                $('#discount_customer').removeClass('numberpercent');
                $('#discount_customer').addClass('numbercurrency');
                $('.label_discount_customer').text('Jumlah (Rp)');
            }
            InputMask.init();
        });

        $('#discount_agent_type').trigger('change');
        $('#discount_customer_type').trigger('change');

        // Button Edit Status Product Data
        // -----------------------------------------------
        $("body").delegate( "a.btn-status-product", "click", function( e ) {
            e.preventDefault();
            var url         = $(this).attr('href');
            var product     = $(this).data('product');
            var status      = $(this).data('status');
            var msg_title   = (status == '1') ? 'Apakah anda yakin akan Meng-Nonaktifkan Produk Ini ?' : 'Apakah anda yakin akan Meng-Aktifkan Produk Ini ?';

            var msg_body    = `
                <div class="row pt-5 align-items-center">
                    <div class="col-sm-12">
                        <h3 class="heading mb-3 text-center">`+ msg_title +`</h3>
                    </div>
                    <div class="col-sm-12 text-center">
                        <small class="text-uppercase text-muted font-weight-bold">Produk : </small>
                        <h2 class="heading-title text-primary mb-0">`+ product +`</h2>
                    </div>
                </div>`;
            bootbox.confirm(msg_body, function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('timer');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    $('#btn_list_table_product').trigger('click');
                                }else{
                                    App.notify({
                                        icon: 'fa fa-exclamation-triangle', 
                                        title: 'Failed', 
                                        message: response.message, 
                                        type: 'danger',
                                    });
                                }
                            }
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
        });

        // Button Delete Product Data
        // -----------------------------------------------
        $("body").delegate( "a.btn-delete-product", "click", function( e ) {
            e.preventDefault();
            var url         = $(this).data('url');
            var product     = $(this).data('product');

            var msg_body    = `
                <div class="row pt-5 align-items-center">
                    <div class="col-sm-12">
                        <h3 class="heading mb-3 text-center">Apakah anda yakin akan Meng-Hapus Produk Ini ?</h3>
                    </div>
                    <div class="col-sm-12 text-center">
                        <small class="text-uppercase text-muted font-weight-bold">Produk : </small>
                        <h2 class="heading-title text-primary mb-0">`+ product +`</h2>
                    </div>
                </div>`;
            bootbox.confirm(msg_body, function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('timer');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    $('#btn_list_table_product').trigger('click');
                                }else{
                                    App.notify({
                                        icon: 'fa fa-exclamation-triangle', 
                                        title: 'Failed', 
                                        message: response.message, 
                                        type: 'danger',
                                    });
                                }
                            }
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
        });
    };

    // ---------------------------------
    // Handle Validation Product Manage
    // ---------------------------------
    var handleValidationProductManage = function() {
        var form            = $('#form-product');
        var wrapper         = $('.wrapper-form-product');

        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                product_name: {
                    minlength: 3,
                    required: true
                },
                product_category: {
                    required: true
                },
                price_agent: {
                    required: true
                },
                price_customer: {
                    required: true
                },
                product_point: {
                    required: true
                },
                min_order: {
                    required: true,
                    min: 1
                },
                weight: {
                    required: true
                },
            },
            messages: {
                product_name: {
                    required: "Nama Produk harus di isi !",
                    minlength: "Minimal 3 karakter"
                },
                category: {
                    required: "Kategori Produk harus di pilih !",
                },
                price_agent: {
                    required: "Harga Agen harus di isi !",
                },
                price_customer: {
                    required: "Harga Konsumen harus di isi !",
                },
                product_point: {
                    required: "Produk Poin harus di isi !",
                },
                min_order: {
                    required: "Minimal Order Agen harus di isi !",
                },
                weight: {
                    required: "Berat Produk harus di isi !",
                },
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').length) { 
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').length) { 
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').length) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').length) { 
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                App.alert({
                    type: 'danger', 
                    icon: 'exclamation-triangle', 
                    message: 'Ada beberapa error, silahkan cek formulir di bawah!', 
                    container: wrapper, 
                    closeInSeconds: 5,
                    place: 'prepend'
                }); 
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-danger'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-danger'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-danger'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url         = $(form).attr('action');
                var data        = new FormData();
                var description = quill_editor.root.innerHTML;

                // get inputs
                $('textarea.form-control, select.form-control, input.form-control',  $(form)).each(function(){
                    data.append($(this).attr("name"), $(this).val());
                });
            
                if (description) {
                    data.append('description', description);
                }
            
                if (product_img) {
                    $.each(product_img, function(key, value){
                        data.append('product_img', value);
                    });
                }

                bootbox.confirm("Apakah anda yakin akan simpan data produk ini ?", function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   data,
                            processData:false,
                            contentType:false,
                            cache:false,
                            async:false,
                            beforeSend: function (){
                                App.run_Loader('timer');
                            },
                            success: function( response ){
                                App.close_Loader();
                                response = $.parseJSON(response);
                                
                                if( response.status == 'access_denied' ){
                                    $(location).attr('href',response.url);
                                }else{
                                    if( response.status == 'success'){
                                        App.notify({
                                            icon: 'fa fa-check-circle', 
                                            title: 'Success', 
                                            message: response.message, 
                                            type: 'success',
                                        });
                                        $(form)[0].reset();
                                        setTimeout(function(){ $(location).attr('href',response.url); }, 1500);
                                    }else{
                                        App.notify({
                                            icon: 'fa fa-exclamation-triangle', 
                                            title: 'Failed', 
                                            message: response.message, 
                                            type: 'danger',
                                        });
                                    }
                                }
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

    // ---------------------------------
    // Handle General Product Package
    // ---------------------------------
    var handleGeneralProductPackage = function() {
        $('#product_img_thumbnail').on('click', function(e) {
            $('.file-image').trigger('click');
        });

        $('#product_file, .file-image').on('change', function(e) {
            readURL( $(this), $('#product_img_thumbnail') );
            product_img = e.target.files;
        });

        // Input Qty Product Package 
        // -----------------------------------------------
        $('#package_qty').bind('blur', function(){
            updateQtyAllProductPackage();
        });

        // -----------------------------------------------
        $('#package_mix').bind('change', function(){
            var _form       = $('#form-product-package');
            var _table          = $('#list_table_product_package');
            var _tbody          = $('tbody', _table);
            var _tr_product     = $('.tr-input-product', _tbody);
            var _total_product  = parseInt(_tr_product.length);

            if ( $(':input[name=package_mix]', _form).prop("checked") == false ) {
                $('.mix').show();
                $('.input-lock-qty').hide();
                if( _total_product > 1 ) {
                    total_price = 0;
                    _tr_product.each(function(index){
                        if ( parseInt(index) > 0 ) {
                            $(this).remove();
                        }
                    });
                    updateQtyAllProductPackage();
                }
            } else {
                $('.input-lock-qty').show();
                $('.mix').hide();
            }
            return false;
        });
        
        // -----------------------------------------------
        $('#lock_qty').bind('change', function(){
            if ( $(':input[name=lock_qty]').prop("checked") == true ) {
                $('.none-mix').show();
            } else {
                $('.none-mix').hide();
            }
            updateQtyAllProductPackage();
            return false;
        });

        // Button Add Product Package 
        // -----------------------------------------------
        $("body").delegate( "#select_product", "change", function( e ) {
            e.preventDefault();
            var _form       = $('#form-product-package');
            var product     = $(this).val();

            if ( product ) {
                addProductPackage();
            } else {
                bootbox.alert('Produk belum di pilih !');
            }
            $('#select_product', _form).val('');
            App.scrollTo($('#package_qty', _form), 0);
            return false;
        });

        // Button Remove Product Package 
        // -----------------------------------------------
        $("body").delegate( ".btn-remove-product-package", "click", function( e ) {
            e.preventDefault();
            var _form       = $('#form-product-package');
            var _product    = $(this).data('id');
            var _tr         = $(this).parents('tr');
            var _table      = $('#list_table_product_package');
            var _tbody      = $('tbody', _table);
            var _count_data = $('tr', _tbody).length;
            
            var package_qty = $('#products_qty_'+_product, _form).val();
            package_qty     = package_qty.replaceAll('.', '');
            
            var qty         = ( package_qty > 0 ? package_qty : $(this).data('qty') );
            
            var price1      = $(this).data('price1');
            var price2      = $(this).data('price2');
            var price3      = $(this).data('price3');
            var bv1         = $(this).data('bv1');
            var bv2         = $(this).data('bv2');
            var bv3         = $(this).data('bv3');
            var subtotal1   = $(this).data('subtotal1');
            var subtotal2   = $(this).data('subtotal2');
            var subtotal3   = $(this).data('subtotal3');
            var subtotalbv1 = $(this).data('subtotalbv1');
            var subtotalbv2 = $(this).data('subtotalbv2');
            var subtotalbv3 = $(this).data('subtotalbv3');

            if( $('[data-id="'+_product+'"]', _tbody).length ) {
                _tr.remove();
                total_qty       = parseInt(total_qty) - parseInt(qty);
                
                total_price1    = parseInt(total_price1) - parseInt(qty * price1);
                total_price2    = parseInt(total_price2) - parseInt(qty * price2);
                total_price3    = parseInt(total_price3) - parseInt(qty * price3);
                total_bv1       = parseInt(total_bv1) - parseInt(qty * bv1);
                total_bv2       = parseInt(total_bv2) - parseInt(qty * bv2);
                total_bv3       = parseInt(total_bv3) - parseInt(qty * bv3);
                
                price_left1     = parseInt(price1) - parseInt(price1);
                price_left2     = parseInt(price2) - parseInt(price2);
                price_left3     = parseInt(price3) - parseInt(price3);
                bv_left1        = parseInt(bv1) - parseInt(bv1);
                bv_left2        = parseInt(bv2) - parseInt(bv2);
                bv_left3        = parseInt(bv3) - parseInt(bv3);

                $('.total_qty', _form).text(App.formatCurrency(total_qty));
                $('.total_price1', _form).text(App.formatCurrency(total_price1));
                $('.total_price2', _form).text(App.formatCurrency(total_price2));
                $('.total_price3', _form).text(App.formatCurrency(total_price3));
                $('.total_bv1', _form).text(App.formatCurrency(total_bv1));
                $('.total_bv2', _form).text(App.formatCurrency(total_bv2));
                $('.total_bv3', _form).text(App.formatCurrency(total_bv3));
                
                $('.total_qty_mix1', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(price_left1));
                $('.total_qty_mix2', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(price_left2));
                $('.total_qty_mix3', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(price_left3));
                $('.total_qty_bv1', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(bv_left1));
                $('.total_qty_bv2', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(bv_left2));
                $('.total_qty_bv3', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(bv_left3));

                if ( _count_data == 1 ) {
                    _tbody.append(_trEmpty);
                }
            }

            App.scrollTo($('#package_qty', _form), 0);
            return false;
        });

        // Update Data Product Package 
        // -----------------------------------------------
        $("body").delegate( ".input-products", "blur", function( e ) {
            e.preventDefault();
            var _form       = $('#form-product-package');
            var _idx        = $(this).data('id');
            var _val        = $(this).val();
            var _type       = $(this).data('type');
            updateDataProductPackage(_type);
            return false;
        });

        $('#lock_qty').trigger('change');

        // Button Edit Status Product Package Data
        // -----------------------------------------------
        $("body").delegate( "a.btn-status-package", "click", function( e ) {
            e.preventDefault();
            var url         = $(this).attr('href');
            var package     = $(this).data('package');
            var status      = $(this).data('status');
            var msg_title   = (status == '1') ? 'Apakah anda yakin akan Meng-Nonaktifkan Paket Produk Ini ?' : 'Apakah anda yakin akan Meng-Aktifkan Paket Produk Ini ?';

            var msg_body    = `
                <div class="row pt-5 align-items-center">
                    <div class="col-sm-12">
                        <h3 class="heading mb-3 text-center">`+ msg_title +`</h3>
                    </div>
                    <div class="col-sm-12 text-center">
                        <small class="text-uppercase text-muted font-weight-bold">Paket : </small>
                        <h2 class="heading-title text-primary mb-0">`+ package +`</h2>
                    </div>
                </div>`;
            bootbox.confirm(msg_body, function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('timer');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    $('#btn_list_table_product').trigger('click');
                                }else{
                                    App.notify({
                                        icon: 'fa fa-exclamation-triangle', 
                                        title: 'Failed', 
                                        message: response.message, 
                                        type: 'danger',
                                    });
                                }
                            }
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
        });

        // Button Delete Product Package Data
        // -----------------------------------------------
        $("body").delegate( "a.btn-delete-package", "click", function( e ) {
            e.preventDefault();
            var url         = $(this).data('url');
            var package     = $(this).data('package');

            var msg_body    = `
                <div class="row pt-5 align-items-center">
                    <div class="col-sm-12">
                        <h3 class="heading mb-3 text-center">Apakah anda yakin akan Meng-Hapus Paket Produk Ini ?</h3>
                    </div>
                    <div class="col-sm-12 text-center">
                        <small class="text-uppercase text-muted font-weight-bold">Paket : </small>
                        <h2 class="heading-title text-primary mb-0">`+ package +`</h2>
                    </div>
                </div>`;
            bootbox.confirm(msg_body, function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('timer');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    $('#btn_list_table_product').trigger('click');
                                }else{
                                    App.notify({
                                        icon: 'fa fa-exclamation-triangle', 
                                        title: 'Failed', 
                                        message: response.message, 
                                        type: 'danger',
                                    });
                                }
                            }
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
        });
    };

    // ---------------------------------
    // Handle Validation Product Package
    // ---------------------------------
    var handleValidationProductPackage = function() {
        var form            = $('#form-product-package');
        var wrapper         = $('.wrapper-form-product-package');

        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                package_name: {
                    minlength: 3,
                    required: true
                },
                package_qty: {
                    required: true,
                    min: 1
                },
                package_weight: {
                    required: true
                },
                product_point: {
                    required: true
                },
            },
            messages: {
                product_name: {
                    required: "Nama Paket Produk harus di isi !",
                    minlength: "Minimal 3 karakter"
                },
                package_qty: {
                    required: "Qty Paket Produk harus di isi !",
                },
                package_weight: {
                    required: "Berat Paket Produk harus di isi !",
                },
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').length) { 
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').length) { 
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').length) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').length) { 
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                App.alert({
                    type: 'danger', 
                    icon: 'exclamation-triangle', 
                    message: 'Ada beberapa error, silahkan cek formulir di bawah!', 
                    container: wrapper, 
                    closeInSeconds: 5,
                    place: 'prepend'
                }); 
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-danger'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-danger'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-danger'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url         = $(form).attr('action');
                var data        = new FormData();
                var description = quill_editor.root.innerHTML;

                var _table      = $('#list_table_product_package');
                var _tbody      = $('tbody', _table);
                var _tr_product = $('.tr-input-product', _tbody);

                if ( ! _tr_product.length ) {
                    App.notify({
                        icon: 'fa fa-exclamation-triangle', 
                        message: 'Produk belum ada yang di pilih. Silahkan pilih produk terlebih dahulu !', 
                        type: 'danger',
                    });
                    App.scrollTo($('#package_qty', _form), 0);
                    return false;
                }

                // get inputs
                $('textarea.form-control, select.form-control, input.form-control',  $(form)).each(function(){
                    data.append($(this).attr("name"), $(this).val());
                });

                if ( $(':input[name=package_mix]').prop("checked") == true ) {
                    data.append('package_mix', 1);
                } else {
                    data.append('package_mix', 0);
                }

                if ( $(':input[name=lock_qty]').prop("checked") == true ) {
                    data.append('lock_qty', 1);
                } else {
                    data.append('lock_qty', 0);
                }
            
                if (description) {
                    data.append('description', description);
                }
            
                if (product_img) {
                    $.each(product_img, function(key, value){
                        data.append('package_img', value);
                    });
                }

                bootbox.confirm("Apakah anda yakin akan simpan data paket produk ini ?", function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   data,
                            processData:false,
                            contentType:false,
                            cache:false,
                            async:false,
                            beforeSend: function (){
                                App.run_Loader('timer');
                            },
                            success: function( response ){
                                App.close_Loader();
                                response = $.parseJSON(response);
                                
                                if( response.status == 'access_denied' ){
                                    $(location).attr('href',response.url);
                                }else{
                                    if( response.status == 'success'){
                                        App.notify({
                                            icon: 'fa fa-check-circle', 
                                            title: 'Success', 
                                            message: response.message, 
                                            type: 'success',
                                        });
                                        $(form)[0].reset();
                                        setTimeout(function(){ $(location).attr('href',response.url); }, 1000);
                                    }else{
                                        App.notify({
                                            icon: 'fa fa-exclamation-triangle', 
                                            title: 'Failed', 
                                            message: response.message, 
                                            type: 'danger',
                                        });
                                    }
                                }
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

    // ---------------------------------
    // Handle General Product Manage
    // ---------------------------------
    var handleGeneralProductCategory = function() {
        $('#btn-modal-category').on('click', function(e) {
            var url = $(this).data('url');
            $('#form-category')[0].reset();
            $('#modal-add-category').modal('show');
            if ( url != '' || url != 'undefined' ) {
                $('#form-category').attr('action', url);
            }
        });


        // Button Edit Category Data 
        // -----------------------------------------------
        $("body").delegate( "a.btn-edit-category", "click", function( e ) {
            e.preventDefault();
            var url         = $(this).attr('href');
            var category    = $(this).data('category');
            $('#form-category')[0].reset();            
            $('#category', $('#form-category')).val(category);            
            $('#modal-add-category').modal('show');
            $('#form-category').attr('action', url);
        });

        // Button Edit Status Category Data
        // -----------------------------------------------
        $("body").delegate( "a.btn-status-category", "click", function( e ) {
            e.preventDefault();
            var url         = $(this).attr('href');
            var category    = $(this).data('category');
            var status      = $(this).data('status');
            var msg_title   = (status == '1') ? 'Apakah anda yakin akan Meng-Nonaktifkan Kategori Ini ?' : 'Apakah anda yakin akan Meng-Aktifkan Kategori Ini ?';

            var msg_body    = `
                <div class="row pt-5 align-items-center">
                    <div class="col-sm-12">
                        <h3 class="heading mb-3 text-center">`+ msg_title +`</h3>
                    </div>
                    <div class="col-sm-12 text-center">
                        <small class="text-uppercase text-muted font-weight-bold">Kategori : </small>
                        <h2 class="heading-title text-primary mb-0">`+ category +`</h2>
                    </div>
                </div>`;
            bootbox.confirm(msg_body, function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('timer');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    $('#btn_list_table_category').trigger('click');
                                }else{
                                    App.notify({
                                        icon: 'fa fa-exclamation-triangle', 
                                        title: 'Failed', 
                                        message: response.message, 
                                        type: 'danger',
                                    });
                                }
                            }
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
        });

        // Button Delete Category Data
        // -----------------------------------------------
        $("body").delegate( "a.btn-delete-category", "click", function( e ) {
            e.preventDefault();
            var url         = $(this).data('url');
            var category    = $(this).data('category');

            var msg_body    = `
                <div class="row pt-5 align-items-center">
                    <div class="col-sm-12">
                        <h3 class="heading mb-3 text-center">Apakah anda yakin akan Meng-Hapus Kategori Ini ?</h3>
                    </div>
                    <div class="col-sm-12 text-center">
                        <small class="text-uppercase text-muted font-weight-bold">Kategori : </small>
                        <h2 class="heading-title text-primary mb-0">`+ category +`</h2>
                    </div>
                </div>`;
            bootbox.confirm(msg_body, function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('timer');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    $('#btn_list_table_category').trigger('click');
                                }else{
                                    App.notify({
                                        icon: 'fa fa-exclamation-triangle', 
                                        title: 'Failed', 
                                        message: response.message, 
                                        type: 'danger',
                                    });
                                }
                            }
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
        });
    };

    // ---------------------------------
    // Handle Validation Product Manage
    // ---------------------------------
    var handleValidationAddCategory = function() {
        var form            = $('#form-category');
        var wrapper         = $('.wrapper-form-category');

        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                category: {
                    minlength: 2,
                    required: true
                }
            },
            messages: {
                category: {
                    required: "Kategori Produk harus di isi !",
                    minlength: "Minimal 2 karakter"
                }
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').length) { 
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').length) { 
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').length) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').length) { 
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                App.alert({
                    type: 'danger', 
                    icon: 'exclamation-triangle', 
                    message: 'Ada beberapa error, silahkan cek formulir di bawah!', 
                    container: wrapper, 
                    closeInSeconds: 5,
                    place: 'prepend'
                }); 
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-danger'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-danger'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-danger'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url         = $(form).attr('action');
                bootbox.confirm("Apakah anda yakin akan simpan data kategori produk ini ?", function(result) {
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
                                
                                if( response.status == 'access_denied' ){
                                    $(location).attr('href',response.url);
                                }else{
                                    if( response.status == 'success'){
                                        App.notify({
                                            icon: 'fa fa-check-circle', 
                                            title: 'Success', 
                                            message: response.message, 
                                            type: 'success',
                                        });
                                        $(form)[0].reset();
                                        $('#modal-add-category').modal('hide');
                                        if ( response.option ) {
                                            var el = $('#product_category');
                                            if ( el.length ) {
                                                el.empty();
                                                el.empty().append(response.option);
                                            }
                                        }
                                        if ( response.form_input ) {
                                            if ( response.form_input == 'category' ) {
                                                $('#btn_list_table_category').trigger('click');
                                            }
                                        }
                                    }else{
                                        App.notify({
                                            icon: 'fa fa-exclamation-triangle', 
                                            title: 'Failed', 
                                            message: response.message, 
                                            type: 'danger',
                                        });
                                    }
                                }
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

    // ---------------------------------
    // Handle General Product Point
    // ---------------------------------
    var handleGeneralProductPoint = function() {
        // Button Edit Product Point Data 
        // -----------------------------------------------
        $("body").delegate( "a.btn-edit-product-point", "click", function( e ) {
            e.preventDefault();
            var form        = $('#form-product-point');
            var url         = $(this).attr('href');
            var source      = $(this).data('source');
            var name        = $(this).data('name');
            var total       = $(this).data('total');
            var point       = $(this).data('point');

            form[0].reset();            
            $('#source', form).val(source);    
            $('#name', form).val(name);    
            $('#total', form).val(total);    
            $('#point', form).val(point);    
            $('#modal-product-point').modal('show');
            form.attr('action', url);
            InputMask.init();
        });
    };

    // ---------------------------------
    // Handle Validation Product Manage
    // ---------------------------------
    var handleValidationProductPoint = function() {
        var form            = $('#form-product-point');
        var wrapper         = $('.wrapper-form-product-point');

        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                total_product: {
                    required: true,
                },
                point_product: {
                    required: true
                }
            },
            messages: {
                total_product: {
                    required: "Jumlah Produk harus di isi !"
                },
                point_product: {
                    required: "Poin Produk harus di isi !"
                }
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').length) { 
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').length) { 
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').length) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').length) { 
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                App.alert({
                    type: 'danger', 
                    icon: 'exclamation-triangle', 
                    message: 'Ada beberapa error, silahkan cek formulir di bawah!', 
                    container: wrapper, 
                    closeInSeconds: 5,
                    place: 'prepend'
                }); 
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-danger'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-danger'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-danger'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url         = $(form).attr('action');
                bootbox.confirm("Apakah anda yakin akan simpan data poin produk ini ?", function(result) {
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
                                
                                if( response.status == 'access_denied' ){
                                    $(location).attr('href',response.url);
                                }else{
                                    if( response.status == 'success'){
                                        App.notify({
                                            icon: 'fa fa-check-circle', 
                                            title: 'Success', 
                                            message: response.message, 
                                            type: 'success',
                                        });
                                        $(form)[0].reset();
                                        $('#modal-product-point').modal('hide');
                                        $('#btn_list_table_product_point').trigger('click');
                                    }else{
                                        App.notify({
                                            icon: 'fa fa-exclamation-triangle', 
                                            title: 'Failed', 
                                            message: response.message, 
                                            type: 'danger',
                                        });
                                    }
                                }
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

    // ---------------------------------
    // Add Product Package
    // ---------------------------------
    var addProductPackage = function() {
        var _form       = $('#form-product-package');
        var _table      = $('#list_table_product_package');
        var _tbody      = $('tbody', _table);
        var _tr         = $('tr', _tbody);
        var _product    = $('#select_product', _form).val();
        var _empty_row  = _tbody.find('tr.data-empty');
        
        var t_product   = $('select[name="select_product"] option:selected').text();
        var t_price1    = $('select[name="select_product"] option:selected').attr('price1');
        var t_price2    = $('select[name="select_product"] option:selected').attr('price2');
        var t_price3    = $('select[name="select_product"] option:selected').attr('price3');
        var t_bv1       = $('select[name="select_product"] option:selected').attr('bv1');
        var t_bv2       = $('select[name="select_product"] option:selected').attr('bv2');
        var t_bv3       = $('select[name="select_product"] option:selected').attr('bv3');
        
        if( $('[data-id="'+_product+'"]', _tbody).length ) {
            bootbox.alert('Produk ini sudah ada ');
            return false;
        }

        if ( $(':input[name=package_mix]', _form).prop("checked") == false ) {
            if( $('.tr-input-product', _tbody).length ) {
                bootbox.alert('Paket Produk tidak bisa di Mix.');
                return false;
            }
        }

        if ( _empty_row.length ) {
            _empty_row.remove();
        }

        var package_qty = $('#package_qty', _form).val();
        package_qty     = package_qty.replaceAll('.', '');
        
        if( package_qty == 0 ){
            bootbox.alert('Silahkan inputkan jumlah Produk!.');
            return false;
        }

        var product_qty = parseInt(package_qty) - parseInt(total_qty);
        
        var subtotal1   = parseInt(t_price1) * parseInt(product_qty);
        var subtotal2   = parseInt(t_price2) * parseInt(product_qty);
        var subtotal3   = parseInt(t_price3) * parseInt(product_qty);
        
        var subtotalbv1 = parseInt(t_bv1) * parseInt(product_qty);
        var subtotalbv2 = parseInt(t_bv2) * parseInt(product_qty);
        var subtotalbv3 = parseInt(t_bv3) * parseInt(product_qty);

        var _append_row = `
            <tr class="tr-input-product" data-id="${_product}">
                <td class="py-1"><b>${t_product}</b></td>
                <td class="py-1 text-right">
                    <input type="text" id="products_qty_${_product}" name="products[${_product}][qty]" 
                        class="form-control form-control-sm numbercurrency text-right input-products" 
                        style="min-width:60px"
                        value="${product_qty}"
                        data-id="${_product}" 
                        data-type="qty" />
                </td>
                <td class="py-1 text-center" style="width: 50px !important;">
                    <div class="form-control form-control-sm" style="border-color: #FFFFFF;">WILAYAH 1</div>
                    <div class="form-control form-control-sm" style="border-color: #FFFFFF;">WILAYAH 2</div>
                    <div class="form-control form-control-sm" style="border-color: #FFFFFF;">WILAYAH 3</div>
                </td>
                <td class="py-1 text-right">
                    <input type="text" id="products_price1_${_product}" name="products[${_product}][price1]" 
                        class="form-control form-control-sm numbercurrency text-right input-products" 
                        style="min-width:80px" 
                        readonly="readonly"
                        value="${t_price1}"
                        data-id="${_product}" 
                        data-type="price1" />
                        
                    <input type="text" id="products_price2_${_product}" name="products[${_product}][price2]" 
                        class="form-control form-control-sm numbercurrency text-right input-products" 
                        style="min-width:80px"
                        readonly="readonly"
                        value="${t_price2}"
                        data-id="${_product}" 
                        data-type="price2" />
                        
                    <input type="text" id="products_price3_${_product}" name="products[${_product}][price3]" 
                        class="form-control form-control-sm numbercurrency text-right input-products"
                        style="min-width:80px"
                        readonly="readonly"
                        value="${t_price3}" 
                        data-id="${_product}" 
                        data-type="price3" />
                        
                    <input type="hidden" id="products_subtotal1_${_product}" name="products[${_product}][subtotal1]" class="form-control" value="${subtotal1}" />
                    <input type="hidden" id="products_subtotal2_${_product}" name="products[${_product}][subtotal2]" class="form-control" value="${subtotal2}" />
                    <input type="hidden" id="products_subtotal3_${_product}" name="products[${_product}][subtotal3]" class="form-control" value="${subtotal3}" />
                </td>
                <td class="py-1 text-right">
                    <input type="text" id="products_bv1_${_product}" name="products[${_product}][bv1]" 
                        class="form-control form-control-sm numbercurrency text-right input-products" 
                        style="min-width:80px" 
                        readonly="readonly"
                        value="${t_bv1}"
                        data-id="${_product}" 
                        data-type="bv1" />
                        
                    <input type="text" id="products_bv2_${_product}" name="products[${_product}][bv2]" 
                        class="form-control form-control-sm numbercurrency text-right input-products" 
                        style="min-width:80px"
                        readonly="readonly"
                        value="${t_bv2}"
                        data-id="${_product}" 
                        data-type="bv2" />
                        
                    <input type="text" id="products_bv3_${_product}" name="products[${_product}][bv3]" 
                        class="form-control form-control-sm numbercurrency text-right input-products"
                        style="min-width:80px"
                        readonly="readonly"
                        value="${t_bv3}" 
                        data-id="${_product}" 
                        data-type="bv3" />
                        
                    <input type="hidden" id="products_subtotalbv1_${_product}" name="products[${_product}][subtotalbv1]" class="form-control" value="${subtotalbv1}" />
                    <input type="hidden" id="products_subtotalbv2_${_product}" name="products[${_product}][subtotalbv2]" class="form-control" value="${subtotalbv2}" />
                    <input type="hidden" id="products_subtotalbv3_${_product}" name="products[${_product}][subtotalbv3]" class="form-control" value="${subtotalbv3}" />
                </td>
                <td class="py-1 text-center">
                    <input type="hidden" name="products[${_product}][id]" value="${_product}" class="form-control d-none" />
                    <input type="hidden" name="products[${_product}][name]" value="${t_product}" class="form-control d-none" />
                    <button class="btn btn-sm btn-outline-warning btn-remove-product-package" type="button" title="Remove" 
                        data-id="${_product}" 
                        data-qty="${product_qty}"
                        data-price1="${t_price1}"
                        data-price2="${t_price2}"
                        data-price3="${t_price3}"
                        data-bv1="${t_bv1}"
                        data-bv2="${t_bv2}"
                        data-bv3="${t_bv3}"
                        data-subtotal1="${subtotal1}"
                        data-subtotal2="${subtotal2}"
                        data-subtotal3="${subtotal3}"
                        data-subtotalbv1="${subtotalbv1}"
                        data-subtotalbv2="${subtotalbv2}"
                        data-subtotalbv3="${subtotalbv3}" >
                    <i class="fa fa-times"></i></button>
                </td>
            </tr>`;

        _tbody.append(_append_row);
        $('#select_product', _form).val('');
        if ( $(':input[name=lock_qty]').prop("checked") == true ) {
            $('.none-mix').show();
        } else {
            $('.none-mix').hide();
        }
        updateQtyAllProductPackage();
        return false;
    }

    // ---------------------------------
    // Update Qty Product Package
    // ---------------------------------
    var updateQtyAllProductPackage = function() {
        var _form       = $('#form-product-package');
        var _pack_qty   = $('#package_qty', _form).val();
        var _table      = $('#list_table_product_package');
        var _tbody      = $('tbody', _table);
        var _tr_product = $('.tr-input-product', _tbody);

        if ( _tr_product.length ) {
            _pack_qty           = _pack_qty.replaceAll('.', '');
            total_qty           = parseInt(_pack_qty);
            _total_product      = parseInt(_tr_product.length);
            _qty_mod            = total_qty % _total_product;

            if ( parseInt(_qty_mod) > 0 ) {
                _qty_product    = (total_qty - _qty_mod) / _total_product;
            } else {
                _qty_product    = total_qty / _total_product;
            }

            _price1         = 0;
            _price2         = 0;
            _price3         = 0;
            _bv1            = 0;
            _bv2            = 0;
            _bv3            = 0;
            
            total_price1    = 0;
            total_price2    = 0;
            total_price3    = 0;
            total_bv1       = 0;
            total_bv2       = 0;
            total_bv3       = 0;
            
            _tr_product.each(function(index){
                _idx        = $(this).data('id');
                _price1     = $('#products_price1_'+_idx).val();
                _price1     = _price1.replaceAll('.', '');
                _price2     = $('#products_price2_'+_idx).val();
                _price2     = _price2.replaceAll('.', '');
                _price3     = $('#products_price3_'+_idx).val();
                _price3     = _price3.replaceAll('.', '');
                _bv1        = $('#products_bv1_'+_idx).val();
                _bv1        = _bv1.replaceAll('.', '');
                _bv2        = $('#products_bv2_'+_idx).val();
                _bv2        = _bv2.replaceAll('.', '');
                _bv3        = $('#products_bv3_'+_idx).val();
                _bv3        = _bv3.replaceAll('.', '');

                if ( parseInt(_qty_mod) > 0 && parseInt(index) == 0 ) {
                    _qty    = _qty_product + _qty_mod;
                } else {
                    _qty    = _qty_product;
                }

                subtotal1       = parseInt(_price1) * parseInt(_qty);
                subtotal2       = parseInt(_price2) * parseInt(_qty);
                subtotal3       = parseInt(_price3) * parseInt(_qty);
                subtotalbv1     = parseInt(_bv1) * parseInt(_qty);
                subtotalbv2     = parseInt(_bv2) * parseInt(_qty);
                subtotalbv3     = parseInt(_bv3) * parseInt(_qty);
                
                total_price1    = parseInt(total_price1) + parseInt(subtotal1);
                total_price2    = parseInt(total_price2) + parseInt(subtotal2);
                total_price3    = parseInt(total_price3) + parseInt(subtotal3);
                total_bv1       = parseInt(total_bv1) + parseInt(subtotalbv1);
                total_bv2       = parseInt(total_bv2) + parseInt(subtotalbv2);
                total_bv3       = parseInt(total_bv3) + parseInt(subtotalbv3);
                
                $('#products_qty_'+_idx).val(_qty);
                $('#products_subtotal1_'+_idx).val(subtotal1);
                $('#products_subtotal2_'+_idx).val(subtotal2);
                $('#products_subtotal3_'+_idx).val(subtotal3);
            });

            if ( $(':input[name=package_mix]', _form).prop("checked") == false ) {
                total_price1    = parseInt(total_qty) * parseInt(_price1);
                total_price2    = parseInt(total_qty) * parseInt(_price2);
                total_price3    = parseInt(total_qty) * parseInt(_price3);
                total_bv1       = parseInt(total_qty) * parseInt(_bv1);
                total_bv2       = parseInt(total_qty) * parseInt(_bv2);
                total_bv3       = parseInt(total_qty) * parseInt(_bv3);
                
                $('.total_qty_mix1', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(_price1));
                $('.total_qty_mix2', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(_price2));
                $('.total_qty_mix3', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(_price3));
                $('.total_qty_bv1', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(_bv1));
                $('.total_qty_bv2', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(_bv2));
                $('.total_qty_bv3', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(_bv3));
            }

            $('.total_qty', _form).text(App.formatCurrency(total_qty));
            $('.total_price1', _form).text(App.formatCurrency(total_price1));
            $('.total_price2', _form).text(App.formatCurrency(total_price2));
            $('.total_price3', _form).text(App.formatCurrency(total_price3));
            $('.total_bv1', _form).text(App.formatCurrency(total_bv1));
            $('.total_bv2', _form).text(App.formatCurrency(total_bv2));
            $('.total_bv3', _form).text(App.formatCurrency(total_bv3));
            
            $('.total_qty_mix1', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(_price1));
            $('.total_qty_mix2', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(_price2));
            $('.total_qty_mix3', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(_price3));
            $('.total_qty_bv1', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(_bv1));
            $('.total_qty_bv2', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(_bv2));
            $('.total_qty_bv3', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(_bv3));
            
            InputMask.init();
        }
        return false;
    };

    var updateDataProductPackage = function( _type = '' ) {
        var _form       = $('#form-product-package');
        var _pack_qty   = $('#package_qty', _form).val();
        var _table      = $('#list_table_product_package');
        var _tbody      = $('tbody', _table);
        var _tr_product = $('.tr-input-product', _tbody);

        if ( _tr_product.length ) {
            _pack_qty   = _pack_qty.replaceAll('.', '');
            _pack_qty   = parseInt(_pack_qty);
            
            _price1         = 0;
            _price2         = 0;
            _price3         = 0;
            _bv1            = 0;
            _bv2            = 0;
            _bv3            = 0;
            
            total_price1    = 0;
            total_price2    = 0;
            total_price3    = 0;
            total_bv1       = 0;
            total_bv2       = 0;
            total_bv3       = 0;
            
            total_qty       = 0;

            _tr_product.each(function(index){
                _idx            = $(this).data('id');
                
                _qty            = $('#products_qty_'+_idx).val();
                _qty            = _qty.replaceAll('.', '');
                
                _price1         = $('#products_price1_'+_idx).val();
                _price1         = _price1.replaceAll('.', '');
                _price2         = $('#products_price2_'+_idx).val();
                _price2         = _price2.replaceAll('.', '');
                _price3         = $('#products_price3_'+_idx).val();
                _price3         = _price3.replaceAll('.', '');
                
                _bv1            = $('#products_bv1_'+_idx).val();
                _bv1            = _bv1.replaceAll('.', '');
                _bv2            = $('#products_bv2_'+_idx).val();
                _bv2            = _bv2.replaceAll('.', '');
                _bv3            = $('#products_bv3_'+_idx).val();
                _bv3            = _bv3.replaceAll('.', '');
                
                _subtotal1      = $('#products_subtotal1_'+_idx).val();
                _subtotal1      = _subtotal1.replaceAll('.', '');
                _subtotal2      = $('#products_subtotal2_'+_idx).val();
                _subtotal2      = _subtotal2.replaceAll('.', '');
                _subtotal3      = $('#products_subtotal3_'+_idx).val();
                _subtotal3      = _subtotal3.replaceAll('.', '');
                
                _subtotalbv1    = $('#products_subtotalbv1_'+_idx).val();
                _subtotalbv1    = _subtotalbv1.replaceAll('.', '');
                _subtotalbv2    = $('#products_subtotalbv2_'+_idx).val();
                _subtotalbv2    = _subtotalbv2.replaceAll('.', '');
                _subtotalbv3    = $('#products_subtotalbv3_'+_idx).val();
                _subtotalbv3    = _subtotalbv3.replaceAll('.', '');
                
                subtotal1       = parseInt(_price1) * parseInt(_qty);
                subtotal2       = parseInt(_price2) * parseInt(_qty);
                subtotal3       = parseInt(_price3) * parseInt(_qty);
                
                subtotalbv1     = parseInt(_bv1) * parseInt(_qty);
                subtotalbv2     = parseInt(_bv2) * parseInt(_qty);
                subtotalbv3     = parseInt(_bv3) * parseInt(_qty);
                
                total_price1    = parseInt(total_price1) + parseInt(subtotal1);
                total_price2    = parseInt(total_price2) + parseInt(subtotal2);
                total_price3    = parseInt(total_price3) + parseInt(subtotal3);
                total_bv1       = parseInt(total_bv1) + parseInt(subtotalbv1);
                total_bv2       = parseInt(total_bv2) + parseInt(subtotalbv2);
                total_bv3       = parseInt(total_bv3) + parseInt(subtotalbv3);
                
                $('#products_qty_'+_idx).val(_qty);
                $('#products_subtotal1_'+_idx).val(subtotal1);
                $('#products_subtotal2_'+_idx).val(subtotal2);
                $('#products_subtotal3_'+_idx).val(subtotal3);
                $('#products_subtotalbv1_'+_idx).val(subtotalbv1);
                $('#products_subtotalbv2_'+_idx).val(subtotalbv2);
                $('#products_subtotalbv3_'+_idx).val(subtotalbv3);
                
                total_qty       = parseInt(total_qty) + parseInt(_qty);

                if ( $(':input[name=lock_qty]', _form).prop("checked") == true ) {
                    if ( _type == 'subtotal' ) {
                        price1          = parseInt(_subtotal1) / parseInt(_qty);
                        price2          = parseInt(_subtotal2) / parseInt(_qty);
                        price3          = parseInt(_subtotal3) / parseInt(_qty);
                        
                        total_price1    = parseInt(total_price1) + parseInt(_subtotal1);
                        total_price2    = parseInt(total_price2) + parseInt(_subtotal2);
                        total_price3    = parseInt(total_price3) + parseInt(_subtotal3);
                        
                        if ( parseInt(_subtotal1) > 0 ) {
                            $('#products_price1_'+_idx).val(price1);
                        } else {
                            subtotal    = parseInt(_price) * parseInt(_qty);
                            $('#products_subtotal1_'+_idx).val(subtotal1);
                        }
                        
                        if ( parseInt(_subtotal2) > 0 ) {
                            $('#products_price2_'+_idx).val(price2);
                        } else {
                            subtotal    = parseInt(_price) * parseInt(_qty);
                            $('#products_subtotal2_'+_idx).val(subtotal2);
                        }
                        
                        if ( parseInt(_subtotal3) > 0 ) {
                            $('#products_price3_'+_idx).val(price3);
                        } else {
                            subtotal    = parseInt(_price) * parseInt(_qty);
                            $('#products_subtotal3_'+_idx).val(subtotal3);
                        }
                    } else {
                        subtotal1       = parseInt(_price1) * parseInt(_qty);
                        subtotal2       = parseInt(_price2) * parseInt(_qty);
                        subtotal3       = parseInt(_price3) * parseInt(_qty);
                        
                        total_price1    = parseInt(total_price1) + parseInt(subtotal1);
                        total_price2    = parseInt(total_price2) + parseInt(subtotal2);
                        total_price3    = parseInt(total_price3) + parseInt(subtotal3);
                        
                        $('#products_subtotal1_'+_idx).val(subtotal1);
                        $('#products_subtotal2_'+_idx).val(subtotal2);
                        $('#products_subtotal3_'+_idx).val(subtotal3);
                    }
                }
            });
            
            $('#package_qty', _form).val(total_qty);
            $('#package_weight', _form).val(total_qty);
            
            $('.total_qty', _form).text(App.formatCurrency(total_qty));
            $('.total_price1', _form).text(App.formatCurrency(total_price1));
            $('.total_price2', _form).text(App.formatCurrency(total_price2));
            $('.total_price3', _form).text(App.formatCurrency(total_price3));
            $('.total_bv1', _form).text(App.formatCurrency(total_bv1));
            $('.total_bv2', _form).text(App.formatCurrency(total_bv2));
            $('.total_bv3', _form).text(App.formatCurrency(total_bv3));
            
            $('.total_qty_mix1', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(_price1));
            $('.total_qty_mix2', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(_price2));
            $('.total_qty_mix3', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(_price3));
            $('.total_qty_bv1', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(_bv1));
            $('.total_qty_bv2', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(_bv2));
            $('.total_qty_bv3', _form).text(App.formatCurrency(total_qty) +' x '+ App.formatCurrency(_bv3));
            InputMask.init();
        }
        return false;
    };

    return {
        init: function() {
            handleGeneralProductManage();
            handleValidationProductManage();
            handleValidationAddCategory();
        },
        initPackage: function() {
            handleGeneralProductPackage();
            handleValidationProductPackage();
        },
        initCategory: function() {
            handleGeneralProductCategory();
            handleValidationAddCategory();
        },
        initProductPoint: function() {
            handleGeneralProductPoint();
            handleValidationProductPoint();
        }
    };
}();

// ===========================================================
// Manage Promo Code Function
// ===========================================================
var PromoCodeManage = function() {
    var _form       = $('#form-promocode');
    var _modal      = $('#modal-form-promocode');
    var wrapper     = $('.wrapper-form-promocode');

    var _trEmpty    = `<tr class="data-empty"><td colspan="2" class="text-center">Produk belum ada yang di pilih.</td></tr>`;

    // ---------------------------------
    // Handle General Promo Code
    // ---------------------------------
    var handleGeneralPromoCode = function() {
        $('#btn-modal-promo-code').on('click', function(e) {
            var url = $(this).data('url');
            _form[0].reset();
            $('input.form-control', _form).val('');
            _modal.modal('show');
            if ( url != '' || url != 'undefined' ) {
                _form.attr('action', url);
            }
            clearProductPromo();
            $('#discount_agent_type').trigger('change');
            $('#discount_customer_type').trigger('change');
        });

        $('#discount_agent_type').on('change', function(e) {
            var val = $(this).val();
            if ( val == 'percent' ) {
                $('#discount_agent').removeClass('numbercurrency');
                $('#discount_agent').addClass('numberpercent');
                $('.label_discount_agent').text('Jumlah (%)');
            } else {
                $('#discount_agent').removeClass('numberpercent');
                $('#discount_agent').addClass('numbercurrency');
                $('.label_discount_agent').text('Jumlah (Rp)');
            }
            InputMask.init();
        });

        $('#discount_customer_type').on('change', function(e) {
            var val = $(this).val();
            if ( val == 'percent' ) {
                $('#discount_customer').removeClass('numbercurrency');
                $('#discount_customer').addClass('numberpercent');
                $('.label_discount_customer').text('Jumlah (%)');
            } else {
                $('#discount_customer').removeClass('numberpercent');
                $('#discount_customer').addClass('numbercurrency');
                $('.label_discount_customer').text('Jumlah (Rp)');
            }
            InputMask.init();
        });

        // Button Edit Promo Data 
        // -----------------------------------------------
        $("body").delegate( "a.btn-edit-promo", "click", function( e ) {
            e.preventDefault();
            var url                 = $(this).attr('href');
            var code                = $(this).data('code');
            var promo               = $(this).data('promo');
            var agent_type          = $(this).data('agent_type');
            var agent_discount      = $(this).data('agent_discount');
            var customer_type       = $(this).data('customer_type');
            var customer_discount   = $(this).data('customer_discount');
            var products            = $(this).data('products');

            App.run_Loader('timer');

            _form[0].reset();
            if ( url != '' || url != 'undefined' ) {
                _form.attr('action', url);
            }
            $('#form_code', _form).val(code);
            $('#promo_code', _form).val(promo);
            $('#discount_agent_type', _form).val(agent_type);
            $('#discount_customer_type', _form).val(customer_type);
            $('#discount_agent_type').trigger('change');
            $('#discount_customer_type').trigger('change');
            if ( products ) {
                clearProductPromo();
                loadProductPromo(products);
            }
            setTimeout(function(){ 
                App.close_Loader();
                $('#discount_agent', _form).val(agent_discount);
                $('#discount_customer', _form).val(customer_discount);
                _modal.modal('show');
            }, 500);
        });

        // Button Edit Status Promo Data
        // -----------------------------------------------
        $("body").delegate( "a.btn-status-promo", "click", function( e ) {
            e.preventDefault();
            var url         = $(this).attr('href');
            var promo       = $(this).data('promo');
            var status      = $(this).data('status');
            var msg_title   = (status == '1') ? 'Apakah anda yakin akan Meng-Nonaktifkan Promo Ini ?' : 'Apakah anda yakin akan Meng-Aktifkan Promo Ini ?';

            var msg_body    = `
                <div class="row pt-5 align-items-center">
                    <div class="col-sm-12">
                        <h3 class="heading mb-3 text-center">`+ msg_title +`</h3>
                    </div>
                    <div class="col-sm-12 text-center">
                        <small class="text-uppercase text-muted font-weight-bold">Kode Promo : </small>
                        <h2 class="heading-title text-primary mb-0">`+ promo +`</h2>
                    </div>
                </div>`;
            bootbox.confirm(msg_body, function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('timer');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    $('#btn_list_table_promo_code').trigger('click');
                                }else{
                                    App.notify({
                                        icon: 'fa fa-exclamation-triangle', 
                                        title: 'Failed', 
                                        message: response.message, 
                                        type: 'danger',
                                    });
                                }
                            }
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
        });

        // Button Add Product Promo Data 
        // -----------------------------------------------
        $("body").delegate( "#select_product", "change", function( e ) {
            e.preventDefault();
            $('#btn-add-product-promo').trigger('click');
        });

        // Button Add Product Promo Data 
        // -----------------------------------------------
        $("body").delegate( "#btn-add-product-promo", "click", function( e ) {
            e.preventDefault();
            var product     = $('#select_product', _form).val();
            if ( product ) {
                addProductPromo();
            } else {
                bootbox.alert('Produk belum di pilih !');
                $('#select_product', _form).val('');
                return false;
            }
        });

        // Button Add Product Promo Data 
        // -----------------------------------------------
        $("body").delegate( ".btn-remove-product-promo", "click", function( e ) {
            e.preventDefault();
            var _product    = $(this).data('id');
            var _tr         = $(this).parents('tr');
            var _table      = $('#list_table_product_promo');
            var _tbody      = $('tbody', _table);
            var _count_data = $('tr', _tbody).length;

            if( $('[data-id="'+_product+'"]', _tbody).length ) {
                _tr.remove();

                if ( _count_data == 1 ) {
                    _tbody.append(_trEmpty);
                }
            }
        });
    };

    // ---------------------------------
    // Add Product Promo Code
    // ---------------------------------
    var addProductPromo = function() {
        var _table      = $('#list_table_product_promo');
        var _tbody      = $('tbody', _table);
        var _tr         = $('tr', _tbody);
        var _count_data = _tr.length;
        var _empty_row  = _tbody.find('tr.data-empty');
        var _product    = $('#select_product', _form).val();
        var t_product   = $('select[name="select_product"] option:selected').text();

        if( $('[data-id="'+_product+'"]', _tbody).length ) {
            bootbox.alert('Produk ini sudah ada ');
            return false;
        }

        if ( _empty_row.length ) {
            _empty_row.remove();
        }

        var _append_row = `
            <tr data-id="${_product}">
                <td class="py-1"><b>${t_product}</b></td>
                <td class="py-1 text-center">
                    <input type="hidden" name="products[${_product}]" value="${_product}" class="d-none input-products" />
                    <button class="btn btn-sm btn-outline-warning btn-remove-product-promo" type="button" data-id="${_product}">
                    <i class="fa fa-times"></i> Remove</button>
                </td>
            </tr>`;
        _tbody.append(_append_row);
        $('#select_product', _form).val('');
    }

    // ---------------------------------
    // Clear Product Promo Code
    // ---------------------------------
    var clearProductPromo = function() {
        var _table      = $('#list_table_product_promo');
        var _tbody      = $('tbody', _table);

        if ( _tbody.length ) {
            _tbody.empty();
            _tbody.append(_trEmpty);
        }
    }

    // ---------------------------------
    // Load Product Promo Code
    // ---------------------------------
    var loadProductPromo = function(products_idx = '') {
        var _table      = $('#list_table_product_promo');
        var _tbody      = $('tbody', _table);

        if ( products_idx ) {
            $.each(products_idx, function(index, val) {
                $('#select_product', _form).val(val);
                if ( $('#select_product', _form).val() ) {
                    $('#select_product', _form).trigger('change');
                }
            });
        }
    }

    // ---------------------------------
    // Handle Validation Promo Code
    // ---------------------------------
    var handleValidationPromoCode = function() {
        var form        = _form;
        var products    = '';

        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                promo_code: {
                    required: true,
                    vouchercode: true,
                    minlength: 2,
                    remote: {
                        url: $("#promo_code").data('url'),
                        type: "post",
                        data: {
                            promo_code: function() {
                                return $("#promo_code").prop( 'readonly' ) ? '' : $("#promo_code").val();
                            },
                            code: function() {
                                return $("#form_code").length ? $("#form_code").val() : '';
                            }
                        }
                    }
                },
                discount_agent: {
                    required: function(element) {
                        if( $('#discount_customer').val() == '' || $('#discount_customer').val() == 0 || $('#discount_customer').val() == '0 %' ){
                            return true;
                        }else{
                            return false;
                        }
                    }
                },
                discount_customer: {
                    required: function(element) {
                        if( $('#discount_agent').val() == '' || $('#discount_agent').val() == 0 || $('#discount_agent').val() == '0 %' ){
                            return true;
                        }else{
                            return false;
                        }
                    }
                }
            },
            messages: {
                promo_code: {
                    required: "Kode Promo harus di isi !",
                    minlength: "Minimal 2 karakter",
                    remote: "Kode Promo sudah terdaftar. Silahkan gunakan Kode Promo lain",
                }
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').length) { 
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').length) { 
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').length) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').length) { 
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                App.alert({
                    type: 'danger', 
                    icon: 'exclamation-triangle', 
                    message: 'Ada beberapa error, silahkan cek formulir di bawah!', 
                    container: wrapper, 
                    closeInSeconds: 5,
                    place: 'prepend'
                }); 
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-danger'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-danger'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-danger'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url         = $(form).attr('action');
                var form_input  = $( ':input[name=form_input]').val();

                if ( form_input == 'products' ) {
                    var _table  = $('#list_table_product_promo');
                    var _tbody  = $('tbody', _table);

                    if ( ! $('.input-products', _tbody).length ) {
                        bootbox.alert('Produk belum di pilih !');
                        return false;
                    }
                }

                bootbox.confirm("Apakah anda yakin akan simpan data kode promo ini ?", function(result) {
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
                                
                                if( response.status == 'access_denied' ){
                                    $(location).attr('href',response.url);
                                }else{
                                    if( response.status == 'success'){
                                        App.notify({
                                            icon: 'fa fa-check-circle', 
                                            title: 'Success', 
                                            message: response.message, 
                                            type: 'success',
                                        });
                                        $(form)[0].reset();
                                        _modal.modal('hide');
                                        $('#btn_list_table_promo_code').trigger('click');
                                    }else{
                                        App.notify({
                                            icon: 'fa fa-exclamation-triangle', 
                                            title: 'Failed', 
                                            message: response.message, 
                                            type: 'danger',
                                        });
                                    }
                                }
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

        $.validator.addMethod("vouchercode", function(value) {
            return /^[a-z0-9\-]{4,100}$/i.test(value);
        }, "Kode Voucher harus berupa Huruf atau Angka atau karakter strip (-)");
    };

    return {
        init: function() {
            handleGeneralPromoCode();
            handleValidationPromoCode();
        }
    };
}();

// ===========================================================
// Manage Shop Order Function
// ===========================================================
var ShopOrderManage = function() {
    var modal_detail = $('#modal-shop-order-detail');

    // ---------------------------------
    // Handle General Promo Code
    // ---------------------------------
    var handleGeneralShopOrder = function() {
        // Button Detail Shop Order
        $("body").delegate( "a.btn-shop-order-detail", "click", function( e ) {
            var url     = $(this).data('url');
            var invoice = $(this).data('invoice');
            $.ajax({
                type:   "POST",
                url:    url,
                beforeSend: function (){
                    App.run_Loader('timer');
                    $('.info-shop-order-detail', modal_detail).empty();
                },
                success: function( response ){
                    App.close_Loader();
                    response = $.parseJSON(response);
                    if( response.status == 'access_denied' ){
                        $(location).attr('href',response.url);
                    }else{
                        if( response.status == 'success'){
                            $('.title-invoice', modal_detail).text(invoice);
                            $('.info-shop-order-detail', modal_detail).html(response.data);
                            modal_detail.modal('show');
                        }else{
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                title: 'Failed', 
                                message: response.message, 
                                type: 'danger',
                            });
                        }
                    }
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
            return false;
        });

        // Shop Order Payment
        $("body").delegate( "a.btn-shop-payment", "click", function( event ) {
            event.preventDefault();
            var bank        = $(this).data('bank');
            var bill        = $(this).data('bill');
            var bill_name   = $(this).data('bill_name');
            var nominal     = $(this).data('nominal');
            var img         = $(this).data('img');
            var type        = $(this).data('type');

            var txt_bank        = 'Bank';
            var txt_bill        = 'No. Rekening';
            var txt_bill_name   = 'Nama Pemilik Rek.';
            var txt_total       = 'Jumlah Transfer';
            var txt_desc        = '';

            if ( type == 'deposite' ) {
                txt_bank        = 'Saldo Deposite';
                txt_bill        = 'Username';
                txt_bill_name   = 'Nama';
                txt_total       = 'Jumlah';
                txt_desc        = '<h4 class="heading-small text-warning">Pembayaran dilakukan melalui Saldo '+ bank +'</h4><hr class="my-2">';
            }
            
            var msg_body    = `
                <h3 class="heading pt-4 pb-3 text-center">Bukti Pembayaran</h3>
                `+txt_desc+`
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">${txt_bank} :</small></div>
                    <div class="col-sm-6 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">${txt_bank} :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${bank} </small></div>
                </div>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">${txt_bill} :</small></div>
                    <div class="col-sm-6 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">${txt_bill} :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${bill} </small></div>
                </div>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">${txt_bill_name} :</small></div>
                    <div class="col-sm-6 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">${txt_bill_name} :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${bill_name} </small></div>
                </div>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">${txt_total} :</small></div>
                    <div class="col-sm-6 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">${txt_total} :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${nominal} </small></div>
                </div>`;

            if ( type != 'deposite' && img != '' ) {
                msg_body   += `
                    <div class="row justify-content-center mt-4">
                        <div class="col-sm-8">
                            <a href="`+img+`" target="_blank">
                                <img class="img-responsive" width="100%" src="`+img+`">
                            </a>
                        </div>
                    </div>`;
            }

            bootbox.alert({
                title: '',
                message: msg_body,
                size: 'large'
            });
        });

        // Button Confirm Detail Shop Order
        $("body").delegate( "a.btn-shop-order-confirm", "click", function( e ) {
            var url     = $(this).data('url');
            var invoice = $(this).data('invoice');
            var total   = $(this).data('total');

            var msg_body    = `
                <h3 class="heading pt-4 pb-3 text-center">Apakah anda yakin akan konfirmasi pesanan ini ?</h3>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Invoice :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Invoice :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${invoice} </small></div>
                </div>
                <div class="row align-items-center">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Total Pembayaran :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Total Pembayaran :</small></div>
                    <div class="col-sm-6"><small class="heading-title text-warning font-weight-bold"> ${total} </small></div>
                </div>
                <hr class="mt-2 mb-3">
                <div class="row justify-content-center">
                    <form class="form-horizontal" id="form-shop-order-confirm">
                        <div class="form-group mb-1">
                            <div class="col-md-12">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Password Konfirmasi" autocomplete="off" value="" />
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>`;

            bootbox.confirm(msg_body, function(result) {
                if( result == true ){
                    var data = {};
                    var password = '';

                    if ($('#password_confirm', '#form-shop-order-confirm').length) {
                        password = $('#password_confirm', '#form-shop-order-confirm').val();
                        data.password = password;
                    }

                    if (password == "" || password == undefined) {
                        App.notify({
                            icon: 'fa fa-exclamation-triangle', 
                            title: 'Failed', 
                            message: 'Password harus diisi !', 
                            type: 'warning',
                        });
                        $('#password_confirm').focus();
                        return false;
                    }

                    $.ajax({
                        type:   "POST",
                        data:   data,
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('timer');
                        },
                        success: function( response ){
                            App.close_Loader();

                            console.log(response);

                            response = $.parseJSON(response);
                            
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    $('#btn_list_table_shop_order').trigger('click');
                                }else{
                                    App.notify({
                                        icon: 'fa fa-exclamation-triangle', 
                                        title: 'Failed', 
                                        message: response.message, 
                                        type: 'danger',
                                    });
                                }
                            }
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
            return false;
        });

        // Button Cancel Detail Shop Order
        $("body").delegate( "a.btn-shop-order-cancel", "click", function( e ) {
            var url     = $(this).data('url');
            var invoice = $(this).data('invoice');
            var total   = $(this).data('total');

            var msg_body    = `
                <h3 class="heading pt-4 pb-3 text-center">Apakah anda yakin akan membatalkan pesanan ini ?</h3>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Invoice :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Invoice :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${invoice} </small></div>
                </div>
                <div class="row align-items-center">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Total Pembayaran :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Total Pembayaran :</small></div>
                    <div class="col-sm-6"><small class="heading-title text-warning font-weight-bold"> ${total} </small></div>
                </div>
                <hr class="mt-2 mb-3">
                <div class="row justify-content-center">
                    <form class="form-horizontal" id="form-shop-order-cancel">
                        <div class="form-group mb-1">
                            <div class="col-md-12">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Password Konfirmasi" autocomplete="off" value="" />
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                `;
            bootbox.confirm(msg_body, function(result) {
                if( result == true ){
                    var data = {};
                    var password = '';

                    if ($('#password_confirm', '#form-shop-order-cancel').length) {
                        password = $('#password_confirm', '#form-shop-order-cancel').val();
                        data.password = password;
                    }

                    if (password == "" || password == undefined) {
                        App.notify({
                            icon: 'fa fa-exclamation-triangle', 
                            title: 'Failed', 
                            message: 'Password harus diisi !', 
                            type: 'warning',
                        });
                        $('#password_confirm').focus();
                        return false;
                    }

                    $.ajax({
                        type:   "POST",
                        data:   data,
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('timer');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    $('#btn_list_table_shop_order').trigger('click');
                                }else{
                                    App.notify({
                                        icon: 'fa fa-exclamation-triangle', 
                                        title: 'Failed', 
                                        message: response.message, 
                                        type: 'danger',
                                    });
                                }
                            }
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
            return false;
        });

        // Button Input Resi Shop Order
        $("body").delegate( "a.btn-shop-order-resi", "click", function( e ) {
            var url         = $(this).data('url');
            var invoice     = $(this).data('invoice');
            var total       = $(this).data('total');
            var courier     = $(this).data('courier');
            var service     = $(this).data('service');

            var courier_html        = '';
            var resi_placeholder    = 'Nomor RESI';
            var resi_icon           = 'fa-truck';

            if ( courier == 'EKSPEDISI' ) {
                courier_html = `
                <div class="row justify-content-center">
                    <div class="col-sm-7">
                        <form class="form-horizontal" id="form-shop-order-courier">
                            <div class="form-group mb-2">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-truck"></i></span>
                                    </div>
                                    <input type="text" name="courier" id="courier" class="form-control text-uppercase" placeholder="Kurir" autocomplete="off" value="" />
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-truck"></i></span>
                                    </div>
                                    <input type="text" name="service" id="service" class="form-control text-uppercase" placeholder="Layanan Kurir" autocomplete="off" value="" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>`;
            } else if ( courier == 'PICKUP' ) {
                resi_placeholder = 'Nama Pengambil';
                resi_icon = 'fa-user';
                courier_html = `
                <div class="row align-items-center">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Metode Pengiriman :</small></div>
                    <div class="col-sm-6 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Metode Pengiriman :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${courier} </small></div>
                </div>`;
            } else {
                courier_html = `
                <div class="row align-items-center">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Kurir :</small></div>
                    <div class="col-sm-6 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Kurir :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${courier} </small></div>
                </div>
                <div class="row align-items-center">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Layanan :</small></div>
                    <div class="col-sm-6 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Layanan :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${service} </small></div>
                </div>`;
            }

            var msg_body    = `
                <h3 class="heading pt-4 pb-3 text-center">Input RESI pesanan</h3>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Invoice :</small></div>
                    <div class="col-sm-6 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Invoice :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${invoice} </small></div>
                </div>
                <div class="row align-items-center">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Total Pembayaran :</small></div>
                    <div class="col-sm-6 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Total Pembayaran :</small></div>
                    <div class="col-sm-6"><small class="heading-small text-warning font-weight-bold"> ${total} </small></div>
                </div>
                <hr class="mt-2 mb-3">
                ${courier_html}
                <hr class="mt-2 mb-3">
                <div class="row justify-content-center">
                    <div class="col-sm-7">
                        <form class="form-horizontal" id="form-shop-order-resi">
                            <div class="form-group mb-2">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa ${resi_icon}"></i></span>
                                    </div>
                                    <input type="text" name="resi" id="resi" class="form-control" placeholder="${resi_placeholder}" autocomplete="off" value="" />
                                </div>
                            </div>
                            <div class="form-group mb-1">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Password Konfirmasi" autocomplete="off" value="" />
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>`;
            bootbox.confirm(msg_body, function(result) {
                if( result == true ){
                    var data = {};
                    var password = '';
                    var resi = '';

                    if ( $('#courier', '#form-shop-order-courier').length ) {
                        courier = $('#courier', '#form-shop-order-courier').val();
                        if ( courier == "" ) {
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                message: 'Kurir harus diisi !', 
                                type: 'warning',
                            });
                            $('#courier', '#form-shop-order-courier').focus();
                            return false;
                        }
                        data.courier = courier;
                    }

                    if ( $('#service', '#form-shop-order-courier').length ) {
                        service = $('#service', '#form-shop-order-courier').val();
                        if ( service == "" ) {
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                message: 'Layanan Kurir harus diisi !', 
                                type: 'warning',
                            });
                            $('#service', '#form-shop-order-courier').focus();
                            return false;
                        }
                        data.service = service;
                    }

                    if ($('#resi', '#form-shop-order-resi').length) {
                        resi = $('#resi', '#form-shop-order-resi').val();
                        data.resi = resi;
                    }

                    if ($('#password_confirm', '#form-shop-order-resi').length) {
                        password = $('#password_confirm', '#form-shop-order-resi').val();
                        data.password = password;
                    }

                    if (resi == "" || resi == undefined) {
                        App.notify({
                            icon: 'fa fa-exclamation-triangle', 
                            message: resi_placeholder + ' harus diisi !', 
                            type: 'warning',
                        });
                        $('#resi').focus();
                        return false;
                    }

                    if (password == "" || password == undefined) {
                        App.notify({
                            icon: 'fa fa-exclamation-triangle', 
                            message: 'Password harus diisi !', 
                            type: 'warning',
                        });
                        $('#password_confirm').focus();
                        return false;
                    }

                    $.ajax({
                        type:   "POST",
                        data:   data,
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('timer');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    $('#btn_list_table_shop_order').trigger('click');
                                }else{
                                    App.notify({
                                        icon: 'fa fa-exclamation-triangle', 
                                        title: 'Failed', 
                                        message: response.message, 
                                        type: 'danger',
                                    });
                                }
                            }
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
            return false;
        });
    };

    return {
        init: function() {
            handleGeneralShopOrder();
        }
    };
}();

// ===========================================================
// Handle Commission
// ===========================================================
var Commission = function() {
    // --------------------------------
    // Handle Commission
    // --------------------------------
    var handleCommission = function() {
        if ( ! ('#search-daterange-commission').length || typeof(moment) != "function" )
            return;

        var startDate   = moment();
        var endDate     = moment();

        if ( daterange  = $( 'input[name=search_date_commission]').val() ) {
            daterange   = daterange.split( '|' );
            startDate   = moment( daterange[0], 'YYYY-MM-DD' );
            endDate     = moment( daterange[1], 'YYYY-MM-DD' );
        }

        cbCommission( startDate, endDate );

        $('#search-daterange-commission').daterangepicker({
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cbCommission);

        $('#search-daterange-commission').on('apply.daterangepicker', function(ev, picker) {
            startDate = picker.startDate.format('YYYY-MM-DD');
            endDate = picker.endDate.format('YYYY-MM-DD');

            $( 'input[name=search_date_commission]' ).val( startDate + '|' + endDate );
            $( '#btn_commission_list' ).click();
        });

        if ( $('input#newdate').length ) {
            var url     = $('input#newdate').data('url')
            var datest  = $('input#newdate').data('star')
            var dateen  = $('input#newdate').data('end')
            $('input#newdate').daterangepicker({
                "startDate": datest,
                "endDate": dateen,
                locale: {
                    format: 'YYYY/MM/DD'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, function(start, end, label) {
                window.location.href = url + '?daterange=' + start.format('YYYY-MM-DD') + '|' + end.format('YYYY-MM-DD');
            });
        }
    };

    var cbCommission = function( start, end ) {
        $( '#search-daterange-commission span' ).html( start.format( 'MMMM D, YYYY' ) + ' - ' + end.format( 'MMMM D, YYYY' ) );
    };

    // --------------------------------
    // Handle My Commission
    // --------------------------------
    var handleMyCommission = function() {
        if ( ! ('#search-daterange').length || typeof(moment) != "function" )
            return;

        var startDate = moment();
        var endDate = moment();

        if ( daterange = $( 'input[name=daterange]').val() ) {
            daterange = daterange.split( '|' );
            startDate = moment( daterange[0], 'YYYY-MM-DD' );
            endDate = moment( daterange[1], 'YYYY-MM-DD' );
        }

        cbMyCommission( startDate, endDate );

        $('#search-daterange').daterangepicker({
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cbMyCommission);

        $('#search-daterange').on('apply.daterangepicker', function(ev, picker) {
            startDate = picker.startDate.format('YYYY-MM-DD');
            endDate = picker.endDate.format('YYYY-MM-DD');

            $( 'input[name=daterange]' ).val( startDate + '|' + endDate );
            $( 'form[name=form-my-commission], form[name=form-commission]' ).submit();
        });
    };

    var cbMyCommission = function( start, end ) {
        $( '#search-daterange span' ).html( start.format( 'MMMM D, YYYY' ) + ' - ' + end.format( 'MMMM D, YYYY' ) );
    };

    return {
        init: function() {
            handleCommission();
            handleMyCommission();
        }
    };
}();

// ===========================================================
// Profile Function
// ===========================================================
var Profile = function() {

    // Handle Profile Function
    // --------------------------------------------------------------------------
    var handleProfile = function() {
        // Reset Change Password Form
        $('.btn-pass-reset').click(function(e){
            e.preventDefault();
            var msg         = $('.alert');

            $(msg).hide();
            $('.form-group').removeClass('has-danger');
            $('.invalid-feedback').hide().empty();
            $('#cpassword')[0].reset();
            return false;
        });

        // Save Update Profile
        $('#do_save_profile').click(function(e){
            e.preventDefault();
            saveProfile();
        });

        // Save Change Password
        $('#do_save_cpassword').click(function(e){
            e.preventDefault();
            var form  = $(this).data('form');
            saveCpassword(form);
        });

        // Save Change Password
        $('#do_save_cpassword_pin').click(function(e){
            e.preventDefault();
            var form  = $(this).data('form');
            saveCpassword(form);
        });
    };

    // --------------------------------------------------------------------------------------
    // General Function
    // --------------------------------------------------------------------------------------

    // Save Profile
    var saveProfile = function() {
        var form_personal   = $('#personal');
        var url             = form_personal.attr('action');
        var data            = form_personal.serialize();

        $.ajax({
            type:   "POST",
            data:   data,
            url:    url,
            beforeSend: function (){
                App.run_Loader('timer');
                $('#save_profile').modal('hide');
            },
            success: function( response ){
                App.close_Loader();
                response = $.parseJSON(response);

                if( response.status == 'login' ){
                    $(location).attr('href',response.url);
                }else{
                    if( response.status == 'success'){
                        if( response.logout ){
                            $(location).attr('href',response.logout);
                        }else{
                            App.notify({
                                icon: 'fa fa-check', 
                                message: response.message, 
                                type: 'success',
                            });
                        }
                    } else {
                        App.notify({
                            icon: 'fa fa-exclamation-triangle', 
                            message: response.message, 
                            type: 'danger',
                        });
                    }
                }
                App.scrollTo($('body'), 0);
            }
        });
        return false;
    };

    // Save Change Password
    var saveCpassword   = function(form = '') {
        if ( form ) {
            var form_cpass  = $('#'+form);
        } else {
            var form_cpass  = $('#cpassword');
        }
        var error       = $('.alert-danger', form_cpass);
        var success     = $('.alert-success', form_cpass);

        var url         = form_cpass.attr('action');
        var data        = form_cpass.serialize();

        $.ajax({
            type:   "POST",
            data:   data,
            url:    url,
            beforeSend: function (){
                App.run_Loader('timer');
                $('#save_cpassword').modal('hide');
                $('#save_cpassword_pin').modal('hide');
            },
            success: function( response ){
                App.close_Loader();
                response = $.parseJSON(response);

                if(response.message == 'error'){
                    if( response.login == 'login' ){
                        $(location).attr('href',response.data);
                    }else{
                        App.notify({
                            icon: 'fa fa-exclamation-triangle', 
                            message: response.data, 
                            type: 'danger',
                        });
                    }

                    // App.scrollTo($('body'), 0);
                }else{
                    if( response.access == "admin" ){
                        App.notify({
                            icon: 'fa fa-check', 
                            message: response.data, 
                            type: 'success',
                        });
                        // error.hide();
                        // success.empty();
                        // success.html(response.data).fadeIn('fast');
                        $('input[type="password"]', form_cpass).val('');
                    }else{
                        $(location).attr('href',response.data);
                    }
                }
            }
        });
    };

	return {
		init: function() {
            handleProfile();
		}
	};
}();

// ===========================================================
// General Setting Function
// ===========================================================
var GeneralSetting = function() {
    // Notification Setting Update
    var handleSaveSetting = function(url, value){
        if ( ! url || url == undefined || value == undefined ) { 
            return; 
        }
        $.ajax({
            type: "POST",
            url: url,
            data: { 'value' : value },
            beforeSend: function (){ App.run_Loader('timer'); },
            success: function( response ){ 
                App.close_Loader();
                response = $.parseJSON(response);
                if( response.status == 'login' ){
                    $(location).attr('href',response.url);
                }else{
                    if( response.status == 'success'){
                        var type = 'success';
                        var icon = 'fa fa-check';
                    }else{
                        var type = 'danger';
                        var icon = 'fa fa-exclamation-triangle';
                    }
                    App.notify({
                        icon: icon, 
                        title: 'Informasi', 
                        message: response.message, 
                        type: type,
                    });
                }
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
    };

    // General Function
    var handleGeneralSetting = function() {
        // Update General Setting
        // -----------------------------------------------
        $('button.general-setting').click(function(e){
            e.preventDefault();
            var url     = $(this).data('url');
            var id      = $(this).data('id');
            var type    = $(this).data('type');
            var wraper  = $(this).data('wraper');
            var value   = $('#'+id).val();

            handleSaveSetting(url, value);
        });

        // Update General Setting
        // -----------------------------------------------
        $('button.general-setting-each').click(function(e){
            e.preventDefault();
            var url     = $(this).data('url');
            var type    = $(this).data('type');
            var data    = new FormData();
            
            // get inputs
            $('textarea.'+type+', select.'+type+', input.'+type).each(function(){
                data.append($(this).attr("name"), $(this).val());
            });

            bootbox.confirm("Apakah anda yakin akan simpan data pengaturan ini ?", function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        data:   data,
                        processData:false,
                        contentType:false,
                        cache:false,
                        async:false,
                        beforeSend: function (){
                            App.run_Loader('timer');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            if( response.status == 'login' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    var type = 'success';
                                    var icon = 'fa fa-check';
                                }else{
                                    var type = 'danger';
                                    var icon = 'fa fa-exclamation-triangle';
                                }
                                App.notify({
                                    icon: icon, 
                                    message: response.message, 
                                    type: type,
                                });
                            }
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
        });

        // Button Action Get Data Notification 
        // -----------------------------------------------
        $("body").delegate( "a.notifdata", "click", function( e ) {
            e.preventDefault();
            var url     = $(this).attr('href');
            var wrapper = $('#notification_list').parents('.dataTables_wrapper');
            var m_edit  = $('#modal-form-notification');
            var m_view  = $('#notification_view_modal');
            var _form   = $('#form_notif_edit');

            $.ajax({
                type:   "POST",
                url:    url,
                beforeSend: function (){
                    App.run_Loader('timer');
                    _form[0].reset();
                },
                success: function( response ){
                    App.close_Loader();
                    response    = $.parseJSON(response);
                    if( response.status == 'login' ){
                        $(location).attr('href',response.url);
                    }else{
                        if( response.status == 'success'){
                            if ( response.process == 'edit' ) {
                                $('#notif_edit_title').text(response.notification.name);
                                $('#notif_id', _form).val(response.notification.id);
                                $('#notif_type', _form).val(response.notification.type);
                                $('#notif_title', _form).val(response.notification.title);
                                $('#notif_status', _form).val(response.notification.status);
                                $('#notif_content_plain', _form).val(response.notification.content);
                                CKEDITOR.instances['notif_content_email'].setData( response.notification.content );
                                CKEDITOR.instances['notif_content_email'].resize(CKEDITOR.instances['notif_content_email'].width, 300);
                                if ( response.notification.type == 'email' ) {
                                    $('#content_email', _form).show();
                                    $('#content_plain', _form).hide();
                                    $('#notif_edit_type').text('Email');
                                    $('#notif_edit_color').removeClass('label-success').addClass('label-primary');
                                    $('#notif_edit_icon').removeClass('fa-whatsapp').addClass('fa-envelope');
                                } else {
                                    $('#content_email', _form).hide();
                                    $('#content_plain', _form).show();
                                    $('#notif_edit_type').text('WhatsApp');
                                    $('#notif_edit_color').removeClass('label-primary').addClass('label-success');
                                    $('#notif_edit_icon').removeClass('fa-envelope').addClass('fa-whatsapp');
                                }
                                m_edit.modal('show');
                            } else {
                                $('#notif_view_title').text(response.notification.title);
                                $('#notif_view_content').html(response.notification.content);
                                if ( response.notification.type == 'email' ) {
                                    $('#notif_view_type').text('Email');
                                    $('#notif_view_color').removeClass('label-success').addClass('label-primary');
                                    $('#notif_view_icon').removeClass('fa-whatsapp').addClass('fa-envelope');
                                }else{
                                    $('#notif_view_type').text('WhatsApp');
                                    $('#notif_view_color').removeClass('label-primary').addClass('label-success');
                                    $('#notif_view_icon').removeClass('fa-envelope').addClass('fa-whatsapp');
                                }
                                m_view.modal('show');
                            }
                        }else{
                            App.alert({
                                type: 'danger',
                                icon: 'warning',
                                message: response.message,
                                container: wrapper,
                                place: 'prepend'
                            });
                        }
                    }
                }
            });
        }); 
    };

    // Handle Form Company Setting Function
    var handleFormSettingCompany = function() {
        var form        = $( '#form-setting-company' );
        var wrapper     = $( '.wrapper-setting-company' );
        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                company_name: {
                    required: true,
                },
                company_phone: {
                    required: true,
                },
                company_email: {
                    email: true,
                    required: true,
                },
                company_province: {
                    required: true,
                },
                company_city: {
                    required: true,
                },
                company_address: {
                    required: true,
                }
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length) {
                    error.insertAfter(element.parent(".input-group"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                App.alert({
                    type: 'danger', 
                    icon: 'exclamation-triangle', 
                    message: 'Ada beberapa error, silahkan cek formulir di bawah!', 
                    container: wrapper, 
                    closeInSeconds: 5,
                    place: 'prepend'
                }); 
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-danger'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-danger'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-danger'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url         = $(form).attr('action');
                bootbox.confirm("Apakah anda yakin akan simpan data infomasi Perumahan ini ?", function(result) {
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
                                
                                if( response.status == 'access_denied' ){
                                    $(location).attr('href',response.url);
                                }else{
                                    if( response.status == 'success'){
                                        App.notify({
                                            icon: 'fa fa-check-circle', 
                                            title: 'Success', 
                                            message: response.message, 
                                            type: 'success',
                                        });
                                    }else{
                                        App.notify({
                                            icon: 'fa fa-exclamation-triangle', 
                                            title: 'Failed', 
                                            message: response.message, 
                                            type: 'danger',
                                        });
                                    }
                                }
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

    // Handle Form Company Billing Function
    var handleFormSettingCompanyBilling = function() {
        var form        = $( '#form-setting-company-billing' );
        var wrapper     = $( '.wrapper-setting-company-billing' );
        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                company_bank: {
                    required: true,
                },
                company_bill: {
                    required: true,
                },
                company_bill_name: {
                    required: true,
                },
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length) {
                    error.insertAfter(element.parent(".input-group"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                App.alert({
                    type: 'danger', 
                    icon: 'exclamation-triangle', 
                    message: 'Ada beberapa error, silahkan cek formulir di bawah!', 
                    container: wrapper, 
                    closeInSeconds: 5,
                    place: 'prepend'
                }); 
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-danger'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-danger'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-danger'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url         = $(form).attr('action');
                bootbox.confirm("Apakah anda yakin akan simpan data Informasi Bank Perusahaan ini ?", function(result) {
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
                                
                                if( response.status == 'access_denied' ){
                                    $(location).attr('href',response.url);
                                }else{
                                    if( response.status == 'success'){
                                        App.notify({
                                            icon: 'fa fa-check-circle', 
                                            title: 'Success', 
                                            message: response.message, 
                                            type: 'success',
                                        });
                                    }else{
                                        App.notify({
                                            icon: 'fa fa-exclamation-triangle', 
                                            title: 'Failed', 
                                            message: response.message, 
                                            type: 'danger',
                                        });
                                    }
                                }
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

    // Handle Form Reward Setting Function
    var handleFormRewardSetting = function() {
        var form        = $( '#form-setting-reward' );
        var wrapper     = $( '.box-body' );
        if ( ! form.length ) {
            return;
        }

        // Handle Validation Setting Reward
        // ---------------------------------
        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                reward: {
                    required: true,
                },
                nominal: {
                    required: true,
                },
                point: {
                    required: true,
                }
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
                bootbox.confirm("Anada yakin akan simpan data setting Reward ?", function(result) {
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
                                    $(location).attr('href', response.url);
                                }

                                App.notify({
                                    icon: alert_icon, 
                                    message: response.message, 
                                    type: alert_type,
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
                return false;
            }
        });

        // Period Reward Change
        // ---------------------------------
        $("body").delegate( $(':input[name=is_lifetime]', form), "change", function( e ) {
            e.preventDefault();
            if ( $(':input[name=is_lifetime]').prop("checked") == true ) {
                $('#period_reward').hide();
            } else {
                $('#period_reward').show();
            }
        });
    };

    // ---------------------------------
    // Handle Setting Intro
    // ---------------------------------
    var handleSettingIntro = function() {
        var intro_img; 

        $('#product_img_thumbnail').on('click', function(e) {
            $('.file-image').trigger('click');
        });

        $('#product_file, .file-image').on('change', function(e) {
            readURL( $(this), $('#product_img_thumbnail') );
            intro_img = e.target.files;
        });

        // Button Save Intro
        // -----------------------------------------------
        $("body").delegate( ".btn-save-intro", "click", function( e ) {
            e.preventDefault();
            var url         = $('#form-intro').attr('action');
            var data        = new FormData();
        
            if ( intro_img ) {
                $.each(intro_img, function(key, value){
                    data.append('intro_img', value);
                });
            } else {
                App.notify({
                    icon: 'fa fa-exclamation-triangle', 
                    message: 'Silahkan pilih gambar terlebih dahulu !', 
                    type: 'danger',
                });
                return false;
            }

            bootbox.confirm("Apakah anda yakin akan simpan gambar intro ini ?", function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        data:   data,
                        processData:false,
                        contentType:false,
                        cache:false,
                        async:false,
                        beforeSend: function (){
                            App.run_Loader('timer');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    $('#form-intro')[0].reset();
                                    $('#form-intro').attr('action', response.url);
                                    $('#btn_list_table_setting_intro').trigger('click');
                                    var img_default = $('#form-intro').data('default');
                                    $('.img-information').hide();
                                    $('.img-thumbnail', $('#form-intro')).attr('src', img_default);
                                }else{
                                    App.notify({
                                        icon: 'fa fa-exclamation-triangle', 
                                        message: response.message, 
                                        type: 'danger',
                                    });
                                }
                            }
                        },
                        error: function( jqXHR, textStatus, errorThrown ) {
                            App.close_Loader();
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                message: 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', 
                                type: 'danger',
                            });
                        }
                    });
                }
            });
        });

        // Button Edit Intro
        // -----------------------------------------------
        $("body").delegate( ".btn-delete-intro", "click", function( e ) {
            e.preventDefault();
            var url         = $(this).attr('href');
            var img         = $(this).data('image');
            var msg         = `<h5 class="heading mt-4 text-center">Apakah anda yakin akan hapus intro ini ?</h5><center><img class="img-thumbnail" id="product_img_thumbnail" width="100%" src="${img}" ></center>`
            bootbox.confirm(msg, function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('timer');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    $('#btn_list_table_setting_intro').trigger('click');
                                }else{
                                    App.notify({
                                        icon: 'fa fa-exclamation-triangle', 
                                        message: response.message, 
                                        type: 'danger',
                                    });
                                }
                            }
                        },
                        error: function( jqXHR, textStatus, errorThrown ) {
                            App.close_Loader();
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                message: 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', 
                                type: 'danger',
                            });
                        }
                    });
                }
            });
        });
    };

    return {
        init: function() {
            handleGeneralSetting();
            handleFormSettingCompany();
            handleFormSettingCompanyBilling();
        },
        initReward: function() {
            handleFormRewardSetting();
        },
        initIntro: function() {
            handleSettingIntro();
        }
    };
}();

// ===========================================================
// Staff
// ===========================================================
var Staff = function() {
    var handleAccess = function() {
        var _staffAccessToggle = function( val ) {
            $( '.staff-access-box' ).hide();
            $( '.staff-access-box.staff-access-box-' + val ).show( 'fast' );
        };
        
        _staffAccessToggle( $( 'input[name=staff_access]:checked' ).val() );
        
        $('label.staff-access-toggle').click( function() {
            var input = $( this ).find( 'input[name=staff_access]' );
            var val = input.val();
            
            input.attr( 'checked', 'checked' );
            _staffAccessToggle( val );
        });

        // PIN Order Edit
        $("body").delegate( "a.delstaff", "click", function( event ) {
            event.preventDefault();
            var url = $(this).attr('href');
            var table_container = $('#list_pin_member').parents('.dataTables_wrapper');

            bootbox.confirm("Anda yakin akan menghapus Akun Staff?", function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('roundBounce');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response    = $.parseJSON(response);
                            if ( response.status == 'access_denied' ) {
                                $(location).attr('href', response.url);
                            }
                            if( response.success ){
                                msg = 'Staff telah berhasil di hapus !';
                                App.alert({
                                    type: 'success',
                                    icon: 'check',
                                    message: msg,
                                    container: table_container,
                                    place: 'prepend'
                                });
                            }else{
                                msg = 'Member tidak berhasil di hapus !';
                                App.alert({
                                    type: 'danger',
                                    icon: 'warning',
                                    message: msg,
                                    container: table_container,
                                    place: 'prepend'
                                });
                            }
                            $('#btn_list_table_staff').trigger('click');
                        },
                        error: function( jqXHR, textStatus, errorThrown ) {
                            App.close_Loader();
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                message: 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', 
                                type: 'danger',
                            });
                        }
                    });
                }
            });
        });
    };

    var handleResetPasswordStaff = function() {
        var formStaffResetPass  = $('#form_staff_reset_password');

        $("body").delegate(".grid-reset-password-staff", "click", function( event ) {
            event.preventDefault();
            var url = $(this).attr('href');
            var table_container = $('#list_staff').parents('.dataTables_wrapper');
            $.ajax({
                type:   "POST",
                url:    url,
                beforeSend: function (){
                    App.run_Loader('roundBounce');
                    formStaffResetPass[0].reset();
                },
                success: function( response ){
                    App.close_Loader();
                    response = $.parseJSON(response);
                    if( response.status == 'login' ){
                        $(location).attr('href',response.message);
                    }else{
                        if( response.status == 'error'){
                            App.alert({
                                type: 'danger',
                                icon: 'warning',
                                message: response.message,
                                container: table_container,
                                place: 'prepend'
                            });
                        }else{
                            $('#staff_id', '#form_staff_reset_password').val(response.data.id);
                            $('#staff_username', '#form_staff_reset_password').val(response.data.username);
                            $('#modal_staff_reset_password').modal('show');
                        }
                    }
                },
                error: function( jqXHR, textStatus, errorThrown ) {
                    App.close_Loader();
                    bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', function(){ 
                        location.reload();
                    });
                }
            });
        });

        $("body").delegate("#btn-staff-reset-password", "click", function( event ) {
            event.preventDefault();
            
            var url         = $(formStaffResetPass).attr('action');
            var data        = $(formStaffResetPass).serialize();
            var wrapper     = $('.wrapper-form_staff_reset_password');
            var _container  = $('#list_table_staff').parents('.dataTables_wrapper');

            $.ajax({
                type: "POST",
                data: data,
                url: url,
                beforeSend: function (){
                    App.run_Loader('roundBounce');
                    $('.alert').hide();
                },
                success: function( response ){
                    App.close_Loader();
                    response = $.parseJSON(response);                    
                    if( response.status == 'login' ){
                        $(location).attr('href',response.message);
                    }else{
                        if( response.status == 'success'){
                            App.alert({
                                type: 'success',
                                icon: 'check',
                                message: response.message,
                                container: _container,
                                place: 'prepend'
                            });
                            formStaffResetPass[0].reset();
                            $('#modal_staff_reset_password').modal('hide');
                        } else {
                            App.alert({
                                type: 'danger',
                                message: response.message,
                                container: wrapper,
                                place: 'prepend'
                            });
                        }
                        return false;
                    }                    
                },
                error: function( jqXHR, textStatus, errorThrown ) {
                    App.close_Loader();
                    bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', function(){ 
                        location.reload();
                    });
                }
            });
        });
    };
    
    return {
        init: function() {
            handleAccess();
            handleResetPasswordStaff();
        }
    };
}();

// ===========================================================
// Generation Tree
// ===========================================================
var Generation = function() {
    // Handle Generation Function Init
    // --------------------------------------------------------------------------
    var handleGenerationTree = function() {
		var el;
		var url;
		var offset = 0;
		var limit = 10;
        var wrapper = $('.alert-wrapper-gen');
		
		var loadMore = function( reset ) {
			reset = typeof reset == 'undefined' ? false : reset;
			if ( reset ) resetTree();
			
			var searchUsername = $( 'input[name=search_username_gen]' ).val();
			$.ajax({
                type:   "POST",
                data:   {
                	username: searchUsername
                },
                url:    url + '/' + offset + '/' + limit,
                beforeSend: function (){
                    $( '.loadmore .fa-refresh' ).addClass( 'fa-spin' );
                },
                success: function( response ){
                	$( '.loadmore .fa-refresh' ).removeClass( 'fa-spin' );
                	response = $.parseJSON( response );
                	if ( response.success ) {
	                	offset += limit;
	                    addTree( response.data );
	                    return;
					}
                    
                    App.alert({
                        type: 'danger', 
                        icon: 'warning', 
                        message: 'Data tidak ditemukan!', 
                        container: wrapper, 
                        place: 'prepend'
                    });
                }
            });
		};
		
		var addTree = function( tree ) {
			id = 'generation-' + offset;
			$( el ).append( '<div id="' + id + '"></div>' );
			refreshTree( '#' + id, tree );
		};
		
		var refreshTree = function( selector, tree ) {
			$( selector ).treeview({ 
				data: tree,
                expandIcon: 'fa fa-plus',
                collapseIcon: 'fa fa-minus',
				nodeIcon: 'fa fa-user',
				color: '#27A9E3',
				showBorder: true,
				levels: $( el ).data( 'levels' ),
				showTags: true,
				highlightSelected: false,
				onNodeSelected: function(event, node) {}
			});
		};
		
		var resetTree = function() {
			offset = 0;
			$( el ).html( '' );
		};
		
		var handleButton = function() {
			$( '.loadmore' ).click( function( e ) {
				e.preventDefault();
				loadMore();
			});
			
			$( '#btn_search_username_gen' ).click( function() {
				loadMore( true );
			});
			
			$( 'input[name=search_username_gen]' ).keyup( function( evt ) {
			    if ( evt.keyCode == 13 ) {
			        $( '#btn_search_username_gen' ).click();
			    }
			});
		};
		
		return {
			init: function() {
				el = $( '.generations' );
				if ( ! el.length )
					return;
				
				url = el.data( 'url' );
				loadMore();
				handleButton();
			},
			loadMore: function( offset ){
				loadMore( offset );
			}
		};
	}();
    
    return {
        init: function() {
            handleGenerationTree.init();
        }
    };
}();
