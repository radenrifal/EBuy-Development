<div class="row">
    <div class="col-xl-4 col-md-6">
        <div class="card bg-gradient-primary">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0 text-white">Saldo Deposite</h5>
                        <span class="h2 font-weight-bold mb-0 text-white"><?php echo ddm_accounting($deposite_saldo); ?></span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-white text-dark rounded-circle shadow">
                            <i class="ni ni-credit-card"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card bg-gradient-info">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0 text-white">Total Deposite IN</h5>
                        <span class="h2 font-weight-bold mb-0 text-white"><?php echo ddm_accounting($deposite_in); ?></span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-white text-dark rounded-circle shadow">
                            <i class="ni ni-cloud-download-95"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card bg-gradient-danger">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0 text-white">Total Deposite OUT</h5>
                        <span class="h2 font-weight-bold mb-0 text-white"><?php echo ddm_accounting($deposite_out); ?></span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-white text-dark rounded-circle shadow">
                            <i class="ni ni-cloud-upload-96"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="table-container">
    <table class="table align-items-center table-flush" id="list_table_deposite" data-url="<?php echo base_url('commission/memberdepositelistdata'); ?>">
        <thead class="thead-light">
            <tr role="row" class="heading">
                <th scope="col" style="width: 10px">#</th>
                <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                <th scope="col" class="text-center"><?php echo lang('type'); ?></th>
                <th scope="col" class="text-center"><?php echo lang('status'); ?></th>
                <th scope="col" class="text-center"><?php echo lang('nominal'); ?></th>
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
                    <select name="search_source" class="form-control form-filter input-sm">
                        <option value=""><?php echo lang('type'); ?>...</option>
                        <option value="bonus">BONUS</option>
                        <option value="withdraw">WITHDRAW</option>
                        <option value="register">REGISTER</option>
                    </select>
                </td>
                <td>
                    <select name="search_status" class="form-control form-filter input-sm">
                        <option value=""><?php echo lang('status'); ?>...</option>
                        <option value="IN">IN</option>
                        <option value="OUT">OUT</option>
                    </select>
                </td>
                <td>
                    <div class="mb-1">
                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_min" placeholder="Min" />
                    </div>
                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_max" placeholder="Max" />
                </td>
                <td><input type="text" class="form-control form-filter input-sm" name="search_desc" /></td>
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