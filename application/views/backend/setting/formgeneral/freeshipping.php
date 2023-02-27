<div class="card-body qty_package_free_shipping_wraper">
    <div class="form-group row mb-2">
        <label class="col-md-3 col-form-label form-control-label">Min Order Paket Produk <span class="required">*</span></label>
        <div class="col-md-6">
            <div class="input-group input-group-merge">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-cart"></i></span>
                </div>
                <input type="text" name="qty_package_free_shipping" id="qty_package_free_shipping" class="form-control numbercurrency" value="<?php echo get_option('qty_package_free_shipping'); ?>" />
                <div class="input-group-append">
                    <span class="input-group-text"><?php echo lang('package'); ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <button 
                type="button" 
                class="btn btn-primary general-setting" 
                data-type="text" 
                data-id="qty_package_free_shipping" 
                data-wraper="qty_package_free_shipping_wraper" 
                data-url="<?php echo base_url('setting/updatesetting/qty_package_free_shipping'); ?>">
                <?php echo lang('save'); ?>
            </button>
        </div>
    </div>
</div>