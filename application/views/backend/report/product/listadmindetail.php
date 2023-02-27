<div class="table-container">
    <table class="table align-items-center table-flush" id="list_report_history_product_detail" data-url="<?php echo base_url('member/historyproductdetaillistdata/'.ddm_encrypt($member_other->id)); ?>">
        <thead class="thead-light">
            <tr role="row" class="heading">
                <th scope="col" style="width: 10px">#</th>
                <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                <th scope="col" class="text-center"><?php echo 'Qty' ?></th>
                <th scope="col" class="text-center" style="width: 100px"><?php echo lang('type'); ?></th>
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
                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_qty_min" placeholder="Min" />
                    </div>
                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_qty_max" placeholder="Max" />
                </td>
                <td>
                    <select name="search_type" class="form-control form-filter input-sm">
                        <option value=""><?php echo lang('type'); ?></option>
                        <option value="IN"><?php echo 'IN'; ?></option>
                        <option value="OUT"><?php echo 'OUT'; ?></option>
                    </select>
                </td>
                <td><input type="text" class="form-control form-filter input-sm" name="search_desc" /></td>
                <td style="text-align: center;">
                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_report_history_product_detail" title="Search"><i class="fa fa-search"></i></button>
                    <button class="btn btn-sm btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
                </td>
            </tr>
        </thead>
        <tbody class="list">
            <!-- Data Will Be Placed Here -->
        </tbody>
    </table>
</div>
            