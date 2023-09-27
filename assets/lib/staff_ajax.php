<?php   
include(dirname(dirname(dirname(__FILE__)))."/objects/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/objects/class_adminprofile.php");
include(dirname(dirname(dirname(__FILE__)))."/objects/class_setting.php");
include(dirname(dirname(dirname(__FILE__)))."/header.php");
include(dirname(dirname(dirname(__FILE__)))."/objects/class_dayweek_avail.php");
include(dirname(dirname(dirname(__FILE__)))."/objects/class_offtimes.php");
include(dirname(dirname(dirname(__FILE__)))."/objects/class_offbreaks.php");
include(dirname(dirname(dirname(__FILE__)))."/objects/class_off_days.php");
include(dirname(dirname(dirname(__FILE__)))."/objects/class_booking.php");
include(dirname(dirname(dirname(__FILE__))).'/objects/class.phpmailer.php');
include(dirname(dirname(dirname(__FILE__)))."/objects/class_dashboard.php");
include(dirname(dirname(dirname(__FILE__))).'/objects/class_general.php');
include(dirname(dirname(dirname(__FILE__))).'/objects/class_email_template.php');
include(dirname(dirname(dirname(__FILE__)))."/objects/class_staff_commision.php");
include(dirname(dirname(dirname(__FILE__)))."/objects/class_payments.php");
include(dirname(dirname(dirname(__FILE__)))."/objects/class_rating_review.php");
include(dirname(dirname(dirname(__FILE__))).'/objects/plivo.php');
include(dirname(dirname(dirname(__FILE__))).'/assets/twilio/Services/Twilio.php');
include(dirname(dirname(dirname(__FILE__)))."/objects/class_nexmo.php");

$con = new prenotazione_campioni_db();
$conn = $con->connect();

$nexmo_admin = new prenotazione_campioni_ct_nexmo();
$nexmo_client = new prenotazione_campioni_ct_nexmo();

$general=new prenotazione_campioni_general();
$general->conn=$conn;

$settings = new prenotazione_campioni_setting();
$settings->conn = $conn;

$bookings = new prenotazione_campioni_booking();
$bookings->conn = $conn;

/* ADDED START*/
$objdashboard = new prenotazione_campioni_dashboard();
$objdashboard->conn = $conn;

$general=new prenotazione_campioni_general();
$general->conn=$conn;

$staff_commision = new prenotazione_campioni_staff_commision();
$staff_commision->conn=$conn;

$objadminprofile = new prenotazione_campioni_adminprofile();
$objadminprofile->conn = $conn;

$emailtemplate=new prenotazione_campioni_email_template();
$emailtemplate->conn=$conn; 
/* ADDED END*/

$objadmin = new prenotazione_campioni_adminprofile();
$objadmin->conn=$conn;

$objdayweek_avail = new prenotazione_campioni_dayweek_avail();
$objdayweek_avail->conn = $conn;

$obj_offtime = new prenotazione_campioni_offtimes();
$obj_offtime->conn = $conn;

$objoffbreaks = new prenotazione_campioni_offbreaks();
$objoffbreaks->conn = $conn;

$offday=new prenotazione_campioni_provider_off_day();
$offday->conn = $conn;

$objpayment = new prenotazione_campioni_payments();
$objpayment->conn = $conn;

$objrating_review = new prenotazione_campioni_rating_review();
$objrating_review->conn = $conn;

$time_int = '60';
$time_interval = '60';

$getdateformat=$settings->get_option('ct_date_picker_date_format');
$time_format = $settings->get_option('ct_time_format');
$timess = "";
if($time_format == "24"){
	$timess = "H:i";
}
else {
	$timess = "h:i A";
}
/* ADDED START */
$symbol_position=$settings->get_option('ct_currency_symbol_position');
$decimal=$settings->get_option('ct_price_format_decimal_places');
$getcurrency_symbol_position=$settings->get_option('ct_currency_symbol_position');
$getdateformate = $settings->get_option('ct_date_picker_date_format');

$gettimeformat=$settings->get_option('ct_time_format');

$get_admin_name_result = $objadminprofile->readone_adminname();
$get_admin_name = $get_admin_name_result[3];
if($get_admin_name == ""){
	$get_admin_name = "Admin";
}
$admin_email = $settings->get_option('ct_admin_optional_email');
if($settings->get_option('ct_company_logo') != null && $settings->get_option('ct_company_logo') != ""){
	$business_logo= SITE_URL.'assets/images/services/'.$settings->get_option('ct_company_logo');
	$business_logo_alt= $settings->get_option('ct_company_name');
}else{
	$business_logo= '';
	$business_logo_alt= $settings->get_option('ct_company_name');
}
$company_city = $settings->get_option('ct_company_city');
$company_state = $settings->get_option('ct_company_state');
$company_zip = $settings->get_option('ct_company_zip_code');
$company_country = $settings->get_option('ct_company_country');
$company_phone = strlen($settings->get_option('ct_company_phone')) < 6 ? "" : $settings->get_option('ct_company_phone');
$company_email = $settings->get_option('ct_company_email');$company_address = $settings->get_option('ct_company_address'); 
/************ END ************/


if($settings->get_option('ct_smtp_authetication') == 'true'){
	$mail_SMTPAuth = '1';
	if($settings->get_option('ct_smtp_hostname') == "smtp.gmail.com"){
		$mail_SMTPAuth = 'Yes';
	}
	
}else{
	$mail_SMTPAuth = '0';
	if($settings->get_option('ct_smtp_hostname') == "smtp.gmail.com"){
		$mail_SMTPAuth = 'No';
	}
}

$mail = new prenotazione_campioni_phpmailer();
$mail->Host = $settings->get_option('ct_smtp_hostname');
$mail->Username = $settings->get_option('ct_smtp_username');
$mail->Password = $settings->get_option('ct_smtp_password');
$mail->Port = $settings->get_option('ct_smtp_port');
$mail->SMTPSecure = $settings->get_option('ct_smtp_encryption');
$mail->SMTPAuth = $mail_SMTPAuth;
$mail->CharSet = "UTF-8";


/*NEXMO SMS GATEWAY VARIABLES*/

$nexmo_admin->ct_nexmo_api_key = $settings->get_option('ct_nexmo_api_key');
$nexmo_admin->ct_nexmo_api_secret = $settings->get_option('ct_nexmo_api_secret');
$nexmo_admin->ct_nexmo_from = $settings->get_option('ct_nexmo_from');

$nexmo_client->ct_nexmo_api_key = $settings->get_option('ct_nexmo_api_key');
$nexmo_client->ct_nexmo_api_secret = $settings->get_option('ct_nexmo_api_secret');
$nexmo_client->ct_nexmo_from = $settings->get_option('ct_nexmo_from');

/*SMS GATEWAY VARIABLES*/
$plivo_sender_number = $settings->get_option('ct_sms_plivo_sender_number');
$twilio_sender_number = $settings->get_option('ct_sms_twilio_sender_number');

/* textlocal gateway variables */
$textlocal_username =$settings->get_option('ct_sms_textlocal_account_username');
$textlocal_hash_id = $settings->get_option('ct_sms_textlocal_account_hash_id');


$lang = $settings->get_option("ct_language");
$label_language_values = array();
$language_label_arr = $settings->get_all_labelsbyid($lang);

if ($language_label_arr[1] != "" || $language_label_arr[3] != "" || $language_label_arr[4] != "" || $language_label_arr[5] != "")
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
	
	$label_decode_front_unserial = unserialize($label_decode_front);
	$label_decode_admin_unserial = unserialize($label_decode_admin);
	$label_decode_error_unserial = unserialize($label_decode_error);
	$label_decode_extra_unserial = unserialize($label_decode_extra);
    
	$label_language_arr = array_merge($label_decode_front_unserial,$label_decode_admin_unserial,$label_decode_error_unserial,$label_decode_extra_unserial);
	
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
		
	
	$label_decode_front_unserial = unserialize($label_decode_front);
	$label_decode_admin_unserial = unserialize($label_decode_admin);
	$label_decode_error_unserial = unserialize($label_decode_error);
	$label_decode_extra_unserial = unserialize($label_decode_extra);
    
	$label_language_arr = array_merge($label_decode_front_unserial,$label_decode_admin_unserial,$label_decode_error_unserial,$label_decode_extra_unserial);
	
	foreach($label_language_arr as $key => $value){
		$label_language_values[$key] = urldecode($value);
	}
}

include(dirname(dirname(dirname(__FILE__))).'/assets/lib/date_translate_array.php');

if(isset($_POST['staff_email'])){
	$objadmin->email = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['staff_email'])));
	$check_staff_email_existing = $objadmin->check_staff_email_existing();
	if($check_staff_email_existing > 0){
		echo 'false';
	}else{
		echo "true";
	}
}
if(isset($_POST['fullemail'])){
	if($_SESSION['ct_useremail'] != trim(strip_tags(mysqli_real_escape_string($conn, $_POST['fullemail'])))){
		$objadmin->email = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['fullemail'])));
		$check_staff_email_existing = $objadmin->check_staff_email_existing();
		if($check_staff_email_existing > 0){
			echo 'false';
		}else{
			echo "true";
		}
	}else{
		echo "true";
	}
}
if(isset($_POST['u_member_email'])){
	$objadmin->email = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['u_member_email'])));
	$check_staff_email_existing = $objadmin->check_staff_email_existing();
	if($check_staff_email_existing > 0){
		echo 'false';
	}else{
		echo "true";
	}
}
elseif(isset($_POST['staff_add'])){
	$objadmin->email = $_POST['email'];
	$objadmin->fullname = ucwords($_POST['name']);
	$objadmin->pass = $_POST['pass'];
	$objadmin->role = $_POST['staff_role'];
	$objadmin->add_staff();
}
elseif(isset($_POST['staff_update'])){
	$objadmin->id = $_POST['id'];
	$objadmin->fullname = $_POST['name'];
	$objadmin->email = $_POST['email'];
	$objadmin->description = $_POST['desc'];
	$objadmin->phone = $_POST['phone'];
	$objadmin->address = $_POST['address'];
	$objadmin->enable_booking = $_POST['staff_booking'];
	$objadmin->city = $_POST['city'];
	$objadmin->state = $_POST['state'];
	$objadmin->zip = $_POST['zip'];
	$objadmin->country = $_POST['country'];
	$objadmin->image = $_POST['staff_image'];
	$new_service = implode(",",$_POST['ct_service_staff']);
	
	$objadmin->ct_service_staff = $new_service;
	$objadmin->update_staff_details();
	
	if($_POST['staff_schedule'] != $_POST['old_schedule']){
		/* $objdayweek_avail->set_schedule_type_staff($_POST['id']); */
	}
	
}

elseif(isset($_POST['staff_detail']))
{
	$objadmin->id = $_POST['staff_id'];
	$staff_id = $_POST['staff_id'];
	$staff_read = $objadmin->readone();
	?>
	
	<link rel="stylesheet" href="<?php  echo BASE_URL; ?>/assets/css/star_rating.min.css" type="text/css" media="all">
	<script src="<?php  echo BASE_URL; ?>/assets/js/star_rating_min.js" type="text/javascript"></script>
	<style>
	.rating-md{
		font-size: 2em !important ;
	}
	</style>
	<script>
	jQuery(function () {
		jQuery('.selectpicker').selectpicker({
			container: 'body'
		});

		if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
			jQuery('.selectpicker').selectpicker('mobile');
		}
		
		jQuery("#ratings_staff_display").rating('refresh', {disabled: true, showClear: false});
	});
	</script>	
	<div class="ct-staff-details tab-content col-md-9 col-sm-8 col-lg-9 col-xs-12">
		<!-- right side common menu for staff -->
		<div class="ct-staff-top-header">
			<span class="ct-staff-member-name pull-left"><?php echo $staff_read['fullname'];?></span>
			
			<button id="ct-delete-staff-member" class="pull-right btn btn-circle btn-danger" rel="popover" data-placement='left' title="<?php echo $label_language_values['delete_member'];?>"> <i class="fa fa-trash"></i></button>
			
			
			<div id="popover-delete-member" style="display: none;">
				<div class="arrow"></div>
				<table class="form-horizontal" cellspacing="0">
					<tbody>
						<tr>
							<td>
								<button id="" data-id="<?php echo $staff_id; ?>" value="Delete" class="staff_delete btn btn-danger" type="submit"><?php echo $label_language_values['yes'];?></button>
								<button id="ct-close-popover-delete-staff" class="btn btn-default" href="javascript:void(0)"><?php echo $label_language_values['cancel'];?></button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
					
		</div>
		<hr id="hr" />
        <ul class="nav nav-tabs nav-justified ct-staff-right-menu">
            <li class="active"><a href="#member-details" data-toggle="tab"><?php  echo $label_language_values['staff_details'];?></a></li>
        </ul>
        <div class="tab-pane active"> <!-- first staff nmember -->
            <div class="container-fluid tab-content ct-staff-right-details pall-15">
													<?php
													if(empty($staff_read['role'])){
														echo "<div class='alert alert-danger'>Attenzione, l'utente è privo di permessi.<br>Assegna un ruolo.</div>";
													}
													?>

                <div class="tab-pane col-lg-12 col-md-12 col-sm-12 col-xs-12 active" id="member-details">
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
						<div class="ct-clean-service-image-uploader">
						<?php  
						if($staff_read['image']==''){
							$imagepath=SITE_URL."assets/images/user.png";
						}else{
							$imagepath=SITE_URL."assets/images/services/".$staff_read['image'];
						}
						?>
						<img data-imagename="" id="pppp<?php   echo $staff_read['id']; ?>staffimage" src="<?php echo $imagepath;?>" class="ct-clean-staff-image br-100" height="100" width="100">
						<input data-us="pppp<?php   echo $staff_read['id']; ?>" class="hide ct-upload-images" type="file" name="" id="ct-upload-imagepppp<?php   echo $staff_read['id'];?>" data-id="<?php echo $staff_read['id'];?>" />
						<?php  
						if($staff_read['image']==''){
							?>
							<label for="ct-upload-imagepppp<?php   echo $staff_read['id']; ?>" class="ct-clean-staff-img-icon-label old_cam_ser<?php   echo $staff_read['id']; ?>">
								<i class="ct-camera-icon-common br-100 fa fa-camera" id="pcls<?php   echo $staff_read['id']; ?>camera"></i>
								<i class="pull-left fa fa-plus-circle fa-2x" id="ctsc<?php   echo $staff_read['id']; ?>plus"></i>
							</label>
						<?php  
						}
						?>
						
						<label for="ct-upload-imagepppp<?php   echo $staff_read['id']; ?>" class="ct-clean-staff-img-icon-label new_cam_ser ser_cam_btn<?php   echo $staff_read['id']; ?>" id="ct-upload-imagepppp<?php   echo $staff_read['id']; ?>" style="display:none;">
							<i class="ct-camera-icon-common br-100 fa fa-camera" id="pppp<?php   echo $staff_read['id']; ?>camera"></i>
							<i class="pull-left fa fa-plus-circle fa-2x" id="ctsc<?php   echo $staff_read['id']; ?>plus"></i>
						</label>
						<?php  
						if($staff_read['image']!==''){
							?>
							<a id="ct-remove-staff-imagepppp<?php   echo $staff_read['id'];?>" data-pclsid="<?php echo $staff_read['id'];?>" data-staff_id="<?php echo $staff_read['id'];?>" class="delete_staff_image pull-left br-100 btn-danger bt-remove-staff-img btn-xs ser_new_del<?php   echo $staff_read['id'];?>" rel="popover" data-placement='left' title="<?php echo $label_language_values['remove_image'];?>"> <i class="fa fa-trash" title="<?php echo $label_language_values['remove_service_image'];?>"></i></a>
						<?php  
						}
						?>
					   <label><b class="error-service error_image" style="color:red;"></b></label>
						<div id="popover-ct-remove-staff-imagepppp<?php   echo $staff_read['id'];?>" style="display: none;">
							<div class="arrow"></div>
							<table class="form-horizontal" cellspacing="0">
								<tbody>
								<tr>
									<td>
										<a href="javascript:void(0)" id="staff_del_images" value="Delete" data-staff_id="<?php echo $staff_read['id'];?>" class="btn btn-danger btn-sm" type="submit"><?php echo $label_language_values['yes'];?></a>
										<a href="javascript:void(0)" id="ct-close-popover-staff-image" class="btn btn-default btn-sm" href="javascript:void(0)"><?php echo $label_language_values['cancel'];?></a>
									</td>
								</tr>
								</tbody>
							</table>
						</div><!-- end pop up -->
					</div>
					<div id="ct-image-upload-popuppppp<?php   echo $staff_read['id'];?>" class="ct-image-upload-popup modal fade" tabindex="-1" role="dialog">
						<div class="vertical-alignment-helper">
							<div class="modal-dialog modal-md vertical-align-center">
								<div class="modal-content">
									<div class="modal-header">
										<div class="col-md-12 col-xs-12">
											<a data-staff_id="<?php echo $staff_read['id'];?>" data-us="pppp<?php   echo $staff_read['id'];?>" class="btn btn-success ct_upload_img_staff" data-imageinputid="ct-upload-imagepppp<?php   echo $staff_read['id'];?>" data-id="<?php echo $staff_read['id']; ?>"><?php echo $label_language_values['crop_and_save'];?></a>
											<button type="button" class="btn btn-default hidemodal" data-dismiss="modal" aria-hidden="true"><?php echo $label_language_values['cancel'];?></button>
										</div>
									</div>
									<div class="modal-body">
										<img id="ct-preview-imgpppp<?php   echo $staff_read['id'];?>" style="width: 100%;"  />
									</div>
									<div class="modal-footer">
										<div class="col-md-12 np">
											<div class="col-md-12 np">
												<div class="col-md-4 col-xs-12">
													<label class="pull-left"><?php echo $label_language_values['file_size'];?></label> <input type="text" class="form-control" id="ppppfilesize<?php   echo $staff_read['id'];?>" name="filesize" />
												</div>
												<div class="col-md-4 col-xs-12">
													<label class="pull-left">H</label> <input type="text" class="form-control" id="pppp<?php   echo $staff_read['id'];?>h" name="h" />
												</div>
												<div class="col-md-4 col-xs-12">
													<label class="pull-left">W</label> <input type="text" class="form-control" id="pppp<?php   echo $staff_read['id'];?>w" name="w" />
												</div>
												<!-- hidden crop params -->
												<input type="hidden" id="pppp<?php   echo $staff_read['id'];?>x1" name="x1" />
												<input type="hidden" id="pppp<?php   echo $staff_read['id'];?>y1" name="y1" />
												<input type="hidden" id="pppp<?php   echo $staff_read['id'];?>x2" name="x2" />
												<input type="hidden" id="pppp<?php   echo $staff_read['id'];?>y2" name="y2" />
												<input type="hidden" id="pppp<?php   echo $staff_read['id'];?>id" name="id" value="<?php echo $staff_read['id'];?>" />
												<input id="ppppctimage<?php   echo $staff_read['id'];?>" type="hidden" name="ctimage" />
												<input type="hidden" id="recordid" value="<?php echo $staff_read['id'];?>">
												<input type="hidden" id="pppp<?php   echo $staff_read['id'];?>ctimagename" class="ppppimg" name="ctimagename" value="<?php echo $staff_read['image'];?>" />
												<input type="hidden" id="pppp<?php   echo $staff_read['id'];?>newname" value="staff_" />
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
						
						</div>
				
          <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 mt-10">
              <form id="staff_update_details">
								<div class="ct-staff-common-table">         
									
									<div class="form-group col-xs-12 col-md-12">
										<div class="col-xs-4 col-md-2"><label for="ct-member-name"><?php echo $label_language_values['name'];?></label></div>
										<div class="col-xs-8 col-md-10"><input type="text" class="form-control" id="ct-member-name" value="<?php echo $staff_read['fullname'];?>" name="u_member_name" /></div>
									</div>

									<div class="form-group col-xs-12 col-md-12">
										<div class="col-xs-4 col-md-2"><label for="ct-member-name"><?php echo $label_language_values['email'];?></label></div>
										<div class="col-xs-8 col-md-10"><input type="text" class="form-control" id="ct-member-email" value="<?php echo $staff_read['email'];?>" name="u_member_email" /></div>
									</div>

									<div class="form-group col-xs-12 col-md-12">
										<div class="col-xs-4 col-md-2"><label for="ct-member-role"><?php echo 'Ruolo';?></label></div>
										<div class="col-xs-8 col-md-10">
											<select name="role" class="form-control">
												<option <?=$staff_read['role'] == '' 												? ' selected="selected"' : '';?> value=''>												Ruolo assente</option>
												<option <?=$staff_read['role'] == 'admin' 							? ' selected="selected"' : '';?> value='admin'>							Amministratore</option>
												<option <?=$staff_read['role'] == 'accettazione' ? ' selected="selected"' : '';?> value='accettazione'>Utente accettazione</option>
												<option <?=$staff_read['role'] == 'laboratorio'		? ' selected="selected"' : '';?> value='laboratorio'>	Utente laboratorio</option>
											</select>
										</div>
									</div>
									
									<div class="form-group col-xs-12 col-md-12">
										<div class="col-xs-4 col-md-2"><label for="ct-member-desc"><?php echo $label_language_values['description'];?></label></div>
										<div class="col-xs-8 col-md-10"><textarea class="form-control" id="ct-member-desc" name="ct-member-desc" ><?php echo $staff_read['description'];?></textarea></div>
									</div>

									<div class="form-group col-xs-12 col-md-12">
										<div class="col-xs-4 col-md-2"><label for="phone-number"><?php echo $label_language_values['phone'];?> </label></div>
										<div class="col-xs-8 col-md-10"><input type="tel" class="form-control" id="phone-number" name="phone-number" value="<?php echo $staff_read['phone'];?>" /></div>
									</div>
									
									<div class="form-group col-xs-12 col-md-12">
										<div class="col-xs-4 col-md-2"><label for="address"><?php echo $label_language_values['address'];?></label></div>
										<div class="col-xs-8 col-md-10"><input type="text" class="form-control" name="ct-member-address" id="ct-member-address" placeholder="Member Street Address" value="<?php echo $staff_read['address']; ?>" />
										</div>
									</div>

										<div class="col-xs-12">	
											<div class="col-xs-12 col-md-6 form-group npl npr">
												<div class="col-xs-4"><label for="city"><?php echo $label_language_values['city'];?></label></div>
												<div class="col-xs-8">
													<input class="form-control value_city" id="ct-member-city" name="ct-member-city" value="<?php echo $staff_read['city'];?>" type="text">
												</div>
											</div>

											<div class="col-xs-12 col-md-6 form-group npl npr">
												<div class="col-xs-4"><label for="state"><?php echo $label_language_values['state'];?></label></div>
												<div class="col-xs-8">
													<input class="form-control value_state" id="ct-member-state" name="ct-member-state" type="text" value="<?php echo $staff_read['state'];?>">
												</div>
											</div>
										</div>

										<div class="col-xs-12">
											<div class="col-xs-12 col-md-6 form-group npl npr">
												<div class="col-xs-4"><label for="zip"><?php echo $label_language_values['zip'];?></label></div>
												<div class="col-xs-8">
													<input class="form-control value_zip" id="ct-member-zip" name="ct-member-zip" type="text" value="<?php echo $staff_read['zip'];?>">
												</div>
											</div>

											<div class="col-xs-12 col-md-6 form-group npl npr">
												<div class="col-xs-4"><label for="country"><?php echo $label_language_values['country'];?></label></div>
												<div class="col-xs-8">
													<input class="form-control value_country" id="ct-member-country" name="ct-member-countrys" type="text" value="<?php echo $staff_read['country'];?>">
												</div>
											</div>
										</div>

									</div>



								  <div class="col-xs-12">
										<div class="col-xs-4 col-md-2"></div>
										<div class="col-xs-8 col-md-10">
										<a href="javascript:void(0);" id="update_staff_details" data-old_schedule_type="<?php echo $staff_read['schedule_type'];?>" class="btn btn-success ct-btn-width mt-20" data-id="<?php echo $staff_read['id'];?>" type="submit"><?php echo $label_language_values['save'];?></a>
										</div>
                  </div>         
								
							</form>
            </div>
                </div>
            </div>
            <!-- end first -->
        </div>
    </div>
	<?php   
}
elseif(isset($_POST['assign_staff_booking'])){
	$staff_id = $_POST['staff_ids'];
	$id = $_POST['order_id'];
	$final_staff = implode(",",$staff_id);
	$bookings->order_id = $_POST['order_id'];
	$bookings->save_staff_to_booking($final_staff);
	if(sizeof((array)$staff_id) > 0){
		/****************************************** EMAIL CODE START ************************************************/
		$orderdetail = $objdashboard->getclientorder($id);
		$clientdetail = $objdashboard->clientemailsender($id);
		
		$admin_company_name = $settings->get_option('ct_company_name');
		$setting_date_format = $settings->get_option('ct_date_picker_date_format');
		$setting_time_format = $settings->get_option('ct_time_format');
		$booking_date = date($setting_date_format, strtotime($clientdetail['booking_date_time']));
		if($setting_time_format == 12){
			$booking_time = str_replace($english_date_array,$selected_lang_label,date("h:i A",strtotime($clientdetail['booking_date_time'])));
		}
		else{
			$booking_time = date("H:i",strtotime($clientdetail['booking_date_time']));
		}
		$company_name = $settings->get_option('ct_email_sender_name');
		$company_email = $settings->get_option('ct_email_sender_address');
		$service_name = $clientdetail['title'];
		if($admin_email == ""){
			$admin_email = $clientdetail['email'];	
		}
		
		$price=$general->ct_price_format($orderdetail[2],$symbol_position,$decimal);

		/* methods */
		$units = $label_language_values['none'];
		$methodname=$label_language_values['none'];
		$hh = $bookings->get_methods_ofbookings($orderdetail[4]);
		$count_methods = mysqli_num_rows($hh);
		$hh1 = $bookings->get_methods_ofbookings($orderdetail[4]);

		if($count_methods > 0){
			while($jj = mysqli_fetch_array($hh1)){
				if($units == $label_language_values['none']){
					$units = $jj['units_title']."-".$jj['qtys'];
				}
				else
				{
					$units = $units.",".$jj['units_title']."-".$jj['qtys'];
				}
				$methodname = $jj['method_title'];
			}
		}

		/* Add ons */
		$addons =  $label_language_values['none'];
		$hh = $bookings->get_addons_ofbookings($orderdetail[4]);
		while($jj = mysqli_fetch_array($hh)){
			if($addons ==  $label_language_values['none']){
				$addons = $jj['addon_service_name']."-".$jj['addons_service_qty'];
			}
			else
			{
				$addons = $addons.",".$jj['addon_service_name']."-".$jj['addons_service_qty'];
			}
		}


		/* if this is guest user than */
		if($orderdetail[4]==0)
		{
			$gc  = $objdashboard->getguestclient($orderdetail[4]);
			$temppp= unserialize(base64_decode($gc[5]));
			$temp = str_replace('\\','',$temppp);
			$vc_status = $temp['vc_status'];
			if($vc_status == 'N'){
				$final_vc_status =  $label_language_values['no'];
			}
			elseif($vc_status == 'Y'){
				$final_vc_status =  $label_language_values['yes'];
			}else{
				$final_vc_status = "N/A";
			}
			$p_status = $temp['p_status'];
			if($p_status == 'N'){
				$final_p_status =  $label_language_values['no'];
			}
			elseif($p_status == 'Y'){
				$final_p_status =  $label_language_values['yes'];
			}else{
				$final_p_status = "N/A";
			}

			$client_name=$gc[2];
			$client_email=$gc[3];
			$client_phone=$gc[4];
			$firstname=$client_name;
			$lastname='';
			$booking_status=$orderdetail[6];
			$final_vc_status;
			$final_p_status;
			$payment_status=$orderdetail[5];
			$client_address=$temp['address'];
			$client_notes=$temp['notes'];
			$client_status=$temp['contact_status'];				
			$client_city = $temp['city'];		$client_state = $temp['state'];		$client_zip	= $temp['zip'];
		}
		else
			/*Registered user */
		{
			$c  = $objdashboard->getguestclient($orderdetail[4]);
			$temppp= unserialize(base64_decode($c[5]));
			$temp = str_replace('\\','',$temppp);
			$vc_status = $temp['vc_status'];
			if($vc_status == 'N'){
				$final_vc_status =  $label_language_values['no'];
			}
			elseif($vc_status == 'Y'){
				$final_vc_status =  $label_language_values['yes'];
			}else{
				$final_vc_status = "N/A";
			}
			$p_status = $temp['p_status'];
			if($p_status == 'N'){
				$final_p_status =  $label_language_values['no'];
			}
			elseif($p_status == 'Y'){
				$final_p_status =  $label_language_values['yes'];
			}else{
				$final_p_status = "N/A";
			}
			$client_name=$c[2];
			$client_email=$c[3];
			$client_phone=$c[4];
			$firstname=$client_name;
			$lastname='';
			$payment_status=$orderdetail[5];
			$final_vc_status;
			$final_p_status;
			$client_address=$temp['address'];
			$client_notes=$temp['notes'];
			$client_status=$temp['contact_status'];
			$client_city = $temp['city'];
			$client_state = $temp['state'];	
			$client_zip	= $temp['zip'];
		}
		foreach($staff_id as $sid){
			$staffdetails = $bookings->get_staff_detail_for_email($sid);
			$staff_name = $staffdetails['fullname'];
			$staff_email = $staffdetails['email'];
			$staff_phone = $staffdetails['phone'];
			
			$bookings->staff_id=$sid;
			$bookings->order_id=$id;
			$status_insert_id = $bookings->staff_status_insert();
			
			$searcharray = array('{{service_name}}','{{booking_date}}','{{business_logo}}','{{business_logo_alt}}','{{client_name}}','{{methodname}}','{{units}}','{{addons}}','{{client_email}}','{{phone}}','{{payment_method}}','{{vaccum_cleaner_status}}','{{parking_status}}','{{notes}}','{{contact_status}}','{{address}}','{{price}}','{{admin_name}}','{{firstname}}','{{lastname}}','{{app_remain_time}}','{{reject_status}}','{{company_name}}','{{booking_time}}','{{client_city}}','{{client_state}}','{{client_zip}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_address}}','{{admin_name}}','{{staff_name}}','{{staff_email}}');
				
			$replacearray = array($service_name, $booking_date , $business_logo, $business_logo_alt, $client_name,$methodname, $units, $addons,$client_email, $client_phone, $payment_status, $final_vc_status, $final_p_status, $client_notes, $client_status,$client_address,$price,$get_admin_name,$firstname,$lastname,'','',$admin_company_name,$booking_time,$client_city,$client_state,$client_zip,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_address,$get_admin_name,$staff_name,$staff_email);
			
			
			$emailtemplate->email_subject="New Appointment Assigned";
			$emailtemplate->user_type="S";
			$staffemailtemplate=$emailtemplate->readone_client_email_template_body();
			
			if($staffemailtemplate[2] != ''){
				$stafftemplate = base64_decode($staffemailtemplate[2]);
			}else{
				$stafftemplate = base64_decode($staffemailtemplate[3]);
			}
			$subject=$staffemailtemplate[1];
		   
		    $new_div="<div style='width: 39%;float: left;margin-left: 270px;background-color: #cb2121;color: #fff;text-align: center;'>
						<label style='font-size: 15px;color: #999999;padding-right: 5px;min-width: 95px;white-space: nowrap;float: left;line-height: 25px;'> </label>
						<span style='font-size: 15px;font-weight: 400;color: #fff;line-height: 25px;float: left;width: 76%;word-wrap: break-word;max-height: 70px;overflow: auto;'>Appointment :<strong><a  style='color: #fff' href='".AJAX_URL."accept_appointment_staff.php?id=".$status_insert_id."&status=A' target='_blank'   id='accept_appointment' >Accept</a></strong> Or <strong><a style='color: #fff' href= '".AJAX_URL."accept_appointment_staff.php?id=".$status_insert_id."&status=D' id='decline_appointment' >Decline</a></strong></span></div>";
						
			if($settings->get_option('ct_staff_email_notification_status') == 'Y' && $staffemailtemplate[4]=='E' ){
				$client_email_body = str_replace($searcharray,$replacearray,$stafftemplate).$new_div;
				if($settings->get_option('ct_smtp_hostname') != '' && $settings->get_option('ct_email_sender_name') != '' && $settings->get_option('ct_email_sender_address') != '' && $settings->get_option('ct_smtp_username') != '' && $settings->get_option('ct_smtp_password') != '' && $settings->get_option('ct_smtp_port') != ''){
					$mail->IsSMTP();
				}else{
					$mail->IsMail();
				}
				$mail->SMTPDebug  = 0;
				$mail->IsHTML(true);
				$mail->From = $company_email;
				$mail->FromName = $company_name;
				$mail->Sender = $company_email;
				$mail->AddAddress($staff_email, $staff_name);
				$mail->Subject = $subject;
				$mail->Body = $client_email_body;
				$mail->send();
				$mail->ClearAllRecipients();
			}
			
			/* TEXTLOCAL CODE */
			if($settings->get_option('ct_sms_textlocal_status') == "Y")
			{
				if($settings->get_option('ct_sms_textlocal_send_sms_to_staff_status') == "Y"){
					if(isset($staff_phone) && !empty($staff_phone))
					{	
						$template = $objdashboard->gettemplate_sms("RS",'S');
						$phone = $staff_phone;				
						if($template[4] == "E") {
							if($template[2] == ""){
								$message = base64_decode($template[3]);
							}
							else{
								$message = base64_decode($template[2]);
							}
						}
						$message = str_replace($searcharray,$replacearray,$message);
						$data = "username=".$textlocal_username."&hash=".$textlocal_hash_id."&message=".$message."&numbers=".$phone."&test=0";
						$ch = curl_init('http://api.textlocal.in/send/?');
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						$result = curl_exec($ch);
						curl_close($ch);
					}	
				}
			}
			/*PLIVO CODE*/
			if($settings->get_option('ct_sms_plivo_status')=="Y"){							
				if($settings->get_option('ct_sms_plivo_send_sms_to_staff_status') == "Y"){
					if(isset($staff_phone) && !empty($staff_phone))
					{ 
						$auth_id = $settings->get_option('ct_sms_plivo_account_SID');
						$auth_token = $settings->get_option('ct_sms_plivo_auth_token');
						$p_client = new Plivo\RestAPI($auth_id, $auth_token, '', '');

						$template = $objdashboard->gettemplate_sms("RS",'S');
						$phone = $staff_phone;
						if($template[4] == "E"){
								if($template[2] == ""){
										$message = base64_decode($template[3]);
								}
								else{
										$message = base64_decode($template[2]);
								}
								$client_sms_body = str_replace($searcharray,$replacearray,$message);
								/* MESSAGE SENDING CODE THROUGH PLIVO */
								$params = array(
										'src' => $settings->get_option('ct_sms_plivo_sender_number'),
										'dst' => $phone,
										'text' => $client_sms_body,
										'method' => 'POST'
								);
								$response = $p_client->send_message($params);
								/* MESSAGE SENDING CODE ENDED HERE*/
						}
					}
				}
			}
			if($settings->get_option('ct_sms_twilio_status') == "Y"){							
				if($settings->get_option('ct_sms_twilio_send_sms_to_staff_status') == "Y"){
					if(isset($staff_phone) && !empty($staff_phone))
					{	
						$AccountSid = $settings->get_option('ct_sms_twilio_account_SID');
						$AuthToken =  $settings->get_option('ct_sms_twilio_auth_token'); 
						$twilliosms_client = new Services_Twilio($AccountSid, $AuthToken);

						$template = $objdashboard->gettemplate_sms("RS",'S');
						$phone = $staff_phone;
						if($template[4] == "E") {
								if($template[2] == ""){
										$message = base64_decode($template[3]);
								}
								else{
										$message = base64_decode($template[2]);
								}
								$client_sms_body = str_replace($searcharray,$replacearray,$message);
								/*TWILIO CODE*/
								$message = $twilliosms_client->account->messages->create(array(
										"From" => $twilio_sender_number,
										"To" => $phone,
										"Body" => $client_sms_body));
						}
					}
				}
			}
			if($settings->get_option('ct_nexmo_status') == "Y"){				
				if($settings->get_option('ct_sms_nexmo_send_sms_to_staff_status') == "Y"){
					if(isset($staff_phone) && !empty($staff_phone))
					{	
						$template = $objdashboard->gettemplate_sms("RS",'S');
						$phone = $staff_phone;				
						if($template[4] == "E") {
							if($template[2] == ""){
								$message = base64_decode($template[3]);
							}
							else{
								$message = base64_decode($template[2]);
							}
							$ct_nexmo_text = str_replace($searcharray,$replacearray,$message);
							$res=$nexmo_client->send_nexmo_sms($phone,$ct_nexmo_text);
						}
					}
				}
			}
		}
		/****************************************** EMAIL CODE END ************************************************/
	}
}
elseif(isset($_POST['delete_staff'])){
	$staff_id = $_POST['staff_id'];
	$objadmin->id = $staff_id;
	$objadmin->delete_staff();
}

if(isset($_POST['action']) && $_POST['action']=='delete_staff_image'){
	$objadmin->id=$_POST['staff_id'];
	$result=$objadmin->update_pic();
}

if(isset($_POST['get_staff_bookingandpayment_by_dateser'])){
	$start = $_POST['startdate'];
	$end = $_POST['enddate'];
	$sid = $_POST['service_id'];
	if($sid == 'all'){
		$all_bookings = $staff_commision->get_staff_bookingandpayment_by_date($start, $end);
	}else{
		$all_bookings = $staff_commision->get_staff_bookingandpayment_by_dateser($start, $end, $sid);
	}
	?>
	<table id="payments-staff-bookingandpymnt-details-ajax" class="display responsive nowrap table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th>#</th>
				<th><?php echo $label_language_values['service'];?></th>
				<th><?php echo $label_language_values['app_date'];?></th>
				<th><?php echo $label_language_values['customer'];?></th>
				<th><?php echo $label_language_values['status'];?></th>
				<th><?php echo $label_language_values['staff_name'];?></th>
				<th><?php echo $label_language_values['net_total'];?></th>
				<th><?php echo $label_language_values['commission_total'];?></th>
				<th><?php echo $label_language_values['action'];?></th>
			</tr>
		</thead>
		<tbody>
			<?php  
			if(mysqli_num_rows($all_bookings) > 0){
				while($all = mysqli_fetch_array($all_bookings)){
					$service_name = $staff_commision->get_service_name($all['service_id']);
					$client_name = $staff_commision->get_client_name($all['client_id']);
					$staff_name = $staff_commision->get_staff_name($all['staff_ids']);
					$net_total = $staff_commision->get_net_total($all['order_id']);
					$get_booking_nettotal = $staff_commision->get_booking_nettotal($all['staff_ids'], $all['order_id']);
					if($all['booking_status'] == 'A'){
						$status = 'Active';
					}elseif($all['booking_status'] == 'C'){
						$status = 'Confirm';
					}elseif($all['booking_status'] == 'R'){
						$status = 'Rejected';
					}elseif($all['booking_status'] == 'CC'){
						$status = 'Cancelled By Client';
					}elseif($all['booking_status'] == 'CS'){
						$status = 'Cancelled By Staff';
					}elseif($all['booking_status'] == 'CO'){
						$status = 'Completed';
					}elseif($all['booking_status'] == 'MN'){
						$status = 'Mark As No Show';
					}elseif($all['booking_status'] == 'RS'){
						$status = 'Rescheduled';
					}
				?>
					<tr>
						<td><?php echo $all['order_id']; ?></td>
						<td><?php echo $service_name; ?></td>
						<td><?php echo str_replace($english_date_array,$selected_lang_label,date($getdateformat,strtotime($all['booking_date_time'])));?></td>
						<td><?php echo $client_name; ?></td>
						<td><?php echo $status; ?></td>
						<td><?php echo rtrim($staff_name,", "); ?></td>
						<td><?php echo  $general->ct_price_format($net_total,$symbol_position,$decimal); ?></td>
						<td><?php echo $general->ct_price_format($get_booking_nettotal,$symbol_position,$decimal); ?></td>
						<td><a href="#add-staff-payment" role="button" class="btn btn-success show_staff_payment_details" data-toggle="modal" data-order_id="<?php echo $all['order_id']; ?>" data-staff_ids="<?php echo $all['staff_ids']; ?>"><?php echo $label_language_values['staff_payment'];?></a></td>
					</tr>
				<?php  
				}
			}
			?>
		</tbody>
	</table>
	<?php  
}

if(isset($_POST['get_payment_staff_by_date'])){
	$start = $_POST['startdate'];
	$end = $_POST['enddate'];
	$all_bookings = $staff_commision->get_payment_staff_by_date($start, $end);
	?>
	<table id="payments-staffp-details-ajax" class="display responsive nowrap table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th>#</th>
				<th><?php echo $label_language_values['client'];?></th>
				<th><?php echo $label_language_values['staff_name'];?></th>
				<th><?php echo $label_language_values['payment_method'];?></th>
				<th><?php echo $label_language_values['payment_date'];?></th>
				<th><?php echo $label_language_values['amount'];?></th>
				<th><?php echo $label_language_values['advance_paid'];?></th>
				<th><?php echo $label_language_values['net_total'];?></th>
			</tr>
		</thead>
		<tbody>
			<?php  
			if(mysqli_num_rows($all_bookings) >0){
				$i=1;
				while($row = mysqli_fetch_array($all_bookings)){
					?>
					<tr>
						<td><?php echo $i; ?></td>
						<td>
							<?php  
							$p_client_name = $objpayment->getclientname($row['order_id']);
							$p_client_name_res = str_split($p_client_name,5);
							echo str_replace(","," ",implode(",",$p_client_name_res));
							?>
						</td>
						<td>
							<?php  
							$objadminprofile->id=$row['staff_id'];
							$s_client_name = $objadminprofile->readone();
							echo $s_client_name['fullname'];
							?>
						</td>
						<td><?php echo $row['payment_method']; ?></td>
						<td><?php echo str_replace($english_date_array,$selected_lang_label,date($getdateformat,strtotime($row['payment_date'])));?></td>
						<td><?php echo  $general->ct_price_format($row['amt_payable'],$symbol_position,$decimal);?></td>
						<td><?php echo  $general->ct_price_format($row['advance_paid'],$symbol_position,$decimal);?></td>
						<td><?php echo  $general->ct_price_format($row['net_total'],$symbol_position,$decimal);?></td>
					</tr>
					<?php  
					$i++;
				}
			}
			?>
		</tbody>
	</table>
	<?php  
}
if(isset($_POST['action']) && $_POST['action']=='payment_status_of_staff'){
	$objpayment->order_id=$_POST['order_id'];
	$objpayment->payment_status="Completed";
	$result=$objpayment->update_payment_status_of_staff();
}
?>