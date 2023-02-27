<?php
    $url    = 'member/memberreg';
    $form   = 'member/form/registerform';
    $formid = 'member_register';
    $lock   = config_item('lock');
?>

<!-- BEGIN REGISTER -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_member') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_member_new') ?></li>
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
                                    <h3 class="mb-0"><?php echo lang('reg_member_formulir'); ?> </h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body wrapper-form-register pt-0">
                            <?php if( $lock ): ?>
                                <div class="alert alert-warning" role="alert">
                                    <h4 class="alert-heading"><i class="fa fa-bell"></i> This service is temporarily unavailabe</h4>
                                    <p class="mb-0">We are currently performing scheduled maintenance. Normal service will be restored soon. Thank you.</p>
                                </div>
                            <?php else: ?>
                                <?php 
                                    $access         = ($is_admin) ? 'admin' : 'member'; 
                                    $currency       = config_item('currency');
                                    $saldo          = 0; 
                                    $product_active = 0;
                                    if ( ! $is_admin ) {
                                        $saldo          = $this->Model_Bonus->get_ewallet_deposite($member->id);
                                        $product_active = $this->Model_Omzet_History->get_product_active($member->id);
                                    }
                                    
                                    // Get Product Packages
                                    $cfg_prod_packages  = '';
                                    if( $packages = $this->Model_Product->get_product_package() ){
                                        $cfg_prod_packages  = $packages;
                                    }

                                    $totalRow       = 0;
                                    $packages       = false;
                                    $get_products = shop_product_package(0, 0, ' AND %status% = 1', '%datecreated% DESC');
                                    if ( $get_products ) {
                                        $totalRow   = isset($get_products['total_row']) ? $get_products['total_row'] : 0;
                                        $packages   = isset($get_products['data']) ? $get_products['data'] : false;
                                    }

                                    $display_package = ( $is_admin ) ? true : true;
                                ?>
                                <?php echo form_open( $url, array( 'id'=>$formid, 'role'=>'form', 'class'=>'form-horizontal', 'data-access'=>$access, 'data-id'=>ddm_encrypt($member->id), 'data-deposite'=>$saldo, 'data-prodactive'=>$product_active ) ); ?>
                                    <!-- Alert Message -->
                                    <div id="alert" class="alert display-hide"></div>

                                    <div class="row justify-content-center">
                                        <div class="col-lg-10">
                                            <?php $this->load->view(VIEW_BACK . $form); ?>
                                            <?php if ( $display_package ) { ?>
                                                <hr class="mt-3 mb-3">
                                                <div class="form-group row mb-2">
                                                    <label class="col-md-3 col-form-label form-control-label" for="select_product"><?php echo lang('package').' '.lang('product'); ?> </label>
                                                    <div class="col-md-9">
                                                        <div class="input-group">
                                                            <select class="form-control" name="select_product_package" id="select_product_package" disabled="disabled" data-url="<?php echo base_url('general/packagedetails'); ?>">
                                                                <option value="">Pilih Paket Produk</option>
                                                                <?php 
                                                                    if ( $cfg_prod_packages ) { 
                                                                        foreach ($cfg_prod_packages as $key => $row) {
                                                                            echo '<option value="'.ddm_encrypt($row->id).'">'.$row->name.'</option>';
                                                                        } 
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="prod_package_details" style="display: none;"></div>

                                                <!--
                                                <div class="form-group row mb-2 payment_method">
                                                    <label class="col-md-3 col-form-label form-control-label">Kode Voucher</label>
                                                    <div class="col-md-9">
                                                        <div class="row">
                                                            <div class="col-8">
                                                                <input type="text" name="voucher" id="voucher" class="form-control text-uppercase" placeholder="Kode Voucher Diskon" data-url="<?php echo base_url('shop/applyDiscountRegAgent') ?>"/>
                                                            </div>
                                                            <div class="col">
                                                                <input type="text" name="discount" id="discount" class="form-control" value="0" readonly="" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                -->
                                                
                                                <div class="form-group row mb-2">
                                                    <label class="col-md-3 col-form-label form-control-label" for="select_courier"><?php echo lang('courier'); ?> </label>
                                                    <div class="col-md-9">
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fa fa-truck"></i></span>
                                                                    </div>
                                                                    <select class="form-control" name="select_courier" id="select_courier">
                                                                        <option value="" selected="">-- <?php echo lang('select').' '.lang('courier'); ?> --</option>
                                                                        <?php
                                                                            if ( $get_couriers = config_item('courier_free') ) {
                                                                                foreach ($get_couriers as $key => $row) {
                                                                                    echo '<option value="'. $row['code'] .'" >'. $row['name'] .'</option>';
                                                                                }   
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr class="mt-3 mb-3">
                                                <?php if ( ! $is_admin ) { ?>
                                                    <div class="form-group row mb-2 payment_method">
                                                        <label class="col-md-3 col-form-label form-control-label"><?php echo lang('payment_method'); ?></label>
                                                        <div class="col-md-9">
                                                            <div class="btn-group" data-toggle="buttons">
                                                                <label id="payment_transfer" class="btn active">
                                                                    <input name="payment_method" class="toggle paymentmethod d-none" type="radio" value="transfer" checked="checked" />Transfer
                                                                </label>
                                                                <?php if($saldo > 0){ ?>
                                                                <label id="payment_deposite" class="btn">
                                                                    <input name="payment_method" class="toggle paymentmethod d-none" type="radio" value="deposite" />
                                                                    <span class="mr-2"><?php echo lang('deposite_saldo'); ?></span>
                                                                    <span class="badge badge-primary" style="text-transform: capitalize; font-size: 14px">
                                                                        <?php echo ddm_accounting($saldo, config_item('currency')); ?>
                                                                    </span>
                                                                </label>
                                                                <?php } ?>
                                                                <?php if($product_active > 0){ ?>
                                                                <label id="payment_product" class="btn">
                                                                    <input name="payment_method" class="toggle paymentmethod d-none" type="radio" value="product" />
                                                                    <span class="mr-2"><?php echo lang('reg_product_active'); ?></span>
                                                                    <span class="badge badge-primary" style="text-transform: capitalize; font-size: 14px">
                                                                        <?php echo $product_active; ?> Liter
                                                                    </span>
                                                                </label>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr class="mt-3 mb-3">
                                                <?php } ?>
                                            <?php } ?>

                                            <div class="form-group row mb-1">
                                                <label class="col-md-3 col-form-label form-control-label">&nbsp;</label>
                                                <div class="col-md-9">
                                                    <div class="custom-control custom-checkbox mb-3">
                                                        <input type="checkbox" class="custom-control-input" name="reg_member_term" id="reg_member_term" value="1" <?php echo set_checkbox( 'reg_member_term', '1' ); ?>>
                                                        <label class="custom-control-label" for="reg_member_term" style="vertical-align: unset;">Saya Setuju Dengan Persayaratan Dan Kondisi Pendaftaran.</label>
                                                        <a href="javascript:;" class="term_condition">Term &amp; Condition</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="mt-0 mb-4"/>
                                    <div class="row justify-content-center">
                                        <div class="col-lg-5">
                                            <button type="submit" class="btn btn-primary bg-gradient-primary" id="btn-register"><?php echo lang('reg_register_member'); ?></button> 
                                            <button type="button" class="btn btn-danger bg-gradient-danger btn-register-reset"><?php echo lang('reset'); ?></button>
                                        </div>
                                    </div>
                                <?php echo form_close(); ?>
                                <?php include "registermodal.php"; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="term_condition_modal" tabindex="-1" role="dialog" aria-labelledby="term_condition_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ni ni-single-copy-04"></i> Term &amp; Condition <small class="text-primary"><?php echo COMPANY_NAME; ?></small>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-0">
                <?php 
                    if ( $term_conditions = config_item('term_conditions') ) {
                        echo "<ul>";
                        foreach ($term_conditions as $key => $value) {
                            echo '<li>'.$value.'</li>';
                        }
                        echo "</ul>";
                    }
                ?>
            </div>
        </div>
    </div>
</div>