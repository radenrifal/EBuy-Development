<div class="table-container">
    <div class="table-actions-wrapper table-group-actions text-right">
        <button class="btn btn-sm btn-success text-white table-export-excel">
            <i class="fa fa-file-excel"></i> <span class="hidden-480">Export ke Excel</span>
        </button>
    </div>
    <table class="table align-items-center table-flush" id="list_table_withdraw" data-url="<?php echo base_url('commission/withdrawlistdata'); ?>">
        <thead class="thead-light">
            <tr role="row" class="heading">
                <th scope="col" style="width: 10px">#</th>
                <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                <th scope="col" class="text-center"><?php echo lang('bank'); ?></th>
                <th scope="col" class="text-center"><?php echo lang('bank_account'); ?></th>
                <th scope="col" class="text-center"><?php echo lang('nominal'); ?></th>
                <th scope="col" class="text-center"><?php echo lang('status'); ?></th>
                <th scope="col" class="text-center"><?php echo lang('information'); ?></th>
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
                    <select class="form-control form-control-sm form-filter" name="search_bank">
                        <option value=""><?php echo lang('select'); ?>...</option>
                        <?php
                            if( $banks = ddm_banks() ){
                                foreach($banks as $b){
                                    echo '<option value="'.$b->id.'">'.$b->kode.' - '.$b->nama.'</option>';
                                }
                            }
                        ?>    
                    </select>
                </td>
                <td>
                    <div class="bottom5">
                        <input type="text" class="form-control form-control-sm form-filter numbermask" name="search_bill" placeholder="<?php echo lang('no_rekening'); ?>" />
                    </div>
                    <input type="text" class="form-control form-control-sm form-filter" name="search_bill_name" placeholder="<?php echo lang('pemilik_rek'); ?>" />
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
                        <option value="transfered">TRANSFERED</option>
                    </select>
                </td>
                <td></td>
                <td>
                    <div class="input-group input-group-sm date date-picker mb-1" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control form-control-sm form-filter" readonly name="search_datemodified_min" placeholder="From" />
                        <span class="input-group-btn">
                            <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                        </span>
                    </div>
                    <div class="input-group input-group-sm date date-picker" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control form-control-sm form-filter" readonly name="search_datemodified_max" placeholder="To" />
                        <span class="input-group-btn">
                            <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                        </span>
                    </div>
                </td>
                <td style="text-align: center;">
                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_withdraw" title="Search"><i class="fa fa-search"></i></button>
                    <button class="btn btn-sm btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
                </td>
            </tr>
        </thead>
        <tbody class="list">
            <!-- Data Will Be Placed Here -->
        </tbody>
    </table>
</div>