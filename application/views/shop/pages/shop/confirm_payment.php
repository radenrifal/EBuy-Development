<?php include APPPATH . 'views/shop/components/header.php'; ?>
<?php include APPPATH . 'views/shop/components/mobile/nav_back.php'; ?>
<?php
    $paymentMethod = '';
    if($order->payment_method == 'productactive'){
        $paymentMethod = 'melalui <b>Produk Aktif</b> anda.';
    }    
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
                                                    $uniquecode         = str_pad($order->unique, 3, '0', STR_PAD_LEFT);
                                                    $unserialize_data   = unserialize($order->products);
                                                    foreach ($unserialize_data as $row) { ?>
                                                         <tr>
                                                            <td class="text-capitalize">
                                                                <?= $row['name'] ?> <br>
                                                                <span class="small">Qty : </span>
                                                                <span class="small product_qty">
                                                                    <?= $row['qty'] ?> &times; <?= ddm_accounting($row['price']) ?>
                                                                </span>
                                                            </td>
                                                            <td class="text-right"><?= ddm_accounting($row['qty'] * $row['price']) ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>

                                            <div class="ps-block--shopping-total mb-5">
                                                <div class="ps-block__header">
                                                    <p>Subtotal <span class="subtotal-cart"> <?= ddm_accounting($order->subtotal) ?> </span></p>
                                                    <?php
                                                        if ( $order->registration > 0 ) {
                                                            echo '<p>Biaya Pendaftaran Agen <span>'. ddm_accounting($order->registration) .'</span></p>';
                                                        }
                                                     ?>
                                                    <!--<p>Ongkos Kirim <span class="courier-cost"> <?= ddm_accounting($order->shipping) ?> </span></p>-->
                                                    <p>Kode Unik <span class="subtotal-cart"> <?= $uniquecode ?> </span></p>
                                                    <?php if ($order->discount) { ?>
                                                    <p>
                                                        Diskon 
                                                        <?php if ($order->voucher) { ?>
                                                            <small class="text-success">( <b><?= $order->voucher; ?></b> )</small>
                                                        <?php } ?>
                                                        <span class="promo-discount"> <?= ddm_accounting($order->discount); ?></span> 
                                                    </p>
                                                    <?php } ?>
                                                </div>
                                                <h3>Total Order <span class="total-checkout"><?= ddm_accounting($order->total_payment) ?> </span></h3>
                                            </div>
                                            
                                            <?php if($order->payment_method != 'productactive'){ ?>
                                            <h3 class="ps-form__heading">Alamat Pengiriman</h3>
                                            <div class="ps-block--shopping-total mb-5" style="margin-bottom: unset;padding: 10px 30px;">
                                                <div class="ps-block__header" style="border-bottom:unset;margin-bottom: unset;">
                                                    <p>Nama <span class="text-capitalize"> <?= $order->name ?></span></p>
                                                    <p>No Telp <span class="promo-discount"> <?= $order->phone ?></span> </p>
                                                    <p>Email <span class="promo-discount"> <?= $order->email ?></span> </p>
                                                    <p>Alamat <span class="text-capitalize"> <?= $order->address ?></span> </p>
                                                    <p>Kota/Kab <span> <?= $order->city ?></span> </p>
                                                    <p>Provinsi <span> <?= $order->province ?></span> </p>
                                                    <p style="margin-bottom: unset;">Kodepos <span> <?= $order->postcode ?></span> </p>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if( $order->payment_method == 'productactive' ){ ?>
                            <div class="col-md-6 col-sm-12 ">
                                <div class="ps-form__total">
                                    <div class="content">
                                        <div class="ps-block--checkout-total">
                                            <div class="ps-block__content">
                                                <h3 class="ps-form__heading">Alamat Pengiriman</h3>
                                                <div class="ps-block--shopping-total mb-5" style="margin-bottom: unset;padding: 10px 30px;">
                                                    <div class="ps-block__header" style="border-bottom:unset;margin-bottom: unset;">
                                                        <p>Nama <span class="text-capitalize"> <?= $order->name ?></span></p>
                                                        <p>No Telp <span class="promo-discount"> <?= $order->phone ?></span> </p>
                                                        <p>Email <span class="promo-discount"> <?= $order->email ?></span> </p>
                                                        <p>Alamat <span class="text-capitalize"> <?= $order->address ?></span> </p>
                                                        <p>Kota/Kab <span> <?= $order->city ?></span> </p>
                                                        <p>Provinsi <span> <?= $order->province ?></span> </p>
                                                        <p style="margin-bottom: unset;">Kodepos <span> <?= $order->postcode ?></span> </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if ($order->status == 1) { ?>
                            <div class="col-md-12 col-sm-12 ">
                                <div class="ps-form__billing-info">
                                    <div class="shop-info primary">
                                        <span>Pembayaran sudah di konfirmasi <?php echo $paymentMethod; ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            
                        <?php } ?>

                        <div class="col-md-6 col-sm-12 ">
                            <div class="ps-form__billing-info">
                            
                                <?php if($order->payment_method == 'transfer'){ ?>
                                
                                <h3 class="ps-form__heading">Rekening Perusahaan</h3>
                                <div class="ps-block--shopping-total mb-5 py-4">
                                    <div class="ps-block__header" style="border-bottom:unset;margin-bottom: unset;">
                                        <?php 
                                            $bill_bank  = '';
                                            $bill_no    = get_option('company_bill');
                                            $bill_name  = get_option('company_bill_name');
                                            if ( $company_bank = get_option('company_bank') ) {
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
                                        <p>Bank <span class="promo-discount"> <?= $bill_bank; ?></span> </p>
                                        <p>No Rekening <span class="promo-discount"> <?= $bill_no ?></span> </p>
                                        <p class="mb-1">Atas Nama <span> <?= $bill_name ?></span></p>
                                    </div>
                                </div>
                                
                                <?php }else{ ?>
                                
                                <h3 class="ps-form__heading">Data MasterAgent</h3>
                                <div class="ps-block--shopping-total mb-5 py-4">
                                    <div class="ps-block__header" style="border-bottom:unset;margin-bottom: unset;">
                                        <?php 
                                            $agent              = ddm_get_memberdata_by_id($order->id_agent);
                                            $bill_bank          = "";
                                            if( $agent->bank > 0 || !empty($agent->bank) ){
                                                $bank           = $this->Model_Bank->get_bank($agent->bank);
                                                $bill_bank      = $bank->kode." - ".$bank->nama;
                                            }
                                            $bill_no            = $agent->bill;
                                            $bill_name          = $agent->bill_name;
                                            $agent_name         = $agent->name;
                                            $agent_phone        = $agent->phone;
                                            
                                            $province_name      = '';
                                            $district_name      = '';
                                            $subdistrict_name   = '';
                                            if( $getProvince    = ddm_provinces($agent->province) ){
                                                $province_name  = $getProvince->province_name;
                                            }
                                            if( $getDistrict    = ddm_districts($agent->district) ){
                                                $district_name  = $getDistrict->district_name;
                                            }
                                            if( $getSubdistrict = ddm_subdistricts($agent->subdistrict) ){
                                                $subdistrict_name = $getSubdistrict->subdistrict_name;
                                            }
                                            
                                            $address = ucwords(strtolower($agent->address)).', '.$subdistrict_name.', '.$district_name.', '.$province_name;
                                        ?>
                                        <p>Nama <span class="promo-discount"> <?= $agent_name; ?></span> </p>
                                        <p>HP <span class="promo-discount"> <?= $agent_phone; ?></span> </p>
                                        <p class="mb-1">Alamat <span> <?= $address; ?></span></p>
                                    </div>
                                </div>
                                
                                <?php } ?>

                                <?php
                                    $condition          = array('type' => 'shop');
                                    $payment_evidence   = $this->Model_Shop->get_payment_evidence_by('id_source', $order->id, $condition, 1);
                                    $currdate           = date('Y-m-d H:i:s');
                                    $timediff           = strtotime($currdate) - strtotime($order->datecreated);
                                ?>

                                <?php if ($order->status == 0 && $timediff > 86400 && !$payment_evidence) { ?>
                                    <div class="shop-info danger">
                                        <span>Pembayaran dibatalkan. Link expired! Silahkan hub Admin</span>
                                    </div>
                                <?php } else if ($order->status == 1) { ?>
                                    <div class="shop-info primary">
                                        <span>Pembayaran sudah di konfirmasi <?php echo $paymentMethod; ?></span>
                                    </div>
                                <?php } else if ($order->status == 2) { ?>
                                    <div class="shop-info danger">
                                        <span>Pembayaran dibatalkan!</span>
                                    </div>
                                <?php } else if ($order->status == 0 && $payment_evidence) { ?>
                                    <div class="shop-info primary">
                                        <span>Pembayaran anda sedang dalam proses review.</span>
                                    </div>
                                <?php } else if ($order->status == 0 || !$payment_evidence) { ?>

                                    <input type="hidden" name="id_order" value=<?= ddm_encrypt($order->id) ?>>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Bank Pengirim<sup>*</sup></label>
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
                                            <div class="form-group">
                                                <label>No Rekening <sup>*</sup></label>
                                                <div class="form-group__content">
                                                    <input name="bill_no" class="form-control" type="number" placeholder="Nomor Rekening Anda">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Nama Rekening <sup>*</sup></label>
                                                <div class="form-group__content">
                                                    <input name="bill_name" class="form-control text-capitalize" type="text" placeholder="Nama Rekening Anda">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Jumlah ditransfer <sup>*</sup></label>
                                                <div class="form-group__content">
                                                    <input name="transfer" class="form-control" type="number" placeholder="Nominal Anda Transfer">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Upload Bukti Transfer</label>
                                                <?php 
                                                    $file_src   = ASSET_PATH . 'backend/img/no_image.jpg';
                                                ?>
                                                <div class="thumbnail mb-1">
                                                    <img class="img-thumbnail mb-3" id="upload_img_thumbnail" width="100%" src="<?php echo $file_src; ?>">
                                                    <div class="caption">
                                                        <p class="text-muted mb-0" style="font-size: 14px">Image ( jpg, jpeg, png ) and Max 2 MB</p>
                                                        <div class="img-information" style="display: none;">
                                                            <i class="ni ni-album-2 mr-1" id="type_img_thumbnail"></i> 
                                                            <span id="size_img_thumbnail" style="font-weight: 600;"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="file" name="upload_file" id="upload_file" class="form-control pt-2 px-2" accept="image/x-png,image/jpeg">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="shop-info primary">
                                        <span>Sebelum konfirmasi pembayaran ini pastikan anda sudah mentransfer sejumlah Rp<b> <?= ddm_accounting($order->total_payment) ?> </b> ke rekening <?= $paymentMethod=='transfer' ? 'Perusahaan' : 'Master Agent. Hubungi MasterAgent sesuai data diatas untuk info Rekening lebih detail.'; ?></span>
                                    </div>
                                    <a id="saveConfirmPayment" class="ps-btn ps-btn--fullwidth" href="javascript:;">Confirm Payment Now</a>

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