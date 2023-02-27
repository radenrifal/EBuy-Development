<div class="card-body">
    <div class="form-group row mb-0">
        <div class="col-md-2 mb-2">
            <label class="form-control-label">Qty <?php echo lang('package') ?></label>
            <input type="text" name="field[cfg_package_qty]" class="form-control numbermask cfg_point_package" value="<?php echo get_option('cfg_package_qty'); ?>" />
        </div>
        <div class="col-md-1 d-none d-md-inline-block">
            <label class="form-control-label" style="display: block">&nbsp;</label>
            <button type="button" class="btn btn-outline-primary general-setting" ><i class="fa fa-arrow-right"></i></button>
        </div>
        <div class="col-md-2">
            <label class="form-control-label"><?php echo lang('point') .' '. lang('package') ?></label>
            <input type="text" name="field[cfg_package_point]" class="form-control numbermask cfg_point_package" value="<?php echo get_option('cfg_package_point'); ?>" />
        </div>
        <div class="col-md-2">
            <label class="form-control-label" style="display: block">&nbsp;</label>
            <button 
                type="button" 
                class="btn btn-primary general-setting-each" 
                data-type="cfg_point_package" 
                data-url="<?php echo base_url('setting/updateallsetting'); ?>">
                <?php echo lang('save'); ?>
            </button>
        </div>
    </div>
</div>