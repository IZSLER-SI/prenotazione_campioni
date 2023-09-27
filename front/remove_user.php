<?php
header('Content-Type: text/json; charset=utf-8');
ob_start();
session_start();
try {
    include(dirname(dirname(__FILE__)) . '/header.php');
    include(dirname(dirname(__FILE__)) . '/objects/class_connection.php');
    include(dirname(dirname(__FILE__)) . '/objects/class_users.php');
    include(dirname(dirname(__FILE__)) . '/objects/class_requests.php');
    include(dirname(dirname(__FILE__)) . '/objects/class.phpmailer.php');
    include(dirname(dirname(__FILE__)) . '/utility.php');

    $database = new prenotazione_campioni_db();
    $conn = $database->connect();
    $database->conn = $conn;
    $user           = new prenotazione_campioni_users();
    $user->conn     = $conn;
    $request        = new prenotazione_campioni_requests();
    $request->conn  = $conn;
    $id_request     = $_POST['id'];
    $motivazione    = $_POST['motivazione'];
    $request_data = $request->readone($id_request);
    $result =     $request->update_removed($id_request);
    if (!empty($result)) {
        $response = array(
            'status' => 'success',
            'message' => 'Utente rimosso con successo'
        );
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Errore nella rimozione dell\'utente'
        );
    }
    $variabili = get_variabili();
    $email_dati = json_decode($variabili['email']['contenuto'], true);
    $mail = new prenotazione_campioni_phpmailer();
    $mail->Host       = 'smtp.office365.com';
    $mail->Username   = 'applicazioniWeb@izsler.it';
    $mail->Password   = 'Dn28032014';
    $mail->Port       = '587';
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth   = true;
    $mail->CharSet    = "UTF-8";

    $to      = $email_dati['to'];
    $to2     = $email_dati['cc'];
    $from    = $email_dati['from'];
    $subject = "Prenotazione campioni - richiesta di accesso rifiutata";
    if(getenv("AMBIENTE") != "master"){
        $subject = "TEST - ";
        $subject .= "Prenotazione campioni - richiesta di accesso rifiutata";
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
 <td style="padding: 20px 0 30px 0;">
  <table>
  <br>
  Utente ' . $request_data['email'] . ' rifiutato.
  <br>
  Motivazione: "'.$motivazione.'"
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
    $mail->SMTPDebug  = 0;
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

    $body   = '
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
Buongiorno, è stata rifiutata la richiesta per la creazione dell\'utente con la seguente motivazione: "'.$motivazione.'"
</td>
</tr>';
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
    $mail->addAddress($request_data['email'], "User");
    $mail->addCC($to2, "Info");

    $mail->send();
    log_data('Gestione utenti','Rimosso utente ' . $request_data['email']);
    echo json_encode($response);
} catch (\Exception $ex) {
    log_data('Gestione utenti','Errore nella rimozione dell\'utente: ' . $ex->getMessage());
    $response = array(
        'status' => 'error',
        'message' => 'Errore nella rimozione dell\'utente: ' . $ex->getMessage()
    );
    echo json_encode($response);
}
