/*
|--------------------------------------------------------------------------
| Sub-district on change
|--------------------------------------------------------------------------
*/
$('.rajaongkir-subdistrict').change(function () {
    appendCourier();
});

/*
|--------------------------------------------------------------------------
| Appending courier
|--------------------------------------------------------------------------
*/
function appendCourier() {
    var params = {};

    if ( $('input[name="total_item"]', '#form-checkout').length ) {
        var total_item = $('input[name="total_item"]', '#form-checkout').val();
        params = { type: 'agent', total_qty : total_item };        
    }

    $.ajax({
        url: base_url + 'address/get_courier',
        method: "POST",
        data: params,
        dataType: 'json',
        beforeSend: function () {
            resetCourier();
            $('[name="courier"]').parent().append('<span class="spinner-border"></span>');
        },
        success: function (response) {
            $('.spinner-border').remove();
            $('[name="courier"]').attr("readonly", false);

            if (response.status == 'success') {
                $('[name="courier"]').append(response.data);
            } else {
                var optionsData = "<option value='' disabled selected>-- Silahkan Pilih Kurir --</option>";
                $('[name="courier"]').append(optionsData);
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
| Appending payment method
|--------------------------------------------------------------------------
*/
function appendPaymentMethod(_payType = 'order') {
    var params = {};

    if ( $('input[name="total_item"]', '#form-checkout').length ) {
        var total_item  = $('input[name="total_item"]', '#form-checkout').val();
        params = { type: 'agent', total_qty : total_item, payment_type : _payType };        
    }

    $.ajax({
        url: base_url + 'address/get_payment_method',
        method: "POST",
        data: params,
        dataType: 'json',
        beforeSend: function () {
            resetPaymentMethod();
            $('[name="payment_method"]').parent().append('<span class="spinner-border"></span>');
        },
        success: function (response) {
            $('.spinner-border').remove();
            $('[name="payment_method"]').attr("readonly", false);

            if (response.status == 'success') {
                $('[name="payment_method"]').append(response.data);
            } else {
                var optionsData = "<option value='' disabled selected>-- Silahkan Pilih Metode Pembayaran --</option>";
                $('[name="payment_method"]').append(optionsData);
                swal("Maaf!", response.message, "error")
            }
        },
        error: function (response) {
            swal("Maaf!", "Please Try Again..", "error")
        },
        complete: function () {},
    });
}

/*
|--------------------------------------------------------------------------
| Choosing payment method
|--------------------------------------------------------------------------
*/
$('[name="payment_method"]').change(function () {
    var payment_method     = $(this).val();

    $.ajax({
        url: base_url + 'address/get_payment_agent',
        method: "POST",
        data: {
            payment_method: payment_method,
        },
        dataType: 'json',
        beforeSend: function () {
            $('[name="agent_data"]').attr("readonly", true).empty().parent().append('<span class="spinner-border"></span>') // empty dropdown
        },
        success: function (response) {
            //console.log(response);
            if (response.status == 'success') {
                $('.spinner-border').remove();
                $("#agent_data_group").show('fast');
                //$("#all_product_active_tab").hide();
                $('[name="agent_data"]').attr("readonly", false);
                // append options data
                $('[name="agent_data"]').append(response.data);
            } else {
                $('.spinner-border').remove();
                $("#agent_data_group").hide();
                //$("#all_product_active_tab").show('fast');
                //swal("Maaf!", response.message, "error")
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
| Appending payment type
|--------------------------------------------------------------------------
*/
function appendPaymentType(payType = 'order') {
    var params = {};

    if ( $('input[name="total_item"]', '#form-checkout').length ) {
        var total_item = $('input[name="total_item"]', '#form-checkout').val();
        params = { type: 'agent', total_qty : total_item, payment_type : payType };        
    }

    $.ajax({
        url: base_url + 'address/get_payment_type',
        method: "POST",
        data: params,
        dataType: 'json',
        beforeSend: function () {
            resetPaymentType();
            $('[name="payment_type"]').parent().append('<span class="spinner-border"></span>');
        },
        success: function (response) {
            $('.spinner-border').remove();
            $('[name="payment_type"]').attr("readonly", false);

            if (response.status == 'success') {
                $('[name="payment_type"]').append(response.data);
                $("#payment_type_group").show('fast');
                $("#label_info_product_active").show('fast');
                //$("#all_product_active_tab").hide();
                appendPaymentMethod(response.payment_type);
            } else {
                var optionsData = "<option value='' disabled selected>-- Silahkan Pilih Metode Pembayaran --</option>";
                $('[name="payment_type"]').append(optionsData);
                $("#label_info_product_active").hide();
                //$("#all_product_active_tab").show('fast');
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
| Appending activation data
|--------------------------------------------------------------------------
*/
function appendActivationData() {
    var params = {};

    if ( $('input[name="total_item"]', '#form-checkout').length ) {
        var total_item = $('input[name="total_item"]', '#form-checkout').val();
        var payType = $('input[name="payment_type"]', '#form-checkout').val();
        params = { type: 'agent', total_qty : total_item, payment_type : payType };        
    }

    $.ajax({
        url: base_url + 'address/get_activation',
        method: "POST",
        data: params,
        dataType: 'json',
        beforeSend: function () {
            resetAgentActivation();
            $('[name="agent_activation"]').parent().append('<span class="spinner-border"></span>');
        },
        success: function (response) {
            $('.spinner-border').remove();
            $('[name="agent_activation"]').attr("readonly", false);

            if (response.status == 'success') {
                $('[name="agent_activation"]').append(response.data);
                //appendPaymentType('activation');
            } else {
                var optionsData = "<option value='' disabled selected>-- Silahkan Pilih Data --</option>";
                $('[name="agent_activation"]').append(optionsData);
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
| Choosing payment type
|--------------------------------------------------------------------------
*/
$('[name="payment_type"]').change(function () {
    var payment_type     = $(this).val();

    $.ajax({
        url: base_url + 'address/get_payment_type',
        method: "POST",
        data: {
            payment_type: payment_type,
        },
        dataType: 'json',
        beforeSend: function () {
            $('[name="payment_type"]').attr("readonly", true).empty().parent().append('<span class="spinner-border"></span>') // empty dropdown
        },
        success: function (response) {
            //console.log(response);
            if (response.status == 'success') {
                $('.spinner-border').remove();
                $('[name="payment_type"]').attr("readonly", false);
                // append options data
                $('[name="payment_type"]').append(response.data);
                if(response.payment_type == 'activation'){
                    $("#agent_activation_group").show('fast');
                    $("#label_info_product_active").hide();
                    //$("#all_product_active_tab").hide();
                    appendActivationData();
                    appendPaymentMethod(response.payment_type);
                }else{
                    $("#agent_activation_group").hide();
                    $("#label_info_product_active").show('fast');
                    //$("#all_product_active_tab").show('fast');
                    appendPaymentMethod('order');
                }
            } else {
                $('.spinner-border').remove();
                $("#agent_activation_group").hide();
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
| Choosing courier
|--------------------------------------------------------------------------
*/
$('[name="courier"]').change(function () {

    var courier     = $(this).val();
    var weight      = $('[name="weight"]').val();
    var destination = $('[name="shipping_subdistrict"]').val();
    var opt_agent   = '';

    if ( $('.question-reg').length ) {
        if ( $('label#opt-agent').hasClass('active') ) {
            opt_agent = 'agent';
        }
    }

    $.ajax({
        url: base_url + 'address/get_courier_cost',
        method: "POST",
        data: {
            courier: courier,
            weight: weight,
            destination: destination,
            opt_agent: opt_agent
        },
        dataType: 'json',
        beforeSend: function () {
            resetTotalAmount(opt_agent);
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

            // if (response['rajaongkir']['status']['code'] == 400) {
            //     resetCourier();
            //     $('.spinner-border').remove();
            //     // swal("Maaf!", response['rajaongkir']['status']['description'], "error")
            // } else {

            //     $('[name="courier_service"]').attr("readonly", false);
            //     $('.spinner-border').remove();

            //     // var optionsData = "<option value='' disabled selected>-- Silahkan Pilih Layanan Kurir --</option>";
            //     // for (i = 0; i < response['rajaongkir']['results'][0]['costs'].length; i++) {
            //     //     var val = response['rajaongkir']['results'][0]['costs'][i]['service'] + ',' + response['rajaongkir']['results'][0]['costs'][i]['cost'][0]['value'];
            //     //     var text = response['rajaongkir']['results'][0]['costs'][i]['service'] + ' - ' + response['rajaongkir']['results'][0]['costs'][i]['description'];
            //     //     optionsData += "<option value='" + val + "'>" + text + "</option>";
            //     // }
            //     // append options data
            //     $('[name="courier_service"]').append(optionsData);
            // }
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
    var courier_cost    = $(this).val().split(",");
    var total_checkout  = infoCart().total_amount;
    total_checkout      = parseInt(total_checkout);
    courier_cost        = parseInt(courier_cost[1]);
    
    var total_payment   = courier_cost + total_checkout;

    if ( $('.question-reg').length ) {
        var opt_agent       = $('label#opt-agent').hasClass('active');
        var register_fee    = $('.register-fee').data('regfee');
        if ( opt_agent ) {
            register_fee    = parseInt(register_fee);
            total_payment   = total_payment + register_fee;
        }
    }

    $('[name="courier_cost"]').val(courier_cost);
    $('.courier-cost').text(accounting(courier_cost));
    $('.total-checkout').text(accounting(total_payment));
});