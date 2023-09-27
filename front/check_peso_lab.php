<?php
ob_start();
session_start();
include(dirname(dirname(__FILE__)) . '/header.php');
include(dirname(dirname(__FILE__)) . '/objects/class_connection.php');
include(dirname(dirname(__FILE__)) . '/objects/class_setting.php');
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$data = json_decode(file_get_contents('php://input'), true);

$query = " 
select sum(izler_prove_bookings.peso_prova * n_campione) as tot
from 
 ct_bookings
left join izler_prove_bookings 
 on (
  ct_bookings.order_id = izler_prove_bookings.id_ordine
 )
where 
record_attivo = 1 and
 booking_date_time = '" . $data['date'] . "' and 
 (tipo_prenotazione = '" . $data['tipo_prenotazione'] . "' or
 tipo_prenotazione = 'manuale') and
 n_campione is not null and
 id_laboratorio = " . $data['lab'];
$con     = new prenotazione_campioni_db();
$conn    = $con->connect();
$setting = new prenotazione_campioni_setting();
$result  = mysqli_query($conn, $query);
$row     = mysqli_fetch_row($result);
if (!empty($row)) {
    $row = $row[0];
} else {
    $row = 0;
}
$tot_day = $row;
$query = " 
 select 
  peso_prove,max_campioni_conoscitivi 
   from izler_laboratori 
    where id = " . $data['lab'] . " and record_attivo = 1";
$con     = new prenotazione_campioni_db();
$conn    = $con->connect();
$setting = new prenotazione_campioni_setting();
$result  = mysqli_query($conn, $query);
$row     = mysqli_fetch_row($result);
if (!empty($row)) {
    if ($data['tipo_prenotazione'] == 'autocontrollo') {
        $row = $row[0];
    } else {
        $row = $row[1];
    }
} else {
    $row = 0;
}
$tot_lab = $row;

$query = " 
select n_campioni from izler_override_campioni where date = '" . $data['date'] . "' and id_laboratorio = " . $data['lab'];
$con     = new prenotazione_campioni_db();
$conn    = $con->connect();
$setting = new prenotazione_campioni_setting();
$result  = mysqli_query($conn, $query);
if ($result->num_rows > 0) {
    $row = mysqli_fetch_row($result);
    if (!empty($row)) {
        $row = $row[0];
    } else {
        $row = 0;
    }
    $tot_override = $row;
} else {
    $tot_override = 0;
}
if ($tot_override > 0) {
    $result_slot = $tot_override - $tot_day;
} else {
    $result_slot = $tot_lab - $tot_day;
}
echo json_encode($result_slot);
