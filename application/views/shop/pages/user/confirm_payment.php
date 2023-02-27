<?php include APPPATH . 'views/shop/components/header.php'; ?>
<?php include APPPATH . 'views/shop/components/mobile/nav_back.php'; ?>

<div class="ps-page--simple">
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?= base_url() ?>">Home</a></li>
                <li class="text-capitalize">Confirm Payment <?= $type ?> Seller</li>
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
                            <div class="ps-form__billing-info">
                                <h3 class="ps-form__heading">Data Akun</h3>
                                <div class="ps-block--shopping-total mb-5" style="margin-bottom: unset;padding: 10px 30px;">
                                    <div class="ps-block__header" style="border-bottom:unset;margin-bottom: unset;">
                                        <p>Username <span class="text-uppercase"> <?= $user->username ?></span></p>
                                        <p>Nama <span class="text-capitalize"> <?= $user->name ?></span></p>
                                        <p>No Telp <span><?= $user->phone ?></span> </p>
                                        <p>Kota/Kab <span> <?= $user->city ?></span> </p>
                                        <p>Provinsi <span> <?= $user->province ?></span> </p>
                                    </div>
                                </div>
                                <h3 class="ps-form__heading">Rekening Perusahaan</h3>
                                <div class="ps-block--shopping-total mb-5" style="margin-bottom: unset;padding: 10px 30px;">
                                    <div class="ps-block__header" style="border-bottom:unset;margin-bottom: unset;">
                                        <p>Nama <span> <?= config_item('billing')['bill_name'] ?></span></p>
                                        <p>Bank <span> <?= config_item('billing')['bill_bank'] ?></span> </p>
                                        <p>No Rekening <span> <?= config_item('billing')['bill_no'] ?></span> </p>
                                        <p style="margin-bottom: unset;">Cabang <span> <?= config_item('billing')['bill_branch']  ?> </span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12 ">
                            <div class="ps-form__billing-info">
                                <h3 class="ps-form__heading">Rekening Pembeli</h3>

                                <?php
                                $payment_evidence = $this->db->get_where(TBL_PAYMENT_EVIDENCE, array('id_user' => $user->id, 'type' => $type))->row();

                                // Check expired
                                $currdate   = date('Y-m-d H:i:s');
                                $timediff   = strtotime($currdate) - strtotime($user->datecreated); ?>

                                <?php if ($user->status == 0 && $timediff > 86400 && !$payment_evidence) { ?>
                                    <div class="shop-info danger">
                                        <span>Pembayaran dibatalkan. Link expired! Silahkan hub Admin</span>
                                    </div>
                                <?php } else if ($user->status == 1) { ?>
                                    <div class="shop-info primary">
                                        <span>Pembayaran sudah di konfirmasi</span>
                                    </div>
                                <?php } else if ($user->status == 2) { ?>
                                    <div class="shop-info danger">
                                        <span>Pembayaran dibatalkan!</span>
                                    </div>
                                <?php } else if ($payment_evidence) { ?>
                                    <div class="shop-info primary">
                                        <span>Pembayaran anda sedang kami review.</span>
                                    </div>
                                <?php } else if (
                                    $user->status == 0 && $type == config_item('payment_evidence')['type']['register'] ||
                                    $user->status == 4 && $type == config_item('payment_evidence')['type']['subscription']
                                ) { ?>

                                    <input type="hidden" name="id_user" value=<?= encrypt_param($user->id) ?>>
                                    <input type="hidden" name="type" value=<?= encrypt_param($type) ?>>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Bank <sup>*</sup></label>
                                                <select name="bill_bank" class="form-control">
                                                    <option value="" disabled="" selected="">Pilih Bank</option>
                                                    <?php
                                                    $banks = $this->db->get(TBL_BANK)->result();
                                                    if (!$banks) {
                                                        echo '<option value="">No Bank Data</option>';
                                                    } else {
                                                        foreach ($banks as $row) {
                                                            echo '<option value="' . encrypt_param($row->name) . '">' . $row->name . '</option>';
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
                                    <div class="shop-info primary">
                                        <span>Sebelum konfirmasi pembayaran ini pastikan anda sudah mentransfer sejumlah <b> <?= accounting(get_options('reg_seller_price') + $user->code_unique) ?> </b> ke rekening perusahan</span>
                                    </div>
                                    <a id="saveConfirmPayment" class="ps-btn ps-btn--fullwidth" href="javascript:;">Confirm Payment Now</a>

                                <?php } else { ?>
                                    <div class="shop-info primary">
                                        <span>Pembayaran sudah dilakukan.</span>
                                    </div>
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