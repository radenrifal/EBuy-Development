<!DOCTYPE HTML>
    <!-- <?php echo get_option('company_name'); ?> Template v1.0 -->
    
    <!--[if lt IE 7]><html dir="ltr" lang="en-US" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
    <!--[if IE 7]><html dir="ltr" lang="en-US" class="lt-ie9 lt-ie8"><![endif]-->
    <!--[if IE 8]><html dir="ltr" lang="en-US" class="lt-ie9"><![endif]-->
    <!--[if IE 9]><html dir="ltr" lang="en-US"  class="lt-ie10"> <![endif]-->
    <!--[if gt IE 9]><!--><html dir="ltr" lang="en-US" class="gt-ie9 non-ie"> <!--<![endif]-->
    <head>
        <!-- Meta Tags -->
        <meta charset="UTF-8" />
        <meta name="description" content="<?php echo get_option('company_name'); ?>" />
        <meta name="author" content="<?php echo get_option('company_name'); ?>" />
      
        <!-- Mobile -->
        <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />
        
        <!-- Page Title -->
        <title><?php echo $title; ?></title>

        <!-- Shortcut Icon -->
        <link rel="shortcut icon" href="<?php echo BE_IMG_PATH .'logo.png'; ?>" type="image/x-icon">
        
        <!-- CSS Stylesheets -->
        <link rel="stylesheet" type="text/css" href="<?php echo MAINTENANCE_CSS_PATH; ?>style.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo MAINTENANCE_CSS_PATH; ?>960.css" />
    </head>
    
    <body>
        <div id="shim"></div>
        <div id="content">
            <h1 class="logo"><span class="two"><?php echo get_option('company_name'); ?></span></h1>
            <p class="info">Our website is under construction.<br/>
                <span>Mohon maaf, untuk sementara waktu website tidak bisa di akses dikarenakan ada proses maintenance. Terimakasih</span>
            </p>
            <p class="links">
                <a href="<?php echo get_option('twitter_link'); ?>" class="tw">Twitter</a>
                <a href="<?php echo get_option('facebook_link'); ?>" class="fb">Facebook</a>
            </p>
        </div>

        <!-- Theme Core JavaScript ================================================== -->
        <script type="text/javascript" src="<?php echo MAINTENANCE_JS_PATH; ?>jquery-1.11.1.min.js"></script>
    
        <!-- Theme JavaScript ======================================================= -->
        <script type="text/javascript" src="<?php echo MAINTENANCE_JS_PATH; ?>cufon-yui.js"></script>
        <script type="text/javascript" src="<?php echo MAINTENANCE_JS_PATH; ?>Copse_400.font.js"></script>
        <script type="text/javascript" src="<?php echo MAINTENANCE_JS_PATH; ?>Gabriola_400.font.js"></script>
        <script type="text/javascript">
            Cufon.replace('h1', {fontFamily: 'Copse', hover:true})
            Cufon.replace('p.info', {fontFamily: 'Gabriola', hover:true})
        </script>
    </body>
</html>