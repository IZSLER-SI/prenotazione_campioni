<?php
ob_start();
session_start();
include(dirname(dirname(__FILE__)) . '/header.php');
include(dirname(dirname(__FILE__)) . '/objects/class_connection.php');
include(dirname(dirname(__FILE__)) . '/objects/class_setting.php');

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$data = json_decode(file_get_contents('php://input'), true);
$query = "select count(*) as tot from ct_bookings
where tipo_prenotazione = 'chimici'
and booking_date_time = '" . $data['date'] . "'
and record_attivo = 1
and id_laboratorio = " . $data['lab'];
$con     = new prenotazione_campioni_db();
$conn    = $con->connect();
$setting = new prenotazione_campioni_setting();
$result  = mysqli_query($conn, $query);
$row     = mysqli_fetch_row($result);
if (!empty($row)) {
  $row = $row[0];
} else {
  $row = 0;
}
$tot_day = $row;
$query = " 
 select 
  max_campioni_chimici 
   from izler_laboratori 
    where id = " . $data['lab'] . " and record_attivo = 1";
$con     = new prenotazione_campioni_db();
$conn    = $con->connect();
$setting = new prenotazione_campioni_setting();
$result  = mysqli_query($conn, $query);
$row     = mysqli_fetch_row($result);
if (!empty($row)) {
  $row = $row[0];
  if ($row[0] == 0) {
    $row = 99999;
  }
} else {
  $row = 0;
}
$tot_lab = $row;
$result_slot = $tot_lab - $tot_day;
echo json_encode($result_slot);
