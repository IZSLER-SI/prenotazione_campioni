<?php   $filename =  dirname(dirname(__FILE__)).'/config.php';$file = file_exists($filename);if($file){	if(!filesize($filename) > 0){		header('location:../ct_install.php');	}	else{		include(dirname(dirname(__FILE__)) . "/objects/class_connection.php");		$cvars = new prenotazione_campioni_myvariable();		$host = trim($cvars->hostnames);		$un = trim($cvars->username);		$ps = trim($cvars->passwords); 		$db = trim($cvars->database);		$con = new prenotazione_campioni_db();		$conn = $con->connect();				if(($conn->connect_errno=='0' && ($host=='' || $db=='')) || $conn->connect_errno!='0' ) {			header('Location: ../config_index.php');		}	}}else{	echo "Config file does not exist";}ob_start();session_start();include(dirname(dirname(__FILE__)).'/header.php');if(!isset($_SESSION['ct_staffid']) && !isset($_SESSION['ct_login_user_id'])){    ?>    <script>        var loginObj={'site_url':'<?php echo SITE_URL;?>'};        var login_url=loginObj.site_url;        window.location=login_url+"admin/";    </script><?php  }include(dirname(dirname(__FILE__)) . '/class_configure.php');include(dirname(dirname(__FILE__))."/objects/class_dashboard.php");include(dirname(dirname(__FILE__))."/objects/class_setting.php");include(dirname(dirname(__FILE__))."/objects/class_general.php");include(dirname(dirname(__FILE__))."/objects/class_off_days.php");include(dirname(dirname(__FILE__))."/objects/class_version_update.php");$cvars = new prenotazione_campioni_myvariable();$host = trim($cvars->hostnames);$un = trim($cvars->username);$ps = trim($cvars->passwords); $db = trim($cvars->database);$con = new prenotazione_campioni_db();$conn = $con->connect();if(($conn->connect_errno=='0' && ($host=='' || $db=='')) || $conn->connect_errno!='0' ) {	header('Location: '.BASE_URL.'/config_index.php');    exit(0);}$objdashboard = new prenotazione_campioni_dashboard();$objdashboard->conn = $conn;$general=new prenotazione_campioni_general();$general->conn=$conn;$setting = new prenotazione_campioni_setting();$setting->conn = $conn;$setting->readAll();$getdateformat=$setting->get_option('ct_date_picker_date_format');$gettimeformat=$setting->get_option('ct_time_format');$offday=new prenotazione_campioni_provider_off_day();$offday->conn = $conn;$symbol_position=$setting->get_option('ct_currency_symbol_position');$decimal=$setting->get_option('ct_price_format_decimal_places');$objcheckversion = new prenotazione_campioni_version_update();$objcheckversion->conn = $conn;$current = $setting->get_option('ct_version');if($current == ""){  $objcheckversion->insert_option("ct_version","1.1");}if($current < 1.1){	$setting->set_option("ct_version","1.1");	$objcheckversion->update1_1();}if($current < 1.2){	$setting->set_option("ct_version","1.2");	$objcheckversion->update1_2();}if($current < 1.3){	$setting->set_option("ct_version","1.3");	$objcheckversion->update1_3();}if($current < 1.4){	$setting->set_option("ct_version","1.4");	$objcheckversion->update1_4();}if($current < 1.5){	$setting->set_option("ct_version","1.5");	$objcheckversion->update1_5();}if($current < 1.6){	$setting->set_option("ct_version","1.6");	$objcheckversion->update1_6();}if($current < 2.0){	$setting->set_option("ct_version","2.0");	$objcheckversion->update2_0();}if($current < 2.1){  $setting->set_option("ct_version","2.1");}if($current < 2.2){  $setting->set_option("ct_version","2.2");	$objcheckversion->update2_2();}if($current < 2.3){  $setting->set_option("ct_version","2.3");	$objcheckversion->update2_3();}if($current < 2.4){  $setting->set_option("ct_version","2.4");	$objcheckversion->update2_4();}if($current < 2.5){  $setting->set_option("ct_version","2.5");	$objcheckversion->update2_5();}if($current < 2.6){  $setting->set_option("ct_version","2.6");	$objcheckversion->update2_6();}if($current < 2.7){  $setting->set_option("ct_version","2.7");	$objcheckversion->update2_7();}if($current < 2.8){  $setting->set_option("ct_version","2.8");	$objcheckversion->update2_8();}if($current < 3.0){  $setting->set_option("ct_version","3.0");	$objcheckversion->update3_0();}if($current < 3.1){  $setting->set_option("ct_version","3.1");}if($current < 3.2){  $setting->set_option("ct_version","3.2");	$objcheckversion->update3_2();}if($current < 3.3){  $setting->set_option("ct_version","3.3");	$objcheckversion->update3_3();}if($current < 4.0){  $setting->set_option("ct_version","4.0");	$objcheckversion->update4_0();}if($current < 4.1){  $setting->set_option("ct_version","4.1");	$objcheckversion->update4_1();}if($current < 4.2){  $setting->set_option("ct_version","4.2");	$objcheckversion->update4_2();}if($current < 4.3){  $setting->set_option("ct_version","4.3");	$objcheckversion->update4_3();}if($current < 4.4){  $setting->set_option("ct_version","4.4");	$objcheckversion->update4_4();}if($current < 5.0){  $setting->set_option("ct_version","5.0");	$objcheckversion->update5_0();}if($current < 5.1){  $setting->set_option("ct_version","5.1");}if($current < 5.2){  $setting->set_option("ct_version","5.2");	$objcheckversion->update5_2();}if($current < 5.3){  $setting->set_option("ct_version","5.3");	$objcheckversion->update5_3();}if($current < 6.0){  $setting->set_option("ct_version","6.0");	$objcheckversion->update6_0();}if($current < 6.1){  $setting->set_option("ct_version","6.1");}if($current < 6.2){  $setting->set_option("ct_version","6.2");	$objcheckversion->update6_2();}if($current < 6.3){  $setting->set_option("ct_version","6.3");	$objcheckversion->update6_3();}if($current < 6.4){  $setting->set_option("ct_version","6.4");	$objcheckversion->update6_4();}if($current < 6.5){  $setting->set_option("ct_version","6.5");	$objcheckversion->update6_5();}$lang = $setting->get_option("ct_language");$label_language_values = array();$language_label_arr = $setting->get_all_labelsbyid($lang);if ($language_label_arr[1] != "" || $language_label_arr[3] != "" || $language_label_arr[4] != "" || $language_label_arr[5] != "" || $language_label_arr[6] != ""){	$default_language_arr = $setting->get_all_labelsbyid("en");	if($language_label_arr[1] != ''){		$label_decode_front = base64_decode($language_label_arr[1]);	}else{		$label_decode_front = base64_decode($default_language_arr[1]);	}	if($language_label_arr[3] != ''){		$label_decode_admin = base64_decode($language_label_arr[3]);	}else{		$label_decode_admin = base64_decode($default_language_arr[3]);	}	if($language_label_arr[4] != ''){		$label_decode_error = base64_decode($language_label_arr[4]);	}else{		$label_decode_error = base64_decode($default_language_arr[4]);	}	if($language_label_arr[5] != ''){		$label_decode_extra = base64_decode($language_label_arr[5]);	}else{		$label_decode_extra = base64_decode($default_language_arr[5]);	}	if($language_label_arr[6] != ''){		$label_decode_front_form_errors = base64_decode($language_label_arr[6]);	}else{		$label_decode_front_form_errors = base64_decode($default_language_arr[6]);	}		$label_decode_front_unserial = unserialize($label_decode_front);	$label_decode_admin_unserial = unserialize($label_decode_admin);	$label_decode_error_unserial = unserialize($label_decode_error);	$label_decode_extra_unserial = unserialize($label_decode_extra);	$label_decode_front_form_errors_unserial = unserialize($label_decode_front_form_errors);    	$label_language_values = array_merge($label_decode_front_unserial,$label_decode_admin_unserial,$label_decode_error_unserial,$label_decode_extra_unserial,$label_decode_front_form_errors_unserial);		foreach($label_language_values as $key => $value){		$label_language_values[$key] = urldecode($value);	}}else{    $default_language_arr = $setting->get_all_labelsbyid("en");    	$label_decode_front = base64_decode($default_language_arr[1]);	$label_decode_admin = base64_decode($default_language_arr[3]);	$label_decode_error = base64_decode($default_language_arr[4]);	$label_decode_extra = base64_decode($default_language_arr[5]);	$label_decode_front_form_errors = base64_decode($default_language_arr[6]);    		$label_decode_front_unserial = unserialize($label_decode_front);	$label_decode_admin_unserial = unserialize($label_decode_admin);	$label_decode_error_unserial = unserialize($label_decode_error);	$label_decode_extra_unserial = unserialize($label_decode_extra);	$label_decode_front_form_errors_unserial = unserialize($label_decode_front_form_errors);    	$label_language_values = array_merge($label_decode_front_unserial,$label_decode_admin_unserial,$label_decode_error_unserial,$label_decode_extra_unserial,$label_decode_front_form_errors_unserial);	foreach($label_language_values as $key => $value){		$label_language_values[$key] = urldecode($value);	}}?><!Doctype html><head>    <meta charset="utf-8" />    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>	<link rel="shortcut icon" type="image/png" href="<?php echo BASE_URL; ?>/assets/images/backgrounds/<?php echo $setting->get_option('ct_favicon_image');?>"/>    <title>prenotazione_campioni | Staff</title>    <meta name="description" content="" />    <meta name="author" content="" />    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/cta-reset.min.css" type="text/css" media="all">    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/cta-admin-style.css" type="text/css" media="all">    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/cta-admin-common.css" type="text/css" media="all">    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/cta-admin-responsive.css" type="text/css" media="all">    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap/bootstrap.min.css" type="text/css" media="all">    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/daterangepicker.css" type="text/css" media="all">		<?php   	if(in_array($lang,array('ary','ar','azb','fa_IR','haz'))){ ?>		<!-- admin rtl css -->	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap/bootstrap-rtl.min.css" type="text/css" media="all">	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/cta-admin-rtl.css" type="text/css" media="all">	<?php   } ?>     		    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/fullcalendar.css" type="text/css" media="all">    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/jquery.Jcrop.min.css" type="text/css" media="all">    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/intlTelInput.css" type="text/css" media="all">    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap/bootstrap-theme.min.css" type="text/css" media="all">    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap-toggle.min.css" type="text/css" media="all">    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap-select.min.css" type="text/css" media="all">    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/jquery.minicolors.css" type="text/css" media="all">    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/jquery.dataTables.min.css" type="text/css" media="all">    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/responsive.dataTables.min.css" type="text/css" media="all">    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/dataTables.bootstrap.min.css" type="text/css" media="all">    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/buttons.dataTables.min.css" type="text/css" media="all">    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/jquery-ui.min.css" type="text/css" media="all">	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/star_rating.min.css" type="text/css" media="all">  	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/font-awesome/css/font-awesome.min.css" type="text/css" media="all">	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/line-icons/simple-line-icons.css" type="text/css" media="all">	<!-- ** Google Fonts **  -->	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet">    <!-- ** Jquery ** -->    <script src="<?php echo BASE_URL; ?>/assets/js/jquery-2.1.4.min.js" type="text/javascript"></script>    <script src="<?php echo BASE_URL; ?>/assets/js/jquery-ui.min.js" type="text/javascript" ></script>    <script src="<?php echo BASE_URL; ?>/assets/js/moment.min.js" type="text/javascript" ></script>       <script src="<?php echo BASE_URL; ?>/assets/js/jquery.Jcrop.min.js" type="text/javascript" ></script>    <script src="<?php echo BASE_URL; ?>/assets/js/jquery.color.js" type="text/javascript" ></script>    <script src="<?php echo BASE_URL; ?>/assets/js/fullcalendar.min.js" type="text/javascript" ></script>    <script src="<?php echo BASE_URL; ?>/assets/js/lang-all.js" type="text/javascript" ></script>    <script src="<?php echo BASE_URL; ?>/assets/js/intlTelInput.js" type="text/javascript" ></script>	<script src="<?php echo BASE_URL; ?>/assets/js/jquery.nicescroll.min.js" type="text/javascript" ></script>    <script src="<?php echo BASE_URL; ?>/assets/js/bootstrap.min.js" type="text/javascript" ></script>	<?php   if(strpos($_SERVER['SCRIPT_NAME'],'service-extra-addons.php')==false && strpos($_SERVER['SCRIPT_NAME'],'service-manage-unit-price.php')==false && strpos($_SERVER['SCRIPT_NAME'],'service-manage-calculation-methods.php')==false ){ ?>	    <script src="<?php echo BASE_URL; ?>/assets/js/bootstrap-toggle.min.js" type="text/javascript" ></script>	<?php   } ?>    <script src="<?php echo BASE_URL; ?>/assets/js/bootstrap-select.min.js" type="text/javascript" ></script>    <script src="<?php echo BASE_URL; ?>/assets/js/daterangepicker.js" type="text/javascript" ></script>    <script src="<?php echo BASE_URL; ?>/assets/js/Chart.js" type="text/javascript" ></script>    <script src="<?php echo BASE_URL; ?>/assets/js/jquery.minicolors.min.js" type="text/javascript" ></script>    <!-- data tables all js inlcude pdf,csv, and excel -->    <script src="<?php echo BASE_URL; ?>/assets/js/datatable/jquery.dataTables.min.js" type="text/javascript" ></script>    <script src="<?php echo BASE_URL; ?>/assets/js/datatable/dataTables.responsive.min.js" type="text/javascript" ></script>    <script src="<?php echo BASE_URL; ?>/assets/js/datatable/dataTables.bootstrap.min.js" type="text/javascript" ></script>    <script src="<?php echo BASE_URL; ?>/assets/js/datatable/dataTables.buttons.min.js" type="text/javascript" ></script>    <script src="<?php echo BASE_URL; ?>/assets/js/datatable/jszip.min.js" type="text/javascript" ></script>    <script src="<?php echo BASE_URL; ?>/assets/js/datatable/pdfmake.min.js" type="text/javascript" ></script>    <script src="<?php echo BASE_URL; ?>/assets/js/datatable/vfs_fonts.js" type="text/javascript" ></script>    <script src="<?php echo BASE_URL; ?>/assets/js/datatable/buttons.html5.min.js" type="text/javascript" ></script>        <!--    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>-->    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->    <!--[if lt IE 9]>    <script src="js/html5shiv.js"></script>    <script src="js/respond.min.js"></script>    <![endif]-->	<script src="<?php echo BASE_URL; ?>/assets/js/star_rating_min.js" type="text/javascript"></script>	<script src="<?php echo BASE_URL; ?>/assets/js/jquery.validate.min.js"></script>		<?php   	include(dirname(dirname(__FILE__)) . "/objects/class_payment_hook.php");	$payment_hook = new prenotazione_campioni_paymentHook();	$payment_hook->conn = $conn;	$payment_hook->payment_extenstions_exist();	$purchase_check = $payment_hook->payment_purchase_status();	include(dirname(dirname(__FILE__)) . "/extension/ct-common-extension-js.php");	?>		<script src="<?php echo BASE_URL; ?>/assets/js/ct-common-admin-jquery.js" type="text/javascript"></script>	<?php      echo "<style>		#cta #cta-main-navigation .navbar-inverse{		background:".$setting->get_option('ct_primary_color_admin')." !important;	}	#cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,	#cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,	#cta #cta-top-nav .navbar .nav > .active > a:focus{		background-color: ".$setting->get_option('ct_secondary_color_admin')." ;		color: ".$setting->get_option('ct_text_color_admin')."  ;	}	#cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,	#cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {		background-color: ".$setting->get_option('ct_secondary_color_admin')." ;		color: ".$setting->get_option('ct_text_color_admin')."  ;	}	#cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a,	#cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a{		color: ".$setting->get_option('ct_text_color_admin')."  ;	}	#cta .noti_color{		color: ".$setting->get_option('ct_text_color_admin')." !important ;	}	#cta a#ct-notifications i.icon-bell.cta-new-booking{		color: ".$setting->get_option('ct_secondary_color_admin')." !important ;	}		#cta a.ct-tooltip-link{		color: ".$setting->get_option('ct_primary_color_admin')." !important ;	}	.navbar-inverse .navbar-nav>.open>a, .navbar-inverse .navbar-nav>.open>a:focus,	.navbar-inverse .navbar-nav>.open>a:hover{		background-color: ".$setting->get_option('ct_secondary_color_admin')." !important  ;	}	#cta #cta-staff-panel .ct-staff-right-details .member-offdays .ct-custom-checkbox  ul.ct-checkbox-list label span,	#cta .ct-custom-radio ul.ct-radio-list label span{		border-color: ".$setting->get_option('ct_primary_color_admin')." !important;	}	#cta #cta-staff-panel .ct-staff-right-details .member-offdays .ct-custom-checkbox  	ul.ct-checkbox-list input[type='checkbox']:checked + label span{		border-color: ".$setting->get_option('ct_secondary_color_admin')." !important;		background-color: ".$setting->get_option('ct_secondary_color_admin')." !important;	}	#cta .ct-custom-radio ul.ct-radio-list input[type='radio']:checked + label span{		border-color: ".$setting->get_option('ct_secondary_color_admin')." !important;	}	#cta .fc-toolbar {		background-color: ".$setting->get_option('ct_primary_color_admin')." !important;	}	#cta .ct-notification-main .notification-header{		color: ".$setting->get_option('ct_text_color_admin')." !important;		background-color: ".$setting->get_option('ct_secondary_color_admin')." !important;	}		#cta .fc-toolbar {		border-top: 1px solid ".$setting->get_option('ct_primary_color_admin')." !important;		border-left: 1px solid ".$setting->get_option('ct_primary_color_admin')." !important;		border-right: 1px solid ".$setting->get_option('ct_primary_color_admin')." !important;	}	#cta .fc button,	#cta .ct-notification-main .notification-header #ct-close-notifications{		color: ".$setting->get_option('ct_text_color_admin')." !important ;	}	#cta .ct-notification-main .notification-header #ct-close-notifications:hover{		background-color: ".$setting->get_option('ct_primary_color_admin')." !important;	}	#cta .fc button:hover{		color: ".$setting->get_option('ct_secondary_color_admin')." !important ;	}	#cta .rating-md{		font-size: 1.5em !important ;	}			/* iPads (portrait and landscape) ----------- */	@media only screen and (min-width : 768px) and (max-width : 1024px) {		#cta #cta-main-navigation .navbar-header,		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,		#cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {			color: ".$setting->get_option('ct_secondary_color_admin')."  ;		}			}	/* iPads (landscape) ----------- */	@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : landscape) {		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,		#cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {			background-color: ".$setting->get_option('ct_secondary_color_admin')." ;			color: ".$setting->get_option('ct_text_color_admin')."  ;		}		}	/* iPads (portrait) ----------- */	@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) {		#cta #cta-top-nav .navbar-header,		#cta #cta-main-navigation .navbar-header,		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,		#cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,		#cta #cta-top-nav .navbar .nav > .active > a:focus,		#cta #cta-top-nav .navbar-nav > li > a:hover,		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,		#cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {			color: ".$setting->get_option('ct_secondary_color_admin')."  ;		}		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,		#cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,		#cta #cta-top-nav .navbar .nav > .active > a:focus{			background: unset !important;		}	}		/********** iPad 3 **********/	@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : landscape) and (-webkit-min-device-pixel-ratio : 2) {		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,		#cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {			background-color: ".$setting->get_option('ct_secondary_color_admin')." ;			color: ".$setting->get_option('ct_text_color_admin')."  ;		}	}	@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) and (-webkit-min-device-pixel-ratio : 2) {			#cta #cta-top-nav .navbar-header,		#cta #cta-main-navigation .navbar-header,		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,		#cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,		#cta #cta-top-nav .navbar .nav > .active > a:focus,		#cta #cta-top-nav .navbar-nav > li > a:hover,		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,		#cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {			color: ".$setting->get_option('ct_secondary_color_admin')."  ;		}		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,		#cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,		#cta #cta-top-nav .navbar .nav > .active > a:focus{			background: unset !important;		}	}	/* Smartphones (landscape) ----------- */	@media only screen and (max-width: 767px) {		#cta #cta-top-nav .navbar-header,		#cta #cta-main-navigation .navbar-header,		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,		#cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,		#cta #cta-top-nav .navbar .nav > .active > a:focus,		#cta #cta-top-nav .navbar-nav > li > a:hover,		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,		#cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {			color: ".$setting->get_option('ct_secondary_color_admin')."  ;		}		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,		#cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,		#cta #cta-top-nav .navbar .nav > .active > a:focus{			background: unset !important;		}			}		/* Smartphones (portrait and landscape) ----------- */	@media only screen and (min-width : 320px) and (max-width : 480px) {				#cta #cta-top-nav .navbar-header,		#cta #cta-main-navigation .navbar-header,		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,		#cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,		#cta #cta-top-nav .navbar .nav > .active > a:focus,		#cta #cta-top-nav .navbar-nav > li > a:hover,		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,		#cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {			color: ".$setting->get_option('ct_secondary_color_admin')."  ;		}		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,		#cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,		#cta #cta-top-nav .navbar .nav > .active > a:focus{			background: unset !important;		}	}</style>";    ?></head><body><div id="rtl-width-setter-enable" style="display:none;"><?php echo $label_language_values['enable'];?></div><div id="rtl-width-setter-disable" style="display:none;"><?php echo $label_language_values['disable'];?></div><div id="rtl-width-setter-on" style="display:none;"><?php echo $label_language_values['o_n'];?></div><div id="rtl-width-setter-off" style="display:none;"><?php echo $label_language_values['off'];?></div> <div class="ct-wrapper"  id="cta"> <!-- main wrapper -->    <!-- loader -->    <div class="ct-loading-main">        <div class="loader">Loading...</div>    </div>    <header class="ct-header">        <?php          if(isset($_SESSION['ct_staffid']))        {        ?>		<div id="cta-top-nav" class="navbar-inner staff-nav">            <nav role="navigation" class="navbar navbar-inverse navbar-fixed-top">                <!-- Brand and toggle get grouped for better mobile display -->				<div class="col-md-12">                <div class="navbar-header">                    <button type="button" data-target="#navbarCollapseMain" data-toggle="collapse" class="navbar-toggle">                        <span class="sr-only">Toggle navigation</span>                        <i class="fa fa-cog"></i>                    </button>                    <a href="<?php echo BASE_URL; ?>" class="navbar-brand"><?php echo $setting->get_option('ct_company_name')." | Staff" ;?></a>                </div>                <!-- Collection of nav links and other content for toggling                 <div id="navbarCollapsetop" class="collapse navbar-collapse">					<ul class="nav navbar-nav navbar-right cta-right-nav-icons">						<li><a href="<?php echo SITE_URL . "admin/" ?>"><img height="32" width="32" class="profile-img br-100" src="<?php echo BASE_URL; ?>/assets/images/services/staff_30618.jpg" /></a></li>					</ul>                 </div>-->				</div>            </nav>        </div><!-- top bar end here -->			    <?php   }?>		 </header>    <?php      include(dirname(dirname(__FILE__))."/admin/language_js_objects.php");   ?>	    <script type="text/javascript">		var ajax_url = '<?php echo AJAX_URL;?>';		var base_url = '<?php echo BASE_URL;?>';		var times={'time_format_values':'<?php echo $gettimeformat;?>'};		var language_new ={'selected_language':'<?php echo substr($lang, strpos($lang,0), strpos($lang, "_")); ;?>'};		var ct_calendar_defaultView = 'month';		var ct_calendar_firstDay = '1';		var titles ={			'selected_today':'<?php echo $label_language_values['calendar_today'];?>',			'selected_month':'<?php echo $label_language_values['calendar_month'];?>',			'selected_week':'<?php echo $label_language_values['calendar_week'];?>',			'selected_day':'<?php echo $label_language_values['calendar_day'];?>'};		var site_ur = {'site_url':'<?php echo SITE_URL;?>'};		<?php    $nacode = explode(',',$setting->get_option("ct_company_country_code"));  $allowed = $setting->get_option("ct_phone_display_country_code");	?>  var countrycodeObj = {'numbercode': '<?php echo $nacode[0];?>', 'alphacode': '<?php echo $nacode[1];?>', 'countrytitle': '<?php echo $nacode[2];?>', 'allowed': '<?php echo $allowed;?>'};  var month = {	'january' : '<?php echo ucfirst(strtolower($label_language_values['january']));?>',	'feb' : '<?php echo ucfirst(strtolower($label_language_values['february']));?>',	'mar' : '<?php echo ucfirst(strtolower($label_language_values['march']));?>',	'apr' : '<?php echo ucfirst(strtolower($label_language_values['april']));?>',	'may' : '<?php echo ucfirst(strtolower($label_language_values['may']));?>',	'jun' : '<?php echo ucfirst(strtolower($label_language_values['june']));?>',	'jul' : '<?php echo ucfirst(strtolower($label_language_values['july']));?>',	'aug' : '<?php echo ucfirst(strtolower($label_language_values['august']));?>',	'sep' : '<?php echo ucfirst(strtolower($label_language_values['september']));?>',	'oct' : '<?php echo ucfirst(strtolower($label_language_values['october']));?>',	'nov' : '<?php echo ucfirst(strtolower($label_language_values['november']));?>',	'dec' : '<?php echo ucfirst(strtolower($label_language_values['december']));?>'};	var days_date = {	'sun':'<?php echo ucfirst($label_language_values['su']);?>',	'mon':'<?php echo ucfirst($label_language_values['mo']);?>',	'tue':'<?php echo ucfirst($label_language_values['tu']);?>',	'wed':'<?php echo ucfirst($label_language_values['we']);?>',	'thu':'<?php echo ucfirst($label_language_values['th']);?>',	'fri':'<?php echo ucfirst($label_language_values['fr']);?>',	'sat':'<?php echo ucfirst($label_language_values['sa']);?>'};	</script>	<!-- all alerts, success messages -->    <div class="ct-alert-msg-show-main mainheader_message">        <div class="ct-all-alert-messags alert alert-success mainheader_message_inner">            <!-- <a href="#" class="close" data-dismiss="alert">&times;</a> -->        <strong><?php echo $label_language_values['success']." ";?></strong><span id="ct_sucess_message"> </span>        </div>    </div>    <div class="ct-alert-msg-show-main mainheader_message_fail">        <div class="ct-all-alert-messags alert alert-danger mainheader_message_inner_fail">            <!-- <a href="#" class="close" data-dismiss="alert">&times;</a> -->            <strong><?php echo $label_language_values['failed']." ";?></strong> <span id="ct_sucess_message_fail"></span>        </div>    </div>		<?php   	$english_date_array = array("January","Jan","February","Feb","March","Mar","April","Apr","May","June","Jun","July","Jul","August","Aug","September","Sep","October","Oct","November","Nov","December","Dec","Sun","Mon","Tue","Wed","Thu","Fri","Sat","su","mo","tu","we","th","fr","sa","AM","PM");	$selected_lang_label = array(ucfirst(strtolower($label_language_values['january'])),ucfirst(strtolower($label_language_values['jan'])),ucfirst(strtolower($label_language_values['february'])),ucfirst(strtolower($label_language_values['feb'])),ucfirst(strtolower($label_language_values['march'])),ucfirst(strtolower($label_language_values['mar'])),ucfirst(strtolower($label_language_values['april'])),ucfirst(strtolower($label_language_values['apr'])),ucfirst(strtolower($label_language_values['may'])),ucfirst(strtolower($label_language_values['june'])),ucfirst(strtolower($label_language_values['jun'])),ucfirst(strtolower($label_language_values['july'])),ucfirst(strtolower($label_language_values['jul'])),ucfirst(strtolower($label_language_values['august'])),ucfirst(strtolower($label_language_values['aug'])),ucfirst(strtolower($label_language_values['september'])),ucfirst(strtolower($label_language_values['sep'])),ucfirst(strtolower($label_language_values['october'])),ucfirst(strtolower($label_language_values['oct'])),ucfirst(strtolower($label_language_values['november'])),ucfirst(strtolower($label_language_values['nov'])),ucfirst(strtolower($label_language_values['december'])),ucfirst(strtolower($label_language_values['dec'])),ucfirst(strtolower($label_language_values['sun'])),ucfirst(strtolower($label_language_values['mon'])),ucfirst(strtolower($label_language_values['tue'])),ucfirst(strtolower($label_language_values['wed'])),ucfirst(strtolower($label_language_values['thu'])),ucfirst(strtolower($label_language_values['fri'])),ucfirst(strtolower($label_language_values['sat'])),ucfirst(strtolower($label_language_values['su'])),ucfirst(strtolower($label_language_values['mo'])),ucfirst(strtolower($label_language_values['tu'])),ucfirst(strtolower($label_language_values['we'])),ucfirst(strtolower($label_language_values['th'])),ucfirst(strtolower($label_language_values['fr'])),ucfirst(strtolower($label_language_values['sa'])),strtoupper($label_language_values['am']),strtoupper($label_language_values['pm']));	?>