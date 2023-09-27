<?php   
include_once(dirname(dirname(__FILE__)).'/header.php');
$filename = '../config.php';
$file = file_exists($filename);
if($file){
	if(filesize($filename) > 0){
	}
	else{
		echo 'error_file';//header('Location:'.SITE_URL.'ct_install.php');
	}
}

include(dirname(dirname(__FILE__)).'/objects/class_connection.php');
include(dirname(dirname(__FILE__))."/objects/class_setting.php");
include(dirname(dirname(__FILE__))."/objects/class_version_update.php");
session_start();
$con = new prenotazione_campioni_db();
$conn = $con->connect();
/**
 * 
 * Per poter legare il login tramite active directory al cms, bisogna agganciarsi alle key in sessione che vengono popolate dal login normale, in modo da non dover ricreare tutto il processo da zero
 * 
 */
if(isset($_SESSION['ct_accettazioneid']) || isset($_SESSION['ct_laboratorioid'])){
	header('Location:'.SITE_URL."admin/calendar.php");
}
elseif(isset($_SESSION['ct_adminid'])){
	header('Location:'.SITE_URL."admin/services.php");
}
elseif(isset($_SESSION['ct_login_user_id'])){
    header('Location:'.SITE_URL."admin/user-profile.php");
}
$query = "select * from ct_admin_info";
$info =  $conn->query($query);
if(@mysqli_num_rows($info) == 0){
    header("Location:../");
}
$settings = new prenotazione_campioni_setting();
$settings->conn = $conn;

$lang = $settings->get_option("ct_language");
$label_language_values = array();
$language_label_arr = $settings->get_all_labelsbyid($lang);

if ($language_label_arr[1] != "" || $language_label_arr[3] != "" || $language_label_arr[4] != "" || $language_label_arr[5] != "" || $language_label_arr[6] != "")
{
	$default_language_arr = $settings->get_all_labelsbyid("en");
	if($language_label_arr[1] != ''){
		$label_decode_front = base64_decode($language_label_arr[1]);
	}else{
		$label_decode_front = base64_decode($default_language_arr[1]);
	}
	if($language_label_arr[3] != ''){
		$label_decode_admin = base64_decode($language_label_arr[3]);
	}else{
		$label_decode_admin = base64_decode($default_language_arr[3]);
	}
	if($language_label_arr[4] != ''){
		$label_decode_error = base64_decode($language_label_arr[4]);
	}else{
		$label_decode_error = base64_decode($default_language_arr[4]);
	}
	if($language_label_arr[5] != ''){
		$label_decode_extra = base64_decode($language_label_arr[5]);
	}else{
		$label_decode_extra = base64_decode($default_language_arr[5]);
	}
	if($language_label_arr[6] != ''){
		$label_decode_front_form_errors = base64_decode($language_label_arr[6]);
	}else{
		$label_decode_front_form_errors = base64_decode($default_language_arr[6]);
	}
	
	$label_decode_front_unserial = unserialize($label_decode_front);
	$label_decode_admin_unserial = unserialize($label_decode_admin);
	$label_decode_error_unserial = unserialize($label_decode_error);
	$label_decode_extra_unserial = unserialize($label_decode_extra);
	$label_decode_front_form_errors_unserial = unserialize($label_decode_front_form_errors);
    
	$label_language_arr = array_merge($label_decode_front_unserial,$label_decode_admin_unserial,$label_decode_error_unserial,$label_decode_extra_unserial,$label_decode_front_form_errors_unserial);
	foreach($label_language_arr as $key => $value){
		$label_language_values[$key] = urldecode($value);
	}
}
else
{
    $default_language_arr = $settings->get_all_labelsbyid("en");
    
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
    
	$label_language_arr = array_merge($label_decode_front_unserial,$label_decode_admin_unserial,$label_decode_error_unserial,$label_decode_extra_unserial,$label_decode_front_form_errors_unserial);
	foreach($label_language_arr as $key => $value){
		$label_language_values[$key] = urldecode($value);
	}
}
$loginimage=$settings->get_option('ct_login_image');
if($loginimage!=''){
	$imagepath=SITE_URL."assets/images/backgrounds/".$loginimage;
}else{
	$imagepath=SITE_URL."assets/images/login-bg.jpg";
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $settings->get_option("ct_page_title"); ?> | Login</title>
	<link rel="shortcut icon" type="image/png" href="<?php echo BASE_URL; ?>/assets/images/backgrounds/<?php echo $settings->get_option('ct_favicon_image');?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/assets/css/login-style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/assets/css/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/assets/css/bootstrap/bootstrap-theme.min.css" />
    <!-- **Google - Fonts** -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet">
    <!--    <link rel="stylesheet" type="text/css" href="--><?php /* echo BASE_URL; */ ?><!--/assets/css/font-awesome.css" />-->
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/assets/css/font-awesome/css/font-awesome.css" />
    <script type="text/javascript" src="<?php echo BASE_URL; ?>/assets/js/jquery-2.1.4.min.js"></script>
   <script src="<?php echo BASE_URL; ?>/assets/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="<?php echo BASE_URL; ?>/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo BASE_URL; ?>/assets/js/login.js"></script>
    <script type="text/javascript">
        var ajax_url = '<?php echo AJAX_URL;?>';
        var base_url = '<?php echo BASE_URL;?>';
    </script>
		<style>
	body{
		font-family: 'Open Sans', sans-serif;
		background: url(<?php echo $imagepath;?>) no-repeat;
		background-image: url("<?php echo $imagepath;?>");
		font-weight: 300;
		background-size: 100% 100% !important;
		font-size: 15px;
		color: #333;
		-webkit-font-smoothing: antialiased;	
	}
	</style>
</head>
 <?php  
    include(dirname(__FILE__)."/language_js_objects.php");
   ?>
<body>
<div id="ct-login">
	<section class="main">
        <div class="vertical-alignment-helper">
            <div class="vertical-align-center">
                <div class="ct-main-login visible animated fadeInUp">
                    <div class="form-container">
                        <div class="tab-content">
                            <form id="" name="" method="POST">
                                <h1 class="log-in"><?php echo $label_language_values['log_in'];?></h1>
                                <div class="form-group fl">
                                    <label for="userEmail"><i class="icon-envelope-alt"></i><?php echo $label_language_values['email'];?></label>
                                    <input type="email" id="userEmail" value="<?php echo isset($_COOKIE['prenotazione_campioni_username'])?$_COOKIE['prenotazione_campioni_username'] : "";?>" name="txtname" placeholder="<?php echo $label_language_values['email'];?>" onkeydown="if (event.keyCode == 13) document.getElementById('mybtnlog').click()">
                                </div>
                                <div class="form-group fl">
                                    <label for="userPassword"><i class="icon-lock"></i><?php echo $label_language_values['password'];?></label>
                                    <input type="password" id="userPassword" name="txtpassword" placeholder="<?php echo $label_language_values['password'];?>" value="<?php echo isset($_COOKIE['prenotazione_campioni_password'])?$_COOKIE['prenotazione_campioni_password'] : "";?>"  class="showpassword" onkeydown="if (event.keyCode == 13) document.getElementById('mybtnlog').click()">
                                    <div class="ct-show-pass">
                                        <input id="show-pass" class="ct-checkbox" name="" value="" type="checkbox">
                                        <label for="show-pass"><span class="show-pass-text"></span></label>
                                    </div>
                                </div>
                                <div class="login-error" style="color:red;">
                                    <label><?php echo $label_language_values['sorry_wrong_email_or_password'];?></label>
                                </div>
                                <div class="ct-custom-checkbox">
                                    <ul class="ct-checkbox-list">
                                        <li>
                                            <input type="checkbox" id="remember_me" class="ct-checkbox" name="remember_me" <?php   if(isset($_COOKIE['prenotazione_campioni_remember'])){ echo "checked";}else{ echo "";}?> />
                                            <label for="remember_me"><span></span><?php echo $label_language_values['remember_me'];?></label>
                                        </li>
                                    </ul>
                                </div>
                                <div class="clearfix">
                                    <a id="mybtnlog" class="btn ct-login-btn btn-lg col-xs-12 mybtnloginadmin" href="javascript:void(0)"><?php echo $label_language_values['login'];?></a>
                                </div>
                                <div class="clearfix">
                                    <a class="btn btn-link col-xs-12" id="ct_forget_password" href="javascript:void(0)"><?php echo $label_language_values['forget_password'];?></a>
                                </div>
                            </form>
                        </div>​​
                    </div>​​
                </div>​​<!-- login end here -->
                <!-- forget password -->
                <div class="ct-main-forget-password hide-data visible">
                    <div class="form-container">
                        <div class="tab-content">
                            <form id="forget_pass" name="" method="POST">
                                <h1 class="forget-password"><?php echo $label_language_values['reset_password'];?></h1>
                                <h4><?php echo $label_language_values['enter_your_email_and_we_will_send_you_instructions_on_resetting_your_password'];?></h4>
                                <div class="form-group fl">
                                    <label for="userfpEmail"><i class="icon-envelope-alt"></i><?php echo $label_language_values['registered_email'];?></label>
                                    <input type="email" id="rp_user_email" name="rp_user_email" placeholder="<?php echo $label_language_values['registered_email'];?>">
                                    <label class="forget_pass_correct"></label>
                                    <label class="forget_pass_incorrect"></label>
                                </div>
                                <div class="forgotpassword-error" style="color:red;">
                                    <label><?php echo $label_language_values['sorry_wrong_email_or_password'];?></label>
                                </div>
                                <div class="clearfix">
                                    <a class="btn ct-info-btn btn-lg col-xs-12 mybtnsendemail_forgotpass" id="reset_pass" href="javascript:void(0)"><?php echo $label_language_values['send_mail'];?></a>
                                </div>
                                <div class="clearfix">
                                    <a class="btn btn-link col-xs-12" id="ct_login_user" href="javascript:void(0)"><?php echo $label_language_values['login'];?></a>
                                </div>
                            </form>
                        </div>​​
                    </div>​​
                </div>​​
            </div>
        </div>
    </section>
</div>
</body>
</html>