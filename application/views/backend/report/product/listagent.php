<div class="nav-wrapper">
    <ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="tabs-icons-text" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="tabs-history_stock_product-tab" data-toggle="tab" href="#tabs-history_stock_product" role="tab" aria-controls="tabs-history_stock_product" aria-selected="true"><i class="ni ni-chart-bar-32 mr-2"></i><?php echo lang('history_stock_product'); ?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tabs-used_product-tab" data-toggle="tab" href="#tabs-used_product" role="tab" aria-controls="tabs-used_product" aria-selected="false"><i class="ni ni-box-2 mr-2"></i><?php echo lang('used_product'); ?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tabs-transfer_product-tab" data-toggle="tab" href="#tabs-transfer_product" role="tab" aria-controls="tabs-transfer_product" aria-selected="false"><i class="ni ni-box-2 mr-2"></i><?php echo lang('transfer').' '.lang('product'); ?></a>
        </li>
    </ul>
</div>
<div class="card shadow">
    <div class="card-body">
        <div class="tab-content" id="productListContent">
        
            <!-- Stock Product -->
            <div class="tab-pane fade show active" id="tabs-history_stock_product" role="tabpanel" aria-labelledby="tabs-history_stock_product-tab">
                <div class="table-container">
                    <table class="table align-items-center table-flush" id="list_report_history_product" data-url="<?php echo base_url('member/historyproductdetaillistdata'); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px">#</th>
                                <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                                <!--
                                <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                                <th scope="col"><?php echo lang('name'); ?></th>
                                -->
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
                                <!--
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                                -->
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
            
            <!-- Product Used -->
            <div class="tab-pane fade" id="tabs-used_product" role="tabpanel" aria-labelledby="tabs-used_product-tab">
                <div class="table-container">
                <table class="table align-items-center table-flush" id="list_report_product_used" data-url="<?php echo base_url('member/productusedlistdata'); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px">#</th>
                                <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                                <th scope="col"><?php echo lang('name'); ?></th>
                                <th scope="col" class="text-center"><?php echo 'Jumlah (Liter)' ?></th>
                                <th scope="col" class="text-center"><?php echo 'Nominal' ?></th>
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
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_qty_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_qty_max" placeholder="Max" />
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_max" placeholder="Max" />
                                </td>
                                <td>
                                    <select name="search_type" class="form-control form-control-sm form-filter">
                                        <option value=""><?php echo lang('select'); ?>...</option>
                                        <option value="omzet">PERDANA</option>
                                        <option value="register">REGISTRASI</option>
                                        <option value="order">ORDER</option>
                                        <option value="activation">AKTIVASI</option>
                                        <option value="transfer">TRANSFER</option>
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
            
            <!-- Transfer Product Report -->
            <div class="tab-pane fade" id="tabs-transfer_product" role="tabpanel" aria-labelledby="tabs-transfer_product-tab">
                <div class="nav-wrapper mt-0 pt-0">
                    <ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="tabs-icons-text" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tabs-transfer_in-tab" data-toggle="tab" href="#tabs-transfer_in" role="tab" aria-controls="tabs-transfer_in" aria-selected="true"><i class="ni ni-box-2 mr-2"></i><?php echo lang('transfer') . ' IN'; ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tabs-transfer_out-tab" data-toggle="tab" href="#tabs-transfer_out" role="tab" aria-controls="tabs-transfer_out" aria-selected="false"><i class="ni ni-box-2 mr-2"></i><?php echo lang('transfer') . ' OUT'; ?></a>
                        </li>
                    </ul>
                </div>
                <div class="card shadow">
                    <div class="card-body">
                        <div class="tab-content" id="transferProductListContent">
                            <!-- Transfer IN -->
                            <div class="tab-pane fade show active" id="tabs-transfer_in" role="tabpanel" aria-labelledby="tabs-transfer_in-tab">
                                <div class="table-container">
                                    <table class="table align-items-center table-flush" id="list_report_transfer_product_in" data-url="<?php echo base_url('member/transferproductagentlistdata/in'); ?>">
                                        <thead class="thead-light">
                                            <tr role="row" class="heading">
                                                <th scope="col" style="width: 10px">#</th>
                                                <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                                                <th scope="col" class="text-center"><?php echo 'Pengirim'; ?></th>
                                                <th scope="col" class="text-center"><?php echo 'Jumlah (Liter)' ?></th>
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
                                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_username_receiver" /></td>
                                                <td>
                                                    <div class="mb-1">
                                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_qty_min" placeholder="Min" />
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_qty_max" placeholder="Max" />
                                                </td>
                                                <td style="text-align: center;">
                                                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_report_transfer_product_in" title="Search"><i class="fa fa-search"></i></button>
                                                    <button class="btn btn-sm btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
                                                </td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Transfer OUT -->
                            <div class="tab-pane fade" id="tabs-transfer_out" role="tabpanel" aria-labelledby="tabs-transfer_out-tab">
                                <div class="table-container">
                                    <table class="table align-items-center table-flush" id="list_report_transfer_product_out" data-url="<?php echo base_url('member/transferproductagentlistdata/out'); ?>">
                                        <thead class="thead-light">
                                            <tr role="row" class="heading">
                                                <th scope="col" style="width: 10px">#</th>
                                                <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                                                <th scope="col" class="text-center"><?php echo 'Penerima'; ?></th>
                                                <th scope="col" class="text-center"><?php echo 'Jumlah (Liter)' ?></th>
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
                                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_username_sender" /></td>
                                                <td>
                                                    <div class="mb-1">
                                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_qty_min" placeholder="Min" />
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_qty_max" placeholder="Max" />
                                                </td>
                                                <td style="text-align: center;">
                                                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_report_transfer_product_out" title="Search"><i class="fa fa-search"></i></button>
                                                    <button class="btn btn-sm btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
                                                </td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>