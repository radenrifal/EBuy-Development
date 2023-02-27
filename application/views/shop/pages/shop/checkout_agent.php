<?php include APPPATH . 'views/shop/components/header.php'; ?>
<?php include APPPATH . 'views/shop/components/mobile/nav_back.php'; ?>
<?php 
    $total_item         = $this->cart->total_items();
    $qty_free_shipping  = get_option('qty_package_free_shipping');
    $qty_free_shipping  = $qty_free_shipping ? $qty_free_shipping : 0;
?>

<div class="ps-page--simple">
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?= base_url() ?>">Home</a></li>
                <li><a href="<?= base_url('cart') ?>">Cart</a></li>
                <li>Checkout</li>
            </ul>
        </div>
    </div>
    <div class="ps-checkout ps-shopping ps-section--shopping section-shadow">
        <div class="container">
            <div class="ps-section__header">
                <h2>Form Checkout</h2>
            </div>

            <div class="ps-section__content">
                <form class="ps-form--checkout" id="form-checkout" data-rdr="link">
                    <input type="hidden" name="id" value="<?= ($user) ? ddm_encrypt($user->id) : ''; ?>">
                    <input type="hidden" name="id_agent" value="<?= ( isset($agent->id) ) ? ddm_encrypt($agent->id) : ''; ?>">
                    <input type="hidden" name="total_item" value="<?= $total_item; ?>">

                    <div class="row">
                        <div class="col-md-6 col-sm-12 ">
                            <div class="ps-form__billing-info">
                                <h3 class="ps-form__heading">Alamat Pengiriman</h3>
                                <div class="form-group">
                                    <label>Nama <sup>*</sup></label>
                                    <input name="shipping_name" value="<?= ($user) ? $user->name : '' ?>" class="form-control text-capitalize loading-input" type="text" placeholder="Masukkan Nama Lengkap Anda">
                                </div>
                                <div class="form-group">
                                    <label>Telepon <sup>*</sup></label>
                                    <input name="shipping_phone" value="<?= ($user) ? $user->phone : '' ?>" class="form-control" type="number" placeholder="Masukkan No HP">
                                </div>
                                <div class="form-group">
                                    <label>Email Address <sup>*</sup>
                                    </label>
                                    <input name="shipping_email" value="<?= ($user) ? $user->email : '' ?>" class="form-control" type="email" placeholder="Masukkan Email Anda" <?= ($user) ? 'readonly' : '' ?>>
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang('reg_provinsi'); ?> <sup>*</sup> </label>
                                    <select name="shipping_province" class="form-control rajaongkir-province" readonly>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang('reg_kota'); ?> <sup>*</sup></label>
                                    <select name="shipping_city" class="form-control rajaongkir-city" readonly>
                                        <option value="" selected disabled>Pilih Kota / Kab</option>
                                        <?php 
                                            if ($user && $user->district && $user->province) {   
                                                if ( $districts = ddm_districts_by_province($user->province) ) {
                                                    foreach ($districts as $row) {
                                                        $selected = ( $user->district == $row->id ) ? 'selected' : '';
                                                        echo '<option value="'.$row->id.'" '.$selected.'>'. $row->district_type . ' ' . $row->district_name .'</option>';
                                                    }
                                                } else {
                                                    echo '<option value="" selected disabled>Pilih Kota / Kab</option>';
                                                }
                                            } 
                                        ?>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="form-group">
                                            <label><?php echo lang('reg_kecamatan'); ?> <sup>*</sup> </label>
                                            <select name="shipping_subdistrict" class="form-control rajaongkir-subdistrict" readonly>
                                                <option value="" selected disabled>Pilih Kecamatan</option>
                                                <?php 
                                                    if ($user && $user->subdistrict && $user->district) { 
                                                        if ( $subdistricts = ddm_subdistricts_by_district($user->district) ) {
                                                            foreach ($subdistricts as $row) {
                                                                $selected = ( $user->subdistrict == $row->id ) ? 'selected' : '';
                                                                echo '<option value="'.$row->id.'" '.$selected.'>'. $row->subdistrict_name .'</option>';
                                                            }
                                                        } else {
                                                            echo '<option value="" selected disabled>Pilih Kecamatan</option>';
                                                        }
                                                    } 
                                                ?> 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label>Kode Pos</label>
                                            <input name="shipping_postcode" value="<?= ($user) ? '' : '' ?>" type="number" class="form-control" placeholder="Kodepos">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang('reg_alamat'); ?> <sup>*</sup></label>
                                    <textarea name="shipping_address" class="form-control text-capitalize" rows="3" placeholder="Alamat Lengkap & No & RT/ RW.."><?= ($user) ? $user->address : '' ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="ps-form__courier-info">
                                <h3 class="ps-form__heading">Informasi Kurir</h3>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo lang('courier'); ?> <sup>*</sup> </label>
                                            <select name="courier" class="form-control" readonly>
                                            </select>
                                        </div>
                                    </div>
                                    <!--
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo lang('service'); ?> <sup>*</sup> </label>
                                            <select name="courier_service" class="form-control" readonly> </select>
                                        </div>
                                    </div>
                                    -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Total Berat (liter) <sup>*</sup> </label>
                                            <input name="weight" type="number" class="form-control" value="<?= sum_cart_option('product_weight') ?>" readonly>
                                        </div>
                                    </div>
                                    <!--
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo lang('shipping_fee'); ?> <sup>*</sup> </label>
                                            <input name="courier_cost" type="number" class="form-control" placeholder="0" readonly>
                                        </div>
                                    </div>
                                    -->
                                </div>
                            </div>
                            <hr>
                            <div class="ps-form__payemntmethod-info">
                                <h3 class="ps-form__heading">Pilihan Belanja</h3>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <!--
                                        <div class="form-group" id="payment_type_group" style="display:none">
                                            <label><?php echo 'Opsi Pembayaran'; ?> <sup>*</sup> </label>
                                            <select name="payment_type" class="form-control" readonly></select>
                                        </div>
                                        <div class="form-group" id="label_info_product_active" style="display:none">
                                            <label style='color: red; font-size: 12px;'><?php echo 'Informasi : Product Active tidak dapat digunakan.'; ?></label>
                                        </div>
                                        -->
                                        <div class="form-group">
                                            <!--
                                            <label><?php echo 'Pembelanjaan'; ?> <sup>*</sup> </label>
                                            -->
                                            <select name="payment_method" id="payment_method" class="form-control" readonly></select>
                                        </div>
                                        <div class="form-group" id="agent_data_group" style="display:none">
                                            <label><?php echo 'Data agent'; ?> <sup>*</sup> </label>
                                            <select name="agent_data" class="form-control" readonly></select>
                                        </div>
                                        <!--
                                        <div class="form-group" id="agent_activation_group" style="display:none">
                                            <label><?php echo 'Data Aktivasi'; ?> <sup>*</sup> </label>
                                            <select name="agent_activation" class="form-control" readonly></select>
                                        </div>
                                        <div class="form-group row mb-1" id="all_product_active_tab">
                                            <div class="col-md-12">
                                                <div class="custom-control custom-checkbox mb-3">
                                                    <input type="checkbox" class="custom-control-input" name="all_product_active" id="all_product_active" value="1" <?php echo set_checkbox( 'all_product_active', '1', true ); ?>>
                                                    <label class="custom-control-label" for="all_product_active" style="vertical-align: unset;">Semuanya menjadi produk aktif.</label>
                                                </div>
                                            </div>
                                        </div>
                                        -->
                                    </div>
                                </div>
                                
                            </div>
                            <?php $opt_reg_agent = false;  ?>
                            <?php if ( !is_logged_in() && $opt_reg_agent ) { ?>
                            <?php //if ( true ) { ?>
                                <div class="px-5 py-3 mb-5 question-reg" style="border: 1px solid #117a8b;">
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
                                    <div class="row opt-toggle-agent mt-5" id="tab-agent" style="display:none">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Kode Referral <sup>(Opsional)</sup></label>
                                                <div class="input-group">
                                                    <input type="text" name="username_sponsor" class="form-control noSpace text-lowercase" placeholder="Cari Username Referral" value="<?= $this->session->userdata('seller_ref_username') ?>">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-secondary searchUsername" data-usertype="<?= ddm_encrypt(MEMBER) ?>" type="button" style="font-size: 13px;">
                                                            <i class="fa fa-search"></i> Cari
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="username-info"></div>
                                            <div class="form-group">
                                                <label>Username <sup>*</sup></label>
                                                <input type="text" name="username_agent" id="username_agent" class="form-control noSpace text-lowercase" placeholder="<?php echo lang('reg_username_ex'); ?>" data-url="<?php echo base_url('member/checkusernamestaff'); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Password <sup>*</sup></label>
                                                <input name="password_agent" class="form-control" type="password" placeholder="Masukkan Password Anda">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Bank <sup>*</sup></label>
                                                <select name="bill_bank" class="form-control">
                                                    <option value="" disabled="" selected="">Pilih Bank</option>
                                                    <?php
                                                    if (!$banks = ddm_banks() ) {
                                                        echo '<option value="">No Bank Data</option>';
                                                    } else {
                                                        foreach ($banks as $row) {
                                                            echo '<option value="' . ddm_encrypt($row->id) . '">' . $row->nama . '</option>';
                                                        }
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Nama Rekening <sup>*</sup></label>
                                                <input name="bill_name" class="form-control text-capitalize" type="text" placeholder="Masukkan Nama Rekening">
                                            </div>
                                            <div class="form-group">
                                                <label>Nomor Rekening <sup>*</sup></label>
                                                <input name="bill_no" class="form-control" type="number" placeholder="Masukkan No Rekening">
                                            </div>
                                        </div>
                                        <?php // include APPPATH . 'views/shop/components/howto.php'; ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <hr>
                            <div class="ps-form__total">
                                <h3 class="ps-form__heading">Ringkasan Order</h3>
                                <div class="content">
                                    <div class="ps-block--checkout-total">
                                        <div class="ps-block__content">
                                            <table class="table ps-block__products mb-5">
                                                <tbody>
                                                <?php foreach ($carts['data'] as $row) : ?>
                                                        <tr class="product_checkout_id_<?= $row['product_id'] ?>">
                                                            <td class="text-capitalize product_checkout" 
                                                                data-id="<?= $row['product_id'] ?>" 
                                                                data-product="<?= $row['product_name'] ?>" 
                                                                data-qty="<?= $row['qty'] ?>"> 
                                                                <?= $row['product_name'] ?> <br>
                                                                <span class="small">Qty : </span>
                                                                <span class="small product_qty">
                                                                    <?= $row['qty'] ?> &times; <?= ddm_accounting($row['cart_price']) ?>
                                                                </span>
                                                            </td>
                                                            <td class="text-right product_subtotal"><?= ddm_accounting($row['cart_subtotal']) ?></td>
                                                        </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>

                                            <?php
                                                $promo_applied  = $this->session->userdata('promo_applied');
                                                $promo_code     = $this->session->userdata('promo_code');
                                                $promo_amount   = $this->session->userdata('promo_amount');
                                                $promo_type     = $this->session->userdata('promo_type');
                                                
                                                if($total_item >= 15 && $total_item <= 1049){
                                                    $promo_applied  = true;
                                                    $promo_code     = "20 %"; 
                                                }elseif($total_item >= 1050){
                                                    $promo_applied  = true;
                                                    $promo_code     = "40 %";
                                                }
                                            ?>

                                            <div class="ps-block--shopping-total">
                                                <div class="ps-block__header">
                                                    <p>Subtotal <span class="subtotal-cart"> <?= ddm_accounting($this->cart->total()); ?> </span></p>
                                                    <!-- <p>Ongkos Kirim <span class="courier-cost"> 0 </span></p> -->
                                                    
                                                    <p>
                                                        Diskon  
                                                        <small class="text-success promo-discount-code text-uppercase">
                                                            <?php if ( $promo_applied ) { ?>
                                                                ( <b><?= $promo_code; ?></b> )
                                                            <?php } ?>
                                                        </small>
                                                        <span class="promo-discount text-success"> <?= ( $promo_applied ) ? '- '. ddm_accounting(total_promo('discount')) : '0' ?></span>
                                                    </p>
                                                </div>
                                                <h3>Total Order <span class="total-checkout"><?= ddm_accounting(total_promo('amount')) ?> </span></h3>
                                            </div>
                                            
                                            <figure>
                                                <div class="mb-2">
                                                    Kode Voucher 
                                                </div>
                                                <div class="form-group" id="form-input-promo">
                                                    <input class="form-control text-uppercase" type="text" name="code_discount" placeholder="Masukkan kode promo voucher untuk mendapatkan diskon" value="<?= ( $promo_applied ) ? $promo_code : '' ?>">
                                                    <small class="form-text text-muted color-green delete-input-promo">
                                                        <?php if ( $promo_applied ) { ?>
                                                            Kode diskon berhasil digunakan. <a href="<?= base_url('shop/removeDiscount') ?>" style="color:red">[Hapus Diskon]</a>
                                                        <?php } ?>
                                                    </small>
                                                </div>
                                            </figure>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <a class="ps-btn green ps-btn--fullwidth text-center applyDiscount" href="javascript:;"><i class="fa fa-check" aria-hidden="true"></i> Apply Kode Promo</a>
                                            <a href="javascript:;" class="ps-btn green ps-btn--fullwidth text-center btn-back-to-cart" data-url="<?= base_url('cart') ?>"> <i class="fa fa-arrow-left" aria-hidden="true"></i> Enter Kode Promo</a>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" id="confirmCheckoutAgent" class="ps-btn ps-btn--fullwidth" href="javascript:;"> <i class="fa fa-check" aria-hidden="true"></i> Konfirmasi Checkout</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php if ($this->cart->contents()) { ?>
    <!-- Modal -->
    <div class="modal fade" id="modal-checkout" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Konfirmasi Checkout</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" style="padding: 20px 15px;">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Produk</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($carts['data'] as $row) { ?>
                                    <tr class="product_checkout_id_<?= $row['product_id'] ?>">
                                        <td class="text-capitalize">
                                            <?= $row['product_name'] ?>
                                        </td>
                                        <td class="product_qty"><?= $row['qty'] ?></td>
                                        <td class="text-right product_subtotal"><?= ddm_accounting($row['cart_subtotal'],'Rp') ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="2">Diskon</td>
                                    <td class="text-right cart-subtotal"><?= ddm_accounting(total_promo('discount'),'Rp') ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Subtotal</td>
                                    <td class="text-right cart-subtotal"><?= ddm_accounting(total_promo('amount'),'Rp') ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><strong>Total Pembayaran</strong></td>
                                    <td class="text-right total-checkout" style="font-weight: bold;"></td>
                                </tr>
                            </tbody>
                        </table>
                        <!--
                        <hr style="margin: 15px 0;">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Subtotal</th>
                                    <th scope="col">Total Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="cart-subtotal"><?= ddm_accounting($this->cart->total(),'Rp') ?></td>
                                    <td class="total-checkout"></td>
                                </tr>
                            </tbody>
                        </table>
                        -->
                    </div>
                </div>
                <div class="modal-footer" style="justify-content: space-between;">
                    <button type="button" class="btn btn btn-danger pull-left py-3 px-4" data-dismiss="modal" style="line-height: 17px;font-size:13px">Tutup</button>
                    <button id="saveOrder" type="button" class="btn btn-success pull-right py-3 px-4" style="line-height: 17px;font-size:13px">Lanjutkan</button>
                </div>
            </div>

        </div>
    </div>
<?php } ?>