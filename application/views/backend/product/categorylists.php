<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_product') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_product_category'); ?></li>
                        </ol>
                    </nav>
                </div>
                <?php if ( $is_admin) { ?>
                    <div class="col-lg-6 col-5 text-right">
                        <a href="javascript:;" class="btn btn-sm btn-neutral" id="btn-modal-category" data-url="<?php echo base_url('productmanage/savecategory'); ?>">
                            <i class="fa fa-plus mr-1"></i> <?php echo lang('menu_product_category'); ?>
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
                            <h3 class="mb-0"><?php echo lang('menu_product_category') ?> </h3>
                        </div>
                    </div>
                </div>
                <div class="table-container">
                    <table class="table align-items-center table-flush" id="list_table_category" data-url="<?php echo base_url('productmanage/categorylistsdata'); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px">#</th>
                                <th scope="col" class="text-center"><?php echo lang('category'); ?></th>
                                <th scope="col" class="text-center">Status</th>
                                <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                            </tr>
                            <tr role="row" class="filter" style="background-color: #f6f9fc">
                                <td></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_category" /></td>
                                <td>
                                    <select name="search_status" class="form-control form-control-sm form-filter">
                                        <option value=""><?php echo lang('select'); ?>...</option>
                                        <option value="active">Active</option>
                                        <option value="nonactive">Non-Active</option>
                                    </select>
                                </td>
                                <td style="text-align: center;">
                                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_category" title="Search"><i class="fa fa-search"></i></button>
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
<div class="modal fade" id="modal-add-category" tabindex="-1" role="dialog" aria-labelledby="modal-add-category" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ni ni-book-bookmark"></i> <?php echo lang('category'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form role="form" method="post" action="<?php echo base_url('productmanage/savecategory'); ?>" id="form-category" class="form-horizontal">
                <input type="hidden" name="form" class="d-none" value="category" />
                <div class="modal-body wrapper-form-category">
                    <div class="form-group">
                        <label class="form-control-label" for="category"><?php echo lang('category'); ?> <span class="required">*</span></label>
                        <input type="text" id="category" name="category" class="form-control" placeholder="<?php echo lang('category'); ?>" />
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
