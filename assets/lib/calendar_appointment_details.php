<?php    

session_start();
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_connection.php');
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_services.php');
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_services_methods_units.php');
include(dirname(dirname(dirname(__FILE__)))."/objects/class_adminprofile.php");
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_services_addon.php');
include_once(dirname(dirname(dirname(__FILE__))) . '/header.php');
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_booking.php');
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_users.php');
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_payments.php');
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_setting.php');
include(dirname(dirname(dirname(__FILE__))).'/objects/class_general.php');
include(dirname(dirname(dirname(__FILE__))).'/objects/class_dayweek_avail.php');
$database = new prenotazione_campioni_db();
$conn = $database->connect();
$database->conn = $conn;
$general=new prenotazione_campioni_general();
$general->conn=$conn;
$settings = new prenotazione_campioni_setting();
$settings->conn = $conn;
$symbol_position=$settings->get_option('ct_currency_symbol_position');
$decimal=$settings->get_option('ct_price_format_decimal_places');
$timeformat = $settings->get_option('ct_time_format');
$dateformat = $settings->get_option('ct_date_picker_date_format');
$service = new prenotazione_campioni_services();
$service_method_unit = new prenotazione_campioni_services_methods_units();
$service_addon = new prenotazione_campioni_services_addon();
$booking = new prenotazione_campioni_booking();
$payment = new prenotazione_campioni_payments();
$user = new prenotazione_campioni_users();
$obj_week_day = new prenotazione_campioni_dayweek_avail();
$obj_week_day->conn = $conn;
$service->conn = $conn;
$booking->conn = $conn;
$user->conn = $conn;
$payment->conn = $conn;

$appointment_detail = array();
$events	= array();
if(!empty($_POST['orders'])){
	$orders = $_POST['orders'];
	foreach($orders as $order){
		$book_detail = $booking->get_booking_details_appt($order);
		$events[] = [
			'sede_consegna' 						=> $book_detail['struttura'],
			'laboratorio'									=> $book_detail['laboratorio'],
			'tipo_prenotazione'			=> $book_detail['tipo_prenotazione'],
			'data_prenotazione'			=> $book_detail['booking_date_time'],
			'codice_prenotazione'	=>	$book_detail['order_id'],
			'finalita'													=> $book_detail['finalita'],
			'campioni'												=> $book_detail['all_case'],
			'numero_campioni'					=> $book_detail['n_campione'],
			'matrice'												 => $book_detail['matrice'],
			'prove'								 						=> $book_detail['prove'],
			'cliente'													=> $book_detail['first_name'].' '.$book_detail['last_name'],
			'telefono'												=> $book_detail['phone'],
		];
	}
	echo json_encode($events);
	die();
}else{
	$order_id = $_POST['appointment_id'];
}




$global_vc_status = $settings->get_option('ct_vc_status');$global_p_status = $settings->get_option('ct_p_status');/*CHECK FOR VC AND PARKING STATUS END*/

$objadmin = new prenotazione_campioni_adminprofile();
$objadmin->conn=$conn;

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



/*new file include*/
include(dirname(dirname(dirname(__FILE__))).'/assets/lib/date_translate_array.php');

/* NEW */
$appointment_detail['id'] = $order_id;
$book_detail = $booking->get_booking_details_appt($order_id);
$appointment_detail['matrice'] 	= $book_detail['matrice'];
$appointment_detail['finalita'] = $book_detail['finalita'];
$appointment_detail['text'] = $book_detail['text'];
$appointment_detail['prove'] = '';
$appointment_detail['prove'] .= '<ul>';
foreach(explode('|',$book_detail['prove']) as $prova){
	$appointment_detail['prove'] 			.= '<li>'.$prova.'</li><br>';
}
$appointment_detail['prove'] .= '</ul>';
$appointment_detail['struttura'] 			= $book_detail['struttura'];
$appointment_detail['prenotazione'] = $book_detail['tipo_prenotazione'];
$appointment_detail['all_case'] = $book_detail['all_case'];


if(empty($book_detail['convocazione_perito']) && $book_detail['convocazione_perito'] == 0 ){
	$appointment_detail['perito'] 	= 'No';
}else{
	$appointment_detail['perito'] 	= 'Si';
}

if(empty($book_detail['unica_istanza']) && $book_detail['unica_istanza'] == 0 ){
	$appointment_detail['istanza'] = 'No';
}else{
	$appointment_detail['istanza'] 	= 'Si';
}

$appointment_detail['appointment_starttime'] = str_replace($english_date_array,$selected_lang_label,date($dateformat, strtotime($book_detail['booking_date_time'])));
if($timeformat == 12){
    $appointment_detail['appointment_start_time'] = str_replace($english_date_array,$selected_lang_label,date("h:i A",strtotime($book_detail['booking_date_time'])));
}
else
{
    $appointment_detail['appointment_start_time'] = date("H:i", strtotime($book_detail['booking_date_time']));
}
/* methods */
$units = $label_language_values['none'];
$methodname=$label_language_values['none'];
$hh = $booking->get_methods_ofbookings($order_id);
$count_methods = mysqli_num_rows($hh);
$hh1 = $booking->get_methods_ofbookings($order_id);
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
$addons = $label_language_values['none'];
$hh = $booking->get_addons_ofbookings($order_id);
while($jj = mysqli_fetch_array($hh)){
    if($addons == $label_language_values['none']){
        $addons = $jj['addon_service_name']."-".$jj['addons_service_qty'];
    }
    else
    {
        $addons = $addons.",".$jj['addon_service_name']."-".$jj['addons_service_qty'];
    }
}

$appointment_detail['method_title'] = ": " . $methodname;
$appointment_detail['unit_title'] = ": " . $units;
$appointment_detail['addons_title'] = ": " . $addons;
//$appointment_detail['service_title'] = ": " . $book_detail[8];
//$appointment_detail['gc_event_id'] = $book_detail[9];
//$appointment_detail['gc_staff_event_id'] = $book_detail['gc_staff_event_id'];
$appointment_detail['staff_ids'] = $book_detail['staff_ids'];
 
$appointment_detail['client_name'] 	= $book_detail['first_name'].' '.$book_detail['last_name'];
$appointment_detail['client_email'] = $book_detail['user_email'];
$appointment_detail['client_phone'] = $book_detail['phone'];
$appointment_detail['laboratorio'] 	= $book_detail['laboratorio'];

$appointment_detail['campione'] 	= $book_detail['campione'];
$appointment_detail['ncampione'] = $book_detail['n_campione'];
$appointment_detail['specie'] 			= $book_detail['specie'];

$appointment_detail['client_address'] = $book_detail['address'].', '.$book_detail['city'].', '.$book_detail['zip'].', '.$book_detail['state'];

//$temppp= unserialize(base64_decode($book_detail[5]));
//$tem = str_replace('\\','',$temppp);

//if($tem['notes']!=""){
//	$finalnotes = " : ".$tem['notes'];
//}else{
//	$finalnotes = "";
//}
//$vc_status = $tem['vc_status'];

//if($vc_status == 'N'){
//	$final_vc_status = $label_language_values['no'];
//}
//elseif($vc_status == 'Y'){
//	$final_vc_status = $label_language_values['yes'];
//}else{
//	$final_vc_status = "-";
//}
//$p_status = $tem['p_status'];
//if($p_status == 'N'){
//	$final_p_status = $label_language_values['no'];
//}
//elseif($p_status == 'Y'){
//	$final_p_status = $label_language_values['yes'];
//}else{
//	$final_p_status = "-";
//}

/* $appointment_detail['client_address'] = " : ".$tem['address'].", ".$tem['city'].", ".$tem['zip'].", ".$tem['state']; */
//$booking_duration = $book_detail['order_duration'];
//if($booking_duration != 0){
//	$hours = intval($booking_duration/60);
//	$minutes = fmod( $booking_duration ,60);
//
//	$appointment_detail['booking_duration'] = " : ".$hours." ".$label_language_values['hours']." ".$minutes." ".$label_language_values['minutes'];
//}else{
//	$appointment_detail['booking_duration'] = "";
//}

//$appointment_detail['vaccum_cleaner'] = " : ".$final_vc_status;
//$appointment_detail['parking'] = " : ".$final_p_status;
//$appointment_detail['client_notes'] = $finalnotes;
//$appointment_detail['contact_status'] = ": " . $tem['contact_status'];
//$appointment_detail['global_vc_status'] = $global_vc_status;
//$appointment_detail['global_p_status'] = $global_p_status;


//if ($book_detail[0] == 'A') {
//    $status = $label_language_values['active'];
//	$appointment_detail['reason_view_status'] = "hide";
//	$appointment_detail['reject_reason'] = "";
//} elseif ($book_detail[0] == 'C') {
//    $status = $label_language_values['confirm'];
//	$appointment_detail['reason_view_status'] = "hide";
//	$appointment_detail['reject_reason'] = "";
//} elseif ($book_detail[0] == 'R') {
//    $status = $label_language_values['reject'];
//	$appointment_detail['reason_view_status'] = "show";
//	if($book_detail['reject_reason'] != ""){
//		$appointment_detail['reject_reason'] = ": " . $book_detail['reject_reason'];
//	}else{
//		$appointment_detail['reject_reason'] = "";
//	}
//} elseif ($book_detail[0] == 'RS') {
//    $status = $label_language_values["rescheduled"];
//	$appointment_detail['reason_view_status'] = "show";
//	if($book_detail['reject_reason'] != ""){
//		$appointment_detail['reject_reason'] = ": " . $book_detail['reject_reason'];
//	}else{
//		$appointment_detail['reject_reason'] = "";
//	}
//} elseif ($book_detail[0] == 'CC') {
//    $status =$label_language_values['cancel_by_client'];
//	$appointment_detail['reason_view_status'] = "show";
//	if($book_detail['reject_reason'] != ""){
//		$appointment_detail['reject_reason'] = ": " . $book_detail['reject_reason'];
//	}else{
//		$appointment_detail['reject_reason'] = "";
//	}
//} elseif ($book_detail[0] == 'CS') {
//    $status = $label_language_values['cancelled_by_service_provider'];
//	$appointment_detail['reason_view_status'] = "show";
//	if($book_detail['reject_reason'] != ""){
//		$appointment_detail['reject_reason'] = ": " . $book_detail['reject_reason'];
//	}else{
//		$appointment_detail['reject_reason'] = "";
//	}
//} elseif ($book_detail[0] == 'CO') {
//    $status = $label_language_values['completed'];
//	$appointment_detail['reason_view_status'] = "hide";
//	$appointment_detail['reject_reason'] = "";
//} else {
//    $book_detail[0] == 'MN';
//    $status = $label_language_values['mark_as_no_show'];
//	$appointment_detail['reason_view_status'] = "hide";
//	$appointment_detail['reject_reason'] = "";
//}
$appointment_detail['booking_status'] = $book_detail['booking_status'];
//if($status == "Confirm"){
//    $appointment_detail['hider'] = "c";
//}
//else
//{
//    $appointment_detail['hider'] = "r";
//}
$booking_day = date("Y-m-d", strtotime($book_detail['booking_date_time']));
$current_day = date("Y-m-d");
if ($current_day > $booking_day)
{
    $appointment_detail['past'] = "Yes";
}
else
{
    $appointment_detail['past'] = "No";
}

$get_staff_services = $objadmin->readall_staff_booking();
$booking->order_id = $order_id;
$get_staff_assignid = explode(",",$booking->fetch_staff_of_booking());

$staff_html = "";
$staff_html .= "<select id='staff_select' class='selectpicker col-md-10' data-live-search='true' multiple data-actions-box='true' data-orderid='".$order_id."'>";

$booking->booking_date_time = $book_detail['booking_date_time'];
$staff_status = $booking->booked_staff_status();
$staff_status_arr = explode(",",$staff_status);

foreach($get_staff_services as $staff_details)
{
	$i = "no";
	$staffname = $staff_details['fullname'];
	$staffid = $staff_details['id'];
	$s_s = "";
	if(in_array($staffid,$staff_status_arr)){
		$s_s = "fa fa-calendar-check-o";
	}
	
	if(in_array($staffid,$get_staff_assignid)){
		$i = "yes";
	}
	if($i == "yes")
	{
		$staff_html .= "<option selected='selected' data-icon='".$s_s." booking-staff-assigned' value='$staffid'>$staffname</option>";
	}
	else{
		$staff_html .= "<option data-icon='".$s_s." booking-staff-assigned' value='$staffid'>$staffname</option>";
	}
}

$staff_html .= "</select><a href='javascript:void(0)' data-orderid='".$order_id."' class='save_staff_booking edit_staff btn btn-info'><i class='remove_add_fafa_class fa fa-pencil-square-o'></i></a>";
$appointment_detail['staff'] = $staff_html;
echo json_encode($appointment_detail);
die();