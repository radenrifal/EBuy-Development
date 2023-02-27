<?php 
    include APPPATH . 'views/shop/components/header.php'; 
    include APPPATH . 'views/shop/components/mobile/nav_back.php'; 
    $currency = config_item('currency');
    $confirm_payment = false;
?>

<div class="ps-page--simple">
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?= base_url() ?>">Home</a></li>
                <li>Confirm Payment</li>
            </ul>
        </div>
    </div>
    <div class="ps-checkout ps-section--shopping">
        <div class="container">
            <div class="ps-section__header">
                <h2>Form Confirm Payment</h2>
            </div>
            <div class="ps-section__content">
                <form class="ps-form--checkout" data-rdr="reload">
                    <div class="row">

                        <div class="col-md-6 col-sm-12 ">
                            <div class="ps-form__total">
                                <h3 class="ps-form__heading">Ringkasan Order #<?= $order->invoice ?></h3>
                                <div class="content">
                                    <div class="ps-block--checkout-total">
                                        <div class="ps-block__content">
                                            <table class="table ps-block__products">
                                                <tbody>

                                                    <?php
                                                    $unserialize_data = unserialize($order->products);
                                                    $uniquecode  = str_pad($order->unique, 3, '0', STR_PAD_LEFT);
                                                    foreach ($unserialize_data as $row) {
                                                        $price          = $row['price'];
                                                        $price_ori      = $row['price_ori'];
                                                        $subtotal       = $row['qty'] * $price;

                                                        if ( $price_ori > $price ) {
                                                            $price_qty  = 'Qty : '. $row['qty'] .' (<s>'. ddm_accounting($price_ori) .'</s> '. ddm_accounting($price) .')';
                                                        } else {
                                                            $price_qty  = 'Qty : '. $row['qty'] .' ('. ddm_accounting($price) .')';
                                                        }
                                                    ?>

                                                        <tr>
                                                            <td class="text-capitalize">
                                                                <?= data_product($row['id'], 'name') ?> <br>
                                                                <span class="small"><?= $price_qty ?></span>
                                                            </td>
                                                            <td class="text-right"><?= ddm_accounting($subtotal) ?></td>
                                                        </tr>

                                                    <?php } ?>

                                                </tbody>
                                            </table>

                                            <div class="ps-block--shopping-total mb-5">
                                                <div class="ps-block__header">
                                                    <p>Kode Diskon <span> <?= ($order->voucher) ? $order->voucher : '-' ?></span></p>
                                                    <p>Diskon <span class="promo-discount"> <?= ddm_accounting($order->discount) ?></span> </p>
                                                    <p>Subtotal <span class="subtotal-cart"> <?= ddm_accounting($order->subtotal) ?> </span></p>
                                                    <p>Ongkos Kirim <span class="courier-cost"> <?= ddm_accounting($order->shipping) ?> </span></p>
                                                </div>
                                                <h3>Total Order <span class="total-checkout"><?= ddm_accounting($order->total_payment) ?> </span></h3>
                                            </div>

                                            <h3 class="ps-form__heading">Alamat Pengiriman</h3>
                                            <div class="ps-block--shopping-total mb-5" style="margin-bottom: unset;padding: 10px 30px;">
                                                <div class="ps-block__header" style="border-bottom:unset;margin-bottom: unset;">
                                                    <p>Nama <span class="text-capitalize"> <?= $order->name ?></span></p>
                                                    <p>No Telp <span class="promo-discount"> <?= $order->phone ?></span> </p>
                                                    <p>Email <span class="promo-discount"> <?= $order->email ?></span> </p>
                                                    <p>Alamat <span class="text-capitalize"> <?= $order->address ?></span> </p>
                                                    <p>Kota/Kab <span> <?= $order->city ?></span> </p>
                                                    <p>Provinsi <span> <?= $order->province ?></span> </p>
                                                    <!-- <p style="margin-bottom: unset;">Kodepos <span> <?= $order->postcode ?></span> </p> -->
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12 ">
                            <div class="ps-form__billing-info">

                                <h3 class="ps-form__heading">Informasi Agen</h3>
                                <div class="ps-block--shopping-total mb-5" style="margin-bottom: unset;padding: 10px 30px;">
                                    <div class="ps-block__header" style="border-bottom:unset;margin-bottom: unset;">
                                        <p>Nama <span> <?= $agent->name; ?></span></p>
                                        <p>No. Telp <span class="promo-discount"> <?= $agent->phone; ?></span> </p>
                                        <p>Email <span class="promo-discount"> <?= $agent->email; ?></span> </p>
                                    </div>
                                </div>

                                <?php
                                // $payment_evidence = $this->db->get_where(TBL_PAYMENT_EVIDENCE, array('id_shop_order' => $order->id))->row();
                                $payment_evidence = '';

                                // Check expired
                                $currdate   = date('Y-m-d H:i:s');
                                $timediff   = strtotime($currdate) - strtotime($order->datecreated);

                                if ($order->status == 0 && $timediff > 86400 && !$payment_evidence) { ?>
                                    <div class="shop-info danger">
                                        <span>Pembayaran dibatalkan. Link expired! Silahkan hub Admin</span>
                                    </div>
                                <?php } else if ($order->status > 2) { ?>
                                    <div class="shop-info primary">
                                        <span>Pembayaran sudah dilakukan.</span>
                                    </div>
                                <?php } else if ($order->status == 1) { ?>
                                    <div class="shop-info primary">
                                        <span>Pembayaran sudah di konfirmasi</span>
                                    </div>
                                <?php } else if ($order->status == 2) { ?>
                                    <div class="shop-info danger">
                                        <span>Pembayaran dibatalkan!</span>
                                    </div>
                                <?php } else if ($order->status == 0 && $payment_evidence) { ?>
                                    <div class="shop-info primary">
                                        <span>Pembayaran anda sedang kami review.</span>
                                    </div>
                                <?php } else if ($order->status == 0 || !$payment_evidence) { ?>

                                    <?php if ( $confirm_payment ) { ?>
                                        <h3 class="ps-form__heading">Rekening Pembeli</h3>
                                        <input type="hidden" name="id_order" value=<?= ddm_encrypt($order->id) ?>>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Bank <sup>*</sup></label>
                                                    <select name="bill_bank" class="form-control">
                                                        <option value="" disabled="" selected="">Pilih Bank</option>
                                                        <?php
                                                        if ( !$banks = ddm_banks() ) {
                                                            echo '<option value="">No Bank Data</option>';
                                                        } else {
                                                            foreach ($banks as $row) {
                                                                echo '<option value="' . ddm_encrypt($row->nama) . '">' . $row->nama . '</option>';
                                                            }
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>No Rekening <sup>*</sup>
                                                    </label>
                                                    <div class="form-group__content">
                                                        <input name="bill_no" class="form-control" type="number" placeholder="Nomor Rekening Anda">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Nama Rekening <sup>*</sup>
                                            </label>
                                            <div class="form-group__content">
                                                <input name="bill_name" class="form-control text-capitalize" type="text" placeholder="Nama Rekening Anda">
                                            </div>
                                        </div>
                                    <?php } ?>
                                    
                                    <div class="shop-info primary">
                                        <span>Sebelum melakukan pembayaran sebesar <b><?= ddm_accounting($order->total_payment, $currency) ?></b>, pastikan anda telah menghubungi Agen terlebih dahulu untuk proses lebih lanjut terhadap pesanan anda.</span>
                                    </div>

                                    <?php if ( $confirm_payment ) { ?>
                                        <a id="saveConfirmPaymentCustomer" class="ps-btn ps-btn--fullwidth" href="javascript:;">Confirm Payment Now</a>
                                    <?php } ?>

                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<br>