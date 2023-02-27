<form role="form" method="post" action="<?php echo base_url('setting/updatecompanybilling'); ?>" id="form-setting-company-billing" class="form-horizontal">
    <div class="card-body wrapper-setting-company-billing">
        <!-- Bank -->
        <div class="form-group row mb-2">
            <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_bank'); ?> <span class="required">*</span></label>
            <div class="col-md-9">
                <select class="form-control" name="company_bank" id="company_bank" data-toggle="select2">
                    <option value=""><?php echo lang('reg_pilih_bank'); ?></option>
                    <?php
                        $company_bank =get_option('company_bank');
                        $banks = ddm_banks();
                        if( !empty($banks) ){
                            foreach($banks as $b){
                                $selected = ( $company_bank == $b->id ) ? 'selected' : '';
                                echo '<option value="'.$b->id.'" '.$selected.'>'.$b->kode.' - '.$b->nama.'</option>';
                            }
                        }
                    ?>
                </select>
            </div>
        </div>

        <!-- Bill Number -->
        <div class="form-group row mb-2">
            <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_no_rekening'); ?> <span class="required">*</span></label>
            <div class="col-md-9">
                <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
                    </div>
                    <input type="text" class="form-control numbermask" name="company_bill" id="company_bill" placeholder="<?php echo lang('reg_no_rekening'); ?>" value="<?php echo get_option('company_bill'); ?>" >
                </div>
            </div>
        </div>

        <!-- Bill Owner -->
        <div class="form-group row mb-0">
            <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_pemilik_rek'); ?> <span class="required">*</span></label>
            <div class="col-md-9">
                <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                    </div>
                    <input type="text" class="form-control text-uppercase" name="company_bill_name" id="company_bill_name" placeholder="<?php echo lang('reg_pemilik_rek'); ?>" value="<?php echo get_option('company_bill_name'); ?>" />
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer my-0">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <button type="submit" class="btn btn-primary my-0"><?php echo lang('save') . ' ' . lang('menu_setting'); ?></button>
            </div>
        </div>
    </div>
</form>