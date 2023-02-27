<div class="table-container">
    <table class="table align-items-center table-flush" id="list_table_deposite" data-url="<?php echo base_url('commission/depositelistdata'); ?>">
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
                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_deposite" title="Search"><i class="fa fa-search"></i></button>
                    <button class="btn btn-sm btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
                </td>
            </tr>
        </thead>
        <tbody class="list">
            <!-- Data Will Be Placed Here -->
        </tbody>
    </table>
</div>