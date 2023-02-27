<?php
    $time   = date('H');
    $hi     = '';
    $name	= $member->name;
    if ( $staff = ddm_get_current_staff() ) {
        $name = $staff->name;
    }

    if( $time >= '00' && $time <= '09'  )       { $hi = lang('morning'); }
    elseif( $time > '09' && $time <= '14'  )    { $hi = lang('daylight'); }
    elseif( $time > '14' && $time <= '18'  )    { $hi = lang('afternoon'); }
    elseif( $time > '18' && $time <= '24'  )    { $hi = lang('evening'); }

    if ( $is_admin ) {
        $dataOmzet      = $this->Model_Member->get_total_member_omzet();
        $dataBonus      = $this->Model_Bonus->get_total_deposite_bonus();
        $dataOrder      = $this->Model_Shop->get_total_shop_order();
    } else {
        $deposite       = $this->Model_Bonus->get_ewallet_deposite($member->id);
        $bonus          = $this->Model_Bonus->get_total_bonus_member($member->id);
        $condition      = ' AND ( sponsor = '. $member->id . ' OR id_member = '. $member->id .' ) '; 
        $dataOmzet      = $this->Model_Member->get_total_member_omzet_group($condition);
        $condition      = ' AND id_member = '. $member->id .' AND `status` = 1'; 
        $dataOrder      = $this->Model_Shop->get_total_shop_order($condition);
        $personalSales  = $this->Model_Omzet_History->get_personal_sales($member->id);

        $total_order            = isset($dataOrder->total_payment) ? $dataOrder->total_payment : 0;
        $total_qty_package      = isset($dataOrder->total_qty) ? $dataOrder->total_qty : 0;
        $total_qty_group        = isset($dataOmzet->total_qty) ? $dataOmzet->total_qty : 0;
        
        $total_personal_sales   = isset($personalSales->total_omzet) ? $personalSales->total_omzet : 0;
        $total_personal_qty     = isset($personalSales->total_qty) ? $personalSales->total_qty : 0;
        $total_product_active   = $this->Model_Omzet_History->get_product_active($member->id);
        $total_product_active   = isset($total_product_active) ? $total_product_active : 0;
    }
?>

<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-12">
                    <?php
                        $welcome_text   = lang('welcome_text');
                        $welcome_text   = str_replace("%hi%", $hi, $welcome_text);
                        $welcome_text   = str_replace("%name%", ucwords(strtolower($name)), $welcome_text);
                        echo $welcome_text;
                    ?>
                </div>
            </div>
            <?php if ( $is_admin ) { ?>
                <div class="row">
                    <div class="col-xl-6 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0"><?php echo lang('member_total'); ?></h5>
                                        <span class="h2 font-weight-bold mb-0"><?php echo ddm_accounting($this->Model_Member->count_data('active')); ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                            <i class="ni ni-circle-08"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <a href="<?php echo base_url('member/lists'); ?>" class="text-nowrap text-default"><?php echo lang('see_more'); ?></a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0"><?php echo lang('omzet_total'); ?></h5>
                                        <span class="h2 font-weight-bold mb-0"><?php echo ddm_accounting($dataOmzet->total_omzet); ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                            <i class="ni ni-chart-bar-32"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <a href="<?php echo base_url('report/omzet'); ?>" class="text-nowrap text-default"><?php echo lang('see_more'); ?></a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0"><?php echo lang('bonus_total'); ?></h5>
                                        <span class="h2 font-weight-bold mb-0"><?php echo ddm_accounting($dataBonus->total_bonus); ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                            <i class="ni ni-money-coins"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <a href="<?php echo base_url('commission/bonus'); ?>" class="text-nowrap text-default"><?php echo lang('see_more'); ?></a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0"><?php echo lang('order_total'); ?></h5>
                                        <span class="h2 font-weight-bold mb-0"><?php echo ddm_accounting($dataOrder->total_trx); ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                            <i class="ni ni-cart"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <a href="<?php echo base_url('report/sales'); ?>" class="text-nowrap text-default"><?php echo lang('see_more'); ?></a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="row">
                    <div class="col-xl-6 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0"><?php echo lang('current_deposite'); ?></h5>
                                        <span class="h2 font-weight-bold mb-0"><?php echo ddm_accounting($deposite);  ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
                                            <i class="ni ni-credit-card"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <a href="<?php echo base_url('commission/deposite'); ?>" class="text-nowrap text-default"><?php echo lang('see_more'); ?></a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0"><?php echo lang('bonus_total'); ?></h5>
                                        <span class="h2 font-weight-bold mb-0"><?php echo ddm_accounting($bonus); ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                            <i class="ni ni-money-coins"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <a href="<?php echo base_url('commission/bonus'); ?>" class="text-nowrap text-default"><?php echo lang('see_more'); ?></a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Total Orderan</h5>
                                        <span class="h2 font-weight-bold mb-0"><?php echo ddm_accounting($total_order); ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                            <i class="ni ni-cart"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    Total <?php echo ddm_accounting($total_qty_package); ?> <?php echo ' Liter '. lang('product'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Total Personal Omzet Sales</h5>
                                        <span class="h2 font-weight-bold mb-0"><?php echo ddm_accounting($total_personal_sales); ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                            <i class="ni ni-cart"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    Total <?php echo ddm_accounting($total_personal_qty); ?> <?php echo 'Liter '. lang('product'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 col-md-12">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0"><?php echo 'Total Produk Aktif' ?></h5>
                                        <span class="h2 font-weight-bold mb-0"><?php echo ddm_accounting($total_product_active); ?> Ltr</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                            <i class="ni ni-bag-17"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    Total Produk Aktif Anda
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php if ( $is_admin ) :
    $sales_trx      = array(0);
    $sales_data     = array(0);
    $sales_label    = array(date('M'));
    if ( $data_omzet ) {
        if ( count($data_omzet) > 1 ) {
            $sales_data     = $sales_trx = $sales_label = array();
        } else {
            $sales_label    = array(date('M', strtotime('-1 month')));
        }
        foreach ($data_omzet as $key => $row) {
            $sales_label[]  = date('M', strtotime($row->month_omzet));
            $sales_data[]   = $row->total_omzet / 1000;
            $sales_trx[]    = $row->total_trx;
        }
    }
    $sales_label = '"' . implode('","', $sales_label) .'"';
?>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-8">
            <div class="card bg-default">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-light text-uppercase ls-1 mb-1">Overview</h6>
                            <h5 class="h3 text-white mb-0">Sales Value</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div class="chart">
                        <span class="d-none sales-dark" 
                            data-toggle="chart" 
                            data-target="#chart-sales-dark" 
                            data-prefix="" 
                            data-suffix="k"
                            data-update='{
                                "data":{
                                    "labels": [<?php echo $sales_label; ?>],
                                    "datasets":[{
                                        "label": "Total Penjualan",
                                        "data":[<?php echo implode(',', $sales_data); ?>]
                                    }]
                                }
                            }' ></span>
                        <!-- Chart wrapper -->
                        <canvas id="chart-sales-dark" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted ls-1 mb-1">Overview</h6>
                            <h5 class="h3 mb-0">Total orders</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div class="chart">
                        <span class="d-none sales-canvas" 
                            data-toggle="chart" 
                            data-target="#chart-bars" 
                            data-prefix=" " 
                            data-suffix=""
                            data-update='{
                                "data":{
                                    "labels": [<?php echo $sales_label; ?>],
                                    "datasets":[{
                                        "label": "Total Penjualan",
                                        "data":[<?php echo implode(',', $sales_trx); ?>]
                                    }]
                                }
                            }' ></span>
                        <canvas id="chart-bars" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php else: ?>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="h3 mb-0 text-capitalize">Orderan Pending</h5>
                            <h6 class="text-warning text-uppercase ls-1 mb-1">PENDING</h6>
                        </div>
                    </div>
                </div>
                <div class="table-responsive" style="min-height: 240px">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                                <th scope="col" class="text-center">Invoice</th>
                                <th scope="col" class="text-center"><?php echo lang('type'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('total_payment'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('product'); ?></th>
                            </tr>
                        </thead>
                        <tbody class="list">
                        <?php 
                            $condition = 'WHERE %id_member% = ' . $member->id .' AND %status% = 0';
                            $data_list = $this->Model_Shop->get_all_shop_order_data(10, 0, $condition);
                            if ( $data_list ) {
                                foreach ($data_list as $key => $row) {
                                    $id_order       = ddm_encrypt($row->id);
                                    $btn_invoice    = '<a href="'.base_url('invoice/'.$id_order).'" 
                                                        class="btn btn-sm btn_block btn-outline-primary" 
                                                        target="_blank"><i class="fa fa-file"></i> '.$row->invoice.'</a>';

                                    $btn_product    = '<a href="javascript:;" 
                                                        data-url="'.base_url('productorder/getagentorderdetail/'.$id_order).'" 
                                                        data-invoice="'.$row->invoice.'"
                                                        class="btn btn-sm btn-block btn-outline-primary btn-shop-order-detail">
                                                        <i class="ni ni-bag-17 mr-1"></i> Detail Order</a>';

                                    $type         = '';
                                    if ( $row->type == 'perdana' )  { $type = '<span class="badge badge-sm badge-warning">PERDANA</span>'; }
                                    if ( $row->type == 'ro' )       { $type = '<span class="badge badge-sm badge-primary">REPEAT ORDER</span>'; }

                                    echo '
                                        <tr>
                                            <td class="text-center">'. date('j M y @H:i', strtotime($row->datecreated)) .'</td>
                                            <td class="text-center">'. $btn_invoice .'</td>
                                            <td class="text-center">'. $type .'</td>
                                            <td class="text-right heading text-warning font-weight-bold">'. ddm_accounting($row->total_payment) .'</td>
                                            <td class="text-center">'. $btn_product .'</td>
                                        </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="5" class="text-center">Belum ada pesanan</td></tr>';
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <p class="mb-0 text-sm">
                        <a href="<?php echo base_url('report/order'); ?>" class="text-nowrap text-default"><?php echo lang('see_more'); ?></a>
                    </p>
                </div>
            </div>
            <!-- Modal Detail PO -->
            <div class="modal fade" id="modal-shop-order-detail" tabindex="-1" role="dialog" aria-labelledby="modal-shop-order-detail" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header pt-3 pb-1">
                            <h5 class="modal-title text-primary"><i class="ni ni-book-bookmark mr-1"></i> <span class="title-invoice font-weight-bold"></span></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body px-4 py-3" style="background-color: #f8f9fe">
                            <div class="info-shop-order-detail"></div>
                        </div>
                        <div class="modal-footer py-2">
                            <button type="button" class="btn btn-sm btn-outline-warning" data-dismiss="modal"><?php echo lang('back'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="row">
                <div class="col-sm-6 col-md-12">
                    <div class="card card-stats">
                        <!-- Card body -->
                        <div class="card-body">
                            <?php 
                                $sales_pending = 0;
                                $condition = 'WHERE %id_agent% = ' . $member->id .' AND %status% = 0';
                                $data_list = $this->Model_Shop->get_all_shop_order_data(0, 0, $condition);
                                if ( $data_list ) {
                                    $sales_pending = ddm_get_last_found_rows();
                                }
                            ?>
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase mb-0"><?php echo lang('menu_report_sales'); ?></h5>
                                    <h6 class="text-warning text-uppercase ls-1 mb-1">PENDING</h6>
                                    <span class="h2 font-weight-bold mb-0"><?php echo ddm_accounting($sales_pending); ?></span>
                                    <span class="h5 text-muted mb-0">Pesanan</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-default text-white rounded-circle shadow">
                                        <i class="ni ni-cart"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-sm">
                                <a href="<?php echo base_url('report/ordercustomer'); ?>" class="text-nowrap text-default"><?php echo lang('see_more'); ?></a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-12">
                    <div class="card bg-gradient-primary">
                        <?php
                            $bill_no    = '-';
                            $bill_name  = (!empty($member->bill_name) ? $member->bill_name : '-');
                            if ( $member->bill ) {
                                $bill_format = '';
                                $arr_bill    = str_split($member->bill, 4);
                                foreach ($arr_bill as $k => $no) {
                                    $bill_format .= $no .' ';
                                }
                                $bill_no = $bill_format ? $bill_format : $bill_no;;
                            }

                            $bank_name  = '-';
                            if ( $member->bank && $getBank = ddm_banks($member->bank) ) {
                                $bank_name = $getBank->nama;
                            }
                        ?>
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row justify-content-between align-items-center">
                                <div class="col">
                                    <span class="text-white"><?php echo lang('reg_no_rekening'); ?></span>
                                </div>
                                <?php if ( $bill_no == '-' || $bill_name == '-' || $bank_name == '-' ) { ?>
                                    <div class="col-auto">
                                        <a href="<?php echo base_url('profile'); ?>" class="text-nowrap badge badge-lg badge-primary">
                                            <i class="fa fa-edit"></i> <?php echo lang('edit'); ?>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="my-3">
                                <span class="h6 surtitle text-light">
                                    Card number
                                </span>
                                <div class="card-serial-number h1 text-white">
                                    <div><?php echo $bill_no; ?></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <span class="h6 surtitle text-light"><?php echo lang('name'); ?></span>
                                    <span class="d-block h5 text-white"><?php echo $bill_name; ?></span>
                                </div>
                                <div class="col">
                                    <span class="h6 surtitle text-light">Bank</span>
                                    <span class="d-block h5 text-white"><?php echo $bank_name; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>
