<!DOCTYPE html>
    <!-- <?php echo COMPANY_NAME; ?> Login Template v1.0 -->
    
    <!--[if lt IE 7]><html dir="ltr" lang="en-US" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
    <!--[if IE 7]><html dir="ltr" lang="en-US" class="lt-ie9 lt-ie8"><![endif]-->
    <!--[if IE 8]><html dir="ltr" lang="en-US" class="lt-ie9"><![endif]-->
    <!--[if IE 9]><html dir="ltr" lang="en-US"  class="lt-ie10"> <![endif]-->
    <!--[if !IE]><!--><html lang="en" class="no-js"><!--<![endif]-->
    
    <!-- BEGIN HEAD -->
    <?php $this->load->view(VIEW_AUTH . 'login_header'); ?>
    <!-- END HEAD -->
    
    <!-- <body oncontextmenu='return false'> -->
    <body class="bg-full-screen-image">
        <!-- BEGIN LOGIN BODY -->
        <?php $this->load->view(VIEW_AUTH . 'login_body'); ?>
        <!-- END LOGIN BODY -->

        <!-- BEGIN LOGIN FOOTER -->
        <?php $this->load->view(VIEW_AUTH . 'login_footer'); ?>
        <!-- END LOGIN FOOTER -->
    </body>
</html>