<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_setting') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_setting_general'); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body wrapper-setting-general">
                    <div class="accordion" id="accordionGeneralSetting">
                        <div class="card mb-2">
                            <div class="card-header py-3 bg-gradient-primary" id="headCompanyInfo" data-toggle="collapse" data-target="#companyInfo" aria-expanded="false" aria-controls="companyInfo">
                                <h5 class="text-white mb-0">Informasi Perusahaan</h5>
                            </div>
                            <div id="companyInfo" class="collapse show" aria-labelledby="headCompanyInfo" data-parent="#accordionGeneralSetting">
                                <?php $this->load->view(VIEW_BACK . 'setting/formgeneral/company'); ?>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-header py-3 bg-gradient-primary" id="headCompanyBilling" data-toggle="collapse" data-target="#companyBilling" aria-expanded="false" aria-controls="companyBilling">
                                <h5 class="text-white mb-0">Informasi Bank Perusahaan</h5>
                            </div>
                            <div id="companyBilling" class="collapse" aria-labelledby="headCompanyBilling" data-parent="#accordionGeneralSetting">
                                <?php $this->load->view(VIEW_BACK . 'setting/formgeneral/companybilling'); ?>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-header py-3 bg-gradient-primary" id="headRegFee" data-toggle="collapse" data-target="#regFee" aria-expanded="false" aria-controls="regFee">
                                <h5 class="text-white mb-0">Biaya Pendaftaran Agen</h5>
                            </div>
                            <div id="regFee" class="collapse" aria-labelledby="headRegFee" data-parent="#accordionGeneralSetting">
                                <?php $this->load->view(VIEW_BACK . 'setting/formgeneral/registrationfee'); ?>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-header py-3 bg-gradient-primary" id="headPoinPack" data-toggle="collapse" data-target="#colPoinPack" aria-expanded="false" aria-controls="colPoinPack">
                                <h5 class="text-white mb-0"><?php echo lang('point') .' '. lang('package') ?></h5>
                            </div>
                            <div id="colPoinPack" class="collapse" aria-labelledby="headPoinPack" data-parent="#accordionGeneralSetting">
                                <?php $this->load->view(VIEW_BACK . 'setting/formgeneral/pointpackage'); ?>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-header py-3 bg-gradient-primary" id="headPoinShare" data-toggle="collapse" data-target="#colPoinShare" aria-expanded="false" aria-controls="colPoinShare">
                                <h5 class="text-white mb-0">Konversi Poin Share</h5>
                            </div>
                            <div id="colPoinShare" class="collapse" aria-labelledby="headPoinShare" data-parent="#accordionGeneralSetting">
                                <?php $this->load->view(VIEW_BACK . 'setting/formgeneral/pointshare'); ?>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-header py-3 bg-gradient-primary" id="headFreeShipping" data-toggle="collapse" data-target="#colFreeShipping" aria-expanded="false" aria-controls="colFreeShipping">
                                <h5 class="text-white mb-0">Gratis Ongkir (Min Order Paket Produk)</h5>
                            </div>
                            <div id="colFreeShipping" class="collapse" aria-labelledby="headFreeShipping" data-parent="#accordionGeneralSetting">
                                <?php $this->load->view(VIEW_BACK . 'setting/formgeneral/freeshipping'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>