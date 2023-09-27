<?php
header('Content-Type: text/json; charset=utf-8');
ob_start();
session_start();
include(dirname(dirname(dirname(__FILE__))) . '/header.php');
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_connection.php');
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_setting.php');

error_reporting(E_ALL);
ini_set('display_errors', 'On'); 

$con = new prenotazione_campioni_db();
$conn = $con->connect();
$setting = new prenotazione_campioni_setting();

$slot      = $_POST['campioni_alimenti'];
$laboratorio   = $_POST['lab'];

$query = "update izler_laboratori set max_campioni_alimenti=".$slot." where id=".$laboratorio;
$result=mysqli_query($conn,$query);
echo json_encode('done');