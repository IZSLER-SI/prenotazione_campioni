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
$setting = new prenotazione_campioni_setting();
$_SESSION['lab_selected'] = !(empty($_POST['lab'])) ? $_POST['lab'] : 0;
//update the lab for the admin user
if(isset($_SESSION['ct_laboratorioid'])){
  $query = "update ct_admin_info set lab=".$_POST['lab']." where id=".$_SESSION['ct_laboratorioid'];
}else{
  $query = "update ct_admin_info set lab=".$_POST['lab']." where id=".$_SESSION['ct_accettazioneid'];
}

$result=mysqli_query($conn,$query);
echo json_encode('done');