<table class="table align-items-center table-flush" id="list_table_shop_order" data-url="<?php echo base_url('productorder/agentorderlistsdata'); ?>">
    <thead class="thead-light">
        <tr role="row" class="heading">
            <th scope="col" style="width: 10px">#</th>
            <th scope="col" class="text-center">Invoice</th>
            <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
            <th scope="col"><?php echo lang('name'); ?></th>
            <th scope="col" class="text-center"><?php echo lang('type'); ?></th>
            <th scope="col" class="text-center"><?php echo lang('total_payment'); ?></th>
            <th scope="col" class="text-center"><?php echo lang('product'); ?></th>
            <th scope="col" class="text-center"><?php echo str_replace(' ', br(), lang('payment_method')); ?></th>
            <th scope="col" class="text-center"><?php echo lang('status'); ?></th>
            <th scope="col" class="text-center">Resi</th>
            <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
            <th scope="col" class="text-center"><?php echo str_replace(' ', br(), lang('confirm_date')); ?></th>
            <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
        </tr>
        <tr role="row" class="filter" style="background-color: #f6f9fc">
            <td></td>
            <td><input type="text" class="form-control form-control-sm form-filter" name="search_invoice" /></td>
            <td><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
            <td><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
            <td>
                <select name="search_type" class="form-control form-control-sm form-filter">
                    <option value=""><?php echo lang('select'); ?>...</option>
                    <option value="perdana">PERDANA</option>
                    <option value="ro">ORDER</option>
                </select>
            </td>
            <td>
                <div class="mb-1">
                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_min" placeholder="Min" />
                </div>
                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_max" placeholder="Max" />
            </td>
            <td></td>
            <td>
                <select name="search_payment_method" class="form-control form-control-sm form-filter">
                    <option value=""><?php echo lang('select'); ?>...</option>
                    <option value="transfer">TRANSFER</option>
                    <option value="deposite">DEPOSIT</option>
                </select>
            </td>
            <td>
                <select name="search_status" class="form-control form-control-sm form-filter">
                    <option value=""><?php echo lang('select'); ?>...</option>
                    <option value="pending">PENDING</option>
                    <option value="confirmed">CONFIRMED</option>
                    <option value="done">DONE</option>
                    <option value="cancelled">CANCELLED</option>
                </select>
            </td>
            <td><input type="text" class="form-control form-control-sm form-filter" name="search_resi" /></td>
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
                <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_shop_order" title="Search"><i class="fa fa-search"></i></button>
                <button class="btn btn-sm btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
            </td>
        </tr>
    </thead>
    <tbody class="list">
        <!-- Data Will Be Placed Here -->
    </tbody>
</table>