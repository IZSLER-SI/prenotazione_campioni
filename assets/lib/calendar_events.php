<?php
session_start();
include(dirname(dirname(dirname(__FILE__))).'/objects/class_connection.php');	
include(dirname(dirname(dirname(__FILE__))).'/objects/class_services.php');	
include_once(dirname(dirname(dirname(__FILE__))).'/header.php');		
include(dirname(dirname(dirname(__FILE__))).'/objects/class_booking.php');

//date 1 week ago
$day = date('Y-m-d', strtotime('-1 week'));
$database=new prenotazione_campioni_db();
$conn=$database->connect();
$conn->query('SET NAMES utf8');
if(isset($_SESSION['lab_selected']) && !empty($_SESSION['lab_selected']) && $_SESSION['lab_selected'] != 0){
	$query = "
	select
	ct_bookings.*,
	izler_finalita.descrizione,
	izler_strutture.descrizione,
	matrice,
	izler_specie.descrizione,
	izler_campione.descrizione,
	group_concat(distinct izler_prove.descrizione separator '<br>') as prove,
	group_concat(distinct izler_prove.descrizione separator '|'),
	ct_users.*,
	izler_mapping.id_izler_laboratorio,
	group_concat(distinct izler_laboratori.descrizione separator '|') as laboratorio
		from ct_bookings
				left join izler_finalita on (
					ct_bookings.id_finalita = izler_finalita.id
				)
				left join izler_strutture on (
					ct_bookings.id_struttura = izler_strutture.id
				)
				left join izler_specie on (
					ct_bookings.id_specie = izler_specie.id
				)
				left join izler_campione on (
					ct_bookings.id_campione = izler_campione.id
				)
				left join izler_prove_bookings on (
					ct_bookings.order_id = izler_prove_bookings.id_ordine
				)
				left join izler_prove on (
					izler_prove_bookings.id_prova = izler_prove.id
				)
				left join ct_users on (
					ct_bookings.client_id = ct_users.id
				)
				left join izler_mapping on (
					izler_strutture.id = izler_mapping.id_izler_struttura and
					izler_finalita.id = izler_mapping.id_izler_finalita and
					izler_prove_bookings.id_prova = izler_mapping.id_izler_prove
				)
				left join izler_laboratori on (
					id_laboratorio = izler_laboratori.id
				)
				where 
				ct_bookings.record_attivo = 1 and
				tipo_prenotazione != 'conoscitivo' and tipo_prenotazione != 'chimici' and
				booking_date_time >= '".$day." 00:00:00' and
				id_laboratorio = ".$_SESSION["lab_selected"]."
				group by ct_bookings.order_id";
}else{
	$query = "
	select
	ct_bookings.*,
	izler_finalita.descrizione,
	izler_strutture.descrizione,
	matrice,
	izler_specie.descrizione,
	izler_campione.descrizione,
	group_concat(distinct izler_prove.descrizione separator '<br>') as prove,
	ct_users.*,
	izler_mapping.id_izler_laboratorio,
	group_concat(distinct izler_laboratori.descrizione separator '|') as laboratorio
		from ct_bookings
				left join izler_finalita on (
					ct_bookings.id_finalita = izler_finalita.id
				)
				left join izler_strutture on (
					ct_bookings.id_struttura = izler_strutture.id
				)
				left join izler_specie on (
					ct_bookings.id_specie = izler_specie.id
				)
				left join izler_campione on (
					ct_bookings.id_campione = izler_campione.id
				)
				left join izler_prove_bookings on (
					ct_bookings.order_id = izler_prove_bookings.id_ordine
				)
				left join izler_prove on (
					izler_prove_bookings.id_prova = izler_prove.id
				)
				left join ct_users on (
					ct_bookings.client_id = ct_users.id
				)
				left join izler_mapping on (
					izler_strutture.id = izler_mapping.id_izler_struttura and
					izler_finalita.id = izler_mapping.id_izler_finalita and
					izler_prove_bookings.id_prova = izler_mapping.id_izler_prove
				)
				left join izler_laboratori on (
					id_laboratorio = izler_laboratori.id
				)
				where 
				booking_date_time >= '".$day." 00:00:00' and
				ct_bookings.record_attivo = 1 and tipo_prenotazione != 'conoscitivo' and tipo_prenotazione != 'chimici' and
				id_laboratorio = 1
				group by ct_bookings.order_id";
}

$result = mysqli_query($conn, $query);
$events = [];
while ($row = mysqli_fetch_assoc($result)) {
	$color = '';
	switch ($row['tipo_prenotazione']) {
		case 'ufficiale':
			$color = '#4e5ecc';
      break;
    case 'autocontrollo':
      $color = '#187d90';
      break;
    case 'occupato':
      $color = '#8E8E8E';
      break;
    case 'chimici':
      $color = '#a65546';
      break;
    default:
      $color = 'black';
      break;
  }
  $events[] = array(
    "id" => $row['order_id'],
    "color_tag" => $color,
    "title" => $row['tipo_prenotazione'],
    "client_name" => empty($row['first_name']) ? '' : $row['first_name'] . ' ' . $row['last_name'],
    "client_email" => empty($row['user_email']) ? '' : $row['user_email'],
    "start" => $row['booking_date_time'],
    "end" => $row['booking_date_time'],
    "event_status" => $row['booking_status'],
    "laboratorio" => $row['laboratorio'],
    "open_popup" => true,
    "date_format" => "d-F-Y",
    "time_format" => "H:i",
    "prove" => $row['prove'],
    "matrice" => $row['matrice'],
    "numero_campioni" => $row['n_campione'],
    "manuale" => empty($row['text']) ? '' : $row['text'],
    "all_cas" => empty($row['all_case']) ? '' : $row['all_case'],
    "orders" => [],
  );
}
if(isset($_SESSION['lab_selected']) && !empty($_SESSION['lab_selected']) && $_SESSION['lab_selected'] != 0){
	$query_conoscitivi = "
	select 
		count(*) as count,ct_bookings.*,izler_laboratori.descrizione as laboratorio,group_concat(ct_bookings.order_id separator ',') as orders
	from 
		ct_bookings
		left join izler_laboratori on (
			id_laboratorio = izler_laboratori.id
		)
	where 
		tipo_prenotazione = 'conoscitivo' and ct_bookings.record_attivo = 1 and 
		booking_date_time >= '".$day." 00:00:00' and
		id_laboratorio = ".$_SESSION["lab_selected"]."
	GROUP	BY 
	booking_date_time";

  $query_chimici = "
    select 
      count(*) as count,ct_bookings.*,izler_laboratori.descrizione as laboratorio,group_concat(ct_bookings.order_id separator ',') as orders
    from
      ct_bookings
      left join izler_laboratori on (
        id_laboratorio = izler_laboratori.id
      )
    where
      tipo_prenotazione = 'chimici' and ct_bookings.record_attivo = 1 and 
      booking_date_time >= '".$day." 00:00:00' and
      id_laboratorio = ".$_SESSION["lab_selected"]."
    GROUP BY
    booking_date_time";
}else{
	$query_conoscitivi = "
	select 
		count(*) as count,ct_bookings.*,izler_laboratori.descrizione as laboratorio,group_concat(ct_bookings.order_id separator ',') as orders
	from 
		ct_bookings
		left join izler_laboratori on (
			id_laboratorio = izler_laboratori.id
		)
	where 
		booking_date_time >= '".$day." 00:00:00' and
		tipo_prenotazione = 'conoscitivo' and ct_bookings.record_attivo = 1
	GROUP	BY 
	booking_date_time";

  $query_chimici = "
	select 
		count(*) as count,ct_bookings.*,izler_laboratori.descrizione as laboratorio,group_concat(ct_bookings.order_id separator ',') as orders
	from 
		ct_bookings
		left join izler_laboratori on (
			id_laboratorio = izler_laboratori.id
		)
	where 
		booking_date_time >= '".$day." 00:00:00' and
		tipo_prenotazione = 'chimici' and ct_bookings.record_attivo = 1
	GROUP	BY 
	booking_date_time";
}

$result_conoscitivi = mysqli_query($conn, $query_conoscitivi);
while ($row = mysqli_fetch_assoc($result_conoscitivi)) {
	$color = '';
	switch ($row['tipo_prenotazione']) {
		case 'conoscitivo':
			$color = 'green';
			break;
		default:
			$color = 'black';
      break;
  }
  $events[] = array(
    "id" => $row['count'],
    "color_tag" => $color,
    "title" => $row['tipo_prenotazione'],
    "start" => $row['booking_date_time'],
    "end" => $row['booking_date_time'],
    "event_status" => 'UC',
    "laboratorio" => $row['laboratorio'],
    "open_popup" => true,
    "date_format" => "d-F-Y",
    "time_format" => "H:i",
    "prove" => '',
    "matrice" => '',
    "numero_campioni" => '',
    "manuale" => '',
    "all_cas" => '',
    "orders" => explode(',', $row['orders']),
  );
}
$result_chimici = mysqli_query($conn, $query_chimici);
while ($row = mysqli_fetch_assoc($result_chimici)) {
  $color = '';
  switch ($row['tipo_prenotazione']) {
    case 'chimici':
      $color = '#a65546';
      break;
    default:
      $color = 'black';
      break;
  }
  $events[] = array(
    "id" => $row['count'],
    "color_tag" => $color,
    "title" => $row['tipo_prenotazione'],
    "start" => $row['booking_date_time'],
    "end" => $row['booking_date_time'],
    "event_status" => 'UC',
    "laboratorio" => $row['laboratorio'],
    "open_popup" => true,
    "date_format" => "d-F-Y",
    "time_format" => "H:i",
    "prove" => '',
    "matrice" => '',
    "numero_campioni" => '',
    "manuale" => '',
    "all_cas" => '',
    "orders" => explode(',', $row['orders']),
  );
}
echo json_encode($events);
?>