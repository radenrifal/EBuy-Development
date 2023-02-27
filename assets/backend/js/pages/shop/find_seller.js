/*
|--------------------------------------------------------------------------
| Form Validation
|--------------------------------------------------------------------------
*/
$(function () {
    $('#find-seller').click(function () {
        var form = $(this).closest("form")
        $(form).validate({
            rules: {}
        });
    });

    $("[name=options_reg]").change(function () {
        var val             = $(this).val();
        $('.opt-toggle-reg').hide();

        if (val == 'agent') {
            $("#tab-option-agent").show('fast');
        }
        return false;
    });
});


/*
|--------------------------------------------------------------------------
| Find Seller
|--------------------------------------------------------------------------
*/
var searchCounter = 0;
$(document).on('click', '#find-seller', function (e) {
    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();

    var form = $(this).closest("form");
    var formData = new FormData($(form)[0]);
    var type = $(this).data('type');
    
    if ($(form).valid()) {
        $.ajax({
            url: base_url + "shop/actionFindSeller",
            type: "POST",
            dataType: "json",
            data: formData,
            mimeType: "multipart/form-data",
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('.loader-screen').show();
                $('.place-alert').addClass('d-none').empty();
                $('#list-seller').addClass('d-none');
                $('#list-seller .profile-page .row').empty();
            },
            success: function (response) {

                if (response.status == "success") {
                    searchCounter = 0;
                    $('#list-seller').removeClass('d-none');

                    if (response.message == "seller by city") {
                        $('.place-alert').html(`
                            <div class="alert alert-success alert-dismissible fade show mb-5 py-3 px-4" role="alert">
                                <i class="fa fa-check mr-1"></i>   
                                Yeayy! Agen terdekat ditemukan...
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `);
                    } else if (response.message == "seller by province") {
                        $('.place-alert').html(`
                            <div class="alert alert-warning alert-dismissible fade show mb-5 py-3 px-4" role="alert">
                                <i class="fa fa-info-circle mr-1"></i>
                                Pencarian Agen di provinsi ditemukan.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `);
                    }

                    var btnChooseSeller = '';
                    var optionsData = '';
                    for (i = 0; i < response.data.length; i++) {

                        if (type == 'shop') {
                            var btnChooseSeller = '<button class="btn btn-primary btn-round choose-seller" data-id="' + response.data[i].id + '"><i class="fa fa-check"></i> Pilih Agen</button>';
                        }

                        optionsData += `
                            <div class="col-md-6">
                                <div class="card profile-header">
                                    <div class="body">
                                        <div class="row">
                                            <div class="col-4 d-flex align-items-center justify-content-center">
                                                <div class="profile-image float-md-right mb-2"> <img src="`+ base_url +`ddmassets/backend/img/icons/avatar.png" alt=""> </div>
                                            </div>
                                            <div class="col-8">
                                                <h5 class="m-t-0 m-b-0 text-capitalize"><strong>` + response.data[i].name + `</strong></h5>
                                                <p class="desc">
                                                    <span class="text-capitalize">` + response.data[i].province_name + `</span>
                                                    <br>
                                                    <span class="text-capitalize">` + response.data[i].district_type + ' ' + response.data[i].district_name + `</span>
                                                    <br>
                                                    <span class="text-capitalize">Kecamatan ` + response.data[i].subdistrict_name + `</span>
                                                </p>
                                                <div>
                                                    <a target=_blank class="btn btn-success btn-round" href="https://api.whatsapp.com/send?phone=` + response.data[i].phone.replace(/0/, '62') + `&text=Hai...Agen DII-DDM saya mau order/tanya tentang produk. Bisakah Anda membantu saya?">
                                                        <i class="fa fa-whatsapp"></i> Chat Agen
                                                    </a>
                                                    ` + btnChooseSeller + `
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                    $('#list-seller .profile-page .row').append(optionsData);

                } else {
                    swal("Maaf!", response.message, "error");

                    if (type == 'shop') {
                        searchCounter++;
                        var urlRandomSeller = base_url + 'shop/actionRandomSeller';
                        if (searchCounter > 2) {
                            $('.place-alert').html(`
                            <div class="alert alert-warning alert-dismissible fade show mb-5 py-3 px-4" role="alert">
                                <i class="fa fa-info-circle mr-1"></i>    
                                <strong>Tidak dapat menemukan Agen?</strong> Silahkan <a href="` + urlRandomSeller + `"><b>klik disini</b></a> untuk cari Agen random
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `);
                        }
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
            complete: function () {
                $('.place-alert').removeClass('d-none');
                $('.loader-screen').hide();
            }
        });
    }
});

/*
|--------------------------------------------------------------------------
| Choose Seller
|--------------------------------------------------------------------------
*/
$(document).on('click', '.choose-seller', function () {
    var el = $(this);
    var idSeller = el.data('id');

    $.ajax({
        url: base_url + 'shop/actionChooseSeller',
        method: "POST",
        dataType: "json",
        data: {
            id: idSeller
        },
        success: function (response) {
            if (response.status == 'success') {
                location.href = response.url;
            }
        },
        error: function () {
            swal("Maaf!", "There was an error..", "error");
        },
    });
    return false; // blocks redirect after submission via ajax
});

/*
|--------------------------------------------------------------------------
| Tracking Options 
|--------------------------------------------------------------------------
*/
$("[name=options_tracking]").change(function () {
    var val = $(this).val();
    $('.opt-tracking-toggle').hide();
    $('#list-seller').addClass('d-none');
    $('#list-seller .profile-page .row').empty();
    $("#tab-" + val).show('fast');
    if (val == 'agent_code') {
        $("[name=agent_code]").val('');
        $("[name=agent_code]").prop('required', true);
        $("[name=province]").prop('required', false).parent().removeClass('error');
    } else if (val == 'agent_location') {
        $("[name=province]").val('');
        $("[name=province]").prop('required', true);
        $("[name=city]").val('').attr('readonly', true);
        $("[name=subdistrict]").val('').attr('readonly', true);
        $("[name=agent_code]").prop('required', false).parent().removeClass('error');
    }
});