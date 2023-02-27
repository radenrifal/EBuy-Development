<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('ddm_headstyles') ){
    /**
     * set the head styles dynamicly
     *
     * @param   array       $styles       Array of Styles
     * @return  $loadstyles Head Styles   string
     */
    function ddm_headstyles($styles, $carabiner = false)
    {
        $CI =& get_instance();
        
        $carabiner  = $carabiner ? $carabiner : config_item('cfg_carabiner');
        $main_css   = '';
        $loadstyles = '';
        if( !$styles || empty($styles) ) return $loadstyles;

        foreach($styles as $s){
            $main_css[] = array($s);
            $loadstyles .= '<link rel="stylesheet" type="text/css" href="'.$s.'" />';
        }
        
        return $carabiner ? $main_css : $loadstyles;
    }
}

if ( !function_exists('ddm_scripts') ){
    /**
     * set the script dynamicly
     *
     * @param   array       $scripts      Array of Script
     * @return  $loadscript Load Script   string
     */
    function ddm_scripts($scripts, $main='')
    {
    	$CI =& get_instance();
        
        $carabiner  = config_item('cfg_carabiner');
        $main_js    = '';
        $loadscript = '';
        if( !$scripts || empty($scripts) ) return $loadscript;
        
        if( !empty($main) ) {
            $loadscript .= $main;
            $main_js = array($main);
        }
    
    	foreach($scripts as $s){
            $main_js[]  = array($s);
            $loadscript .= '<script type="text/javascript" src="'.$s.'"></script>';
    	}
        
        return $carabiner ? $main_js : $loadscript;
    }
}

if ( !function_exists('ddm_scripts_init') ){
    /**
     * set the script init dynamicly
     *
     * @param   array       $scripts      Array of Script Init
     * @return  $loadscript Load Script   string
     */
    function ddm_scripts_init($scripts)
    {
    	$CI =& get_instance();
        
        $scripts_init = '';
        if( !$scripts || empty($scripts) ) return $scripts_init;
    
        $scripts_init .= '
        <script type="text/javascript">
            jQuery(document).ready(function() { ';
            	foreach($scripts as $s){
                    $scripts_init .= $s;
            	}
        $scripts_init .= '
            });
        </script>';
        
        return $scripts_init;
    }
}
