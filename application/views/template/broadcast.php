<?php
$getNotif   = $this->db->get_where(TBL_NOTIFICATION, array('id' => $id))->row();
$type       = NOTIF_BROADCAST;
$imgpath    = UPLOAD_IMG . $type;
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
            <tr class="top">
                <td class="no-padding" style="padding: unset !important;vertical-align: top;">
                    <table style="width: 100%;line-height: inherit;text-align: left;">
                        <tr>
                            <td class="title mobile-center" style="padding: 5px;vertical-align: top;padding-bottom: 20px;font-size: 45px;line-height: 45px;color: #333;">
                                <img src="<?= LOGO_IMG ?>" style="width:100%; max-width:60px;">
                            </td>
                            <td class="mobile-center" style="text-align: right;padding: 5px;vertical-align: top;padding-bottom: 20px;">
                                <span style="font-size:13px;">Tanggal : <?= date_indo($getNotif->datecreated, 'datetime') ?><br></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td style="font-size:18px;padding:30px 15px 10px 15px;font-weight: bold;text-transform:capitalize"><?= $getNotif->title ?></td>
            </tr>
            <tr>
                <td style="padding:10px 15px;width:100%" class="group-list">
                    <p style="font-size:14px;white-space:pre-line;width:100%">
                        <img src="<?= get_image_single($getNotif->id, $type, $imgpath) ?>" style="width: 100%;margin-bottom:20px">
                        <?= $getNotif->description ?>
                    </p>
                </td>
            </tr>
            <tfooter>
                <tr>
                    <td style="font-size:14px;padding:50px 15px 0 15px;">
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