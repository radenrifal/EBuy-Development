<!-- Personal Info -->
<?php if( $as_member ): ?>

    <?php echo form_open( 'member/personalinfo', array( 'id'=>'personal', 'class'=>'form-horizontal', 'role'=>'form', 'enctype'=>'multipart/form-data' ) ); ?>

        <?php if( !empty($member_other) && $member_other->type == MEMBER ): ?>
            <input type="hidden" name="member_id" value="<?php echo $member_other->id; ?>" />
        <?php endif ?>

        <h6 class="heading-small text-muted mb-4">Informasi Akun</h6>
        <div class="pl-lg-4">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-control-label" for="member_username"><?php echo lang('username'); ?></label>
                        <input type="text" name="member_username" id="member_username" class="form-control text-lowercase" placeholder="Username" value="<?php echo $the_member->username; ?>" disabled="disabled">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-control-label" for="member_name"><?php echo lang('name'); ?></label>
                        <input type="text" name="member_name" id="member_name" class="form-control text-uppercase" value="<?php echo $the_member->name; ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-control-label" for="member_email"><?php echo lang('reg_email'); ?></label>
                        <input type="email" name="member_email" id="member_email" class="form-control text-lowercase" value="<?php echo $the_member->email; ?>">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-control-label" for="member_phone"><?php echo lang('reg_no_telp'); ?></label>
                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text">+62</span>
                            </div>
                            <input type="text" name="member_phone" id="member_phone" class="form-control numbermask phonenumber" value="<?php echo $the_member->phone; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="my-4" />
        <!-- Address -->
        <h6 class="heading-small text-muted mb-4">Informasi Alamat</h6>
        <div class="pl-lg-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-control-label" for="member_address"><?php echo lang('reg_alamat'); ?></label>
                        <input type="text" name="member_address" id="member_address" class="form-control" value="<?php echo $the_member->address; ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <?php
                    $member_province    = $the_member->province;
                    $member_city        = $the_member->district;
                    $member_subdistrict = $the_member->subdistrict;
                    $provinces          = ddm_provinces();
                    $cities             = ddm_districts_by_province($member_province);
                    $subdistricts       = ddm_subdistricts_by_district($member_city);
                ?>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label" for="member_province"><?php echo lang('reg_provinsi'); ?></label>
                        <select class="form-control select_province" name="member_province" id="member_province" data-form="profile" data-url="<?php echo base_url('address/selectprovince'); ?>" data-toggle="select2">
                            <option value=""><?php echo lang('reg_pilih_provinsi'); ?></option>
                            <?php
                                if( !empty($provinces) ){
                                    foreach($provinces as $p){
                                        $selected = ( $p->id == $member_province ) ? 'selected=""' : ''; 
                                        echo '<option value="'.$p->id.'" '.$selected.'>'.$p->province_name.'</option>';
                                    }
                                }
                            ?> 
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label" for="member_district"><?php echo lang('reg_kota'); ?></label>
                        <select class="form-control select_district" name="member_district" id="member_district" data-url="<?php echo base_url('address/selectdistrict'); ?>" data-toggle="select2" >
                            <option value=""><?php echo lang('reg_pilih_kecamatan'); ?></option>
                            <?php
                                if( !empty($cities) ){
                                    foreach($cities as $c){
                                        $selected   = ( $c->id == $member_city ) ? 'selected=""' : ''; 
                                        $city_name  = $c->district_type .' '. $c->district_name; 
                                        echo '<option value="'.$c->id.'" '.$selected.'>'.$city_name.'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label" for="member_subdistrict"><?php echo lang('reg_kecamatan'); ?></label>
                        <select class="form-control select_subdistrict" name="member_subdistrict" id="member_subdistrict" data-toggle="select2">
                            <option value=""><?php echo lang('reg_pilih_kecamatan'); ?></option>
                            <?php
                                if( !empty($subdistricts) ){
                                    foreach($subdistricts as $s){
                                        $selected   = ( $s->id == $member_subdistrict ) ? 'selected=""' : ''; 
                                        echo '<option value="'.$s->id.'" '.$selected.'>'.$s->subdistrict_name.'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <hr class="my-4" />
        <!-- Description -->
        <h6 class="heading-small text-muted mb-4">Informasi Akun Bank</h6>
        <?php 
            $member_bank    = $the_member->bank; 
            $banks          = ddm_banks();
        ?>
        <div class="pl-lg-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-control-label" for="member_bank"><?php echo lang('reg_bank'); ?></label>
                        <select class="form-control" name="member_bank" id="member_bank" data-toggle="select2">
                            <option value=""><?php echo lang('reg_pilih_bank'); ?></option>
                            <?php
                                if( !empty($banks) ){
                                    foreach($banks as $b){
                                        $selected   = ( $b->id == $member_bank ) ? 'selected=""' : ''; 
                                        echo '<option value="'.$b->id.'" '.$selected.'>'.$b->kode.' - '.$b->nama.'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-control-label" for="member_bill"><?php echo lang('reg_no_rekening'); ?></label>
                        <input type="text" class="form-control numbermask" name="member_bill" id="member_bill" placeholder="<?php echo lang('reg_no_rekening'); ?>"  value="<?php echo $the_member->bill; ?>" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-control-label" for="member_bill_name"><?php echo lang('reg_pemilik_rek'); ?></label>
                        <input type="text" class="form-control" name="member_bill_name" id="member_bill_name" placeholder="<?php echo lang('reg_pemilik_rek'); ?>" value="<?php echo $the_member->bill_name; ?>" >
                    </div>
                </div>
            </div>
        </div>
        <hr class="my-4" />
        <div class="text-center">
            <button type="submit" class="btn btn-primary bg-gradient-default my-2"><?php echo lang('save'); ?></button>
        </div>
    <?php echo form_close(); ?>

<?php elseif($as_staff): ?>

    <?php echo form_open( 'member/staffinfo', array( 'id'=>'personal', 'class'=>'form-horizontal mb-4', 'role'=>'form' ) ); ?>
        <h6 class="heading-small text-muted mb-4">Informasi Akun</h6>
        <div class="pl-lg-4">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-control-label" for="member_username"><?php echo lang('username'); ?></label>
                        <input type="text" name="member_username" id="member_username" class="form-control text-lowercase" placeholder="Username" value="<?php echo $staff->username; ?>" disabled="disabled">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-control-label" for="member_name"><?php echo lang('name'); ?></label>
                        <input type="text" name="member_name" id="member_name" class="form-control text-uppercase" value="<?php echo $staff->name; ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-control-label" for="member_email"><?php echo lang('reg_email'); ?></label>
                        <input type="email" name="member_email" id="member_email" class="form-control" value="<?php echo $staff->email; ?>">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-control-label" for="member_phone"><?php echo lang('reg_no_telp'); ?></label>
                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text">+62</span>
                            </div>
                            <input type="text" name="member_phone" id="member_phone" class="form-control numbermask phonenumber" value="<?php echo $staff->phone; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="my-4" />
        <div class="text-center">
            <button type="submit" class="btn btn-primary bg-gradient-default my-2"><?php echo lang('save'); ?> Profile</button>
        </div>
    <?php echo form_close(); ?>

    <div class="accordion" id="accordionChangePassword">
        <div class="card mb-3">
            <div class="card-header bg-gradient-info" id="headChangePassword" data-toggle="collapse" data-target="#collapseChangePassword" aria-expanded="false" aria-controls="collapseChangePassword">
                <h5 class="text-white mb-0">Ganti Password</h5>
            </div>
            <div id="collapseChangePassword" class="collapse" aria-labelledby="headChangePassword" data-parent="#accordionChangePassword">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <?php echo form_open( 'member/changepasswordstaff', array( 'id'=>'cpassword', 'role'=>'form' ) ); ?>
                                <div class="form-group">
                                    <label class="control-label">Password Lama</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="cur_pass" id="cur_pass" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-flat btn-white pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Password Baru</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="new_pass" id="new_pass" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-flat btn-white pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Konfirmasi Password Baru</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="cnew_pass" id="cnew_pass" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-flat btn-white pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <hr class="my-4" />
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary bg-gradient-default my-2">Ganti Password</button>
                                </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="save_cpassword" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-lock"></i>  Ganti Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin akan mengubah password ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-default" id="do_save_cpassword" data-form="cpassword">Lanjut</button>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>

    <?php echo form_open( 'member/admininfo', array( 'id'=>'personal', 'class'=>'form-horizontal mb-4', 'role'=>'form' ) ); ?>
        <h6 class="heading-small text-muted mb-4">Informasi Akun</h6>
        <div class="pl-lg-4">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-control-label" for="member_username"><?php echo lang('username'); ?></label>
                        <input type="text" name="member_username" id="member_username" class="form-control text-lowercase" placeholder="Username" value="<?php echo $member->username; ?>" disabled="disabled">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-control-label" for="member_name"><?php echo lang('name'); ?></label>
                        <input type="text" name="member_name" id="member_name" class="form-control text-uppercase" value="<?php echo $member->name; ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-control-label" for="member_email"><?php echo lang('reg_email'); ?></label>
                        <input type="email" name="member_email" id="member_email" class="form-control" value="<?php echo $member->email; ?>">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-control-label" for="member_phone"><?php echo lang('reg_no_telp'); ?></label>
                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text">+62</span>
                            </div>
                            <input type="text" name="member_phone" id="member_phone" class="form-control numbermask phonenumber" value="<?php echo $member->phone; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="my-4" />
        <div class="text-center">
            <button type="submit" class="btn btn-primary bg-gradient-default my-2"><?php echo lang('save'); ?> Profile</button>
        </div>
    <?php echo form_close(); ?>

    <div class="accordion" id="accordionChangePassword">
        <div class="card mb-3">
            <div class="card-header bg-gradient-info" id="headChangePassword" data-toggle="collapse" data-target="#collapseChangePassword" aria-expanded="false" aria-controls="collapseChangePassword">
                <h5 class="text-white mb-0">Ganti Password</h5>
            </div>
            <div id="collapseChangePassword" class="collapse" aria-labelledby="headChangePassword" data-parent="#accordionChangePassword">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <?php echo form_open( 'member/changepassword', array( 'id'=>'cpassword', 'role'=>'form' ) ); ?>
                                <div class="form-group">
                                    <label class="control-label">Password Lama</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="cur_pass" id="cur_pass" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-flat btn-white pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Password Baru</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="new_pass" id="new_pass" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-flat btn-white pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Konfirmasi Password Baru</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="cnew_pass" id="cnew_pass" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-flat btn-white pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <hr class="my-4" />
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary bg-gradient-default my-2">Ganti Password</button>
                                </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="save_cpassword" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-lock"></i>  Ganti Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin akan mengubah password ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-default" id="do_save_cpassword" data-form="cpassword">Lanjut</button>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<!-- BEGIN CONFIRMATION MODAL -->
<div class="modal fade" id="save_profile" tabindex="-1" role="dialog" aria-labelledby="modalsave_profile" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-edit"></i>  Profil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah profil <?php echo ( !empty($member_other) && $member_other->type == MEMBER ? 'anggota <strong>' . $member_other->username . '</strong>' : 'Anda' ); ?> sudah benar ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="do_save_profile">Lanjut</button>
            </div>
        </div>
    </div>
</div>
<!-- END CONFIRMATION MODAL -->