var ActivationPersonalSales = function() {
    var form_aps        = $('#personal-sales-activation');
    var wrapper         = $('.wrapper-form-aps');

    // Handle Validation Form
    var handleValidationActivationPersonalSales = function() {
        form_aps.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                aps_member_username: {
                    minlength: 5,
                    required: function(element) {
                        return $('label#other_agent').hasClass('active');
                    }
                },
                aps_amount: {
                    required: true,
                    min: 15,
                    max: $('#aps_amount').data('max'),
                    number: true
                }
            },
            messages: {
                aps_member_username: {
                    minlength: "Minimal username 5 karakter",
                    required: "Username Agent harus di isi",
                },
                aps_amount: {
                    min: "Minimal jumlah produk aktif adalah 15 liter",
                    required: "Jumlah produk aktif harus di isi",
                    number: "Jumlah produk hanya dapat diinputkan angka!"
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
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                var prodactive      = form_aps.data('prodactive');
                var amount          = ( $('#aps_amount').length ? $('#aps_amount').val() : 0 );

                if ( parseInt(amount) > parseInt(prodactive) ) {
                    App.notify({
                        icon: 'fa fa-exclamation-triangle', 
                        message: 'Produk Aktif Anda tidak mencukupi jumlah aktivasi personal sales ini !', 
                        type: 'danger',
                    });
                    App.scrollTo($('#personal-sales-activation'), 0);
                    return false;
                }
                $('#modal-save-aps').modal('show');
            }
        });
    };

    // Handle General Activation Personal Sales Form
    var handleGeneralAPSForm = function() {
        // QTY Min
        $("body").delegate( "#btn_amount_minus", "click", function( event ) {
            event.preventDefault();
            qtyMin("#btn_amount_minus", 15);
        });
        
        // QTY Plus
        $("body").delegate( "#btn_amount_plus", "click", function( event ) {
            event.preventDefault();
            qtyPlus("#btn_amount_plus", 15);
        });
        
        // Select APS For
        $('label.aps').each(function(){
            $(this).click(function(e){
                e.preventDefault();
                var val = $(this).find('input.toggle').val();
                $('#aps_member_username').val('');

                if( val == 'other_agent' ){
                    $('#other_agent_form').fadeIn();
                    $('#other_agent_form').find('.help-block').remove();
                    $('#aps_member_username').attr('disabled', false);
                }else{
                    $('#other_agent_form').fadeOut();
                    $(this).parent().parent().removeClass('has-error');
                    $('#other_agent_form').find('.help-block').empty().hide();
                    $('#agent-info').empty().hide();
                }
            });
        });
        
        // Search Agent Username
        $('#aps_member_username').bind('blur', function(){
            $('#btn_search_aps_username').trigger('click');
        });

        $("body").delegate( "#btn_search_aps_username", "click", function( e ) {
            e.preventDefault();
            var username    = $('#aps_member_username').val();
            var url         = $(this).data('url');
            var el          = $('#agent-info');
            var search      = true;
            
            if ( username == '' ) {
                search      = false;
                $(el).empty().hide();
                $('#aps_member_username').val('');
            }

            if ( $('input[name="member_agent_username"]').length ) {
                if ( $('input[name="member_agent_username"]').val() == username ) {
                    search  = false;
                }
            }

            if ( search ) {
                $.ajax({
                    type:   "POST",
                    data:   { 'username' : username },
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
                                $('#aps_member_username').val('');
                            }else{
                                App.notify({
                                    icon: 'fa fa-check-circle', 
                                    title: 'Success', 
                                    message: response.message, 
                                    type: 'success',
                                });
                                $(this).parent().parent().find('div.invalid-feedback').hide();
                                $(el).html(response.info).fadeIn('fast');
                            }
                        }
                    }
                });
            }
            return false;
        });
        
        // Save Aktivasi Personal Sales
        $('#do_save_activation_personal_sales').click(function(e){
            e.preventDefault();
            var formid  = $(this).data('formid');
            saveAPS($('#' + formid));
        });

        // Reset Form Aktivasi Personal Sales
        $('.btn-personal-sales-activation-reset').click(function(e){
            e.preventDefault();
            if( $('#agent-info').is(":visible") ){ $('#agent-info').empty().hide(); }
            form_aps[0].reset();
        });
    };

    // Save Aktivasi Personal Sales
    var saveAPS = function(form) {
        var form_aps    = $(form);
        var url         = form_aps.attr('action');
        var data        = form_aps.serialize();

        $.ajax({
            type:   "POST",
            data:   data,
            url:    url,
            beforeSend: function (){
                App.run_Loader('timer');
                $('#modal-save-aps').modal('hide');
            },
            success: function( resp ){
                App.close_Loader();
                resp = resp.replace(/<br\s*[\/]?>/g,"");
                response = $.parseJSON(resp);
                if(response.status == 'error'){
                    if( response.login == 'login' ){
                        $(location).attr('href',response.url);
                    }else{
                        App.notify({
                            icon: 'fa fa-exclamation-triangle', 
                            title: 'Failed', 
                            message: response.message, 
                            type: 'danger',
                        });
                    }
                }else if(response.status == 'success'){
                    $('#success_aps').empty().html(response.message);
                    $('#modal-success-save-aps').modal('show');
                    $('#modal-success-save-aps').on('hidden.bs.modal', function () {
                        if( $('#agent-info').is(":visible") ){ $('#agent-info').empty().hide(); }
                        form_aps[0].reset();
                        location.reload();
                    });
                }

                App.scrollTo(wrapper, 0);
                return false;
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
    
    /*
    |--------------------------------------------------------------------------
    | + / - Quantity Func
    |--------------------------------------------------------------------------
    */
    function qtyPlus(el, step = 1) {
        var count   = $(el).closest(".input-group").find('#aps_amount').val();
        var countEl = $(el).closest(".input-group").find('#aps_amount');
    
        // count++;
        count = parseInt(count) + parseInt(step);
        countEl.val(count).change();
    }
    
    function qtyMin(el, step = 1) {
        var count   = $(el).closest(".input-group").find('#aps_amount').val();
        var countEl = $(el).closest(".input-group").find('#aps_amount');
    
        if ( parseInt(count) > parseInt(step) ) {
            // count--;
            count = parseInt(count) - parseInt(step);
            countEl.val(count).change();
        }
    }

    return {
        init: function() {
            handleValidationActivationPersonalSales();
            handleGeneralAPSForm();
        }
    };
}();
    