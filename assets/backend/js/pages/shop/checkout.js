/*
|--------------------------------------------------------------------------
| Save Order
|--------------------------------------------------------------------------
*/
$(document).on('click', '#confirmCheckout', function (e) {

    e.preventDefault();

    var form        = $("#form-checkout");
    var redirect    = form.data('rdr');
    var formData    = new FormData($(form)[0]);
    var quesReg     = $('.question-reg');

    form.validate({
        rules: {
            shipping_name: {
                required: true,
                lettersonly: true,
            },
            shipping_email: {
                required: true,
                email: true,
                nospace: true,
            },
            shipping_phone: {
                required: true,
                digits: true,
                nospace: true
            },
            shipping_postcode: {
                digits: true,
                nospace: true,
            },
            shipping_address: {
                required: true,
            },
            shipping_province: {
                required: true,
            },
            shipping_city: {
                required: true,
            },
            shipping_subdistrict: {
                required: true,
            },
            couriers: {
                required: true,
            },
            courier_service: {
                required: true,
            },
            payment_method: {
                required: true,
            },
        },
    });

    $.validator.addMethod("pwcheck", function(value) {
        return /[a-z].*[0-9]|[0-9].*[a-z]/i.test(value); // consists of only these
    }, "Password harus terdiri dari huruf dan angka" );
    
    $.validator.addMethod("unamecheck", function(value) {
        return /^[A-Za-z0-9]{4,16}$/i.test(value);   // consists of only these
    }, "Username tidak memenuhi kriteria" );

    if( quesReg.length ) {
        var id_user     = $(':input[name="id"]', form).val();
        var id_agent    = $(':input[name="id_agent"]', form).val();
        var opt_agent   = $('label#opt-agent').hasClass('active')

        if ( id_user == '' && id_agent == '' && opt_agent == false) {
            swal({
                title: "Agen belum dipilih",
                text: "Silahkan pilih Agen terlebih dahulu untuk memesan produk ini !",
                type: "warning",
                showCancelButton: !0
            }).then(isConfirm => {
                if (isConfirm.value) {
                    location.href = base_url + 'find-agent/shop';
                }
            });
            return false;
        }

        setRuleValidateAgent();
    }

    if ($(form).valid()) {
        $('#modal-checkout').modal('show');
        $(document).off('click', '#saveOrder').on('click', '#saveOrder', function () {

            $.ajax({
                url: base_url + 'shop/saveOrder',
                method: "POST",
                dataType: "json",
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $(this).attr("disabled", true); //set button disable
                    $('.loader-screen').show();
                },
                success: function (result) {

                    if (result.status == "success") {
                        if (redirect == 'reload') {
                            swal({
                                title: "Success!",
                                text: result.message,
                                type: "success",
                                closeOnClickOutside: false
                            }).then(function () {
                                location.reload();
                            });
                        } else if (redirect == 'link') {
                            swal({
                                title: "Success!",
                                text: result.message,
                                type: "success",
                                closeOnClickOutside: false
                            }).then(function () {
                                location.href = base_url + result.url;
                            });
                        } else {
                            swal({
                                title: "Success!",
                                text: result.message,
                                type: "success",
                                closeOnClickOutside: false
                            }).then(function () {
                                location.href = base_url + redirect;
                            });
                        }
                    } else {
                        if (result.redirect !== undefined && result.redirect == 'reload') {
                            swal({
                                title: "Error!",
                                text: result.message,
                                type: "error",
                                closeOnClickOutside: false
                            }).then(function () {
                                location.reload();
                            });
                        } else {
                            swal("Maaf!", result.message, "error");
                        }
                    }
                },
                error: function () {
                    swal("Maaf!", "There was an error..", "error");
                },
                complete: function () {
                    $(this).attr("disabled", false);
                    $('.loader-screen').hide();
                }
            });
            return false; // blocks redirect after submission via ajax
        });
    }
});
/*
|--------------------------------------------------------------------------
| Save Order Agent
|--------------------------------------------------------------------------
*/
$(document).on('click', '#confirmCheckoutAgent', function (e) {

    e.preventDefault();

    var form        = $("#form-checkout");
    var redirect    = form.data('rdr');
    var formData    = new FormData($(form)[0]);

    form.validate({
        rules: {
            shipping_name: {
                required: true,
                lettersonly: true,
            },
            shipping_email: {
                required: true,
                email: true,
                nospace: true,
            },
            shipping_phone: {
                required: true,
                digits: true,
                nospace: true
            },
            shipping_postcode: {
                digits: true,
                nospace: true,
            },
            shipping_address: {
                required: true,
            },
            shipping_province: {
                required: true,
            },
            shipping_city: {
                required: true,
            },
            shipping_subdistrict: {
                required: true,
            },
            courier: {
                required: true,
            },
            courier_service: {
                required: true,
            },
            payment_method: {
                required: true,
            },
        },
    });

    if ($(form).valid()) {
        $('#modal-checkout').modal('show');
        $(document).off('click', '#saveOrder').on('click', '#saveOrder', function () {

            $.ajax({
                url: base_url + 'shop/saveOrderAgent',
                method: "POST",
                dataType: "json",
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $(this).attr("disabled", true); //set button disable
                    $('.loader-screen').show();
                },
                success: function (result) {

                    if (result.status == "success") {
                        if (redirect == 'reload') {
                            swal({
                                title: "Success!",
                                text: result.message,
                                type: "success",
                                closeOnClickOutside: false
                            }).then(function () {
                                location.reload();
                            });
                        } else if (redirect == 'link') {
                            swal({
                                title: "Success!",
                                text: result.message,
                                type: "success",
                                closeOnClickOutside: false
                            }).then(function () {
                                location.href = base_url + result.url;
                            });
                        } else {
                            swal({
                                title: "Success!",
                                text: result.message,
                                type: "success",
                                closeOnClickOutside: false
                            }).then(function () {
                                location.href = base_url + redirect;
                            });
                        }
                    } else {
                        if (result.redirect !== undefined && result.redirect == 'reload') {
                            swal({
                                title: "Error!",
                                text: result.message,
                                type: "error",
                                closeOnClickOutside: false
                            }).then(function () {
                                location.reload();
                            });
                        } else {
                            swal("Maaf!", result.message, "error");
                        }
                    }
                },
                error: function () {
                    swal("Maaf!", "There was an error..", "error");
                },
                complete: function () {
                    $(this).attr("disabled", false);
                    $('.loader-screen').hide();
                }
            });
            return false; // blocks redirect after submission via ajax
        });
    }
});

// Handle Validation New Agent
var setRuleValidateAgent = function() {
    if( $('.question-reg').length ){
        var opt_agent = $('label#opt-agent').hasClass('active');
        $("[name=username_agent]").rules( "remove");
        $("[name=password_agent]").rules( "remove");

        if ( opt_agent ) {
            $("[name=username_agent]").rules( "add", {
                minlength: 6,
                required: true,
                unamecheck: true,
                remote: {
                    url: $("#username_agent").data('url'),
                    type: "post",
                    data: {
                        username: function() {
                            return $("#username_agent").prop( 'readonly' ) ? '' : $("#username_agent").val();
                        }
                    }
                },
                messages: {
                    remote: "Username tidak valid. Silahkan gunakan username lain"
                }
            });

            $("[name=password_agent]").rules( "add", {
                minlength: 6,
                required: true,
                pwcheck: true,
            });
        }
    }
    return false;
};

/*
|--------------------------------------------------------------------------
| Search username
|--------------------------------------------------------------------------
*/
$('[name="username_sponsor"]').bind('blur', function () {
    $('.searchUsername').trigger('click');
});

$(document).on('click', '.searchUsername', function (e) {
    e.preventDefault();
    var val         = $('[name="username_sponsor"]').val();
    var usertype    = $(this).data('usertype');
    var el          = $('#username-info');
    el.empty();

    if (val) {
        $.ajax({
            url: base_url + 'member/searchAgentUsername',
            method: "POST",
            dataType: "json",
            data: {
                'username': val,
                'usertype': usertype
            },
            beforeSend: function () {
                el.empty();
                $('.loader-screen').show();
                $('.searchUsername').attr("disabled", true); //set button disable
            },
            success: function (result) {
                if (result.status == "success") {
                    swal("Success!", result.message, "success");
                    el.html(result.data)
                } else {
                    el.empty();
                    $('[name="username_sponsor"]').val('');
                    swal("Maaf!", result.message, "error");
                }
            },
            error: function () {
                swal("Maaf!", "There was an error..", "error");
            },
            complete: function () {
                $('.searchUsername').attr("disabled", false);
                $('.loader-screen').hide();
            }
        });
        return false; // blocks redirect after submission via ajax
    }
});

/*
|--------------------------------------------------------------------------
| Search Consumer
|--------------------------------------------------------------------------
*/
$(document).on('click', '#loadPhoneNumber', function (e) {
    var val = $('.searchPhone[name="shipping_phone"]').val();
    if (val) {
        $.ajax({
            url: base_url + 'shop/actionSearchCustomer',
            method: "POST",
            dataType: "json",
            data: {
                'phone': val
            },
            beforeSend: function () {
                $('.loader-screen').show();
            },
            success: function (response) {
                $('.loader-screen').hide();
                if (response.status == "success") {
                    resetCourier();
                    appendCourier();
                    $('.question-save-consumer').empty();
                    swal("Success!", response.message, "success");
                    $('.rajaongkir-province').attr("readonly", true);
                    $('.rajaongkir-province').val(response.data.id_province);
                    $(".rajaongkir-city").empty().append("<option value='" + response.data.id_city + "' selected>" + response.data.city_name + "</option>");
                    $(".rajaongkir-subdistrict").empty().append("<option value='" + response.data.id_subdistrict + "' selected>" + response.data.subdistrict_name + "</option>");
                    $('[name="shipping_postcode"]').val(response.data.postcode);
                    $('[name="shipping_address"]').val(response.data.address);
                    $('[name="shipping_name"]').val(response.data.name);
                    $('[name="shipping_email"]').val(response.data.email);
                    $('[name="id_customer"]').val(encryptParam(response.data.id));
                } else {
                    $('[name="id_customer"]').val('');
                }
            },
            error: function () {
                $('.loader-screen').hide();
                swal("Maaf!", "There was an error..", "error");
            },
            complete: function () {}
        });
        return false; // blocks redirect after submission via ajax
    }
});

/*
|--------------------------------------------------------------------------
| Register type Options
|--------------------------------------------------------------------------
*/
$("[name=options_reg]").change(function () {
    var val             = $(this).val();
    var reset_courier   = false;
    $('.opt-toggle-agent').hide();

    if (val == 'customer') {
        $("[name=username_agent]").prop('required', false).parent().removeClass('error');
        $("[name=password_agent]").prop('required', false).parent().removeClass('error');
        $("[name=bill_bank]").prop('required', false).parent().removeClass('error');
        $("[name=bill_name]").prop('required', false).parent().removeClass('error');
        $("[name=bill_no]").prop('required', false).parent().removeClass('error');
        $('.question-save-consumer').show('fast');
        $('.register-fee').hide();
        reset_courier = true;
    } else if (val == 'agent') {

        if( $('.product_checkout').length ){
            var product_checkout = `
                <p>Syarat menjadi Agen harus pesan produk sesuai dengan minimal order sebagai berikut:</p>
                <table class="table table-bordered mb-3">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Produk</th>
                            <th scope="col">Minimal Order</th>
                        </tr>
                    </thead>
                    <tbody>`;
            $('.product_checkout').each(function(){
                product_checkout += `
                    <tr>
                        <td class="text-capitalize">`+ $(this).data("product") +`</td>
                        <td>`+ $(this).data("minorder") +`</td>
                    </tr>`;
            });

            product_checkout += `
                    </tbody>
                </table>
                <p>Apakah Anda ingin menjadi Agen dan edit Qty pesanan produk anda ?</p>`;
        }

        swal({
            title: "Konfirmasi",
            html: product_checkout,
            type: "warning",
            showCancelButton: !0

        }).then(isConfirm => {
            if (isConfirm.value) {
                $.ajax({
                    url: base_url + 'shop/updateCart',
                    method: "POST",
                    dataType: "json",
                    data: {
                        options_reg: "agent"
                    },
                    success: function (result) {
                        if (result['status'] == 'success') {
                            $("[name=username_agent]").prop('required', true);
                            $("[name=password_agent]").prop('required', true);
                            $("[name=bill_bank]").prop('required', true);
                            $("[name=bill_name]").prop('required', true);
                            $("[name=bill_no]").prop('required', true);
                            $('.question-save-consumer').hide();
                            $('.register-fee').show('fast');
                            $('.searchUsername').trigger('click');
                            $("#tab-agent").show('fast');
                            reset_courier = true; 

                            $.each(result.data, function(index, val) {
                                update_product_checkout = $('.product_checkout_id_' + val.id );
                                if ( update_product_checkout.length ) {
                                    $('.product_qty', update_product_checkout ).text(val.qty);
                                    $('.product_subtotal', update_product_checkout ).text(val.subtotal);
                                }
                            });
                        }
                    },
                    error: function (result) {
                        swal("Maaf!", 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', "error")
                    }
                });

                $('[name="courier"]').val('');
                $('[name="courier_service"]').attr("readonly", true).empty();
                resetTotalAmount(val);
                return false; // blocks redirect after submission via ajax
            } else {
                $('#opt-agent').removeClass('active');
                $('#opt-customer').addClass('active');
                
                $('[name="courier"]').val('');
                $('[name="courier_service"]').attr("readonly", true).empty();
                resetTotalAmount(val);
            }
        });
    }

    if ( reset_courier ) {
        $('[name="courier"]').val('');
        $('[name="courier_service"]').attr("readonly", true).empty();
        resetTotalAmount(val);
    }
});


/*
|--------------------------------------------------------------------------
| Options Save Data Customer
|--------------------------------------------------------------------------
*/
$("[name=options_save_customer]").change(function () {
    $('.opt-toggle').hide();
    $("#tab-" + $(this).val()).show('fast');
});


/*
|--------------------------------------------------------------------------
| Button Back to Cart
|--------------------------------------------------------------------------
*/
$(document).on('click', '.btn-back-to-cart', function (e) {
    e.preventDefault();
    var url = $(this).data('url');
    $.ajax({
        url: base_url + 'shop/updateCart',
        method: "POST",
        dataType: "json",
        success: function (result) {
            if (result['status'] == 'success') {
                location.href = url;
                // $(location).attr('href',url);
            }
        },
        error: function (result) {
            swal("Maaf!", 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', "error")
        }
    });
    return false;
});

/*
|--------------------------------------------------------------------------
| Apply Code Promo
|--------------------------------------------------------------------------
*/

$('input[name=code_discount]').keypress(function (e) {
    var key = e.which;
    if (key == 13) // the enter key code
    {
        $('.applyDiscount').click();
        return false;
    }
});

$(document).on('click', '.applyDiscount', function () {
    var el          = $(this);
    var formId      = $('#form-input-promo');
    var codePromo   = $('input[name="code_discount"]', formId).val();

    if ( codePromo ) {
        $.ajax({
            url: base_url + 'shop/applyDiscount',
            method: "POST",
            dataType: "json",
            data: { code_discount : codePromo },
            beforeSend: function () {
                el.attr("disabled", true); //set button disable
                el.text('Proccessing..');
            },
            success: function (result) {
                if (result.status == 'success') {
                    swal({
                        title: "Success!",
                        text: result.message,
                        type: "success",
                        closeOnClickOutside: false
                    }).then(function () {
                        // location.reload();
                        $('.promo-discount').text('- ' + result.total_discount);
                        $('.delete-input-promo').html(result.delete_discount);
                        $('.promo-discount-code').html('( <b>'+ codePromo +'</b> )');
                        if ( $('[name="courier_service"]').length ) {
                            var courier_service = $('[name="courier_service"]').val();
                            if ( courier_service != '' && courier_service != undefined ) {
                                $('[name="courier_service"]').trigger('change');
                            } else {
                                resetTotalAmount();
                            }
                        }
                    });
                } else {
                    swal("Maaf!", result.message, "error");
                }
            },
            error: function (result) {
                console.log(result);
                swal("Maaf!", result.message, "error");
            },
            complete: function (result) {
                el.attr("disabled", false);
                el.text('Apply Diskon');
            }
        });
    }
    return false; // blocks redirect after submission via ajax
});