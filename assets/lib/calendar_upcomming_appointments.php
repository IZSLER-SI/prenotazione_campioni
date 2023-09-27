<?php    
session_start();
include(dirname(dirname(dirname(__FILE__))).'/objects/class_connection.php');	
include(dirname(dirname(dirname(__FILE__))).'/objects/class_services.php');	
include_once(dirname(dirname(dirname(__FILE__))).'/header.php');		
include(dirname(dirname(dirname(__FILE__))).'/objects/class_booking.php');
include(dirname(dirname(dirname(__FILE__))).'/objects/class_users.php');
include(dirname(dirname(dirname(__FILE__)))."/objects/class_dashboard.php");
include(dirname(dirname(dirname(__FILE__)))."/objects/class_setting.php");
include(dirname(dirname(dirname(__FILE__))).'/objects/class_general.php');
include(dirname(dirname(dirname(__FILE__)))."/objects/class_gc_hook.php");
include(dirname(dirname(dirname(__FILE__))).'/objects/class_front_first_step.php');
$database=new prenotazione_campioni_db();
$conn=$database->connect();
$database->conn=$conn;

$setting = new prenotazione_campioni_setting();
$setting->conn = $conn;
$general=new prenotazione_campioni_general();
$general->conn=$conn;
$symbol_position=$setting->get_option('ct_currency_symbol_position');
$decimal=$setting->get_option('ct_price_format_decimal_places');
$getdateformate = $setting->get_option('ct_date_picker_date_format');
$time_format = $setting->get_option('ct_time_format');
$service=new prenotazione_campioni_services();	
$booking=new prenotazione_campioni_booking();
$user=new prenotazione_campioni_users();
$service->conn=$conn;		
$booking->conn=$conn;
$user->conn=$conn;
$gc_hook = new prenotazione_campioni_gcHook();
$gc_hook->conn = $conn;
$first_step=new prenotazione_campioni_first_step();
$first_step->conn=$conn;
if(isset($_SESSION['cal_service_id'])){
	$booking->service_id=$_SESSION['cal_service_id'];
}
if(isset($_SESSION['cal_provider_id'])){
	$booking->provider_id=$_SESSION['cal_provider_id'];	
}
if(isset($_SESSION['cal_startdate'])){
	$booking->booking_start_datetime=$_SESSION['cal_startdate'];
}
if(isset($_SESSION['cal_enddate'])){
	$booking->booking_end_datetime=$_SESSION['cal_enddate'];
}

$t_zone_value = $setting->get_option('ct_timezone');
$server_timezone = date_default_timezone_get();
if(isset($t_zone_value) && $t_zone_value!=''){
	$offset= $first_step->get_timezone_offset($server_timezone,$t_zone_value);
	$timezonediff = $offset/3600;
}else{
	$timezonediff =0;
}
if(is_numeric(strpos($timezonediff,'-'))){
	$timediffmis = str_replace('-','',$timezonediff)*60;
	$currDateTime_withTZ= strtotime("-".$timediffmis." minutes",strtotime(date('Y-m-d H:i:s')));
}else{
	$timediffmis = str_replace('+','',$timezonediff)*60;
	$currDateTime_withTZ = strtotime("+".$timediffmis." minutes",strtotime(date('Y-m-d H:i:s')));
}

$start_date = $_GET["start"];
$end_date = $_GET["end"];

if($time_format == 12){
	$format= 'H:ia';
}else{
	$format= 'H:i';
}

$appointment_array_for_cal = array();

$all_db_gc_admin_ids = array();
$all_db_gc_staff_ids = array();
/** Get Google Calendar Bookings **/
$CalenderBooking = array();
if($gc_hook->gc_purchase_status() == 'exist'){
	$gc_hook->google_cal_TwoSync_admin_hook();
	$all_gc_ids_result = $booking->get_all_gc_from_db();
	if(mysqli_num_rows($all_gc_ids_result) > 0){
		while($row = mysqli_fetch_assoc($all_gc_ids_result)){
			$order_id = $row["order_id"];
			$gc_event_id = $row["gc_event_id"];
			$gc_staff_event_id = $row["gc_staff_event_id"];
			$all_db_gc_admin_ids[$order_id] = $gc_event_id;
			$all_db_gc_staff_ids[$order_id] = $gc_staff_event_id;
		}
	}
	if(!empty($CalenderBooking)){
		foreach($CalenderBooking as $cb){
			if(!in_array($cb["id"],$all_db_gc_admin_ids)){
				$order_id = $cb["id"];
				$color = $cb["color"];
				$title = $cb["title"];
				$start = date("Y-m-d H:i:s",$cb["start"]);
				$status = "GC";
				$appointment_array_for_cal[]= array(
					"id"=>"$order_id",
					"color_tag"=>"$color",
					"title"=>"$title",
					"start"=>"$start",
					"end"=>"$start",
					"event_status"=>"$status",
					"date_format"=>"$getdateformate",
					"time_format"=>"$format",
					"open_popup"=>false
				);
			}
		}
	}
}
/** Get Google Calendar Bookings **/

$myarrbook = $booking->getallbookings($start_date,$end_date);
while($tt = mysqli_fetch_array($myarrbook)){
	$order_id = $tt['order_id'];
	$color=$tt['color'];
	$title=$tt['title'];
	
	$start=$tt['booking_date_time'];
	$end=$tt['booking_date_time'];
	$price=$general->ct_price_format($tt['net_amount'],$symbol_position,$decimal);
	$status = $tt['booking_status'];
	if($tt['client_id'] == 0){
		$gcn = $user->readoneguest($tt['order_id']);
		$clientname = $gcn[2];
		$clientphone = $gcn[4];
		$clientemail = $gcn[3];
	}else{
		$user->user_id = $tt['client_id'];
		$cn = $user->readone();
		$clientname = $cn[3]."".$cn[4];
		$fetch_phone =  strlen($cn[5]);
		if($fetch_phone >= 6){
			$clientphone = $cn[5];
		}else{
			$clientphone = '';
		}
    $clientemail = $cn[1];
  }
  $appointment_array_for_cal[]= array(
		"id"=>"$order_id",
		"color_tag"=>"$color",
		"title"=>"$title",
		"start"=>"$start",
		"end"=>"$start",
		"event_status"=>"$status",
		"client_name"=>"$clientname",
		"client_phone"=>"$clientphone",
		"client_email"=>"$clientemail",
		"total_price"=>"$price",
		"date_format"=>"$getdateformate",
		"time_format"=>"$format",
		"open_popup"=>true
  );
}
if(isset($appointment_array_for_cal)){
	$json_encoded_string_for_cal  =  json_encode($appointment_array_for_cal);
	echo $json_encoded_string_for_cal;die();
}
?>