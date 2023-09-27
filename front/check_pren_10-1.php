<?php
ob_start();
session_start();
include(dirname(dirname(__FILE__)) . '/header.php');
include(dirname(dirname(__FILE__)) . '/objects/class_connection.php');
include(dirname(dirname(__FILE__)) . '/objects/class_setting.php');

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$data = json_decode(file_get_contents('php://input'), true);

$query = "select * 
from ct_bookings 
where
id_laboratorio = ".$data['lab']." and
date(booking_date_time) = '".$data['date']."' and
id_categoria_matrice = 17
and record_attivo = 1

";
$con     = new prenotazione_campioni_db();
$conn    = $con->connect();
$setting = new prenotazione_campioni_setting();
$result  = mysqli_query($conn, $query);
if($result->num_rows===0) {
    $return = true;
}else{
    $return = false;
}
echo json_encode($return);

?>