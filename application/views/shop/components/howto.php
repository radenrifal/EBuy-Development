<?php 
    $currency   = config_item('currency'); 
    $reg_fee    = get_option('register_fee'); 
    $reg_fee    = $reg_fee ? $reg_fee : 0; 
?>

<div class="col-12 my-1">
    <div class="term" style="overflow-y:auto;height:320px;text-align:justify;padding:20px;border: 1px solid #c1c1c1;">
        <h4>Bagaimana Cara menjadi Agen di <?= COMPANY_NAME ?></h4>
        <ol type="1">
            <li>Anda hanya perlu membeli produk per-Paket</li>
            <li>Mengisi Form Pendaftaran Agen</li>
            <li>Biaya Pendaftaran Agen <?= ( $reg_fee > 0 ) ? 'sebesar '. ddm_accounting($reg_fee, $currency) : 'Gratis'; ?></li>
        </ol>
        <br>
        <h4>Apa Saja Benefit Yang Akan Didapatkan</h4>
        <ol type="1">
            <li>Produk Berkualitas dan Eksklusif</li>
            <li>Komisi Besar (10% - 50% dari harga produk)</li>
            <li>Rewards Menarik</li>
            <li>Bisa Pantau Komisi & Reward secara Real Time di Aplikasi</li>
            <li>Harga Join Sangat Terjangkau</li>
        </ol>
    </div>
</div>

<div class="col-md-12">
    <div class="ps-site-features mobile" style="background: #f8f8f8;padding: 30px 0;">
        <div class="text-center wow rotateIn">
            <h5 class="text-center">Daftar sekarang juga dan dapatkan keuntungannya</h5>
            <a href="<?= base_url('register/agent') ?>">
                <button type="button" class="btn btn-outline-dark px-5" style="font-size:16px;">Daftar Menjadi Agen</button>
            </a>
        </div>
    </div>
</div>