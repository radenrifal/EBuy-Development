<!-- BEGIN CONFIRMATION MODAL -->
<div class="modal fade" id="modal-save-member" tabindex="-1" role="dialog" aria-labelledby="modal-save-member" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-user-plus"></i> <?php echo lang('reg_new_member'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" data-admin="<?php echo $is_admin ? 1 : 0; ?>">
                <div class="row">
                    <label class="col-md-5 text-right"><?php echo lang('username'); ?> : </label>
                    <span class="col-md-7 text-lowercase confirm-new-member confirm-new-member-username"></span>
                </div>
                <div class="row">
                    <label class="col-md-5 text-right"><?php echo lang('name'); ?> : </label>
                    <span class="col-md-7 text-uppercase confirm-new-member confirm-new-member-name"></span>
                </div>
                <div class="row">
                    <label class="col-md-5 text-right"><?php echo lang('reg_email'); ?> : </label>
                    <span class="col-md-7 text-lowercase confirm-new-member confirm-new-member-email"></span>
                </div>
                <div class="row">
                    <label class="col-md-5 text-right"><?php echo lang('username').'/'.lang('name'); ?> Sponsor : </label>
                    <span class="col-md-7 confirm-new-member confirm-new-member-sponsor"></span>
                </div>
                <hr>
                <h5><strong><?php echo lang('reg_bank_information'); ?></strong></h5>
                <div class="row">
                    <label class="col-md-5 text-right"><?php echo lang('reg_bank'); ?> : </label>
                    <span class="col-md-7 text-uppercase confirm-new-member confirm-new-member-bank"></span>
                </div>
                <div class="row">
                    <label class="col-md-5 text-right"><?php echo lang('reg_no_rekening'); ?> : </label>
                    <span class="col-md-7 confirm-new-member confirm-new-member-bill"></span>
                </div>
                <div class="row">
                    <label class="col-md-5 text-right"><?php echo lang('reg_pemilik_rek'); ?> : </label>
                    <span class="col-md-7 text-uppercase confirm-new-member confirm-new-member-bill-name"></span>
                </div>
                <br>
                <h5 class="heading-small"><?php echo lang('reg_persetujuan'); ?></h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo lang('cancel'); ?></button>
                <button type="button" class="btn btn-primary" id="do_save_member" data-formid="<?php echo $formid; ?>"><?php echo lang('continue'); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- END CONFIRMATION MODAL -->

<!-- BEGIN INFORMATION SUCCESS SAVE MEMBER MODAL -->
<div class="modal fade" id="modal-success-save" tabindex="-1" role="dialog" aria-labelledby="modal-success-save" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-check"></i> <?php echo lang('reg_register_success'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php echo lang('reg_registering_member'); ?> :</p>
                <div class="note note-info" id="success_member"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END INFORMATION SUCCESS SAVE MEMBER MODAL -->