<?php 
    $the_member         = ( !empty( $member_other ) && $member_other->type == MEMBER ) ? $member_other : $member;
    $as_admin           = as_administrator($the_member);
    $as_member          = as_member($the_member);
    $as_stockist        = $the_member->as_stockist;
    $as_staff           = ( $staff = ddm_get_current_staff() ) ? true : false;
    $as_mine            = ( $the_member->id == $member->id ) ? true : false;

    $p_member_name      = $the_member->name;
    $p_member_user      = $the_member->username;
    $p_status           = lang('agent');
    $p_class            = 'default';
    if ( $as_admin ) {
        $p_status       = 'Admin';
        $p_class        = 'danger';
    }
    if ( $the_member->type == 1 && $as_staff ) {
        $p_member_name  = $staff->name;
        $p_member_user  = $staff->username;
        if ( $staff->id > 1 ) {
            $p_status   = 'Staff';
            $p_class    = 'success';
        }
    }

    $avatar = ( empty($the_member->photo) ? 'avatar.png' : $the_member->photo );

    $data_profile = array(
        'member_other'  => $member_other,
        'the_member'    => $the_member,
        'staff'         => $as_staff ? ddm_get_current_staff() : false,
        'as_admin'      => $as_admin,
        'as_member'     => $as_member,
        'as_staff'      => $as_staff,
        'as_mine'       => $as_mine,
    );
?>

<div class="header d-flex align-items-center" style="min-height: 350px; background-image: url(<?php echo BE_IMG_PATH.'bg-profile.jpg'; ?>); background-size: cover; background-position: center top;">
    <!-- Mask -->
    <span class="mask bg-gradient-default opacity-8"></span>
    <!-- Header container -->
    <div class="container-fluid mt--9">
        <div class="row">
            <div class="col-lg-7 col-md-10">
                <h1 class="display-4 text-white mb-0"><?php echo ucwords(strtolower($p_member_name)); ?></h1>
                <p class="text-white mt-0 mb-2">This is your profile page.</p>
            </div>
        </div>
    </div>
</div>

<!-- Page content -->
<div class="container-fluid mt--9">
    <div class="row">
        <div class="col-xl-4 order-xl-2">
            <div class="card card-profile">
                <img src="<?php echo BE_IMG_PATH.'bg-profile.jpg'; ?>" alt="Image placeholder" class="card-img-top">
                <div class="row justify-content-center">
                    <div class="col-lg-3 order-lg-2">
                        <div class="card-profile-image">
                            <a href="#">
                                <img src="<?php echo BE_IMG_PATH . 'icons/' . $avatar; ?>" class="rounded-circle">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                    <div class="d-flex justify-content-between">
                        <a href="#" class="btn btn-sm btn-info  mr-4 ">Photo</a>
                        <a href="#" class="btn btn-sm btn-<?php echo $p_class; ?> float-right"><?php echo $p_status; ?></a>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="text-center">
                        <h5 class="h3 mb-0"><?php echo ucwords(strtolower($p_member_name)); ?></h5>
                        <h5 class="h4 text-primary"><?php echo $p_member_user; ?></h5>
                    </div>
                </div>
            </div>
            <?php if ( $as_member ) { ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="h3 mb-0">Ganti Password</h5>
                    </div>
                    <div class="card-body">
                        <?php 
                            if ( $is_admin ) {
                                $this->load->view(VIEW_BACK . 'member/profiledetail/password_member', $data_profile); 
                            } else {
                                $this->load->view(VIEW_BACK . 'member/profiledetail/password_mine', $data_profile); 
                            }
                        ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="col-xl-8 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h3 class="mb-0">Edit profile </h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php $this->load->view(VIEW_BACK . 'member/profiledetail/personal_info', $data_profile); ?>
                </div>
            </div>
        </div>
    </div>
</div>