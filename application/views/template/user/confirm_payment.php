<?php
//$user = $this->Model_user->getUser(52);
$paymentEvidence = $this->db->get_where(TBL_PAYMENT_EVIDENCE, array('id_user' => $user->id))->row();
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
                <td colspan="2" style="padding: 15px 0;"></td>
            </tr>
            <tr>
                <td colspan="2" class="group-list">
                    <div class="info-box" style="padding: 20px;margin: auto;background: #3b9dcd;color: white;">
                        Terima kasih telah melakukan pembayaran. Silahkan tunggu email dari admin jika pembayaran anda disetujui.
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="font-size:17px;padding:30px 20px 10px;font-weight: bold;">Rekening Perusahaan</td>
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
                <td colspan="2" style="font-size:17px;padding:0 20px 10px;font-weight: bold;">Informasi Rekening Seller</td>
            </tr>
            <tr>
                <td colspan="2" style="border: solid 1px #ddd; padding:10px 20px;" class="group-list">
                    <p style="font-size:14px;margin:0 0 8px 0;">
                        <span class="title-list">Tanggal Transfer</span>: <?= $paymentEvidence->datecreated ?>
                    </p>
                    <p style="font-size:14px;margin:0 0 8px 0;">
                        <span class="title-list">Jumlah Transfer</span>: <?= accounting($paymentEvidence->amount) ?>
                    </p>
                    <p style="font-size:14px;margin:0 0 8px 0;">
                        <span class="title-list">No Rekening</span><span style="text-transform:capitalize;">: <?= $paymentEvidence->bill_no ?></span>
                    </p>
                    <p style="font-size:14px;margin:0 0 8px 0;">
                        <span class="title-list">Nama</span><span style="text-transform:capitalize;">: <?= $paymentEvidence->bill_name ?></span>
                    </p>
                    <p style="font-size:14px;margin:0 0 8px 0;">
                        <span class="title-list">Bank</span>: <?= $paymentEvidence->bill_bank ?>
                    </p>
                </td>
            </tr>

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