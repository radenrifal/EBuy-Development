<?php
include APPPATH . 'views/shop/components/header.php';
include APPPATH . 'views/shop/components/mobile/nav_search.php';
?>

<div class="ps-breadcrumb">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?= base_url() ?>">Home</a></li>
            <li>Find Seller</li>
        </ul>
    </div>
</div>
<div class="ps-order-tracking ps-shopping ps-section--shopping section-shadow">
    <div class="container">
        <div class="ps-section__header">
            <h3><i class="fa fa-search"></i> Find Nearby Agent</h3>
            <p>
                Silahkan masukkan Kode Agen atau lokasi anda untuk mencari Agen terdekat
            </p>
        </div>
        <div class="ps-section__content">


            <form class="ps-form--order-tracking">

                <?php if ( isset($page_type) && strtolower($page_type) == 'shop' ) { ?>
                    <div class="px-5 py-3 mb-5 options-reg" style="border: 1px solid #117a8b;">
                        <div class="form-group mb-0 mt-2">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    Apakah Anda ingin menjadi Agen ?
                                </div>
                                <div class="col-md-4 col-sm-12 text-right">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label id="opt-agent" class="btn btn-info px-4 py-2">
                                            <input value="agent" type="radio" name="options_reg" autocomplete="off"> Ya
                                        </label>
                                        <label id="opt-customer" class="btn btn-info px-3 py-2 active">
                                            <input value="customer" type="radio" name="options_reg" autocomplete="off" checked> Tidak, Cukup Konsumen Saja
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row opt-toggle-reg mt-3" id="tab-option-agent" style="display:none">
                            <?php include APPPATH . 'views/frontend/components/howto.php'; ?>
                        </div>
                    </div>
                <?php } ?>

                <span class="place-alert">
                    <?php if ( isset($msg) && !empty($msg) ) { ?>
                        <div class="alert alert-warning alert-dismissible fade show mb-5 py-3 px-4" role="alert">
                            <i class="fa fa-info-circle mr-1"></i>    
                            <?php echo $msg; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>
                </span>

                <div class="px-5 pt-3 pb-4 mb-4 question-reg" style="border: 1px solid #607acc;">
                    <div class="form-group mb-0 mt-2">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                Cari Agen Berdasarkan ?
                            </div>

                            <div class="col-md-6 col-sm-12 text-right">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-primary px-3 py-2 active">
                                        <input value="agent_location" type="radio" name="options_tracking" autocomplete="off" checked> Lokasi Agen
                                    </label>
                                    <label class="btn btn-primary px-4 py-2">
                                        <input value="agent_code" type="radio" name="options_tracking" autocomplete="off"> Kode Agen
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-9 opt-tracking-toggle" id="tab-agent_location">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group no-label mb-0">
                                        <select name="province" class="form-control rajaongkir-province" readonly></select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group no-label mb-0">
                                        <select name="city" class="form-control rajaongkir-city" readonly>
                                            <option value="">Kota/Kab</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group no-label mb-0">
                                        <select name="subdistrict" class="form-control rajaongkir-subdistrict" readonly>
                                            <option value="">Kecamatan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9 opt-tracking-toggle" id="tab-agent_code" style="display:none">
                            <div class="form-group no-label mb-0">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text pl-4" style="background: transparent; border-right: none; font-weight: bold;"><i class="icon-user"></i></span>
                                    </div>
                                    <input type="text" name="agent_code" class="form-control pl-2" placeholder="Masukkan Kode Agen" style="border-left: none;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <button class="ps-btn ps-btn--fullwidth" id="find-seller" data-type="<?= $type ?>" style="height: 40px;padding: 0;"><i class="fa fa-search mr-1"></i> Find Agen</button>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group mb-0 d-none" id="list-seller">
                                <hr>
                                <div class="profile-page">
                                    <div class="row"> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<br><br>