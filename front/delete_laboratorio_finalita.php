<?php
ob_start();
session_start();
include(dirname(dirname(__FILE__)) . '/header.php');
include(dirname(dirname(__FILE__)) . '/objects/class_connection.php');
include(dirname(dirname(__FILE__)) . '/objects/class_setting.php');
include(dirname(dirname(__FILE__)) . "/objects/class_services.php");

$current_time = date("Y-m-d H:i:s");

$con = new prenotazione_campioni_db();
$conn = $con->connect();
$setting = new prenotazione_campioni_setting();
$setting->conn = $conn;
$service = new prenotazione_campioni_services();
$service->conn = $conn;
$id = $_POST['id'];
$service->delete_lab_assoc($id);