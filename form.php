<?php

session_start();
//include utility functions

function checkSpid() {
  include 'utility.php';
  if (empty($_SESSION['ct_useremail'])) {
    if (strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') === 0) {
      $text = file_get_contents('php://input');
      $text = json_decode($text);
      file_put_contents('php://output', urlencode($text->fiscalnumber));
      exit;
    } else {
      if (empty($_REQUEST['data'])) {
        header('Location: /');
      }

      $path = dirname(__FILE__) . '/public.pem';
      $pub_key = file_get_contents($path);
      openssl_get_publickey($pub_key);
      if (!empty($_REQUEST['data'])) {
        log_data('Spid GET request', 'spid');
        $username = base64_decode($_REQUEST['data']);
        openssl_public_decrypt($username, $decrypted, $pub_key);
        $name = substr($decrypted, -16);
        $database = new prenotazione_campioni_db();
        $conn = $database->connect();
        $query = "select id,user_email from ct_users where codice_fiscale = '$name'";
        $result = mysqli_query($conn, $query);
        $user = mysqli_fetch_assoc($result);
        if (!empty($user) && !empty($name)) {
          $_SESSION['ct_useremail'] = $user['user_email'];
          log_data('Spid login',$user['user_email']);
        } else {
          echo "Errore, utente non autorizzato. Contattare l'assistenza per abilitare l'accesso <a href='mailto:accettazione@izsler.it'>accettazione@izsler.it</a>.";
          exit;
        }
      } else {
        echo 'Errore connessione spid';
        exit;
      }
    }
  }
}
function checkMobile() {
  $isMob = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile"));
  if ($isMob) {
    header('Location: /mobile.php');
  }
}

checkSpid();
checkMobile();
if (getenv("AMBIENTE") != "master") {
  echo '<div style="position:fixed;width:100%;background: #ff000061;height: 40px;top: 0px;color:white;text-align:center;font-size: 20px;"><b>AMBIENTE DI TEST</b></div>';
}
//$_SESSION['ct_useremail'] = 'r.kassame@invisiblefarm.com';
//$_SESSION['ct_usermail']='d.barbieri@invisiblefarm.com';
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <script>
        if(typeof window.history.pushState == 'function') {
            window.history.pushState({}, "Hide", '<?php echo $_SERVER['PHP_SELF'];?>');
        }
    </script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prenotazione campioni</title>
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap@5.11.3/main.min.css' rel='stylesheet' />
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'>
    <script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" crossorigin="" />
    <!-- JavaScript -->
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" crossorigin=""></script>
    <script src="./assets/js/main.js?<?php echo time(); ?>"></script>
    <link rel="stylesheet" href="main.css?<?php echo time(); ?>">
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

</head>

<body>
<div class='top-header-head'>
    <div class='logged'>
      <?php
      if (!empty($_SESSION['ct_useremail'])) {
        echo $_SESSION['ct_useremail'];
        echo '     |      <a href="#" onClick="esci()"> Esci</a>';
      }
      ?>
    </div>
</div>
<div class='top-middle-header'>
    <div class='logo'>
        <img src="assets/images/logo3.png">
        <img src="assets/images/logo2.png">
    </div>
    <div class='box'>
        <img src="assets/images/claim.png">
    </div>
</div>
<div class='top-bottom-header'>
</div>
<main>
    <div class="" id="grad1">
        <div class="row justify-content-center mt-0">
            <div class="col-11 text-center p-0 card">
                <div class="card px-0 pb-0 mb-3">
                    <div class="row">
                        <div class="col-md-12 mx-0">
                            <form id="msform">
                                <input type="hidden" name="tipo_prenotazione" id='tipo_prenotazione' value=''>
                                <input type="hidden" name="date_prenotazione" id='date_prenotazione' value=''>
                                <ul id="progressbar">
                                    <div id='ufficiale'>
                                        <li class="active" id="ufficiale_prenotazione">
                                            <p>Tipo prenotazione</p>
                                        </li>
                                        <li id="ufficiale_struttura">
                                            <p>Sede di consegna</p>
                                        </li>
                                        <li id="ufficiale_check_finalita">
                                            <p>Finalità</p>
                                        </li>
                                        <li id="ufficiale_matrice">
                                            <p>Matrice</p>
                                        </li>
                                        <li id="ufficiale_prova">
                                            <p>Prova/e</p>
                                        </li>
                                        <li id="ufficiale_calendario">
                                            <p>Calendario</p>
                                        </li>
                                        <li id="ufficiale_riepilogo">
                                            <p>Riepilogo</p>
                                        </li>
                                        <li id="ufficiale_conclusione">
                                            <p>Conclusione</p>
                                        </li>
                                    </div>
                                    <div id='chimici' style='display:none'>
                                        <li class="active" id="chimici_prenotazione">
                                            <p>Tipo prenotazione</p>
                                        </li>
                                        <li id="chimici_struttura">
                                            <p>Sede di consegna</p>
                                        </li>
                                        <li id="chimici_check_finalita">
                                            <p>Finalità</p>
                                        </li>
                                        <li id="chimici_matrice">
                                            <p>Matrice</p>
                                        </li>
                                        <li id="chimici_prova">
                                            <p>Prova/e</p>
                                        </li>
                                        <li id="chimici_calendario">
                                            <p>Calendario</p>
                                        </li>
                                        <li id="chimici_riepilogo">
                                            <p>Riepilogo</p>
                                        </li>
                                        <li id="chimici_conclusione">
                                            <p>Conclusione</p>
                                        </li>
                                    </div>
                                    <div id='autocontrollo' style='display:none'>
                                        <li class="active" id="autocontrollo_prenotazione">
                                            <p>Tipo prenotazione</p>
                                        </li>
                                        <li id="autocontrollo_struttura">
                                            <p>Sede di consegna </p>
                                        </li>
                                        <li id="autocontrollo_finalita">
                                            <p>Campione</p>
                                        </li>
                                        <li id="autocontrollo_matrice">
                                            <p>Matrice</p>
                                        </li>
                                        <li id="autocontrollo_prova">
                                            <p>Prova/e</p>
                                        </li>
                                        <li id="autocontrollo_calendario">
                                            <p>Calendario</p>
                                        </li>
                                        <li id="autocontrollo_riepilogo">
                                            <p>Riepilogo</p>
                                        </li>
                                        <li id="ufficiale_conclusione">
                                            <p>Conclusione</p>
                                        </li>
                                    </div>
                                    <div id='conoscitivo' style='display:none'>
                                        <li class="active" id="conoscitivo_prenotazione">
                                            <p>Tipo prenotazione</p>
                                        </li>
                                        <li id="conoscitivo_struttura">
                                            <p>Sede di consegna </p>
                                        </li>
                                        <li id="conoscitivo_check_finalita">
                                            <p>Finalità</p>
                                        </li>
                                        <li id="conoscitivo_matrice">
                                            <p>Matrice</p>
                                        </li>
                                        <li id="conoscitivo_prova">
                                            <p>Prova/e</p>
                                        </li>
                                        <li id="conoscitivo_calendario">
                                            <p>Calendario</p>
                                        </li>
                                        <li id="conoscitivo_riepilogo">
                                            <p>Riepilogo</p>
                                        </li>
                                        <li id="conoscitivo_conclusione">
                                            <p>Conclusione</p>
                                        </li>
                                    </div>
                                </ul>
                                <fieldset>
                                    <div class="form-card">
                                        <div class="radio-group">
                                            <div id='radio_ufficiale' class='radio shadow-lg button-card1 button-card' data-value="ufficiale">
                                                <div class="row">
                                                    <div class="col-7">
                                                        MICROBIOLOGICO
                                                        <br>
                                                        <br>
                                                        UFFICIALI
                                                        <br>
                                                        <u>UNICA ISTANZA</u>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id='radio_chimici' class='radio shadow-lg button-card4 button-card' data-value="chimici">
                                                <div class="row">
                                                    <div class="col-7">
                                                        CHIMICO
                                                        <br>
                                                        <br>
                                                        UFFICIALI
                                                        <br>
                                                        <u>UNICA ISTANZA</u>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id='radio_conoscitivi' class='radio shadow-lg button-card3 button-card' data-value="conoscitivo">
                                                <div class="row">
                                                    <div class="col-7">
                                                        MICROBIOLOGICO
                                                        <br>
                                                        <br>
                                                        UFFICIALI
                                                        <br>
                                                        <u>CONOSCITIVI</u>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id='radio_autocontrollo' class='radio shadow-lg button-card2 button-card'
                                                 data-value="autocontrollo">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <br>
                                                        AUTOCONTROLLO
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button style='display:none' type="button" id='first' onclick='generateStrutture()' name="next" class="next btn btn-primary">Avanti</button>
                                </fieldset>
                                <fieldset>
                                    <div class="form-card">
                                        <div class='map-select shadow-lg'>
                                            <label for="struttura">Selezionare dove verranno conferiti i campioni *</label>
                                            <select onselect="changeView()" class="form-control" id="autocontrollo_strutttura" name='strutttura'>
                                            </select>
                                        </div>
                                        <div id='map' class='shadow-lg'>

                                        </div>
                                    </div>
                                    <button type="button" name="previous" class="btn btn-secondary previous">Indietro</button>
                                    <button type="button" id='second' onclick='generateFinalita();generateSpecie();generateCampione()' name="next" class="next btn btn-primary">Avanti</button>
                                </fieldset>
                                <fieldset>
                                    <div class="form-card" id='ufficiale_finalita_form'>
                                        <label for="finalita">Selezionare la finalità *</label>
                                        <select class="form-control" id="ufficiale_finalita" name="ufficiale_finalita">
                                        </select>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="unica_istanza" value='1' name="unica_istanza" checked>
                                            <label for="unica_istanza">Unica istanza</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="convocazione_perito" value='1' name="convocazione_perito" checked>
                                            <label for="convocazione_perito">Convocazione del perito</label>
                                        </div>
                                    </div>
                                    <div class="form-card" id='conoscitivo_finalita_form'>
                                        <label for="finalita">Selezionare la finalità *</label>
                                        <select class="form-control" id="conoscitivo_finalita_inline" name="conoscitivo_finalita_inline">
                                        </select>
                                        <label for='note_cono'>Note</label>
                                        <input id='note_cono' class='form-control' name='note_cono' type='text'>
                                    </div>
                                    <div class="form-card" id='chimici_finalita_form'>
                                        <label for="finalita">Selezionare la finalità *</label>
                                        <select class="form-control" id="chimici_finalita" name="chimici_finalita">
                                        </select>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="unica_istanza_chimici" value='1' name="unica_istanza" checked>
                                            <label for="unica_istanza_chimici">Unica istanza</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="convocazione_perito_chimici" value='1' name="convocazione_perito" checked>
                                            <label for="convocazione_perito_chimici">Convocazione del perito</label>
                                        </div>
                                        <label for='note_chimici'>Note</label>
                                        <input id='note_chimici' class='form-control' name='note_chimici' type='text'>
                                    </div>
                                    <div class="form-card" id='ufficiale_campioni_form' style="display:none">
                                        <label for="struttura">Seleziona il tipo di campione *</label>
                                        <select class="form-control" name='autocontrollo_campione' id="autocontrollo_campione">
                                        </select>
                                        <label for="campioni">Numero campioni *</label>
                                        <input id='autocontrollo_n_campione' class='form-control ' name='autocontrollo_n_campione' type='text'>

                                        <div id='microbiologia_alimenti' style="display:none">
                                            <label for='all_case'>Note</label>
                                            <input id='all_case' class='form-control ' name='all_case' type='text'>
                                            <label for="matrice">Materiale/matrice *</label>
                                            <textarea id="autocontrollo_matrice_materiale" name="matrice_autocontrollo" class="form-control" rows="2" placeholder="Inserire la matrice o il materiale da conferire"></textarea>
                                            <div class ='alert alert-success m-2'>
                                                Si chiede di allegare SEMPRE, alla consegna dei campioni, il verbale di prelievo e l’informativa PG 00/019 V.<br>
                                                Link utili per consultare e scaricare la documentazione in uso presso IZSLER:
                                                <br> INFORMATIVA PG 00/019 V: <a href="https://www.izsler.it/wp-content/uploads/sites/2/2022/11/19V.000.pdf" target="_blank">https://www.izsler.it/wp-content/uploads/sites/2/2022/11/19V.000.pdf</a>
                                                <br>Conferimento campioni ISTRUZIONI E MODULISTICA: <a href="https://www.izsler.it/chi-siamo/per-chi-e-con-chi-lavoriamo/qualita/conferimento-campioni-privati-modulistica-documentazione-informativa/" target="_blank">
                                                    https://www.izsler.it/chi-siamo/per-chi-e-con-chi-lavoriamo/qualita/conferimento-campioni-privati-modulistica-documentazione-informativa/</a>
                                            </div>
                                        </div>

                                        <div id='default_autocontrollo' style="display:block">
                                            <label for="specie">Selezionare la specie *</label>
                                            <select class="form-control" id="autocontrollo_specie" name="autocontrollo_specie">
                                            </select>
                                            <label for='all_case'>Allevamento/caseificio dei campioni</label>
                                            <input id='all_case' class='form-control ' name='all_case' type='text'>
                                        </div>

                                    </div>
                                    <button type="button" name="previous" class="btn btn-secondary previous">Indietro</button>
                                    <button type="button" id='third' onclick='generateMatrice()' name="next" class="next btn btn-primary">Avanti</button>
                                </fieldset>
                                <fieldset>
                                    <div class="form-card">
                                        <label for="matrice">Seleziona la categoria desiderata *</label>
                                        <select class="form-control" name='ufficiale_matrice' id="ufficiale_matrice_select">
                                        </select>
                                        <label id="autocontrollo_materiale_label" for="matrice">Materiale/matrice *</label>
                                        <textarea id="autocontrollo_materiale" name="matrice" class="form-control" rows="2" placeholder="Inserire la matrice o il materiale da conferire"></textarea>
                                    </div>
                                    <button type="button" id='fourth_back' name="previous" class="btn btn-secondary previous">Indietro</button>
                                    <button type="button" id='fourth' name="next" onclick='generateProva()' class="next btn btn-primary">Avanti</button>
                                </fieldset>
                                <fieldset>
                                    <div class="form-card">
                                        <label for="prove">Selezionare le prove *</label>
                                        <div class="form-group row" id="prove_rows">
                                        </div>
                                    </div>
                                    <button type="button" id='fifth_back' name="previous" class="btn btn-secondary previous">Indietro</button>
                                    <button type="button" id='fifth' name="next" onclick='delay()' class="next btn btn-primary">Avanti</button>
                                </fieldset>
                                <fieldset>
                                    <div class="form-card">
                                        <div class='main'>
                                            <h5>
                                                <center>Per data di prenotazione si intende la data di esecuzione delle analisi.</center>
                                            </h5>
                                            <div id='lab_selected'></div>
                                            <div id='sede_selected'></div>
                                            <div id='calendar'></div>
                                        </div>
                                    </div>
                                    <button type="button" name="previous" class="btn btn-secondary previous">Indietro</button>
                                    <button type="button" id='sixth' name='next' class="next btn btn-primary">Avanti</button>
                                </fieldset>
                                <fieldset>
                                    <div class="form-card">
                                        <div id='riepilogo'></div>
                                        <div id='return_content' style='display:none;'></div>
                                        <div class="confirm">Vuoi confermare la prenotazione?
                                        </div>
                                    </div>
                                    <button type="button" name="previous" class="btn btn-secondary previous">Indietro</button>
                                    <button type="button" name='end' class="end btn btn-primary">Conferma</button>
                                </fieldset>
                                <fieldset id="concluded" class='btn-group-vertical'>
                                    <div class="form-card">
                                        <div class="row">
                                            <div class="col">
                                                <img src="assets/images/img-conclusione.svg">
                                                <div class="confirm2">Prenotazione effettuata con successo
                                                </div>
                                            </div>
                                            <div class="col-6 vertical">
                                                <button type="button" onclick="print_elem('return_content')" class="btn-line btn btn-main">Stampa prenotazione</button>
                                                <button id='nine' type="button" onclick="back()" class="btn-line btn btn-main">Replica la prenotazione</button>
                                                <button type="button" onclick="reload()" class="btn-line btn btn-main">Compila una nuova prenotazione</button>
                                                <button type="button" onclick="remove()" class="btn-line btn btn-main">Cancella la prenotazione</button>
                                                <button type="button" onclick="exit()" class="btn-line btn btn-main">Esci</button>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<div id="mySidenav" class="sidenav">
    <a href="#" onclick="riepilogo_sidebar();return false;" id="about">Riepilogo prenotazione</a>
</div>
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" id='riepilogo_data'>
            </div>
            <div class="modal-footer">
                <button type="button" id='close' class="btn btn-s btn-secondary" data-dismiss="modal">chiudi</button>
            </div>
        </div>
    </div>
</div>


<div id="mySidenav2" class="sidenav2">
    <a href="#" onclick="sidebar();return false;" id="about2">VIDEO GUIDA PER L’UTILIZZO Lombardia</a>
</div>

<div id="mySidenav3" class="sidenav3">
    <a href="#" onclick="sidebar2();return false;" id="about">VIDEO GUIDA PER L’UTILIZZO Emilia-Romagna</a>
</div>


<div class="modal fade" id="modal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div style="padding:52.84% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/715169086?h=88e1635a4f&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;" title="Prenotazione campioni - Utente esterno"></iframe></div>
                <script src="https://player.vimeo.com/api/player.js"></script>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_er" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div style="padding:52.84% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/724414195?h=d2cfdb5f1a&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;" title="Prenotazione campioni - Utente esterno Emilia-Romagna"></iframe></div>
                <script src="https://player.vimeo.com/api/player.js"></script>
            </div>
        </div>
    </div>
</div>
<footer>
    <a href="https://www.izsler.it/">Copyright 2022 by IZSLER </a> |
    <a href="http://www.izsler.it/privacy/">Privacy</a> |
    <a href="http://www.izsler.it/note-legali/">Note legali</a>
</footer>
</body>

</html>