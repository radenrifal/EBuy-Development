<?php
    $count_agents   = $this->Model_Member->count_by(array('type' => 2));
    $admindata      = '';
    if( $count_agents == 0 ){
        $admindata  = ddm_get_memberdata_by_id(1);
    }
?>

<!-- Username Sponsor -->
<?php if( $is_admin ): ?>
    <?php if($count_agents==0){ ?>
    <div class="form-group row mb-2">
        <div class="col-md-12">
            <div class="px-3 py-2 bg-gradient-warning text-white">
                Belum ada data Agen. <?=strtoupper($admindata->name)?> otomatis akan menjadi Sponsor
            </div>
        </div>
    </div>
    <?php }?>
    <div class="form-group row mb-2">
        <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_sponsor_username'); ?> <span class="required">*</span></label>
        <div class="col-md-9">
            <div class="input-group input-group-merge">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                </div>
                <input type="hidden" name="reg_member_sponsor_admin" id="reg_member_sponsor_admin" value="admin" />
                <input type="text" 
                    name="reg_member_sponsor" 
                    id="reg_member_sponsor" 
                    class="form-control text-lowercase" 
                    placeholder="<?php echo lang('reg_sponsor_username'); ?>" 
                    autocomplete="off"
                    value="<?php echo ( $count_agents==0 ? $admindata->username : '' ); ?>"
                    <?php echo ( $count_agents==0 ? 'readonly="readonly"' : '' ); ?> />
                <span class="input-group-append">
                    <button class="btn btn-primary" type="button" id="btn_search_sponsor" data-url="<?php echo base_url('member/searchsponsor'); ?>"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="form-group row mb-2">
        <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_sponsor_username'); ?> <span class="required">*</span></label>
        <div class="col-md-9">
            <input type="hidden" name="current_member_username" value="<?php echo $member->username; ?>" />
            <input type="hidden" name="current_member_name" value="<?php echo $member->name; ?>" />
            <div class="btn-group" data-toggle="buttons">
                <label id="other_sponsor" class="btn spon active">
                    <input name="sponsored" class="toggle sponsored d-none" type="radio" value="other_sponsor" checked="checked" />Sponsor
                </label>
                <label id="as_sponsor" class="btn spon">
                    <input name="sponsored" class="toggle sponsored d-none" type="radio" value="as_sponsor" /><?php echo lang('reg_saya_sponsor'); ?>
                </label>
            </div>
            <div id="sponsor_form" style="margin-top: 5px;">
                <div class="input-group">
                    <input type="text" name="reg_member_sponsor" id="reg_member_sponsor" class="form-control text-lowercase" placeholder="<?php echo lang('reg_sponsor_username'); ?>" autocomplete="off" />
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" id="btn_search_sponsor" 
                        data-url="<?php echo base_url('member/searchsponsor'); ?>" 
                        data-form="newmember"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<div id="sponsor_info">
    <?php if( $count_agents == 0 ){ ?>
    <div class="form-group row mb-2">
        <label class="col-md-3 col-form-label form-control-label"><?=lang("name")?> Sponsor &nbsp;</label>
        <div class="col-md-9">
            <div class="input-group input-group-merge">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                </div>
                <input type="hidden" name="reg_member_sponsor_id" class="form-control" value="<?=$admindata->id?>" />
                <input type="hidden" name="reg_member_sponsor_username" class="form-control" value="<?=strtolower($admindata->username)?>" />
                <input type="text" name="reg_member_sponsor_name_dsb" class="form-control" placeholder="Nama Sponsor" disabled="" value="<?=strtoupper($admindata->name)?>" />
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<hr class="mt-3 mb-3">

<!-- Username -->
<div class="form-group row mb-2">
	<label class="col-md-3 col-form-label form-control-label"><?php echo lang('username'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
            </div>
            <input type="text" name="reg_member_username" id="reg_member_username" class="form-control text-lowercase" placeholder="<?php echo lang('reg_username_ex'); ?>" autocomplete="off" data-url="<?php echo base_url('member/checkusernamestaff'); ?>" />
        </div>
    </div>
</div>

<!-- Password -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_password'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-lock"></i></span>
            </div>
            <input type="password" name="reg_member_password" id="reg_member_password" class="form-control" placeholder="<?php echo lang('reg_valid_password'); ?>" autocomplete="off" value="" />
            <div class="input-group-append">
                <button class="btn btn-primary pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
            </div>
        </div>
    </div>
</div>

<!-- Confirmed Password -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label">Confirm password <span class="required">*</span></label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-lock"></i></span>
            </div>
            <input type="password" name="reg_member_password_confirm" id="reg_member_password_confirm" class="form-control" placeholder="Konfirmasi Password" autocomplete="off" value="" />
            <div class="input-group-append">
                <button class="btn btn-primary pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
            </div>
        </div>
    </div>
</div>

<!-- Nama -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('name'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
            </div>
            <input type="text" name="reg_member_name" id="reg_member_name" class="form-control text-uppercase" placeholder="<?php echo lang('reg_fullname'); ?>" autocomplete="off" value="" />
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
            <input type="text" name="reg_member_email" id="reg_member_email" class="form-control" placeholder="<?php echo ucfirst(lang('reg_email')); ?>" data-url="<?php echo base_url('member/checkemail'); ?>" />
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
            <input type="text" name="reg_member_phone" id="reg_member_phone" class="form-control numbermask phonenumber" placeholder="8xxxxxxxxx" data-url="<?php echo base_url('member/checkphone'); ?>" />
            <div class="input-group-append">
                <span class="input-group-text"><i class="fa fa-phone"></i></span>
            </div>
        </div>
    </div>
</div>

<!-- NIK -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_nik'); ?></label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-address-card"></i></span>
            </div>
            <input type="text" name="reg_member_idcard" id="reg_member_idcard" class="form-control numbermask" placeholder="NIK" />
        </div>
    </div>
</div>    

<!-- Province -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_provinsi'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <select class="form-control select_province" name="reg_member_province" id="reg_member_province" data-form="register" data-url="<?php echo base_url('address/selectprovince'); ?>" data-toggle="select2">
            <option value=""><?php echo lang('reg_pilih_provinsi'); ?></option>
            <?php
                $province = ddm_provinces();
                if( !empty($province) ){
                    foreach($province as $p){
                        echo '<option value="'.$p->id.'">'.$p->province_name.' - WILAYAH '.$p->province_area.'</option>';
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
        <select class="form-control select_district" name="reg_member_district" id="reg_member_district" disabled="disabled" data-url="<?php echo base_url('address/selectdistrict'); ?>" data-toggle="select2">
            <option value=""><?php echo lang('reg_pilih_kota'); ?></option>
        </select>
    </div>
</div>  

<!-- District -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_kecamatan'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <select class="form-control select_subdistrict" name="reg_member_subdistrict" id="reg_member_subdistrict" disabled="disabled" data-toggle="select2">
            <option value=""><?php echo lang('reg_pilih_kecamatan'); ?></option>
        </select>
    </div>
</div>  

<!-- Alamat 1 -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_alamat'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="ni ni-square-pin"></i></span>
            </div>
            <input type="text" name="reg_member_address" id="reg_member_address" class="form-control" placeholder="Alamat Lengkap" />
        </div>
    </div>
</div>

<hr class="bottom10">
<!-- Bank -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_bank'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <select class="form-control" name="reg_member_bank" id="reg_member_bank" data-toggle="select2">
            <option value=""><?php echo lang('reg_pilih_bank'); ?></option>
            <?php
                $banks = ddm_banks();
                if( !empty($banks) ){
                    foreach($banks as $b){
                        echo '<option value="'.$b->id.'">'.$b->kode.' - '.$b->nama.'</option>';
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
            <input type="text" class="form-control numbermask" name="reg_member_bill" id="reg_member_bill" placeholder="<?php echo lang('reg_no_rekening'); ?>" data-url="<?php echo base_url('member/checkbill'); ?>" >
        </div>
    </div>
</div>

<!-- Bill Owner -->
<div class="form-group row">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_pemilik_rek'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
            </div>
            <input type="text" class="form-control text-uppercase" name="reg_member_bill_name" id="reg_member_bill_name" placeholder="<?php echo lang('reg_pemilik_rek'); ?>" >
        </div>
    </div>
</div>
