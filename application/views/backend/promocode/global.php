<?php $discount_type = config_item('discount_type'); ?>

<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_promo_code'); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_promo_global'); ?></li>
                        </ol>
                    </nav>
                </div>
                <?php if ( $is_admin) { ?>
                    <div class="col-lg-6 col-5 text-right">
                        <a href="javascript:;" class="btn btn-sm btn-neutral" id="btn-modal-promo-code" data-url="<?php echo base_url('promocode/savepromocode'); ?>">
                            <i class="fa fa-plus mr-1"></i> <?php echo lang('add') .' '. lang('menu_promo_code'); ?>
                        </a>
                    </div>
                <?php } ?>
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
                            <h3 class="mb-0"><?php echo lang('menu_promo_code') .' - '. lang('menu_promo_global'); ?> </h3>
                        </div>
                    </div>
                </div>
                <div class="table-container">
                    <table class="table align-items-center table-flush" id="list_table_promo_code" data-url="<?php echo base_url('promocode/promogloballistsdata/global'); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px" rowspan="2">#</th>
                                <th scope="col" class="text-center" rowspan="2"><?php echo lang('promo_code'); ?></th>
                                <th scope="col" class="text-center" colspan="2"><?php echo lang('agent'); ?></th>
                                <th scope="col" class="text-center" colspan="2"><?php echo lang('customer'); ?></th>
                                <th scope="col" class="text-center" rowspan="2">Status</th>
                                <th scope="col" class="text-center" rowspan="2"><?php echo lang('create_date'); ?></th>
                                <th scope="col" class="text-center" rowspan="2"><?php echo lang('actions'); ?></th>
                            </tr>
                            <tr role="row" class="heading">
                                <th scope="col" class="text-center"><?php echo lang('discount_type'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('nominal'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('discount_type'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('nominal'); ?></th>
                            </tr>
                            <tr role="row" class="filter" style="background-color: #f6f9fc">
                                <td></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_code" /></td>
                                <td>
                                    <select name="search_type_agent" class="form-control form-control-sm form-filter">
                                        <option value=""><?php echo lang('select'); ?>...</option>
                                        <?php
                                            if ( $discount_type ) {
                                                foreach ($discount_type as $key => $val) {
                                                    echo '<option value="'. $key .'" '.$selected.'>'. $val .'</option>';
                                                }   
                                            }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center" name="search_discount_agent_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center" name="search_discount_agent_max" placeholder="Max" />
                                </td>
                                <td>
                                    <select name="search_type_customer" class="form-control form-control-sm form-filter">
                                        <option value=""><?php echo lang('select'); ?>...</option>
                                        <?php
                                            if ( $discount_type ) {
                                                foreach ($discount_type as $key => $val) {
                                                    echo '<option value="'. $key .'" '.$selected.'>'. $val .'</option>';
                                                }   
                                            }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center" name="search_discount_customer_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center" name="search_discount_customer_max" placeholder="Max" />
                                </td>
                                <td>
                                    <select name="search_status" class="form-control form-control-sm form-filter">
                                        <option value=""><?php echo lang('select'); ?>...</option>
                                        <option value="active">Active</option>
                                        <option value="nonactive">Non-Active</option>
                                    </select>
                                </td>
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
                                <td style="text-align: center;">
                                    <button class="btn btn-sm btn-block btn-outline-default btn-tooltip filter-submit" id="btn_list_table_promo_code" title="Search"><i class="fa fa-search"></i></button>
                                    <button class="btn btn-sm btn-block btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
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

<!-- Modal Form Promo Code -->
<div class="modal fade" id="modal-form-promocode" tabindex="-1" role="dialog" aria-labelledby="modal-form-promocode" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ni ni-book-bookmark"></i> <?php echo lang('menu_promo_code'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form role="form" method="post" action="<?php echo base_url('promocode/savepromocode'); ?>" id="form-promocode" class="form-horizontal">
                <input type="hidden" name="form_input" class="d-none" value="global" />
                <input type="hidden" name="form_code" id="form_code" class="d-none" value="" />
                <div class="modal-body wrapper-form-promocode py-2">
                    <div class="form-group">
                        <label class="form-control-label" for="promo_code"><?php echo lang('promo_code'); ?> <span class="required">*</span></label>
                        <input type="text" id="promo_code" name="promo_code" class="form-control text-uppercase" placeholder="<?php echo lang('promo_code'); ?>" data-url="<?php echo base_url('promocode/checkcode'); ?>" />
                    </div>
                    <h6 class="heading-small text-muted mb-1"><?php echo lang('discount_agent'); ?></h6>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-1">
                                <label class="form-control-label" for="discount_agent_type"><?php echo lang('discount_type'); ?> </label>
                                <select class="form-control" name="discount_agent_type" id="discount_agent_type">
                                    <?php
                                        if ( $discount_type ) {
                                            foreach ($discount_type as $key => $val) {
                                                echo '<option value="'. $key .'" '.$selected.'>'. $val .'</option>';
                                            }   
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-1">
                                <label class="form-control-label" for="discount_agent">Jumlah (%)</label>
                                <input type="text" id="discount_agent" name="discount_agent" class="form-control" placeholder="0">
                            </div>
                        </div>
                    </div>
                    <hr class="my-2" />
                    <h6 class="heading-small text-muted mb-1"><?php echo lang('discount_customer'); ?></h6>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-control-label" for="discount_customer_type"><?php echo lang('discount_type'); ?></label>
                                <select class="form-control" name="discount_customer_type" id="discount_customer_type">
                                    <?php
                                        if ( $discount_type ) {
                                            foreach ($discount_type as $key => $val) {
                                                echo '<option value="'. $key .'" '.$selected.'>'. $val .'</option>';
                                            }   
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-control-label" for="discount_customer">Jumlah (%) </label>
                                <input type="text" id="discount_customer" name="discount_customer" class="form-control" placeholder="0">
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-primary" role="alert">
                        <h4 class="alert-heading"><i class="fa fa-bell"></i> Information</h4>
                        <p class="mb-0">Kode Diskon berlaku untuk semua produk</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo lang('back'); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo lang('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
