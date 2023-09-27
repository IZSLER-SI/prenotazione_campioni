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


$values = new \stdClass;
$values->id_struttura    = $_POST['select_struttura'];
$values->id_finalita     = $_POST['select_finalita'];
$values->id_categoria     = $_POST['select_categoria'];
$values->id_prove        = $_POST['select_prove'];
$values->peso_prova      = $_POST['input_peso_ufficiale'];
$values->id_laboratorio  = $_POST['select_laboratorio'];
$service->add_fin_assoc($values);