<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_setting') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_setting_reward'); ?></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="<?php echo base_url('setting/reward/create'); ?>" class="btn btn-sm btn-neutral">
                        <i class="fa fa-plus mr-1"></i> <?php echo lang('add') .' '. lang('menu_setting_reward'); ?>
                    </a>
                </div>
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
                            <h3 class="mb-0"><?php echo lang('menu_setting_reward'); ?> </h3>
                        </div>
                    </div>
                </div>
                <div class="table-container">
                    <table class="table align-items-center table-flush" id="list_table_setting_reward" data-url="<?php echo base_url('setting/rewardlistdata'); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th class="width5 text-center">No</th>
                                <th class="width25 text-center">Reward</th>
                                <th class="width15 text-center">Nominal Reward</th>
                                <th class="width10 text-center"><?php echo lang('point') ?></th>
                                <th class="width10 text-center"><?php echo lang('period') ?></th>
                                <th class="width10 text-center"><?php echo lang('status') ?></th>
                                <th class="width10 text-center"><?php echo lang('actions') ?></th>
                            </tr>
                            <tr role="row" class="filter">
                                <td></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_reward" /></td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter numbermask text-right" name="search_nominal_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter numbermask text-right" name="search_nominal_max" placeholder="Max" />
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter numbermask text-right" name="search_point_left_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter numbermask text-right" name="search_point_left_max" placeholder="Max" />
                                </td>
                                <td>
                                    <select name="search_period" class="form-control form-control-sm form-filter">
                                        <option value=""><?php echo lang('select'); ?>...</option>
                                        <option value="lifetime">Lifetime Reward</option>
                                        <option value="period">Periode Reward</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="search_status" class="form-control form-control-sm form-filter">
                                        <option value=""><?php echo lang('select'); ?>...</option>
                                        <option value="active">AKTIIF</option>
                                        <option value="notactive">TIDAK AKTIF</option>
                                    </select>
                                </td>
                                <td style="text-align: center;">
                                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_setting_reward" title="Search"><i class="fa fa-search"></i></button>
                                    <button class="btn btn-sm btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
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