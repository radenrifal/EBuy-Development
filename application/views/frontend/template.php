<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo BE_IMG_PATH; ?>logo.png">

    <title><?= $title ?></title>

    <link rel="stylesheet" href="<?= FE_CSS_PATH ?>plugins.css">
    <link rel="stylesheet" href="<?= FE_CSS_PATH ?>style.css">
    <link rel="stylesheet" href="<?= FE_CSS_PATH ?>colors/purple.css">

</head>

<body>
    <div class="content-wrapper">
        <header class="wrapper bg-soft-primary">

            <nav class="navbar navbar-expand-lg classic transparent <?= ($this->uri->uri_string() == '' ? 'position-absolute navbar-dark' : 'center-nav navbar-light') ?>">
                <div class="container flex-lg-row flex-nowrap align-items-center">
                    <div class="navbar-brand w-100">
                        <a href="<?= base_url() ?>">
                            <img class="logo-dark" src="<?php echo BE_IMG_PATH; ?>logo.png" width="70px" alt="" />
                            <img class="logo-light" src="<?php echo BE_IMG_PATH; ?>logo.png" width="80px" alt="" />
                        </a>
                    </div>
                    <div class="navbar-collapse offcanvas-nav">
                        <div class="offcanvas-header d-lg-none d-xl-none">
                            <a href="<?= base_url() ?>"><img src="<?php echo BE_IMG_PATH; ?>logo.png" width="80px" alt="" /></a>
                            <button type="button" class="btn-close btn-close-white offcanvas-close offcanvas-nav-close" aria-label="Close"></button>
                        </div>
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link" href="<?= base_url() ?>">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= base_url('aboutus') ?>">About Us</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= base_url('product') ?>">Product</a></li>
                        </ul>
                        <!-- /.navbar-nav -->
                    </div>
                    <!-- /.navbar-collapse -->
                    <div class="navbar-other ms-lg-4">
                        <ul class="navbar-nav flex-row align-items-center ms-auto" data-sm-skip="true">
                            <!--<li class="nav-item d-none d-md-block"> -->
                            <li class="nav-item">
                                <a href="<?= base_url('login') ?>" class="btn btn-sm btn-primary rounded-pill">Login</a>
                            </li>
                            <li class="nav-item d-lg-none">
                                <div class="navbar-hamburger"><button class="hamburger animate plain" data-toggle="offcanvas-nav"><span></span></button></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <!-- /header -->

        <?php $this->load->view(VIEW_FRONT . $content); ?>

    </div>

    <!-- /.content-wrapper -->
    <footer class="bg-navy text-inverse">
        <div class="container py-13 py-md-15">
            <div class="d-lg-flex flex-row align-items-lg-center">
                <div class="col-md-6">
                    <h3 class="display-4 mb-6 mb-lg-0 pe-lg-20 text-white">Join our community</h3>
                </div>
                <div class="col-md-6">
                    <a href="<?= base_url('register/agent') ?>" class="float-md-end btn btn-primary rounded-pill mb-0 text-nowrap">Register Now</a>
                </div>
            </div>
            <!--/div -->
            <hr class="mt-11 mb-12" />
            <div class="row gy-6 gy-lg-0">
                <div class="col-md-4 col-lg-3">
                    <div class="widget">
                        <img class="mb-4" src="<?php echo BE_IMG_PATH; ?>logo.png" width="150px" alt="" />
                        <nav class="nav social social-white">
                            <a href="#"><i class="uil uil-twitter"></i></a>
                            <a href="#"><i class="uil uil-facebook-f"></i></a>
                            <a href="#"><i class="uil uil-dribbble"></i></a>
                            <a href="#"><i class="uil uil-instagram"></i></a>
                            <a href="#"><i class="uil uil-youtube"></i></a>
                        </nav>
                        <!-- /.social -->
                    </div>
                    <!-- /.widget -->
                </div>
                <!-- /column -->
                <div class="col-md-4 col-lg-3">
                    <div class="widget">
                        <h4 class="widget-title text-white mb-3">Get in Touch</h4>
                        <address class="pe-xl-15 pe-xxl-17"><?= COMPANY_ADDRESS ?></address>
                        <a href="mailto:#"><?= COMPANY_EMAIL ?></a><br /> <?= COMPANY_PHONE ?>
                    </div>
                    <!-- /.widget -->
                </div>
                <!-- /column -->
                <div class="col-md-4 col-lg-3">
                    <div class="widget">
                        <h4 class="widget-title text-white mb-3">Learn More</h4>
                        <ul class="list-unstyled  mb-0">
                            <li><a href="<?= base_url('aboutus') ?>">About Us</a></li>
                            <li><a href="<?= base_url('product') ?>">Product</a></li>
                            <li><a href="<?= base_url('login') ?>">Login</a></li>
                        </ul>
                    </div>
                    <!-- /.widget -->
                </div>
                <!-- /column -->
                <div class="col-md-12 col-lg-3">
                    <div class="widget">
                        <h4 class="widget-title text-white mb-3">Our Newsletter</h4>
                        <p class="mb-5">Subscribe to our newsletter to get our news & deals delivered to you.</p>
                        <div class="newsletter-wrapper">
                            <!-- Begin Mailchimp Signup Form -->
                            <div id="mc_embed_signup2">
                                <form action="#" method="post" id="mc-embedded-subscribe-form2" name="mc-embedded-subscribe-form" class="validate dark-fields" target="_blank" novalidate>
                                    <div id="mc_embed_signup_scroll2">
                                        <div class="mc-field-group input-group form-floating">
                                            <input type="email" value="" name="EMAIL" class="required email form-control" placeholder="Email Address" id="mce-EMAIL2">
                                            <label for="mce-EMAIL2">Email Address</label>
                                            <input type="submit" value="Join" name="subscribe" id="mc-embedded-subscribe2" class="btn btn-primary">
                                        </div>
                                        <div id="mce-responses2" class="clear">
                                            <div class="response" id="mce-error-response2" style="display:none"></div>
                                            <div class="response" id="mce-success-response2" style="display:none"></div>
                                        </div> <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                        <div class="clear"></div>
                                    </div>
                                </form>
                            </div>
                            <!--End mc_embed_signup-->
                        </div>
                        <!-- /.newsletter-wrapper -->
                    </div>
                    <!-- /.widget -->
                </div>
                <!-- /column -->
            </div>
            <!--/.row -->
        </div>
        <!-- /.container -->
    </footer>
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>

    <script src="<?= FE_JS_PATH ?>plugins.js"></script>
    <script src="<?= FE_JS_PATH ?>theme.js"></script>
</body>

</html>