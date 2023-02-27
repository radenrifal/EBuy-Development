<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <?= (isset($meta) ? $meta : '') ?>

    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="<?= (isset($metaDesc) ? $metaDesc : '') ?>">

    <link rel="manifest" href="/manifest.json?ver=<?= VER_MANIFEST ?>" />

    <title><?= $title ?></title>

    <link rel="shortcut icon" type="image/x-icon" href="<?= BE_IMG_PATH . 'dii-logo.png' ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700&amp;amp;subset=latin-ext">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= FE_FONTS_PATH ?>Linearicons/Font/files/icon.css">
    <link href="<?= GLOBAL_CSS_PATH . 'font-awesome4.min.css' ?>" rel="stylesheet">

    <?php
    ## CSS
    $mainCSS = array(
        array("https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"),
        array(SH_PLUGIN_PATH . "owl-carousel/assets/owl.carousel.css"),
        array(SH_PLUGIN_PATH . "slick/slick/slick.css"),
        array(SH_PLUGIN_PATH . "lightGallery-master/dist/css/lightgallery.min.css"),
        array(SH_PLUGIN_PATH . "jquery-bar-rating/dist/themes/fontawesome-stars.css"),
        array(SH_PLUGIN_PATH . "jquery-ui/jquery-ui.min.css"),
        array(SH_PLUGIN_PATH . "select2/dist/css/select2.min.css"),
        // array("https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css"),
        array(BE_PLUGIN_PATH . "animate.css/animate.min.css"),
        array(BE_PLUGIN_PATH . "sweetalert2/dist/sweetalert2.min.css"),
        array(GLOBAL_PLUGINS_PATH . "snackbar/snackbar.min.css"),
        array(SH_CSS_PATH . "table-responsive.css"),
        array(SH_CSS_PATH . "style.css?ver=" . CSS_VER_FRONT),
        array(SH_CSS_PATH . "electronic.css"),
        array(SH_CSS_PATH . "track-order.css?ver=" . CSS_VER_FRONT),
    );
    
    if ( $carabiner = config_item('cfg_carabiner') ) {
        $this->carabiner->group('main_css', array('css' => $mainCSS));
        echo $this->carabiner->display('main_css');
    }else{
        foreach($mainCSS as $css){
            echo '<link rel="stylesheet" href="'.$css[0].'"></link>';
        }
    }
    ?>

    <link rel="stylesheet" href="<?= SH_CSS_PATH ?>custom.css?ver=<?= CSS_VER_FRONT ?>">

</head>

<body style="background: white;">

    <div class="row no-gutters vh-100 loader-screen" style="display: none;">
        <div class="col align-self-center text-white text-center">
            <img src="<?= LOGO_IMG ?>" alt="logo">
            <br>
            <div class="loader-horizontal">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>

    <?php $this->load->view(VIEW_SHOP . $content); ?>

    <div class="d-none d-sm-block">

        <footer>
            <div class="ps-footer" style="background: #313332;">
                <div class="container">
                    <div class="ps-footer__widgets">
                        <aside class="widget widget_footer">
                            <h3 class="widget-title"><i class="fa fa-desktop mr-1"></i> Media Sosial</h3>
                            <ul class="ps-list--social">
                                <li><a class="instagram" href="#"><i class="fa fa-instagram"></i></a></li>
                                <li><a class="facebook" href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a class="twitter" href="#"><i class="fa fa-youtube"></i></a></li>
                            </ul>
                        </aside>
                        <aside class="widget widget_footer widget_contact-us">
                            <h3 class="widget-title"><i class="fa fa-phone-square mr-1"></i> Ada Pertanyaan?</h3>
                            <div class="widget_content text-white">
                                <h3 style="color:white">
                                    <?php echo get_option('company_phone'); ?>
                                </h3>
                            </div>
                        </aside>
                        <aside class="widget widget_footer text-white">
                            <h3 class="widget-title"><i class="fa fa-clock-o mr-1"></i> Jam Kerja</h3>
                            <div class="widget_content text-white">
                                <h4 style="color:white">Senin – Sabtu ( 09.00 – 17.00)</h4>
                            </div>
                        </aside>
                    </div>
                </div>
                <div class="py-3 text-center text-white" style="background: #252726;">
                    Copyrights © 2021 <?php echo get_option('company_name'); ?>
                </div>
            </div>
        </footer>
    </div>

    <div id="back2top">
        <i class="pe-7s-angle-up"></i>
    </div>
    <div class="ps-site-overlay"></div>

    <div id="loader-wrapper">
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>

    <script>
        history.scrollRestoration = "manual"
        const base_url = "<?php echo base_url(); ?>";
    </script>

    <?php
    
    if ( $carabiner = config_item('cfg_carabiner') ) {
        ## Main JS
        $mainJS = array(
            array(SH_PLUGIN_PATH . "jquery-1.12.4.min.js"),
            array(SH_PLUGIN_PATH . "popper.min.js"),
            array(SH_PLUGIN_PATH . "owl-carousel/owl.carousel.min.js"),
            array(SH_PLUGIN_PATH . "bootstrap4/js/bootstrap.min.js"),
            array(SH_PLUGIN_PATH . "imagesloaded.pkgd.min.js"),
            array(SH_PLUGIN_PATH . "masonry.pkgd.min.js"),
            array(SH_PLUGIN_PATH . "isotope.pkgd.min.js"),
            array(SH_PLUGIN_PATH . "jquery.matchHeight-min.js"),
            array(SH_PLUGIN_PATH . "slick/slick/slick.min.js"),
            array(SH_PLUGIN_PATH . "jquery-bar-rating/dist/jquery.barrating.min.js"),
            array(SH_PLUGIN_PATH . "slick-animation.min.js"),
            array(SH_PLUGIN_PATH . "lightGallery-master/dist/js/lightgallery-all.min.js"),
            array(SH_PLUGIN_PATH . "jquery-ui/jquery-ui.min.js"),
            array(SH_PLUGIN_PATH . "sticky-sidebar/dist/sticky-sidebar.min.js"),
            array(SH_PLUGIN_PATH . "jquery.slimscroll.min.js"),
            array(SH_PLUGIN_PATH . "select2/dist/js/select2.full.min.js"),
            array(GLOBAL_PLUGINS_PATH . "snackbar/snackbar.min.js"),
            array(BE_PLUGIN_PATH . "sweetalert2/dist/sweetalert2.min.js"),
            array(BE_PLUGIN_PATH . "moment/min/moment.min.js"),
            array(BE_PLUGIN_PATH . "jquery-validation/dist/jquery.validate.min.js"),
            array(BE_PLUGIN_PATH . "jquery-idletimeout/store.min.js"),
            array(BE_PLUGIN_PATH . "jquery-idletimeout/jquery-idleTimeout.min.js"),
            array("https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"),
            array("https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"),
            array("https://cdn.jsdelivr.net/npm/jquery.session@1.0.0/jquery.session.min.js"),
        );
        
        ## App JS
        $carabinerConfig = array('minify_js' => FALSE);
        $this->carabiner->config($carabinerConfig);
        $this->carabiner->group('main_js', array('js' => $mainJS));
        echo $this->carabiner->display('main_js');
        
        $appJS = array(
            array(SH_JS_PATH . 'main.js?ver=' . JS_VER_FRONT),
            array(BE_JS_PATH . 'components/validator.js?ver=' . JS_VER_FRONT),
            array(BE_JS_PATH . 'components/global.js?ver=' . JS_VER_FRONT),
        );
        $this->carabiner->group('app_js', array('js' => $appJS));
        echo $this->carabiner->display('app_js');
        $this->carabiner->display('custom_js');
    }else{
        $allJS = array(
            SH_PLUGIN_PATH . "jquery-1.12.4.min.js",
            SH_PLUGIN_PATH . "popper.min.js",
            SH_PLUGIN_PATH . "owl-carousel/owl.carousel.min.js",
            SH_PLUGIN_PATH . "bootstrap4/js/bootstrap.min.js",
            SH_PLUGIN_PATH . "imagesloaded.pkgd.min.js",
            SH_PLUGIN_PATH . "masonry.pkgd.min.js",
            SH_PLUGIN_PATH . "isotope.pkgd.min.js",
            SH_PLUGIN_PATH . "jquery.matchHeight-min.js",
            SH_PLUGIN_PATH . "slick/slick/slick.min.js",
            SH_PLUGIN_PATH . "jquery-bar-rating/dist/jquery.barrating.min.js",
            SH_PLUGIN_PATH . "slick-animation.min.js",
            SH_PLUGIN_PATH . "lightGallery-master/dist/js/lightgallery-all.min.js",
            SH_PLUGIN_PATH . "jquery-ui/jquery-ui.min.js",
            SH_PLUGIN_PATH . "sticky-sidebar/dist/sticky-sidebar.min.js",
            SH_PLUGIN_PATH . "jquery.slimscroll.min.js",
            SH_PLUGIN_PATH . "select2/dist/js/select2.full.min.js",
            GLOBAL_PLUGINS_PATH . "snackbar/snackbar.min.js",
            BE_PLUGIN_PATH . "sweetalert2/dist/sweetalert2.min.js",
            BE_PLUGIN_PATH . "moment/min/moment.min.js",
            BE_PLUGIN_PATH . "jquery-validation/dist/jquery.validate.min.js",
            BE_PLUGIN_PATH . "jquery-idletimeout/store.min.js",
            BE_PLUGIN_PATH . "jquery-idletimeout/jquery-idleTimeout.min.js",
            "https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js",
            "https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js",
            "https://cdn.jsdelivr.net/npm/jquery.session@1.0.0/jquery.session.min.js",
            BE_JS_PATH . 'components/validator.js?ver=' . JS_VER_FRONT,
            BE_JS_PATH . 'components/global.js?ver=' . JS_VER_FRONT,
            SH_JS_PATH . 'main.js?ver=' . JS_VER_FRONT,
        );
        if( !empty($scripts) ){
            $allJS = array_merge($allJS, $scripts);
        }
        foreach($allJS as $js){
            echo '<script src="'. $js .'"></script>';
        }
    }
    
    ?>
    <script>

        var IdleTimeout = function() {
            return {
                init: function( url, idleTimeout ) {
                    $(document).idleTimeout({
                        redirectUrl: url,               // redirect to this url on logout. Set to "redirectUrl: false" to disable redirect
                        // idle settings
                        idleTimeLimit: idleTimeout,     // 'No activity' time limit in seconds. 1200 = 20 Minutes
                        idleCheckHeartbeat: 2,          // Frequency to check for idle timeouts in seconds
                        enableDialog: false,            // set to false for logout without warning dialog
                    });
                }
            };
        }();

        $(function() {
            new WOW().init();
            <?php if ( isset($member) && $member ) { ?>
                IdleTimeout.init( "<?php echo base_url('logout'); ?>", <?php echo config_item('idle_timeout'); ?> );
            <?php } ?>
        });

        // copy link
        $('.copyLink').click(function() {
            var copyText = $(this).data('link');
            var el = $('<input style="position: absolute; bottom: -120%" type="text" value="' + copyText + '"/>').appendTo('body');
            el[0].select();
            document.execCommand("copy");
            el.remove();
            Snackbar.show({
                text: 'Link berhasil di copy, silahkan share link anda',
                pos: 'bottom-center'
            });
        });
    </script>

    <?php if (isset($uri_promo)) { ?>
        <script>
            $(document).ready(function() {
                $('.ps-popup.promo').addClass('active');
            });
        </script>
    <?php } ?>

    <script src="<?= base_url('sw-register.js') ?>"></script>

</body>

</html>