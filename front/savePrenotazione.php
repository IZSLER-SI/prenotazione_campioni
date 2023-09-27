<?php
ob_start();
session_start();
ini_set("error_log", __DIR__ . DIRECTORY_SEPARATOR . "error.log"); // LOG FILE
include(dirname(dirname(__FILE__)) . '/header.php');
include(dirname(dirname(__FILE__)) . '/objects/class_connection.php');
include(dirname(dirname(__FILE__)) . '/objects/class_setting.php');
include(dirname(dirname(__FILE__)) . '/objects/class_booking.php');
include(dirname(dirname(__FILE__)) . '/objects/class_offbreaks.php');
include(dirname(dirname(__FILE__)) . '/objects/class.phpmailer.php');
include(dirname(dirname(__FILE__)) . '/utility.php');

$current_time = date("Y-m-d H:i:s");
$con = new prenotazione_campioni_db();
$conn = $con->connect();
$setting = new prenotazione_campioni_setting();
$setting->conn = $conn;
$booking = new prenotazione_campioni_booking();
$booking->conn = $conn;
$offbreaks = new prenotazione_campioni_offbreaks();
$offbreaks->conn = $conn;
$event_block            =    isset($_POST['event_block'])        ? $_POST['event_block']         : null;
$staff_id               =    isset($_POST['staff_id'])           ? $_POST['staff_id']            : null;
$booking_date_time      =    isset($_POST["booking_date_time"])  ? $_POST["booking_date_time"]   : null;
$tipo_prenotazione      =    isset($_POST["tipo_prenotazione"])  ? $_POST["tipo_prenotazione"]   : null;
$id_struttura           =    isset($_POST["strutttura"])         ? $_POST["strutttura"]          : null;
$matrice                =    isset($_POST["matrice"])            ? $_POST["matrice"]             : null;
$matrice_autocontrollo  =    isset($_POST["matrice_autocontrollo"])  ? $_POST["matrice_autocontrollo"]             : null;
$categoria_matrice      =    isset($_POST["ufficiale_matrice"])  ? $_POST["ufficiale_matrice"]   : null;
$prenotazione           =    isset($_POST["date_prenotazione"])  ? $_POST["date_prenotazione"]   : null;
$convocazione_perito    =    isset($_POST["convocazione_perito"]) ? $_POST["convocazione_perito"] : 0;
$unica_istanza          =    isset($_POST["unica_istanza"])      ? $_POST["unica_istanza"]       : 0;
$deleted = isset($_POST["del"])  ? $_POST["del"]   : null;
$id_d = isset($_POST['id_d']) ? $_POST['id_d'] : null;
$last_order_id = $booking->last_booking_id();
if ($last_order_id == "0" || $last_order_id == null) {
    $orderid = 1000;
} else {
    $orderid = $last_order_id + 1;
}
$user = 0;
if ($deleted == true) {
    $del_id = $id_d;
    send_email($del_id, $deleted);
    exit;
}
if (empty($event_block)) {
    if (!empty($_SESSION['ct_useremail'])) {
        $email = $_SESSION['ct_useremail'];
        $query = "select id from ct_users where `user_email` = '" . $email . "'";
        $result = mysqli_query($conn, $query);
        $user = mysqli_fetch_row($result)[0];
    } else {
        die();
    }
    if ($tipo_prenotazione == 'ufficiale') {
        $id_finalita = $_POST["ufficiale_finalita"];
        $booking->id_finalita = $id_finalita;
        $booking->n_campione = 1;
        $booking->convocazione_perito = $convocazione_perito;
        $booking->unica_istanza = $unica_istanza;
    } elseif ($tipo_prenotazione == 'chimici') {
        $id_finalita = $_POST["chimici_finalita"];
        $booking->id_finalita = $id_finalita;
        $booking->n_campione = 1;
        $booking->convocazione_perito = $convocazione_perito;
        $booking->unica_istanza = $unica_istanza;
        $booking->all_case = $_POST['note_chimici'];
    }elseif($tipo_prenotazione == 'conoscitivo'){
        $id_finalita                = $_POST["conoscitivo_finalita_inline"];
        $booking->id_finalita       = $id_finalita;
        $booking->n_campione        = 1;
        $booking->all_case          = $_POST['note_cono'];
    } else {
        $campione                   = $_POST['autocontrollo_campione'];
        $ncampioni                  = $_POST['autocontrollo_n_campione'];
        $specie                     = $_POST['autocontrollo_specie'];
        $booking->id_campione       = $campione;
        $booking->id_specie         = $specie;
        $booking->n_campione        = $ncampioni;
        $booking->all_case          = $_POST['all_case'];
    }
    $booking->client_id             = $user;
    $booking->order_date            = $current_time;
    $booking->booking_date_time     = $booking_date_time;
    $booking->booking_status        = 'C';
    $booking->lastmodify            = $current_time;
    $booking->read_status           = "U";
    $booking->staff_id              = $staff_id;
    $booking->order_id              = $orderid;
    $booking->id_struttura          = $id_struttura;
    $booking->matrice               = $tipo_prenotazione != 'autocontrollo' ? $matrice : $matrice_autocontrollo;
    $booking->id_categoria_matrice  = $categoria_matrice;
    $booking->tipo_prenotazione     = $tipo_prenotazione;
    $booking->booking_date_time     = $prenotazione;

    if ($tipo_prenotazione == 'ufficiale') {
        $query = "select id_izler_laboratorio from izler_mapping where id_izler_struttura = " . $id_struttura . " and id_izler_finalita = " . $id_finalita . " and record_attivo = 1 limit 1";
    } elseif($tipo_prenotazione == 'conoscitivo') {
      $query = "select id_izler_laboratorio from izler_mapping where id_izler_struttura = " . $id_struttura . " and id_izler_finalita = " . $id_finalita . " and record_attivo = 1 limit 1";
    } elseif($tipo_prenotazione == 'chimici') {
      $query = "select id_izler_laboratorio from izler_mapping where id_izler_struttura = " . $id_struttura . " and id_izler_finalita = " . $id_finalita . " and record_attivo = 1 limit 1";
    } else {
      $query = "select id_izler_laboratorio from izler_mapping where id_izler_struttura = " . $id_struttura . " and id_izler_campione = " . $campione . " and record_attivo = 1 limit 1";
    }
    $res = mysqli_query($conn, $query);
    $lab_id = mysqli_fetch_row($res)[0];
    $booking->id_laboratorio     = $_GET['lab'];
    $last_id = $booking->add_booking();
    log_data('Prenotazione '.$orderid, $booking->client_id);
    if (!empty($_POST['autocontrollo_prove'])) {
        foreach ($_POST['autocontrollo_prove'] as $prova) {
            $query = "INSERT INTO izler_prove_bookings (id_prova,id_ordine,peso_prova) VALUES (" . explode("-", $prova)[0] . "," . $orderid . "," . explode("-", $prova)[1] . ")";
            mysqli_query($conn, $query);
        }
    }
    send_email($last_id);
    //echo json_encode($booking);
} else if ($event_block == 1) {
    $selected_date = $_POST["selected_date"];
    $date_block = $_POST['slots'];
    $booking->client_id             = 0;
    $booking->order_date            = $current_time;
    $booking->booking_status        = 'ZZ';
    $booking->lastmodify            = $current_time;
    $booking->read_status           = "U";
    $booking->booking_date_time     = $selected_date . ' ' . $date_block[0];
    $booking->booking_date_time_end = $selected_date . ' ' . $date_block[1];
    $booking->staff_id              = 0;
    $booking->order_id              = $orderid;
    $booking->id_struttura          = 0;
    $booking->service_id            = 99;
    $booking->matrice               = '';
    $booking->tipo_prenotazione     = 'occupato';
    $booking->id_laboratorio        = getLab();
    if (isset($_POST['allday']) && $_POST['allday'] == 1) {
        $booking->tipo_prenotazione     = 'giornata';
        $query_check = "select id from ct_bookings where record_attivo = 1 and DATE(booking_date_time) = DATE('$selected_date') and id_laboratorio = " . getLab();
        $result_check = mysqli_query($conn, $query_check);
        if ($result_check->num_rows > 0) {
            echo 'error';
            exit;
        }
    }
    $booking->add_booking();
    log_data('Prenotazione '.$orderid, $booking->client_id);
    print_r($booking);
} else if ($event_block == 2) {
    $values                         = new \stdClass;
    $values->date                   = date($_POST['selected_date']);
    $values->n_campioni             = (int)$_POST['n_campioni'];
    $values->lab                    = getLab();
    $booking->insert_override_campioni($values);
} else if ($event_block == 22) {
    $values                         = new \stdClass;
    $values->date                   = date($_POST['selected_date']);
    $values->n_campioni             = (int)$_POST['n_campioni'];
    $values->lab                    = getLab();
    $booking->insert_override_campioni_conoscitivi($values);
} else if ($event_block == 33) {
  $values                         = new \stdClass;
  $values->date                   = date($_POST['selected_date']);
  $values->n_campioni             = (int)$_POST['n_campioni'];
  $values->lab                    = getLab();
  $booking->insert_override_campioni_chimici($values);
} else if ($event_block == 3) {
    $selected_date                  = $_POST["selected_date"];
    $query_check = "select id from ct_bookings where record_attivo = 1 and tipo_prenotazione = 'giornata' and DATE(booking_date_time) = DATE('$selected_date') and id_laboratorio = " . getLab();
    $result_check = mysqli_query($conn, $query_check);
    if ($result_check->num_rows > 0) {
        echo 'error';
        exit;
    }
    $date_block                     = $_POST['slots'];
    $booking->client_id             = 0;
    $booking->order_date            = $current_time;
    $booking->booking_status        = 'ZZ';
    $booking->lastmodify            = $current_time;
    $booking->read_status           = "U";
    $booking->booking_date_time     = $selected_date . ' ' . $date_block[0];
    $booking->booking_date_time_end = $selected_date . ' ' . $date_block[1];
    $booking->staff_id              = 0;
    $booking->order_id              = $orderid;
    $booking->id_struttura          = 0;
    $booking->service_id            = 88;
    $booking->matrice               = '';
    $booking->tipo_prenotazione     = 'manuale';
    $booking->text                  = $_POST['manuale'];
    $booking->id_laboratorio        = getLab();
    $booking->add_booking();
    log_data('Prenotazione '.$orderid, $booking->client_id);
} else if ($event_block == 4) {
    $selected_date                  = $_POST["selected_date"];
    $query_check = "select id from ct_bookings where record_attivo = 1 and tipo_prenotazione = 'giornata' and DATE(booking_date_time) = DATE('$selected_date') and id_laboratorio = " . getLab();
    $result_check = mysqli_query($conn, $query_check);
    if ($result_check->num_rows > 0) {
        echo 'error';
        exit;
    }
    $booking->client_id             = 0;
    $booking->order_date            = $current_time;
    $booking->booking_status        = 'ZZ';
    $booking->lastmodify            = $current_time;
    $booking->read_status           = "U";
    $booking->booking_date_time     = $selected_date;
    $booking->booking_date_time_end = $selected_date;
    $booking->staff_id              = 0;
    $booking->order_id              = $orderid;
    $booking->id_struttura          = 0;
    $booking->service_id            = 99;
    $booking->matrice               = '';
    $booking->tipo_prenotazione     = 'manuale';
    $booking->id_laboratorio        = getLab();
    $booking->text                  = $_POST['manuale'];
    $booking->n_campione            = (int)$_POST['n_campioni'];
    $booking->add_booking();
    log_data('Prenotazione '.$orderid, $booking->client_id);
    //$values = new \stdClass;
    //$values->date           = date($_POST['selected_date']);
    //$values->n_campioni     = (int)$_POST['n_campioni'];
    //$values->lab            = getLab();
    //$booking->insert_override_campioni($values);
} else {
    echo 'nothing to see here';
}


function getLab() {
    global $conn;
    if (isset($_SESSION['ct_adminid'])) {
        $id = $_SESSION['ct_adminid'];
    }
    if (isset($_SESSION['ct_accettazioneid'])) {
        $id = $_SESSION['ct_accettazioneid'];
    }
    if (isset($_SESSION['ct_laboratorioid'])) {
        $id = $_SESSION['ct_laboratorioid'];
    }
    $query = "select lab from ct_admin_info where id = " . $id;
    $res = mysqli_query($conn, $query);
    $lab_id = mysqli_fetch_row($res)[0];
    return $lab_id;
}
function getEvent($id, $del) {
    global $conn;
    if ($del != true) {
        $query = "select
            ct_bookings.*,
            izler_finalita.descrizione as finalita,
            izler_strutture.descrizione as struttura,
            matrice,
            izler_specie.descrizione as specie,
            izler_campione.descrizione as campione,
             izler_categorie_matrici.descrizione as categoria_matrice,
            group_concat(distinct izler_prove.descrizione separator '|') as prove,
            ct_users.*,
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
                left join izler_categorie_matrici on (
                    id_categoria_matrice = izler_categorie_matrici.id
                )
                where ct_bookings.id=" . $id . "
                group by ct_bookings.order_id";
    } else {
        $query = "select
            ct_bookings.*,
            izler_finalita.descrizione as finalita,
            izler_strutture.descrizione as struttura,
            matrice,
            izler_specie.descrizione as specie,
            izler_campione.descrizione as campione,
             izler_categorie_matrici.descrizione as categoria_matrice,
            group_concat(distinct izler_prove.descrizione separator '|') as prove,
            ct_users.*,
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
                left join izler_categorie_matrici on (
                    id_categoria_matrice = izler_categorie_matrici.id
                )
                where ct_bookings.order_id=" . $id . "
                group by ct_bookings.order_id";
    }

    $res    =   mysqli_query($conn, $query);
    $array  =   mysqli_fetch_assoc($res);
    return $array;
}
function send_email($id, $del = false) {
    if(empty($id)){
        header("HTTP/1.1 500 Internal Server Error");
        die();
    }
    $tipo_note_label = 'Allevamento/Caseificio';
    $con = new prenotazione_campioni_db();
    $conn = $con->connect();
    $setting = new prenotazione_campioni_setting();
    $setting->conn = $conn;
    $booking = new prenotazione_campioni_booking();
    $booking->conn = $conn;
    $offbreaks = new prenotazione_campioni_offbreaks();
    $offbreaks->conn = $conn;

    $event = getEvent($id, $del);
    if($event['tipo_prenotazione'] == 'chimici' || $event['tipo_prenotazione'] == 'conoscitivi'){
      $tipo_note_label = 'Note';
    }
    $lab_mail = $offbreaks->getLabInfo($event['id_laboratorio']);
    //$last_added_event = $id;


    $mail = new prenotazione_campioni_phpmailer();
    $mail->Host       = 'smtp.office365.com';
    $mail->Username   = 'applicazioniWeb@izsler.it';
    $mail->Password   = 'Dn28032014';
    $mail->Port       = '587';
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth   = true;
    $mail->CharSet    = "UTF-8";
    if(!empty($lab_mail) && !empty($lab_mail['email']) && $lab_mail['invio_mail'] == 1){
        $mail->addCC($lab_mail['email'], "Lab");
    }
    $to = $event['user_email'];
    $subject = "Prenotazione campione";
    if(getenv("AMBIENTE") != "master"){
        $subject = "TEST - ";
        $subject .= "Prenotazione campione";
    }
    $from = 'applicazioniWeb@izsler.it';
    if ($event['unica_istanza'] == 0 && $event['unica_istanza'] == null) {
        $event['unica_istanza'] = 'No';
    } else {
        $event['unica_istanza'] = 'Si';
    }

    if ($event['convocazione_perito'] == 0 && $event['convocazione_perito'] == null) {
        $event['convocazione_perito'] = 'No';
    } else {
        $event['convocazione_perito'] = 'Si';
    }
    if ($del != true) {
        $body = '
    <!doctype html>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
    <tr>
    <td align="center" style="padding: 40px 0 30px 0;">
    <img src="https://prenotazione-campioni.izsler.it/assets/images/logo2.png" style="display: block;" />
    </td>
    </tr>
    <tr>
    <td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
     <td>
      Gentile ' . $event['first_name'] . ' ' . $event['last_name'] . ',
    di seguito il dettaglio della sua prenotazione.<br>
    Per cancellare la prenotazione effettuata è necessario chiamare il laboratorio.
     </td>
    </tr>
    <tr>
     <td style="padding: 20px 0 30px 0;">
      
      <table>
    
       <tr>
        <td>CODICE PRENOTAZIONE:  </td>
            <td>
                <b>
                ' . $event['order_id'] . '
                </b>
            </td>
       </tr>
       <tr>
        <td>Tipo prenotazione:</td>
        <td>' . $event['tipo_prenotazione'] . '</td>
       </tr>  
       <tr>
        <td>Data appuntamento:</td>
        <td>' . date_format(date_create($event['booking_date_time']), 'd/m/Y H:i') . '</td>
       </tr>
    
       <tr>
        <td>Sede di consegna:</td>
        <td>' . $event['struttura'] . '</td>
       </tr>

       <tr>
       <td>Laboratorio:</td>
       <td>' . $event['laboratorio'] . '</td>
      </tr>
    
       <tr>
        <td>Finalità:</td>
        <td>' . $event['finalita'] . '</td>
       </tr>
    
       <tr>
       <td>Convocazione del perito:</td>
       <td>' . $event['convocazione_perito'] . '</td>
      </tr>

      <tr>
      <td>Istanza unica:</td>
      <td>' . $event['unica_istanza'] . '</td>
     </tr>
       <tr>
        <td>Specie:</td>
        <td>' . $event['specie'] . '</td>
       </tr>
    
       <tr>
        <td>Campione:</td>
        <td>' . $event['campione'] . '</td>
       </tr>
    
       <tr>
        <td>Numero campioni:</td>
        <td>' . $event['n_campione'] . '</td>
       </tr>
    
       <tr>
       <td>'.$tipo_note_label.'</td>
       <td>' . $event['all_case'] . '</td>
      </tr>

      <tr>
      <td>Categoria matrice:</td>
      <td>' . $event['categoria_matrice'] . '</td>
     </tr>
       <tr>
        <td>Matrice:</td>
        <td>' . $event['matrice'] . '</td>
       </tr>    
    
       <tr>
        <td>Prove:</td>
        <td>' . $event['prove'] . '</td>
       </tr>
      </table>
    </tr>
    <tr>
    <td bgcolor="#187d90" style="padding: 30px 30px 30px 30px;">
    <a href="https://prenotazione-campioni.izsler.it/"/>IZSLER - Prenotazione campioni</a>
    </td>
    </tr>
    </table>
    </body>
    </html>';
    } else {
      if($_POST['backoffice'] == true){
        $messaggio = 'la prenotazione é stata eliminata dal backoffice, di seguito il dettaglio della prenotazione eliminata.';
      }else{
        $messaggio = 'di seguito il dettaglio della prenotazione eliminata.';
      }
        $body = '
    <!doctype html>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
    <tr>
    <td align="center" style="padding: 40px 0 30px 0;">
    <img src="https://prenotazione-campioni.izsler.it/assets/images/logo2.png" style="display: block;" />
    </td>
    </tr>
    <tr>
    <td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
     <td>
      Gentile ' . $event['first_name'] . ' ' . $event['last_name'] . ',
    '.$messaggio.' 
     </td>
    </tr>
    <tr>
     <td style="padding: 20px 0 30px 0;">
      
      <table>
    
       <tr>
        <td>CODICE PRENOTAZIONE:  </td>
            <td>
                <b>
                ' . $event['order_id'] . '
                </b>
            </td>
       </tr>
       <tr>
        <td>Tipo prenotazione:</td>
        <td>' . $event['tipo_prenotazione'] . '</td>
       </tr>  
       <tr>
        <td>Data appuntamento:</td>
        <td>' . date_format(date_create($event['booking_date_time']), 'd/m/Y H:i') . '</td>
       </tr>
    
       <tr>
        <td>Sede di consegna:</td>
        <td>' . $event['struttura'] . '</td>
       </tr>
    
       <tr>
       <td>Laboratorio:</td>
       <td>' . $event['laboratorio'] . '</td>
      </tr>

       <tr>
        <td>Finalità:</td>
        <td>' . $event['finalita'] . '</td>
       </tr>
    
       <tr>
        <td>Specie:</td>
        <td>' . $event['specie'] . '</td>
       </tr>
    
       <tr>
        <td>Campione:</td>
        <td>' . $event['campione'] . '</td>
       </tr>
    
       <tr>
        <td>Numero campioni:</td>
        <td>' . $event['n_campione'] . '</td>
       </tr>
    
       <tr>
<td>'.$tipo_note_label.'</td>
       <td>' . $event['all_case'] . '</td>
      </tr>
      <tr>
      <td>Categoria matrice:</td>
      <td>' . $event['categoria_matrice'] . '</td>
     </tr>
       <tr>
        <td>Matrice:</td>
        <td>' . $event['matrice'] . '</td>
       </tr>
    
    
       <tr>
        <td>Prove:</td>
        <td>' . $event['prove'] . '</td>
       </tr>
      </table>
    </tr>
    <tr>
    <td bgcolor="#187d90" style="padding: 30px 30px 30px 30px;">
    <a href="https://prenotazione-campioni.izsler.it/"/>IZSLER - Prenotazione campioni</a>
    </td>
    </tr>
    </table>
    </body>
    </html>';
        $subject = 'Eliminazione prenotazione campioni';
        if (getenv("AMBIENTE") != "master") {
            $subject = "TEST - ";
            $subject .= 'Eliminazione prenotazione campioni';
        }
    }
    $mail->IsSMTP();
    $mail->SMTPDebug  = 0;
    $mail->IsHTML(true);
    $mail->From = $from;
    $mail->FromName = $from;
    $mail->Sender = $from;
    $mail->addAddress($to, "Admin");
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->send();
    $mail->ClearAllRecipients();
    $return_data = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
     <td>
      Gentile ' . $event['first_name'] . ' ' . $event['last_name'] . ',
    di seguito il dettaglio della sua prenotazione 
    <br>
    Per cancellare la prenotazione effettuata è necessario chiamare il laboratorio.
     </td>
    </tr>
    <tr>
     <td style="padding: 20px 0 30px 0;">
      
     <table>
    
     <tr>
      <td>CODICE PRENOTAZIONE:  </td>
          <td>
              <b>
              ' . $event['order_id'] . '
              </b>
          </td>
     </tr>
     <tr>
      <td>Tipo prenotazione:</td>
      <td>' . $event['tipo_prenotazione'] . '</td>
     </tr>  
     <tr>
      <td>Data appuntamento:</td>
      <td>' . date_format(date_create($event['booking_date_time']), 'd/m/Y H:i') . '</td>
     </tr>
  
     <tr>
      <td>Sede di consegna:</td>
      <td>' . $event['struttura'] . '</td>
     </tr>

     <tr>
     <td>Laboratorio:</td>
     <td>' . $event['laboratorio'] . '</td>
    </tr>
  
     <tr>
      <td>Finalità:</td>
      <td>' . $event['finalita'] . '</td>
     </tr>
  
     <tr>
     <td>Convocazione del perito:</td>
     <td>' . $event['convocazione_perito'] . '</td>
    </tr>

    <tr>
    <td>Istanza unica:</td>
    <td>' . $event['unica_istanza'] . '</td>
   </tr>
     <tr>
      <td>Specie:</td>
      <td>' . $event['specie'] . '</td>
     </tr>
  
     <tr>
      <td>Campione:</td>
      <td>' . $event['campione'] . '</td>
     </tr>
  
     <tr>
      <td>Numero campioni:</td>
      <td>' . $event['n_campione'] . '</td>
     </tr>
  
     <tr>
<td>'.$tipo_note_label.'</td>
     <td>' . $event['all_case'] . '</td>
    </tr>
    <tr>
    <td>Categoria matrice:</td>
    <td>' . $event['categoria_matrice'] . '</td>
   </tr>
     <tr>
      <td>Matrice:</td>
      <td>' . $event['matrice'] . '</td>
     </tr>
  
  
     <tr>
      <td>Prove:</td>
      <td>' . $event['prove'] . '</td>
     </tr>
    </table>';
    echo json_encode(['html' => $return_data, 'order' => $event['order_id']], JSON_UNESCAPED_UNICODE);
}
