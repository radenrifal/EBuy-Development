<!DOCTYPE html>
    <!-- <?php echo COMPANY_NAME; ?> Login Template v1.0 -->
    
    <!--[if lt IE 7]><html dir="ltr" lang="en-US" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
    <!--[if IE 7]><html dir="ltr" lang="en-US" class="lt-ie9 lt-ie8"><![endif]-->
    <!--[if IE 8]><html dir="ltr" lang="en-US" class="lt-ie9"><![endif]-->
    <!--[if IE 9]><html dir="ltr" lang="en-US"  class="lt-ie10"> <![endif]-->
    <!--[if !IE]><!--><html lang="en" class="no-js"><!--<![endif]-->
    
    <!-- BEGIN HEAD -->
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

        <title><?php echo TITLE .' Not Found'; ?></title>
        
        <!-- Shortcut Icon ================================================ -->
        <link rel="shortcut icon" href="<?php echo BE_IMG_PATH .'logo.png'; ?>" type="image/x-icon">

        <!--===============================================================================================-->
        <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="<?= FE_FONTS_PATH ?>Linearicons/Font/files/icon.css">
        <link href="<?= GLOBAL_CSS_PATH . 'font-awesome4.min.css' ?>" rel="stylesheet">
        <!--===============================================================================================-->
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

        <!-- Plugins Styles ========================================================== -->
        <link rel="stylesheet" href="<?= FE_PLUGIN_PATH ?>slick/slick/slick.css">
        <link rel="stylesheet" href="<?= FE_PLUGIN_PATH ?>lightGallery-master/dist/css/lightgallery.min.css">
        <link rel="stylesheet" href="<?= FE_PLUGIN_PATH ?>jquery-ui/jquery-ui.min.css">
        <link rel="stylesheet" href="<?= FE_PLUGIN_PATH ?>select2/dist/css/select2.min.css">
        <link rel="stylesheet" href="<?= FE_CSS_PATH ?>style.css">
        <link rel="stylesheet" href="<?= FE_CSS_PATH ?>electronic.css">
        <link rel="stylesheet" href="<?= FE_CSS_PATH ?>custom.css">
        <link rel="stylesheet" href="<?php echo GLOBAL_CSS_PATH; ?>notfound.css">

    </head>
    <!-- END HEAD -->
    
    <body oncontextmenu='return false'>

        <?php include APPPATH . 'views/frontend/components/header.php'; ?>
        <?php include APPPATH . 'views/frontend/components/mobile/nav_search.php'; ?>

        <div class="error-content pt-5">
            <div class="container pt-5">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="error-text">
                            <h1 class="error py-5">404 Error</h1>
                            <div class="im-sheep">
                                <div class="top">
                                    <div class="body"></div>
                                    <div class="head">
                                        <div class="im-eye one"></div>
                                        <div class="im-eye two"></div>
                                        <div class="im-ear one"></div>
                                        <div class="im-ear two"></div>
                                    </div>
                                </div>
                                <div class="im-legs">
                                    <div class="im-leg"></div>
                                    <div class="im-leg"></div>
                                    <div class="im-leg"></div>
                                    <div class="im-leg"></div>
                                </div>
                            </div>
                            <?php if ( isset($message) && !empty($message) ) { ?>
                                <h4>Oops! <?php echo $message; ?></h4>
                            <?php } else { ?>
                                <h4>Oops! This page Could Not Be Found!</h4>
                            <?php } ?>
                            <p>Sorry bit the page you are looking for does not exist, have been removed or name changed.</p>
                            <a href="<?=base_url()?>" class="btn btn-lg btn-primary btn-round">Go to homepage</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--===============================================================================================-->
        <script src="<?= FE_PLUGIN_PATH ?>jquery-1.12.4.min.js"></script>
        <script src="<?= FE_PLUGIN_PATH ?>bootstrap4/js/bootstrap.min.js"></script>
        <script src="<?= FE_PLUGIN_PATH ?>slick/slick/slick.min.js"></script>
        <script src="<?= FE_PLUGIN_PATH ?>lightGallery-master/dist/js/lightgallery-all.min.js"></script>
        <script src="<?= FE_PLUGIN_PATH ?>select2/dist/js/select2.full.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="<?= FE_JS_PATH ?>main.js"></script>

        <script src="<?= base_url('sw-register.js') ?>"></script>
    </body>
</html>