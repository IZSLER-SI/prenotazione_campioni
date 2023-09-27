<?php
include(dirname(__FILE__) . '/header.php');
include(dirname(dirname(__FILE__)) . "/objects/class_services.php");
include(dirname(__FILE__) . '/user_session_check.php');
include(dirname(dirname(__FILE__)) . '/objects/class_users.php');
$con = new prenotazione_campioni_db();
$conn = $con->connect();
$objservice = new prenotazione_campioni_services();
$objservice->conn = $conn;
$users = new prenotazione_campioni_users();
$users->conn = $conn;
$rows = $objservice->get_lab_assoc();
$rows2 = $objservice->get_camp_assoc();
$labs = $objservice->get_labs();
$fins = $objservice->get_fin();
$camp = $objservice->get_camp();
$multi_array    = array();
$multi_camp_array    = array();
$fin_array      = array();
$lab_array      = array();
$camp_array     = array();
while ($row = mysqli_fetch_assoc($rows)) {
    array_push($multi_array, $row);
}
while ($row = mysqli_fetch_assoc($rows2)) {
    array_push($multi_camp_array, $row);
}
while ($campione = mysqli_fetch_assoc($camp)) {
    array_push($camp_array, $campione);
}
while ($fin = mysqli_fetch_assoc($fins)) {
    array_push($fin_array, $fin);
}
while ($lab = mysqli_fetch_assoc($labs)) {
    array_push($lab_array, $lab);
}
echo '
    <div class="container_margin">
    <h3 style="margin-bottom:30px;">Assegnazione delle finalità ai laboratori</h3>
        <div class="row">
        <form id="myform">
            <div class="col-md-5">
            <label for="select_finalita">Finalità</label>
            <select name="select_finalita" id="select_finalita" class="form-control">';
foreach ($fin_array as $finality) {
    echo '<option value="' . $finality['id'] . '">' . $finality['descrizione'] . '</option>';
}
echo    '</select></div>
            <div class="col-md-3">
            <label for="select_laboratorio">Laboratorio</label>
            <select name="select_laboratorio" id="select_laboratorio" class="form-control">';

foreach ($lab_array as $labo) {
    echo '<option value="' . $labo['id'] . '">' . $labo['descrizione'] . '</option>';
}
echo '</select></div></form>
            <div class="col-md-2">
            <label for="btn"></label>
            <button name="btn" onclick="add()" type="button" class="btn btn-success form-control" onclick="' . $row['id'] . '">Aggiungi</button></div>
        </div>
';
echo '
<div class="row container_margin">
    <table class="table" id="tab_fin">
        <thead>
            <tr>
                <th></th>
                <th>Finalità</th>
                <th>Laboratorio</th>
                <th>Azione</th>
            </tr>
        </thead>
';
foreach ($multi_array as $row) {
    echo '
    <tr>
        <td></td>
        <td>' . $row['fin'] . '</td>
        <td>' . $row['lab'] . '</td>
        <td>
            <button type="button" class="btn btn-danger"            onclick="remove(' . $row['id'] . ')">Rimuovi</button>
            <button type="button" class="btn btn-warning"   onclick="prove_finalita(' . $row['id'] . ')">Gestisci prove</button>
        </td>
    </tr>
    ';
}
echo '
    </table>
    </div>
</div>
';
                    echo '<hr>
    <div class="container_margin">
    <h3 style="margin-bottom:30px;">Assegnazione dei campioni ai laboratori</h3>
        <div class="row">
        <form id="myform_campioni">
        <div class="col-md-5">
            <label for="select_finalita">Campione</label>
            <select name="select_campione" id="select_campione" class="form-control">';
                    foreach ($camp_array as $campione) {
                        echo '<option value="' . $campione['id'] . '">' . $campione['descrizione'] . '</option>';
                    }
                    echo    '</select></div>
            <div class="col-md-5">
            <label for="select_laboratorio">Laboratorio</label>
            <select name="select_laboratorio2" id="select_laboratorio2" class="form-control">';

                    foreach ($lab_array as $labo) {
                        echo '<option value="' . $labo['id'] . '">' . $labo['descrizione'] . '</option>';
                    }
                    echo '</select></div></form>
                    <div class="col-md-2">
                    <label for="btn"></label>
                    <button name="btn" onclick="add_campione()" type="button" class="btn btn-success form-control" onclick="' . $row['id'] . '">Aggiungi</button></div>
                </div>
        ';
        echo '
        <div class="row container_margin">
            <table class="table" id="tab_cam">
                <thead>
                    <tr>
                        <th></th>
                        <th>Campione</th>
                        <th>Laboratorio</th>
                        <th>Azione</th>
                    </tr>
                </thead>
        ';
        foreach ($multi_camp_array as $row) {
            echo '
            <tr>
                <td></td>
                <td>' . $row['cam'] . '</td>
                <td>' . $row['lab'] . '</td>
                <td>
                    <button type="button" class="btn btn-danger" onclick="remove_campione(' . $row['id'] . ')">Rimuovi</button>
                    <button type="button" class="btn btn-warning" onclick="prove_campione(' . $row['id'] . ')">Gestisci prove</button>
                </td>
            </tr>
            ';
        }
        echo '
            </table>
            </div>
        </div>';
?>
<style>
    .container_margin {
        margin-top: 40px;
        margin-left: 20px;
        margin-right: 20px;
    }
</style>
<script>
    $('#tab_fin').DataTable({
        "order": [
            [2, 'asc'],
            
        ],
        "language": {
            "zeroRecords":    "Non sono presenti relazioni tra laboratori e finalità",
            "info":           "Pagina _PAGE_ di _PAGES_",
            "infoEmpty":      "Non sono presente relazioni tra laboratori e finalità",
            "infoFiltered":   "(filtrati tra _MAX_ relazioni)",
            "search":         "Cerca:",
            "lengthMenu":     "Visualizza _MENU_ elementi",
            "paginate": {
                "first":      "Primo",
                "last":       "Ultimo",
                "next":       "Avanti",
                "previous":   "Indietro"
            },
        }
    });
    $('#tab_cam').DataTable({
        "order": [
            [2, 'asc'],
            
        ],
        "language": {
            "zeroRecords":    "Non sono presenti relazioni tra laboratori e campioni",
            "info":           "Pagina _PAGE_ di _PAGES_",
            "infoEmpty":      "Non sono presente relazioni tra laboratori e campioni",
            "infoFiltered":   "(filtrati tra _MAX_ relazioni)",
            "search":         "Cerca:",
            "lengthMenu":     "Visualizza _MENU_ elementi",
            "paginate": {
                "first":      "Primo",
                "last":       "Ultimo",
                "next":       "Avanti",
                "previous":   "Indietro"
            },
        }
    });
    function remove(id){
        $.ajax({
            type: 'POST',
            url: '../front/delete_laboratorio_finalita.php',
            data: {'id':id},
            success: function(result) {
                location.reload()
            },
            error: function(result) {

            },
            beforeSend: function(result){
                $.LoadingOverlay("show")
            }
        });
    }
    function remove_campione(id){
        $.ajax({
            type: 'POST',
            url: '../front/delete_laboratorio_campione.php',
            data: {'id':id},
            success: function(result) {
                location.reload()
            },
            error: function(result) {

            },
            beforeSend: function(result){
                $.LoadingOverlay("show")
            }
        });
    }
    function add(){
        var form = $("#myform").serialize()
        $.ajax({
            type: 'POST',
            url: '../front/save_laboratorio_finalita.php',
            data: form,
            success: function(result) {
                var lab  = $( "#select_laboratorio option:selected" ).text();
                var fin  = $( "#select_finalita option:selected" ).text();
                var html = `Attenzione, la finalita '${fin}' è già stata assegnata al laboratorio '${lab}'`
                result = JSON.parse(result);
                if(result.status == true){
                    location.reload()
                }else{
                    $.LoadingOverlay("hide")
                    $('#error_modal .modal-body').html(html);
                    $('#error_modal').modal({show:true})
                }
            },
            error: function(result) {

            },
            beforeSend: function(result){
                $.LoadingOverlay("show")
            }
        });
    }
    function add_campione(){
        var form = $("#myform_campioni").serialize()
        $.ajax({
            type: 'POST',
            url: '../front/save_laboratorio_campione.php',
            data: form,
            success: function(result) {
                var lab  = $( "#select_laboratorio2 option:selected" ).text();
                var cam  = $( "#select_campione option:selected" ).text();
                var html = `Attenzione, il campione '${cam}' è già stata assegnata al laboratorio '${lab}'`
                result = JSON.parse(result);
                if(result.status == true){
                    location.reload()
                }else{
                    $.LoadingOverlay("hide")
                    $('#error_modal .modal-body').html(html);
                    $('#error_modal').modal({show:true})
                }
            },
            error: function(result) {

            },
            beforeSend: function(result){
                $.LoadingOverlay("show")
            }
        });
    }
    function prove_finalita(id){
        let dropdown = $('#error_modal .modal-body')
        dropdown.empty()
        const url = "../front/get_prova_laboratorio.php?type=finalita&id="+id;
        $.getJSON(url, function(data) {
            $.each(data, function(key, entry) {
                dropdown.append($(`
                    <div class='eld'>
                    <input class="form-check-input" type="checkbox" id="finalita_prove_${entry.id}" name="finalita_prove[]" value="${entry.id}">
                    <label class="form-check-label" id="finalita_prove_${entry.id}">${entry.descrizione}</label>
                    </div>
                `));
            })
        });
        $('#error_modal').modal({show:true})
    }
    function prove_campione(id){
        let dropdown = $('#error_modal .modal-body')
        dropdown.empty()
        const url = "../front/get_prova_laboratorio.php?type=campione&id="+id;
        $.getJSON(url, function(data) {
            $.each(data, function(key, entry) {
                dropdown.append($(`
                    <div class='eld'>
                    <input class="form-check-input" type="checkbox" id="campione_prove_${entry.id}" name="campione_prove[]" value="${entry.id}">
                    <label class="form-check-label" id="campione_prove_${entry.id}">${entry.descrizione}</label>
                    </div>
                `));
            })
        });
        $('#error_modal').modal({show:true})
    }
</script>

<?php
include(dirname(__FILE__) . '/footer.php');
?>
<script type="text/javascript">
    var ajax_url = '<?php echo AJAX_URL; ?>';
    var ajaxObj = {
        'ajax_url': '<?php echo AJAX_URL; ?>'
    };
    var servObj = {
        'site_url': '<?php echo SITE_URL . 'assets/images/business/'; ?>'
    };
    var imgObj = {
        'img_url': '<?php echo SITE_URL . 'assets/images/'; ?>'
    };
</script>

<div class="modal fade" id="error_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <div class="modal-body">
        </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</div>
</div>