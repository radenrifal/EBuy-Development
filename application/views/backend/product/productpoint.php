<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_product') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_product_point'); ?></li>
                        </ol>
                    </nav>
                </div>
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
                            <h3 class="mb-0"><?php echo lang('menu_product_point') ?> </h3>
                        </div>
                    </div>
                </div>
                <div class="table-container">
                    <table class="table align-items-center table-flush" id="list_table_product_point" data-url="<?php echo base_url('productmanage/productpointlistsdata'); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px">#</th>
                                <th scope="col" class="text-center"><?php echo lang('type'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('name'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('total'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('point'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                            </tr>
                            <tr role="row" class="filter" style="background-color: #f6f9fc">
                                <td></td>
                                <td>
                                    <select name="search_source" class="form-control form-control-sm form-filter">
                                        <option value=""><?php echo lang('select'); ?>...</option>
                                        <option value="product"><?php echo lang('product'); ?></option>
                                        <option value="package"><?php echo lang('package'); ?></option>
                                    </select>
                                </td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_source" /></td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center" name="search_total_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center" name="search_total_max" placeholder="Max" />
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center" name="search_point_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center" name="search_point_max" placeholder="Max" />
                                </td>
                                <td style="text-align: center;">
                                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_product_point" title="Search"><i class="fa fa-search"></i></button>
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
        </div>
    </div>
</div>

<!-- Modal Add Category -->
<div class="modal fade" id="modal-product-point" tabindex="-1" role="dialog" aria-labelledby="modal-product-point" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ni ni-book-bookmark"></i> <?php echo lang('menu_product_point'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form role="form" method="post" action="<?php echo base_url('productmanage/saveproductpoint'); ?>" id="form-product-point" class="form-horizontal">
                <div class="modal-body wrapper-form-product-point">
                    <div class="form-group">
                        <label class="form-control-label" for="source"><?php echo lang('type'); ?></label>
                        <input type="text" id="source" name="source" class="form-control" readonly="" />
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="name"><?php echo lang('name'); ?></label>
                        <input type="text" id="name" name="name" class="form-control" readonly="" />
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label" for="total"><?php echo lang('total'); ?> <span class="required">*</span></label>
                                <input type="text" id="total" name="total" class="form-control numbercurrency">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label" for="point"><?php echo lang('point'); ?> <span class="required">*</span></label>
                                <input type="text" id="point" name="point" class="form-control numbercurrency">
                            </div>
                        </div>
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
