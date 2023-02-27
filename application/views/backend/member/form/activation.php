<?php
    $lock   = config_item('lock');
?>

<!-- BEGIN ACTIVATION -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Aktivasi Personal Sales</li>
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
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="mb-0">Formulir Aktivasi Personal Sales</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body wrapper-form-aps">
                            <?php if( $lock ): ?>
                            
                                <div class="alert alert-warning" role="alert">
                                    <h4 class="alert-heading"><i class="fa fa-bell"></i> This service is temporarily unavailabe</h4>
                                    <p class="mb-0">We are currently performing scheduled maintenance. Normal service will be restored soon. Thank you.</p>
                                </div>
                                
                            <?php else: ?>
                                <?php 
                                    $currency       = config_item('currency');
                                    $product_active = 0;
                                    if ( ! $is_admin ) {
                                        $product_active = $this->Model_Omzet_History->get_product_active($member->id);
                                    }
                                ?>
                                
                                <div class="row">
                                    <div class="col-xl-12 col-md-12">
                                        <div class="card bg-gradient-primary">
                                            <!-- Card body -->
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col">
                                                        <h5 class="card-title text-uppercase text-muted mb-0 text-white">Produk Aktif</h5>
                                                        <span class="h2 font-weight-bold mb-0 text-white"><?php echo $product_active; ?> Ltr</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="icon icon-shape bg-white text-dark rounded-circle shadow">
                                                            <i class="ni ni-bag-17"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if( $product_active > 0){ ?>
                                
                                    <?php echo form_open( 'productorder/activation', array( 'id'=>'personal-sales-activation', 'role'=>'form', 'class'=>'form-horizontal', 'data-id'=>ddm_encrypt($member->id), 'data-prodactive'=>$product_active ) ); ?>
                                        <div class="form-group row mb-2">
                                            <label class="col-md-3 col-form-label form-control-label">Username <span class="text-red">*</span></label>
                                            <div class="col-md-9">
                                                <input type="hidden" name="current_member_username" value="<?php echo $member->username; ?>" />
                                                <input type="hidden" name="current_member_name" value="<?php echo $member->name; ?>" />
                                                <div class="btn-group" data-toggle="buttons">
                                                    <label id="other_agent" class="btn aps active">
                                                        <input name="activationfor" class="toggle activationfor d-none" type="radio" value="other_agent" checked="checked" />Agen Lain
                                                    </label>
                                                    <label id="myself" class="btn aps">
                                                        <input name="activationfor" class="toggle activationfor d-none" type="radio" value="myself" />Pribadi
                                                    </label>
                                                </div>
                                                <div id="other_agent_form" style="margin-top: 5px;">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                                                        </div>
                                                        <input type="text" name="aps_member_username" id="aps_member_username" class="form-control text-lowercase" placeholder="Username Agent" autocomplete="off" />
                                                        <span class="input-group-append">
                                                            <button class="btn btn-primary" type="button" id="btn_search_aps_username" data-url="<?php echo base_url('member/searchagent'); ?>" >
                                                                <i class="fa fa-search"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="agent-info"></div>
                                        
                                        <div class="form-group row mb-2">
                                            <label class="col-md-3 col-form-label form-control-label">Jumlah Produk <span class="text-red">*</span><br />(<small>Kelipatan 15</small>)</label>
                                            <div class="col-md-3">
                                                <div class="input-group input-group-merge">
                                                    <div class="input-group-prepend">
                                                        <button class="btn btn-warning" type="button" id="btn_amount_minus">
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" name="aps_amount" id="aps_amount" class="form-control numbermask text-center" value="15" 
                                                    placeholder="Jumlah Produk Aktif" readonly="readonly" data-max="<?php echo $product_active; ?>" />
                                                    <div class="input-group-append">
                                                        <button class="btn btn-success" type="button" id="btn_amount_plus">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
    
                                        <hr class="mt-4 mb-4"/>
                                        <div class="row justify-content-center">
                                            <div class="col-lg-6">
                                                <button type="submit" class="btn btn-primary bg-gradient-primary" id="btn-personal-sales-activation">Aktivasi</button> 
                                                <button type="button" class="btn btn-danger bg-gradient-danger btn-personal-sales-activation-reset"><?php echo lang('reset'); ?></button>
                                            </div>
                                        </div>
                                    <?php echo form_close(); ?>
                                
                                <?php }else{ ?>
                                
                                    <div class="px-4 py-4 bg-gradient-warning text-white">
                                        Anda saat ini belum memiliki Produk Aktif untuk melakukan Aktivasi Personal Sales.<br />
                                        Silahkan Order Produk terlebih dahulu!<br />
                                        <a href="<?php echo base_url('shop');  ?>" class="btn btn-danger mt-2"><i class="ni ni-bag-17"></i> Order Produk</a>
                                    </div>
                                
                                <?php } ?>

                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BEGIN CONFIRMATION PERSONAL SALES ACTIVATION MODAL -->
<div class="modal fade" id="modal-save-aps" tabindex="-1" role="dialog" aria-labelledby="modal-save-aps" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-check"></i> KONFIRMASI AKTIVASI PERSONAL SALES</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah data Aktivasi Personal Sales sudah benar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                <button type="button" class="btn btn-primary" id="do_save_activation_personal_sales" data-formid="personal-sales-activation"><i class="fa fa-check"></i> <?php echo lang('continue'); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- END CONFIRMATION PERSONAL SALES ACTIVATION MODAL -->

<!-- BEGIN INFORMATION SUCCESS SAVE PERSONAL SALES ACTIVATION MODAL -->
<div class="modal fade" id="modal-success-save-aps" tabindex="-1" role="dialog" aria-labelledby="modal-success-save-aps" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-check"></i> AKTIVASI PERSONAL SALES SUKSES</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="note note-info" id="success_aps"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END INFORMATION SUCCESS SAVE PERSONAL SALES ACTIVATION MODAL -->