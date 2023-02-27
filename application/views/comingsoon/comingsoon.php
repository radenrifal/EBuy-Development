<!DOCTYPE HTML>
    <!-- <?php echo get_option('company_name'); ?> Template v1.0 -->
    
    <!--[if lt IE 7]><html dir="ltr" lang="en-US" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
    <!--[if IE 7]><html dir="ltr" lang="en-US" class="lt-ie9 lt-ie8"><![endif]-->
    <!--[if IE 8]><html dir="ltr" lang="en-US" class="lt-ie9"><![endif]-->
    <!--[if IE 9]><html dir="ltr" lang="en-US"  class="lt-ie10"> <![endif]-->
    <!--[if gt IE 9]><!--><html dir="ltr" lang="en-US" class="gt-ie9 non-ie"> <!--<![endif]-->
    <head>
        <!-- Meta Tags -->
    	<meta charset="UTF-8" />
    	<meta name="description" content="<?php echo get_option('company_name'); ?>" />
    	<meta name="author" content="<?php echo get_option('company_name'); ?>" />
    	
    	<!-- Mobile -->
    	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />
    	
    	<!-- Page Title -->
    	<title><?php echo $title; ?></title>
        
        <!-- Shortcut Icon -->
        <link rel="shortcut icon" href="<?php echo BE_IMG_PATH .'logo.png'; ?>" type="image/x-icon">
        
        <!-- CSS Stylesheets -->
        <link rel="stylesheet" type="text/css" href="<?php echo COMINGSOON_CSS_PATH; ?>style.css" />
    </head>
    
    <body>
        <div class="fullwidth clearfix">
        	<div id="topcontainer" class="bodycontainer clearfix" data-uk-scrollspy="{cls:'uk-animation-fade', delay: 300, repeat: true}">
        		<p><span class="fa fa-signal"></span></p>
        		<h1><span>Progress</span><br />is coming soon</h1>
        	</div>
        </div>
        
        <div class="arrow-separator arrow-white"></div>

        <div class="fullwidth colour1 clearfix">
        	<div id="countdown" class="bodycontainer clearfix" data-uk-scrollspy="{cls:'uk-animation-fade', delay: 300, repeat: true}">
        		<div id="countdowncont" class="clearfix">
        			<ul id="countscript">
        				<li>
        					<span class="days">00</span>
        					<p>Days</p>
        				</li>
        				<li>
        					<span class="hours">00</span>
        					<p>Hours</p>
        				</li>
        				<li class="clearbox">
        					<span class="minutes">00</span>
        					<p>Minutes</p>
        				</li>
        				<li>
        					<span class="seconds">00</span>
        					<p>Seconds</p>
        				</li>
        			</ul>
        		</div>
        	</div>
        </div>
        
        <div class="arrow-separator arrow-theme"></div>
        
        <div class="fullwidth clearfix">
        	<div id="footercont" class="bodycontainer clearfix" data-uk-scrollspy="{cls:'uk-animation-fade', delay: 300, repeat: true}">
        		<div id="socialmedia" class="clearfix">
        			<ul style="margin-bottom: 20px;">
        				<li><a title="" href="<?php echo get_option('facebook_link'); ?>" rel="external"><span class="fa fa-facebook"></span></a></li>
        				<li><a title="" href="<?php echo get_option('twitter_link'); ?>" rel="external"><span class="fa fa-twitter"></span></a></li>
        				<li><a title="" href="<?php echo get_option('googleplus_link'); ?>" rel="external"><span class="fa fa-google-plus"></span></a></li>
        			</ul>
        		</div>
        		<p style="margin-bottom: 0px;">Copyright &copy; 2020. <?php echo get_option('company_name'); ?></p>
        	</div>
        </div>

        <!-- Theme Core JavaScript ================================================== -->
        <script type="text/javascript" src="<?php echo COMINGSOON_JS_PATH; ?>jquery-1.11.1.min.js"></script>
    
        <!-- Theme JavaScript ======================================================= -->
        <script type="text/javascript" src="<?php echo COMINGSOON_JS_PATH; ?>countdown.js"></script>
        <script type="text/javascript" src="<?php echo COMINGSOON_JS_PATH; ?>owlcarousel.js"></script>
        <script type="text/javascript" src="<?php echo COMINGSOON_JS_PATH; ?>uikit.scrollspy.js"></script>
        <script type="text/javascript" src="<?php echo COMINGSOON_JS_PATH; ?>scripts.js"></script>
        
        <!-- JS Header ============================================================== -->
        <!--[if lt IE 9]><script src="<?php echo COMINGSOON_JS_PATH; ?>html5shiv.js"></script><![endif]-->
        
        <!-- Custom JS ============================================================== -->
        <script>
            /** Countdown Timer **/
            $(document).ready(function() {
                "use strict";
                var comingsoon_time = "<?php echo get_option('comingsoon_time'); ?>";
                var base_url = "<?php echo base_url(); ?>";
                
            	$("#countdown").countdown({
            		date: comingsoon_time, /** Enter new date here **/
            		format: "on"
            	},
            	function() {
                    // callback function
                    // $(location).attr('href',base_url);
                });   
            });
        </script>
    </body>
</html>