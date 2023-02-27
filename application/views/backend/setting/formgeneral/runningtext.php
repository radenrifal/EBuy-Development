<div class="text_running_wraper">
    <h4 class="box-title"><?php echo lang('running_text'); ?></h4>
    <hr style="margin-top: 10px">
    <form class="horizontal-form" action="#" novalidate="novalidate">
        <div class="form-body">
            <div class="form-group bottom20">
                <textarea class="form-control" style="resize: vertical; min-height: 50px;" id="be_runningtext"><?php echo get_option('be_runningtext'); ?></textarea>
            </div>
            <button 
                type="button" 
                class="btn btn-flat bg-blue general-setting" 
                data-type="text" 
                data-id="be_runningtext" 
                data-wraper="text_running_wraper" 
                data-url="<?php echo base_url('setting/updatesetting/be_runningtext'); ?>">
                <?php echo lang('save') . ' ' . lang('menu_setting'); ?>
            </button>
        </div>
    </form>
</div>
