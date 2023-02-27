<?php
    $wd_min         = get_option('setting_withdraw_minimal');
    $wd_min         = $wd_min ? $wd_min : 0;
    $wd_fee         = get_option('setting_withdraw_fee');
    $wd_fee         = $wd_fee ? $wd_fee : 0;
    $wd_tax_npwp    = get_option('setting_withdraw_tax_npwp');
    $wd_tax_npwp    = $wd_tax_npwp ? $wd_tax_npwp : 0;
    $wd_tax         = get_option('setting_withdraw_tax');
    $wd_tax         = $wd_tax ? $wd_tax : 0;
?>

<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_setting') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_setting_withdraw'); ?></li>
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
                <form  action="<?php echo base_url('setting/updatewithdraw'); ?>" method="post" class="form-horizontal" id="form-setting-wd">
                    <div class="card-body wrapper-setting-withdraw">
                        <div class="form-body top20 bottom20">
                            <div class="form-group row mb-2">
                                <label class="col-md-3 col-form-label form-control-label d-md-none">Minimal Withdraw <span class="required">*</span></label>
                                <label class="col-md-3 col-form-label form-control-label text-right d-none d-md-inline-block">Minimal Withdraw <span class="required">*</span></label>
                                <div class="col-md-4">
                                    <input type="text" name="wd_min" id="wd_min" class="form-control numbercurrency" placeholder="0" value="<?php echo $wd_min; ?>"/>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-md-3 col-form-label form-control-label d-md-none">Biaya Transfer <span class="required">*</span></label>
                                <label class="col-md-3 col-form-label form-control-label text-right d-none d-md-inline-block">Biaya Transfer <span class="required">*</span></label>
                                <div class="col-md-4">
                                    <input type="text" name="wd_fee" id="wd_fee" class="form-control numbercurrency" placeholder="0" value="<?php echo $wd_fee; ?>"/>
                                </div>
                            </div>
                            <!-- <div class="form-group row mb-2">
                                <label class="col-md-3 col-form-label form-control-label">Pajak NPWP <span class="required">*</span></label>
                                <div class="col-md-3">
                                    <input type="text" name="wd_tax_npwp" id="wd_tax_npwp" class="form-control numberpercent" placeholder="0" value="<?php echo $wd_tax_npwp; ?>"/>
                                </div>
                                <div class="col-md-6">
                                    <p class="help-block">Dalam bentuk persentase ( % )</p>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-md-3 col-form-label form-control-label">Pajak Non NPWP <span class="required">*</span></label>
                                <div class="col-md-3">
                                    <input type="text" name="wd_tax" id="wd_tax" class="form-control numberpercent" placeholder="0" value="<?php echo $wd_tax; ?>"/>
                                </div>
                                <div class="col-md-6">
                                    <p class="help-block">Dalam bentuk persentase ( % )</p>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <div class="card-footer my-0">
                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-primary my-0"><?php echo lang('save') . ' ' . lang('menu_setting'); ?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
