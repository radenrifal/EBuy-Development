<?php
    $order              = $this->Model_Shop->get_shop_orders ($id_order);
    $current_member     = ddm_get_current_member();
    $condition          = array('type' => 'shop');
    $paymentEvidence    = $this->Model_Shop->get_payment_evidence_by('id_source', $id_order, $condition, 1);
    
    $agentdata          = '';
    if( $order->id_agent > 0 ){
        $agentdata      = ddm_get_memberdata_by_id($order->id_agent);
    }
    $agent              = ( !empty($agentdata) || $agentdata ? true : false );
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
                            <?php
                                $status_sent = false;
                                if ( $order->status == 1 && $order->datesent && $order->datesent != '0000-00-00 00:00:00' ) {
                                    $status_sent = true;
                                }
                            ?>
                            <td class="mobile-center" style="text-align: right;padding: 5px;vertical-align: top;padding-bottom: 20px;">
                                <span style="color: #086b08;font-weight: bold;">Status : <?= status_order($order->id, $order->status, 'agent', $status_sent) ?></span><br>
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
                            $bill_no    = get_option('company_bill');
                            $bill_name  = get_option('company_bill_name');
                            if ( $company_bank = get_option('company_bank') ) {
                                if ( $getBank = ddm_banks($company_bank) ) {
                                    $bill_bank = $getBank->nama;
                                }
                            }
                            
                            if( $agent ){
                                $bill_bank  = '';
                                $bill_no    = $agentdata->bill;
                                $bill_name  = $agentdata->bill_name;
                                if ( $getBank = ddm_banks($agentdata->bank) ) {
                                    $bill_bank = $getBank->nama;
                                }
                                
                                $address            = '';
                                $province_name      = '';
                                $district_name      = '';
                                $subdistrict_name   = '';
                                if( $getProvince    = ddm_provinces($agentdata->province) ){
                                    $province_name  = $getProvince->province_name;
                                }
                                if( $getDistrict    = ddm_districts($agentdata->district) ){
                                    $district_name  = $getDistrict->district_name;
                                }
                                if( $getSubdistrict = ddm_subdistricts($agentdata->subdistrict) ){
                                    $subdistrict_name = $getSubdistrict->subdistrict_name;
                                }
                                $address = ucwords(strtolower($agentdata->address)).', '.$subdistrict_name.', '.$district_name.', '.$province_name;
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
                                <b>Informasi <?= $agent ? 'Master Agen' : 'Rekening Perusahaan' ?></b><br>
                                <?php if( $agent ){ ?>
                                    <span class="capitalize" style="text-transform: capitalize;">Nama : <?= $agentdata->name; ?><br></span>
                                    <span class="capitalize" style="text-transform: capitalize;">HP : <?= $agentdata->phone; ?><br></span>
                                    <span class="capitalize" style="text-transform: capitalize;">Alamat : <?= $address; ?><br></span>
                                <?php }else{ ?>
                                    <span class="capitalize" style="text-transform: capitalize;">Bank : <?= $bill_bank; ?><br></span>
                                    <span class="capitalize" style="text-transform: capitalize;">No Rek : <?= $bill_no; ?><br></span>
                                    <span class="capitalize" style="text-transform: capitalize;">Nama : <?= $bill_name; ?><br></span>
                                <?php } ?>
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
        <?php if ($order->status == 1 && $order->resi) { ?>
            <table class="table" style="margin-bottom: 20px;width: 100%;line-height: inherit;text-align: left;">
                <tr class="heading">
                    <th style="background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 5px 7px;">No Resi</th>
                </tr>
                <tr class="item">
                    <td style="padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;"><?= $order->resi; ?></td>
                </tr>
            </table>
        <?php } ?>

        <!-- Info Product -->
        <table class="table no-wrap table-responsive" style="margin-bottom: 30px;width: 100%;line-height: inherit;text-align: left;">
            <thead>
                <tr class="heading">
                    <th style="width:70%;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 5px 7px;">Produk</th>
                    <th style="width:100%;text-align: right;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 5px 7px;">Subtotal (Rp)</th>
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
                            <span style="font-size: 12px;margin-bottom: -5px;display: block;"><?= ( $row['name'] ? $row['name'] : 'Produk Tidak Ditemukan' ) ?></span>
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
                            <span style="font-size: 10px;">Qty: <?= ddm_accounting($row['qty']) ?> Liter</span>
                        </td>
                        <td class="text-center" style="width: 50%; text-align: right;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                            <?= ddm_accounting($row['price']) ?>
                        </td>
                    </tr>
                <?php } ?>
                <td class="text-center" colspan="2" style="width: 50%; text-align: right;padding: 5px;vertical-align: top;white-space: nowrap;font-weight: bold">
                    <?= ddm_accounting($order->subtotal) ?>
                </td>
            </tbody>
        </table>

        <table class="table" style="margin-bottom: 20px;width: 100%;line-height: inherit;text-align: left;">
            <tr class="total">
                <?php $uniquecode = str_pad($order->unique, 3, '0', STR_PAD_LEFT); ?>
                <td colspan="2" style="border-top: unset;padding: 20px 0;text-align: right;vertical-align: top;font-weight: bold; min-width:100%" class="mobile-text-left">
                    Diskon : Rp <?= ddm_accounting($order->discount) ?><br>
                    Kode Unik : <?= $uniquecode ?> <br>
                    Total Pembayaran : Rp <strong><?= ddm_accounting($order->total_payment) ?></strong>
                </td>
            </tr>
            <tr class="heading">
                <th style="width: 50%;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 5px 7px;">Pengiriman</th>
                <th style="width: 50%;text-align: right;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 5px 7px;">Detail</th>
            </tr>

            <tr class="item">
                <td style="width: 50%;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;">Kurir</td>
                <td style="text-transform: uppercase;text-align: right;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;"><?= $order->courier; ?></td>
            </tr>
            <!--
            <tr class="item">
                <td style="width: 50%;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;">Layanan</td>
                <td style="width: 50%;text-align: right;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;"><?= $order->service ?></td>
            </tr>
            <tr class="item">
                <td style="width: 50%;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;">Ongkir</td>
                <td style="width: 50%;text-align: right;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;"><?= ddm_accounting($order->shipping); ?></td>
            </tr>
            -->

            <tr class="details">
                <td style="padding: 5px;vertical-align: top;"></td>
            </tr>

        </table>

        <?php if ($order->status == 0 && !$paymentEvidence && ($order->id_member == $current_member->id)) { ?>
            <div class="info-box" style="padding: 20px;margin: auto;background: #5c4b79;color: white;">
                Sebelum konfirmasi pembayaran ini pastikan anda sudah mentransfer sejumlah <strong><?= ddm_accounting($order->total_payment) ?></strong> ke rekening <?= $agent ? 'MasterAgent' : 'Perusahaan' ?>. 
                Hubungi <?= $agent ? 'MasterAgent sesuai data diatas' : 'Perusahaan' ?> untuk info rekening lebih detail.
            </div>
            <br>
            <center>
                <div class="btn-confirm-payment" style="margin: 15px 0;">
                    <a href="<?= base_url('confirm/payment/' . encrypt_param($order->id)) ?>" class="btn-green" style="background: #43c344;width: 200px;padding: 13px 26px;border-radius: 40px;color: white;text-decoration: unset;">
                        Konfirmasi Pembayaran
                    </a>
                </div>
            </center>
        <?php }elseif($order->status == 0 && !$paymentEvidence){ ?>
            <div class="info-box" style="padding: 20px;margin: auto;background: #5c4b79;color: white;">
                Silahkan tunggu Konfirmasi Pembayaran dari Agent Sebesar <strong><?= ddm_accounting($order->total_payment) ?></strong> Ke Rekening Anda.
            </div>
        <?php } ?>

    </div>
</body>

</html>