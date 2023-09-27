<?php
session_start();
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_connection.php');
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_services.php');
include_once(dirname(dirname(dirname(__FILE__))) . '/header.php');

$database = new prenotazione_campioni_db();
$conn = $database->connect();

$lab  = $_GET['lab'];
$date = $_GET['date'];
$end_date = $_GET['end_date'];
$query = "
  select * from ct_bookings where record_attivo = 1 and id_laboratorio = $lab and booking_date_time = '$date'";
//$query = "
//select 
//  l.id, 
//  l.campioni_ufficiale, t.numero, case when t.unica_istanza = 1 then 0 else l.campioni_ufficiale - t.numero end as differenza
//  from izler_laboratori l
//      left join (
//          select count(order_id) as numero,id_laboratorio,unica_istanza
//          from ct_bookings
//          where 
//          record_attivo = 1 and 
//          booking_date_time = '$date'
//      ) as t on (l.id = t.id_laboratorio)
//  where id = $lab";
$result = mysqli_query($conn, $query);
$numero = mysqli_num_rows($result);


$query_between = "select id from ct_bookings where id_laboratorio = $lab and booking_date_time >= '$date' and booking_date_time <'$end_date'
and record_attivo = 1";
$result_between = mysqli_query($conn, $query_between);
$numero_between = mysqli_num_rows($result_between);
if ($numero == 0 && $numero_between == 0) {
  echo json_encode(true);
 } else {
  echo json_encode(false);
 }