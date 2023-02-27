<?php include APPPATH . 'views/shop/components/header.php'; ?>
<?php include APPPATH . 'views/shop/components/mobile/nav_search.php'; ?>

<div class="ps-breadcrumb">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?= base_url() ?>">Home</a></li>
            <li>Contact Us</li>
        </ul>
    </div>
</div>

<div class="ps-contact-form">
    <div class="container">
        <form class="ps-form--contact-us" action="#" method="get">
            <h3>Any Help? <br> Contact Us Using This Form</h3>
            <div class="row">
                <div class="col-12 col-md-6 offset-md-3">
                    <div class="row">

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 ">
                            <div class="form-group">
                                <input class="form-control" type="text" placeholder="Name *">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 ">
                            <div class="form-group">
                                <input class="form-control" type="text" placeholder="Email *">
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <div class="form-group">
                                <input class="form-control" type="text" placeholder="Subject *">
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <div class="form-group">
                                <textarea class="form-control" rows="5" placeholder="Message"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group submit">
                <button class="ps-btn">Send message</button>
            </div>
        </form>
    </div>
</div>
</div>