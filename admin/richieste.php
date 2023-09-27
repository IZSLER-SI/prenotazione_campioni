<?php
include(dirname(__FILE__) . '/header.php');
include(dirname(__FILE__) . '/user_session_check.php');
include(dirname(dirname(__FILE__)) . '/objects/class_users.php');
include(dirname(dirname(__FILE__)) . '/objects/class_order_client_info.php');
$database = new prenotazione_campioni_db();
$conn = $database->connect();
$database->conn = $conn;
$user           = new prenotazione_campioni_users();
$user->conn     = $conn;
$requests       = $user->get_user_requests();
// while ($g_data = mysqli_fetch_array($guest_user_data)) {
?>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
<style>
    .container_margin {
        margin-top: 40px;
        margin-left: 20px;
        margin-right: 20px;
    }

    .spaced {
        margin-bottom: 10px;
    }
</style>
<div class="container_margin panel-group">
    <h3 style="margin-bottom:30px;">Gestione delle richieste di accesso degli utenti Frontoffice</h3>
    <table id="requests-table" class="display responsive nowrap table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Email</th>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Telefono</th>
                <th>Codice fiscale</th>
                <!-- <th>Laboratorio</th> -->
                <th>Ente</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($user_info = mysqli_fetch_array($requests)) {
                echo "<tr id='row" . $user_info['id'] . "'>";
                echo "<td>" . $user_info['email'] . "</td>";
                echo "<td>" . $user_info['nome'] . "</td>";
                echo "<td>" . $user_info['cognome'] . "</td>";
                echo "<td>" . $user_info['telefono'] . "</td>";
                echo "<td>" . $user_info['codice_fiscale'] . "</td>";
                echo "<td>" . $user_info['ente'] . "</td>";
                // echo "<td>" . $user_info['role'] . "</td>";
                echo '
      <td width="50px">
            <button name="btn" onclick="add_user(' . $user_info['id'] . ')"    type="button" class="spaced btn btn-success form-control">Accetta</button>
            <button name="btn" onclick="remove_user(' . $user_info['id'] . ')" type="button" class="spaced btn btn-danger  form-control">Rifiuta</button>
       </td>';
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<script>
    function add_user(id) {
        alertify.confirm('Conferma utente', 'Sei sicuro di voler accettare la richiesta di accesso?', function() {
            $('#row' + id).hide();
            $.ajax({
                type: 'POST',
                url: '../front/add_user.php',
                data: {
                    id
                },
                success: function(result) {
                    $.LoadingOverlay("hide")
                    alertify.alert(result.status, result.message);
                },
                error: function(result) {

                },
                beforeSend: function(result) {
                    $.LoadingOverlay("show")
                }
            });
        }, function() {});

    }

    function remove_user(id) {
        alertify.confirm('Eliminazione utente', 'Sei sicuro di voler eliminare la richiesta di accesso?', function() {
            alertify.prompt('Motivazione', 'Inserire la motivazione da mostrare all\'utente', '', function(evt, value) {
                var motivazione = value
                $('#row' + id).hide();
                $.ajax({
                    type: 'POST',
                    url: '../front/remove_user.php',
                    data: {
                        id,
                        motivazione
                    },
                    success: function(result) {
                        alertify.alert(result.status, result.message);
                        $.LoadingOverlay("hide")
                    },
                    error: function(result) {

                    },
                    beforeSend: function(result) {
                        $.LoadingOverlay("show")
                    }
                })
            }, function() {});
        }, function() {});

    }
</script>
<?php
include(dirname(__FILE__) . '/footer.php');
?>