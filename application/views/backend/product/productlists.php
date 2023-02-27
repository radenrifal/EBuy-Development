<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_product') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_product_list'); ?></li>
                        </ol>
                    </nav>
                </div>
                <?php if ( $is_admin) { ?>
                    <div class="col-lg-6 col-5 text-right">
                        <a href="<?php echo base_url('productmanage/productnew') ?>" class="btn btn-sm btn-neutral"><i class="fa fa-plus mr-1"></i> <?php echo lang('menu_product_new'); ?></a>
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
                            <h3 class="mb-0"><?php echo lang('menu_product_list') ?> </h3>
                        </div>
                    </div>
                </div>
                <div class="table-container">
                    <table class="table align-items-center table-flush" id="list_table_product" data-url="<?php echo base_url('productmanage/productlistsdata'); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px">#</th>
                                <th scope="col" class="text-center"><?php echo lang('product'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('category'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('price'); ?> Wilayah 1</th>
                                <th scope="col" class="text-center"><?php echo lang('price'); ?> Wilayah 2</th>
                                <th scope="col" class="text-center"><?php echo lang('price'); ?> Wilayah 3</th>
                                <th scope="col" class="text-center">Status</th>
                                <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                            </tr>
                            <tr role="row" class="filter" style="background-color: #f6f9fc">
                                <td></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_category" /></td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center" name="search_price_agent1_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center" name="search_price_agent1_max" placeholder="Max" />
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center" name="search_price_agent2_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center" name="search_price_agent2_max" placeholder="Max" />
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center" name="search_price_agent3_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center" name="search_price_agent3_max" placeholder="Max" />
                                </td>
                                <td>
                                    <select name="search_status" class="form-control form-control-sm form-filter">
                                        <option value=""><?php echo lang('select'); ?>...</option>
                                        <option value="active">Active</option>
                                        <option value="nonactive">Non-Active</option>
                                    </select>
                                </td>
                                <td style="text-align: center;">
                                    <button class="btn btn-sm btn-block btn-outline-default btn-tooltip filter-submit" id="btn_list_table_product" title="Search"><i class="fa fa-search"></i></button>
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
