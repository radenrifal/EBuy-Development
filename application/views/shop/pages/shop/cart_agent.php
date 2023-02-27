<?php
    include APPPATH . 'views/shop/components/header.php';
    include APPPATH . 'views/shop/components/mobile/nav_back.php'; 
    
    $auth           = auth_redirect( true );

    //if(!empty($auth)){
        $currency       = config_item('currency');
        $condition      = array('status' => 'perdana');
        $member         = ddm_get_current_member();
        $status_order   = 'ro';
        $min_order      = config_item('min_order_agent');;
        $multiple       = true;
        $cfg_minorder   = config_item('order_type');
        $cfg_minorder   = isset($cfg_minorder[$status_order]) ? $cfg_minorder[$status_order] : false;
        $count_minorder = ($cfg_minorder) ? count($cfg_minorder) : 0;
        $total_item     = $this->cart->total_items();
    //}
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

            <?php //if (empty($auth)) { ?>
                <!--
                <center>
                    <p>Please login your account</p>
                    <p class="return-to-shop">
                        <a class="ps-btn" href="<?= base_url('login') ?>">Login</a>
                    </p>
                </center>
                -->
            <?php //} else
                if (empty($this->cart->contents())) { ?>
                <center>
                    <p>Your cart is currently empty.</p>
                    <p class="return-to-shop">
                        <a class="ps-btn" href="<?= base_url('shop') ?>">Return to shop </a>
                    </p>
                </center>
            <?php } else { ?>

                <div class="ps-section__content">

                    <?php if ($carts['has_error']) { ?>
                        <div class="shop-info warning">
                            <span>Salah satu produk anda bermasalah! Silahkan <b>Update Cart</b> untuk melanjutkan.</span>
                        </div>
                    <?php } ?>

                    <?php if ( $cfg_minorder ) { ?>
                        <?php if ( $count_minorder > 1 ) { ?>
                            <div class="px-5 py-3 mb-5" style="border: 1px solid #117a8b;">
                                <div class="form-group mb-0 mt-2">
                                    <div class="row">
                                        <div class="col-md-9 col-sm-7 mb-3">
                                            <label class="mt-3"><b>Silahkan Pilih Box Produk</b></label>
                                        </div>
                                        <div class="col-md-3 col-sm-5 text-right">
                                            <select name="total_qty" class="form-control totalQtyOrder" id="totalQtyOrder" hidden>
                                                <?php 
                                                    foreach ($cfg_minorder as $key => $row) {
                                                        $selected = ($total_item == $row['min_qty'] ) ? 'selected' : '';
                                                        echo '<option value="'.$row['min_qty'].'" '.$selected.'>'.$row['min_qty'].' '. lang('package') .'</option>';
                                                    } 
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <?php
                                $min_qty_order  = $cfg_minorder[0]['min_qty'];
                                if ( $total_item > $min_qty_order ) {
                                    $total_item_mod = $total_item % $min_qty_order; 
                                    if ( $total_item_mod > 0 ) {
                                        $min_qty_order = $total_item - $total_item_mod;
                                    } else {
                                        $min_qty_order = $total_item;
                                    }
                                }
                            ?>
                            <div class="alert alert-info alert-dismissible fade show mb-4 py-3 px-4" role="alert">
                                Minimal Order <?php echo $cfg_minorder[0]['min_qty'] ?> Produk !
                            </div>
                            
                            <!--
                            <div class="px-5 py-3 mb-5" style="border: 1px solid #117a8b;">
                                <div class="form-group mb-0 mt-2">
                                    <div class="row">
                                        <div class="col-md-9 col-sm-7 mb-3">
                                            <label class="mt-1" style="font-size: 16px">Total Qty Order Produk</label>
                                        </div>
                                        <div class="col-md-3 col-sm-5 text-right total-package-quantity">
                                            <div class="form-group--number">
                                                <?php if ( $cfg_minorder[0]['min_qty'] ) { ?>
                                                    <!--
                                                    <button class="up" onclick="totalQtyPlus(this, 1)"> + </button>
                                                    <button class="down" onclick="totalQtyMin(this, 1)"> - </button>
                                                    -->
                                                <?php } ?>
                                                <!--
                                                <input class="form-control totalQtyOrder" type="number" 
                                                    value="<?= $min_qty_order ?>" 
                                                    step="<?= $cfg_minorder[0]['min_qty'] ?>" 
                                                    min="<?= $cfg_minorder[0]['min_qty'] ?>" 
                                                    name="total_qty"
                                                    title="Total Qty" pattern="[0-9]*" inputmode="numeric" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            -->

                        <?php } ?>
                    <?php } ?>

                    <div class="table-responsive">
                        <table class="shop_table shop_table_responsive">
                            <thead style="background:#f7f7f7">
                                <tr>
                                    <th class="product-remove">&nbsp;</th>
                                    <th class="product-thumbnail">&nbsp;</th>
                                    <th class="product-name"><?php echo lang('package'); ?></th>
                                    <th class="product-price"><?php echo lang('price'); ?></th>
                                    <th class="product-quantity">Qty</th>
                                    <th class="product-subtotal">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $num = 1; 
                                    foreach ($carts['data'] as $row) { ?>

                                    <?php
                                        if ( $get_product = ddm_products($row['id']) ) {
                                            if (  $get_product->image ) {
                                                $img_src = product_image($get_product->image);
                                            } else {
                                                $img_src = ASSET_PATH . 'backend/img/no_image.jpg'; 
                                            }
                                        } else {
                                            $img_src = ASSET_PATH . 'backend/img/no_image.jpg'; 
                                        }

                                    ?>

                                    <tr class="cart_item">
                                        <td class="product-remove" title="Hapus Produk">
                                            <a href="javascript:;" class="deleteCartItemPack remove" data-rowid="<?= $row['rowid'] ?>" data-packageid="0"> <i class="fa fa-trash"></i> </a>
                                        </td>
                                        <td class="product-thumbnail" data-title="">
                                            <a href="<?= base_url('product/detail/' . $row['product_slug']) ?>">
                                                <img class="img-fluid" src="<?= $img_src; ?>" alt="product-img">
                                            </a>
                                        </td>
                                        <td class="product-name" data-title="Product">
                                            <a class="text-capitalize" href="<?= base_url('product/detail/' . $row['product_slug']) ?>"> <?= $row['product_name'] ?> </a>
                                        </td>
                                        <td class="product-price" data-title="Price">
                                            <span class="Price-amount">
                                                <span class="price-data"><?= ddm_accounting($row['cart_price']) ?></span>
                                            </span>
                                        </td>
                                        <td class="product-quantity" data-title="Quantity">
                                            <div class="form-group--number">
                                                <button class="up" onclick="qtyPackagePlus(this, 15)"> + </button>
                                                <button class="down" onclick="qtyPackageMin(this, 15)"> - </button>
                                                <!--
                                                <button class="up"> + </button>
                                                <button class="down"> - </button>
                                                -->
                                                <input class="form-control numberQtyAgent" type="number" 
                                                    data-rowid="<?= $row['rowid']; ?>" 
                                                    data-productid="<?= ddm_encrypt($row['product_id']); ?>" 
                                                    data-weight="<?= $row['weight']; ?>" 
                                                    data-price="<?= $row['cart_price']; ?>" 
                                                    data-num="<?= $num; ?>" 
                                                    value="<?= $row['qty']; ?>" 
                                                    step="1" 
                                                    min="1" 
                                                    name="qty" 
                                                    title="Qty" pattern="[0-9]*" inputmode="numeric" />
                                            </div>
                                        </td>
                                        <td class="product-subtotal" data-title="Total">
                                            <span class="total-cart total-cart-<?= $num ?>"><?= ddm_accounting($row['cart_subtotal']) ?></span>
                                        </td>
                                    </tr>
                                <?php $num++; } ?>

                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="ps-section__footer">
                    <div class="row">
                        <!--
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
                        -->
                        <div class="col-md-12">
                            <div class="ps-block--shopping-total">
                                <div class="ps-block__header">
                                    <p>Subtotal <span class="subtotal-cart"> <?= ddm_accounting($this->cart->total()); ?> </span></p>
                                    <p>Diskon <span class="promo-discount"> <?= (true) ? ddm_accounting(total_promo('discount')) : '0' ?></span></p>
                                </div>
                                <h3>Total <span class="promo-total"><?= ddm_accounting(total_promo('amount')) ?> </span></h3>
                            </div>
                        </div>
                        <!--
                        <div class="col-md-4 mb-3">
                            <a class="ps-btn green ps-btn--fullwidth text-center applyDiscount" href="javascript:;"><i class="fa fa-check" aria-hidden="true"></i> Apply Kode Promo</a>
                        </div>
                        -->
                        <div class="col-md-6 mb-3">
                            <a class="ps-btn grey btn-secondary btn ps-btn--fullwidth text-center emptyCart" href="<?= base_url('shop/destroyCart') ?>"><i class="fa fa-times" aria-hidden="true"></i> Empty Cart</a>
                        </div>
                        <div class="col-md-6">
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