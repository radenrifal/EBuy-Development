<?php include APPPATH . 'views/shop/components/header.php'; ?>
<?php include APPPATH . 'views/shop/components/mobile/nav_back.php'; ?>

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
            <div class="ps-shopping__header">

                <?php if ( $user ) { ?>
                    <p class="ref-text"><i class="fa fa-user"></i> Kode Agen Anda : <strong> <?= $user->username ?> </strong></p>
                <?php } else if ( $agent ) { ?>
                    <p class="ref-text" style="min-width: unset !important;"><i class="fa fa-user"></i> Kode Agen Dipakai : <b><?= $agent->username; ?></b></p>
                <?php } else { ?>
                    <p><i class="fa fa-user"></i> Tidak ada Referral</p>
                <?php } ?>
            </div>

            <div class="ps-section__content">
                <form class="ps-form--checkout" id="form-checkout" data-rdr="link">
                    <input type="hidden" name="id" value="<?= ($user) ? ddm_encrypt($user->id) : '' ?>">
                    <input type="hidden" name="id_agent" value="<?= ( isset($agent->id) ) ? ddm_encrypt($agent->id) : '' ?>">
                    <input type="hidden" name="id_customer">

                    <?php $opt_reg_agent = false;  ?>
                    <?php if ( !is_logged_in() && $opt_reg_agent ) { ?>
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

                    <div class="row">
                        <div class="col-md-6 col-sm-12 ">
                            <div class="ps-form__billing-info">
                                <h3 class="ps-form__heading">Shipping Details</h3>

                                <div class="form-group">
                                    <label>Province<sup>*</sup> </label>
                                    <select name="shipping_province" class="form-control rajaongkir-province" readonly>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>City/District <sup>*</sup></label>
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
                                <div class="form-group">
                                    <label>Sub-Disctrict <sup>*</sup> </label>
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
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Courier <sup>*</sup> </label>
                                            <select name="courier" class="form-control" readonly>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Service <sup>*</sup> </label>
                                            <select name="courier_service" class="form-control" readonly> </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Total Berat (gr) <sup>*</sup> </label>
                                            <input name="weight" type="number" class="form-control" value="<?= sum_cart_option('product_weight') ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Ongkir <sup>*</sup> </label>
                                            <input name="courier_cost" type="number" class="form-control" placeholder="0" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Postcode</label>
                                    <input name="shipping_postcode" value="<?= ($user) ? '' : '' ?>" type="number" class="form-control" placeholder="Kodepos">
                                </div>
                                <div class="form-group">
                                    <label>Address <sup>*</sup></label>
                                    <textarea name="shipping_address" class="form-control text-capitalize" rows="2" placeholder="Alamat Lengkap & No & RT/ RW.."><?= ($user) ? $user->address : '' ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="ps-form__billing-info">
                                <h3 class="ps-form__heading">Billing Details</h3>
                                <div class="form-group">
                                    <label>Nama <sup>*</sup>
                                    </label>
                                    <input name="shipping_name" value="<?= ($user) ? $user->name : '' ?>" class="form-control text-capitalize loading-input" type="text" placeholder="Masukkan Nama Lengkap Anda">
                                </div>
                                <div class="form-group">
                                    <label>Email Address <sup>*</sup>
                                    </label>
                                    <input name="shipping_email" value="<?= ($user) ? $user->email : '' ?>" class="form-control" type="email" placeholder="Masukkan Email Anda" <?= ($user) ? 'readonly' : '' ?>>
                                </div>
                                <div class="form-group">
                                    <label>Telepon <sup>*</sup></label>
                                    <input name="shipping_phone" value="<?= ($user) ? $user->phone : '' ?>" class="form-control <?= ($user) ? '' : 'searchPhone' ?>" type="number" placeholder="Masukkan No HP">
                                    <?php if ( !$member ) { ?>
                                        <div class="alert alert-warning">
                                            <small class="form-text text-muted">
                                                Silahkan masukkan No HP anda yang telah disimpan sebelumnya maka data anda otomatis terisi. Untuk mengembalikan informasi ke data anda yang sebelumnya silahkan <a href="javascript:;" id="loadPhoneNumber"><b>Klik Refresh</b></a> halaman ini.
                                            </small>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="ps-form__total">
                                <h3 class="ps-form__heading">Ringkasan Order</h3>
                                <div class="content">
                                    <div class="ps-block--checkout-total">
                                        <div class="ps-block__content">
                                            <table class="table ps-block__products">
                                                <tbody>
                                                    <?php foreach ($carts['data'] as $row) { ?>
                                                        <tr class="product_checkout_id_<?= $row['product_id'] ?>">
                                                            <td class="text-capitalize product_checkout" 
                                                                data-id="<?= $row['product_id'] ?>" 
                                                                data-product="<?= $row['product_name'] ?>" 
                                                                data-minorder="<?= $row['min_order_agent'] ?>" 
                                                                data-qty="<?= $row['qty'] ?>"> 
                                                                <?= $row['product_name'] ?> <br>
                                                                <span class="small">Qty : </span>
                                                                <span class="small product_qty"><?= $row['qty'] ?></span>
                                                            </td>
                                                            <td class="text-right product_subtotal"><?= ddm_accounting($row['cart_price'] * $row['qty']) ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <div class="ps-block--shopping-total">
                                                <div class="ps-block__header">
                                                    <p>Subtotal <span class="subtotal-cart"> <?= ddm_accounting($this->cart->total()); ?> </span></p>
                                                    <?php if ( ! $member ) { ?>
                                                        <?php 
                                                            $register_fee = get_option('register_fee'); 
                                                            $register_fee = $register_fee ? $register_fee : 0; 
                                                        ?>
                                                        <p class="register-fee" data-regfee="<?= $register_fee ?>" style="display: none">
                                                            Biaya Pendaftaran Agen <span><?= ddm_accounting($register_fee) ?></span>
                                                        </p>
                                                    <?php } ?>
                                                    <p>Ongkos Kirim <span class="courier-cost"> 0 </span></p>
                                                    <p>
                                                        Diskon  
                                                        <?php if (($this->session->userdata('promo_applied'))) { ?>
                                                            <small class="text-success">( <b><?= $this->session->userdata('promo_code')  ?></b> )</small>
                                                        <?php } ?>
                                                        <span class="promo-discount text-success"> <?= ($this->session->userdata('promo_applied')) ? '- '. ddm_accounting(total_promo('discount')) : '0' ?></span>
                                                    </p>
                                                </div>
                                                <h3>Total Order <span class="total-checkout"><?= ddm_accounting(total_promo('amount')) ?> </span></h3>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <?php if ( ! $member ) { ?>
                                            <div class="col-12 question-save-consumer">
                                                <div class="px-5 py-3 mb-4 " style="border: 1px solid #cacaca;">
                                                    <div class="form-group mb-0 mt-2">
                                                        <div class="row">
                                                            <div class="col-md-9 mb-3">
                                                                Apakah Anda ingin menyimpan data informasi alamat ini ?
                                                            </div>
                                                            <div class="col-md-3 col-sm-12">
                                                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                                    <label class="btn btn-info px-4 py-2">
                                                                        <input value="1" type="radio" name="options_save_customer" autocomplete="off"> Ya
                                                                    </label>
                                                                    <label class="btn btn-info px-3 py-2 active">
                                                                        <input value="0" type="radio" name="options_save_customer" autocomplete="off" checked=""> Tidak
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row opt-toggle" id="tab-1" style="display:none">
                                                        <div class="col-md-12 mt-3">
                                                            <div class="alert alert-success fade show">
                                                                <strong><i class="fa fa-bell"></i></strong>&nbsp;
                                                                Data informasi alamat ini akan tersimpan.<br>
                                                                Selanjutnya cukup masukkan No HP anda untuk mengambil data alamat, dll. Dan data form akan secara otomatis terisi.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-md-6 mb-4">
                                            <a href="javascript:;" class="ps-btn green ps-btn--fullwidth text-center btn-back-to-cart" data-url="<?= base_url('cart') ?>"> <i class="fa fa-arrow-left" aria-hidden="true"></i> Enter Kode Promo</a>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="submit" id="confirmCheckout" class="ps-btn ps-btn--fullwidth" href="javascript:;"> <i class="fa fa-check" aria-hidden="true"></i> Konfirmasi Checkout</button>
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
                                        <td class="product_subtotal"><?= ddm_accounting($row['cart_price'] * $row['qty']) ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <hr style="margin: 15px 0;">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Subtotal</th>
                                    <th scope="col">Ongkir</th>
                                    <th scope="col">Diskon</th>
                                    <th scope="col">Total Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="cart-subtotal"><?= ddm_accounting($this->cart->total(), 'Rp') ?></td>
                                    <td class="courier-cost"></td>
                                    <td><?= ($this->session->userdata('promo_applied')) ? ddm_accounting(total_promo('discount'), 'Rp') : '-' ?></td>
                                    <td class="total-checkout"></td>
                                </tr>
                            </tbody>
                        </table>
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