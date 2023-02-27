<?php
    // $url        = 'member/register/save';
    $url        = 'member/memberreg';
    $form       = 'member/form/registerform';
    $formid     = 'member_register';
    $title_page = 'Member';
    $lock       = config_item('lock'); 

?>

<!-- BEGIN TREE -->
<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <!-- Tree Diagram -->
            <div class="box box-solid">
    			<div class="box-body">
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-8">
                            <h3 style="margin-top: 0;">
                                <small>
                                <?php if( !empty($member_other) ): ?>
                                    <?php if( $member_other->type == MEMBER || ($member_other->id == $member->id || !$is_down) ): ?>
                                        <?php echo lang('my_tree_network'); ?>
                                    <?php else: ?>
                                        <?php echo lang('member_tree_network'); ?>:
                                        <a href="<?php echo base_url('profile/' . $member_other->id); ?>">
                                            <strong><?php echo $member_other->name . ' ('. $member_other->username .')'; ?></strong>
                                        </a>
                                    <?php endif ?>
                                <?php else: ?>
                                    <?php echo lang('my_tree_network'); ?>
                                <?php endif ?>
                                </small>
                            </h3>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input class="form-control" type="text" name="search_tree_username" id="search_tree_username" placeholder="<?php echo lang('search_member_username'); ?> ..." />
                                <span class="input-group-btn">
                                    <button class="btn bg-blue" id="btn_search_tree" data-url="<?php echo base_url('member/searchtree'); ?>">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-12"><div style="border-bottom: 1px solid #DDD; height: 15px;"></div></div>
                    </div>

                    <?php include "treediagram.php"; ?>
                </div>
            </div>

            <!-- BEGIN FORM TREE REGISTER -->
            <div class="display-hide" id="tree_register">

                <?php if( $lock ): ?>
                    <div class="callout callout-warning">
                        <h4 class="block" style="color: white"><strong>This service is temporarily unavailabe</strong></h4>
                        <p style="color: white">We are currently performing scheduled maintenance. Normal service will be restored soon. Thank you.</p>
                    </div>
                <?php else: ?>

                    <?php if($is_admin): ?>

                        <!-- Begin Register Form -->
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?php echo lang('reg_member_formulir'); ?></h3>
                            </div>

                            <?php echo form_open( $url, array( 'id'=>$formid, 'role'=>'form', 'data-val'=>'tree', 'data-url'=>current_url(), 'class'=>'form-horizontal', ' autocomplete' => 'off', 'data-access'=>'admin', 'data-id'=>$member->id ) ); ?>
                            <div class="box-body register_body_wrapper">
                                <!-- Alert Message -->
                                <div id="alert" class="alert display-hide"></div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('package'); ?> <span class="required">*</span></label>
                                    <div class="col-md-7">
                                        <select class="form-control" name="reg_member_package" id="reg_member_package">
                                            <option value=""><?php echo lang('select'); ?></option>
                                            <?php
                                                $packages = tfy_packages();
                                                if( !empty($packages) ){
                                                    foreach($packages as $p){
                                                        echo '<option value="'.$p->package.'" omzet="'.$p->omzet.'">'. $p->package_name .'</option>';
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Omzet &nbsp;&nbsp;</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input type="text" name="reg_member_package_omzet" id="reg_member_package_omzet" class="form-control" placeholder="0" disabled="" />
                                            <span class="input-group-addon"><i class="fa fa-shopping-bag"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <hr>

                                <!-- Upline Information -->
                                <div id="upline_info" class="display-hide"></div>

                                <?php $this->load->view(VIEW_BACK . $form); ?>
                            </div>
                            <div class="box-footer">
                                <div class="row" style="padding-bottom: 20px">
                                    <div class="col-md-7 col-md-offset-3">
                                        <button type="submit" class="btn btn-flat bg-blue" id="btn-register"><?php echo lang('reg_register_member'); ?></button>
                                        <button type="button" class="btn btn-flat btn-danger btn-register-reset" data-form="tree"><?php echo lang('reset'); ?></button>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                        <!-- End Register Form -->

                    <?php else:  ?>

                        <!-- Begin Register Form -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?php echo lang('reg_member_formulir'); ?></h3>
                            </div>

                            <?php $pin = tfy_member_pin($member->id, 'active', true); ?>
                            <?php if( $pin == 0 ): ?>
                            
                                <div class="box_new_member">
                                    <div class="box-body">
                                        <div class="callout callout-warning">
                                            Maaf, Anda tidak memiliki <b>PIN Register</b>. Silahkan pesan <b>PIN Register</b> terlebih dahulu !
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <div class="row">
                                            <div class="col-md-10 col-md-offset-1">
                                                <a href="<?php echo base_url('pin/order'); ?>" class="btn btn-flat bg-blue"><i class="fa fa-cart-arrow-down"></i> <?php echo lang('menu_pin_order'); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                            <?php else: ?>

                                <?php echo form_open( $url, array( 'id'=>$formid, 'role'=>'form', 'data-val'=>'tree', 'data-id'=>$member->id, 'data-url'=>current_url(), 'class'=>'form-horizontal box_new_member', ' autocomplete' => 'off', 'data-access'=>'member' ) ); ?>
                                <div class="box-body register_body_wrapper">
                                    <!-- Alert Message -->
                                    <div id="alert" class="alert display-hide"></div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label"><?php echo lang('package'); ?> <span class="required">*</span></label>
                                        <div class="col-md-7">
                                            <select class="form-control" name="reg_member_package" id="reg_member_package">
                                                <option value=""><?php echo lang('select'); ?></option>
                                                <?php
                                                    $packages = tfy_packages();
                                                    if( !empty($packages) ){
                                                        foreach($packages as $p){
                                                            echo '<option value="'.$p->package.'" omzet="'.$p->omzet.'">'. $p->package_name .'</option>';
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Omzet &nbsp;&nbsp;</label>
                                        <div class="col-md-7">
                                            <div class="input-group">
                                                <input type="text" name="reg_member_package_omzet" id="reg_member_package_omzet" class="form-control" placeholder="0" disabled="" />
                                                <span class="input-group-addon"><i class="fa fa-shopping-bag"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">ID PIN <span class="required">*</span></label>
                                        <div class="col-md-7">
                                            <select class="form-control" name="reg_member_pin" id="reg_member_pin">
                                                <option value=""><?php echo lang('select'); ?></option>
                                            </select>
                                        </div>
                                    </div>

                                    <hr>
                                    
                                    <!-- Upline Information -->
                                    <div id="upline_info" class="display-hide"></div>

                                    <?php $this->load->view(VIEW_BACK . $form); ?>
                                </div>
                                <div class="box-footer">
                                    <div class="row" style="padding-bottom: 20px">
                                        <div class="col-md-7 col-md-offset-3">
                                            <button type="submit" class="btn btn-flat bg-blue" id="btn-register"><?php echo lang('reg_register_member'); ?></button>
                                            <button type="button" class="btn btn-flat btn-danger btn-register-reset" data-form="tree"><?php echo lang('reset'); ?></button>
                                        </div>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>

                            <?php endif ?>
                        </div>
                        <!-- End Register Form -->
                    <?php endif ?>

                <?php endif ?>

            </div>
            <!-- END FORM TREE REGISTER -->

        </div>
    </div>
</section>
<!-- END TREE -->

<?php include "registermodal.php"; ?>