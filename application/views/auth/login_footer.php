<!--===============================================================================================-->
<script src="<?php echo ASSET_PATH; ?>auth/js/jquery-3.4.1.min.js"></script>
<!--===============================================================================================-->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="<?php echo ASSET_PATH; ?>auth/js/bootstrap.min.js"></script>
<script src="<?php echo ASSET_PATH; ?>auth/plugins/waitMe/waitMe.js"></script>

<!-- Plugins JavaScript ====================================================== -->
<?php
    if ( $carabiner = config_item('cfg_carabiner') ) {	    
        $this->carabiner->group('app_js', array('js' => $scripts));
        echo $this->carabiner->display('app_js');
    } else {
        echo $scripts; 
    }
?>

<!-- Init JavaScript ========================================================= -->
<?php 
    echo $scripts_init; 
    echo $scripts_add;
?>