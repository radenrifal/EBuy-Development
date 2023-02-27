<div class="text_dashboard_wraper">
    <h4 class="box-title"><?php echo lang('text_dashboard'); ?></h4>
    <form class="horizontal-form" action="#" novalidate="novalidate">
        <div class="form-body">
            <!-- <div class="form-group bottom20">
                <textarea class="form-control ckeditor" style="resize: vertical; min-height: 100px;" id="be_dashboard_member"><?php echo get_option('be_dashboard_member'); ?></textarea>
            </div> -->
            <div class="form-group bottom20">
                <textarea class="form-control" style="resize: vertical; min-height: 450px" id="tinymce_member">
                    <?php echo get_option('be_dashboard_member'); ?>
                </textarea>
            </div>
            <button 
                type="button" 
                class="btn btn-flat bg-blue general-setting" 
                data-type="html" 
                data-id="be_dashboard_member" 
                data-wraper="text_dashboard_wraper" 
                data-url="<?php echo base_url('setting/updatesetting/be_dashboard_member'); ?>">
                <?php echo lang('save') . ' ' . lang('menu_setting'); ?>
            </button>
        </div>
    </form>
</div>