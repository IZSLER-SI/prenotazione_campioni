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

$date = $_POST['date'];
$query = "
select
sum(izler_mapping.peso_prova) as peso
	from ct_bookings
			left join izler_finalita on (ct_bookings.id_finalita = izler_finalita.id)
			left join izler_strutture on (ct_bookings.id_struttura = izler_strutture.id)
			left join izler_specie on (ct_bookings.id_specie = izler_specie.id)
			left join izler_campione on (ct_bookings.id_campione = izler_campione.id)
			left join izler_prove_bookings on (ct_bookings.order_id = izler_prove_bookings.id_ordine)
			left join izler_prove on (izler_prove_bookings.id_prova = izler_prove.id)
			left join ct_users on (ct_bookings.client_id = ct_users.id)
   left join izler_mapping on (
       izler_strutture.id = izler_mapping.id_izler_struttura and
       izler_finalita.id = izler_mapping.id_izler_finalita and
       izler_prove_bookings.id_prova = izler_mapping.id_izler_prove
   )
   left join izler_laboratori on izler_mapping.id_izler_laboratorio = izler_laboratori.id
   where 
			ct_bookings.record_attivo = 1 and
			DATE(ct_bookings.booking_date_time) >= '$date' and DATE(ct_bookings.booking_date_time) <= '$date'";
$result=mysqli_query($conn,$query);
$numero_prove = $row=mysqli_fetch_row($result)[0];
if($numero_prove){
 echo $numero_prove;
}else{
 echo 0;
}