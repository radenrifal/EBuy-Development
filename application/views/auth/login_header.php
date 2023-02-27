<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta keyword="<?php echo COMPANY_NAME; ?>">
    <meta name="author" content="<?php echo COMPANY_NAME; ?>" />
    <meta name="description" content="<?php echo COMPANY_NAME; ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Mobile -->
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <title><?php echo $title; ?></title>
    
    <!-- Shortcut Icon ================================================ -->
    <link rel="shortcut icon" href="<?php echo BE_IMG_PATH .'dii-logo.png'; ?>" type="image/x-icon">

    <!--===============================================================================================-->
    <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo ASSET_PATH; ?>auth/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo ASSET_PATH; ?>auth/css/bootstrap.min.css">

    <!-- Plugins Styles ========================================================== -->
    <link rel="stylesheet" type="text/css" href="<?php echo ASSET_PATH; ?>auth/plugins/waitMe/waitMe.css">
    <?php
        if ( $carabiner = config_item('cfg_carabiner') ) {
            $this->carabiner->group('main_css', array('css' => $headstyles));
            echo $this->carabiner->display('main_css');
        } else {
            echo $headstyles; 
        }
    ?>
</head>