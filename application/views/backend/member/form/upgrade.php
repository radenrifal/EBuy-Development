<?php 
    $lock       = config_item('lock'); 
    $pin        = tfy_member_pin($member->id, 'active', true);
?>

<!-- BEGIN Upgrade Package (UP) -->
<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">

            <?php if( $lock ): ?>

                <div class="callout callout-warning">
                    <h4 class="block" style="color: white"><strong>This service is temporarily unavailabe</strong></h4>
                    <p style="color: white">We are currently performing scheduled maintenance. Normal service will be restored soon. Thank you.</p>
                </div>

            <?php else: ?>
        
                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo lang('menu_member_upgrade'); ?></h3>
                    </div>
                    
                    <?php if( $pin == 0 ): 
                            $msg = 'Maaf, Anda tidak memiliki PIN. Silahkan lakukan pemesanan PIN terlebih dahulu!';
                            if ( $member->as_stockist ) {
                                $msg = 'Maaf, Anda tidak memiliki PIN. Silahkan lakukan pemesanan PIN terlebih dahulu!';
                            }
                        ?>
                            
                        <div class="box-body upgrade_member_wrapper">
                            <div class="box-body">
                                <div class="callout callout-warning">
                                    Maaf, Anda tidak memiliki <b>PIN</b>. Silahkan pesan <b>PIN</b> terlebih dahulu !
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
                        
                        <form action="<?php echo base_url('member/memberupgrade'); ?>" role="form" method="post" class="form-horizontal" id="upgrade_member_form" data-registrar="<?php echo $member->username ?>" data-id="<?php echo $member->id ?>" data-access="member" >
                            <div class="box-body upgrade_member_wrapper">
                                <div id="alert" class="alert display-hide"></div>
                                <!-- Update me -->
                                <div class="form-group top20">
                                    <label class="col-md-3 control-label"></label>
                                    <div class="col-md-9">
                                        <div class="btn-group" data-toggle="buttons">
                                            <label id="up_me" class="btn btn-flat bg-blue up-opt-toggle active">
                                                <input name="upopt" class="toggle" type="radio" value="me" checked="checked"><i class="fa fa-hand-o-up"></i> Upgrade Package Saya
                                            </label>
                                            <label id="up_others" class="btn btn-flat bg-gray up-opt-toggle">
                                                <input name="upopt" class="toggle" type="radio" value="others"><i class="fa fa-hand-o-right"></i> Upgrade Package Member Lain
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="up-opt up-opt-others top20" style="display: none;">
                                    <!-- Username -->
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Username <span class="required">*</span></label>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input type="text" name="up_member_username" id="up_member_username" class="form-control" placeholder="Username Member lainnya" autocomplete="off" value="" />
                                                <span class="input-group-btn">
                                                    <button class="btn btn-flat btn-primary" type="button" id="btn_search_member_upgrade"><i class="fa fa-search"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr><h4 class="form-section top30">Informasi Member</h4><hr class="top0">
                                <!-- Username -->
                                <div class="form-group">
                                    <input type="hidden" name="up_member_id" id="up_member_id" class="form-control" />
                                    <label class="col-md-3 control-label">Username</label>
                                    <div class="col-md-6">
                                        <input type="text" name="up_username" id="up_username" class="form-control up_info" disabled="" />
                                    </div>
                                </div>
                                <!-- Name -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('name'); ?></label>
                                    <div class="col-md-6">
                                        <input type="text" name="up_name" id="up_name" class="form-control up_info" disabled="" />
                                    </div>
                                </div>
                                <!-- Package -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('package'); ?></label>
                                    <div class="col-md-6">
                                        <input type="text" name="up_package" id="up_package" class="form-control up_info" disabled="" />
                                    </div>
                                </div>

                                <hr><h4 class="form-section top30">Informasi Upgrade</h4><hr class="top0">
                                <!-- Name -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('package'); ?> (Upgrade) <span class="required">*</span></label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="up_member_package" id="up_member_package"></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">ID PIN <span class="required">*</span></label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="up_member_pin" id="up_member_pin">
                                            <option value=""><?php echo lang('select'); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="box-footer" style="background: #f5f5f5; padding: 20px 0px">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn btn-flat bg-blue" id="btn-upgrade">Submit</button> 
                                        <button type="reset" class="btn btn-flat btn-danger btn-ps-reset">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                    <?php endif ?>
                </div>
                            
            <?php endif ?>
        </div>
    </div>
</section>
<!-- END Upgrade Package (UP) -->

<!-- BEGIN INFORMATION SUCCESS SAVE UPGRADE MODAL -->
<div class="modal fade" id="success_save_upg_member" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h5 class="modal-title"><?php echo lang('reg_upgrade_success'); ?></h5>
            </div>
            <div class="modal-body">
                <div class="note note-info" id="success_upg_member"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-flat btn-sm btn-success" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END INFORMATION SUCCESS SAVE UPGRADE MODAL -->