<?php echo form_open( 'member/changepassword', array( 'id'=>'cpassword', 'role'=>'form', 'enctype'=>'multipart/form-data' ) ); ?>
    <input type="hidden" name="pass_type" value="login" />
    <?php if ( ! $is_admin ) { ?>
        <div class="form-group">
            <label class="control-label">Password Lama</label>
            <div class="input-group">
                <input type="password" class="form-control" name="cur_pass" id="cur_pass" />
                <span class="input-group-btn">
                    <button class="btn btn-flat btn-white pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                </span>
            </div>
        </div>
    <?php } ?>
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
                Apakah Anda yakin akan mengubah password <?php echo $is_admin ? 'Anggota ini ?' : 'Anda ?'; ?>
                <?php 
                    $logout = false;
                    if ( !$is_admin && $logout ):
                        echo br()."Setelah password berhasil di ubah Anda akan otomatis keluar. Anda dapat login kembali dengan password baru.";
                    endif;
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-default" id="do_save_cpassword" data-form="cpassword">Lanjut</button>
            </div>
        </div>
    </div>
</div>