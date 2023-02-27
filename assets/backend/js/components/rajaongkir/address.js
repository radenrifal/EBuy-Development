/*
|--------------------------------------------------------------------------
| Clear form RajaOngkir field
|--------------------------------------------------------------------------
*/
function resetCourier() {
    $('[name="courier"]').prop('selectedIndex', 0).attr("readonly", true).empty();
    $('[name="courier_service"]').prop('selectedIndex', 0).attr("readonly", true).empty();
    $('[name="courier_cost"]').val("");
    resetTotalAmount()
}

function resetPaymentMethod() {
    $('[name="payment_method"]').prop('selectedIndex', 0).attr("readonly", true).empty();
}

function resetPaymentType() {
    $('[name="payment_type"]').prop('selectedIndex', 0).attr("readonly", true).empty();
}

function resetAgentActivation() {
    $('[name="agent_activation"]').prop('selectedIndex', 0).attr("readonly", true).empty();
}

/*
|--------------------------------------------------------------------------
| Reset Amount
|--------------------------------------------------------------------------
*/
function resetTotalAmount( opt_agent = '' ) {
    var total_checkout  = infoCart().total_amount;
    var total_payment   = parseInt(total_checkout);
    if ( $('.question-reg').length && $('.register-fee').length ) {
        var register_fee    = $('.register-fee').data('regfee');
        register_fee        = register_fee ? register_fee : 0;;
        if ( opt_agent == 'agent' ) {
            register_fee    = parseInt(register_fee);
            total_payment   = total_payment + register_fee;
        }
    }

    $('[name="courier_cost"]').val("");
    $('.courier-cost').text('0');
    $('.total-checkout').text(accounting(total_payment));
}

/*
|--------------------------------------------------------------------------
| Append Select Options Province (RajaOngkir - RO)
|--------------------------------------------------------------------------
*/
function selectProvinceRO() {

    $.ajax({
        url: base_url + 'address/getprovince',
        method: "POST",
        dataType: 'json',
        beforeSend: function () {
            $('.rajaongkir-province').attr("readonly", true).empty().parent().append('<span class="spinner-border"></span>'); // empty dropdown
        },
        success: function (response) {
            if (response.status == 'success') {
                $('.spinner-border').remove();
                $('.rajaongkir-province').attr("readonly", false);
                // append options data
                $('.rajaongkir-province').append(response.data);
                appendUserProvince();
            } else {
                $('.spinner-border').remove();
                swal("Maaf!", response.message, "error")
            }

        },
        error: function (response) {
            $('.spinner-border').remove();
            swal("Maaf!", "Please Try Again..", "error")
        }
    });

};
selectProvinceRO();

/*
|--------------------------------------------------------------------------
| Get City From Drodown Province (RajaOngkir - RO)
|--------------------------------------------------------------------------
*/
$('.rajaongkir-province').change(function () {

    var province_id = $(this).val();
    var el = $(this);

    $.ajax({
        url: base_url + 'address/selectprovince',
        method: "POST",
        dataType: 'json',
        data: {
            province: province_id
        },
        beforeSend: function () {
            resetCourier(); // clear form
            el.attr("readonly", true);
            $('.rajaongkir-city').attr("readonly", true).empty().parent().append('<span class="spinner-border"></span>'); // empty dropdown
            $('.rajaongkir-subdistrict').attr("readonly", true).empty(); // empty dropdown
            $('.rajaongkir-subdistrict').append("<option value='' disabled selected>Kecamatan</option>");
        },
        success: function (response) {
            if (response.status == 'success') {
                $('.spinner-border').remove();
                $('.rajaongkir-city').attr("readonly", false);
                // append options data
                $('.rajaongkir-city').append(response.data);
            } else {
                resetCourier();
                $('.spinner-border').remove();
                swal("Maaf!", response.message, "error")
            }
        },
        error: function (data) {
            $('.spinner-border').remove();
            swal("Maaf!", "Please Try Again..", "error");
        },
        complete: function () {
            el.attr("readonly", false);
        }
    });
});

/*
|--------------------------------------------------------------------------
| Get Sub-District From Drodown City (RajaOngkir - RO)
|--------------------------------------------------------------------------
*/
$('.rajaongkir-city').change(function () {

    var city_id = $(this).val();
    var el = $(this);

    $.ajax({
        url: base_url + 'address/selectdistrict',
        method: "POST",
        dataType: 'json',
        data: {
            district: city_id
        },
        beforeSend: function () {
            resetCourier(); // clear form
            el.attr("readonly", true);
            $('.rajaongkir-subdistrict').attr("readonly", true).empty().parent().append('<span class="spinner-border"></span>'); // empty dropdown
        },
        success: function (response) {
            if (response.status == 'success') {
                $('.spinner-border').remove();
                $('.rajaongkir-subdistrict').attr("readonly", false);
                // append options data
                $('.rajaongkir-subdistrict').append(response.data);
            } else {
                resetCourier();
                $('.spinner-border').remove();
                swal("Maaf!", response.message, "error")
            }
        },
        error: function (data) {
            swal("Maaf!", "Please Try Again..", "error");
        },
        complete: function () {
            el.attr("readonly", false);
        }
    });
});

/*
|--------------------------------------------------------------------------
| Append User Province
|--------------------------------------------------------------------------
*/
function appendUserProvince() {

    var lastUrl = window.location.pathname.split("/").pop();
    var id      = $('[name="id"').val();

    $.ajax({
        url: base_url + "member/getAgentData",
        type: "POST",
        dataType: "json",
        data: {
            id: id
        },
        beforeSend: function () {},
        success: function (response) {
            optionsData = '';
            if (response.status === "success") {
                if ( response.data.province ) {
                    // optionsData += "<option value='" + response.data.province + "," + response.data.province_name + "' selected>" + response.data.province_name + "</option>";
                    $('.rajaongkir-province').val(response.data.province);
                    // $('.rajaongkir-province').attr("readonly", true);
                    $('.rajaongkir-city').attr("readonly", false);
                    $('.rajaongkir-subdistrict').attr("readonly", false);

                    if (lastUrl == 'checkout') {
                        appendCourier();
                        appendPaymentType();
                        //appendPaymentMethod();
                    }
                }
            }
        },
        error: function () {
            swal("Maaf!", "Please Try Again..", "error");
        },

    });

}