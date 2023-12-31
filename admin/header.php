<?php
error_reporting(0);
$filename =  dirname(dirname(__FILE__)) . '/config.php';
$file = file_exists($filename);
if ($file) {
	if (!filesize($filename) > 0) {
		header('location:../ct_install.php');
	} else {
		include(dirname(dirname(__FILE__)) . "/objects/class_connection.php");
		$cvars = new prenotazione_campioni_myvariable();
		$host = trim($cvars->hostnames);
		$un = trim($cvars->username);
		$ps = trim($cvars->passwords);
		$db = trim($cvars->database);

		$con = new prenotazione_campioni_db();
		$conn = $con->connect();

		if (($conn->connect_errno == '0' && ($host == '' || $db == '')) || $conn->connect_errno != '0') {
			header('Location: ../config_index.php');
		}
	}
} else {
	echo "Config file does not exist";
}

ob_start();
session_start();
include(dirname(dirname(__FILE__)) . '/header.php');
if (!isset($_SESSION['ct_adminid']) && !isset($_SESSION['ct_accettazioneid']) && !isset($_SESSION['ct_laboratorioid']) && !isset($_SESSION['ct_login_user_id'])) {
?>
	<script>
		var loginObj = {
			'site_url': '<?php echo SITE_URL; ?>'
		};
		var login_url = loginObj.site_url;
		window.location = login_url + "admin/";
	</script>
<?php
}
//include(dirname(dirname(__FILE__)) . '/class_configure.php');
include(dirname(dirname(__FILE__)) . "/objects/class_dashboard.php");
include(dirname(dirname(__FILE__)) . "/objects/class_setting.php");
include(dirname(dirname(__FILE__)) . "/objects/class_general.php");
include(dirname(dirname(__FILE__)) . "/objects/class_off_days.php");
include(dirname(dirname(__FILE__)) . "/objects/class_version_update.php");
include(dirname(dirname(__FILE__)) . "/objects/class_gc_hook.php");
$cvars = new prenotazione_campioni_myvariable();
$host = trim($cvars->hostnames);
$un = trim($cvars->username);
$ps = trim($cvars->passwords);
$db = trim($cvars->database);
$con = new prenotazione_campioni_db();
$conn = $con->connect();
$labs = getLabsByUser();
$select_labs = "<select id='labs_select_box' class='form-control'>";
$lab_selected = isset($_SESSION['lab_selected']) ? $_SESSION['lab_selected'] : 1;
foreach ($labs as $lab) {
	if($lab_selected == $lab['id']){
		$select_labs .= "<option value='" . $lab['id'] . "'selected>" . $lab['descrizione'] . "</option>";
	}else{
		$select_labs .= "<option value='" . $lab['id'] . "'>" . $lab['descrizione'] . "</option>";
	}
}
$select_labs .= "</select>";
if (($conn->connect_errno == '0' && ($host == '' || $db == '')) || $conn->connect_errno != '0') {
	header('Location: ' . BASE_URL . '/config_index.php');
	exit(0);
}
if (getenv("AMBIENTE") != "master") {
	echo '<div style="position:fixed;width:100%;background: #ff000061;height: 40px;top: 0px;color:white;text-align:center;font-size: 20px;"><b>AMBIENTE DI TEST</b></div>';
}
$objdashboard = new prenotazione_campioni_dashboard();
$objdashboard->conn = $conn;
$general = new prenotazione_campioni_general();
$general->conn = $conn;
$setting = new prenotazione_campioni_setting();
$setting->conn = $conn;
$setting->readAll();
$getdateformat = $setting->get_option('ct_date_picker_date_format');
$gettimeformat = $setting->get_option('ct_time_format');
$offday = new prenotazione_campioni_provider_off_day();
$offday->conn = $conn;
$symbol_position = $setting->get_option('ct_currency_symbol_position');
$decimal = $setting->get_option('ct_price_format_decimal_places');
$gc_hook = new prenotazione_campioni_gcHook();
$gc_hook->conn = $conn;

$lang = $setting->get_option("ct_language");
$label_language_values = array();
$language_label_arr = $setting->get_all_labelsbyid($lang);
if ($language_label_arr[1] != "" || $language_label_arr[3] != "" || $language_label_arr[4] != "" || $language_label_arr[5] != "" || $language_label_arr[6] != "") {
	$default_language_arr = $setting->get_all_labelsbyid("en");
	if ($language_label_arr[1] != '') {
		$label_decode_front = base64_decode($language_label_arr[1]);
	} else {
		$label_decode_front = base64_decode($default_language_arr[1]);
	}
	if ($language_label_arr[3] != '') {
		$label_decode_admin = base64_decode($language_label_arr[3]);
	} else {
		$label_decode_admin = base64_decode($default_language_arr[3]);
	}
	if ($language_label_arr[4] != '') {
		$label_decode_error = base64_decode($language_label_arr[4]);
	} else {
		$label_decode_error = base64_decode($default_language_arr[4]);
	}
	if ($language_label_arr[5] != '') {
		$label_decode_extra = base64_decode($language_label_arr[5]);
	} else {
		$label_decode_extra = base64_decode($default_language_arr[5]);
	}
	if ($language_label_arr[6] != '') {
		$label_decode_front_form_errors = base64_decode($language_label_arr[6]);
	} else {
		$label_decode_front_form_errors = base64_decode($default_language_arr[6]);
	}

	$label_decode_front_unserial = unserialize($label_decode_front);
	$label_decode_admin_unserial = unserialize($label_decode_admin);
	$label_decode_error_unserial = unserialize($label_decode_error);
	$label_decode_extra_unserial = unserialize($label_decode_extra);
	$label_decode_front_form_errors_unserial = unserialize($label_decode_front_form_errors);

	$label_language_arr = array_merge($label_decode_front_unserial, $label_decode_admin_unserial, $label_decode_error_unserial, $label_decode_extra_unserial, $label_decode_front_form_errors_unserial);
	foreach ($label_language_arr as $key => $value) {
		$label_language_values[$key] = urldecode($value);
	}
} else {
	$default_language_arr = $setting->get_all_labelsbyid("en");

	$label_decode_front = base64_decode($default_language_arr[1]);
	$label_decode_admin = base64_decode($default_language_arr[3]);
	$label_decode_error = base64_decode($default_language_arr[4]);
	$label_decode_extra = base64_decode($default_language_arr[5]);
	$label_decode_front_form_errors = base64_decode($default_language_arr[6]);

	$label_decode_front_unserial = unserialize($label_decode_front);
	$label_decode_admin_unserial = unserialize($label_decode_admin);
	$label_decode_error_unserial = unserialize($label_decode_error);
	$label_decode_extra_unserial = unserialize($label_decode_extra);
	$label_decode_front_form_errors_unserial = unserialize($label_decode_front_form_errors);

	$label_language_arr = array_merge($label_decode_front_unserial, $label_decode_admin_unserial, $label_decode_error_unserial, $label_decode_extra_unserial, $label_decode_front_form_errors_unserial);
	foreach ($label_language_arr as $key => $value) {
		$label_language_values[$key] = urldecode($value);
	}
}

?>
<!Doctype html>

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="shortcut icon" type="image/png" href="<?php echo BASE_URL; ?>/assets/images/backgrounds/<?php echo $setting->get_option('ct_favicon_image'); ?>" />
	<title><?php echo $setting->get_option("ct_page_title"); ?> |
		<?php
		if (strpos($_SERVER['SCRIPT_NAME'], 'my-appointments.php') != false) {
			echo 'My Appointments';
		} elseif (strpos($_SERVER['SCRIPT_NAME'], 'user-profile.php') != false) {
			echo 'Profile';
		} else {
			echo "Admin";
		}
		?>
	</title>
	<meta name="description" content="" />
	<meta name="author" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Manual Booking CSS Files Start -->
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/ct-main.css" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/ct-common.css" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/tooltipster.bundle.min.css" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/tooltipster-sideTip-shadow.min.css" type="text/css" media="all" />
	<?php
	if (in_array($lang, array('ary', 'ar', 'azb', 'fa_IR', 'haz'))) { ?>
		<!-- Front RTL style -->
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/cta-front-rtl.css" type="text/css" media="all" />

	<?php   } ?>
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/jquery_editor/jquery-te-1.4.0.css" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/ct-responsive.css" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/ct-manual-booking.css" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/ct-reset.min.css" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/jquery-ui.theme.min.css" type="text/css" media="all" />

	<style>
		.error {
			color: red;
		}
	</style>
	<style>
		#ct .not-scroll-custom {
			margin-top: 0 !important;
		}
	</style>
	<style>
		#filt button {
			margin-left: 20px;
		}

		.dt-buttons {
			padding-left: 2%;
		}

		#filt label {
			padding-left: 30px;
			margin-top: 5.5%;
		}

		div#header3:before {
			width: 100%;
			height: 43px;
			background: #4e5ecc;
			content: '';
			position: absolute;
		}

		.dnn_layout {
			width: 100%;
			margin: 0px auto;
		}

		.vr {
			border-right: 1px solid #dbdbdb;
		}

		.head {
			height: 43px;
			background: #4e5ecc;
		}

		.bottom {
			border-top: 2px solid #4e5ecc;
			box-shadow: 0 2px 4px 0 rgb(0 0 0 / 20%);
			margin-bottom: 40px;
		}

		.max {
			width: 100%;
			--bs-gutter-x: 1.5rem;
			--bs-gutter-y: 0;
			display: flex;
			flex-wrap: wrap;
			margin-top: calc(var(--bs-gutter-y) * -1);
			margin-right: calc(var(--bs-gutter-x) * -.5);
			margin-left: calc(var(--bs-gutter-x) * -.5);
		}

		.str {
			background: url(../assets/images/claim.png);
			background-position: right 60px top 9px;
			background-repeat: no-repeat;
			background-size: contain;
		}

		.cont {
			text-align: center;
		}

		li.line {
			display: flex !important;
			flex-direction: row;
			align-items: center;
		}

		span#lab_selected_view {
            font-size: 22px;
            color: white;
            padding-left: 50px;
            display: flex;
            text-align: center;
            align-items: center;
            justify-content: center;
		}
		#download{
margin-left: 10px;
		}
	</style>
	<!-- Manual Booking CSS Files End -->
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/cta-reset.min.css" type="text/css" media="all">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/cta-admin-style.css" type="text/css" media="all">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/cta-admin-common.css" type="text/css" media="all">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/cta-admin-responsive.css" type="text/css" media="all">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap/bootstrap.min.css" type="text/css" media="all">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/daterangepicker.css" type="text/css" media="all">

	<?php
	if (in_array($lang, array('ary', 'ar', 'azb', 'fa_IR', 'haz'))) { ?>
		<!-- admin rtl css -->
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap/bootstrap-rtl.min.css" type="text/css" media="all">
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/cta-admin-rtl.css" type="text/css" media="all">
	<?php   } ?>

	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/fullcalendar.css" type="text/css" media="all">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/jquery.Jcrop.min.css" type="text/css" media="all">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/intlTelInput.css" type="text/css" media="all">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap/bootstrap-theme.min.css" type="text/css" media="all">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap-toggle.min.css" type="text/css" media="all">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap-select.min.css" type="text/css" media="all">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/jquery.minicolors.css" type="text/css" media="all">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/jquery.dataTables.min.css" type="text/css" media="all">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/responsive.dataTables.min.css" type="text/css" media="all">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/dataTables.bootstrap.min.css" type="text/css" media="all">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/buttons.dataTables.min.css" type="text/css" media="all">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/jquery-ui.min.css" type="text/css" media="all">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/star_rating.min.css" type="text/css" media="all">

	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/font-awesome/css/font-awesome.min.css" type="text/css" media="all">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/line-icons/simple-line-icons.css" type="text/css" media="all">
	<!-- ** Google Fonts **  -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet">
	<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
	<!-- ** Jquery ** -->
	<script src="<?php echo BASE_URL; ?>/assets/js/jquery-2.1.4.min.js" type="text/javascript"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/bootstrap-multiselect.js" type="text/javascript"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/jquery-ui.min.js" type="text/javascript"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/moment.min.js" type="text/javascript"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/jquery.Jcrop.min.js" type="text/javascript"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/jquery.color.js" type="text/javascript"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/fullcalendar.min.js" type="text/javascript"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/lang-all.js" type="text/javascript"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/gcal.js" type="text/javascript"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/intlTelInput.js" type="text/javascript"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/bootstrap.min.js" type="text/javascript"></script>
	<?php if (strpos($_SERVER['SCRIPT_NAME'], 'service-extra-addons.php') == false && strpos($_SERVER['SCRIPT_NAME'], 'service-manage-unit-price.php') == false && strpos($_SERVER['SCRIPT_NAME'], 'service-manage-calculation-methods.php') == false) { ?>
		<script src="<?php echo BASE_URL; ?>/assets/js/bootstrap-toggle.min.js" type="text/javascript"></script>
	<?php   } ?>
	<script src="<?php echo BASE_URL; ?>/assets/js/vue.min.js" type="text/javascript"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/bootstrap-select.min.js" type="text/javascript"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/daterangepicker.js" type="text/javascript"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/Chart.js" type="text/javascript"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/jquery.minicolors.min.js" type="text/javascript"></script>
	<!-- data tables all js inlcude pdf,csv, and excel -->
	<script src="<?php echo BASE_URL; ?>/assets/js/datatable/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/datatable/dataTables.responsive.min.js" type="text/javascript"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/datatable/dataTables.bootstrap.min.js" type="text/javascript"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/datatable/dataTables.buttons.min.js" type="text/javascript"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/datatable/jszip.min.js" type="text/javascript"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/datatable/pdfmake.min.js" type="text/javascript"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/datatable/vfs_fonts.js" type="text/javascript"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/datatable/buttons.html5.min.js" type="text/javascript"></script>

	<!--    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
	<!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
	<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
	<script src="https://cdn.datatables.net/plug-ins/1.10.21/sorting/datetime-moment.js"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/star_rating_min.js" type="text/javascript"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/jquery.validate.min.js"></script>
	<?php
	include(dirname(dirname(__FILE__)) . "/objects/class_payment_hook.php");
	$payment_hook = new prenotazione_campioni_paymentHook();
	$payment_hook->conn = $conn;
	$payment_hook->payment_extenstions_exist();
	$purchase_check = $payment_hook->payment_purchase_status();
	include(dirname(dirname(__FILE__)) . "/extension/ct-common-extension-js.php");
	?>
	<script src="<?php echo BASE_URL; ?>/assets/js/ct-common-admin-jquery.js?<?php echo time(); ?>" type="text/javascript"></script>
	<?php
	echo "<style>
	
	#cta #cta-main-navigation .navbar-inverse{
		background:" . $setting->get_option('ct_primary_color_admin') . " !important;
	}
	#cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,
	#cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,
	#cta #cta-top-nav .navbar .nav > .active > a:focus{
		background-color: " . $setting->get_option('ct_secondary_color_admin') . " ;
		color: " . $setting->get_option('ct_text_color_admin') . "  ;
	}
	#cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,
	#cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {
		background-color: " . $setting->get_option('ct_secondary_color_admin') . " ;
		color: " . $setting->get_option('ct_text_color_admin') . "  ;
	}
	#cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a,
	#cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a{
		color: " . $setting->get_option('ct_text_color_admin') . "  ;
	}
	#cta .noti_color{
		color: " . $setting->get_option('ct_text_color_admin') . " !important ;
	}
	#cta a#ct-notifications i.icon-bell.cta-new-booking{
		color: " . $setting->get_option('ct_secondary_color_admin') . " !important ;
	}
	
	#cta a.ct-tooltip-link{
		color: " . $setting->get_option('ct_primary_color_admin') . " !important ;
	}
	.navbar-inverse .navbar-nav>.open>a, .navbar-inverse .navbar-nav>.open>a:focus,
	.navbar-inverse .navbar-nav>.open>a:hover{
		background-color: " . $setting->get_option('ct_secondary_color_admin') . " !important  ;
	}
	#cta #cta-staff-panel .ct-staff-right-details .member-offdays .ct-custom-checkbox  ul.ct-checkbox-list label span,
	#cta .ct-custom-radio ul.ct-radio-list label span{
		border-color: " . $setting->get_option('ct_primary_color_admin') . " !important;
	}
	#cta #cta-staff-panel .ct-staff-right-details .member-offdays .ct-custom-checkbox  	ul.ct-checkbox-list input[type='checkbox']:checked + label span{
		border-color: " . $setting->get_option('ct_secondary_color_admin') . " !important;
		background-color: " . $setting->get_option('ct_secondary_color_admin') . " !important;
	}
	#cta .ct-custom-radio ul.ct-radio-list input[type='radio']:checked + label span{
		border-color: " . $setting->get_option('ct_secondary_color_admin') . " !important;
	}
	#cta .fc-toolbar {
		background-color: " . $setting->get_option('ct_primary_color_admin') . " !important;
	}
	#cta .ct-notification-main .notification-header{
		color: " . $setting->get_option('ct_text_color_admin') . " !important;
		background-color: " . $setting->get_option('ct_secondary_color_admin') . " !important;
	}
	
	#cta .fc-toolbar {
		border-top: 1px solid " . $setting->get_option('ct_primary_color_admin') . " !important;
		border-left: 1px solid " . $setting->get_option('ct_primary_color_admin') . " !important;
		border-right: 1px solid " . $setting->get_option('ct_primary_color_admin') . " !important;
	}
	#cta .fc button,
	#cta .ct-notification-main .notification-header #ct-close-notifications{
		color: " . $setting->get_option('ct_text_color_admin') . " !important ;
	}
	#cta .ct-notification-main .notification-header #ct-close-notifications:hover{
		background-color: " . $setting->get_option('ct_primary_color_admin') . " !important;
	}
	#cta .fc button:hover{
		color: " . $setting->get_option('ct_secondary_color_admin') . " !important ;
	}
	
	
	/* iPads (portrait and landscape) ----------- */
	@media only screen and (min-width : 768px) and (max-width : 1024px) {
		#cta #cta-main-navigation .navbar-header,
		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,
		#cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {
			color: " . $setting->get_option('ct_secondary_color_admin') . "  ;
		}
		
	}
	/* iPads (landscape) ----------- */
	@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : landscape) {
		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,
		#cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {
			background-color: " . $setting->get_option('ct_secondary_color_admin') . " ;
			color: " . $setting->get_option('ct_text_color_admin') . "  ;
		}
	
	}
	/* iPads (portrait) ----------- */
	@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) {
		#cta #cta-top-nav .navbar-header,
		#cta #cta-main-navigation .navbar-header,
		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,
		#cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,
		#cta #cta-top-nav .navbar .nav > .active > a:focus,
		#cta #cta-top-nav .navbar-nav > li > a:hover,
		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,
		#cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {
			color: " . $setting->get_option('ct_secondary_color_admin') . "  ;
		}
		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,
		#cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,
		#cta #cta-top-nav .navbar .nav > .active > a:focus{
			background: unset !important;
		}
	}	
	/********** iPad 3 **********/
	@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : landscape) and (-webkit-min-device-pixel-ratio : 2) {
		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,
		#cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {
			background-color: " . $setting->get_option('ct_secondary_color_admin') . " ;
			color: " . $setting->get_option('ct_text_color_admin') . "  ;
		}
	}
	@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) and (-webkit-min-device-pixel-ratio : 2) {	
		#cta #cta-top-nav .navbar-header,
		#cta #cta-main-navigation .navbar-header,
		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,
		#cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,
		#cta #cta-top-nav .navbar .nav > .active > a:focus,
		#cta #cta-top-nav .navbar-nav > li > a:hover,
		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,
		#cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {
			color: " . $setting->get_option('ct_secondary_color_admin') . "  ;
		}
		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,
		#cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,
		#cta #cta-top-nav .navbar .nav > .active > a:focus{
			background: unset !important;
		}
	}
	/* Smartphones (landscape) ----------- */
	@media only screen and (max-width: 767px) {
		#cta #cta-top-nav .navbar-header,
		#cta #cta-main-navigation .navbar-header,
		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,
		#cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,
		#cta #cta-top-nav .navbar .nav > .active > a:focus,
		#cta #cta-top-nav .navbar-nav > li > a:hover,
		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,
		#cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {
			color: " . $setting->get_option('ct_secondary_color_admin') . "  ;
		}
		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,
		#cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,
		#cta #cta-top-nav .navbar .nav > .active > a:focus{
			background: unset !important;
		}
		
	}	
	/* Smartphones (portrait and landscape) ----------- */
	@media only screen and (min-width : 320px) and (max-width : 480px) {
		
		#cta #cta-top-nav .navbar-header,
		#cta #cta-main-navigation .navbar-header,
		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,
		#cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,
		#cta #cta-top-nav .navbar .nav > .active > a:focus,
		#cta #cta-top-nav .navbar-nav > li > a:hover,
		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,
		#cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {
			color: " . $setting->get_option('ct_secondary_color_admin') . "  ;
		}
		#cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,
		#cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,
		#cta #cta-top-nav .navbar .nav > .active > a:focus{
			background: unset !important;
		}
	}
	ul{
		width:100%;
	}
	li.logout{
		float:right!important;
	}
	#video{
	color: black!important;
	}
	li.video{
		background: rgb(255, 201, 71);
		background: -moz-linear-gradient( 165deg, rgba(255, 201, 71, 1) 0%, rgba(255, 152, 0, 1) 50% );
		background: -webkit-linear-gradient( 165deg, rgba(255, 201, 71, 1) 0%, rgba(255, 152, 0, 1) 50% );
		background: linear-gradient( 165deg, rgba(255, 201, 71, 1) 0%, rgba(255, 152, 0, 1) 50% );
		float:right!important;
	}
	.modal-xl {
		max-width: 1140px;
}
.modal-dialog2 {
	margin: 30px auto;
}
</style>
";
	?>
</head>

<body>
	<div id="rtl-width-setter-enable" style="display:none;"><?php echo $label_language_values['enable']; ?></div>
	<div id="rtl-width-setter-disable" style="display:none;"><?php echo $label_language_values['disable']; ?></div>
	<div id="rtl-width-setter-on" style="display:none;"><?php echo $label_language_values['o_n']; ?></div>
	<div id="rtl-width-setter-off" style="display:none;"><?php echo $label_language_values['off']; ?></div>
	<div class="ct-wrapper" id="cta">
		<!-- main wrapper -->
		<!-- loader -->
		<?php if ($setting->get_option("ct_loader") == 'css' && $setting->get_option("ct_custom_css_loader") != '') { ?>
			<div class="ct-loading-main" align="center">
				<?php echo $setting->get_option("ct_custom_css_loader"); ?>
			</div>
		<?php   } elseif ($setting->get_option("ct_loader") == 'gif' && $setting->get_option("ct_custom_gif_loader") != '') { ?>
			<div class="ct-loading-main" align="center">
				<img style="margin-top:18%;" src="<?php echo BASE_URL; ?>/assets/images/gif-loader/<?php echo $setting->get_option("ct_custom_gif_loader"); ?>"></img>
			</div>
		<?php   } else { ?>
			<div class="ct-loading-main">
				<div class="loader">Loading...</div>
			</div>
		<?php   } ?>
		<header>
			<div class='row head'>
			</div>
			<div class='row max'>
				<div class='col-md-8'>
					<a id="dnn_dnnLOGO_hypLogo" title="Prenotazione campioni" aria-label="Dizionari Test" href="./"><img class='img-fluid ext' id="dnn_dnnLOGO_imgLogo" src="../assets/images/logo2.png" alt="Prenotazione campioni" style="border-width:0px;">
					</a>
				</div>
				<div class='col-md-4 str'>
				</div>
			</div>
			<div class='bottom row'>
			</div>
		</header>
		<header class="ct-header">
			<?php
			if (isset($_SESSION['ct_adminid'])) {
			?>
				<!-- top bar end here -->
				<!-- recent notifications listing -->
				<div class="ct-overlay-notification"></div>
				<div id="ct-notification-container">
					<div class="ct-notifications-inner">
						<div class="ct-notification-main">
							<div class="ct-notification-main">
								<h4 class="notification-header"><?php echo $label_language_values['booking_notifications']; ?>
									<a id="ct-close-notifications" class="pull-right" href="javascript:void(0);" title="<?php echo $label_language_values['close_notifications']; ?>"><i>×</i></a>
								</h4>
								<div class="ct-recent-booking-container">
									<div class="ct-load-bar">
										<div class="ct-bar"></div>
										<div class="ct-bar"></div>
										<div class="ct-bar"></div>
									</div>
									<ul class="ct-recent-booking-list myloadednotification">
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- end recent notifications -->
			<?php   } ?>

			<?php
			if (isset($_SESSION['ct_adminid'])) {
			?>
				<div id="cta-main-navigation" class="navbar-inner">
					<nav role="navigation" class="navbar navbar-inverse cta-admin-nav">
						<div class="contain">
							<!-- Brand and toggle get grouped for better mobile display -->
							<div class="navbar-header">
								<button type="button" data-target="#navbarCollapseMain" data-toggle="collapse" class="navbar-toggle">
									<span class="sr-only">Toggle navigation</span>
									<i class="fa fa-bars"></i>
								</button>
								<a href="javascript:void(0);" class="navbar-brand">Menu</a>
							</div>
							<!-- Collection of nav links and other content for toggling -->
							<div id="navbarCollapseMain" class="collapse navbar-collapse">

								<ul class="nav navbar-nav cta-nav-tab">
									<li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'services.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'service-manage-calculation-methods.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'service-extra-addons.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'service-manage-unit-price.php') != false) {
																					echo 'active';
																				} ?>"><a href="<?php echo BASE_URL; ?>/admin/services.php"><i class="fa fa-tasks"></i><span><?php echo 'Gestione librerie'; ?></span> </a></li>
									<li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'staff.php') != false) {
																					echo 'active';
																				} ?>"><a class="staff_link_clicked" href="<?php echo BASE_URL; ?>/admin/staff.php"><i class="fa fa-user-circle-o"></i><span> <?php echo $label_language_values['staff']; ?></span></a>
									</li>
									<!--li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'customers.php') != false) {
																								echo 'active';
																							} ?>"><a href="<?php echo BASE_URL; ?>/admin/customers.php"><i class="fa fa-users"></i><span><?php echo $label_language_values['customers']; ?></span></a></li-->
									<li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'crmn.php') != false) {
																					echo 'active';
																				}
																				if (strpos($_SERVER['SCRIPT_NAME'], 'emlsms.php') != false) {
																					echo 'active';
																				} ?>"><a href="<?php echo BASE_URL; ?>/admin/crmn.php"><i class="fa fa-users"></i><span><?php echo  $label_language_values['crm']; ?></span></a></li>


									<!-- 									<li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'settings.php') != false) {
																																			echo 'active';
																																		} ?>"><a href="<?php echo BASE_URL; ?>/admin/settings.php"><i class="fa fa-cog"></i><span><?php echo $label_language_values['settings']; ?></span></a>
   						   </li> -->
									<li class='line'>
										<span id='lab_selected_view'>
											<?php
											echo $select_labs;

											?>
										</span>
									</li>

									<li class='video'><a id="video" href="javascript:void(0)"><i class="fa fa-video-camera"></i><span>VIDEO GUIDA PER L’UTILIZZO</span></a></li>
									<li class='logout'><a id="logout" href="javascript:void(0)"><i class="fa fa-power-off"></i><span>Esci</span></a></li>
								</ul>
							</div>
						</div>
					</nav>
				</div><!-- top bar end here -->

			<?php
			}
			if (isset($_SESSION['ct_accettazioneid'])) {
			?>
				<!--    USER MENUS    -->
				<div id="cta-main-navigation" class="navbar-inner">
					<nav role="navigation" class="navbar navbar-inverse">
						<div class="container_nav">
							<!-- Brand and toggle get grouped for better mobile display -->
							<div class="navbar-header">
								<button type="button" data-target="#navbarCollapseMain" data-toggle="collapse" class="navbar-toggle">
									<span class="sr-only">Toggle navigation</span>
									<i class="fa fa-bars"></i>
								</button>
								<a href="javascript:void(0);" class="navbar-brand">Menu</a>
							</div>
							<!-- Collection of nav links and other content for toggling -->
							<div id="navbarCollapseMain" class="collapse navbar-collapse">
								<ul class="nav navbar-nav cta-nav-tab">
									<li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'calendar.php') != false) {
																					echo 'active';
																				} ?>"><a href="<?php echo BASE_URL; ?>/admin/calendar.php"><i class="fa fa-calendar"></i><span><?php echo $label_language_values['appointments']; ?></span></a>
									</li>
									<li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'services.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'service-manage-calculation-methods.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'service-extra-addons.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'service-manage-unit-price.php') != false) {
																					echo 'active';
																				} ?>"><a href="<?php echo BASE_URL; ?>/admin/services.php"><i class="fa fa-tasks"></i><span><?php echo 'Gestione librerie'; ?></span> </a></li>
									<!--li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'customers.php') != false) {
																								echo 'active';
																							} ?>"><a href="<?php echo BASE_URL; ?>/admin/customers.php"><i class="fa fa-users"></i><span><?php echo $label_language_values['customers']; ?></span></a></li-->
									<li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'crmn.php') != false) {
																					echo 'active';
																				}
																				if (strpos($_SERVER['SCRIPT_NAME'], 'emlsms.php') != false) {
																					echo 'active';
																				} ?>"><a href="<?php echo BASE_URL; ?>/admin/crmn.php"><i class="fa fa-users"></i><span><?php echo  $label_language_values['crm']; ?></span></a></li>


									<!-- 									<li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'settings.php') != false) {
																																			echo 'active';
																																		} ?>"><a href="<?php echo BASE_URL; ?>/admin/settings.php"><i class="fa fa-cog"></i><span><?php echo $label_language_values['settings']; ?></span></a>
   						   </li> -->
									<li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'export.php') != false) {
																					echo 'active';
																				} ?>"><a href="<?php echo BASE_URL; ?>/admin/export.php"><i class="fa fa-file-pdf-o"></i> <span><?php echo 'Esportazioni'; ?></span></a>
									</li>
									<li class='user'><a id="user" href="<?php echo BASE_URL; ?>/admin/richieste.php"><i class="fa fa-user"></i><span>Richieste di accesso</span></a></li>
                                    <li class='user'><a id="user" href="<?php echo BASE_URL; ?>/admin/info_slot.php"><i class="fa fa-flask"></i><span>Informazioni laboratori</span></a></li>
									<li class='line'>
										<span id='lab_selected_view'>
											<?php
											echo 'Accettazione Centralizzata';
																						echo $select_labs;
											?>
										</span>
									</li>

									<li class='video'><a id="video" href="javascript:void(0)"><i class="fa fa-video-camera"></i><span>Video guida</span></a></li>
									<li class='logout'><a id="logout" href="javascript:void(0)"><i class="fa fa-power-off"></i><span>Esci</span></a></li>
								</ul>
							</div>
						</div>
					</nav>
				</div><!-- top bar end here -->
			<?php
			}
			if (isset($_SESSION['ct_laboratorioid'])) {
			?>
				<!--    USER MENUS    -->
				<div id="cta-main-navigation" class="navbar-inner">
					<nav role="navigation" class="navbar navbar-inverse">
						<div class="container_nav">
							<!-- Brand and toggle get grouped for better mobile display -->
							<div class="navbar-header">
								<button type="button" data-target="#navbarCollapseMain" data-toggle="collapse" class="navbar-toggle">
									<span class="sr-only">Toggle navigation</span>
									<i class="fa fa-bars"></i>
								</button>
								<a href="javascript:void(0);" class="navbar-brand">Menu</a>
							</div>
							<!-- Collection of nav links and other content for toggling -->
							<div id="navbarCollapseMain" class="collapse navbar-collapse">
								<ul class="nav navbar-nav cta-nav-tab">
									<li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'calendar.php') != false) {
																					echo 'active';
																				} ?>"><a href="<?php echo BASE_URL; ?>/admin/calendar.php"><i class="fa fa-calendar"></i><span><?php echo $label_language_values['appointments']; ?></span></a>
									</li>
									<li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'services.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'service-manage-calculation-methods.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'service-extra-addons.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'service-manage-unit-price.php') != false) {
																					echo 'active';
																				} ?>"><a href="<?php echo BASE_URL; ?>/admin/services.php"><i class="fa fa-tasks"></i><span><?php echo 'Gestione librerie'; ?></span> </a></li>
									<li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'schedule.php') != false) {
																					echo 'active';
																				} ?>"><a href="<?php echo BASE_URL; ?>/admin/schedule.php"><i class="fa fa-clock-o"></i><span><?php echo $label_language_values['schedule']; ?></span></a>
									</li>
									<!--li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'customers.php') != false) {
																								echo 'active';
																							} ?>"><a href="<?php echo BASE_URL; ?>/admin/customers.php"><i class="fa fa-users"></i><span><?php echo $label_language_values['customers']; ?></span></a></li-->
									<li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'crmn.php') != false) {
																					echo 'active';
																				}
																				if (strpos($_SERVER['SCRIPT_NAME'], 'emlsms.php') != false) {
																					echo 'active';
																				} ?>"><a href="<?php echo BASE_URL; ?>/admin/crmn.php"><i class="fa fa-users"></i><span><?php echo  $label_language_values['crm']; ?></span></a></li>


									<!-- 									<li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'settings.php') != false) {
																																			echo 'active';
																																		} ?>"><a href="<?php echo BASE_URL; ?>/admin/settings.php"><i class="fa fa-cog"></i><span><?php echo $label_language_values['settings']; ?></span></a>
													</li> -->
									<li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'export.php') != false) {
																					echo 'active';
																				} ?>"><a href="<?php echo BASE_URL; ?>/admin/export.php"><i class="fa fa-file-pdf-o"></i> <span><?php echo 'Esportazioni'; ?></span></a>
									</li>
									<li class='line'>
										<span id='lab_selected_view'>
											<?php
																						echo $select_labs;
											?>
										</span>
									</li>

									<li class='video'><a id="video" href="javascript:void(0)"><i class="fa fa-video-camera"></i><span>Video guida</span></a></li>
									<li class='logout'><a id="logout" href="javascript:void(0)"><i class="fa fa-power-off"></i><span>Esci</span></a></li>
								</ul>
							</div>
						</div>
					</nav>
				</div><!-- top bar end here -->
			<?php  	 }	 ?>
			<div id="booking-details-dashboard" class="modal fade booking-details-index-dashboard" tabindex="-1" role="dialog" aria-hidden="true"></div>
			<div id="GC-details-dashboard" class="modal fade GC-details-index-dashboard" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-md">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title"><?php echo $label_language_values['reschedule']; ?></h4>
						</div>
						<div class="modal-body">
							<div class="col-xs-12">
								<div class="form-group">
									<label class="cta-col2 ct-w-50"><?php echo $label_language_values['date_and_time']; ?>:</label>
									<div class="cta-col4 ct-w-50">
										<?php $staff_id = 1;
										$today_date = date("Y-m-d"); ?>
										<input class="exp_cp_date form-control" id="gc_date_check" data-staffid="<?php echo $staff_id; ?>" value="<?php echo $today_date; ?>" data-date-format="yyyy/mm/dd" data-provide="datepicker" />
									</div>
									<div class="cta-col6 ct-w-50 float-right mytime_slots_booking">
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<a href="javascript:void(0);" class="pull-left btn btn-info" id="edit_gc_reschedual" data-gc_event="" data-duration=""><?php echo $label_language_values['update_appointment']; ?></a>
						</div>
					</div>
				</div>
			</div>
		</header>
		<?php
		include(dirname(__FILE__) . "/language_js_objects.php");
		?>

		<script type="text/javascript">
			var ajax_url = '<?php echo AJAX_URL; ?>';
			var base_url = '<?php echo BASE_URL; ?>';
			var times = {
				'time_format_values': "<?php echo $gettimeformat; ?>"
			};
			var language_new = {
				'selected_language': ""
			};
			var titles = {
				'selected_today': "<?php echo $label_language_values['calendar_today']; ?>",
				'selected_month': "<?php echo $label_language_values['calendar_month']; ?>",
				'selected_week': "<?php echo $label_language_values['calendar_week']; ?>",
				'selected_day': "<?php echo $label_language_values['calendar_day']; ?>"
			};
			var site_ur = {
				'site_url': "<?php echo SITE_URL; ?>"
			};
			<?php
			$nacode = explode(',', $setting->get_option("ct_company_country_code"));
			$allowed = $setting->get_option("ct_phone_display_country_code");
			?>
			var ct_calendar_defaultView = '<?php if ($setting->get_option("ct_calendar_defaultView") != '') {
																																			echo $setting->get_option("ct_calendar_defaultView");
																																		} else {
																																			echo 'month';
																																		} ?>';
			var ct_calendar_firstDay = '<?php if ($setting->get_option("ct_calendar_firstDay") != '') {
																																echo $setting->get_option("ct_calendar_firstDay");
																															} else {
																																echo '1';
																															} ?>';
			var countrycodeObj = {
				'numbercode': '<?php echo $nacode[0]; ?>',
				'alphacode': '<?php echo $nacode[1]; ?>',
				'countrytitle': '<?php echo $nacode[2]; ?>',
				'allowed': '<?php echo $allowed; ?>'
			};
			var month = {
				'january': '<?php echo ucfirst(strtolower($label_language_values['january'])); ?>',
				'feb': '<?php echo ucfirst(strtolower($label_language_values['february'])); ?>',
				'mar': '<?php echo ucfirst(strtolower($label_language_values['march'])); ?>',
				'apr': '<?php echo ucfirst(strtolower($label_language_values['april'])); ?>',
				'may': '<?php echo ucfirst(strtolower($label_language_values['may'])); ?>',
				'jun': '<?php echo ucfirst(strtolower($label_language_values['june'])); ?>',
				'jul': '<?php echo ucfirst(strtolower($label_language_values['july'])); ?>',
				'aug': '<?php echo ucfirst(strtolower($label_language_values['august'])); ?>',
				'sep': '<?php echo ucfirst(strtolower($label_language_values['september'])); ?>',
				'oct': '<?php echo ucfirst(strtolower($label_language_values['october'])); ?>',
				'nov': '<?php echo ucfirst(strtolower($label_language_values['november'])); ?>',
				'dec': '<?php echo ucfirst(strtolower($label_language_values['december'])); ?>'
			};
			var days_date = {
				'sun': '<?php echo ucfirst($label_language_values['su']); ?>',
				'mon': '<?php echo ucfirst($label_language_values['mo']); ?>',
				'tue': '<?php echo ucfirst($label_language_values['tu']); ?>',
				'wed': '<?php echo ucfirst($label_language_values['we']); ?>',
				'thu': '<?php echo ucfirst($label_language_values['th']); ?>',
				'fri': '<?php echo ucfirst($label_language_values['fr']); ?>',
				'sat': '<?php echo ucfirst($label_language_values['sa']); ?>'
			};
		</script>
		<!-- all alerts, success messages -->
		<div class="ct-alert-msg-show-main mainheader_message">
			<div class="ct-all-alert-messags alert alert-success mainheader_message_inner">
				<!-- <a href="#" class="close" data-dismiss="alert">&times;</a> -->
				<strong><?php echo $label_language_values['success'] . " "; ?></strong><span id="ct_sucess_message"> </span>
			</div>
		</div>
		<div class="ct-alert-msg-show-main mainheader_message_fail">
			<div class="ct-all-alert-messags alert alert-danger mainheader_message_inner_fail">
				<!-- <a href="#" class="close" data-dismiss="alert">&times;</a> -->
				<strong><?php echo $label_language_values['failed'] . " "; ?></strong> <span id="ct_sucess_message_fail"></span>
			</div>
		</div>
		<div id="ct-remove-sample-data-popup" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content" style="margin-top: 180px;">
					<div class="modal-body">
						<h4><?php echo $label_language_values['remove_sample_data_message']; ?></h4>
					</div>
					<div class="modal-footer">
						<button id="ct-remove-sample-data-ok" class="btn btn-success" data-dismiss="modal"><?php echo $label_language_values['ok_remove_sample_data']; ?></button>
						<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $label_language_values['cancel']; ?></button>
					</div>
				</div>
			</div>
		</div>
		<div id="ct-buy-support-modal" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
						<h4 class="modal-title"><i class="fa fa-ticket"></i> prenotazione_campioni Support (24/7 Support)</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<h5>Contact with our Sales and Support Team for quick chat over skype for any bug, issue or any customization you required.</h5>
								<h5>All Suggestion to improve prenotazione_campioni is welcomed, Help us to server you better!!</h5>
								<center><a type="button" class="btn btn-info" onclick="Skype.tryAnalyzeSkypeUri('chat', '0');" href="skype:techguys123?chat"><i class="fa fa-comments-o" aria-hidden="true"></i> Live Chat</a></center>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<a href="https://codecanyon.net/checkout/from_item/18397969?support=renew_6month" target="_blank" type="button" class="btn btn-link pull-right"><i class="fa fa-money" aria-hidden="true"></i> Buy Support</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="modal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog2 modal-xl" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<div style="padding:52.84% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/715168978?h=836fb7c047&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;" title="Prenotazione campioni - Accesso Active Directory"></iframe></div>
						<script src="https://player.vimeo.com/api/player.js"></script>
					</div>
				</div>
			</div>
		</div>
		<?php
		if ($gc_hook->gc_purchase_status() == 'exist') {
			if ($setting->get_option('ct_gc_status_configure') == 'Y' && $setting->get_option('ct_gc_status') == 'Y') {
		?>
				<input type="hidden" id="extension_js" value="true" />
			<?php
			} else {
			?>
				<input type="hidden" id="extension_js" value="false" />
		<?php
			}
		}
		$english_date_array = array(
			"January", "Jan", "February", "Feb", "March", "Mar", "April", "Apr", "May", "June", "Jun", "July", "Jul", "August", "Aug", "September", "Sep", "October", "Oct", "November", "Nov", "December", "Dec", "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "su", "mo", "tu", "we", "th", "fr", "sa", "AM", "PM"
		);
		$selected_lang_label = array(
			ucfirst(strtolower($label_language_values['january'])),
			ucfirst(strtolower($label_language_values['jan'])),
			ucfirst(strtolower($label_language_values['february'])),
			ucfirst(strtolower($label_language_values['feb'])),
			ucfirst(strtolower($label_language_values['march'])),
			ucfirst(strtolower($label_language_values['mar'])),
			ucfirst(strtolower($label_language_values['april'])),
			ucfirst(strtolower($label_language_values['apr'])),
			ucfirst(strtolower($label_language_values['may'])),
			ucfirst(strtolower($label_language_values['june'])),
			ucfirst(strtolower($label_language_values['jun'])),
			ucfirst(strtolower($label_language_values['july'])),
			ucfirst(strtolower($label_language_values['jul'])),
			ucfirst(strtolower($label_language_values['august'])),
			ucfirst(strtolower($label_language_values['aug'])),
			ucfirst(strtolower($label_language_values['september'])),
			ucfirst(strtolower($label_language_values['sep'])),
			ucfirst(strtolower($label_language_values['october'])),
			ucfirst(strtolower($label_language_values['oct'])),
			ucfirst(strtolower($label_language_values['november'])),
			ucfirst(strtolower($label_language_values['nov'])),
			ucfirst(strtolower($label_language_values['december'])),
			ucfirst(strtolower($label_language_values['dec'])),
			ucfirst(strtolower($label_language_values['sun'])),
			ucfirst(strtolower($label_language_values['mon'])),
			ucfirst(strtolower($label_language_values['tue'])),
			ucfirst(strtolower($label_language_values['wed'])),
			ucfirst(strtolower($label_language_values['thu'])),
			ucfirst(strtolower($label_language_values['fri'])),
			ucfirst(strtolower($label_language_values['sat'])),
			ucfirst(strtolower($label_language_values['su'])),
			ucfirst(strtolower($label_language_values['mo'])),
			ucfirst(strtolower($label_language_values['tu'])),
			ucfirst(strtolower($label_language_values['we'])),
			ucfirst(strtolower($label_language_values['th'])),
			ucfirst(strtolower($label_language_values['fr'])),
			ucfirst(strtolower($label_language_values['sa'])),
			strtoupper($label_language_values['am']),
			strtoupper($label_language_values['pm'])
		);
		?>
		<?php
		function getLabsByUser() {
			$laboratori = [];
			global $conn;
            if (isset($_SESSION['ct_accettazioneid'])) {
                //all labs
                $query = "select descrizione, id from izler_laboratori";
                $result = mysqli_query($conn, $query);
                if (!empty($result) && $result->num_rows > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        $laboratori[] = $row;
                    }
                }
                return $laboratori;

            }
			$query = "select izler_laboratori.descrizione, izler_laboratori.id
													from ct_admin_laboratori
													         join izler_laboratori on ct_admin_laboratori.id_izler_laboratori = izler_laboratori.id
													         join ct_admin_info on ct_admin_laboratori.id_ct_admin = ct_admin_info.id
													where id_ct_admin = " . $_SESSION['ct_laboratorioid'];
			$result = mysqli_query($conn, $query);
			if (!empty($result) && $result->num_rows > 0) {
				while ($row = mysqli_fetch_array($result)) {
					$laboratori[] = $row;
				}
			}
			if (empty($laboratori)) {

				$query = "select descrizione, id
				from izler_laboratori		where id = " . $_SESSION['lab_selected'];
				$result = mysqli_query($conn, $query);
				while ($row = mysqli_fetch_array($result)) {
					$laboratori[] = $row;
				}
			}
			return	$laboratori;
		}
		?>