<!DOCTYPE HTML>
    <!-- <?php echo get_option('company_name'); ?> Template v1.0 -->
    
    <!--[if lt IE 7]><html dir="ltr" lang="en-US" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
    <!--[if IE 7]><html dir="ltr" lang="en-US" class="lt-ie9 lt-ie8"><![endif]-->
    <!--[if IE 8]><html dir="ltr" lang="en-US" class="lt-ie9"><![endif]-->
    <!--[if IE 9]><html dir="ltr" lang="en-US"  class="lt-ie10"> <![endif]-->
    <!--[if gt IE 9]><!--><html dir="ltr" lang="en-US" class="gt-ie9 non-ie no-js"> <!--<![endif]-->
    
    <!-- BEGIN TEMPLATE HEADER -->
    <?php $this->load->view(VIEW_BACK . 'template_header'); ?>
    <!-- END TEMPLATE HEADER -->

    <!-- BEGIN BODY -->
    <body>
        <!-- BEGIN SIDEBAR -->
        <?php $this->load->view(VIEW_BACK . 'template_sidebar'); ?>
        <!-- END SIDEBAR -->

        <!-- BEGIN TEMPLATE BODY -->
        <?php $this->load->view(VIEW_BACK . 'template_body'); ?>
        <!-- END TEMPLATE BODY -->

        <!-- BEGIN TEMPLATE FOOTER -->
        <?php $this->load->view(VIEW_BACK . 'template_footer'); ?>
        <!-- END TEMPLATE FOOTER -->
    </body>
    <!-- END BODY -->
</html>