<?php
//$user = $this->Model_user->getUser(32);
//print_r($user);
?>

<html>

<head>
    <meta charset="utf-8" />
    <title><?= COMPANY_NAME ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <style>
        @media only screen and (max-width: 600px) {
            table {
                padding: 20px !important;
            }

            .product img {
                width: 100px !important;
                height: 100px !important;
            }

            .group-list .title-list {
                min-width: 80px !important;
            }
        }

        .group-list .title-list {
            font-weight: bold;
            display: inline-block;
            min-width: 135px;
        }

        .button {
            background-color: #4CAF50;
            /* Green */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }

        .red {
            background-color: #f44336;
        }

        .capitalize {
            text-transform: capitalize;
        }

        /* Red */
    </style>

</head>

<body style="background-color:#e2e1e0;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
    <table style="max-width:670px;margin:50px auto 10px;background-color:#fff;
                padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;
                border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);
                box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); 
                border-top: solid 10px #ea4347;font-size: 15px;">
        <thead>
            <tr>
                <th style="text-align:left;"><img style="max-width: 50px;display:block" src="<?= LOGO_IMG ?>" alt="logo" title="logo"></th>
                <th style="text-align:right;font-weight:400;"><?= date_indo($user->datecreated, 'datetime') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2" style="font-size:18px;padding:20px 20px 10px;font-weight: bold;">Welcome to <?= COMPANY_NAME ?></td>
            </tr>
            <tr>
                <?php if ($user->type == USER_PRODUCT_PARTNER) { ?>
                    <td colspan="2" style="height:15px;padding:10px 20px 20px;">
                        Terima kasih telah mendaftar sebagai Product Partner. Team Salesin akan menghubungi Anda, jika memenuhi kriteria sebagai Product Partner.
                    </td>
                <?php } else { ?>
                    <td style="height:15px"></td>
                <?php } ?>
            </tr>

            <tr>
                <td colspan="2" style="border: solid 1px #ddd; padding:10px 20px;" class="group-list">

                    <p style="font-size:14px;margin:0 0 8px 0;">
                        <span class="title-list ">Nama</span><span style="text-transform:uppercase;">: <?= $user->name ?></span>
                    </p>
                    <p style="font-size:14px;margin:0 0 8px 0;">
                        <span class="title-list">Telepon</span>: <?= $user->phone ?>
                    </p>

                    <?php if ($user->type == USER_SELLER) { ?>
                        <p style="font-size:14px;margin:0 0 8px 0;">
                            <span class="title-list">Provinsi</span>: <?= $user->province_name ?>
                        </p>
                        <p style="font-size:14px;margin:0 0 8px 0;">
                            <span class="title-list">Kab/Kota</span>: <?= $user->city_name ?>
                        </p>
                        <p style="font-size:14px;margin:0 0 8px 0;">
                            <span class="title-list">Kecamatan</span>: <?= $user->subdistrict ?>
                        </p>
                    <?php } ?>

                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 15px 0;"></td>
            </tr>
            <tr>
                <td colspan="2" style="border: solid 1px #ddd; padding:10px 20px;" class="group-list">
                    <p style="font-size:14px;margin:0 0 8px 0;">
                        <span class="title-list">Username</span>
                        <span style="text-transform:uppercase;">: <?= $user->username ?></span>
                    </p>
                    <p style="font-size:14px;margin:0 0 8px 0;">
                        <span class="title-list">Status akun</span>
                        <span style="text-transform:uppercase;">: <?= ($user->status == 1 ? 'aktif' : ($user->status == 4 ? 'Pending Subscription' : 'belum aktif')) ?></span>
                    </p>
                    <p style="font-size:14px;margin:0 0 8px 0;">
                        <span class="title-list">Tipe akun</span>
                        <span style="text-transform:uppercase;">: <?= str_replace('_', ' ', $user->type) ?></span>
                    </p>
                    <p style="font-size:14px;margin:20px 0 8px 0;">
                        Jika status akun anda sudah <b>AKTIF</b>, silahkan login dengan menggunakan username dan password anda
                    </p>
                </td>
            </tr>

            <?php /* if ($user->type == USER_SELLER) { ?>
                <tr>
                    <td colspan="2" style="padding: 15px 0;"></td>
                </tr>
                <tr>
                    <td colspan="2" class="group-list">
                        <?php include APPPATH . 'views/frontend/components/howto.php'; ?>
                    </td>
                </tr>
            <?php } */ ?>

            <?php
            if ($user->status == 0) {
                $type = config_item('payment_evidence')['type']['register'];
            }
            if ($user->status == 4) {
                $type = config_item('payment_evidence')['type']['subscription'];
            }
            if ($user->type == USER_SELLER && $user->status == 0 || $user->status == 4) {
            ?>

                <tr>
                    <td colspan="2" style="padding: 15px 0;"></td>
                </tr>
                <tr>
                    <td colspan="2" style="border: solid 1px #ddd; padding:10px 20px;" class="group-list">
                        <p style="font-size:14px;margin:0 0 8px 0;">
                            <span class="title-list">No Rekening</span><span style="text-transform:capitalize;">: <?= config_item('billing')['bill_no'] ?></span>
                        </p>
                        <p style="font-size:14px;margin:0 0 8px 0;">
                            <span class="title-list">Nama</span><span style="text-transform:capitalize;">: <?= config_item('billing')['bill_name'] ?></span>
                        </p>
                        <p style="font-size:14px;margin:0 0 8px 0;">
                            <span class="title-list">Bank</span>: <?= config_item('billing')['bill_bank'] ?>
                        </p>
                        <p style="font-size:14px;margin:0 0 8px 0;">
                            <span class="title-list">Cabang</span>: <?= config_item('billing')['bill_branch'] ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding: 15px 0;"></td>
                </tr>
                <tr>
                    <td colspan="2" style="border: solid 1px #ddd; padding:10px 20px;" class="group-list">
                        <p style="font-size:14px;margin:0 0 8px 0;">
                            <span class="title-list">Nominal</span>: <?= accounting(get_options('reg_seller_price')) ?>
                        </p>
                        <p style="font-size:14px;margin:0 0 8px 0;">
                            <span class="title-list">Kode Unik</span>: <?= $user->code_unique ?>
                        </p>
                        <p style="font-size:14px;margin:0 0 8px 0;">
                            <span class="title-list">Total</span>: <?= accounting(get_options('reg_seller_price') + $user->code_unique) ?>
                        </p>
                        <div class="info-box" style="padding: 12px 20px;margin: 15px 0 10px;background: #eca72c;color: white;">
                            Biaya diatas adalah untuk menjadi Seller Salesin selama 12 bulan.
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding: 15px 0;"></td>
                </tr>
                <tr>
                    <td colspan="2" class="group-list">
                        <div class="info-box" style="padding: 20px;margin: auto;background: #3b9dcd;color: white;border-radius: 13px;">
                            Silahkan transfer sebesar <?= accounting(get_options('reg_seller_price') + $user->code_unique) ?> ke Rekening Perusahaan untuk mengaktifkan atau memperpanjang kayanan anda.
                        </div>
                        <br>
                        <center>
                            <div class="btn-confirm-payment" style="margin: 15px 0;">
                                <a href="<?= base_url($type . '/confirm/payment/' . encrypt_param($user->id)) ?>" class="btn-green" style="background: #43c344;width: 200px;padding: 13px 26px;border-radius: 40px;color: white;text-decoration: unset;">
                                    Konfirmasi Pembayaran
                                </a>
                            </div>
                        </center>
                    </td>
                </tr>
            <?php } ?>

        </tbody>

        <tfooter>
            <tr>
                <td colspan="2" style="font-size:14px;padding:50px 15px 0 15px;">
                    <strong style="display:block;margin:0 0 10px 0;">Regards</strong>
                    <?= get_option('company_name') ?>,<br>
                    <?= get_option('company_address') ?><br><br>
                    <b>Phone:</b> <?= get_option('company_phone') ?><br>
                    <b>Email:</b> <?= get_option('company_email') ?>
                </td>
            </tr>
        </tfooter>
    </table>
</body>

</html>