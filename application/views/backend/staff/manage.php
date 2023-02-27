<!-- BEGIN Manage Staff -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="javascript:;"><?php echo lang('staff') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('staff_list'); ?></li>
                        </ol>
                    </nav>
                </div>
                <?php if ( $is_admin) { ?>
                    <div class="col-lg-6 col-5 text-right">
                        <a href="<?php echo base_url('staff/new') ?>" class="btn btn-sm btn-neutral"><i class="fa fa-plus mr-1"></i> <?php echo lang('menu_staff_add'); ?></a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Manage <?php echo lang('menu_staff') ?> </h3>
                        </div>
                    </div>
                </div>
                <div class="table-container">
                    <table class="table align-items-center table-flush" id="list_table_staff" data-url="<?php echo base_url('staff/managelist'); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px">#</th>
                                <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('name'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('access'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                            </tr>
                            <tr role="row" class="filter" style="background-color: #f6f9fc">
                                <td></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                                <td></td>
                                <td style="text-align: center;">
                                    <button class="btn btn-sm btn-block btn-outline-default btn-tooltip filter-submit" id="btn_list_table_staff" title="Search"><i class="fa fa-search"></i></button>
                                    <button class="btn btn-sm btn-block btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        </thead>
                        <tbody class="list">
                            <!-- Data Will Be Placed Here -->
                        </tbody>
                    </table>
                </div>
            
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_staff_reset_password" tabindex="-1" role="dialog" aria-labelledby="modal_staff_reset_password" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ni ni-key-25"></i> Reset Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form role="form" method="post" action="<?php echo base_url('staff/resetpassword'); ?>" id="form_staff_reset_password" class="form-horizontal">
                <input type="hidden" name="staff_id" id="staff_id">
                <div class="modal-body wrapper-form_staff_reset_password py-2">
                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label form-control-label"><?php echo lang('username'); ?> <span class="required">*</span></label>
                        <div class="col-md-9">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                </div>
                                <input type="text" name="staff_username" id="staff_username" class="form-control text-lowercase" autocomplete="off" disabled="" />
                            </div>
                        </div>
                    </div>

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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo lang('cancel'); ?></button>
                    <button type="submit" class="btn btn-primary" id="btn-staff-reset-password"><?php echo lang('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>