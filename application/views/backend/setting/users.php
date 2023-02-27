<section class="content-header">
    <h1><?php echo lang('menu_setting_user'); ?></h1>
    <ol class="breadcrumb">
        <li><a href="#"><?php echo lang('menu_setting'); ?></a></li>
        <li class="active"><?php echo lang('menu_setting_user'); ?></li>
    </ol>
</section>

<section class="content">
    <div class="box box-solid color-palette-box">
        <div class="box-body">
            <?php if( isset($alert_msg) && !empty($alert_msg) ): ?>
                <div class="alert alert-success hide-it"><?php echo $alert_msg; ?></div>         
            <?php endif; ?>
            <div class="table-container table-responsive">
                <div class="table-actions-wrapper">
                    <a href="<?php echo base_url('setting/users/create') ?>" class="btn btn-flat btn-sm bg-blue">
                        <i class="fa fa-plus"></i> <?php echo lang('create') ?>
                    </a>
                </div>
                <table class="table table-striped table-bordered table-advance table-hover" id="setting_users_list" data-url="<?php echo base_url('setting/userlistdata'); ?>">
                    <thead>
                        <tr role="row" class="heading">
                            <th class="width5 text-center">No</th>
                            <th class="width10 text-center">Username</th>
                            <th class="width20 text-center"><?php echo lang('name') ?></th>
                            <th class="width20 text-center"><?php echo lang('reg_email') ?></th>
                            <th class="width15 text-center"><?php echo lang('type') ?></th>
                            <th class="width10 text-center"><?php echo lang('status') ?></th>
                            <th class="width10 text-center"><?php echo lang('last_login'); ?></th>
                            <th class="width10 text-center"><?php echo lang('actions') ?></th>
                        </tr>
                        <tr role="row" class="filter">
                            <td></td>
                            <td><input type="text" class="form-control form-filter input-sm" name="search_username" /></td>
                            <td><input type="text" class="form-control form-filter input-sm" name="search_name" /></td>
                            <td><input type="text" class="form-control form-filter input-sm" name="search_email" /></td>
                            <td>
                                <select name="search_type" class="form-control form-filter input-sm">
                                    <option value=""><?php echo lang('select'); ?>...</option>
                                    <?php 
                                        if ( $cfg_status = config_item('user_type') ) {
                                            foreach ($cfg_status as $cfg => $row) {
                                                echo '<option value="'. $cfg .'" '. $selected .'>'. $row .'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select name="search_status" class="form-control form-filter input-sm">
                                    <option value=""><?php echo lang('select'); ?>...</option>
                                    <option value="active">Active</option>
                                    <option value="nonactive">Non-Active</option>
                                    <option value="banned">Banned</option>
                                </select>
                            </td>
                            <td></td>
                            <td style="text-align: center;">
                                <button class="btn btn-sm btn-flat bg-blue btn-tooltip filter-submit" id="btn_setting_users_list" title="Search"><i class="fa fa-search"></i></button> <button class="btn btn-sm btn-flat btn-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</section>