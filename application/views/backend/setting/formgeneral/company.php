<form role="form" method="post" action="<?php echo base_url('setting/updatecompany'); ?>" id="form-setting-company" class="form-horizontal">
    <div class="card-body wrapper-setting-company">
        <div class="form-group row mb-2">
            <label class="col-md-3 col-form-label form-control-label" for="company_name">Nama Perusahaan <span class="required">*</span></label>
            <div class="col-md-9">
                <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-building"></i></span>
                    </div>
                    <input type="text" name="company_name" id="company_name" class="form-control" placeholder="" value="<?php echo get_option('company_name'); ?>">
                </div>
            </div>
        </div>

        <!-- No. Telp/HP -->
        <div class="form-group row mb-2">
            <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_no_telp'); ?> <span class="required">*</span></label>
            <div class="col-md-9">
                <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                        <span class="input-group-text">+62</span>
                    </div>
                    <input type="text" name="company_phone" id="company_phone" class="form-control numbermask phonenumber" placeholder="8xxxxxxxxx" value="<?php echo get_option('company_phone'); ?>" />
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                    </div>
                </div>
            </div>
        </div>  

        <!-- Email -->
        <div class="form-group row mb-2">
            <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_email'); ?> <span class="required">*</span></label>
            <div class="col-md-9">
                <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                    </div>
                    <input type="text" name="company_email" id="company_email" class="form-control text-lowercase" placeholder="<?php echo lang('reg_email'); ?>" value="<?php echo get_option('company_email'); ?>"/>
                </div>
            </div>
        </div>

        <!-- Province -->
        <div class="form-group row mb-2">
            <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_provinsi'); ?> <span class="required">*</span></label>
            <div class="col-md-9">
                <select class="form-control select_province" name="company_province" id="company_province" data-form="register" data-url="<?php echo base_url('address/selectprovince'); ?>" data-toggle="select2">
                    <option value=""><?php echo lang('reg_pilih_provinsi'); ?></option>
                    <?php
                        $company_province = get_option('company_province');
                        $province = ddm_provinces();
                        if( !empty($province) ){
                            foreach($province as $p){
                                $selected = ( $company_province == $p->id ) ? 'selected' : '';
                                echo '<option value="'.$p->id.'" '.$selected.'>'.$p->province_name.'</option>';
                            }
                        }
                    ?> 
                </select>
            </div>
        </div>   

        <!-- City -->
        <div class="form-group row mb-2">
            <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_kota'); ?> <span class="required">*</span></label>
            <div class="col-md-9">
                <select class="form-control select_district" name="company_city" id="company_city" data-toggle="select2">
                    <option value=""><?php echo lang('reg_pilih_kota'); ?></option>
                    <?php
                        $company_city = get_option('company_city');
                        $cities = ddm_districts_by_province($company_province);
                        if( !empty($cities) ){
                            foreach($cities as $c){
                                $selected = ( $company_city == $c->id ) ? 'selected' : '';
                                echo '<option value="'.$c->id.'" '.$selected.'>'.$c->district_type.' '.$c->district_name.'</option>';
                            }
                        }
                    ?>
                </select>
            </div>
        </div>   

        <!-- Alamat 1 -->
        <div class="form-group row mb-0">
            <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_alamat'); ?> <span class="required">*</span></label>
            <div class="col-md-9">
                <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-square-pin"></i></span>
                    </div>
                    <input type="text" name="company_address" id="company_address" class="form-control" placeholder="Alamat Lengkap" value="<?php echo get_option('company_address'); ?>" />
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