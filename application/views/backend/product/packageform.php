<?php 
    $data_package   = isset($data_package) ? $data_package : false; 
    $data_product   = isset($data_package->product_details) ? $data_package->product_details : false; 
    $data_product   = ($data_product) ? maybe_unserialize($data_product) : false; 
    $total_qty      = isset($data_package->qty) ? $data_package->qty : 0; 
    $total_price1   = isset($data_package->price1) ? $data_package->price1 : 0; 
    $total_price2   = isset($data_package->price2) ? $data_package->price2 : 0; 
    $total_price3   = isset($data_package->price3) ? $data_package->price3 : 0; 
    $total_bv1      = isset($data_package->bv1) ? $data_package->bv1 : 0; 
    $total_bv2      = isset($data_package->bv2) ? $data_package->bv2 : 0; 
    $total_bv3      = isset($data_package->bv3) ? $data_package->bv3 : 0;
?>

<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('package') .' '. lang('menu_product') ?></a></li>
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
                                        <h5 class="text-muted mb-0"><?php echo $data_package->name; ?> </h5>
                                    <?php } ?>
                                </div>
                                <div class="col-4 text-right">
                                    <?php if ( $form_page == 'edit' ) { ?>
                                        <a href="<?php echo base_url('productmanage/packagelist') ?>" class="btn btn-sm btn-danger">
                                            <span class="fa fa-history"></span> <?php echo lang('back'); ?>
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php echo base_url('productmanage/packagelist') ?>" class="btn btn-sm btn-outline-default">
                                            <span class="fa fa-list"></span> <?php echo lang('menu_package_list'); ?>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body wrapper-form-product-package">
                            <?php 
                                $form_action = base_url('productmanage/savepackage');
                                if ( $form_page == 'edit' ) { 
                                    $form_action .= '/'. ddm_encrypt($data_package->id);
                                }
                            ?>
                            <form role="form" method="post" action="<?php echo $form_action; ?>" id="form-product-package" class="form-horizontal">
                                <div class="form-group row mb-1">
                                    <label class="col-md-3 col-form-label form-control-label d-md-none" for="package_name">
                                        <?php echo lang('package'); ?> <span class="required">*</span>
                                    </label>
                                    <label class="col-md-3 col-form-label form-control-label d-none d-md-inline-block text-right" for="package_name">
                                        <?php echo lang('package'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-9 col-lg-7">
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="ni ni-box-2"></i></span>
                                            </div>
                                            <input type="text" name="package_name" id="package_name" class="form-control" placeholder="<?php echo lang('package'); ?>" value="<?php echo( $data_package ? $data_package->name : ''); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label form-control-label"></label>
                                    <?php 
                                        $file_src   = ASSET_PATH . 'backend/img/no_image.jpg'; 
                                        if ( $data_package ) {
                                            if ( $data_package->image ) {
                                                $file_path = PRODUCT_IMG_PATH . $data_package->image;
                                                if ( file_exists($file_path) ) {
                                                    $file_src = PRODUCT_IMG . $data_package->image;
                                                }
                                            }
                                        }

                                    ?>
                                    <div class="col-sm-12 col-md-7 col-lg-4">
                                        <div class="thumbnail mb-1">
                                            <img class="img-thumbnail" id="product_img_thumbnail" width="100%" src="<?php echo $file_src; ?>" style="cursor: pointer;">
                                            <div class="caption">
                                                <p class="text-muted mb-0" style="font-size: 14px">Image ( jpg, jpeg, png ) and Max 2 MB</p>
                                                <div class="img-information" style="display: none;">
                                                    <i class="ni ni-album-2 mr-1" id="type_img_thumbnail"></i> 
                                                    <span id="size_img_thumbnail"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="file" name="package_image" id="package_image" class="form-control file-image" accept="image/x-png,image/jpeg">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-md-3 col-form-label form-control-label d-md-none" for="package_qty">
                                        Qty <span class="required">*</span>
                                    </label>
                                    <label class="col-md-3 col-form-label form-control-label d-none d-md-inline-block text-right" for="package_qty">
                                        Qty <span class="required">*</span>
                                    </label>
                                    <div class="col-md-7 col-lg-4">
                                        <input type="text" id="package_qty" name="package_qty" class="form-control numbercurrency" placeholder="0" value="<?php echo ( $data_package ? $data_package->qty : ''); ?>">
                                        <small class="d-block text-muted">Qty Produk per-Paket</small>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-md-3 col-form-label form-control-label d-md-none" for="package_weight">
                                        <?php echo lang('weight'); ?> <span class="required">*</span>
                                    </label>
                                    <label class="col-md-3 col-form-label form-control-label d-none d-md-inline-block text-right" for="package_weight">
                                        <?php echo lang('weight'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-7 col-lg-4">
                                        <div class="input-group input-group-merge">
                                            <input type="text" id="package_weight" name="package_weight" class="form-control numbercurrency" placeholder="0" value="<?php echo ( $data_package ? $data_package->weight : ''); ?>">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Liter</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if ( $form_point = false ) { ?>
                                    <div class="form-group row mb-3">
                                        <label class="col-md-3 col-form-label form-control-label d-md-none" for="package_point">
                                            <?php echo lang('point'); ?>  <span class="required">*</span>
                                        </label>
                                        <label class="col-md-3 col-form-label form-control-label d-none d-md-inline-block text-right" for="package_point">
                                            <?php echo lang('point'); ?>  <span class="required">*</span>
                                        </label>
                                        <div class="col-md-7 col-lg-4">
                                            <input type="text" id="package_point" name="package_point" class="form-control numberdecimal" placeholder="0" value="<?php echo ( $data_package ? ($data_package->point+0) : ''); ?>">
                                        </div>
                                    </div>
                                <?php } ?>
                                <hr class="my-4" />
                                <div class="form-group row mb-1 d-none">
                                    <?php 
                                        $mix_checked = $lock_checked = ''; 
                                        if ( $data_package ) {
                                            $mix_checked    = ( $data_package->is_mix ) ? 'checked="checked"' : ''; 
                                            $lock_checked   = ( $data_package->lock_qty && $data_package->is_mix ) ? 'checked="checked"' : ''; 
                                        }
                                    ?>
                                    <label class="col-md-3 col-form-label form-control-label"></label>
                                    <div class="col-md-7 col-lg-4">
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input type="checkbox" class="custom-control-input" name="package_mix" id="package_mix" value="1" <?php echo set_checkbox( 'package_mix', '1' ); ?> <?php echo $mix_checked; ?>>
                                            <label class="custom-control-label font-weight-bold" for="package_mix"><?php echo lang('product'); ?> Mix </label>
                                        </div>
                                        <div class="custom-control custom-checkbox mb-3 input-lock-qty" style="display: none">
                                            <input type="checkbox" class="custom-control-input" name="lock_qty" id="lock_qty" value="1" <?php echo set_checkbox( 'lock_qty', '1' ); ?> <?php echo $lock_checked; ?>>
                                            <label class="custom-control-label font-weight-bold" for="lock_qty">Fixed Qty and Price </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-md-3 col-form-label form-control-label d-md-none" for="package_product">
                                        <?php echo lang('product'); ?> 
                                    </label>
                                    <label class="col-md-3 col-form-label form-control-label d-none d-md-inline-block text-right" for="package_product">
                                        <?php echo lang('product'); ?> 
                                    </label>
                                    <div class="col-md-9 col-lg-9">
                                        <div class="input-group">
                                            <select class="form-control" name="select_product" id="select_product">
                                                <option value="" disabled="" selected="">-- <?php echo lang('select').' '.lang('product'); ?> --</option>
                                                <?php
                                                    if ( $products = ddm_products(0, true) ) {
                                                        foreach ($products as $key => $row) {
                                                            echo '<option value="'. $row->id .'" 
                                                                price1="'.$row->price_agent1.'" 
                                                                price2="'.$row->price_agent2.'" 
                                                                price3="'.$row->price_agent3.'"
                                                                bv1="'.$row->bv1.'" 
                                                                bv2="'.$row->bv2.'" 
                                                                bv3="'.$row->bv3.'">'. ucwords($row->name) .'</option>';
                                                        }   
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="table-responsive my-3" style="border: 1px solid #172b4d; border-top: none; border-bottom: none;">
                                            <table class="table align-items-center table-flush" id="list_table_product_package">
                                                <thead>
                                                    <tr style="background-color: #172b4d; color: #fff">
                                                        <th scope="col"><?php echo lang('product'); ?></th>
                                                        <th scope="col" class="text-right">Qty</th>
                                                        <th scope="col" class="text-center">Wilayah</th>
                                                        <th scope="col" class="text-center"><?php echo lang('price'); ?></th>
                                                        <th scope="col" class="text-center">BV</th>
                                                        <th scope="col" class="text-center" style="width: 10%"><?php echo lang('actions'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        if ( $data_product ) {
                                                            foreach ($data_product as $key => $row) {
                                                                $subtotal1      = ( isset($row['subtotal1']) ? $row['subtotal1'] : $row['qty'] * $row['price1'] );
                                                                $subtotal2      = ( isset($row['subtotal2']) ? $row['subtotal2'] : $row['qty'] * $row['price2'] );
                                                                $subtotal3      = ( isset($row['subtotal3']) ? $row['subtotal3'] : $row['qty'] * $row['price3'] );
                                                                
                                                                $subtotalbv1    = ( isset($row['subtotalbv1']) ? $row['subtotalbv1'] : $row['qty'] * ( isset($row['bv1']) ? $row['bv1'] : 0 ) );
                                                                $subtotalbv2    = ( isset($row['subtotalbv2']) ? $row['subtotalbv2'] : $row['qty'] * ( isset($row['bv2']) ? $row['bv2'] : 0 ) );
                                                                $subtotalbv3    = ( isset($row['subtotalbv3']) ? $row['subtotalbv3'] : $row['qty'] * ( isset($row['bv3']) ? $row['bv3'] : 0 ) );
                                                                
                                                                echo '
                                                                <tr class="tr-input-product" data-id="'.$row['id'].'">
                                                                    <td class="py-1"><b>'.$row['name'].'</b></td>
                                                                    <td class="py-1 text-right">
                                                                        <input type="text" id="products_qty_'.$row['id'].'" name="products['.$row['id'].'][qty]" 
                                                                            class="form-control form-control-sm numbercurrency text-right input-products" 
                                                                            style="min-width:60px"
                                                                            value="'.$row['qty'].'"
                                                                            data-id="'.$row['id'].'" 
                                                                            data-type="qty" />
                                                                    </td>
                                                                    <td class="py-1 text-center" style="width: 50px !important;">
                                                                        <div class="form-control form-control-sm" style="border-color: #FFFFFF;">WILAYAH 1</div>
                                                                        <div class="form-control form-control-sm" style="border-color: #FFFFFF;">WILAYAH 2</div>
                                                                        <div class="form-control form-control-sm" style="border-color: #FFFFFF;">WILAYAH 3</div>
                                                                    </td>
                                                                    <td class="py-1 text-right">
                                                                        <input type="text" id="products_price1_'.$row['id'].'" name="products['.$row['id'].'][price1]" 
                                                                            class="form-control form-control-sm numbercurrency text-right input-products" 
                                                                            style="min-width:80px" 
                                                                            readonly="readonly"
                                                                            value="'.$row['price1'].'"
                                                                            data-id="'.$row['id'].'" 
                                                                            data-type="price1" />
                                                                            
                                                                        <input type="text" id="products_price2_'.$row['id'].'" name="products['.$row['id'].'][price2]" 
                                                                            class="form-control form-control-sm numbercurrency text-right input-products" 
                                                                            style="min-width:80px"
                                                                            readonly="readonly"
                                                                            value="'.$row['price2'].'"
                                                                            data-id="'.$row['id'].'" 
                                                                            data-type="price2" />
                                                                            
                                                                        <input type="text" id="products_price3_'.$row['id'].'" name="products['.$row['id'].'][price3]" 
                                                                            class="form-control form-control-sm numbercurrency text-right input-products"
                                                                            style="min-width:80px"
                                                                            readonly="readonly"
                                                                            value="'.$row['price3'].'" 
                                                                            data-id="'.$row['id'].'" 
                                                                            data-type="price3" />

                                                                        <input type="hidden" id="products_subtotal1_'.$row['id'].'" name="products['.$row['id'].'][subtotal1]" class="form-control" value="'.$subtotal1.'" />
                                                                        <input type="hidden" id="products_subtotal2_'.$row['id'].'" name="products['.$row['id'].'][subtotal2]" class="form-control" value="'.$subtotal2.'" />
                                                                        <input type="hidden" id="products_subtotal3_'.$row['id'].'" name="products['.$row['id'].'][subtotal3]" class="form-control" value="'.$subtotal3.'" />
                                                                    </td>
                                                                    <td class="py-1 text-right">
                                                                        <input type="text" id="products_bv1_'.$row['id'].'" name="products['.$row['id'].'][bv1]" 
                                                                            class="form-control form-control-sm numbercurrency text-right input-products" 
                                                                            style="min-width:80px" 
                                                                            readonly="readonly"
                                                                            value="'.( isset($row['bv1']) ? $row['bv1'] : 0 ).'"
                                                                            data-id="'.$row['id'].'" 
                                                                            data-type="bv1" />
                                                                            
                                                                        <input type="text" id="products_bv2_'.$row['id'].'" name="products['.$row['id'].'][bv2]" 
                                                                            class="form-control form-control-sm numbercurrency text-right input-products" 
                                                                            style="min-width:80px"
                                                                            readonly="readonly"
                                                                            value="'.( isset($row['bv2']) ? $row['bv2'] : 0 ).'"
                                                                            data-id="'.$row['id'].'" 
                                                                            data-type="bv2" />
                                                                            
                                                                        <input type="text" id="products_bv3_'.$row['id'].'" name="products['.$row['id'].'][bv3]" 
                                                                            class="form-control form-control-sm numbercurrency text-right input-products"
                                                                            style="min-width:80px"
                                                                            readonly="readonly"
                                                                            value="'.( isset($row['bv3']) ? $row['bv3'] : 0 ).'" 
                                                                            data-id="'.$row['id'].'" 
                                                                            data-type="bv3" />
                                                                            
                                                                        <input type="hidden" id="products_subtotalbv1_'.$row['id'].'" name="products['.$row['id'].'][subtotalbv1]" class="form-control" value="'.$subtotalbv1.'" />
                                                                        <input type="hidden" id="products_subtotalbv2_'.$row['id'].'" name="products['.$row['id'].'][subtotalbv2]" class="form-control" value="'.$subtotalbv2.'" />
                                                                        <input type="hidden" id="products_subtotalbv3_'.$row['id'].'" name="products['.$row['id'].'][subtotalbv3]" class="form-control" value="'.$subtotalbv3.'" />
                                                                    </td>
                                                                    <td class="py-1 text-center">
                                                                        <input type="hidden" name="products['.$row['id'].'][id]" value="'.$row['id'].'" class="form-control d-none" />
                                                                        <input type="hidden" name="products['.$row['id'].'][name]" value="'.$row['name'].'" class="form-control d-none" />
                                                                        <button class="btn btn-sm btn-outline-warning btn-remove-product-package" type="button" title="Remove" 
                                                                            data-id="'.$row['id'].'" 
                                                                            data-qty="'.$row['qty'].'"
                                                                            data-price1="'.$row['price1'].'"
                                                                            data-price2="'.$row['price2'].'"
                                                                            data-price3="'.$row['price3'].'"
                                                                            data-bv1="'.( isset($row['bv1']) ? $row['bv1'] : 0 ).'"
                                                                            data-bv2="'.( isset($row['bv2']) ? $row['bv2'] : 0 ).'"
                                                                            data-bv3="'.( isset($row['bv3']) ? $row['bv3'] : 0 ).'"
                                                                            data-subtotal1="'.$subtotal1.'"
                                                                            data-subtotal2="'.$subtotal2.'"
                                                                            data-subtotal3="'.$subtotal3.'"
                                                                            data-subtotalbv1="'.$subtotalbv1.'"
                                                                            data-subtotalbv2="'.$subtotalbv2.'"
                                                                            data-subtotalbv3="'.$subtotalbv3.'" >
                                                                        <i class="fa fa-times"></i></button>
                                                                    </td>
                                                                </tr>';
                                                            }   
                                                        } else {
                                                            echo '<tr class="data-empty"><td colspan="5" class="text-center">Produk belum ada yang di pilih.</td></tr>';
                                                        }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="6" style="min-height: 20px"></th>
                                                    </tr>
                                                    <tr style="background-color: #172b4d; color: #fff">
                                                        <th class="heading text-capitalize py-2">
                                                            <span class="heading-small mix"><?php echo lang('total'); ?></span>
                                                        </th>
                                                        <th class="heading text-right py-2">
                                                            <span class="total_qty"><?php echo ddm_accounting($total_qty); ?></span>
                                                        </th>
                                                        <th class="py-1 text-center" style="width: 50px !important;">
                                                            <div>SUBTOTAL WILAYAH 1</div>
                                                            <div>SUBTOTAL WILAYAH 2</div>
                                                            <div>SUBTOTAL WILAYAH 3</div>
                                                        </th>
                                                        <th class="py-2" style="min-width: 300px;">
                                                            <div class="row mix">
                                                                <div class="col">
                                                                    ( <span class="total_qty_mix1"><?php echo ddm_accounting($total_qty) .' x '. ( $total_qty > 0 ? ddm_accounting(($total_price1/$total_qty)) : 0 ); ?></span> )
                                                                </div>
                                                                <div class="col text-right">
                                                                    <span class="total_price1"><?php echo ddm_accounting($total_price1); ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="row mix">
                                                                <div class="col">
                                                                    ( <span class="total_qty_mix2"><?php echo ddm_accounting($total_qty) .' x '. ( $total_qty > 0 ? ddm_accounting(($total_price2/$total_qty)) : 0 ); ?></span> )
                                                                </div>
                                                                <div class="col text-right">
                                                                    <span class="total_price2"><?php echo ddm_accounting($total_price2); ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="row mix">
                                                                <div class="col">
                                                                    ( <span class="total_qty_mix3"><?php echo ddm_accounting($total_qty) .' x '. ( $total_qty > 0 ? ddm_accounting(($total_price3/$total_qty)) : 0 ); ?></span> )
                                                                </div>
                                                                <div class="col text-right">
                                                                    <span class="total_price3"><?php echo ddm_accounting($total_price3); ?></span>
                                                                </div>
                                                            </div>
                                                        </th>
                                                        <th class="py-2" style="min-width: 300px;">
                                                            <div class="row mix">
                                                                <div class="col">
                                                                    ( <span class="total_qty_bv1"><?php echo ddm_accounting($total_qty) .' x '. ( $total_qty > 0 ? ddm_accounting(($total_bv1/$total_qty)) : 0 ); ?></span> )
                                                                </div>
                                                                <div class="col text-right">
                                                                    <span class="total_bv1"><?php echo ddm_accounting($total_bv1); ?></span>
                                                                    
                                                                </div>
                                                            </div>
                                                            <div class="row mix">
                                                                <div class="col">
                                                                    ( <span class="total_qty_bv2"><?php echo ddm_accounting($total_qty) .' x '. ( $total_qty > 0 ? ddm_accounting(($total_bv2/$total_qty)) : 0 ); ?></span> )
                                                                </div>
                                                                <div class="col text-right">
                                                                    <span class="total_bv2"><?php echo ddm_accounting($total_bv2); ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="row mix">
                                                                <div class="col">
                                                                    ( <span class="total_qty_bv3"><?php echo ddm_accounting($total_qty) .' x '. ( $total_qty > 0 ? ddm_accounting(($total_bv3/$total_qty)) : 0 ); ?></span> )
                                                                </div>
                                                                <div class="col text-right">
                                                                    <span class="total_bv3"><?php echo ddm_accounting($total_bv3); ?></span>
                                                                </div>
                                                            </div>
                                                        </th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-6">
                                    <label class="col-md-3 col-form-label form-control-label d-md-none" for="editor">
                                        Deskripsi
                                    </label>
                                    <label class="col-md-3 col-form-label form-control-label d-none d-md-inline-block text-right" for="editor">
                                        Deskripsi
                                    </label>
                                    <div class="col-md-9 col-lg-7">
                                        <div id="editor" data-quill-placeholder="Deskripsi Paket Produk">
                                            <?php echo ( $data_package ? $data_package->description : ''); ?>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4" />
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary bg-gradient-default my-2">
                                        <?php echo lang('save') .' '. lang('package') .' '. lang('product'); ?>
                                    </button>
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
