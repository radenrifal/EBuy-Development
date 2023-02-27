<?php
    $currency   = config_item('currency');
    include APPPATH . 'views/shop/components/header.php';
    include APPPATH . 'views/shop/components/mobile/nav_back.php'; 
?>

<div class="ps-page--simple">
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?= base_url() ?>">Home</a></li>
                <li><a href="<?= base_url('shop') ?>">Shop</a></li>
                <li>Shopping Cart</li>
            </ul>
        </div>
    </div>
    <div class="ps-section--shopping ps-shopping-cart section-shadow">
        <div class="container">
            <div class="ps-section__header">
                <h2>Shopping Cart</h2>
            </div>

            <?php if (empty($this->cart->contents())) { ?>
                <center>
                    <p>Your cart is currently empty.</p>
                    <p class="return-to-shop">
                        <a class="ps-btn" href="<?= base_url('shop') ?>">Return to shop </a>
                    </p>
                </center>
            <?php } else { ?>

                <div class="ps-section__content">

                    <!--
                        <div class="px-5 py-3 mb-5 options-reg" style="border: 1px solid #117a8b;">
                            <div class="form-group mb-0 mt-2">
                                <div class="row">
                                    <div class="col-md-8 mb-3">
                                        Apakah Anda ingin menjadi Agen ?
                                    </div>
                                    <div class="col-md-4 col-sm-12 text-right">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label id="opt-agent" class="btn btn-info px-4 py-2">
                                                <input value="agent" type="radio" name="options_reg" autocomplete="off"> Ya
                                            </label>
                                            <label id="opt-customer" class="btn btn-info px-3 py-2 active">
                                                <input value="customer" type="radio" name="options_reg" autocomplete="off" checked> Tidak, Cukup Konsumen Saja
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row opt-toggle-reg mt-3" id="tab-option-agent" style="display:none">
                                <?php include APPPATH . 'views/shop/components/howto.php'; ?>
                            </div>
                        </div>
                    -->

                    <?php if ($carts['has_error']) { ?>
                        <div class="shop-info warning">
                            <span>Salah satu produk anda bermasalah! Silahkan <b>Update Cart</b> untuk melanjutkan.</span>
                        </div>
                    <?php } ?>

                    <div class="table-responsive">
                        <table class="shop_table shop_table_responsive">
                            <thead style="background:#f7f7f7">
                                <tr>
                                    <th class="product-remove">&nbsp;</th>
                                    <th class="product-thumbnail">&nbsp;</th>
                                    <th class="product-name">Product</th>
                                    <th class="product-price">Price</th>
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-subtotal">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($carts['data'] as $row) { ?>

                                    <?php
                                        if ( $get_product = ddm_products($row['product_id']) ) {
                                            if (  $get_product->image ) {
                                                $img_src = product_image($get_product->image);
                                            } else {
                                                $img_src = ASSET_PATH . 'backend/img/no_image.jpg'; 
                                            }
                                        } else {
                                            $img_src = ASSET_PATH . 'backend/img/no_image.jpg'; 
                                        }

                                        $discount       = 0;
                                        $discount_info  = ( $row['disc_min_qty'] > 1 && $row['disc_amount'] > 0 ) ? true : false;
                                        $discount_msg   = '<strong>Kabar Baik!</strong> ';
                                        if ( $row['disc_amount'] > 0 ) {
                                            if ( $row['disc_type'] == 'nominal' ) {
                                                $discount       = ddm_accounting($row['disc_amount'], $currency);
                                                $price_promo    = ddm_accounting(($row['product_price'] - $row['disc_amount']), $currency);
                                                $discount_msg  .= 'Ada potongan harga sebesar '. $discount .' per-Pcs jika Anda membeli <b class="text-capitalize">'. $row['product_name'] .'</b> minimal '. $row['disc_min_qty'];
                                            }
                                            if ( $row['disc_type'] == 'percent' ) {
                                                $discount       = $row['disc_amount'] .' %';
                                                $discount_msg  .= 'Anda akan mendapatkan diskon sebesar <b>'. $discount .'</b> jika membeli <b class="text-capitalize">'. $row['product_name'] .'</b> minimal '. $row['disc_min_qty'];
                                            }
                                        }

                                        $discount_show  = ( $row['disc_amount'] > 0 && $row['qty'] >= $row['disc_min_qty'] ) ? true : false; 
                                        $readonly       = ( is_logged_in() ) ? 'readonly="readonly"' : '';
                                        $show_stock     = false;
                                    ?>

                                    <?php if ( $discount_info ) { ?>
                                        <div class="alert alert-warning alert-dismissible fade show mb-4 py-3 px-4" role="alert">
                                            <?= $discount_msg; ?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    <?php } ?>

                                    <tr class="cart_item">
                                        <td class="product-remove" title="Hapus Produk">
                                            <a href="javascript:;" class="deleteCartItem remove" data-rowid="<?= $row['rowid'] ?>" data-packageid="0"> <i class="fa fa-trash"></i> </a>
                                        </td>
                                        <td class="product-thumbnail" data-title="">
                                            <a href="<?= base_url('product/detail/' . $row['product_slug']) ?>">
                                                <img class="img-fluid" src="<?= $img_src; ?>" alt="product-img">
                                            </a>
                                        </td>
                                        <td class="product-name" data-title="Product">
                                            <a class="text-capitalize" href="<?= base_url('product/detail/' . $row['product_slug']) ?>"> <?= $row['product_name'] ?> </a>
                                            <?php if ( $readonly ) { ?>
                                                <div style="color: #5e72e4; font-size: 12px">Minimal Order : <?= $row['min_order'] ?></div>
                                            <?php } ?>
                                        </td>
                                        <td class="product-price" data-title="Price">
                                            <span class="Price-amount">
                                                <span class="price-data"><?= ddm_accounting($row['product_price']) ?></span>
                                                <span class="discount-data"><?= ($discount_show ? '<sup>-' . $discount . '</sup>'  : '') ?></span>
                                            </span>
                                        </td>
                                        <td class="product-quantity" data-title="Quantity">
                                            <div class="form-group--number">
                                                <button class="up" onclick="qtyPlus(this, <?= $row['min_order'] ?>)"> + </button>
                                                <button class="down" onclick="qtyMin(this, <?= $row['min_order'] ?>)"> - </button>
                                                <input class="form-control numberQty" type="number" data-rowid="<?= $row['rowid'] ?>" data-productid="<?= ddm_encrypt($row['product_id']) ?>" data-weight="<?= $row['weight'] ?>" value="<?= $row['qty'] ?>" step="<?= $row['min_order'] ?>" min="1" name="qty" title="Qty" pattern="[0-9]*" inputmode="numeric" <?= $readonly; ?> />
                                                <?php if ( $readonly && $show_stock ) { ?>
                                                    <div style="text-align: center;margin-top: 3px;color: <?= ($row['qty'] > $row['product_stock']) ? 'red' : 'green' ?>">
                                                        Stok : <?= $row['product_stock'] ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </td>
                                        <td class="product-subtotal" data-title="Total">
                                            <span class="total-cart"><?= ddm_accounting($row['cart_price'] * $row['qty']) ?></span>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="ps-section__footer">
                    <div class="row">
                        <div class="col-md-6 ">
                            <figure>
                                <figcaption style="margin-bottom:20px">
                                    Discount
                                    <?php
                                    $promo_amount   = $this->session->userdata('promo_amount');
                                    $promo_type     = $this->session->userdata('promo_type');
                                    ?>
                                    <?= (!$this->session->userdata('promo_applied')) ?  '' : ($promo_type == 'nominal' ? '<sup>(-' . ddm_accounting($promo_amount) . ')</sup>' : '<sup>(-' . $promo_amount . '%)</sup>' ) ?>
                                </figcaption>
                                <form id="form-input-promo">
                                    <div class="form-group">
                                        <input class="form-control text-uppercase" type="text" name="code_discount" placeholder="Masukkan kode promo untuk mendapatkan diskon" value="<?= ($this->session->userdata('promo_applied')) ? $this->session->userdata('promo_code') : '' ?>">
                                        <?php if ($this->session->userdata('promo_applied')) { ?>
                                            <small class="form-text text-muted color-green">
                                                Kode diskon berhasil digunakan. <a href="<?= base_url('shop/removeDiscount') ?>" style="color:red">[Hapus Diskon]</a>
                                            </small>
                                        <?php } ?>
                                    </div>
                                </form>
                            </figure>
                        </div>
                        <div class="col-md-6">
                            <div class="ps-block--shopping-total">
                                <div class="ps-block__header">
                                    <p>Diskon <span class="promo-discount"> <?= ($this->session->userdata('promo_applied')) ? ddm_accounting(total_promo('discount')) : '0' ?></span></p>
                                    <p>Subtotal <span class="subtotal-cart"> <?= ddm_accounting($this->cart->total()); ?> </span></p>
                                </div>
                                <h3>Total <span class="promo-total"><?= ddm_accounting(total_promo('amount')) ?> </span></h3>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a class="ps-btn green ps-btn--fullwidth text-center applyDiscount" href="javascript:;"><i class="fa fa-check" aria-hidden="true"></i> Apply Kode Promo</a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a class="ps-btn grey btn-secondary btn ps-btn--fullwidth text-center emptyCart" href="<?= base_url('shop/destroyCart') ?>"><i class="fa fa-times" aria-hidden="true"></i> Empty Cart</a>
                        </div>
                        <div class="col-md-4">
                            <?php if ($carts['has_error']) { ?>
                                <a class="ps-btn ps-btn--fullwidth text-center" href="<?= base_url('shop/updateCart') ?>">Update cart</a>
                            <?php } else { ?>
                                <a class="ps-btn ps-btn--fullwidth text-center" href="<?= base_url('checkout') ?>">Proceed to checkout <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>