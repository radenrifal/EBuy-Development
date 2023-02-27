<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_setting') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_setting_notification'); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0"><?php echo lang('menu_setting_notification'); ?> </h3>
                        </div>
                    </div>
                </div>
                <div class="table-container">
                    <table class="table align-items-center table-flush" id="notification_list" data-url="<?php echo base_url('setting/notificationlistdata'); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th class="width5 text-center">No</th>
                                <th class="width55 text-center"><?php echo lang('name') ?></th>
                                <th class="width15 text-center"><?php echo lang('type') ?></th>
                                <th class="width15 text-center"><?php echo lang('status') ?></th>
                                <th class="width10 text-center"><?php echo lang('actions') ?></th>
                            </tr>
                            <tr role="row" class="filter">
                                <td></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                                <td>
                                    <select name="search_type" class="form-control form-control-sm form-filter">
                                        <option value=""><?php echo lang('select'); ?>...</option>
                                        <option value="email">EMAIL</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="search_status" class="form-control form-control-sm form-filter">
                                        <option value=""><?php echo lang('select'); ?>...</option>
                                        <option value="active">AKTIIF</option>
                                        <option value="notactive">TIDAK AKTIF</option>
                                    </select>
                                </td>
                                <td style="text-align: center;">
                                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_notification_list" title="Search"><i class="fa fa-search"></i></button>
                                    <button class="btn btn-sm btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Form Promo Code -->
<div class="modal fade" id="modal-form-notification" tabindex="-1" role="dialog" aria-labelledby="modal-form-notification" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-envelope" id="notif_edit_icon"></i> <span id="notif_edit_title"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo base_url('setting/updatenotification'); ?>" role="form" method="post" class="form-horizontal" id="form_notif_edit" >
                <input type="hidden" name="notif_id" id="notif_id" class="hide">
                <input type="hidden" name="notif_type" id="notif_type" class="hide">
                <div class="modal-body wrapper_notif_edit py-2">
                    <div class="form-group mb-2">
                        <label class="control-label">Title <span class="required">*</span></label>
                        <input type="text" name="notif_title" id="notif_title" class="form-control">
                    </div>
                    <div class="form-group mb-2">
                        <label class="control-label">Status <span class="required">*</span></label>
                        <select name="notif_status" id="notif_status" class="form-control">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="form-group mb-0 display-none" id="content_email">
                        <label class="control-label">Pesan <span class="required">*</span></label>
                        <textarea class="form-control ckeditor" id="notif_content_email" name="notif_content_email" rows="5" style="resize: vertical;"></textarea> 
                    </div>
                    <div class="form-group mb-0 display-none" id="content_plain">
                        <label class="control-label">Pesan <span class="required">*</span></label>
                        <textarea class="form-control" id="notif_content_plain" name="notif_content_plain" rows="5" style="resize: vertical; height: 300px; padding: 5px 10px; border: 1px solid #2b579a;"></textarea> 
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

<div class="modal fade" id="notification_view_modal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <span class="label label-primary pull-right" id="notif_view_color" style="margin-right: 8px">
                        <i class="fa fa-envelope" id="notif_view_icon"></i>
                        <span id="notif_view_type">Email</span>
                    </span>
                    <b><span id="notif_view_title"></span></b>
                </h4>
            </div>
            <div class="modal-body" style="background-color: #F5F5F5">
                <div id="notif_view_content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-flat btn-warning" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>