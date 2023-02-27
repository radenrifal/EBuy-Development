<?php 
    $data_product   = isset($data_product) ? $data_product : false; 
    $discount_type  = config_item('discount_type');
?>

<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_product') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Form</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-12">
            <div class="row justify-content-center">
                <div class="col-lg-12 card-wrapper">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-1"><?php echo $form_title; ?> </h3>
                                    <?php if ( $form_page == 'edit' ) { ?>
                                        <h5 class="text-muted mb-0"><?php echo $data_product->name; ?> </h5>
                                    <?php } ?>
                                </div>
                                <div class="col-4 text-right">
                                    <?php if ( $form_page == 'edit' ) { ?>
                                        <a href="<?php echo base_url('productmanage/productlist') ?>" class="btn btn-sm btn-danger"><span class="fa fa-history"></span> Kembali</a>
                                    <?php } else { ?>
                                        <a href="<?php echo base_url('productmanage/productlist') ?>" class="btn btn-sm btn-outline-default"><span class="fa fa-list"></span> List Product</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body wrapper-form-product pt-0">
                            <?php 
                                $form_action = base_url('productmanage/saveproduct');
                                if ( $form_page == 'edit' ) { 
                                    $form_action .= '/'. ddm_encrypt($data_product->id);
                                }
                            ?>
                            <form role="form" method="post" action="<?php echo $form_action; ?>" id="form-product" class="form-horizontal">
  
                                    <div class="row">
                                        <div class="col-xl-12 order-xl-2 pt-3">
                                            <div class="form-group">
                                                <label class="form-control-label" for="product_name"><?php echo lang('product'); ?> <span class="required">*</span></label>
                                                <div class="input-group input-group-merge input-group-alternative">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="ni ni-box-2"></i></span>
                                                    </div>
                                                    <input type="text" name="product_name" id="product_name" class="form-control" placeholder="<?php echo lang('product_name'); ?>" value="<?php echo( $data_product ? $data_product->name : ''); ?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="product_category"><?php echo lang('category'); ?> <span class="required">*</span></label>
                                                <div class="input-group">
                                                    <select class="form-control" name="product_category" id="product_category">
                                                        <option value="" disabled="" selected="">-- <?php echo lang('select').' '.lang('category'); ?> --</option>
                                                        <?php
                                                            if ( $product_category = ddm_product_category(0, true) ) {
                                                                foreach ($product_category as $key => $row) {
                                                                    $selected = '';
                                                                    if ( $data_product ) {
                                                                        if ( $data_product->id_category == $row->id ) {
                                                                            $selected = 'selected=""';
                                                                        } else {
                                                                            $selected = '';
                                                                        }
                                                                    }
                                                                    echo '<option value="'. $row->id .'" '.$selected.'>'. ucwords($row->name) .'</option>';
                                                                }   
                                                            }
                                                        ?>
                                                    </select>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-primary" type="button" id="btn-modal-category">
                                                            <i class="fa fa-plus"></i> <?php echo lang('category'); ?>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="price_agent1"><?php echo lang('price_agent'); ?> Wilayah 1 <span class="required">*</span></label>
                                                        <input type="text" id="price_agent1" name="price_agent1" class="form-control numbercurrency" placeholder="0" value="<?php echo ( $data_product ? $data_product->price_agent1 : ''); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="price_agent2"><?php echo lang('price_agent'); ?> Wilayah 2 <span class="required">*</span></label>
                                                        <input type="text" id="price_agent2" name="price_agent2" class="form-control numbercurrency" placeholder="0" value="<?php echo ( $data_product ? $data_product->price_agent2 : ''); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="price_agent3"><?php echo lang('price_agent'); ?> Wilayah 3 <span class="required">*</span></label>
                                                        <input type="text" id="price_agent3" name="price_agent3" class="form-control numbercurrency" placeholder="0" value="<?php echo ( $data_product ? $data_product->price_agent3 : ''); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="price_customer1"><?php echo lang('price_customer'); ?> Wilayah 1 <span class="required">*</span></label>
                                                        <input type="text" id="price_customer1" name="price_customer1" class="form-control numbercurrency" placeholder="0" value="<?php echo ( $data_product ? $data_product->price_customer1 : ''); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="price_customer2"><?php echo lang('price_customer'); ?> Wilayah 2 <span class="required">*</span></label>
                                                        <input type="text" id="price_customer2" name="price_customer2" class="form-control numbercurrency" placeholder="0" value="<?php echo ( $data_product ? $data_product->price_customer2 : ''); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="price_customer3"><?php echo lang('price_customer'); ?> Wilayah 3 <span class="required">*</span></label>
                                                        <input type="text" id="price_customer3" name="price_customer3" class="form-control numbercurrency" placeholder="0" value="<?php echo ( $data_product ? $data_product->price_customer3 : ''); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="min_order">Min Agen Order <span class="required">*</span></label>
                                                        <input type="text" id="min_order" name="min_order" class="form-control numbercurrency" placeholder="0" value="<?php echo ( $data_product ? $data_product->min_order : ''); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="weight"><?php echo lang('weight'); ?> <sup>(Liter)</sup> <span class="required">*</span></label>
                                                        <input type="text" id="weight" name="weight" class="form-control numbercurrency" placeholder="0 (ltr)" value="<?php echo ( $data_product ? $data_product->weight : ''); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="stock">Stok</label>
                                                        <input type="text" id="stock" name="stock" class="form-control numbercurrency" placeholder="0" value="<?php echo ( $data_product ? $data_product->stock : ''); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="editor">Deskripsi</label>
                                                <div id="editor" data-quill-placeholder="Deskripsi Produk">
                                                    <?php echo ( $data_product ? $data_product->description : ''); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-12 order-xl-1 bg-secondary py-2">
                                            <div class="form-group">
                                                <?php 
                                                    $file_src   = ASSET_PATH . 'backend/img/no_image.jpg'; 
                                                    if ( $data_product ) {
                                                        if ( $data_product->image ) {
                                                            $file_path = PRODUCT_IMG_PATH . $data_product->image;
                                                            if ( file_exists($file_path) ) {
                                                                $file_src = PRODUCT_IMG . $data_product->image;
                                                            }
                                                        }
                                                    }

                                                ?>
                                                <div class="thumbnail mb-1 text-center">
                                                    <img class="img-thumbnail" id="product_img_thumbnail" width="50%" src="<?php echo $file_src; ?>" style="cursor: pointer;">
                                                    <div class="caption">
                                                        <p class="text-muted mb-0" style="font-size: 14px">Image ( jpg, jpeg, png ) and Max 2 MB</p>
                                                        <div class="img-information" style="display: none;">
                                                            <i class="ni ni-album-2 mr-1" id="type_img_thumbnail"></i> 
                                                            <span id="size_img_thumbnail"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="file" name="product_file" id="product_file" class="form-control file-image" accept="image/x-png,image/jpeg">
                                            </div>
                                            <div class="accordion" id="accordionDiscountProduct">
                                                <div class="card mb-3">
                                                    <div class="card-header" id="headingDiscountAgent" data-toggle="collapse" data-target="#collapseDiscountAgent" aria-expanded="false" aria-controls="collapseDiscountAgent">
                                                        <h5 class="mb-0">Diskon Agent</h5>
                                                    </div>
                                                    <div id="collapseDiscountAgent" class="collapse" aria-labelledby="headingDiscountAgent" data-parent="#accordionDiscountProduct">
                                                        <div class="card-body">
                                                            <div class="form-group">
                                                                <label class="form-control-label" for="discount_agent_min">Min Qty Order <sup>(Opsional)</sup></label>
                                                                <input type="text" id="discount_agent_min" name="discount_agent_min" class="form-control form-control-sm numbermask" placeholder="0" value="<?php echo ( $data_product ? $data_product->discount_agent_min : ''); ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-control-label" for="discount_agent_type">Jenis Diskon </label>
                                                                <select class="form-control form-control-sm" name="discount_agent_type" id="discount_agent_type">
                                                                    <?php
                                                                        if ( $discount_type ) {
                                                                            foreach ($discount_type as $key => $val) {
                                                                                $selected = '';
                                                                                if ( $data_product ) {
                                                                                    if ( $data_product->discount_agent_type == $key ) {
                                                                                        $selected = 'selected=""';
                                                                                    } else {
                                                                                        $selected = '';
                                                                                    }
                                                                                }
                                                                                echo '<option value="'. $key .'" '.$selected.'>'. $val .'</option>';
                                                                            }   
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-control-label label_discount_agent" for="discount_agent">Jumlah (%)</label>
                                                                <input type="text" id="discount_agent" name="discount_agent" class="form-control form-control-sm" placeholder="0" value="<?php echo ( $data_product ? $data_product->discount_agent : ''); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card mb-3">
                                                    <div class="card-header" id="headingDiscountCustomer" data-toggle="collapse" data-target="#collapseDiscountCustomer" aria-expanded="false" aria-controls="collapseDiscountCustomer">
                                                        <h5 class="mb-0">Diskon Konsumen</h5>
                                                    </div>
                                                    <div id="collapseDiscountCustomer" class="collapse" aria-labelledby="headingDiscountCustomer" data-parent="#accordionDiscountProduct">
                                                        <div class="card-body">
                                                            <div class="form-group">
                                                                <label class="form-control-label" for="discount_customer_min">Min Qty Order <sup>(Opsional)</sup></label>
                                                                <input type="text" id="discount_customer_min" name="discount_customer_min" class="form-control form-control-sm numbermask" placeholder="0" value="<?php echo ( $data_product ? $data_product->discount_customer_min : ''); ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-control-label" for="discount_customer_type">Jenis Diskon </label>
                                                                <select class="form-control form-control-sm" name="discount_customer_type" id="discount_customer_type">
                                                                    <?php
                                                                        if ( $discount_type ) {
                                                                            foreach ($discount_type as $key => $val) {
                                                                                $selected = '';
                                                                                if ( $data_product ) {
                                                                                    if ( $data_product->discount_customer_type == $key ) {
                                                                                        $selected = 'selected=""';
                                                                                    } else {
                                                                                        $selected = '';
                                                                                    }
                                                                                }
                                                                                echo '<option value="'. $key .'" '.$selected.'>'. $val .'</option>';
                                                                            }   
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-control-label label_discount_customer" for="discount_customer">Jumlah (%)</label>
                                                                <input type="text" id="discount_customer" name="discount_customer" class="form-control form-control-sm" placeholder="0" value="<?php echo ( $data_product ? $data_product->discount_customer : ''); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card mb-0">
                                                    <div class="card-header" id="headingFeeShipping" data-toggle="collapse" data-target="#collapseFeeShipping" aria-expanded="false" aria-controls="collapseFeeShipping">
                                                        <h5 class="mb-0">Gratis Ongkir</h5>
                                                    </div>
                                                    <div id="collapseFeeShipping" class="collapse" aria-labelledby="headingFeeShipping" data-parent="#accordionDiscountProduct">
                                                        <div class="card-body">
                                                            <div class="form-group">
                                                                <label class="form-control-label" for="qty_free_shipping">Min Qty Order</label>
                                                                <input type="text" id="qty_free_shipping" name="qty_free_shipping" class="form-control form-control-sm numbermask" placeholder="0" value="<?php echo ( $data_product ? $data_product->qty_free_shipping : ''); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <hr class="my-4" />
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary bg-gradient-default my-2"><?php echo lang('product_save'); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
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
                <h5 class="modal-title"><i class="fa fa-plus"></i> <?php echo lang('category'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form role="form" method="post" action="<?php echo base_url('productmanage/savecategory'); ?>" id="form-category" class="form-horizontal">
                <input type="hidden" name="form" class="d-none" value="product" />
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
