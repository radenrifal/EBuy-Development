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
                            <li class="breadcrumb-item active" aria-current="page">Intro</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-12">
            <div class="row justify-content-center">
                <div class="col-lg-12 card-wrapper">
                    <div class="card">
                        <div class="card-body wrapper-form-product pt-0">
                            <div class="row">
                                <div class="col-xl-8 order-xl-2 pt-3">
                                    <h5 class="heading-small">List Intro</h5>
                                    <div class="table-container">
                                        <table class="table align-items-center table-flush" id="list_table_setting_intro" data-url="<?php echo base_url('setting/introlistdata'); ?>">
                                            <thead class="thead-light">
                                                <tr role="row" class="heading">
                                                    <th class="text-center" style="width: 25px;">No</th>
                                                    <th class="text-center" style="width: 60%;">Image</th>
                                                    <th class="text-right">
                                                        <?php echo lang('actions') ?>
                                                        <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit ml-2" id="btn_list_table_setting_intro" title="Search"><i class="fa fa-search"></i></button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-xl-4 order-xl-1 bg-secondary py-2">
                                    <?php $file_src = ASSET_PATH . 'backend/img/no_image.jpg'; ?>
                                    <form role="form" method="post" action="<?php echo base_url('setting/saveintro'); ?>" id="form-intro" class="form-horizontal" data-default="<?php echo $file_src ?>">
                                        <div class="form-group">
                                            <div class="thumbnail mb-1">
                                                <img class="img-thumbnail" id="product_img_thumbnail" width="100%" src="<?php echo $file_src; ?>" style="cursor: pointer;">
                                                <div class="caption">
                                                    <p class="text-muted mb-0" style="font-size: 14px">Image ( jpg, jpeg, png ) and Max 2 MB</p>
                                                    <div class="img-information" style="display: none;">
                                                        <i class="ni ni-album-2 mr-1" id="type_img_thumbnail"></i> 
                                                        <span id="size_img_thumbnail"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="file" name="product_file" id="product_file" class="form-control file-image" accept="image/x-png,image/jpeg">
                                        </div>
                                        <hr class="my-4" />
                                        <div class="text-center">
                                            <button type="button" class="btn btn-primary btn-save-intro my-2"><?php echo lang('save'); ?> Intro</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
