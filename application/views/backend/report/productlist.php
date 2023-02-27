<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_report') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_report_product'); ?></li>
                        </ol>
                    </nav>
                </div>
                <?php if ( $is_admin && ( $member_other || !empty($member_other) ) )  { ?>
                    <div class="col-lg-6 col-5 text-right">
                        <a href="<?php echo base_url('report/product') ?>" class="btn btn-sm btn-neutral"><i class="fa fa-arrow-left mr-1"></i> <?php echo 'Kembali'; ?></a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="float-right"><strong>Saldo Produk: <span class="text-danger"><?php echo $product_active; ?></span> Liter</strong></div>
                            <h3 class="mb-0">
                            <?php if( $is_admin && ( $member_other || !empty($member_other) ) ){ ?>
                                <?php echo 'History '. lang('menu_report_product').' Agen : <span class="text-info">'.$member_other->username.'</span>'; ?>
                            <?php }else{ ?>
                                <?php echo 'Laporan '. lang('menu_report_product').($is_admin ? ' Agen' : ' Saya'); ?>
                            <?php } ?>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <?php 
                        if ( $is_admin ) {
                            if( $member_other || !empty($member_other) ){
                                $this->load->view(VIEW_BACK . 'report/product/listadmindetail');
                            }else{
                                $this->load->view(VIEW_BACK . 'report/product/listadmin');
                            }
                        } else {
                            $this->load->view(VIEW_BACK . 'report/product/listagent');
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
