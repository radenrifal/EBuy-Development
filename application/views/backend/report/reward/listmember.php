<div class="card">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Reward Saya </h3>
            </div>
        </div>
    </div>
    <div class="table-container">
        <table class="table align-items-center table-flush" id="list_table_reward" data-url="<?php echo base_url('member/rewardlistdata'); ?>">
            <thead class="thead-light">
                <tr role="row" class="heading">
                    <th scope="col" style="width: 10px">#</th>
                    <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                    <th scope="col" class="text-center">Reward</th>
                    <th scope="col" class="text-center"><?php echo lang('nominal'); ?></th>
                    <th scope="col" class="text-center"><?php echo lang('status'); ?></th>
                    <th scope="col" class="text-center"><?php echo lang('confirm_date'); ?></th>
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
                    <td>
                        <select name="search_reward" class="form-control form-control-sm form-filter">
                            <option value=""><?php echo lang('select'); ?>...</option>
                            <?php
                                if( $cfg_reward = $this->Model_Option->get_all_reward_data() ){
                                    foreach($cfg_reward as $key => $row){
                                        echo '<option value="'.$row->id.'">'.$row->reward.'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </td>
                    <td>
                        <div class="mb-1">
                            <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_min" placeholder="Min" />
                        </div>
                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_max" placeholder="Max" />
                    </td>
                    <td>
                        <select name="search_status" class="form-control form-control-sm form-filter">
                            <option value=""><?php echo lang('select'); ?>...</option>
                            <option value="pending">PENDING</option>
                            <option value="confirmed">CONFIRMED</option>
                        </select>
                    </td>
                    <td>
                        <div class="input-group input-group-sm date date-picker mb-1" data-date-format="yyyy-mm-dd">
                            <input type="text" class="form-control form-control-sm form-filter" readonly name="search_dateconfirm_min" placeholder="From" />
                            <span class="input-group-btn">
                                <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                            </span>
                        </div>
                        <div class="input-group input-group-sm date date-picker" data-date-format="yyyy-mm-dd">
                            <input type="text" class="form-control form-control-sm form-filter" readonly name="search_dateconfirm_max" placeholder="To" />
                            <span class="input-group-btn">
                                <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                            </span>
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_reward" title="Search"><i class="fa fa-search"></i></button>
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

<?php
    $cfg_reward     = $this->Model_Option->get_all_reward_data();
    $currency       = config_item('currency');
?>
<div class="card">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0"><i class="fa fa-info"></i> Informasi Reward</h3>
            </div>
        </div>
    </div>
    <div class="table-container">
        <table class="table align-items-center table-flush" id="list_table_setting_reward" data-url="<?php echo base_url('setting/rewardlistdata'); ?>">
            <thead class="thead-light">
                <tr role="row" class="heading">
                    <th class="text-center" style="width: 10px">No</th>
                    <th class="text-center">Reward</th>
                    <th class="text-right">Nominal Reward</th>
                    <th class="text-right"><?php echo lang('point') ?></th>
                    <th class="text-center"><?php echo lang('period') ?></th>
                </tr>
            </thead>
            <tbody>
            <?php 
                if ( $cfg_reward ) :
                    $no = 1;
                    foreach ($cfg_reward as $key => $row) : 
                        if ( $row->is_active == 0 ) { continue; }
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $no; ?></td>
                            <td><span class="h5 text-primary font-weight-bold"><?php echo $row->reward; ?></span></td>
                            <td class="text-right"><?php echo ddm_accounting($row->nominal, $currency); ?></td>
                            <td class="text-right"><?php echo ddm_accounting($row->point); ?></td>
                            <td class="text-center">
                                <?php 
                                    $period     = '<span class="btn btn-sm btn-outline-default d-block mb-2">Periode Reward</span>';
                                    $period    .= 'Start : '. date('d-M-Y', strtotime($row->start_date)) .br();
                                    $period    .= 'End : '. date('d-M-Y', strtotime($row->end_date));
                                    if ( $row->is_lifetime > 0 ) {
                                        $period = '<span class="btn btn-sm btn-outline-success">Lifetime Reward</span>';
                                    }
                                    echo $period;
                                ?>
                            </td>
                        </tr>
                <?php $no++; endforeach; 
                else:
                endif;
            ?>
            </tbody>
        </table>
    </div>
</div>