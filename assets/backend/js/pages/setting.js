var CustomPage = function () {

    var product_img;

    $.fn.digits = function(){
        return this.each(function() {
            $(this).val( $(this).val().replace(/\D/g,"").replace(/\B(?=(\d{3})+(?!\d))/g, ".") );
        });
    };

    var readURL = function(file_id, input) {
        if (input[0].files && input[0].files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                file_id.attr('src', e.target.result);
            }
            reader.readAsDataURL(input[0].files[0]);
        }
    };

    var addlist = function (obj, out) {
        if ( obj == '' || obj == undefined ) {
            return;
        }

        var grup = obj.form[obj.name];
        var len = grup.length;
        var list = new Array();

        if (len > 1) {
           for (var i = 0; i < len; i++) {
              if (grup[i].checked) {
                 list[list.length] = grup[i].value;
              }
           }
        } else {
           if (grup.checked) {
              list[list.length] = grup.value;
           }
        }

        document.getElementById(out).value = list.join(', ');
        return;
    }

    //show all data product function
    var handleDataGridProduct = function(){

        var url             = $('#list_product').data( 'url' );
        var grid            = new Datatable();

        grid.init({
            src: $('#list_product'),
            onSuccess: function(grid) {},
            onError: function(grid) {},
            dataTable: {  // here you can define a typical datatable settings from http://datatables.net/usage/options
                "aLengthMenu": [
                    [10, 20, 50, 100, -1],
                    [10, 20, 50, 100, "All"]                        // change per page values here
                ],
                "iDisplayLength": 50,                               // default record count per page
                "bServerSide": true,                                // server side processing
                "sAjaxSource": url,      // ajax source
                "aoColumnDefs": [
                  { 'bSortable': false, 'aTargets': [ -1, 0 ] },
                ]
            }
        });
    }
    
    var handleValidationProduct = function() {
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation
        var _form   = $( '#form-product' );
        var msg     = $( '#alert' );

        if ( ! _form.length ) {
            return;
        }
        
        _form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                product: {
                    required: true,
                    unamecheck: true,
                    minlength: 3
                },
                product_name: {
                    required: true,
                    minlength: 4,
                },
                bv: {
                    required: true,
                },
                amount: {
                    required: true,
                },
                amount_member: {
                    required: true,
                },
                amount_customer: {
                    required: true,
                },
                weight: {
                    required: true,
                }
            },
            messages: {
                product: {
                    unamecheck: "Username tidak memenuhi kriteria"
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
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit 
                msg.html('<button class="close" data-close="alert"></button>Anda memiliki beberapa kesalahan. Silakan cek di formulir bawah ini!');
                msg.removeClass('alert-success').addClass('alert-danger').show();
                App.scrollTo(msg, -200);
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
                var data        = new FormData();
                var description = CKEDITOR.instances['desc'].getData();

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

                bootbox.confirm("Simpan data produk ?", function(result) {
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
                                $(location).attr('href', response.url);
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
        });
        
        $.validator.addMethod("unamecheck", function(value) {
            return /^[A-Za-z0-9]{3,16}$/i.test(value);   // consists of only these
        });

        $('#product_image').on('change', function(e) {
            readURL( $('#view_product_image'),  $(this));
            product_img = e.target.files;
        });
    };
    
    var handleValidationWD = function() {

        var _form           = $( '#form-setting-wd' );
        var form_container  = $( '.box-body' );

        if ( ! _form.length ) {
            return;
        }
        
        _form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                wd_min: {
                    required: true,
                },
                wd_min_manual: {
                    required: true,
                },
                wd_fee: {
                    required: true,
                },
                wd_tax: {
                    required: true,
                },
                wd_auto_maintenance_percent: {
                    required: true,
                },
                wd_auto_maintenance_max: {
                    required: true,
                },
                wd_auto_maintenance_point: {
                    required: true,
                }
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").size() > 0) {
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
                    container: form_container,
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
                bootbox.confirm("Anada yakin akan edit data setting Withdraw ?", function(result) {
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
                                var alert_icon = 'warning';
                                if ( response.status == 'success' ) {
                                    alert_type = 'success';
                                    alert_icon = 'check';
                                }

                                App.alert({
                                    type: alert_type,
                                    icon: alert_icon,
                                    message: response.message,
                                    container: form_container,
                                    closeInSeconds: 5,
                                    place: 'prepend'
                                });
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
    
    var handleValidationReward = function() {

        var _form           = $( '#form-reward' );
        var form_container  = $( '.box-body' );

        if ( ! _form.length ) {
            return;
        }
        
        _form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                reward: {
                    required: true,
                },
                type: {
                    required: true,
                },
                omzet: {
                    required: true,
                },
                point_left: {
                    required: true,
                },
                point_right: {
                    required: true,
                },
                period: {
                    required: true,
                }
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").size() > 0) {
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
                    container: form_container,
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
                bootbox.confirm("Anada yakin akan simpan data setting reward ?", function(result) {
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
                                $(location).attr('href', response.url);
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

        $( ':input.numbercurrency', _form ).each( function() {
            $( this ).digits();
        });
        $( ':input.numbercurrency', _form ).keyup(function(e){
            e.preventDefault();
            if (e.which >= 37 && e.which <= 40) return;

            $(this).val(function(index, value) {
                return value.replace(/\D/g,"").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            });
        });

        if ( $('input#period').length ) {
            var datest  = $('input#period').data('star')
            var dateen  = $('input#period').data('end')
            $('input#period').daterangepicker({
                "startDate": datest,
                "endDate": dateen,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                ranges: {
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')]
                }
            });
        }

        $( ':input.package_checklist', _form ).change(function(e){
            e.preventDefault();
            addlist( $(this)[0], 'package_list' );
        });
    };
    
    var handleRewardPointConvertion = function() {
        var _form           = $( '#form-reward-point-convertion' );
        var _table          = $( '#table_reward_point_convertion' );
        var _tbody          = $( '#table_reward_point_convertion > tbody ' );
        var form_container  = $( '#reward_convertion' );

        if ( ! _form.length ) {
            return;
        }

        _form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block text-red', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {},
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").size() > 0) {
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
                    container: form_container,
                    place: 'prepend'
                });
            },
            submitHandler: function (form) {
                var url             = $(form).attr('action');
                var _count_data     = $('tr', _tbody).length;
                var _empty_row      = _tbody.find('tr.data-empty');

                if ( _empty_row.length ) {
                    _count_data -= 1;
                }

                if ( _count_data == '' || _count_data == 0  || _count_data == undefined  ) {
                    App.alert({
                        type: 'danger',
                        icon: 'warning',
                        message: 'Nilai Konversi Poin Reward tidak boleh kosong. Silahkan masukan nilai konversi omzet member !',
                        container: form_container,
                        closeInSeconds: 5,
                        place: 'prepend'
                    });

                    return false;
                }

                bootbox.confirm("Anada yakin akan simpan data konversi reward poin ?", function(result) {
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
                                var alert_icon = 'warning';

                                if ( response.status == 'login' ) {
                                    return $(location).attr('href', response.url);
                                }

                                if ( response.status == 'success' ) {
                                    alert_type = 'success';
                                    alert_icon = 'check';
                                    row_load_data(response.data);
                                }

                                App.alert({
                                    type: alert_type,
                                    icon: alert_icon,
                                    message: response.message,
                                    container: form_container,
                                    closeInSeconds: 5,
                                    place: 'prepend'
                                });
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

        var setRuleValidatePoint = function() {
            if ( $( ':input.numbercurrency', _form ).length ) {
                $( ':input.numbercurrency', _form ).rules( "remove" );
                $( ':input.numbercurrency', _form ).each( function() {
                    $( this ).rules( "add", {
                        required: true
                    });
                });
            }
            return false;
        };

        var row_load_data = function( dataConvert ) {
            if ( ! dataConvert || dataConvert == '' || dataConvert == undefined ) { 
                return;
            }

            var _row = '';
            _tbody.empty();
            $.each(dataConvert, function(index, val) { 
                var point = val.point + 0;
                _row += `<tr data-id="${val.id}" data-rowid="${val.id}">
                            <td class="text-center">
                                <span class="required">*</span>
                                <input type="hidden" name="convert[${val.id}][id]" class="hide" value="${val.id}" />
                            </td>
                            <td><input type="text" name="convert[${val.id}][omzet]" class="form-control numbercurrency" placeholder="Omzet" value="${val.omzet}" /></td>
                            <td><input type="text" name="convert[${val.id}][point]" class="form-control numberdecimal" placeholder="Point" value="${point}" /></td>
                            <td class="text-center"><button class="btn btn-xs btn-flat btn-danger btn-delete-convert" type="button" data-id="${val.id}" ><i class="fa fa-trash"></i></button></td>
                        </tr>`;
            });

            _tbody.append(_row);

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

            setRuleValidatePoint();
        }

        var row_append = function( _id = 0, _num = 1 ) {
            if ( ! _id || _id == '' || _id == 0 || _id == undefined ) { 
                return;
            }

            var _row = `<tr data-id="${_id}">
                            <td class="text-center">
                                <span class="required">*</span>
                                <input type="hidden" name="convert[${_id}][id]" class="hide" />
                            </td>
                            <td><input type="text" name="convert[${_id}][omzet]" class="form-control numbercurrency" placeholder="Omzet" /></td>
                            <td><input type="text" name="convert[${_id}][point]" class="form-control numbercurrency" placeholder="Point" /></td>
                            <td class="text-center"><button class="btn btn-xs btn-flat btn-danger btn-remove-convert" type="button" data-id="${_id}" ><i class="fa fa-minus"></i></button></td>
                        </tr>`;
            _tbody.append(_row);

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

            setRuleValidatePoint();
        }

        $(_form).delegate( ".btn-add-convert", "click", function( e ) {
            e.preventDefault();
            var _count_data     = $('tr', _tbody).length;
            var _empty_row      = _tbody.find('tr.data-empty');
            var _last_row       = $('tr:last', _tbody);
            var _last_id        = _last_row.data('id');
            var _new_id         = _last_id + 1;
            var _no_row         = _count_data + 1;

            if ( _empty_row.length ) {
                _empty_row.remove();
                _no_row -= 1;
            }

            row_append(_new_id, _no_row);
        });

        $(_form).delegate( ".btn-remove-convert", "click", function( e ) {
            e.preventDefault();
            var _id         = $(this).data('id');
            var _tr         = $(this).parents('tr');
            var _count_data = $('tr', _tbody).length;
            _tr.remove();
            if ( _count_data == 1 ) {
                var _trEmpty    =  `<tr class="data-empty" data-id=0 data-rowid=0>
                                        <td class="text-center" colspan="4">Data Reward Konversi Poin tidak ditemukan</td>
                                    </tr>`;
                _tbody.append(_trEmpty);
            }

        });

        $(_form).delegate( ".btn-delete-convert", "click", function( e ) {
            e.preventDefault();
            var _id         = $(this).data('id');
            var _url        = _form.data('urldel');
            var _tr         = $(this).parents('tr');
            var _count_data = $('tr', _tbody).length;

            var _trEmpty    =  `<tr class="data-empty" data-id=0 data-rowid=0>
                                    <td class="text-center" colspan="4">Data Reward Konversi Poin tidak ditemukan</td>
                                </tr>`;

            bootbox.confirm("Anada yakin akan menghapus data konversi reward poin ?", function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    _url,
                        data:   { id : _id },
                        beforeSend: function (){
                            App.run_Loader('timer');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            var alert_type = 'danger';
                            var alert_icon = 'warning';

                            if ( response.status == 'login' ) {
                                return $(location).attr('href', response.url);
                            }

                            if ( response.status == 'success' ) {
                                alert_type = 'success';
                                alert_icon = 'check';
                                _tr.remove();
                                if ( _count_data == 1 ) {
                                    _tbody.append(_trEmpty);
                                }
                            }

                            App.alert({
                                type: alert_type,
                                icon: alert_icon,
                                message: response.message,
                                container: form_container,
                                closeInSeconds: 5,
                                place: 'prepend'
                            });
                        },
                        error: function( jqXHR, textStatus, errorThrown ) {
                            App.close_Loader();
                            bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.');
                        }
                    });
                }
            });           

        });
    };
    
    var handleRewardTripPeriod = function() {
        var _form           = $( '#form-reward-trip-period' );
        var form_container  = $( '#reward_trip_period' );

        if ( ! _form.length ) {
            return;
        }

        _form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block text-red', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                period_trip: {
                    required: true,
                },
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").size() > 0) {
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
                    container: form_container,
                    place: 'prepend'
                });
            },
            submitHandler: function (form) {
                var url             = $(form).attr('action');
                bootbox.confirm("Anada yakin akan simpan data Periode Trip Reward ?", function(result) {
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
                                var alert_icon = 'warning';

                                if ( response.status == 'login' ) {
                                    return $(location).attr('href', response.url);
                                }

                                if ( response.status == 'success' ) {
                                    alert_type = 'success';
                                    alert_icon = 'check';
                                }

                                App.alert({
                                    type: alert_type,
                                    icon: alert_icon,
                                    message: response.message,
                                    container: form_container,
                                    closeInSeconds: 5,
                                    place: 'prepend'
                                });
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

        if ( $('input#period_trip').length ) {
            var datest  = $('input#period_trip').data('star')
            var dateen  = $('input#period_trip').data('end')
            $('input#period_trip').daterangepicker({
                "startDate": datest,
                "endDate": dateen,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                ranges: {
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')]
                }
            });
        }
    };

    var handleDataGridPromo = function(){

        var url             = $('#list_promo').data( 'url' );
        var grid            = new Datatable();

        grid.init({
            src: $('#list_promo'),
            onSuccess: function(grid) {},
            onError: function(grid) {},
            dataTable: {  // here you can define a typical datatable settings from http://datatables.net/usage/options
                "aLengthMenu": [
                    [10, 20, 50, 100, -1],
                    [10, 20, 50, 100, "All"]                        // change per page values here
                ],
                "iDisplayLength": 50,                               // default record count per page
                "bServerSide": true,                                // server side processing
                "sAjaxSource": url,      // ajax source
                "aoColumnDefs": [
                  { 'bSortable': false, 'aTargets': [ -1, 0 ] },
                ]
            }
        });
    };
    
    var handleValidationPromo = function() {
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation
        var _form   = $( '#form-promo' );
        var msg     = $( '#alert' );

        if ( ! _form.length ) {
            return;
        }
        
        _form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                promo: {
                    required: true
                },
                discount: {
                    required: true,
                }
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
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit 
                msg.html('<button class="close" data-close="alert"></button>Anda memiliki beberapa kesalahan. Silakan cek di formulir bawah ini!');
                msg.removeClass('alert-success').addClass('alert-danger').show();
                App.scrollTo(msg, -200);
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
                var discount    = $(':input[name="discount"]', $(form)).val();
                var discount    = discount.replace('%', '');
                var discount    = $.trim(discount);

                if ( discount == 0 || discount == '' ) {
                    App.alert({
                        type: 'danger',
                        icon: 'warning',
                        message: 'Nilai diskon tidak boleh kosong atau nol !',
                        container: $(form),
                        closeInSeconds: 5,
                        place: 'prepend'
                    });
                    return false;
                }

                var data = {
                    id : $(':input[name="id"]', $(form)).val(),
                    promo : $(':input[name="promo"]', $(form)).val(),
                    discount : discount,
                    status : $('select[name="status"]', $(form)).val()
                }
                bootbox.confirm("Anada yakin akan simpan data setting promo ?", function(result) {
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
                                $(location).attr('href', response.url);
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

            $( ':input.numbercurrency').inputmask("currency", {
                prefix: "",
                radixPoint: ",",
                groupSeparator: ".",
                placeholder: "0",
                digits: 0,
                rightAlign: 0
            });

            $( ':input.numberdecimal').inputmask("decimal", {
                prefix: "",
                radixPoint: ",",
                digits: 2,
                groupSeparator: ".",
                rightAlign: 0
            });

            $( ':input.numberpercent').inputmask("percentage", {
                radixPoint: ",",
                digits: 2,
                groupSeparator: ".",
                rightAlign: 0
            });

            handleDataGridProduct();
            handleValidationProduct();
            handleValidationWD();
            handleValidationReward();
            handleRewardPointConvertion();
            handleRewardTripPeriod();
            handleDataGridPromo();
            handleValidationPromo();
        }
   };
}();


// ===========================================================
// General Setting Function
// ===========================================================
var Page_GeneralSetting = function() {
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
        var _form           = $( '#form-setting-reward' );
        if ( ! _form.length ) {
            return;
        }
        if ( $('input#period').length ) {
            var datest  = $('input#period').data('star')
            var dateen  = $('input#period').data('end')
            $('input#period').daterangepicker({
                "startDate": datest,
                "endDate": dateen,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                ranges: {
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')]
                }
            });
        }

        $( '.is_lifetime', _form ).click(function(e){
            e.preventDefault();
            if ( $(':input[name=is_lifetime]').prop("checked") == true ) {
                $('#period_reward').hide();
            } else {
                $('#period_reward').show();
            }
        });
    };

    return {
        init: function() {
            handleGeneralSetting();
            handleFormSettingCompany();
            handleFormSettingCompanyBilling();
        }
    };
}();
    