var UpgradeMember = function() {
    var form_upg            = $('#upgrade_member_form');
    var wrapper             = $('.upgrade_member_wrapper');
    var alert_msg           = $('#alert');

    var access              = form_upg.data('access');
    var upMe                = true;
    var _dataPackage        = [];
    var _dataListPackage    = []; 
    var _dataPIN            = [];
    var _dataPINList        = []; 

    // Reset Form Upgrade
    var resetForm = function() {
        form_upg[0].reset();
        alert_msg.hide();
        clearSearchMember();
    };

    // Clear Search Member
    var clearSearchMember = function() {
        $(':input.up_info', form_upg).val('');
        _dataPackage        = [];
        _dataListPackage    = Object.assign([], _dataPackage);
        generateSelectPackage();
    };

    // Handle Validation Form
    var handleValidationUpgradeMember = function() {
        form_upg.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                up_member_package: {
                    required: true,
                },
                up_member_pin: {
                    required: true,
                },
                up_member_username: {
                    minlength: 3,
                    required: function(element) {
                        if( access == 'admin' ){
                            return true;
                        }else{
                            return $('label#up_others').hasClass('active');
                        }
                    }
                },
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").size() > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').size() > 0) { 
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').size() > 0) { 
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').size() > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').size() > 0) { 
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else if (element.parents('.icheckbox_minimal-green').size() > 0) { 
                    // error.appendTo(element.parents('.icheckbox_minimal-green').attr("data-error-container"));
                    var new_element = element.parents('.icheckbox_minimal-green').parents('.input-group');
                    error.insertAfter(new_element); // for other inputs, just perform default behavior
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit 
                alert_msg.html('<button class="close" data-close="alert" type="button"><i class="fa fa-times"></i></button>Anda memiliki beberapa kesalahan. Silakan cek di formulir bawah ini!');          
                alert_msg.removeClass('alert-success').addClass('alert-danger').show();
                App.scrollTo(alert_msg, -200);
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
                saveUpgradeMember();
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

    // Search Member
    var searchMemberUpgrade = function() {
        var searchData  = {};

        if ( access == 'admin' ) {
            upMe = false;    
        }

        if ( upMe ) {
            searchData  = { upfor: 'upme', access: access, username: form_upg.data('registrar') };
        } else {
            username    = $( 'input[name=up_member_username]' ).val();
            if ( ! username.length ){
                return;
            }
            searchData  = { upfor: 'upother', access: access, username: username };
        }

        if ( {} != searchData ) {
            alert_msg.hide();
            $("div#mask").fadeIn();
            clearSearchMember();

            API.get( 'get_upgrade_package', searchData, function( response ) {
                if ( ! response.success ) {
                    bootbox.alert( 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.' );
                    return false;
                }

                if( response.status == 'login' ){
                    $(location).attr('href',response.message);
                    return false;
                }

                if( response.status == 'available'){
                    var _type   = 'success';
                    var _icon   = 'check';
                } else {
                    var _type   = 'danger';
                    var _icon   = 'warning';
                }

                App.alert({
                    type: _type,
                    icon: _icon,
                    message: response.message,
                    container: wrapper,
                    place: 'prepend'
                });

                if ( response.info ) {
                    $(':input[name=up_member_id]', form_upg).val(response.info.id);
                    $(':input[name=up_username]', form_upg).val(response.info.username);
                    $(':input[name=up_name]', form_upg).val(response.info.name);
                    $(':input[name=up_package]', form_upg).val(response.info.package.package_name);
                }

                if ( response.upgrade ) {
                    _dataPackage = [];
                    $.each(response.upgrade, function(index, val) {
                         _dataPackage.push(val)
                    });

                    _dataListPackage = Object.assign([], _dataPackage);
                    generateSelectPackage();
                }
            });
        } else {
            bootbox.alert( 'Cari data member error!' );
        }
    };

    // Generate Select Package
    var generateSelectPackage = function() {
        var selectPackage = $('select[name=up_member_package]', form_upg);
        if ( ! selectPackage.length ) {  
            return;
        }
        selectPackage.empty().append('<option value="" omzet="0">Pilih Agen Upgrade</option>');
        $.each(_dataListPackage, function(index, val) {
            selectPackage.append('<option value="' + val.package + '" omzet="' + val.omzet + '">' + val.name + '</option>');
        });
    };

    // Handle Get PIN
    var handleGetActivePIN = function() {
        var id_member   = form_upg.data('id');
        var package     = $('select[name=up_member_package]', form_upg).val();

        var params      = {
            id_member : id_member,
            status: 'active',
            count: 0,
            product : package
        };

        _dataPIN        = [];
        _dataPINList    = Object.assign([], _dataPIN);

        API.get( 'get_pin', params, function( response ) {
            if ( ! response.success ) {
                return false;
            }

            _dataPIN    = [];
            $.each(response.data, function(index, val) {
                 _dataPIN.push(val)
            });

            _dataPINList = Object.assign([], _dataPIN);
            generateSelecttPIN();
        });
    };

    // Generate Select PIN
    var generateSelecttPIN = function() {
        sortRecordPIN();
        var selectPIN = $('select[name=up_member_pin]', form_upg);
        if ( ! selectPIN.length ) {  
            return;
        }

        selectPIN.empty().append('<option value="">-- Pilih PIN --</option>');
        $.each(_dataPINList, function(index, val) {
            selectPIN.append('<option value="' + val.id + '">' + val.pin + '</option>');
        });
    };

    // Sort Record PIN
    var sortRecordPIN = function() {
        _dataPINList.sort(function(a, b) {
            return a.order - b.order;
        });
    };

    var _upOptToggle = function( val ) {
        if ( access != 'admin' ) {
            $( '.up-opt' ).hide();
            $( 'input[name=up_member_id]', form_upg ).val('');
            upMe = val == 'me';
            resetForm();
            searchMemberUpgrade();
            if ( val == 'others') {
                $('#up_me').removeClass('bg-blue').addClass('bg-gray');
                $('#up_others').removeClass('bg-gray').addClass('bg-blue');
            } else {
                $('#up_others').removeClass('bg-blue').addClass('bg-gray');
                $('#up_me').removeClass('bg-gray').addClass('bg-blue');
            }
            
            $( '.up-opt.up-opt-' + val ).show( 'fast' );
        }
    };

    // Handle General Upgrade Form
    var handleGeneralUpgradeForm = function() {
        $('select[name=up_member_package]', form_upg).change(function(e){
            e.preventDefault();
            if ( access != 'admin' ) {
                handleGetActivePIN();
            }
        });

        $('label.up-opt-toggle').click( function() {
            var input = $( this ).find( 'input[name=upopt]' );
            var val = input.val();

            console.log(val);
            input.attr( 'checked', 'checked' );
            _upOptToggle( val );
        });

        // Input Search Member
        $(':input[name=up_member_username]', form_upg).bind('blur', function(){
            $('#btn_search_member_upgrade').trigger('click');
        });

        // Button Search Member
        $(form_upg).delegate( "#btn_search_member_upgrade", "click", function( e ) {
            e.preventDefault();
            searchMemberUpgrade();
        });

        // reset Form
        $(form_upg).delegate( ".btn-upgrade-reset", "click", function( e ) {
            e.preventDefault();
            resetForm();
            searchMemberUpgrade();
        });

        $('#success_save_upg_member').on('hidden.bs.modal', function () {
            location.reload();
        });
    };

    // Save Upgrade Member
    var saveUpgradeMember = function() {
        var url         = form_upg.attr('action');
        var data        = form_upg.serialize();

        alert_msg.hide();

        bootbox.confirm("Apakah data Upgrade Member sudah benar?", function(result) {
            if( result == true ){
                $.ajax({
                    type:   "POST",
                    data:   data,
                    url:    url,
                    beforeSend: function (){
                        $("div#mask").fadeIn();
                    },
                    success: function( resp ){
                        $("div#mask").fadeOut();
                        resp = resp.replace(/<br\s*[\/]?>/g,"");
                        response = $.parseJSON(resp);

                        if( response.status == 'login' ){
                            $(location).attr('href',response.message);
                        }else{
                            if(response.status == 'success'){
                                var _type   = 'success';
                                var _icon   = 'check';
                                form_upg[0].reset();
                                $('#success_upg_member').empty().html(response.info);
                                $('#success_save_upg_member').modal('show');
                            } else {
                                var _type   = 'danger';
                                var _icon   = 'warning';
                            }
                            App.alert({
                                type: _type, 
                                icon: _icon, 
                                message: response.message, 
                                container: wrapper, 
                                place: 'prepend'
                            });
                        }

                        App.scrollTo($('body'), 0);
                        return false;
                    }
                });
            }
        });
    };

    return {
        init: function() {
            handleValidationUpgradeMember();
            resetForm();
            handleGeneralUpgradeForm();
            searchMemberUpgrade();
        }
    };
}();
    