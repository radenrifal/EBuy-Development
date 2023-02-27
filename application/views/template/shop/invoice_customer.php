<?php

$order      = $this->Model_Shop->get_shop_order_customer_by('id', $id_order);
$agent      = ddm_get_memberdata_by_id($order->id_member);
$paymentEvidence = false;

?>

<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= COMPANY_NAME . ' Invoice #' . $order->invoice ?></title>

</head>

<style>
    * {
        font-size: 14px;
    }

    @media only screen and (max-width:480px) {
        table td.mobile-center {
            width: 100% !important;
            display: block !important;
            text-align: left !important;
        }

        table td.title.mobile-center {
            text-align: center !important
        }

        .mobile-hide {
            display: none;
        }

        .mobile-text-left {
            text-align: left !important;
        }

    }

    @media print {
        body {
            background: white !important;
            padding: 0 !important;
        }

        .invoice-box {
            box-shadow: unset !important;
            border: unset !important;
        }

        .img-product {
            display: none;
        }

        .invoice-box .info-box {
            display: none !important;
        }

        .invoice-box .btn-confirm-payment {
            display: none !important;
        }
    }

    .no-padding {
        padding: unset !important;
    }

    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }

    .rtl table {
        text-align: right;
    }

    .rtl table tr td:nth-child(2) {
        text-align: left;
    }

    table.no-wrap td {
        white-space: unset;
    }
</style>

<body style="<?= ($this->uri->segment(1) == 'check-order') ? '' : 'background: #e4e4e4;padding: 25px 0;' ?>">

    <div class="invoice-box" style="
    background-color: white; 
    /*background: url(https://services.google.com/fh/files/emails/play_dev_dark_mode_116.png);*/
    background-repeat: repeat-x; background-size: 100%;
    max-width: 800px;margin: auto;padding: 30px;border: 1px solid #eee;box-shadow: 0 0 10px rgba(0, 0, 0, .15);font-size: 16px;line-height: 24px;font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;color: #555;">
        <table cellpadding="0" cellspacing="0" style="margin-bottom: 20px;width: 100%;line-height: inherit;text-align: left;">

            <tr class="top">
                <td colspan="2" class="no-padding" style="padding: unset !important;vertical-align: top;">
                    <table style="width: 100%;line-height: inherit;text-align: left;">
                        <tr>
                            <td class="title mobile-center" style="padding: 5px;vertical-align: top;padding-bottom: 20px;font-size: 45px;line-height: 45px;color: #333;">
                                <img src="<?= LOGO_IMG ?>" style="width:100%; max-width:60px;">
                            </td>
                            <td class="mobile-center" style="text-align: right;padding: 5px;vertical-align: top;padding-bottom: 20px;">
                                <span style="color: #086b08;font-weight: bold;">Status : <?= status_order($order->id, $order->status) ?></span><br>
                                Invoice : <?= '#' . $order->invoice ?><br>
                                Tgl Order : <?= date_indo($order->datecreated, 'datetime') ?><br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2" class="no-padding" style="padding: unset !important;vertical-align: top;">
                    <table style="width: 100%;line-height: inherit;text-align: left;">
                        <?php 
                            $bill_bank  = '';
                            $bill_no    = $agent->bill;
                            $bill_name  = $agent->bill_name;
                            if ( $company_bank = $agent->bank ) {
                                if ( $getBank = ddm_banks($company_bank) ) {
                                    $bill_bank = $getBank->nama;
                                }
                            }

                            if ( $bill_no ) {
                                $bill_format = '';
                                $arr_bill    = str_split($bill_no, 4);
                                foreach ($arr_bill as $no) {
                                    $bill_format .= $no .' ';
                                }
                                $bill_no = $bill_format ? $bill_format : $bill_no;;
                            }

                        ?>
                        <tr>
                            <td class="mobile-center" style="padding: 5px;vertical-align: top;padding-bottom: 40px;">
                                <b>Informasi Agen</b><br>
                                <span class="capitalize" style="text-transform: capitalize;">Nama : <?= $agent->name; ?><br></span>
                                <span class="capitalize" style="text-transform: capitalize;">No. Tlp : <?= $agent->phone; ?><br></span>
                                <span class="capitalize" style="text-transform: capitalize;">Email : <?= $agent->email; ?><br></span>

                            </td>
                            <td class="mobile-center" style="text-align: right;padding: 5px;vertical-align: top;padding-bottom: 40px;">
                                <b>Detail Pengiriman</b><br>
                                <span class="capitalize" style="text-transform: capitalize;"><?= $order->name ?><br></span>
                                <span class="capitalize" style="text-transform: capitalize;"><?= $order->phone ?><br></span>
                                <span class="capitalize" style="text-transform: capitalize;"><?= $order->address ?><br></span>
                                <span class="capitalize" style="text-transform: capitalize;"><?= $order->subdistrict . ', ' . $order->city . ', ' . $order->province . ', ' . $order->postcode ?><br></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Info Resi -->
        <?php if ($order->status == 4) { ?>
            <table class="table" style="margin-bottom: 20px;width: 100%;line-height: inherit;text-align: left;">
                <tr class="heading">
                    <th style="background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 5px 7px;">No Resi</th>
                </tr>
                <tr class="item">
                    <td style="padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;">12345</td>
                </tr>
            </table>
        <?php } ?>

        <!-- Info Product -->
        <table class="table no-wrap table-responsive" style="margin-bottom: 30px;width: 100%;line-height: inherit;text-align: left;">
            <thead>
                <tr class="heading">
                    <th style="width:100%;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 5px 7px;">Produk</th>
                    <th style="width:100%;text-align: right;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 5px 7px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $num = 1;
                $unserialize_data = unserialize($order->products);
                foreach ($unserialize_data as $row) {
                    $idMaster   = $row['id'];
                    $image      = data_product($idMaster, 'image');
                    $img_src    = product_image($image);
                ?>
                    <tr>
                        <td style="width: 50%;text-align: left;text-transform: capitalize;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;">
                            <img src="<?= $img_src; ?>" style="width: 55px;float: left;margin-right: 10px;">
                            <span style="font-size: 12px;margin-bottom: -5px;display: block;"><?= data_product($row['id'], 'name') ?></span>
                            <span style="font-size: 11px;display:block;margin-bottom:-10px">
                                Harga:
                                <?php 
                                    if ( $row['price_ori'] > $row['price'] ) {
                                        echo '<s style="font-size: 10px">'. ddm_accounting($row['price_ori']) .'</s> '. ddm_accounting($row['price']) ;
                                    } else {
                                        echo ddm_accounting($row['price']);
                                    }
                                ?>
                            </span>
                            <span style="font-size: 10px;">Qty: <?= $row['qty'] ?></span>
                        </td>
                        <td class="text-center" style="width: 50%; text-align: right;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                            <?= ddm_accounting($row['qty'] * $row['price']) ?>
                        </td>
                    </tr>
                <?php } ?>
                <td class="text-center" colspan="2" style="width: 50%; text-align: right;padding: 5px;vertical-align: top;white-space: nowrap;font-weight: bold">
                    <?= ddm_accounting($order->subtotal) ?>
                </td>
            </tbody>
        </table>

        <table class="table" style="margin-bottom: 20px;width: 100%;line-height: inherit;text-align: left;">
            <tr class="heading">
                <th style="width: 50%;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 5px 7px;">Pengiriman</th>
                <th style="width: 50%;text-align: right;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 5px 7px;">Detail</th>
            </tr>

            <tr class="item">
                <td style="width: 50%;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;">Kurir</td>
                <td style="text-transform: uppercase;text-align: right;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;"><?= $order->courier; ?></td>
            </tr>
            <tr class="item">
                <td style="width: 50%;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;">Layanan</td>
                <td style="width: 50%;text-align: right;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;"><?= $order->service ?></td>
            </tr>
            <tr class="item">
                <td style="width: 50%;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;">Ongkir</td>
                <td style="width: 50%;text-align: right;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;"><?= ddm_accounting($order->shipping); ?></td>
            </tr>

            <tr class="details">
                <td style="padding: 5px;vertical-align: top;"></td>
            </tr>

            <tr class="total">
                <?php $uniquecode = str_pad($order->unique, 3, '0', STR_PAD_LEFT); ?>
                <td colspan="2" style="border-top: unset;padding: 20px 0;text-align: right;vertical-align: top;font-weight: bold; min-width:100%" class="mobile-text-left">
                    Kode Diskon : <?= ($order->voucher) ? $order->voucher : '-' ?> <br>
                    Diskon : <?= ddm_accounting($order->discount) ?><br>
                    Kode Unik : <?= $uniquecode ?> <br>
                    Total Pembayaran : <?= ddm_accounting($order->total_payment) ?>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>