<?php
//$user = get_userdata(4);
//$token = $this->db->get_where(TBL_TOKEN_PASS, array('token' => '5b1f09d4d38b46280ca7fd4238888e2308557cdd', 'id_user' => 4, 'status' => 1))->row();
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
                <th style="text-align:left;"><img style="max-width: 60px;" src="<?= LOGO_IMG ?>"></th>
                <th style="text-align:right;font-weight:400;"><?= date_indo($token->datecreated, 'datetime') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2" style="font-size:18px;padding:30px 15px 10px 15px;font-weight: bold;">Informasi Reset Password</td>
            </tr>
            <tr>
                <td style="height:15px;"></td>
            </tr>
            <tr>
                <td colspan="2" style="border: solid 1px #ddd; padding:10px 20px;" class="group-list">
                    <p style="font-size:14px;margin:0 0 8px 0;">
                        <span class="title-list">Username</span><span style="text-transform:lowercase;">: <?= $user->username ?></span>
                    </p>
                    <p style="font-size:14px;margin:0 0 8px 0;">
                        <span class="title-list">Email</span>: <?= $user->email ?>
                    </p>
                    <p style="font-size:14px;margin:20px 0 8px 0;">
                        Klik link dibawah ini untuk melakukan pergantian password anda :
                    </p>
                    <p style="font-size:14px;margin:0 0 8px 0;">
                        <a href="<?= base_url('auth/reset-password/' . $token->token) ?>"><?= base_url('auth/reset-password/' . $token->token) ?></a>
                    </p>
                    <p style="font-size:14px;margin:40px 0 8px 0;">
                        NB: Segera lakukan konfirmasi pergantian password, karna link ini akan kadaluarsa pada <b><?= date_indo($token->date_expired, 'datetime') ?></b>
                    </p>
                </td>
            </tr>
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