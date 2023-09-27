<?php
ob_start();
session_start();
include(dirname(dirname(__FILE__)) . '/header.php');
include(dirname(dirname(__FILE__)) . '/objects/class_connection.php');
include(dirname(dirname(__FILE__)) . '/objects/class_setting.php');

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$data = json_decode(file_get_contents('php://input'), true);

$con = new prenotazione_campioni_db();
$conn = $con->connect();
$setting = new prenotazione_campioni_setting();
$query = 'select slot from izler_laboratori where id = '.$_GET['lab'];
$result = mysqli_query($conn, $query);
$row[2] = mysqli_fetch_row($result)[0];
$query2 = 'select weekday_id,day_start_time as start,day_end_time as end from ct_week_days_available where provider_id ='.$_GET['lab'];
$result2 = mysqli_query($conn, $query2);
if($result2->num_rows > 0){
    while ($line = mysqli_fetch_assoc($result2)) {
        $business[$line['weekday_id']] = [$line['start'],$line['end']];
    }
}else{
    $query2 = 'select weekday_id,day_start_time as start,day_end_time as end from ct_week_days_available
    where provider_id = 0';
    $result2 = mysqli_query($conn, $query2);
    while ($line = mysqli_fetch_assoc($result2)) {
        $business[$line['weekday_id']] = [$line['start'],$line['end']];
    }
}
$row[3] = $business;
echo json_encode($row);