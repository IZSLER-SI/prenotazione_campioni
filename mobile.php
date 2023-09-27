<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta http-equiv="cache-control" content="max-age=0" />
  <meta http-equiv="cache-control" content="no-cache" />
  <meta http-equiv="expires" content="0" />
  <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
  <meta http-equiv="pragma" content="no-cache" />
  <title>Prenotazione campioni</title>
  <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap@5.11.3/main.min.css' rel='stylesheet' />
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet'>
  <script src="https://code.jquery.com/jquery-3.6.3.min.js"
    integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" crossorigin=""></script>
  <link rel="stylesheet" href="main_mobile.css?<?php echo time(); ?>">
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
  <script src="./assets/js/main_mobile.js?<?php echo time(); ?>"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css"
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
  <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css" />

</head>

<body>
  <nav class="navbar navbar-light bg-light">
    <div class="container-fluid">
      <img src="./assets/images/logo3.png" alt="" height="40px" class="d-inline-block align-text-top">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" onclick="riepilogo_sidebar();return false">Riepilogo</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Video guida</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" onClick="esci()"> Esci</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class='top-bottom-header'></div>
  <form id="msform">
    <input type="hidden" name="tipo_prenotazione" id='tipo_prenotazione' value=''>
    <input type="hidden" name="date_prenotazione" id='date_prenotazione' value=''>
  <div>
    <!-- -->
    <div class="tab tab-form">
      <div class='main-title'>
        <div class='round'>
          <img src="./assets/images/icons/tipo-prenotazione.svg" alt="">
        </div>
        <h1>Tipo prenotazione</h1>
      </div>
      <div class="form-card">
        <div class="radio-group">
          <div id="radio_ufficiale" class="radio shadow-lg button-card" data-value="ufficiale">
            <div class="button-card1">
              <div class="col-7">
              Prenotazione campioni ufficiali Unica Istanza
              </div>
            </div>
          </div>
          <div id="radio_autocontrollo" class="radio shadow-lg button-card" data-value="conoscitivo">
            <div class="button-card2 ">
              <div class="col-7">
              Prenotazione campioni ufficiali conoscitivi
              </div>
            </div>
          </div>
          <div id="radio_autocontrollo" class="radio shadow-lg button-card" data-value="autocontrollo">
            <div class="button-card3 ">
              <div class="col-7">
              Prenotazione campioni autocontrollo
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="progresso">
        <img src="./assets/images/progresso1.svg" alt="">
      </div>
      <button style='display:none' type="button" id='first' onclick='generateStrutture()' name="next" class="next btn btn-primary">Avanti</button>
    </div>

    <!-- -->
    <div class='tab tab-form'>
      <div class='main-title'>
        <div class='round'>
          <img src="./assets/images/icons/sede-consegna.svg" alt="">
        </div>
        <h1>Sede di consegna</h1>
      </div>
      <div class="map-select-container form-card">
        <div class='map-select shadow-lg'>
          <label for="struttura">Selezionare dove verranno conferiti i campioni</label>
          <select onselect="changeView()" class="form-control" id="autocontrollo_strutttura" name='strutttura'>
          </select>
        </div>
        <div id='map' class='shadow-lg'>
        </div>
      </div>
      <div class='button_container'>
        <button type="button" name="previous" class="btn btn-secondary previous">Indietro</button>
        <button type="button" id='second' onclick='generateFinalita();generateSpecie();generateCampione()' name="next" class="next btn btn-primary">Avanti</button>
      </div>
      <div class="progresso">
        <img src="./assets/images/progresso2.svg" alt="">
      </div>
    </div>
    <!-- -->
    <div class='tab tab-form'>
      <div class='main-title' id='ufficiale_finalita_round'>
        <div class='round'>
          <img src="./assets/images/icons/finalita.svg" alt="">
        </div>
        <h1>Finalità</h1>
      </div>
      <div class='main-title' id='ufficiale_campioni_round'  style="display:none">
        <div class='round'>
          <img src="./assets/images/icons/campione.svg" alt="">
        </div>
        <h1>Campione</h1>
      </div>
      <div class="form-card" id='ufficiale_finalita_form'>
        <label for="finalita">Selezionare la finalità</label>
        <select class="form-control" id="ufficiale_finalita" name="ufficiale_finalita">
        </select>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="unica_istanza" value='1' name="unica_istanza" checked>
          <label for="unica_istanza">Unica istanza</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="convocazione_perito" value='1' name="convocazione_perito"
            checked>
          <label for="convocazione_perito">Convocazione del perito</label>
        </div>
      </div>
      <div class="form-card" id='conoscitivo_finalita_form'>
        <label for="finalita">Selezionare la finalità</label>
        <select class="form-control" id="conoscitivo_finalita" name="conoscitivo_finalita">
        </select>
        <label for='note_cono'>Note</label>
        <input id='note_cono' class='form-control' name='note_cono' type='text'>
      </div>
      <div class="form-card" id='ufficiale_campioni_form' style="display:none">
                      <label for="struttura">Seleziona il tipo di campione</label>
                      <select class="form-control" name='autocontrollo_campione' id="autocontrollo_campione">
                      </select>
                      <label for="campioni">Numero campioni</label>
                      <input id='autocontrollo_n_campione' class='form-control ' name='autocontrollo_n_campione' type='text'>
                      
                      <div id='microbiologia_alimenti' style="display:none">
                        <label for='all_case'>Note</label>
                        <input id='all_case' class='form-control ' name='all_case' type='text'>
                        <label for="matrice">Materiale/matrice</label>
                        <textarea id="autocontrollo_matrice_materiale" name="matrice_autocontrollo" class="form-control" rows="2" placeholder="Inserire la matrice o il materiale da conferire"></textarea>
                      <div class ='alert alert-success m-2'>
                      “Si chiede di allegare SEMPRE ad ogni campionamento il verbale di prelievo e l’informativa PG 00/019 V.
                      E’ possibile consultare e scaricare la documentazione in uso presso IZSLER al link:<br>
<a href="https://www.izsler.it/chi-siamo/per-chi-e-con-chi-lavoriamo/qualita/conferimento-campioni-privati-modulistica-documentazione-informativa/">https://www.izsler.it/chi-siamo/per-chi-e-con-chi-lavoriamo/qualita/conferimento-campioni-privati-modulistica-documentazione-informativa/</a>, è possibile consultare e scaricare la documentazione inerente il CONFERIMENTO CAMPIONI e la MODULISTICA in uso presso IZSLER.”
    </div>
                      </div>
                      
                      <div id='default_autocontrollo' style="display:block">
                        <label for="specie">Selezionare la specie</label>
                        <select class="form-control" id="autocontrollo_specie" name="autocontrollo_specie">
                        </select>
                        <label for='all_case'>Allevamento/caseificio dei campioni</label>
                        <input id='all_case' class='form-control ' name='all_case' type='text'>
                      </div>
                    
                    </div>
      <div class='button_container'>
        <button type="button" name="previous" class="btn btn-secondary previous">Indietro</button>
        <button type="button" id='third' onclick='generateMatrice()' name="next" class="next btn btn-primary">Avanti</button>
      </div>
      <div class="progresso">
        <img src="./assets/images/progresso3.svg" alt="">
      </div>
    </div>
    <!-- -->
    <div class="tab tab-form">
      <div class='main-title'>
        <div class='round'>
          <img src="./assets/images/icons/matrice.svg" alt="">
        </div>
        <h1>Matrice</h1>
      </div>
      <div class="form-card">
        <label for="matrice">Seleziona la categoria desiderata</label>
        <select class="form-control" name='ufficiale_matrice' id="ufficiale_matrice_select">
        </select>
        <label for="matrice">Materiale/matrice</label>
        <textarea id="autocontrollo_materiale" name="matrice" class="form-control" rows="2"
          placeholder="Inserire la matrice o il materiale da conferire"></textarea>
      </div>
      <div class='button_container'>
        <button type="button" name="previous" class="btn btn-secondary previous">Indietro</button>
        <button type="button" id='fourth' name="next" onclick='generateProva()' class="next btn btn-primary">Avanti</button>
      </div>
      <div class="progresso">
        <img src="./assets/images/progresso4.svg" alt="">
      </div>
    </div>
    <!-- -->
    <div class="tab tab-form">
      <div class='main-title'>
        <div class='round'>
          <img src="./assets/images/icons/prova.svg" alt="">
        </div>
        <h1>Prove</h1>
      </div>
      <div class="form-card">
        <label for="prove">Selezionare le prove</label>
        <div class="form-group row" id="prove_rows">
        </div>
      </div>
      <div class='button_container'>
        <button type="button" name="previous" class="btn btn-secondary previous">Indietro</button>
        <button type="button" id='fifth' name="next" onclick='delay()' class="next btn btn-primary">Avanti</button>
      </div>
      <div class="progresso">
        <img src="./assets/images/progresso5.svg" alt="">
      </div>
    </div>
    <!-- -->
    <div class="tab tab-form">
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
      <div class='button_container'>
        <button type="button" name="previous" class="btn btn-secondary previous">Indietro</button>
        <button type="button" id='sixth' name='next' class="next btn btn-primary">Avanti</button>
      </div>
      <div class="progresso">
        <img src="./assets/images/progresso6.svg" alt="">
      </div>
    </div>
    <!-- -->
    <div class="tab tab-form">
      <div class="form-card">
        <div class='main-title'>
          <div class='round'>
            <img src="./assets/images/icons/riepilogo.svg" alt="">
          </div>
          <h1>Riepilogo</h1>
        </div>
        <div id='riepilogo'></div>
        <div id='return_content' style='display:none;'></div>
        <div class="confirm">Vuoi confermare la prenotazione?
        </div>
        <div class='button_container'>
          <button type="button" name="previous" class="btn btn-secondary previous">Indietro</button>
          <button type="button" name='end' class="end btn btn-primary">Conferma</button>
        </div>
        <div class="progresso">
          <img src="./assets/images/progresso7.svg" alt="">
        </div>
      </div>
    </div>
    <div id="concluded" class='tab tab-form btn-group-vertical'>
      <div class="form-card">
        <div class="row">
          <div class="col">
            <img src="assets/images/img-conclusione.svg">
            <div class="confirm2">Prenotazione effettuata con successo
            </div>
          </div>
          <div class="vertical">
            <button type="button" onclick="print_elem('return_content')" class="btn-line btn btn-main">Stampa prenotazione</button>
            <button id='nine' type="button" onclick="back()" class="btn-line btn btn-main">Replica la prenotazione</button>
            <button type="button" onclick="reload()" class="btn-line btn btn-main">Compila una nuova prenotazione</button>
            <button type="button" onclick="remove()" class="btn-line btn btn-main">Cancella la prenotazione</button>
            <button type="button" onclick="exit()" class="btn-line btn btn-main">Esci</button>
          </div>
        </div>
      </div>
    </div>
    </div>
  </form>
    <!-- -->
    <div class="modal fade" id="modal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <div style="padding:52.84% 0 0 0;position:relative;"><iframe
                src="https://player.vimeo.com/video/715169086?h=88e1635a4f&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479"
                frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen
                style="position:absolute;top:0;left:0;width:100%;height:100%;"
                title="Prenotazione campioni - Utente esterno"></iframe></div>
            <script src="https://player.vimeo.com/api/player.js"></script>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal_er" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <div style="padding:52.84% 0 0 0;position:relative;"><iframe
                src="https://player.vimeo.com/video/724414195?h=d2cfdb5f1a&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479"
                frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen
                style="position:absolute;top:0;left:0;width:100%;height:100%;"
                title="Prenotazione campioni - Utente esterno Emilia-Romagna"></iframe></div>
            <script src="https://player.vimeo.com/api/player.js"></script>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
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
</body>
<footer>
  <a href="https://www.izsler.it/">Copyright</a> |
  <a href="https://www.izsler.it/privacy/">Privacy</a> |
  <a href="https://www.izsler.it/note-legali/">Note legali</a>
</footer>

</html>
