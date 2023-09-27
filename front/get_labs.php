<?php
ob_start();
session_start();
include(dirname(dirname(__FILE__)) . '/header.php');
include(dirname(dirname(__FILE__)) . '/objects/class_connection.php');
include(dirname(dirname(__FILE__)) . '/objects/class_setting.php');
header('Content-type: application/json; charset=UTF-8');

$con = new prenotazione_campioni_db();
$conn = $con->connect();
$setting = new prenotazione_campioni_setting();

$query = "select id, descrizione from izler_laboratori where record_attivo = 1";
$result = mysqli_query($conn, $query);
$value = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['descrizione'] = $row['descrizione'];
    $value[] = $row;
}
$test2 = json_encode($value,JSON_UNESCAPED_UNICODE);
echo $test2;