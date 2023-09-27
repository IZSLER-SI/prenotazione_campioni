<?php
ob_start();
session_start();
include(dirname(dirname(__FILE__)) . '/header.php');
include(dirname(dirname(__FILE__)) . '/objects/class_connection.php');
include(dirname(dirname(__FILE__)) . '/objects/class_setting.php');

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$data = json_decode(file_get_contents('php://input'), true);
if (!empty($data['fin'])) {
    $query = "
        SELECT izler_laboratori.descrizione,izler_laboratori.id,slot from izler_mapping
        join izler_laboratori on izler_mapping.id_izler_laboratorio = izler_laboratori.id
        where id_izler_struttura = ".$data['strut']." and id_izler_finalita = ".$data['fin']." and id_izsler_categoria_matrice = ".$data['cat']." and id_izler_prove = ".$data['prove'][0]." limit 1";
} else {
    $query = "
        SELECT izler_laboratori.descrizione,izler_laboratori.id,slot from izler_mapping
        join izler_laboratori on izler_mapping.id_izler_laboratorio = izler_laboratori.id
        where id_izler_struttura = ".$data['strut']." and id_izler_campione = ".$data['campione']." and id_izler_prove = ".$data['prove'][0]." limit 1";
}
$con = new prenotazione_campioni_db();
$conn = $con->connect();
$setting = new prenotazione_campioni_setting();
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_row($result);
$query2 = 'select weekday_id,day_start_time as start,day_end_time as end from ct_week_days_available
where provider_id ='.$row[1];
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