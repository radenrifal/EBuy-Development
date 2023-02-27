<?php
include APPPATH . 'views/shop/components/header.php';
include APPPATH . 'views/shop/components/mobile/nav_search.php';
?>

<div class="ps-breadcrumb">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?= base_url() ?>">Home</a></li>
            <li>Check Order</li>
        </ul>
    </div>
</div>

<div class="ps-order-tracking">
    <div class="container">
        <div class="ps-section__header">
            <h3><i class="fa fa-search"></i> Track Order</h3>
            <p>
                To track your order please enter your Invoice and Email in the box below.
            </p>
        </div>
        <div class="ps-section__content">
            <form class="ps-form--order-tracking mb-3" action="" method="get">
                <div class="row">
                    <div class="form-group col-md-4">
                        <input name="invoice" type="text" class="form-control" placeholder="Nomor Invoice" value="<?= ($get_invoice) ? $get_invoice : '' ?>" required>
                    </div>
                    <div class="form-group col-md-4">
                        <input name="email" type="email" class="form-control" placeholder="Masukkan Email Anda" value="<?= ($get_email) ? $get_email : '' ?>" required>
                    </div>
                    <div class="form-group col-md-4"><button class="ps-btn ps-btn--fullwidth" style="height: 40px;padding: 0;">Track Order</button></div>
                </div>
            </form>

            <?php
            if ($get_invoice & $get_email) {
                if ($id_order) {
                    $condition  = array('type' => 'shop');
                    if ( $order_by == 'agent' ) {
                        $order = $this->Model_Shop->get_shop_order_by ('id', $id_order);
                        $paymentEvidence = $this->Model_Shop->get_payment_evidence_by('id_source', $id_order, $condition, 1);
                    } else {
                        $order = $this->Model_Shop->get_shop_order_customer_by ('id', $id_order);
                        $paymentEvidence = false;
                    }

                    $status_sent = false;
                    if ( $order->status == 1 && $order->datesent && $order->datesent != '0000-00-00 00:00:00' ) {
                        $status_sent = true;
                    }
            ?>

                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div id="tracking">
                                <div class="text-center tracking-status-intransit text-tight">
                                    <div class="row">
                                        <div class="col-md-6 text-left px-5 mb-3">
                                            <b>ALAMAT</b> <br>
                                            <span class="text-capitalize">
                                                <?= $order->address ?> <br>
                                                <?= $order->province . ', ' . $order->city . ', ' . $order->postcode ?>
                                            </span>
                                        </div>
                                        <div class="col-md-6 text-right px-5">
                                            <b>PENGIRIMAN</b> <br>
                                            <span class="text-uppercase"><?= $order->courier . ' - ' . $order->service; ?> </span> <br>
                                        </div>
                                    </div>
                                    <p class="tracking-status text-tight mt-4"><?= status_order($order->id, $order->status, $order_by, $status_sent) ?></p>

                                </div>
                                <div class="tracking-list">
                                    <div class="tracking-item">
                                        <div class="tracking-icon status-inforeceived">
                                            <i class="fa fa-shopping-cart"></i>
                                        </div>
                                        <div class="tracking-date"><?= date_indo($order->datecreated, 'dateonly') ?>
                                            <span><?= date_create($order->datecreated)->format('h:i A'); ?></span>
                                        </div>
                                        <div class="tracking-content text-uppercase">Pesanan Dibuat
                                            <span>Pesanan anda berhasil dibuat</span>
                                        </div>
                                    </div>
                                    <?php if ($order->status == 2) { ?>
                                        <div class="tracking-item">
                                            <div class="tracking-icon status-failed">
                                                <i class="fa fa-times"></i>
                                            </div>
                                            <div class="tracking-date"><?= date_indo($order->datemodified, 'dateonly') ?>
                                                <span><?= date_create($order->datemodified)->format('h:i A') ?></span>
                                            </div>
                                            <div class="tracking-content text-uppercase">Pesanan Dibatalkan
                                                <span>Pesanan anda dibatalkan. Silahkan hub <?= ( $order_by == 'agent' ) ? 'Admin' : 'Agen'; ?></span>
                                            </div>
                                        </div>
                                    <?php exit;
                                    }
                                    if ($order->status === 0) { ?>
                                        <div class="tracking-item">
                                            <div class="tracking-icon status-intransit">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <div class="tracking-date"><?= date_indo($order->datecreated, 'dateonly') ?>
                                                <span><?= date_create($order->datecreated)->format('h:i A') ?></span>
                                            </div>
                                            <div class="tracking-content text-uppercase">Menunggu Pembayaran
                                                <span>silahkan lakukan pembayaran</span>
                                            </div>
                                        </div>
                                    <?php }
                                    if ($order->status >= 1 || $paymentEvidence) { ?>
                                        <div class="tracking-item">
                                            <div class="tracking-icon status-inforeceived">
                                                <i class="fa fa-money"></i>
                                            </div>
                                            <div class="tracking-date"><?= date_indo($order->dateconfirm, 'dateonly') ?>
                                                <span><?= date_create($order->dateconfirm)->format('h:i A') ?></span>
                                            </div>
                                            <div class="tracking-content text-uppercase">Pembayaran dikonfirmasi
                                                <span>pembayaran di konfirmasi oleh <b><?= ($order->status == 0 && $paymentEvidence) ? 'pembeli' : ( $order_by == 'agent' ? 'Admin' : 'Agen') ?></b></span>
                                            </div>
                                        </div>
                                    <?php }
                                    if ( $order->status == 1 && $status_sent ) { ?>
                                        <div class="tracking-item">
                                            <div class="tracking-icon status-outfordelivery">
                                                <i class="fa fa-truck"></i>
                                            </div>
                                            <div class="tracking-date"><?= date_indo($order->datesent, 'dateonly') ?>
                                                <span><?= date_create($order->datesent)->format('h:i A') ?></span>
                                            </div>
                                            <div class="tracking-content text-uppercase">Pemesanan dikirim
                                                <span>pemesanan anda dikirim dengan no resi : <b><?= $order->resi ?></b></span>
                                            </div>
                                        </div>
                                    <?php }
                                    if ($order->status == 5) { ?>
                                        <div class="tracking-item">
                                            <div class="tracking-icon status-delivered">
                                                <i class="fa fa-home"></i>
                                            </div>
                                            <div class="tracking-date"><?= date_indo($order->datedelivered, 'dateonly') ?>
                                                <span><?= date_create($order->datedelivered)->format('h:i A') ?></span>
                                            </div>
                                            <div class="tracking-content text-uppercase">Pemesanan selesai
                                                <span>pemesan anda telah selesai, terima kasih</span>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } else { ?>
                    <center>
                        Invoice tidak ditemukan
                    </center>
                <?php } ?>
            <?php } ?>


        </div>
    </div>
</div>

<br><br>