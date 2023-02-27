<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <!-- Brand -->
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand" href="javascript:void(0)">
                <span class="text-primary font-weight-bold">
                    FRESHINDO
                </span>
                <!-- <img src="<?php echo BE_IMG_PATH; ?>logo.png" class="navbar-brand-img" alt="..."> -->
            </a>
            <div class=" ml-auto ">
                <!-- Sidenav toggler -->
                <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Nav items -->
                <?php 
                include APPPATH.'views/backend/menu.php';
                if($sidebar): 
                    echo '<ul class="navbar-nav"> ';
                    $access     = '';
                    $roles      = array();
                    if ( $staff = ddm_get_current_staff() ) { 
                        $access = $staff->access;
                        $roles  = $staff->role ? $staff->role : $roles;
                    }

                    foreach($sidebar as $nav): 
                        if (as_administrator($member)) {
                            if ( $access == 'partial' ) {
                                $nav_role = array_intersect($nav['roles'], $roles);
                                if ( $nav['roles'] && !$nav_role) { continue; }
                            } 
                            if ( $access == 'all' ) {
                                $nav_role = array_intersect($nav['roles'], array(STAFF_ACCESS9));
                                if ( $nav['roles'] && $nav_role) {
                                    if ( $roles ) {
                                        $nav_role_admin = array_intersect($nav['roles'], $roles);
                                        if ( ! $nav_role_admin ) { continue; }
                                    } else {
                                        continue;
                                    }
                                }
                            } 
                        }

                        if ( $nav['sub'] ) {
                            echo '<li class="nav-item">
                                    <a class="nav-link '.($active_page == $nav['nav'] ? 'active' : '').'" href="#navbar-'.$nav['nav'].'" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-'.$nav['nav'].'">
                                        <i class="ni '. $nav['icon'].' text-primary"></i>
                                        <span class="nav-link-text">'. $nav['title'].'</span>
                                    </a>
                                    <div class="collapse '.($active_page == $nav['nav'] ? 'show' : '').'" id="navbar-'.$nav['nav'].'">
                                        <ul class="nav nav-sm flex-column">';
                                        foreach($nav['sub'] as $sub):
                                            if ( !$sub) { continue; }
                                            if (as_administrator($member)) {
                                                if ( $access == 'partial' ) {
                                                    $subnav_role = array_intersect($sub['roles'], $roles);
                                                    if ( $sub['roles'] && !$subnav_role) { continue; }
                                                } 
                                                if ( $access == 'all' ) {
                                                    $subnav_role = array_intersect($sub['roles'], array(STAFF_ACCESS9));
                                                    if ( $sub['roles'] && $subnav_role) {
                                                        if ( $roles ) {
                                                            $subnav_role_admin = array_intersect($sub['roles'], $roles);
                                                            if ( ! $subnav_role_admin ) { continue; }
                                                        } else {
                                                            continue;
                                                        }
                                                    }
                                                } 
                                            }

                                            $sub_newtab = ((isset($sub['newtab']) && $sub['newtab']) ? 'target="_blank"' : '');
                                            $sub_icon   = ((isset($sub['icon']) && !empty($sub['icon'])) ? '<i class="fa '.$sub['icon'].'"></i>' : '');
                                            $sub_icon   = ( empty($sub_icon) ) ? strtoupper(substr($sub['title'], 0,1)) : $sub_icon;
                                            echo '<li class="nav-item">
                                                    <a href="'.$sub['link'].'" '.$sub_newtab.' class="nav-link '.($active_sub == $sub['nav'] ? 'active' : '').'">
                                                        <span class="sidenav-mini-icon"> '. $sub_icon .' </span>
                                                        <span class="sidenav-normal">'. $sub['title'] .'</span>
                                                    </a>
                                                </li>';
                                        endforeach;
                                    echo '</ul>
                                    </div>';

                        } else {
                            $nav_newtab = ((isset($nav['newtab']) && $nav['newtab']) ? 'target="_blank"' : '');
                            echo '<li class="nav-item">
                                    <a class="nav-link '.($active_page == $nav['nav'] ? 'active' : '').'" href="'.$nav['link'].'" '.$nav_newtab.'>
                                        <i class="ni '.$nav['icon'].' text-primary"></i>
                                        <span class="nav-link-text">'. $nav['title'] .'</span>
                                    </a>
                                </li>';
                        }
                    endforeach;
                    echo "</ul>";
                endif;
                ?>
                
                <!-- Divider -->
                <hr class="my-3">
                <!-- Heading -->
                <!-- <h6 class="navbar-heading p-0 text-muted">
                    <span class="docs-normal">Documentation</span>
                </h6> -->
                <!-- Navigation -->
                <ul class="navbar-nav">
                    <?php if (!as_administrator($member)) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url('shop') ?>">
                            <i class="ni ni-bag-17 text-info"></i>
                            <span class="nav-link-text">Order Produk</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url('activation') ?>">
                            <i class="ni ni-bag-17 text-info"></i>
                            <span class="nav-link-text">Aktivasi Personal Sales</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url('transfer') ?>">
                            <i class="ni ni-bag-17 text-info"></i>
                            <span class="nav-link-text">Transfer Produk Aktif</span>
                        </a>
                    </li>
                    <?php } ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url('logout') ?>">
                            <i class="ni ni-button-power text-warning"></i>
                            <span class="nav-link-text">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

