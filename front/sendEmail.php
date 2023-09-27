<?php
ob_start();
session_start();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode('error');
    exit;
}
//controllo anti stamp bot form dei contatti
if (!empty($_POST['website'])) {
    echo json_encode('error');
    die();
}
$email = isset($_POST['email']) ? $_POST['email'] : null;
$nome = isset($_POST['nome']) ? $_POST['nome'] : null;
$cognome = isset($_POST['cognome']) ? $_POST['cognome'] : null;
$accesso = isset($_POST['accesso']) ? $_POST['accesso'] : null;
$nt = isset($_POST['nt']) ? $_POST['nt'] : null;
$cf = isset($_POST['cf']) ? strtoupper($_POST['cf']) : null;
$lab = isset($_POST['lab']) ? $_POST['lab'] : null;
$ente = isset($_POST['ente']) ? $_POST['ente'] : null;
if (
    $email == null ||
    $nome == null ||
    $cognome == null ||
    $accesso == null ||
    $cf == null ||
    $nt == null ||
    $ente == null
) {
    echo json_encode('error');
    die();
}
include(dirname(dirname(__FILE__)) . '/header.php');
include(dirname(dirname(__FILE__)) . '/objects/class_connection.php');
include(dirname(dirname(__FILE__)) . '/objects/class_setting.php');
include(dirname(dirname(__FILE__)) . '/objects/class_booking.php');
include(dirname(dirname(__FILE__)) . '/objects/class_users.php');
include(dirname(dirname(__FILE__)) . '/objects/class.phpmailer.php');
$con = new prenotazione_campioni_db();
$conn = $con->connect();
$user = new prenotazione_campioni_users();
$user->conn = $conn;
$check = $user->check_cf_existing($cf);
if (!empty($check)) {
    echo json_encode('error');
    exit;
}
$user->insert_user_request([
    'email' => $email,
    'pwd' => '',
    'nome' => addslashes($nome),
    'cognome' => addslashes($cognome),
    'telefonno' => $nt,
    'codice_fiscale' => $cf,
    'lab' => 0,
    'role' => $accesso,
    'ente' => $ente,
]);
include(dirname(dirname(__FILE__)) . '/utility.php');
$variabili = get_variabili();
$email_dati = json_decode($variabili['email']['contenuto'], true);
$mail = new prenotazione_campioni_phpmailer();
$mail->Host = 'smtp.office365.com';
$mail->Username = 'applicazioniWeb@izsler.it';
$mail->Password = 'Dn28032014';
$mail->Port = '587';
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->CharSet = "UTF-8";

$to = $email_dati['to'];
$to2 = $email_dati['cc'];
$from = $email_dati['from'];
$subject = "Prenotazione campioni - richiesta di accesso";
if (getenv("AMBIENTE") != "master") {
    $subject = "TEST - ";
    $subject .= "Prenotazione campioni - richiesta di accesso";
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
Buongiorno, è stata inviata una richiesta di accesso da parte di un utente.<br>
Di seguito la tabella con il riepilogo dei dati forniti.
 </td>
</tr>
<tr>
 <td style="padding: 20px 0 30px 0;">
  <table>
   <tr>
    <td>Nome:</td>
    <td>' . $nome . '</td>
   </tr>  
   <tr>
    <td>Cognome:</td>
    <td>' . $cognome . '</td>
   </tr>
   <tr>
    <td>Ente/Azienda:</td>
    <td>' . $ente . '</td>
   </tr>
   <tr>
    <td>Email:</td>
    <td>' . $email . '</td>
   </tr>
   <tr>
   <td>Tipo di accesso:</td>
   <td>' . $accesso . '</td>
  </tr>
   <tr>
    <td>Numero di telefono:</td>
    <td>' . $nt . '</td>
   </tr>
   <tr>
   <td>Codice fiscale:</td>
   <td>' . $cf . '</td>
  </tr>';
if ($lab != null) {
    $body .= '<tr>
   <td>Laboratorio:</td>
   <td>' . $lab . '</td>';
}
$body .= '</table>
  <br>
  <br>
  Per confermare l\'abilitazione dell\'utente rispondete inviando una mail a: prenotazione_campioni@invisiblefarm.it
</tr>
<tr>
<td bgcolor="#187d90" style="padding: 30px 30px 30px 30px;">
<a href="https://prenotazione-campioni.izsler.it/"/>IZSLER - Prenotazione campioni</a>
</td>
</tr>
</table>
</body>
</html>';
$mail->IsSMTP();
$mail->SMTPDebug = 0;
$mail->IsHTML(true);
$mail->From = $from;
$mail->FromName = $from;
$mail->Sender = $from;
$mail->addAddress($to, "Admin");
$mail->addCC($to2, "Info");
$mail->Subject = $subject;
$mail->Body = $body;
$mail->send();
$mail->ClearAllRecipients();

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
Buongiorno, è stata inviata correttamente la richiesta per la creazione dell\'utenza.<br>
Di seguito la tabella con il riepilogo dei dati forniti.
</td>
</tr>
<tr>
 <td style="padding: 20px 0 30px 0;">
  <table>
   <tr>
    <td>Nome:</td>
    <td>' . $nome . '</td>
   </tr>  
   <tr>
    <td>Cognome:</td>
    <td>' . $cognome . '</td>
   </tr>
   <tr>
    <td>Ente/Azienda:</td>
    <td>' . $ente . '</td>
   </tr>
   <tr>
    <td>Email:</td>
    <td>' . $email . '</td>
   </tr>
   <tr>
   <td>Tipo di accesso:</td>
   <td>' . $accesso . '</td>
  </tr>
   <tr>
    <td>Numero di telefono:</td>
    <td>' . $nt . '</td>
   </tr>
   <tr>
   <td>Codice fiscale:</td>
   <td>' . $cf . '</td>
  </tr>';
if ($lab != null) {
    $body .= '<tr>
   <td>Laboratorio:</td>
   <td>' . $lab . '</td>';
}
$body .= '</table>
</tr>
<tr>
<td bgcolor="#187d90" style="padding: 30px 30px 30px 30px;">
<a href="https://prenotazione-campioni.izsler.it/"/>IZSLER - Prenotazione campioni</a>
</td>
</tr>
</table>
</body>
</html>';
$mail->Body = $body;
$mail->AddAddress($email);
$mail->addBCC($to2);

$mail->send();

echo json_encode('done');