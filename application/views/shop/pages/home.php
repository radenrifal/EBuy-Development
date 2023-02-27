<?php include APPPATH . 'views/shop/components/header.php'; ?>
<?php include APPPATH . 'views/shop/components/mobile/nav_search.php'; ?>

<div id="homepage-7">
    <div class="ps-home-banner mb-0">
        <div class="ps-section__center">
            <div class="ps-carousel--nav-inside owl-slider" data-owl-hover-pause="false" data-owl-auto="true" data-owl-loop="true" data-owl-speed="2000" data-owl-gap="0" data-owl-nav="true" data-owl-dots="true" data-owl-item="1" data-owl-item-xs="1" data-owl-item-sm="1" data-owl-item-md="1" data-owl-item-lg="1" data-owl-duration="500" data-owl-mousedrag="on" data-owl-animate-in="fadeIn" data-owl-animate-out="fadeOut">
                <div class="item">
                    <div class="textoverlay">
                        <div class="container d-block">
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 px-5 d-flex justify-content-center align-items-center mb-5 wow slideInLeft">
                                    <img src="<?= FE_IMG_PATH ?>product-1.png" alt="" width="400px">
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mb-5 wow slideInRight text-right">
                                    <h2>Mulailah bisnis online Anda <br>sekarang dan raih mimpimu.</h2>
                                    <style>
                                        ul.checkmark li:before {
                                            content: "\2714\0020";
                                            list-style: none;
                                            margin-right: 5px;
                                        }

                                        ul.checkmark {
                                            list-style: none;
                                        }

                                        ul.checkmark li {
                                            font-size: 15px;
                                            line-height: 30px;
                                            margin-left: -40px;
                                            text-transform: uppercase;
                                        }
                                    </style>
                                    <ul class="checkmark my-4">
                                        <li>Disiapin Sistemnya</li>
                                        <li>Disediain Produknya</li>
                                        <li>Dicariin Pembelinya* </li>
                                    </ul>
                                    <small style="font-size:12px">* Fitur Find Agen</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="#"><img class="wow slideInRight" src="<?= FE_IMG_PATH ?>slider/slide.jpg" alt=""></a>
                </div>
            </div>
        </div>
    </div>

    <div class="ps-site-features mobile" style="background: #fcfeff;padding: 70px 0;">
        <div class="container">
            <div class="text-center mb-4 wow rotateIn">
                <h2>3 Langkah Mudah Sukses di <?php echo get_option('company_name'); ?></h2>
            </div>
            <div class="ps-block--site-features ps-block--site-features-2 py-3 mb-4">
                <div class="ps-block__item wow slideInRight">
                    <div class="ps-block__left"><i class="icon-user"></i></div>
                    <div class="ps-block__right">
                        <h4>Jadi Agen</h4>
                        <p>Daftar jadi Agen hanya 3 menit</p>
                    </div>
                </div>
                <div class="ps-block__item wow slideInUp">
                    <div class="ps-block__left"><i class="icon-cart"></i></div>
                    <div class="ps-block__right">
                        <h4>Share & Jual</h4>
                        <p>Share dan Jual produk <?= COMPANY_NAME ?> di sosmed</p>
                    </div>
                </div>
                <div class="ps-block__item wow slideInLeft">
                    <div class="ps-block__left"><i class="icon-wallet"></i></div>
                    <div class="ps-block__right">
                        <h4>Dapat Komisi & Rewards</h4>
                        <p>Komisi dan Rewards menarik menjadi milik Anda</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ps-promotions bg-parallax" style="background:url(<?= FE_IMG_PATH ?>bg-1.jpg); ">
        <div class="container">
            <div class="ps-section__content">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 px-5 d-flex justify-content-center align-items-center mb-5 wow slideInLeft">
                        <img src="<?= FE_IMG_PATH ?>people.png" alt="" width="350px">
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mb-5 wow slideInRight">
                        <div class="text-black" style="white-space:pre-line">
                            <h2 class="mb-0">Siapapun Bisa Menjadi Agen di <br><?php echo get_option('company_name'); ?></h2>
                            <p style="font-size:16px;">
                                Waktu yang fleksibel dan hanya menggunakan handphone, siapa saja bisa menjadi Agen di <?php echo get_option('company_name'); ?>. Anda bisa meraih impian dengan bekerja dari rumah dan memiliki banyak waktu untuk keluarga tercinta.

                                Dapatkan jutaan sampai puluhan juta setiap bulannya dari komisi penjualan. Bawa pulang reward Motor, Emas, Mobil Keluarga di program Lifetime Reward.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ps-promotions">
        <div class="container">
            <div class="text-center wow bounceInUp">
                <h2>Keuntungan Agen <?php echo get_option('company_name'); ?></h2>
            </div>
            <br><br>
            <div class="row">

                <?php
                $data = array(
                    [
                        'image' => 'https://www.flaticon.com/svg/static/icons/svg/1474/1474613.svg',
                        'title' => 'Produk Berkualitas dan Eksklusif ',
                    ],
                    [
                        'image' => 'https://www.flaticon.com/svg/static/icons/svg/2830/2830596.svg',
                        'title' => 'Perusahaan Tidak Menjual Langsung ke Customer',
                    ],
                    [
                        'image' => 'https://www.flaticon.com/svg/static/icons/svg/2405/2405257.svg',
                        'title' => 'Komisi Besar (10% - 50% dari harga produk)',
                    ],
                    [
                        'image' => 'https://www.flaticon.com/svg/static/icons/svg/3079/3079314.svg',
                        'title' => 'Rewards Menarik',
                    ],
                    [
                        'image' => 'https://www.flaticon.com/svg/static/icons/svg/925/925748.svg',
                        'title' => 'Harga Join Sangat Terjangkau',
                    ],
                    [
                        'image' => 'https://www.flaticon.com/svg/static/icons/svg/1189/1189160.svg',
                        'title' => 'Mempunyai Fitur Find Agen',
                    ],
                    [
                        'image' => 'https://www.flaticon.com/svg/static/icons/svg/2910/2910756.svg',
                        'title' => 'Disediakan Marketing Tools untuk Berjualan',
                    ],
                    [
                        'image' => 'https://www.flaticon.com/svg/static/icons/svg/3337/3337515.svg',
                        'title' => 'Bisa Pantau Komisi & Reward secara Real Time di Aplikasi',
                    ],
                );
                foreach ($data as $key => $row) {
                ?>
                    <div class="col-12 col-md-6">
                        <div class="row d-flex justify-content-center align-items-center mb-5">
                            <div class="col-md-2 col-12 mb-3 text-center">
                                <img src="<?= $row['image'] ?>" alt="" class="img-responsive" style="height: 60px;width: 60px;">
                            </div>
                            <div class="col-md-10 col-12 mobile-center">
                                <h4><a href="javascript:;"><?= $row['title'] ?></a></h4>
                            </div>
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>

</div>

<?php //include APPPATH . 'views/shop/components/mobile/floating_cart.php'; 
?>