<?php $member_other_id = ddm_encrypt($member_other->id); ?>

<div class="table-container">
    <div class="table-actions-wrapper">        
        <div class="row">
            <div class="col-md-6">
                <h4><?php echo lang('agent'); ?> : 
                    <a href="<?php echo base_url('profile/' . $member_other_id); ?>" class="text-primary">
                        <strong><?php echo $member_other->name . ' ('. $member_other->username .')'; ?></strong>
                    </a>
                </h4>
            </div>
            <div class="col-md-6 text-right">                 
                <a href="<?php echo base_url('commission/bonus'); ?>" class="btn btn-sm btn-outline-warning"><i class="fa fa-step-backward"></i> <?php echo lang('back'); ?></a>
            </div>
        </div>  
    </div>
    <table class="table align-items-center table-flush" id="list_table_history_bonus" data-url="<?php echo base_url('commission/memberbonuslistdata/'.$member_other_id); ?>">
        <thead class="thead-light">
            <tr role="row" class="heading">
                <th scope="col" style="width: 10px">#</th>
                <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
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