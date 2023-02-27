<?php
$pageID = 'company_profile';
$imgpath = UPLOAD_IMG . $pageID;
?>
<?php include APPPATH . 'views/shop/components/header.php'; ?>
<?php include APPPATH . 'views/shop/components/mobile/nav_search.php'; ?>

<div class="ps-breadcrumb">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?= base_url() ?>">Home</a></li>
            <li><?= $page ?></li>
        </ul>
    </div>
</div>
<div class="ps-page--single">
    <img src="<?= ($data) ? get_image_single($data->id, $pageID, $imgpath) : NO_IMAGE_PATH ?>" style="width: 100%;">
    <div class="ps-about-intro">
        <div class="container">
            <div class="ps-section__header">
                <h3 class="text-capitalize font-weight-bold"><?= ($data) ? $data->title : '' ?></h3>
                <div class="text-justify">
                    <?= ($data) ? $data->description : '' ?>
                </div>
            </div>
        </div>
    </div>

</div>