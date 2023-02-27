/*
|--------------------------------------------------------------------------
| Validations Register Agent
|--------------------------------------------------------------------------
*/
function handleValidationRegAgent() {
    var form        = $("#form-register-agent");

    form.validate({
        rules: {
            username_agent: {
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
                }
            },
            password_agent: {
                minlength: 6,
                required: true,
                pwcheck: true,
            },
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
        },
        messages: {
            username_agent: {
                remote: "Username tidak valid. Silahkan gunakan username lain"
            }
        }
    });

    $.validator.addMethod("pwcheck", function(value) {
        return /[a-z].*[0-9]|[0-9].*[a-z]/i.test(value); // consists of only these
    }, "Password harus terdiri dari huruf dan angka" );
    
    $.validator.addMethod("unamecheck", function(value) {
        return /^[A-Za-z0-9]{4,16}$/i.test(value);   // consists of only these
    }, "Username tidak memenuhi kriteria" );
}

handleValidationRegAgent();

/*
|--------------------------------------------------------------------------
| Save Register Agent
|--------------------------------------------------------------------------
*/
$(document).on('click', '#confirmRegister', function (e) {

    e.preventDefault();

    var form        = $("#form-register-agent");
    var redirect    = form.data('rdr');
    var formData    = new FormData($(form)[0]);

    if ($(form).valid()) {
        $('#modal-register-confirm').modal('show');
        $(document).off('click', '#saveRegister').on('click', '#saveRegister', function () {
            $.ajax({
                url: base_url + 'shop/saveOrderRegister',
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
                    swal({
                        title: "Success!",
                        html: result.message +'<br/><br/>',
                        type: "success",
                        showConfirmButton: false,
                        timer: 1000,
                    });
                    el.html(result.data)
                    $("#username_agent").focus()
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
| input phonenumber
|--------------------------------------------------------------------------
*/
$("input.phonenumber").keyup(function () {
    if (this.value.substring(0, 1) == "0") {
        this.value = this.value.replace(/^0+/g, "");
    }
});


/*
|--------------------------------------------------------------------------
| Show / Hide Password
|--------------------------------------------------------------------------
*/
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

/*
|--------------------------------------------------------------------------
| + / - Quantity Func
|--------------------------------------------------------------------------
*/
function qtyPackPlus(el, step = 1) {
    var total   = $('.totalPackQtyOrder').val();
    var count   = $(el).closest(".product-quantity").find('.numberQtyPack').val();
    var countEl = $(el).closest(".product-quantity").find('.numberQtyPack');

    // count++;
    count = parseInt(count) + parseInt(step);
    countEl.val(count).change();
}

function qtyPackMin(el, step = 1) {
    var total   = $('.totalPackQtyOrder').val();
    var count   = $(el).closest(".product-quantity").find('.numberQtyPack').val();
    var countEl = $(el).closest(".product-quantity").find('.numberQtyPack');

    if ( parseInt(count) >= parseInt(step) && parseInt(total) >= parseInt(count) ) {
        count = parseInt(count) - parseInt(step);
        countEl.val(count).change();
    }
}

// ---------------------------------
// Update All Qty Product Package
// ---------------------------------
function updateQtyAllProductPackage () {
    var total_qty   = $('.totalPackQtyOrder').val();
    var el_qty_all  = $('.numberQtyPack');
    
    var updateQty   = false;
    var updatedata  = [];

    if ( el_qty_all.length ) {
        total_qty       = parseInt(total_qty);
        total_product   = parseInt(el_qty_all.length);
        qty_mod         = total_qty % total_product;

        current_qty     = 0;
        el_qty_all.each(function(index){
            _val_qty    = $(this).val();
            current_qty = parseInt(current_qty) + parseInt(_val_qty);
        });

        total_qty_pack  = total_qty;
        if ( total_qty > current_qty ) {
            total_qty   = total_qty - current_qty;
            qty_mod     = total_qty % total_product;
        }

        if ( parseInt(qty_mod) > 0 ) {
            qty_product = (total_qty - qty_mod) / total_product;
        } else {
            qty_product = total_qty / total_product;
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
    calculateTotalPayment();
    return false;
};

updateQtyAllProductPackage();

/*
|--------------------------------------------------------------------------
| Update Qty Product Package
|--------------------------------------------------------------------------
*/
$(".totalPackQtyOrder").on("change", function (e) {
    e.preventDefault();
    updateQtyAllProductPackage();
    appendCourier();
});

$(".numberQtyPack").on("change", function (e) {
    e.preventDefault();
    var rowid       = $(this).data('rowid');
    var qty         = $(this).val();
    var total_qty   = $('.totalPackQtyOrder').val();
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
    calculateTotalPayment();
    return false;
});

/*
|--------------------------------------------------------------------------
| Sub-district on change
|--------------------------------------------------------------------------
*/
$('.rajaongkir-subdistrict').change(function () {
    appendCourier();
    appendPaymentMethod();
});

/*
|--------------------------------------------------------------------------
| Appending courier
|--------------------------------------------------------------------------
*/
function appendCourier() {
    var total_qty   = $('.totalPackQtyOrder').val();
    var params      = { type: 'agent', total_qty : total_qty }; 
    $.ajax({
        url: base_url + 'address/get_courier',
        method: "POST",
        data: params,
        dataType: 'json',
        beforeSend: function () {
            resetSelectCourier();
            $('[name="courier"]').parent().append('<span class="spinner-border"></span>');
        },
        success: function (response) {
            $('.spinner-border').remove();
            $('[name="courier"]').attr("readonly", false);

            if ( response.data ) {
                $('[name="courier"]').append(response.data);
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
| Clear form RajaOngkir field
|--------------------------------------------------------------------------
*/
function resetSelectCourier() {
    $('[name="courier"]').prop('selectedIndex', 0).attr("readonly", true).empty();
    $('[name="courier_service"]').prop('selectedIndex', 0).attr("readonly", true).empty();
    $('[name="payment_method"]').prop('selectedIndex', 0).attr("readonly", true).empty();
    resetTotalPayment()
}

/*
|--------------------------------------------------------------------------
| Choosing courier
|--------------------------------------------------------------------------
*/
$('[name="courier"]').change(function () {
    var courier     = $(this).val();
    var destination = $('[name="shipping_subdistrict"]').val();
    var weight      = 0;
    var el_qty_all  = $('.numberQtyPack');

    if ( el_qty_all.length > 1 ) {
        el_qty_all.each(function(index) {
            qty     = $(this).val();
            pw      = $(this).data('weight');
            weight  = parseInt(weight) + ( parseInt(qty) * parseInt(pw) );
        });
    } 

    $.ajax({
        url: base_url + 'address/get_courier_cost',
        method: "POST",
        data: {
            courier: courier,
            weight: weight,
            destination: destination,
            opt_agent: 'agent'
        },
        dataType: 'json',
        beforeSend: function () {
            resetTotalPayment();
            $('[name="courier_service"]').attr("readonly", true).empty().parent().append('<span class="spinner-border"></span>') // empty dropdown
        },
        success: function (response) {
            //console.log(response);
            if (response.status == 'success') {
                $('.spinner-border').remove();
                $('[name="courier_service"]').attr("readonly", false);
                // append options data
                $('[name="courier_service"]').append(response.data);
            } else {
                $('.spinner-border').remove();
                swal("Maaf!", response.message, "error")
            }
        },
        error: function (response) {
            //console.log(response);
            swal("Maaf!", "Please Try Again..", "error")
        },
        complete: function () {},
    });
});

/*
|--------------------------------------------------------------------------
| Show ongkir
|--------------------------------------------------------------------------
*/
$('[name="courier_service"]').change(function () {
    calculateTotalPayment();
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
    var products    = $('.numberQtyPack');

    if ( codePromo ) {
        var form_data = new FormData();
        form_data.append('code_discount', codePromo);

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
            url: base_url + 'shop/applyDiscountRegAgent',
            data:   form_data,
            dataType: "json",
            contentType: false,
            processData: false,
            beforeSend: function () {
                el.attr("disabled", true); //set button disable
                el.text('Proccessing..');
                removeDiscount();
            },
            success: function (result) {
                if (result.status == 'success') {
                    swal({
                        title: "Success!",
                        text: result.message,
                        type: "success"
                    }).then(function () {
                        $('.delete-input-promo', formId).html(result.delete_discount);
                        $('.promo-discount').text(accounting(result.total_discount, ''));
                        $('.voucher-code').html('( <b>'+ codePromo +'</b> )');
                        calculateTotalPayment();
                    });
                } else {
                    swal("Maaf!", result.message, "error");
                }
                el.attr("disabled", false);
                el.text('Apply Diskon');
            },
            error: function (result) {
                swal("Maaf!", result.message, "error");
            }
        });
    } else {
        removeDiscount();
    }
    return false; // blocks redirect after submission via ajax
});

$(document).on('click', '.removeDiscount', function () {
    removeDiscount(true);
});

/*
|--------------------------------------------------------------------------
| Remove Discount
|--------------------------------------------------------------------------
*/
function removeDiscount(remove_code = false) {
    if ( remove_code ) {
        $('input[name=code_discount]').val('');
    }
    $('.promo-discount').text('');
    $('.voucher-code').html('');
    $('.delete-input-promo').html('');
    calculateTotalPayment();
}

/*
|--------------------------------------------------------------------------
| Reset Amount
|--------------------------------------------------------------------------
*/
function resetTotalPayment() {
    $('.courier_service').val("");
    $('.courier-cost').val("0");
    calculateTotalPayment();
}


/*
|--------------------------------------------------------------------------
| Reset Amount
|--------------------------------------------------------------------------
*/
function calculateTotalPayment() {
    var total_payment   = 0;
    var total_price     = 0;
    var total_weight    = 0;
    var courier_cost    = 0;
    var total_discount  = $('.total_discount').val();
    var courier_service = $('.courier_service').val();
    var el_qty_all      = $('.numberQtyPack');
    var register_fee    = $('#form-register-agent').data('regfee');
    register_fee        = register_fee ? register_fee : 0;
    total_discount      = total_discount ? total_discount : 0;

    if ( el_qty_all.length > 1 ) {
        el_qty_all.each(function(index) {
            qty         = $(this).val();
            num         = $(this).data('num');
            price       = $(this).data('price');
            weight      = $(this).data('weight');
            subtotal    = parseInt(qty) * parseInt(price);
            total_price = parseInt(total_price) + parseInt(subtotal);
            total_weight= parseInt(total_weight) + ( parseInt(qty) * parseInt(weight) );

            $('.product_qty_'+num).text(accounting(qty, ''));
            $('.product_subtotal_'+num).text(accounting(subtotal, ''));
        });
    } 

    if ( courier_service ) {
        courier_cost    = courier_service.split(",");
        courier_cost    = parseInt(courier_cost[1]);
    }

    total_payment   = parseInt(total_price) + parseInt(register_fee) + parseInt(courier_cost) - parseInt(total_discount);

    $('.subtotal-cart').text(accounting(total_price, ''));
    $('.total-weight').text('( ' + accounting(total_weight, '') + ' gr )');
    $('.courier-cost').text(accounting(courier_cost, ''))
    $('.total-payment').text(accounting(total_payment));
}