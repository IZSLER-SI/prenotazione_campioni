<?php
header('Content-Type: text/json; charset=utf-8');
ob_start();
session_start();
include(dirname(dirname(__FILE__)) . '/header.php');
include(dirname(dirname(__FILE__)) . '/objects/class_connection.php');
include(dirname(dirname(__FILE__)) . '/objects/class_setting.php');

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$con = new prenotazione_campioni_db();
$conn = $con->connect();
$conn->query('SET NAMES utf8');
$setting = new prenotazione_campioni_setting();

$query = "select distinct izler_finalita.descrizione,izler_finalita.id from izler_mapping
 join izler_finalita on izler_mapping.id_izler_finalita = izler_finalita.id and izler_finalita.record_attivo = 1
 where izler_mapping.record_attivo = 1 and id_izler_struttura = " . $_GET['struttura'];
$result = mysqli_query($conn, $query);
$value = [];
while ($row = mysqli_fetch_assoc($result)) {
  $row['descrizione'] = $row['descrizione'];
  $value[] = $row;
}
$test2 = json_encode($value, JSON_UNESCAPED_UNICODE);
echo $test2;
