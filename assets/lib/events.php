<?php


session_start();
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_connection.php');
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_services.php');
include_once(dirname(dirname(dirname(__FILE__))) . '/header.php');
function add_time($time,$plusMinutes){

	$endTime = strtotime("+{$plusMinutes} minutes", strtotime($time));
	return date('Y-m-d h:i:s', $endTime);
}

$database = new prenotazione_campioni_db();
$conn = $database->connect();
$tipo_prenotazione = $_GET['prenotazione'];
$lab 														= $_GET['lab'];
$istanza 										= $_GET['istanza'];
$slot_query = "select slot from izler_laboratori where id = ".$lab;
$slot_result = mysqli_query($conn, $slot_query);
$slot = mysqli_fetch_row($slot_result)[0];

switch ($tipo_prenotazione) {
	case 'ufficiale':
		$query = "select * from ct_bookings where  record_attivo = 1 and tipo_prenotazione != 'autocontrollo' and id_laboratorio = ".$lab;
		break;
	case 'autocontrollo':
		$query = "select * from ct_bookings where record_attivo = 1 and tipo_prenotazione != 'ufficiale' and id_laboratorio = ".$lab;
		break;
	case 'conoscitivo':
		$query = "select * from ct_bookings where record_attivo = 1 and tipo_prenotazione != 'ufficiale' and tipo_prenotazione != 'autocontrollo' and tipo_prenotazione != 'manuale' and id_laboratorio = ".$lab;
		break;
  case 'chimici':
    $query = "select * from ct_bookings where  record_attivo = 1 and tipo_prenotazione != 'autocontrollo' and tipo_prenotazione != 'ufficiale'  and id_laboratorio = ".$lab;
    break;
	default:
		break;
}
$result = mysqli_query($conn, $query);
$events = [];
while ($row = mysqli_fetch_assoc($result)) {
	$event = array(
		"id"																=>	$row['order_id'],
		"backgroundColor"			=>	"#4e5ecc",
		"title"													=>	'prenotazione',
		"start"													=>	$row['booking_date_time'],
		"end"															=>	add_time($row['booking_date_time'],$slot),
		"event_status"						=>	$row['reminder_status'],
		"open_popup"								=>	true,
	);
	if ($row['tipo_prenotazione'] == 'giornata') {
		$event['allDay'] 									= 'true';
		$event['display'] 								= 'background';
		$event['title']											=	'Non disponibile';
		$event['backgroundColor']	= 'red';
		$event['textColor']							= 'black';
	}
	$events[] = $event;
}
switch ($tipo_prenotazione) {
	case 'autocontrollo':
		$events = [];
		break;
	case 'conoscitivo':
		$events = [];
		break;
  case 'chimici':
    $events = [];
    break;
	default:
		break;
}
echo json_encode($events);
