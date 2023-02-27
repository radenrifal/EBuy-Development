<div class="card-body register_fee_wraper">
    <div class="form-group row mb-2">
        <label class="col-md-3 col-form-label form-control-label">Biaya Registrasi <span class="required">*</span></label>
        <div class="col-md-6">
            <div class="input-group input-group-merge">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
                </div>
                <input type="text" name="register_fee" id="register_fee" class="form-control numbercurrency" value="<?php echo get_option('register_fee'); ?>" />
            </div>
        </div>
        <div class="col-md-3">
            <button 
                type="button" 
                class="btn btn-primary general-setting" 
                data-type="text" 
                data-id="register_fee" 
                data-wraper="register_fee_wraper" 
                data-url="<?php echo base_url('setting/updatesetting/register_fee'); ?>">
                <?php echo lang('save'); ?>
            </button>
        </div>
    </div>
</div>