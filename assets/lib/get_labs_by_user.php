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

$query = "select izler_laboratori.descrizione, izler_laboratori.id
from ct_admin_laboratori
         join izler_laboratori on ct_admin_laboratori.id_izler_laboratori = izler_laboratori.id
         join ct_admin_info on ct_admin_laboratori.id_ct_admin = ct_admin_info.id
where id_ct_admin = ".$_SESSION['ct_laboratorioid'];
$result=mysqli_query($conn,$query);
while($row=mysqli_fetch_array($result)){
    $laboratori[] = $row;
}
echo json_encode($laboratori);