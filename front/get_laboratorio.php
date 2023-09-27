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
$id_user = isset($_GET['id']) ? $_GET['id'] : null;
if(!empty($id_user)){
    $query = "SELECT lab FROM ct_admin_info where id = ".$id_user;
    $result=mysqli_query($conn,$query);
    $lab=mysqli_fetch_row($result);
    if(empty($lab[0])){
        $query = "select id, descrizione from izler_laboratori where record_attivo = 1";
        $result=mysqli_query($conn,$query);
        $value = [];
        while($row=mysqli_fetch_assoc($result)){
            $row['descrizione'] = $row['descrizione'];
            $value[] = $row;
        }
        $test2 = json_encode($value,JSON_UNESCAPED_UNICODE);
        echo $test2;
    }else{
        $query = "select id, descrizione from izler_laboratori where record_attivo = 1 and id = ".$lab[0];
        $result=mysqli_query($conn,$query);
        $value = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $row['descrizione'] = $row['descrizione'];
            $value[] = $row;
        }
        $test2 = json_encode($value,JSON_UNESCAPED_UNICODE);
        echo $test2;
    }
}