<link href='https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap@5.8.0/main.min.css' rel='stylesheet' />
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.8.0/main.min.css' rel='stylesheet'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.8.0/main.min.js'></script>
<link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'>
<script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
<?php
include 'utility.php';
$variabili = get_variabili();
if(getenv("AMBIENTE") != "master"){
	echo '<div style="position:fixed;width:100%;background: #ff000061;height: 40px;top: 0px;color:white;text-align:center;font-size: 20px;"><b>AMBIENTE DI TEST</b></div>';
}
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script>
    //sidebar function
    function sidebar() {
        $('#modal').modal('show')
    }

    function sidebar2() {
        $('#modal_er').modal('show')
    }

    function contact() {
        $('#modal_form').modal('show')
    }

    function send() {
        var form = $('#form_email').serialize()
        if ($('#email').val() == '') {
            alert('Inserire una email')
            return
        } else {
            if (!validateEmail($('#email').val())) {
                alert('Email non valida')
                return
            }
        }
        if ($('#nome').val() == '') {
            alert('Inserire un nome')
            return
        }
        if ($('#ente').val() == '') {
            alert('Inserire un ente/azienda')
            return
        }
        if ($('#cognome').val() == '') {
            alert('Inserire un cognome')
            return
        }
        if ($('#cf').val() == '') {
            alert('Inserire il codice fiscale')
            return
        }
        var cf = $('#cf').val()
        if (!validaCodiceFiscale(cf)) {
            alert('Codice fiscale non valido')
            return
        }
        if ($('#accesso').val() == 'backoffice' && $('#lab').val() == '') {
            alert('Inserire il laboratorio')
            return
        }
        if ($('#nt').val() == '') {
            alert('Inserire un numero di telefono')
            return
        }
        //$('#modal_form').modal('hide')
        $.ajax({
            type: 'POST',
            url: './front/sendEmail.php',
            data: form,
            success: function(result) {
                result = JSON.parse(result);
                $.LoadingOverlay("hide")
                if(result == 'error'){
                    document.getElementById("error").style.display = "block";
                    return;
                }
                document.getElementById("button_special").style.display = "none";
                document.getElementById("error").style.display = "none";
                document.getElementById("success").style.display = "block";
                setTimeout(function() {
                    window.location.reload();
                }, 5000);
            },
            beforeSend: function() {
                $.LoadingOverlay("show")
            },
            error: function(result) {
                $.LoadingOverlay("hide")
            }
        });
    }

    function addlab() {
        var accesso = $('#accesso').val()
        if (accesso == 'backoffice') {
            $('#laboratorio').html(`
            <label for="lab">Laboratorio</label>
            <select id="lab" class="form-control" name='lab'>
            </select>
            `)
            let dropdown = $('#lab');
            dropdown.empty();
            dropdown.append('<option value="" selected="true"> Seleziona il laboratorio</option>');
            dropdown.prop('selectedIndex', 0);
            const url = "./front/get_labs.php";
            $.getJSON(url, function(data) {
                $.each(data, function(key, entry) {
                    dropdown.append($('<option></option>').attr('value', entry.descrizione).text(entry.descrizione));
                })
            });

        } else {
            $('#laboratorio').html('')
        }
    }


    function validaCodiceFiscale(cf) {
        var validi, i, s, set1, set2, setpari, setdisp;
        if (cf == '') return '';
        cf = cf.toUpperCase();
        if (cf.length != 16)
            return false;
        validi = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for (i = 0; i < 16; i++) {
            if (validi.indexOf(cf.charAt(i)) == -1)
                return false;
        }
        set1 = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        set2 = "ABCDEFGHIJABCDEFGHIJKLMNOPQRSTUVWXYZ";
        setpari = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        setdisp = "BAKPLCQDREVOSFTGUHMINJWZYX";
        s = 0;
        for (i = 1; i <= 13; i += 2)
            s += setpari.indexOf(set2.charAt(set1.indexOf(cf.charAt(i))));
        for (i = 0; i <= 14; i += 2)
            s += setdisp.indexOf(set2.charAt(set1.indexOf(cf.charAt(i))));
        if (s % 26 != cf.charCodeAt(15) - 'A'.charCodeAt(0))
            return false;
        return true;
    }

    function validateEmail(email) {
        var re = /\S+@\S+\.\S+/;
        return re.test(email);
    }
</script>
<style>
    .row {
        margin-left: 0px;
        margin-right: 0px;
    }

    @import url(https://fonts.googleapis.com/css?family=Titillium+Web:400,600);

    .italia-it-button {
        display: inline-block;
        position: relative;
        padding: 0;
        color: #FFF;
        font-family: "Titillium Web", HelveticaNeue, Helvetica Neue, Helvetica, Arial, Lucida Grande, sans-serif;
        font-weight: 600;
        line-height: 1em;
        text-decoration: none;
        border: 0;
        text-align: center;
        cursor: pointer;
        overflow: hidden;
        border-radius: 3px;
    }

    .italia-it-button-icon,
    .italia-it-button-text {
        display: block;
        float: left
    }

    .italia-it-button-icon {
        margin: 0 -.4em 0 0;
        padding: 0.6em .8em .5em;
        border-right: rgba(255, 255, 255, 0.1) 0.1em solid
    }

    .italia-it-button-text {
        padding: .95em 1em .85em 1em;
        font-size: 1.15em;
        text-align: center
    }

    svg {
        width: 1.8em;
        height: 1.8em;
        fill: #fff
    }

    .italia-it-block {
        display: block
    }

    .italia-it-button-size-s {
        font-size: 10px
    }

    .italia-it-button-size-s>span img {
        width: 19px;
        height: 19px;
        border: 0
    }

    .italia-it-button-size-m {
        font-size: 15px
    }

    .italia-it-button-size-m>span img {
        width: 29px;
        height: 29px;
        border: 0
    }

    .italia-it-button-size-l {
        font-size: 20px
    }

    .italia-it-button-size-l>span img {
        width: 38px;
        height: 38px;
        border: 0
    }

    .italia-it-button-size-xl {
        font-size: 25px
    }

    .italia-it-button-size-xl>span img {
        width: 47px;
        height: 47px;
        border: 0
    }

    .button-spid {
        background-color: #06C;
        color: #FFF
    }

    .button-spid svg {
        fill: #FFF
    }

    .button-spid:hover {
        background-color: #036;
        color: #FFF
    }

    .button-spid:active {
        background-color: #83BEED;
        color: #036
    }

    .spid-sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        border: 0
    }


    .login-form-1 {
        padding: 5%;
        background-color: white;
    }

    .login-form-1 h3 {
        text-align: center;
        color: #333;
    }

    .login-form-2 {
        padding: 5%;
        background-color: white;
    }

    .login-form-2 h3 {
        text-align: center;
        color: #fff;
    }

    .btnSubmit {
        width: 50%;
        border-radius: 1rem;
        padding: 1.5%;
        border: none;
        cursor: pointer;
    }

    .login-form-1 .btnSubmit {
        font-weight: 600;
        color: #fff;
        background-color: #0062cc;
    }

    .login-form-2 .btnSubmit {
        font-weight: 600;
        color: #0062cc;
        background-color: #fff;
    }

    .login-form-2 .ForgetPwd {
        color: #fff;
        font-weight: 600;
        text-decoration: none;
    }

    .login-form-1 .ForgetPwd {
        color: #0062cc;
        font-weight: 600;
        text-decoration: none;
    }

    form #website {
        display: none;
    }
</style>
<style>
    div#header3:before {
        width: 100%;
        height: 43px;
        background: #4e5ecc;
        content: '';
        position: absolute;
    }

    .dnn_layout {
        width: 100%;
        margin: 0px auto;
    }

    .vr {
        border-right: 1px solid #dbdbdb;
    }

    .head {
        height: 43px;
        background: #4e5ecc;
    }

    .bottom {
        border-top: 2px solid #4e5ecc;
        box-shadow: 0 2px 4px 0 rgb(0 0 0 / 20%);
        margin-bottom: 40px;
    }

    .max {
        width: 100%;
        margin-bottom: 20px;
    }

    .str {
        background: url(assets/images/claim.png);
        background-position: right 60px top 9px;
        background-repeat: no-repeat;
        background-size: contain;
    }

    .cont {
        text-align: center;
    }

    .box-login {
        padding: 2rem;
        width: 100%;
        height: 17rem;
        overflow: hidden;
        position: relative;
        background: linear-gradient(180deg, rgba(59, 73, 138, 1) 0%, rgba(0, 51, 102, 1) 100%);
        color: #fff;
    }

    .txt-accesso {
        color: #fff;
        font-size: 1.3rem;
    }

    .ad img.bg-icon {
        float: right;
        position: absolute;
        bottom: -51px;
        right: -36px;
        height: 19rem;
    }

    .spid img.bg-icon {
        float: right;
        position: absolute;
        bottom: -50px;
        right: 0;
        height: 12rem;
    }

    .img-fluid {
        max-width: 80% !important;
        height: auto;
    }

    #mySidenav a {
        overflow: hidden;
        top: 60vh;
        position: fixed;
        /* Position them relative to the browser window */
        left: -20px;
        /* Position them outside of the screen */
        transition: 0.3s;
        /* Add transition on hover */
        width: 200px;
        /* Set a specific width */
        text-decoration: none;
        /* Remove underline */
        font-size: 20px;
        /* Increase font size */
        color: black;
        /* White text color */
        border-radius: 5px;
        padding: 20px;

    }
    #mySidenav2 a {
        overflow: hidden;
        top: calc(60vh + 120px);
        position: fixed;
        /* Position them relative to the browser window */
        left: -20px;
        /* Position them outside of the screen */
        transition: 0.3s;
        /* Add transition on hover */
        width: 200px;
        /* Set a specific width */
        text-decoration: none;
        /* Remove underline */
        font-size: 20px;
        /* Increase font size */
        color: black;
        /* White text color */
        border-radius: 5px;
        padding: 20px;

    }

    #mySidenav a:hover {
        left: 0;
    }
    #mySidenav2 a:hover {
        left: 0;
    }

    #about {
        top: 20px;
        background: rgb(255, 201, 71);
        background: -moz-linear-gradient(165deg,
                rgba(255, 201, 71, 1) 0%,
                rgba(255, 152, 0, 1) 50%);
        background: -webkit-linear-gradient(165deg,
                rgba(255, 201, 71, 1) 0%,
                rgba(255, 152, 0, 1) 50%);
        background: linear-gradient(165deg,
                rgba(255, 201, 71, 1) 0%,
                rgba(255, 152, 0, 1) 50%);
    }
</style>
<header>
    <div class='row head'>
    </div>
    <div class='row max'>
        <div class='col'>
            <a id="dnn_dnnLOGO_hypLogo" title="Prenotazione campioni" aria-label="Prenotazione campioni" href="./"><img class='img-fluid ext' id="dnn_dnnLOGO_imgLogo" src="assets/images/logo2.png" alt="Prenotazione campioni" style="border-width:0px;"></a>
        </div>
        <div class='col-3 d-flex align-items-center'>
            <img class='img-fluid ext' id="dnn_dnnLOGO_imgLogo" src="assets/images/logo3.png" alt="Prenotazione campioni" style="border-width:0px;">
        </div>
        <div class='col str'>
        </div>
    </div>
    <div class='bottom row'>
    </div>
</header>
<div class="container login-container">
    <div class="alert alert-warning" role="alert">
        <p>L’Istituto Zooprofilattico Sperimentale delle Lombardia e dell’Emilia Romagna ha attivato il servizio di prenotazione delle prestazioni analitiche.
            Per procedere alla prenotazione ONLINE, come per tutte le pubbliche amministrazioni, è necessario essere in possesso dello SPID</p>
        <p>Per il <b>primo accesso</b> gli utenti <b>esterni</b> devono richiedere l'abilitazione del proprio utente <a onclick='contact()' href='#'>al seguente link</a>.
        </p>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-12 login-form-2 mt-2">
            <div class="box-login float-start ad">
                <img class="bg-icon" src="/assets/images/logo-izsler-trasp.png" />
                <p class="txt-accesso mt-1"><strong>Dipendenti e collaboratori IZSLER</strong><br>già registrati sul dominio<br></p>
                <button class="italia-it-button italia-it-button-size-m button-spid" <?= $variabili['aad']['contenuto'] == 'false' ? 'disabled' : ''; ?>>
                    <span onclick="window.location.href='/admin';" class='italia-it-button-text'>Entra con active directory</span>
                </button>
                <br>
                <br>
                <button class="italia-it-button italia-it-button-size-m button-spid" <?= $variabili['login']['contenuto'] == 'false' ? 'style="display:none"' : ''; ?>>
                    <span onclick="window.location.href='/admin2';" class='italia-it-button-text'>Entra con username e password</span>
                </button>
            </div>
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12 login-form-1 mt-2">
            <div class="box-login float-end spid">
                <p class="txt-accesso mt-5">Per utenti <strong>esterni</strong></p>
                <img class="bg-icon" src="/assets/images/logo-spid-trasp.png" />
                <form action="https://spid.izsler.it" type="x-www-form-urlencoded" method="POST">
                    <input type="hidden" name="service" value="<?= $variabili['spid']['contenuto']; ?>">
                    <button class="italia-it-button italia-it-button-size-m button-spid">
                        <span class="italia-it-button-icon"><img src="assets/spid-ico-circle-bb.svg" onerror="this.src='assets/spid-ico-circle-bb.png'; this.onerror=null;" alt=""></span>
                        <span class="italia-it-button-text">Entra con SPID</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div id="mySidenav" class="sidenav">
        <a href="#" onclick="sidebar();return false;" id="about">VIDEO GUIDA PER L’UTILIZZO</a>
    </div>


    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div style="padding:52.84% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/715168952?h=79df5f863f&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;" title="Prenotazione campioni - Introduzione"></iframe></div>
                    <script src="https://player.vimeo.com/api/player.js"></script>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h6>Si prega di compilare le informazioni. <br>Tutti i campi sono obbligatori.</h6>
                    <br>
                    <h6>Gli utenti verranno attivati entro 48 ore dalla richiesta.</h6>
                    <form id='form_email'>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input type="text" id="website" name="website" />
                                <label for="email">Email aziendale</label>
                                <input type="email" aria-describedby="emailHelp" class="form-control" name='email' id="email" placeholder="email">
                            </div>
                        </div>
                        <div class="form-group  col-md-6">
                            <label for="nome">ENTE/AZIENDA per la quale viene effettuata la richiesta</label>
                            <input type="text" class="form-control" name='ente' id="ente" placeholder="ente/azienda">
                        </div>
                        <div class="form-group  col-md-6">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" name='nome' id="nome" placeholder="nome">
                        </div>
                        <div class="form-group  col-md-6">
                            <label for="cognome">Cognome</label>
                            <input type="text" class="form-control" name='cognome' id="cognome" placeholder="cognome">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="cf">Codice fiscale</label>
                                <input style="text-transform:uppercase" placeholder="codice fiscale" type="text" name='cf' maxlength="16" class="form-control" id="cf">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="accesso">Tipo di accesso</label>
                                <select onchange='addlab()' id="accesso" class="form-control" name='accesso'>
                                    <!-- <option value='' selected disabled>Seleziona il tipo di accesso</option> -->
                                    <option value='frontoffice' selected>Front office</option>
                                    <!-- <option value='backoffice'>Back office</option> -->
                                </select>
                            </div>
                            <div class="form-group col-md-4" id="laboratorio">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="nt">Numero di telefono aziendale</label>
                                <input placeholder='numero di telefono' type="text" maxlength="10" class="form-control" id="nt" name='nt'>
                            </div>
                        </div>

                    </form>
                    <button id='button_special' type="button" onclick='send()' class="btn btn-primary">Invia</button>
                    <br>
                    <div style='display:none' id='success' class='alert alert-success'>La richiesta è stata inviata con successo.</div>
                    <div style='display:none' id='error' class='alert alert-danger'>Attenzione, l’utente risulta già abilitato per l’accesso al sistema.</div>
                </div>
            </div>
        </div>
    </div>