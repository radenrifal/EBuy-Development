<div class="header bg-secondary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-light">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_setting_staff') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo ! empty( $staff->id ) ? 'Edit Staff' : 'Tambah Staff' ?></li>
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
                                    <h3 class="mb-0">Form <?php echo lang('menu_setting_staff'); ?></h3>
                                </div>
                                <div class="col text-right">
                                    <a href="<?php echo base_url('staff') ?>" class="btn btn-sm btn-danger"><span class="fa fa-history"></span> Kembali</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body wrapper-form-staff">
                        	<?php 
                                $form_action = base_url('staff/savestaff');
                                $form_input  = 'create';
                                if ( isset($staff->id) && !empty($staff->id) ) {
	                                $form_input  = 'edit';
                                    $form_action .= '/'. ddm_encrypt($staff->id);
                                }
                            ?>
                            <form role="form" method="post" action="<?php echo $form_action; ?>" id="form-staff" class="form-horizontal">
                            	<div class="row justify-content-center">
                                    <div class="col-md-10 col-sm-12">
                                        <div class="form-group row mb-2">
                                            <label class="col-md-3 col-form-label form-control-label"><?php echo lang('username'); ?> <span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <div class="input-group input-group-merge">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control text-lowercase" name="staff_username" id="staff_username" data-url="<?php echo base_url('member/checkusernamestaff'); ?>" placeholder="<?php echo lang('username'); ?>" value="<?php echo ddm_isset( $staff->username, '', '' ) ?>" <?php echo ( $form_input == 'edit' ) ? 'disabled="disabled"' : ''; ?> />
                                                </div>
                                            </div>
                                        </div>

										<?php if ( $form_input == 'create' ) { ?>
						                    <div class="form-group row mb-2">
						                        <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_password'); ?> <span class="required">*</span></label>
						                        <div class="col-md-9">
						                            <div class="input-group input-group-merge">
						                                <div class="input-group-prepend">
						                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
						                                </div>
						                                <input type="password" name="staff_password" id="staff_password" class="form-control" placeholder="<?php echo lang('reg_valid_password'); ?>" autocomplete="off" value="" />
						                                <div class="input-group-append">
						                                    <button class="btn btn-default pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
						                                </div>
						                            </div>
						                        </div>
						                    </div>

						                    <div class="form-group row mb-2">
						                        <label class="col-md-3 col-form-label form-control-label">Confirm password <span class="required">*</span></label>
						                        <div class="col-md-9">
						                            <div class="input-group input-group-merge">
						                                <div class="input-group-prepend">
						                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
						                                </div>
						                                <input type="password" name="staff_password_confirm" id="staff_password_confirm" class="form-control" placeholder="Konfirmasi Password" autocomplete="off" value="" />
						                                <div class="input-group-append">
						                                    <button class="btn btn-default pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
						                                </div>
						                            </div>
						                        </div>
						                    </div>
										<?php } ?>

										<!-- Name -->
                                        <div class="form-group row mb-2">
                                            <label class="col-md-3 col-form-label form-control-label"><?php echo lang('name'); ?> <span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <div class="input-group input-group-merge">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control text-uppercase" name="staff_name" id="staff_name" placeholder="<?php echo lang('name'); ?>" value="<?php echo ddm_isset( $staff->name, '', '' ) ?>" />
                                                </div>
                                            </div>
                                        </div>

										<!-- No. Telp/HP -->
										<div class="form-group row mb-2">
										    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_no_telp'); ?> <span class="required">*</span></label>
										    <div class="col-md-9">
										        <div class="input-group input-group-merge">
										            <div class="input-group-prepend">
										                <span class="input-group-text">+62</span>
										            </div>
										            <input type="text" name="staff_phone" id="staff_phone" class="form-control numbermask phonenumber" placeholder="8xxxxxxxxx" data-url="<?php echo base_url('member/checkphone'); ?>" value="<?php echo ddm_isset( $staff->phone, '', '' ) ?>" />
										            <div class="input-group-append">
										                <span class="input-group-text"><i class="fa fa-phone"></i></span>
										            </div>
										        </div>
										    </div>
										</div>  

										<!-- Email -->
										<div class="form-group row mb-2">
										    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_email'); ?> <span class="required">*</span></label>
										    <div class="col-md-9">
										        <div class="input-group input-group-merge">
										            <div class="input-group-prepend">
										                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
										            </div>
										            <input type="text" name="staff_email" id="staff_email" class="form-control text-lowercase" placeholder="<?php echo lang('reg_email'); ?>" data-url="<?php echo base_url('member/checkemail'); ?>" value="<?php echo ddm_isset( $staff->email, '', '' ) ?>" />
										        </div>
										        <p>Pastikan Email Anda valid dan aktif. Password akan dikirim ke email Anda.</p>
										    </div>
										</div>

										<hr>

									    <div class="form-group row">
									        <label class="col-md-3 col-form-label form-control-label"><?php echo lang('access'); ?> <span class="required">*</span></label>
									        <div class="col-md-9">
												<div class="btn-group" data-toggle="buttons">
										            <label id="staff_all" class="btn btn-flat btn-primary staff-access-toggle <?php echo ddm_isset( $staff->access, 'all' ) == 'all' ? 'active' : ''; ?>">
										            	<input name="staff_access" class="toggle" type="radio" value="all" 
										            		<?php echo ddm_isset( $staff->access, 'all' ) == 'all' ? 'checked="checked"' : ''; ?>>
										            		<i class="fa fa-unlock-alt"></i> Semua Fitur
										        	</label>
										            <label id="staff_not_all" class="btn btn-flat btn-primary staff-access-toggle <?php echo ddm_isset( $staff->access, 'all' ) == 'partial' ? 'active' : ''; ?>">
										            	<input name="staff_access" class="toggle" type="radio" value="partial"
										            		<?php echo ddm_isset( $staff->access, 'all' ) == 'partial' ? 'checked="checked"' : ''; ?>>
										            		<i class="fa fa-lock"></i> Fitur Tertentu
										        	</label>
										        </div>
									        </div>
									    </div>

										<!-- particular features access -->
										<div class="form-group row mb-2 staff-access-box staff-access-box-partial">
											<div class="col-md-3"></div>
											<div class="col-md-9">
												<?php for ( $i = 1; $i <= 9; $i++ ) { ?>
												<?php $access_id = constant( 'STAFF_ACCESS' . $i ); ?>
												<?php if ( in_array( $access_id, array( STAFF_ACCESS8, STAFF_ACCESS9 ) ) ) continue; ?>
                                                <div class="custom-control custom-checkbox mb-3">
                                                    <input type="checkbox" class="custom-control-input" id="staff_access_partial_<?php echo $access_id; ?>" name="staff_access_partial[]" value="<?php echo $access_id; ?>" <?php echo ddm_isset( $staff->role, '', '', false, false ) && is_array( $staff->role ) && in_array( $access_id, $staff->role ) ? 'checked="checked"' : ''; ?> />
                                                    <label class="custom-control-label" for="staff_access_partial_<?php echo $access_id; ?>" style="vertical-align: unset;">
                                                    	<?php echo $config[ $access_id ]; ?>
                                                    </label>
                                                </div>
											  	<?php } ?>
											</div>
										</div>

										<!-- all features access -->
										<div class="form-group row mb-2 staff-access-box staff-access-box-all">
											<div class="col-md-3"></div>
											<div class="col-md-9">
												<?php for ( $i = 8; $i <= 9; $i++ ) { ?>
												<?php $access_id = constant( 'STAFF_ACCESS' . $i ); ?>
												<?php if ( !in_array( $access_id, array( STAFF_ACCESS8, STAFF_ACCESS9 ) ) ) continue; ?>
                                                <div class="custom-control custom-checkbox mb-3">
                                                    <input type="checkbox" class="custom-control-input" id="staff_access_all_<?php echo $access_id; ?>" name="staff_access_all[]" value="<?php echo $access_id; ?>" <?php echo ddm_isset( $staff->role, '', '', false, false ) && is_array( $staff->role ) && in_array( $access_id, $staff->role ) ? 'checked="checked"' : ''; ?> />
                                                    <label class="custom-control-label" for="staff_access_all_<?php echo $access_id; ?>" style="vertical-align: unset;">
                                                    	<?php echo 'Termasuk ' . $config[ $access_id ]; ?>
                                                    </label>
                                                </div>
											  	<?php } ?>
											</div>
										</div>
                                    </div>
                                </div>
                                <hr class="my-4" />
                                <div class="text-center">
                                    <button type="submit" class="btn btn-default bg-gradient-default my-2">
                                        <?php echo lang('save') .' '. lang('menu_setting_staff'); ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>