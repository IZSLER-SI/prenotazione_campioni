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

$id      = $_POST['id'];
$text    = $_POST['text'];
$query = "update ct_bookings set text='".mysqli_real_escape_string($conn,$text)."' where order_id=".$id;
$result=mysqli_query($conn,$query);
echo json_encode('done');