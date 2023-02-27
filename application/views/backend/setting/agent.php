<section class="content-header">
    <h1><?php echo lang('menu_setting_agent'); ?></h1>
    <ol class="breadcrumb">
        <li><a href="#"><?php echo lang('menu_setting'); ?></a></li>
        <li class="active"><?php echo lang('menu_setting_agent'); ?></li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-default color-palette-box">
                <div class="box-body skpd_wrapper">
                    <?php if( isset($alert_msg) && !empty($alert_msg) ): ?>
                        <div class="alert alert-success hide-it"><?php echo $alert_msg; ?></div>         
                    <?php endif; ?>
                    <div class="table-container table-responsive">
                        <div class="table-actions-wrapper">
                            <a href="<?php echo base_url('master/skpd/create') ?>" class="btn btn-flat btn-sm bg-blue">
                                <i class="fa fa-plus"></i> <?php echo lang('create') ?> SKPD
                            </a>
                        </div>
                        <table class="table table-striped table-bordered table-advance table-hover" id="skpd_list" data-url="<?php echo base_url('master/skpdlistdata'); ?>">
                            <thead>
        						<tr role="row" class="heading">
        							<th class="width5 text-center">No</th>
                                    <th class="width25 text-center">SKPD</th>
                                    <th class="width15 text-center">alias</th>
                                    <th class="width15 text-center"><?php echo lang('type'); ?></th>
                                    <th class="width20 text-center"><?php echo lang('head_departement'); ?></th>
                                    <th class="width10 text-center"><?php echo lang('status'); ?></th>
        							<th class="width10 text-center"><?php echo lang('actions'); ?></th>
        						</tr>
                                <tr role="row" class="filter">
        							<td></td>
                                    <td><input type="text" class="form-control form-filter input-sm text-uppercase" name="search_skpd" /></td>
                                    <td><input type="text" class="form-control form-filter input-sm" name="search_slug" /></td>
                                    <td>
                                        <select name="search_type" class="form-control form-filter input-sm">
                                            <option value=""><?php echo lang('select'); ?>...</option>
                                            <?php 
                                                $skpd_type  = config_item('skpd_type');
                                                if ( $skpd_type ) {
                                                    foreach ($skpd_type as $skpd => $name) {
                                                        echo '<option value="'.$skpd.'">'.$name.'</option>';
                                                    } 
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control form-filter input-sm" name="search_head" /></td>
                                    <td>
                                        <select name="search_status" class="form-control form-filter input-sm">
                                            <option value=""><?php echo lang('select'); ?>...</option>
                                            <option value="active">Aktif</option>
                                            <option value="notactive">Tidak Aktif</option>
                                        </select>
                                    </td>
                                    <td style="text-align: center;">
                                        <button class="btn btn-sm btn-flat bg-blue filter-submit btn-tooltip" title="Search" id="btn_skpd_list"><i class="fa fa-search"></i></button>
                                        <button class="btn btn-sm btn-flat btn-warning filter-cancel btn-tooltip" title="Reset"><i class="fa fa-times"></i></button>
        							</td>
        						</tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>  
        </div>
    </div>
</section>