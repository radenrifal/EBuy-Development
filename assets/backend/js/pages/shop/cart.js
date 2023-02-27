/*
|--------------------------------------------------------------------------
| Add To Cart Button
|--------------------------------------------------------------------------
*/
$(document).on('click', '.addCart', function () {

    el = $(this);
    id = $(this).data("id");
    qty = $(this).data("qty");
    type = $(this).data("type");

    $.ajax({
        url: base_url + 'shop/addToCart',
        method: "POST",
        dataType: "json",
        data: {
            id: id,
            qty: qty
        },
        beforeSend: function () {
            el.removeClass('addCart').addClass('goCart');
            el.attr("disabled", true); //set button disable
            el.text('Proccessing..');
        },
        success: function (result) {
            if (result.status == 'success') {

                if (type == 'buynow') {
                    location.href = base_url + 'checkout';
                    el.text('Go to Checkout');
                } else {
                    Snackbar.show({
                        text: result.message,
                        pos: 'bottom-center'
                    });
                    el.text('Go to cart').addClass('btn-gocart');
                }

                el.attr("disabled", false); //set button disable
                el.addClass('goCart');
                $('.floating-cart').removeClass('d-none');
                $('.cart-item-count').html(infoCart().total_item);
                $('.cart-total-amount').html(numberFormat('Rp ' + infoCart().total_amount));
            } else {
                Snackbar.show({
                    text: result.message,
                    pos: 'bottom-center'
                });
                el.text('Add to cart');
            }
        },
        error: function (result) {
            console.log(result);
            el.removeClass('goCart').addClass('addCart');
            Snackbar.show({
                text: 'Try again later!',
                pos: 'bottom-center'
            });
        },
        complete: function (result) {
            el.attr("disabled", false); //set button disable
        }
    });
    return false; // blocks redirect after submission via ajax

});

$(document).on('click', '.goCart', function () {
    location.href = base_url + 'cart';
});


/*
|--------------------------------------------------------------------------
| Remove Cart Button
|--------------------------------------------------------------------------
*/
$(".deleteCartItem").on("click", function (e) {
    e.preventDefault();

    var rowid       = $(this).data("rowid");
    var packageid   = $(this).data("packageid");

    swal({
        title: "Konfirmasi",
        text: "Apakah anda yakin untuk menghapus item ini?",
        type: "warning",
        showCancelButton: !0

    }).then(isConfirm => {
        if (isConfirm.value) {

            $.ajax({
                url: base_url + 'shop/deleteCart',
                method: "POST",
                dataType: "json",
                data: {
                    rowid: rowid,
                    packageid: packageid
                },
                success: function (result) {
                    if (result['status'] == 'success') {
                        swal({
                            title: "Congratulation!",
                            text: result['message'],
                            type: "success",
                            closeOnClickOutside: false
                        }).then(function () {
                            location.reload();
                        });
                    } else {
                        swal("Maaf!", result['message'], "error")
                    }
                },
                error: function (result) {
                    swal("Maaf!", result['message'], "error")
                }
            });
            return false; // blocks redirect after submission via ajax
        }
    });
});

/*
|--------------------------------------------------------------------------
| Remove Cart Button
|--------------------------------------------------------------------------
*/
$(".deleteCartItemPack").on("click", function (e) {
    e.preventDefault();

    var rowid       = $(this).data("rowid");
    var packageid   = $(this).data("packageid");

    swal({
        title: "Konfirmasi",
        text: "Apakah anda yakin untuk menghapus item ini?",
        type: "warning",
        showCancelButton: !0

    }).then(isConfirm => {
        if (isConfirm.value) {

            $.ajax({
                url: base_url + 'shop/deleteCart',
                method: "POST",
                dataType: "json",
                data: {
                    rowid: rowid,
                    packageid: packageid
                },
                success: function (result) {
                    if (result['status'] == 'success') {
                        updateQtyAllProductPackage('delete', result['message']);
                    } else {
                        swal("Maaf!", result['message'], "error")
                    }
                },
                error: function (result) {
                    swal("Maaf!", result['message'], "error")
                }
            });
            return false; // blocks redirect after submission via ajax
        }
    });
});

/*
|--------------------------------------------------------------------------
| Remove Cart Button
|--------------------------------------------------------------------------
*/
$(".emptyCart").on("click", function (e) {
    e.preventDefault();

    var rowid = $(this).data("rowid");

    swal({
        title: "Konfirmasi",
        text: "Apakah anda yakin untuk menghapus semua item di cart?",
        type: "warning",
        showCancelButton: !0

    }).then(isConfirm => {
        if (isConfirm.value) {

            $.ajax({
                url: base_url + 'shop/emptyCart',
                method: "POST",
                dataType: "json",
                data: {
                    rowid: rowid
                },
                success: function (result) {
                    if (result['status'] == 'success') {
                        swal({
                            title: "Success!",
                            text: result.message,
                            type: "success",
                            closeOnClickOutside: false
                        }).then(function () {
                            location.reload();
                        });
                    } else {
                        swal("Maaf!", result['message'], "error")
                    }
                },
                error: function (result) {
                    swal("Maaf!", result['message'], "error")
                }
            });
            return false; // blocks redirect after submission via ajax
        }
    });
});


/*
|--------------------------------------------------------------------------
| + / - Quantity Func
|--------------------------------------------------------------------------
*/
function qtyPlus(el, step = 1) {
    var count   = $(el).closest(".product-quantity").find('.numberQty').val();
    var countEl = $(el).closest(".product-quantity").find('.numberQty');

    // count++;
    count = parseInt(count) + parseInt(step);
    countEl.val(count).change();
}

function qtyMin(el, step = 1) {
    var count   = $(el).closest(".product-quantity").find('.numberQty').val();
    var countEl = $(el).closest(".product-quantity").find('.numberQty');

    if ( parseInt(count) > parseInt(step) ) {
        // count--;
        count = parseInt(count) - parseInt(step);
        countEl.val(count).change();
    }
}

/*
|--------------------------------------------------------------------------
| + / - Quantity Package Func
|--------------------------------------------------------------------------
*/
function qtyPackagePlus(el, step = 1) {
    var total   = $('.totalQtyOrder').val();
    var count   = $(el).closest(".product-quantity").find('.numberQtyAgent').val();
    var countEl = $(el).closest(".product-quantity").find('.numberQtyAgent');

    var total   = parseInt(count) + parseInt(step);
    //console.log(total);
    // count++;
    if ( parseInt(total) > parseInt(count) ) {
        count = parseInt(count) + parseInt(step);
        countEl.val(count).change();
    }
}

function qtyPackageMin(el, step = 1) {
    var total   = $('.totalQtyOrder').val();
    var min     = 15;
    var count   = $(el).closest(".product-quantity").find('.numberQtyAgent').val();
    var countEl = $(el).closest(".product-quantity").find('.numberQtyAgent');
    
    var total   = parseInt(count) + parseInt(step);
    if(count < min){
        var total   = min;
    }
    //console.log(total +' = '+min);
    
    if(count > min){
        if ( parseInt(count) > parseInt(step) && parseInt(total) > parseInt(count) ) {
            count = parseInt(count) - parseInt(step);
            countEl.val(count).change();
        }
    }
}

/*
$(document).ready(function () {
    $("body").delegate( ".up", "click", function( event ) {
        event.preventDefault();
        qtyPackagePlus(".up", 1);
    });
    
    $("body").delegate( ".down", "click", function( event ) {
        event.preventDefault();
        qtyPackageMin(".down", 1);
    });
});
*/



/*
|--------------------------------------------------------------------------
| + / - Total Quantity Package Func
|--------------------------------------------------------------------------
*/
function totalQtyPlus(el, step = 1) {
    var total   = $('.totalQtyOrder').val();
    var count   = $(el).closest(".total-package-quantity").find('.totalQtyOrder').val();
    var countEl = $(el).closest(".total-package-quantity").find('.totalQtyOrder');

    // count++;
    count = parseInt(count) + parseInt(step);
    countEl.val(count).change();
}

function totalQtyMin(el, step = 1) {
    var total   = $('.totalQtyOrder').val();
    var count   = $(el).closest(".total-package-quantity").find('.totalQtyOrder').val();
    var countEl = $(el).closest(".total-package-quantity").find('.totalQtyOrder');

    //if ( parseInt(count) > parseInt(step) ) {
    if ( parseInt(count) > 15 ) {
        count = parseInt(count) - parseInt(step);
        countEl.val(count).change();
    }
}

/*
|--------------------------------------------------------------------------
| Update Qty
|--------------------------------------------------------------------------
*/
$(".numberQty").on("change", function (e) {
    e.preventDefault();

    var rowid = $(this).data("rowid");
    var productid = $(this).data("productid");
    var qty = $(this).val();
    var totalCart = $(this).closest(".cart_item").find('.total-cart');
    var priceCart = $(this).closest(".cart_item").find('.price-data');
    var discountByQty = $(this).closest(".cart_item").find('.discount-data');

    if (qty == 0) {
        location.reload();
    } else {
        $.ajax({
            url: base_url + 'shop/updateQty',
            method: "POST",
            dataType: "json",
            data: {
                rowid: rowid,
                productid: productid,
                qty: qty
            },
            success: function (result) {

                if (result.status == 'success') {
                    totalCart.text(result.total_cart);
                    priceCart.text(result.price);
                    if( result.discount_by_qty ) {
                        if ( result.discount_by_qty == 'percent' ) {
                            discountByQty.html('<sup>-' + result.discount_by_qty + '%</sup>');
                        } else {
                            discountByQty.html('<sup>-' + result.discount_by_qty + '</sup>');
                        }
                    } else {
                        discountByQty.html('');
                    }
                    $(".subtotal-cart").text(result.subtotal_cart);
                    $(".promo-discount").text(result.total_promo_discount);
                    $(".promo-total").text(result.total_promo_amount);
                } else {
                    swal({
                        title: "Error!",
                        text: result.message,
                        type: "error",
                        closeOnClickOutside: false
                    }).then(function () {
                        location.reload();
                    });
                }
            }
        });
    }
});

/*
|--------------------------------------------------------------------------
| Update Qty Product Package
|--------------------------------------------------------------------------
*/
$(".totalQtyOrder").on("change", function (e) {
    e.preventDefault();
    updateQtyAllProductPackage();
});

$(".numberQtyAgent").on("change", function (e) {
    e.preventDefault();
    var rowid       = $(this).data('rowid');
    var qty         = $(this).val();
    //var total_qty   = $('.totalQtyOrder').val();
    var total_qty   = $('.numberQtyAgent').val();
    var el_qty_all  = $('.numberQtyAgent');

    if ( el_qty_all.length > 1 ) {
        total_product   = parseInt(el_qty_all.length);
        product_mod     = total_product - 1;
        total_qty_mod   = parseInt(total_qty) - parseInt(qty);
        _qty            = 0;
        if ( parseInt(qty) >= parseInt(total_qty) ) {
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
            _idProduct  = $(this).data('productid');

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
        e.preventDefault();
        updateQtyAllProductPackage();
        return false;
    }

    processUpdateQtyProductPackage();
});

// ---------------------------------
// Update All Qty Product Package
// ---------------------------------
function updateQtyAllProductPackage (type = '', msg = '') {
    //var total_qty   = $('.totalQtyOrder').val();
    var total_qty   = $('.numberQtyAgent').val();
    var el_qty_all  = $('.numberQtyAgent');
    
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

        if ( updatedata ) {
            updateQty = true;
        }
    }

    if ( updateQty ) {
        processUpdateQtyProductPackage(type, msg);
    }
    return false;
};

// ---------------------------------
// Proccess Update All Qty Product Package
// ---------------------------------
function processUpdateQtyProductPackage (type = '', msg = '') {
    var el_qty_all  = $('.numberQtyAgent');
    var updatedata  = [];

    el_qty_all.each(function(index) {
        _val        = $(this).val();
        _idRow      = $(this).data('rowid');
        _idProduct  = $(this).data('productid');

        updatedata[index] = {
            rowid: _idRow,
            productid: _idProduct,
            qty: _val
        }
    });

    if ( updatedata ) {
        $.ajax({
            url: base_url + 'shop/updateQtyAgent',
            method: "POST",
            dataType: "json",
            data: {
                products: updatedata
            },
            success: function (result) {
                if (result.status == 'success') {
                    $(".subtotal-cart").text(result.subtotal_cart);
                    $(".promo-discount").text(result.total_promo_discount);
                    $(".promo-total").text(result.total_promo_amount);
                    el_qty_all.each(function(index) {
                        qty         = $(this).val();
                        price       = $(this).data('price');
                        num         = $(this).data('num');
                        subtotal    = parseInt(qty) * parseInt(price);
                        $(".total-cart-"+num).text(accounting(subtotal, ''));
                    });
                    if ( type == 'delete') {
                        swal({
                            title: "Congratulation!",
                            text: msg,
                            type: "success",
                            closeOnClickOutside: false
                        }).then(function () {
                            location.reload();
                        });
                    }
                } else {
                    swal({
                        title: "Error!",
                        text: result.message,
                        type: "error",
                        closeOnClickOutside: false
                    }).then(function () {
                        location.reload();
                    });
                }
            },
            error: function (result) {
                swal({
                    title: "Error!",
                    text: 'Terjadi kesalahan sistem. Coba beberapa saat lagi',
                    type: "error",
                    closeOnClickOutside: false
                }).then(function () {
                    location.reload();
                });
            },
        });
    }
    return false;
};

/*
|--------------------------------------------------------------------------
| Check stock
|--------------------------------------------------------------------------
*/
$(".checkStock").on("change", function (e) {

    e.preventDefault();

    var qty = $(this).val();
    var productid = $(this).data("productid");
    var el = $(this).closest(".quantity").find('.checkStock');

    if (qty == 0) {
        el.val('1');
    } else {

        $.ajax({
            url: base_url + 'shop/checkStock',
            method: "POST",
            dataType: "json",
            data: {
                productid: productid,
                qty: qty
            },
            success: function (result) {
                if (result.status !== 'success') {
                    swal("Maaf!", result.message, "error")
                    el.val(result.stock);
                }
            }
        });
    }

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

    el = $(this);
    formId = '#form-input-promo';

    $(formId).validate({
        rules: {
            code_discount: {
                required: true,
                nospace: true
            },
        }
    });

    if ($(formId).valid()) {
        $.ajax({
            url: base_url + 'shop/applyDiscount',
            method: "POST",
            dataType: "json",
            data: $(formId).serialize(),
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
                        location.reload();
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

/*
|--------------------------------------------------------------------------
| Call php function on js | Count Total Cart
|--------------------------------------------------------------------------
*/
function countTotalCart() {
    $.ajax({
        type: 'POST',
        dataType: "json",
        async: false,
        url: base_url + "shop/countTotalCart",
        success: function (result) {
            response = result.data;
        }
    });
    return response;

}

/*
|--------------------------------------------------------------------------
| Register type Options
|--------------------------------------------------------------------------
*/
$("[name=options_reg]").change(function () {
    var val             = $(this).val();
    $('.opt-toggle-reg').hide();

    if (val == 'agent') {
        $("#tab-option-agent").show('fast');
    }
    return false;
});

updateQtyAllProductPackage();