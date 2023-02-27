<div class="main-content" id="panel">
    <!-- BEGIN TOPBAR -->
    <?php $this->load->view(VIEW_BACK . 'template_topbar'); ?>
    <!-- END TOPBAR -->

    <!-- BEGIN CONTENT -->
    <?php if ( ddm_is_assuming() ): ?>
        <div class="px-3 py-2 bg-gradient-danger text-white">
            <?php 
                $_curr_member   = ddm_get_current_member();
                $_assume_member = $_curr_member->name . ' (' . $_curr_member->username . ')';
                $assumed_text   = lang('assumed_text');
                $assumed_text   = str_replace("%cur_member_name%", $_assume_member, $assumed_text);
                echo $assumed_text;
            ?>
        </div>
    <?php endif ?>

    <?php $this->load->view(VIEW_BACK . $main_content); ?>
    <!-- END CONTENT -->
    
    <!-- BEGIN FOOTER -->
    <div class="container-fluid mt-4">
        <footer class="footer pt-0">
            <div class="row align-items-center justify-content-lg-between">
                <div class="col-lg-12">
                    <div class="copyright text-center  text-lg-left  text-muted">
                        &copy; 2021 <a href="#!" class="font-weight-bold ml-1"><?php echo COMPANY_NAME; ?></a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <!-- END FOOTER -->
</div>