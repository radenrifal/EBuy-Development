<head>
    <!-- Meta Tags -->
	<meta charset="UTF-8" />
	<meta name="description" content="<?php echo COMPANY_NAME; ?>" />
	<meta name="author" content="<?php echo COMPANY_NAME; ?>" />
    <meta name="google" content="notranslate">
	
	<!-- Mobile -->
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	
	<!-- Page Title =================================================== -->
	<title><?php echo $title; ?></title>

    <!-- Shortcut Icon ================================================ -->
    <link rel="shortcut icon" href="<?php echo BE_IMG_PATH .'dii-logo.png'; ?>" type="image/x-icon">

    <!-- Fonts ================================================ -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">

    <!-- Icons ======================================== -->
    <link rel="stylesheet" href="<?php echo BE_PLUGIN_PATH; ?>nucleo/css/nucleo.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BE_PLUGIN_PATH; ?>@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
    
    <!-- Plugins Styles =============================================== -->
    <link rel="stylesheet" href="<?php echo ASSET_PATH; ?>auth/plugins/waitMe/waitMe.css" type="text/css">

    <?php
        if ( $carabiner = config_item('cfg_carabiner') ) {
            // Theme Styles ========================================
            $headstyles[] = array(BE_CSS_PATH .'style.css?ver='. CSS_VER_BACK);

            $this->carabiner->group('main_css', array('css' => $headstyles));
            echo $this->carabiner->display('main_css');
        } else {
            echo $headstyles; 
            // Theme Styles ========================================
            echo '<link rel="stylesheet" href="'. BE_CSS_PATH .'style.css?ver='. CSS_VER_BACK .'" type="text/css">';
        }
    ?>    
</head>