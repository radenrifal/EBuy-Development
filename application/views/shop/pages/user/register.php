<?php 
    include APPPATH . 'views/shop/components/header.php'; 
    include APPPATH . 'views/shop/components/mobile/nav_back.php';

    $currency       = config_item('currency');
    //$cfg_order      = config_item('order_type');
    //$cfg_order      = isset($cfg_order['perdana']) ? $cfg_order['perdana'] : false;
    
    $cfg_order      = '';
    if( $packages = $this->Model_Product->get_product_package() ){
        $cfg_order  = $packages;
    }
    
    $register_fee   = get_option('register_fee'); 
    $register_fee   = $register_fee ? $register_fee : 0; 
?>

<div class="ps-page--simple">
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?= base_url() ?>">Home</a></li>
                <li>Register</li>
            </ul>
        </div>
    </div>
    <div class="ps-checkout ps-shopping ps-section--shopping section-shadow">
        <div class="container">
            <div class="ps-section__header">
                <h2>Form Pendaftaran Agen</h2>
            </div>

            <div class="ps-section__content">
                <form class="ps-form--checkout" id="form-register-agent" data-rdr="link" data-regfee="<?= $register_fee ?>">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 ">
                            <div class="ps-form__account-info">
                                <h3 class="ps-form__heading">Informasi Akun</h3>
                                <div class="form-group mb-2">
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
                                <hr class="mb-4">
                                <div class="form-group mb-4">
                                    <label>Username <sup>*</sup></label>
                                    <input type="text" name="username_agent" id="username_agent" class="form-control noSpace text-lowercase text-lowercase" placeholder="<?php echo lang('reg_username_ex'); ?>" data-url="<?php echo base_url('member/checkusernamestaff'); ?>">
                                </div>
                                <div class="form-group mb-4">
                                    <label>Password <sup>*</sup></label>
                                    <div class="input-group">
                                        <input type="password" name="password_agent" class="form-control" placeholder="Masukkan Password Anda">
                                        <div class="input-group-append">
                                            <button class="btn bg-white px-4 pass-show-hide" type="button" style="font-size: 14px;border: 1px solid #ddd;border-left: none;">
                                                <i class="fa fa-eye-slash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label>Nama <sup>*</sup></label>
                                    <input type="text" name="shipping_name" class="form-control text-capitalize" placeholder="Masukkan Nama Lengkap Anda">
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group mb-4">
                                            <label>No. HP/WA <sup>*</sup></label>
                                            <div class="input-group">
                                                <div class="input-group-append"><span class="input-group-text bg-white" style="font-size: 14px;">+62</span></div>
                                                <input type="number" name="shipping_phone" class="form-control phonenumber pl-3" placeholder="Masukkan No HP atau WA">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group mb-4">
                                            <label>Email Address <sup>*</sup></label>
                                            <input type="email" name="shipping_email" class="form-control text-lowercase loading-input" placeholder="Masukkan Email Anda">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="mb-3">
                            <div class="ps-form__address-info">
                                <h3 class="ps-form__heading">Informasi Alamat</h3>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group mb-4">
                                            <label><?php echo lang('reg_provinsi'); ?><sup>*</sup> </label>
                                            <select name="shipping_province" class="form-control rajaongkir-province" readonly>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group mb-4">
                                            <label><?php echo lang('reg_kota'); ?> <sup>*</sup></label>
                                            <select name="shipping_city" class="form-control rajaongkir-city" readonly>
                                                <option value="" selected disabled>Pilih Kota / Kab</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group mb-4">
                                            <label><?php echo lang('reg_kecamatan'); ?> <sup>*</sup> </label>
                                            <select name="shipping_subdistrict" class="form-control rajaongkir-subdistrict" readonly>
                                                <option value="" selected disabled>Pilih Kecamatan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group mb-4">
                                            <label>Kode POS</label>
                                            <input type="number" name="shipping_postcode" class="form-control" placeholder="Kode Pos">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label>Address <sup>*</sup></label>
                                    <textarea name="shipping_address" class="form-control text-capitalize" rows="2" placeholder="Alamat Lengkap & No & RT/ RW.."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="ps-form__package-product-info">
                                <h3 class="ps-form__heading">Ringkasan Order</h3>
                                <div class="form-group mb-4">
                                    <label>Paket Produk <sup>*</sup> </label>
                                    <select name="package_product" class="form-control package-product totalPackQtyOrder">
                                        <?php 
                                            if ( $cfg_order ) { 
                                                foreach ($cfg_order as $key => $row) {
                                                    echo '<option value="'.ddm_encrypt($row->id).'">'.$row->name.'</option>';
                                                } 
                                            } else {
                                                echo '<option value="" selected disabled>Pilih Paket Produk</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                                
                                <!--
                                <div class="content">
                                    <div class="ps-block__content">
                                        <table class="table ps-block__products mb-5">
                                            <tbody>
                                            <?php 
                                                if ( $packages ) { 
                                                    $num = 1;
                                                    foreach ($packages as $row) {
                                                        $id_encrypt = ddm_encrypt($row->id);
                                                        if (  $row->image ) {
                                                            $img_src = product_image($row->image);
                                                        } else {
                                                            $img_src = ASSET_PATH . 'backend/img/no_image.jpg'; 
                                                        }
                                                        $detail         = '';
                                                        $productDetail  = $row->product_details;
                                                        $productDetail  = ($productDetail) ? maybe_unserialize($productDetail) : false; 
                                                        if ( $productDetail ) {
                                                            $countDetail = count($productDetail);
                                                            $detail .= ($countDetail > 1) ? '<ul>' : '';
                                                            foreach ($productDetail as $det) {
                                                                $detail .= ($countDetail > 1) ? '<li class="font-weight-normal">' : '';
                                                                $detail .= ''. $det['qty'] .' produk '. $det['name'];
                                                                $detail .= ($countDetail > 1) ? '</li>' : '';
                                                            }
                                                            $detail .= ($countDetail > 1) ? '</ul>' : '';
                                                        }
                                                    ?>
                                                    <tr class="product_checkout_<?= $num ?>">
                                                        <td class="product_checkout py-4" 
                                                            style="border: 1px solid #dddddd; border-right: none;"> 
                                                            <span class="text-capitalize d-block mb-2"><?= $row->name ?></span>
                                                            <span class="small d-block" style="min-height: 50px">
                                                                Paket ini berisi <?= $detail ?> <br>
                                                                Berat: <?= ddm_accounting($row->weight); ?>gr
                                                            </span>
                                                            <span class="product_price"><?= ddm_accounting($row->price, $currency) ?></span>
                                                        </td>
                                                        <td class="text-right product-quantity py-4" style="border: 1px solid #dddddd; border-left: none;">
                                                            <div class="product-thumbnail mb-3" style="padding: 0px 15px">
                                                                <img class="img-fluid" src="<?= $img_src; ?>" alt="product-img" style="max-width: 70px;"/>
                                                            </div>
                                                            <div class="form-group--number"><!--
                                                                <input type="hidden" name="products[<?= ($id_encrypt) ?>][id]" class="d-none" value="<?= ($id_encrypt) ?>">
                                                                <button type="button" class="up" onclick="qtyPackPlus(this, 1)"> + </button>
                                                                <button type="button" class="down" onclick="qtyPackMin(this, 1)"> - </button>
                                                                <input class="form-control numberQtyPack" type="number" 
                                                                    data-rowid="<?= ($id_encrypt) ?>" 
                                                                    data-num="<?= ($num) ?>" 
                                                                    data-price="<?= ($row->price) ?>" 
                                                                    data-weight="<?= ($row->weight) ?>" 
                                                                    value="<?= 1 ?>" 
                                                                    step="<?= 1 ?>" 
                                                                    name="products[<?= ($id_encrypt) ?>][qty]"
                                                                    title="Qty" pattern="[0-9]*" inputmode="numeric" readonly="readonly" 
                                                                    style="background-color: transparent !important;" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php $num++;
                                                    } 
                                                } 
                                            ?>
                                            </tbody>
                                        </table>
                                        <div class="ps-form__address-info">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group mb-3">
                                                        <label>Courier <sup>*</sup> </label>
                                                        <select name="courier" class="form-control courier" readonly>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group mb-3">
                                                        <label>Service <sup>*</sup> </label>
                                                        <select name="courier_service" class="form-control courier_service" readonly> </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ps-block--shopping-total"><!--
                                            <div class="ps-block__header">
                                                <p>Subtotal <span class="subtotal-cart"> <?= ddm_accounting(0); ?> </span></p>
                                                <p class="register-fee" data-regfee="<?= $register_fee ?>">
                                                    Biaya Pendaftaran <span class="regfee-cart"><?= ddm_accounting($register_fee) ?></span>
                                                </p>
                                                <p>Ongkos Kirim <small class="total-weight">( 0 gr)</small> <span class="courier-cost"> 0</span></p>
                                                <p>
                                                    Diskon  
                                                    <small class="text-success voucher-code"></small>
                                                    <span class="promo-discount text-success"></span>
                                                </p>
                                            </div>
                                            <h3>Total Pembayaran <span class="total-payment"><?= ddm_accounting(0) ?> </span></h3>
                                        </div>
                                        <figure>
                                            <div class="mb-2">
                                                Kode Voucher 
                                            </div>
                                            <div class="form-group" id="form-input-promo">
                                                <input class="form-control text-uppercase" type="text" name="code_discount" placeholder="Masukkan kode promo voucher untuk mendapatkan diskon" value="">
                                                <small class="form-text text-muted color-green delete-input-promo"></small>
                                            </div>
                                        </figure>
                                    </div>
                                </div>
                                -->
                            </div>
                            <div class="row">
                                <!--
                                <div class="col-md-6 mb-4">
                                    <a class="ps-btn green ps-btn--fullwidth text-center applyDiscount" href="javascript:;"><!--Apply Kode Voucher</a>
                                </div>
                                -->
                                <div class="col-md-6">
                                    <button type="submit" id="confirmRegister" class="ps-btn ps-btn--fullwidth" href="javascript:;"> <i class="fa fa-check" aria-hidden="true"></i> Konfirmasi Checkout</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modal-register-confirm" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Konfirmasi Checkout</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" style="padding: 20px 15px;">
                Apakah Data Registrasi Agen sudah benar?
                <!--
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Produk</th>
                                <th scope="col" class="text-center">Qty</th>
                                <th scope="col" class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ( $packages ) { 
                                $num = 1;
                                foreach ($packages as $row) { ?>
                                <tr class="product_checkout_id_<?= $num; ?>">
                                    <td class="text-capitalize">
                                        <?= $row->name ?>
                                    </td>
                                    <td class="text-center product_qty_<?= $num; ?>">0</td>
                                    <td class="text-right product_subtotal_<?= $num; ?>">0</td>
                                </tr>
                            <?php $num++; } } ?>
                        </tbody>
                    </table>
                    <hr style="margin: 15px 0;">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" class="text-right">Subtotal</th>
                                <th scope="col" class="text-right">Ongkir</th>
                                <th scope="col" class="text-center">Diskon</th>
                                <th scope="col" class="text-right">Total Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-right subtotal-cart">0</td>
                                <td class="text-right courier-cost"></td>
                                <td class="text-center">-</td>
                                <td class="text-right total-payment"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                -->
            </div>
            <div class="modal-footer" style="justify-content: space-between;">
                <button type="button" class="btn btn btn-danger pull-left py-3 px-4" data-dismiss="modal" style="line-height: 17px;font-size:13px">Tutup</button>
                <button id="saveRegister" type="button" class="btn btn-success pull-right py-3 px-4" style="line-height: 17px;font-size:13px">Lanjutkan</button>
            </div>
        </div>

    </div>
</div>