// ===========================================================
// Read Url File
// ===========================================================
var upload_img;

var readURL = function(input, img_id) {
    if (input[0].files && input[0].files[0]) {
        var typeFile    = input[0].files[0].type;
        var sizeFile    = input[0].files[0].size;
        var _size       = Math.round(sizeFile/1024);
        $('.img-information').show();

        var reader = new FileReader();
        reader.onload = function (e) {
            img_id.attr('src', e.target.result);
            img_id.show();

            if ( $('#size_img_thumbnail').length ) {
                if ( _size >= 1024 ) {
                    _size = Math.round(_size/1024);
                    _size = _size + ' MB';
                } else {
                    _size = _size + ' KB';
                }
                $('#size_img_thumbnail').text('Size : ' + _size);
            }
        }

        reader.readAsDataURL(input[0].files[0]);
    }
};

/*
|--------------------------------------------------------------------------
| Read File Upload
|--------------------------------------------------------------------------
*/
$(document).on('change', '#upload_file', function (e) {
    e.preventDefault();
    readURL( $(this), $('#upload_img_thumbnail') );
    upload_img = e.target.files;
});

/*
|--------------------------------------------------------------------------
| Save Payment Confirmation
|--------------------------------------------------------------------------
*/
$(document).on('click', '#saveConfirmPayment', function (e) {

    e.preventDefault();

    var el = $(this);
    var form = el.closest("form");
    var redirect = form.data('rdr');
    var formData = new FormData($(form)[0]);

    if (upload_img) {
        $.each(upload_img, function(key, value){
            formData.append('upload_img', value);
        });
    }

    form.validate({
        rules: {
            bill_bank: {
                required: true,
            },
            bill_no: {
                required: true,
                digits: true
            },
            bill_name: {
                required: true,
            },
            transfer: {
                required: true,
                digits: true
            },
        },
    });

    if ($(form).valid()) {

        // Swal Confirmation
        swal({
            title: "Konfirmasi",
            text: "Apakah Data Anda Telah Benar?",
            type: "warning",
            showCancelButton: !0

        }).then(isConfirm => {
            if (isConfirm.value) {

                $.ajax({
                    url: base_url + 'general/savePaymentEvidence',
                    method: "POST",
                    dataType: "json",
                    data: formData,
                    mimeType: "multipart/form-data",
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('.loader-screen').show();
                        el.hide().attr("disabled", true).off('click'); //set button disable and off click
                    },
                    success: function (result) {
                        if (result.status == "success") {
                            swal("Success!", result.message, "success");
                            if (redirect == 'reload') {
                                setTimeout(function () {
                                    location.reload();
                                }, 2000);
                            } else if (redirect == '#') {
                                location.href = redirect;
                            } else {
                                setTimeout(function () {
                                    location.href = base_url + redirect;
                                }, 2000);
                            }
                        } else {
                            $('.loader-screen').hide();
                            swal("Maaf!", result.message, "error");
                        }
                    },
                    error: function () {
                        swal("Maaf!", "There was an error..", "error");
                    },
                    complete: function () {
                        el.show().attr("disabled", false).on('click');
                        $('.loader-screen').hide();
                    }
                });
                return false; // blocks redirect after submission via ajax
            }
        });
    }
});