var RegisterMember = function() {
    var form_reg        = $('#member_register');
    var wrapper         = $('.wrapper-form-register');
    var alert_msg       = $('#alert'); 
    var subtotal        = 0;
    var total_weight    = 0;
    var total_payment   = 0;
    var total_qty       = 0;
    var shipping_fee    = 0;
    var discount        = 0;
    var discount_code   = '';

    var prod_package    = $('#select_product_package', form_reg).val();

    // Update Modal Confrimation
    var updateModalRegisterConfirm = function(modal) {
        var modal_body  = $('.modal-body', modal);
        var is_admin    = modal_body.data('admin');

        var tusername   = $('input[name=reg_member_username]').val();
        var tname       = $('input[name=reg_member_name]').val();
        var temail      = $('input[name=reg_member_email]').val();
        var tbank       = $('select[name="reg_member_bank"] option:selected').text();
        var tbill       = $('input[name=reg_member_bill]').val();
        var tbillname   = $('input[name=reg_member_bill_name]').val();
        var tpackage    = $('select[name="reg_member_package"] option:selected').text();
        var ttotalbv    = $('input[name=reg_member_package_omzet]').val();
        
        if ( ttotalbv == undefined || ttotalbv == "" ) {
            ttotalbv = 0;
        }
        
        var tsponsor    = '';
        if( is_admin == 1 ){
            tsponsor    = $('input[name=reg_member_sponsor]').val() + ' / ' + $('input[name=reg_member_sponsor_name_dsb]').val();
        }else{
            if ($('input[name=sponsored]:checked').val() == 'other_sponsor') {
                tsponsor = $('input[name=reg_member_sponsor]').val() + ' / ' + $('input[name=reg_member_sponsor_name_dsb]').val();
            }else{
                tsponsor = $('input[name=current_member_username]').val() + ' / ' + $('input[name=current_member_name]').val();
            }
        }
        
        $('.confirm-new-member-username', modal_body).text(tusername);
        $('.confirm-new-member-name', modal_body).text(tname);
        $('.confirm-new-member-email', modal_body).text(temail);
        $('.confirm-new-member-sponsor', modal_body).text(tsponsor);
        $('.confirm-new-member-total-bv', modal_body).text(ttotalbv);
        $('.confirm-new-member-package', modal_body).text(tpackage);
        $('.confirm-new-member-bank', modal_body).text(tbank);
        $('.confirm-new-member-bill', modal_body).text(tbill);
        $('.confirm-new-member-bill-name', modal_body).text(tbillname);
    };

    // Handle Validation Form
    var handleValidationRegMember = function() {
        form_reg.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                reg_member_cloning: {
                    minlength: 3,
                },
                reg_member_sponsor: {
                    minlength: 3,
                    required: function(element) {
                        if( $('#reg_member_sponsor_admin').length ){
                            return true;
                        }else{
                            return $('label#other_sponsor').hasClass('active');
                        }
                    }
                },
                reg_member_username: {
                    minlength: 5,
                    required: true,
                    unamecheck: true,
                    remote: {
                        url: $("#reg_member_username").data('url'),
                        type: "post",
                        data: {
                            username: function() {
                                return $("#reg_member_username").prop( 'readonly' ) ? '' : $("#reg_member_username").val();
                            }
                        }
                    }
                },
                reg_member_password: {
                    minlength: 6,
                    required: true,
                    pwcheck: true,
                },
                reg_member_password_confirm: {
                    required: true,
                    equalTo: '#reg_member_password'
                },
                reg_member_name: {
                    minlength: 3,
                    required: true,
                    lettersonly: true,
                },
                reg_member_email: {
                    email: true,
                    required: true,
                    remote: {
                        url: $("#reg_member_email").data('url'),
                        type: "post",
                        data: {
                            email: function() {
                                return $("#reg_member_email").prop( 'readonly' ) ? '' : $("#reg_member_email").val();
                            }
                        }
                    }
                },
                reg_member_phone: {
                    minlength: 8,
                    required: true,
                    remote: {
                        url: $("#reg_member_phone").data('url'),
                        type: "post",
                        data: {
                            phone: function() {
                                return $("#reg_member_phone").prop( 'readonly' ) ? '' : $("#reg_member_phone").val();
                            }
                        }
                    }
                },
                reg_member_province: {
                    required: true,
                },
                reg_member_district: {
                    required: true,
                },
                reg_member_subdistrict: {
                    required: true,
                },
                reg_member_address: {
                    required: true,
                },
                reg_member_bank: {
                    required: true,
                },
                reg_member_bill: {
                    required: true
                },
                select_courier: {
                    required: true
                },
                select_service: {
                    required: true
                },
                reg_member_term: {
                    required: true,
                },
            },
            messages: {
                reg_member_username: {
                    remote: "Username sudah digunakan. Silahkan gunakan username lain",
                },
                reg_member_password_confirm: {
                    equalTo: "Password konfirmasi tidak cocok dengan password yang di atas",
                },
                reg_member_email: {
                    remote: "Email sudah digunakan. Silahkan gunakan email lain",
                },
                reg_member_phone: {
                    remote: "No. Telp/HP sudah digunakan. Silahkan gunakan No. Telp/HP lain",
                },
                reg_member_bill: {
                    remote: "No. Rekening sudah terdaftar. Silahkan gunakan No. Rekening lain",
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
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                var access          = form_reg.data('access');
                var saldo           = form_reg.data('deposite');
                var prodactive      = form_reg.data('prodactive');
                var total_payment   = ( $('span.total_payment').length ? $('span.total_payment').data('total') : 0 );
                var total_qty       = ( $('span.total_payment').length ? $('span.total_payment').data('totalqty') : 0 );

                if( $('.payment_method').length ){
                    if ( access == 'member' ) {
                        if ( ! $('#prod_package_details').length ) {
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                message: 'Paket Produk belum di pilih. Silahkan pilih paket produk terlebih dahulu !', 
                                type: 'danger',
                            });
                            App.scrollTo($('#select_product_package'), 0);
                            return false;
                        }

                        if ( $('label#payment_deposite').hasClass('active') ) {
                            if ( parseInt(total_payment) > parseInt(saldo) ) {
                                App.notify({
                                    icon: 'fa fa-exclamation-triangle', 
                                    message: 'Saldo Deposite Anda tidak mencukupi untuk pendaftaran Agen ini !', 
                                    type: 'danger',
                                });
                                App.scrollTo($('#prod_package_details'), 0);
                                return false;
                            }
                        }
                        
                        if ( $('label#payment_product').hasClass('active') ) {
                            if ( parseInt(total_qty) > parseInt(prodactive) ) {
                                App.notify({
                                    icon: 'fa fa-exclamation-triangle', 
                                    message: 'Produk Aktif Anda tidak mencukupi untuk pendaftaran Agen ini !', 
                                    type: 'danger',
                                });
                                App.scrollTo($('#prod_package_details'), 0);
                                return false;
                            }
                        }
                    }
                }

                updateModalRegisterConfirm('#modal-save-member');
                $('#modal-save-member').modal('show');
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

    // Handle General Register Form
    var handleGeneralRegForm = function() {
        // Member Name == Bill Name
        $(':input[name=reg_member_name]', form_reg).bind('blur, keyup', function(){
            var name = $(this).val();
            $(':input[name=reg_member_bill_name]', form_reg).val(name);
        });

        // Select Sponsor
        $('label.spon').each(function(){
            $(this).click(function(e){
                e.preventDefault();
                var val = $(this).find('input.toggle').val();
                $('#reg_member_sponsor').val('');

                if( val == 'other_sponsor' ){
                    // $('label#other_sponsor').removeClass('bg-light-blue');
                    // $('label#other_sponsor').addClass('bg-blue');
                    // $('label#as_sponsor').removeClass('bg-blue');
                    // $('label#as_sponsor').addClass('bg-light-blue');
                    $('#sponsor_form').fadeIn();
                    $('#sponsor_form').find('.help-block').remove();
                    $('#reg_member_sponsor').attr('disabled', false);
                }else{
                    // $('label#as_sponsor').removeClass('bg-light-blue');
                    // $('label#as_sponsor').addClass('bg-blue');
                    // $('label#other_sponsor').removeClass('bg-blue');
                    // $('label#other_sponsor').addClass('bg-light-blue');
                    $('#sponsor_form').fadeOut();
                    $(this).parent().parent().removeClass('has-error');
                    $('#sponsor_form').find('.help-block').empty().hide();
                    $('#sponsor_info').empty().hide();
                }
            });
        });

        // Change Minus Qty Product 
        // -----------------------------------------------
        $("body").delegate( "#select_product_package", "change", function( e ) {
            e.preventDefault();
            var id      = $(this).val();
            var url     = $(this).data('url');
            var el      = $('#prod_package_details');
            var provid  = $('#reg_member_province').val();
            
            $(el).empty().hide();
            
            if( provid == "" ){
                App.notify({icon: 'fa fa-exclamation-triangle', message: 'Silahkan pilih Propinsi terlebih dahulu!', type: 'danger'});
                return false;
            }else{
                if( id == '' ){
                    App.notify({icon: 'fa fa-exclamation-triangle', message: 'Silahkan pilih paket produk!', type: 'danger'});
                }else{
                    $.ajax({
                        url: url,
                        method: "POST",
                        data: { param : id, provid : provid },
                        beforeSend: function () {
                            App.run_Loader('timer');
                        },
                        success: function (response) {
                            App.close_Loader();
                            response = $.parseJSON(response);
                            
                            if (response.status == 'success') {
                                $(el).empty().html(response.message).show();
                                App.scrollTo($('#prod_package_details'), 0);
                            } else {
                                App.notify({icon: 'fa fa-exclamation-triangle', message: response.message, type: 'danger'});
                            }
                        },
                        error: function (response) {
                            App.close_Loader();
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                message: 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', 
                                type: 'danger',
                            });
                        },
                        complete: function () {},
                    });
                }
            }
            
        });

        // Change Minus Qty Product 
        // -----------------------------------------------
        $("body").delegate( ".btn_minus_qty", "click", function( e ) {
            e.preventDefault();
            var step    = $(this).data('step');
            var count   = $(this).closest(".product-quantity").find('.numberQtyPack').val();
            var countEl = $(this).closest(".product-quantity").find('.numberQtyPack');

            if ( parseInt(count) >= parseInt(step) && parseInt(total_qty) >= parseInt(count) ) {
                count = parseInt(count) - parseInt(step);
                countEl.val(count).change();
            }
        });

        // Change Plus Qty Product 
        // -----------------------------------------------
        $("body").delegate( ".btn_plus_qty", "click", function( e ) {
            e.preventDefault();
            var step    = $(this).data('step');
            var count   = $(this).closest(".product-quantity").find('.numberQtyPack').val();
            var countEl = $(this).closest(".product-quantity").find('.numberQtyPack');

            if ( parseInt(total_qty) > parseInt(count) ) {
                count = parseInt(count) + parseInt(step);
                countEl.val(count).change();
            }
        });

        // Change Qty Product 
        // -----------------------------------------------
        $("body").delegate( ".numberQtyPack", "change", function( e ) {
            e.preventDefault();
            var rowid       = $(this).data('rowid');
            var qty         = $(this).val();
            var el_qty_all  = $('.numberQtyPack');

            if ( el_qty_all.length > 1 ) {
                total_product   = parseInt(el_qty_all.length);
                product_mod     = total_product - 1;
                total_qty_mod   = parseInt(total_qty) - parseInt(qty);
                _qty            = 0;
                if ( parseInt(qty) > parseInt(total_qty) ) {
                    el_qty_all.each(function() {
                        _val        = $(this).val();
                        _qty        = parseInt(_qty) + parseInt(_val);
                    });

                    var reversQty   = parseInt(_qty) - parseInt(total_qty);
                    qty             = parseInt(qty) - parseInt(reversQty);
                    $(this).val(qty);
                    // swal("Maaf!", 'Qty tidak bisa ditambah lagi. Maksimal '+total_qty+' Qty Paket Produk', "error");
                    return false;
                }

                var qty_mod     = total_qty_mod % product_mod;
                if ( parseInt(qty_mod) > 0 ) {
                    qty_product = (total_qty_mod - qty_mod) / product_mod;
                } else {
                    qty_product = total_qty_mod / product_mod;
                }

                var no = 0;
                el_qty_all.each(function(index) {
                    _val        = $(this).val();
                    _idRow      = $(this).data('rowid');
                    if ( _idRow != rowid ) {
                        if ( no == 0 ) {
                            new_qty = parseInt(qty_product) + parseInt(qty_mod);
                        } else {
                            new_qty = qty_product;
                        }
                        $(this).val(new_qty);
                        no = parseInt(no) + 1;
                    }
                });
            } else {
                $(this).val(total_qty);
            }
            removeDiscount(true);
            resetTotalPayment();
            return false;
        });

        // Voucher Discount 
        // -----------------------------------------------
        $("body").delegate( "#voucher", "blur", function( e ) {
            e.preventDefault();
            var voucher     = $(this).val();
            var url         = $(this).data('url');
            var products    = $('.numberQtyPack');

            if ( voucher ) {
                var form_data = new FormData();
                form_data.append('code_discount', voucher);

                // get inputs product
                if ( products.length ) {
                    products.each(function(index) {
                        idx         = $(this).data('rowid');
                        qty         = $(this).val();
                        price       = $(this).data('price');
                        form_data.append('products['+index+'][id]', idx);
                        form_data.append('products['+index+'][qty]', qty);
                        form_data.append('products['+index+'][price]', price);
                    });
                }

                $.ajax({
                    method: "POST",
                    url: url,
                    data:   form_data,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        App.run_Loader('timer');
                        removeDiscount();
                    },
                    success: function (result) {
                        App.close_Loader();
                        App.scrollTo($('#voucher'), 0);
                        if (result.status == 'success') {
                            $('#discount').val(result.total_discount);
                            $('.discount').text( App.formatCurrency(result.total_discount, '') );
                            $('.discount_code').html('( <b>'+ voucher +'</b> )');
                            calculateTotalPayment();
                            App.notify({icon: 'fa fa-check', message: result.message, type: 'success'});
                        } else {
                            App.notify({icon: 'fa fa-exclamation-triangle', message: result.message, type: 'danger'});
                        }
                    },
                    error: function (result) {
                        App.close_Loader();
                        App.notify({
                            icon: 'fa fa-exclamation-triangle', 
                            message: 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', 
                            type: 'danger',
                        });
                    }
                });
            } else {
                removeDiscount();
            }
            return false; // blocks redirect after submission via ajax
        });

        // Select Courier 
        // -----------------------------------------------
        /*
        $("body").delegate( "#select_courier", "change", function( e ) {
            e.preventDefault();
            var url         = $(this).data('url');
            var courier     = $(this).val();
            var province    = $('.select_province').val();
            var district    = $('.select_district').val();
            var subdistrict = $('.select_subdistrict').val();
            var products    = $('.input-products');
            var el_service  = $('#select_service');

            if ( courier != 'pickup' && courier != 'ekspedisi' ) {
                if ( province == '' || province == 0 || province == undefined ) {
                    App.notify({
                        icon: 'fa fa-exclamation-triangle', 
                        message: 'Provinsi belum di pilih. Silahkan pilih provinsi terlebih dahulu !', 
                        type: 'danger',
                    });
                    $("#select_courier").val('');
                    return false;
                }

                if ( district == '' || district == 0 || district == undefined ) {
                    App.notify({
                        icon: 'fa fa-exclamation-triangle', 
                        message: 'Kab/Kota belum di pilih. Silahkan pilih Kab/Kota terlebih dahulu !', 
                        type: 'danger',
                    });
                    $("#select_courier").val('');
                    return false;
                }

                if ( subdistrict == '' || subdistrict == 0 || subdistrict == undefined ) {
                    App.notify({
                        icon: 'fa fa-exclamation-triangle', 
                        message: 'Kecamatan belum di pilih. Silahkan pilih Kecamatan terlebih dahulu !', 
                        type: 'danger',
                    });
                    $("#select_courier").val('');
                    return false;
                }
            }

            var form_data = new FormData();
            form_data.append('courier', courier);
            form_data.append('province', province);
            form_data.append('district', district);
            form_data.append('subdistrict', subdistrict);
            form_data.append('weight', total_weight);

            // get inputs product
            if ( products.length ) {
                $('input.input-products',  form_reg).each(function(){
                    form_data.append($(this).attr("name"), $(this).val());
                });
            }

            shipping_fee = 0;
            $('#courier_cost', form_reg).val(shipping_fee);
            $('.shipping_fee', form_reg).text(App.formatCurrency(shipping_fee));
            resetTotalPayment();

            if ( courier ) {
                $.ajax({
                    type:   "POST",
                    url:    url,
                    data:   form_data,
                    contentType: false,
                    processData: false,
                    beforeSend: function (){
                        App.run_Loader('timer');
                    },
                    success: function( resp ){
                        App.close_Loader();
                        response = $.parseJSON(resp);
                        var _icon = 'fa fa-exclamation-triangle';
                        var _type = 'danger';

                        if( response.login == 'login' ){
                            $(location).attr('href',response.data);
                        }else{
                            if(response.status == 'success'){
                                var _icon = 'fa fa-check';
                                var _type = 'success';
                            }

                            if ( el_service.length ) {
                                el_service.empty();
                                el_service.removeAttr('disabled');
                                el_service.parent().removeClass('has-danger');
                                el_service.parent().find('.invalid-feedback').empty().hide();
                                el_service.html(response.data);
                            }

                            App.notify({
                                icon: _icon, 
                                message: response.message, 
                                type: _type,
                            });
                            App.scrollTo($('#select_courier'), 0);
                            $('#select_courier').val(courier);
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
            return false;
        });
        */

        // Select Courier Service 
        // -----------------------------------------------
        $("body").delegate( "#select_service", "change", function( e ) {
            e.preventDefault();
            var cost = $('select[name="select_service"] option:selected').data('cost');
            if ( cost ) {
                shipping_fee = parseInt(cost);
            } else {
                shipping_fee = 0;
            }

            $('#courier_cost', form_reg).val(shipping_fee);
            $('.shipping_fee', form_reg).text(App.formatCurrency(shipping_fee));
            calculateTotalPayment();
            return false;
        });

        // Button Add Product Register 
        // -----------------------------------------------
        $("body").delegate( "#select_product", "change", function( e ) {
            e.preventDefault();
            App.run_Loader('timer');
            var product     = $(this).val();
            if ( product ) {
                addProductRegister();
            } else {
                bootbox.alert('Produk belum di pilih !');
                $('#select_product', form_reg).val('');
            }
            setTimeout(function(){ App.close_Loader(); }, 500);
            App.scrollTo($('.register_fee'), 0);
            return false;
        });

        // Button Remove Product Register 
        // -----------------------------------------------
        $("body").delegate( ".btn-remove-product-reg", "click", function( e ) {
            e.preventDefault();
            App.run_Loader('timer');
            var _product    = $(this).data('id');
            var _total      = $(this).data('total');
            var _weight     = $(this).data('weight');
            var _tr         = $(this).parents('tr');
            var _table      = $('#list_table_product_reg');
            var _tbody      = $('tbody', _table);
            var _count_data = $('tr', _tbody).length;

            if( $('[data-id="'+_product+'"]', _tbody).length ) {
                _tr.remove();
                subtotal        = parseInt(subtotal) - parseInt(_total);
                total_weight    = parseInt(total_weight) - parseInt(_weight);

                $('.subtotal', form_reg).text(App.formatCurrency(subtotal));
                $('.total_weight', form_reg).text(App.formatCurrency(total_weight));
                updateTotalPayment();

                if ( _count_data == 1 ) {
                    _tbody.append(_trEmpty);
                }
            }

            setTimeout(function(){ App.close_Loader(); }, 500);
            App.scrollTo($('.register_fee'), 0);
            return false;
        });

        // Save Registered Member
        $('#do_save_member').click(function(e){
            e.preventDefault();
            var formid  = $(this).data('formid');
            saveMember($('#' + formid));
        });

        // Reset Form Register
        $('.btn-register-reset').click(function(e){
            e.preventDefault();
            if( $('#sponsor_info').is(":visible") ){ $('#sponsor_info').empty().hide(); }
            $('select', form_reg).val('');
            form_reg[0].reset();
        });

        $('#success_save').on('hidden.bs.modal', function () {
            location.reload();
        });
    };

    // ---------------------------------
    // Update All Qty Product Package
    // ---------------------------------
    var updateQtyAllProductPackage = function() {
        var el_qty_all  = $('.numberQtyPack');
        if ( el_qty_all.length ) {
            total_qty       = parseInt(total_qty);
            total_product   = parseInt(el_qty_all.length);
            qty_mod         = total_qty % total_product;

            current_qty     = 0;
            el_qty_all.each(function(index){
                _val_qty    = $(this).val();
                current_qty = parseInt(current_qty) + parseInt(_val_qty);
            });

            _total_qty      = total_qty;
            total_qty_pack  = _total_qty;
            if ( total_qty > current_qty ) {
                _total_qty  = _total_qty - current_qty;
                qty_mod     = _total_qty % total_product;
            }

            if ( parseInt(qty_mod) > 0 ) {
                qty_product = (_total_qty - qty_mod) / total_product;
            } else {
                qty_product = _total_qty / total_product;
            }

            el_qty_all.each(function(index){
                if ( parseInt(qty_mod) > 0 && parseInt(index) == 0 ) {
                    new_qty = qty_product + qty_mod;
                } else {
                    new_qty = qty_product;
                }

                if ( total_qty_pack > current_qty  ) {
                    _val    = $(this).val();
                    new_qty = parseInt(new_qty) + parseInt(_val);
                }

                $(this).val(new_qty);
            });
        }
        removeDiscount(true);
        resetTotalPayment();
        return false;
    };

    // ---------------------------------
    // Append Courier
    // ---------------------------------
    function appendCourier() {
        var total_item  = $('#select_product_package', form_reg).val();
        var params      = { type: 'agent', total_qty : total_item }; 
        $.ajax({
            url: base_url_ddm + 'address/get_courier',
            method: "POST",
            data: params,
            dataType: 'json',
            beforeSend: function () {
                resetTotalPayment();
                $('#select_courier').empty();
            },
            success: function (response) {
                $('.spinner-border').remove();
                $('[name="courier"]').attr("readonly", false);

                if ( response.data ) {
                    $('#select_courier').append(response.data);
                }
                if ( response.status != 'success') {
                    swal("Maaf!", response.message, "error")
                }
            },
            error: function (response) {
                //console.log(response);
                swal("Maaf!", "Please Try Again..", "error")
            },
            complete: function () {},
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Remove Discount
    |--------------------------------------------------------------------------
    */
    function removeDiscount( remove_code = false ) {
        if ( remove_code ) {
            $('#voucher').val('');
        }
        $('#discount').val("0");
        $('.discount').text("");
        $('.discount_code').html("");
        calculateTotalPayment();
    }

    /*
    |--------------------------------------------------------------------------
    | Reset Amount
    |--------------------------------------------------------------------------
    */
    function resetTotalPayment() {
        $('#select_courier').val('');
        $('#select_service').empty().append('<option value="" selected="">Pilih Layanan Kurir</option>');
        $('#courier_cost').val("0");
        $('.shipping_fee', form_reg).text(0);
        calculateTotalPayment();
    }

    // ---------------------------------
    // Calculate Product
    // ---------------------------------    
    function calculateTotalPayment() {
        var total_price     = 0;
        var _weight         = 0;
        var reg_fee         = 0;
        var total_discount  = $('#discount').val();
        var courier_cost    = $('#courier_cost').val();
        var courier_service = $('#select_service').val();
        var el_qty_all      = $('.numberQtyPack');

        if ( el_qty_all.length > 1 ) {
            el_qty_all.each(function(index) {
                qty         = $(this).val();
                num         = $(this).data('num');
                price       = $(this).data('price');
                weight      = $(this).data('weight');
                subtotal    = parseInt(qty) * parseInt(price);
                total_price = parseInt(total_price) + parseInt(subtotal);
                _weight     = parseInt(_weight) + ( parseInt(qty) * parseInt(weight) );
            });
        }

        total_payment   = parseInt(total_price) + parseInt(reg_fee) + parseInt(courier_cost) - parseInt(total_discount);
        total_weight    = parseInt(_weight);

        $('.subtotal').text(App.formatCurrency(total_price, ''));
        $('.total-weight').text('( ' + App.formatCurrency(total_weight, '') + ' gr )');
        $('.total_payment').text(App.formatCurrency(total_payment));
    }

    // ---------------------------------
    // Add Product
    // ---------------------------------
    var addProductRegister = function() {
        var _table      = $('#list_table_product_reg');
        var _tbody      = $('tbody', _table);
        var _tr         = $('tr', _tbody);
        var _count_data = _tr.length;
        var _empty_row  = _tbody.find('tr.data-empty');
        var _product    = $('#select_product', form_reg).val();
        var t_product   = $('select[name="select_product"] option:selected').text();
        var weight      = $('select[name="select_product"] option:selected').attr('weight');
        var price       = $('select[name="select_product"] option:selected').attr('price');
        var min_order   = $('select[name="select_product"] option:selected').attr('min_order');
        var min_qty     = $('select[name="select_product"] option:selected').attr('min_qty');
        var discount    = $('select[name="select_product"] option:selected').attr('discount');
        var discount_type   = $('select[name="select_product"] option:selected').attr('discount_type');
        var discount_price  = 0;
        var discount_info   = '';

        var t_weight    = parseInt(weight) * parseInt(min_order);

        if ( parseInt(discount) > 0 && parseInt(min_order) >= parseInt(min_qty) && discount_type ) {
            if ( discount_type == 'percent' ) {
                discount_price  = parseInt(price) * (100 - parseInt(discount)) / 100;
                discount_info   = App.formatCurrency(discount) +' %';
            } else {
                discount_price  = parseInt(price) - parseInt(discount);
                discount_info   = App.formatCurrency(discount);
            }
            var total   = parseInt(discount_price) * parseInt(min_order);
        } else {
            var total   = parseInt(price) * parseInt(min_order);
        }


        subtotal        = parseInt(subtotal) + parseInt(total);
        total_weight    = parseInt(total_weight) + parseInt(t_weight);

        if( $('[data-id="'+_product+'"]', _tbody).length ) {
            bootbox.alert('Produk ini sudah ada ');
            $('#select_product', form_reg).val('');
            return false;
        }

        if ( _empty_row.length ) {
            _empty_row.remove();
        }

        var _append_price = App.formatCurrency(price);
        if ( discount_info !== '' ) {
            _append_price = App.formatCurrency(price) +  ` <sup class="text-warning"> - ` + discount_info + `</sup>`;
        }

        var _append_row = `
            <tr data-id="${_product}">
                <td class="py-1"><b>${t_product}</b></td>
                <td class="py-1 text-right">${_append_price}</td>
                <td class="py-1 text-right">${App.formatCurrency(min_order)}</td>
                <td class="py-1 text-right">${App.formatCurrency(total)}</td>
                <td class="py-1 text-center">
                    <input type="hidden" name="products[${_product}]" value="${_product}" class="d-none input-products" />
                    <button class="btn btn-sm btn-outline-warning btn-remove-product-reg" type="button" data-id="${_product}" data-total="${total}" data-weight="${t_weight}" title="Remove">
                    <i class="fa fa-times"></i></button>
                </td>
            </tr>`;
        _tbody.append(_append_row);
        $('#select_product', form_reg).val('');

        $('.subtotal', form_reg).text(App.formatCurrency(subtotal));
        $('.total_weight', form_reg).text(App.formatCurrency(total_weight));
        updateTotalPayment();
    }

    // ---------------------------------
    // Update Total Payment
    // ---------------------------------
    var updateTotalPayment = function() {
        total_payment   = parseInt(subtotal) + parseInt(shipping_fee) + parseInt(reg_fee) - parseInt(discount);
        $('.total_payment', form_reg).text(App.formatCurrency(total_payment));
    }

    // Save Member
    var saveMember = function(form) {
        var form_reg    = $(form);
        var form_url    = form_reg.data('url');
        var form_val    = form_reg.data('val');
        var msg         = $('#alert');
        var url         = form_reg.attr('action');
        var data        = form_reg.serialize();
        var wrapper     = $('.register_body_wrapper');

        msg.hide();

        $.ajax({
            type:   "POST",
            data:   data,
            url:    url,
            beforeSend: function (){
                App.run_Loader('timer');
                $('#modal-save-member').modal('hide');
            },
            success: function( resp ){
                App.close_Loader();
                resp = resp.replace(/<br\s*[\/]?>/g,"");
                response = $.parseJSON(resp);
                if(response.message == 'error'){
                    if( response.login == 'login' ){
                        $(location).attr('href',response.data);
                    }else{
                        App.notify({
                            icon: 'fa fa-exclamation-triangle', 
                            title: 'Failed', 
                            message: response.data.msg, 
                            type: 'danger',
                        });
                    }
                }else if(response.message == 'success'){
                    $('#success_member').empty().html(response.data.memberinfo);
                    $('#modal-success-save').modal('show');
                    $('#modal-success-save').on('hidden.bs.modal', function () {
                        if( $('#sponsor_info').is(":visible") ){ $('#sponsor_info').empty().hide(); }
                        form_reg[0].reset();
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

    return {
        init: function() {
            handleValidationRegMember();
            handleGeneralRegForm();
            updateQtyAllProductPackage();
        }
    };
}();
    