<div class="nav-wrapper">
    <ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="tabs-icons-text" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="tabs-total_bonus-tab" data-toggle="tab" href="#tabs-total_bonus" role="tab" aria-controls="tabs-total_bonus" aria-selected="true"><i class="ni ni-chart-bar-32 mr-2"></i><?php echo lang('bonus_total_member'); ?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tabs-history_bonus-tab" data-toggle="tab" href="#tabs-history_bonus" role="tab" aria-controls="tabs-history_bonus" aria-selected="false"><i class="ni ni-time-alarm mr-2"></i><?php echo lang('history_bonus_member'); ?></a>
        </li>
    </ul>
</div>
<div class="card shadow">
    <div class="card-body">
        <div class="tab-content" id="bonusListContent">
            <div class="tab-pane fade show active" id="tabs-total_bonus" role="tabpanel" aria-labelledby="tabs-total_bonus-tab">
                <div class="table-container">
                    <table class="table align-items-center table-flush" id="list_table_total_bonus" data-url="<?php echo base_url('commission/totalbonuslistdata'); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px">#</th>
                                <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                                <th scope="col"><?php echo lang('name'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('total'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                            </tr>
                            <tr role="row" class="filter" style="background-color: #f6f9fc">
                                <td></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_max" placeholder="Max" />
                                </td>
                                <td style="text-align: center;">
                                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_total_bonus" title="Search"><i class="fa fa-search"></i></button>
                                    <button class="btn btn-sm btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        </thead>
                        <tbody class="list">
                            <!-- Data Will Be Placed Here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="tabs-history_bonus" role="tabpanel" aria-labelledby="tabs-history_bonus-tab">
                <div class="table-container">
                    <table class="table align-items-center table-flush" id="list_table_history_bonus" data-url="<?php echo base_url('commission/historybonuslistdata'); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px">#</th>
                                <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                                <th scope="col"><?php echo lang('name'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('nominal'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('type'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('information'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                            </tr>
                            <tr role="row" class="filter" style="background-color: #f6f9fc">
                                <td></td>
                                <td>
                                    <div class="input-group input-group-sm date date-picker mb-1" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control form-control-sm form-filter" readonly name="search_datecreated_min" placeholder="From" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                                        </span>
                                    </div>
                                    <div class="input-group input-group-sm date date-picker" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control form-control-sm form-filter" readonly name="search_datecreated_max" placeholder="To" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                                        </span>
                                    </div>
                                </td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_max" placeholder="Max" />
                                </td>
                                <td>
                                    <select name="search_type" class="form-control form-filter input-sm">
                                        <option value=""><?php echo lang('type'); ?>...</option>
                                        <?php
                                            $bonus_type = config_item('bonus_type');
                                            if( $bonus_type && !empty($bonus_type) ){
                                                foreach($bonus_type as $key => $val){
                                                    echo '<option value="'.$key.'">'.strtoupper($val).'</option>';
                                                }
                                            }
                                        ?>
                                    </select>
                                </td>
                                <td><input type="text" class="form-control form-filter input-sm" name="search_desc" /></td>
                                <td style="text-align: center;">
                                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_history_bonus" title="Search"><i class="fa fa-search"></i></button>
                                    <button class="btn btn-sm btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
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