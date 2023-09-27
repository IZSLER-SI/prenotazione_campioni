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

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$strut         = $objservice->get_strut();
$fins          = $objservice->get_fin();
$camp          = $objservice->get_camp();
$categ         = $objservice->get_cat();
$prove         = $objservice->get_prove();
$labs          = $objservice->get_labs();

$map_auto      = $objservice->get_mapping_campioni();
$map_uffi      = $objservice->get_mapping_finalita();

$strut_array   = array();
$fin_array     = array();
$cat_array     = array();
$camp_array    = array();
$prove_array   = array();
$lab_array     = array();

$ufficiale     = array();
$autocontrollo = array();
if($map_uffi){
    while ($uff = mysqli_fetch_assoc($map_uffi)) {
        array_push($ufficiale, $uff);
    }
}

if($map_auto){
    while ($auto = mysqli_fetch_assoc($map_auto)) {
        array_push($autocontrollo, $auto);
    }
}
while ($stru = mysqli_fetch_assoc($strut)) {
    array_push($strut_array, $stru);
}
while ($fin = mysqli_fetch_assoc($fins)) {
    array_push($fin_array, $fin);
}
while ($cat = mysqli_fetch_assoc($categ)){
    array_push($cat_array, $cat);
}
while ($campione = mysqli_fetch_assoc($camp)) {
    array_push($camp_array, $campione);
}
while ($prov = mysqli_fetch_assoc($prove)) {
    array_push($prove_array, $prov);
}
while ($lab = mysqli_fetch_assoc($labs)) {
    array_push($lab_array, $lab);
}
if(!empty($_SESSION['ct_accettazioneid'])){
    echo '
    <div class="container_margin">
    <h3 style="margin-bottom:30px;">Download mapping presenti all\'interno del sistema di prenotazione</h3>
    <span id="download">
    <a type="button" class="btn btn-primary" href="/front/export_mappings.php">Download</a>
    </span>
    </div>
    ';
}

// tabella per gestire i mapping ufficiali
echo '
    <div class="container_margin">
    <h3 style="margin-bottom:30px;">Collegamento delle variabili per le prenotazioni ufficiali</h3>
        <div class="row">
        <form id="myform_ufficiale">
        <div class="col-md-2">
            <label for="select_struttura">Sede di consegna</label>
            <select name="select_struttura" id="select_struttura_ufficiale" class="form-control">
            <option></option>';
foreach ($strut_array as $struttura) {
    echo '<option value="' . $struttura['id'] . '">' . $struttura['descrizione'] . '</option>';
}
echo        '
           </select>
      </div>
      <div class="col-md-2">
            <label for="select_finalita">Finalità</label>
            <select name="select_finalita" id="select_finalita_ufficiale" class="form-control">
            <option></option>';
foreach ($fin_array as $finality) {
    echo '<option value="' . $finality['id'] . '">' . $finality['descrizione'] . '</option>';
}
echo '
        </select>
    </div>
    <div class="col-md-2">
        <label for="select_categoria">Categoria</label>
        <select name="select_categoria" id="select_categoria_ufficiale" class="form-control">
            <option></option>';
foreach ($cat_array as $categoria) {
    echo '<option value="' . $categoria['id'] . '">' . $categoria['descrizione'] . '</option>';
}
echo    '</select>
                    </div>            
           <div class="col-md-2">
           <label for="select_laboratorio">Prove</label>
           <select name="select_prove" id="select_prove_ufficiale" class="form-control">
           <option></option>';
foreach ($prove_array as $prova) {
    echo '<option value="' . $prova['id'] . '">' . $prova['descrizione'] . '</option>';
}
echo '</select>
     </div>
     <div class="col-md-1">
        <label for="input_peso_ufficiale">Peso prova</label>
        <input type="text" id="input_peso_ufficiale" name="input_peso_ufficiale" class="form-control">
     </div>
     <div class="col-md-2">
      <label for="select_laboratorio">Laboratorio</label>
      <select name="select_laboratorio" id="select_laboratorio_ufficiale" class="form-control">
      <option></option>';
foreach ($lab_array as $labo) {
    echo '<option value="' . $labo['id'] . '">' . $labo['descrizione'] . '</option>';
}
echo '</select>
   </div>
  </form><div class="col-md-1">';

if (isset($_SESSION['ct_laboratorioid'])) {
    echo '<label for="btn"></label>
    <button name="btn" onclick="add_ufficiale()" type="button" class="btn btn-success form-control disabled">Aggiungi</button>';
}

echo '</div>
      </div>
    </div>
      <div class="row container_margin">
          <table class="table" id="tab_fin">
              <thead>
                  <tr>
                      <th class="col-md-2">Sede di consegna</th>
                      <th class="col-md-2">Finalità</th>
                      <th class="col-md-2">Categoria</th>
                      <th class="col-md-2">&nbsp; Prova</th>
                      <th class="col-md-1">&nbsp; Peso</th>
                      <th class="col-md-2">&nbsp; Laboratorio</th>
                      <th class="col-md-1">&nbsp; Azione</th>
                  </tr>
              </thead>
      ';

foreach ($ufficiale as $riga) {
    echo '
          <tr>
             <td class="col-md-2">' . $riga['s'] . '</td>
             <td class="col-md-2">' . $riga['f'] . '</td>
             <td class="col-md-2">' . $riga['cat'] . '</td>
             <td class="col-md-2">'.(!empty($riga['p']) ? $riga['p'] : '').'</td>
             <td class="col-md-1">'.(!empty($riga['peso_prova']) ? $riga['peso_prova'] : '').'</td>
             <td class="col-md-2">'.(!empty($riga['l']) ? $riga['l'] : '').'</td>
             <td class="col-md-1">
                  <button type="button" class="btn btn-danger" onclick="remove_campione(' . $riga['id_izler_finalita'] . ')" disabled>Rimuovi</button>
              </td>
          </tr>
          ';
}


echo '
          </table>
          </div>
      </div>';

// tabella per gestire i mapping autocontrollo
echo '
    <div class="container_margin">
    <h3 style="margin-bottom:30px;">Collegamento delle variabili per le prenotazioni autocontrollo</h3>
        <div class="row">
        <form id="myform_autocontrollo">
        <div class="col-md-2">
            <label for="select_struttura">Sede di consegna</label>
            <select name="select_struttura" id="select_struttura_autocontrollo" class="form-control">
            <option></option>';
foreach ($strut_array as $struttura) {
    echo '<option value="' . $struttura['id'] . '">' . $struttura['descrizione'] . '</option>';
}

echo        '
           </select>
      </div>
      <div class="col-md-2">
            <label for="select_campioni">Campione</label>
            <select name="select_campioni" id="select_campioni_autocontrollo" class="form-control">
            <option></option>';
foreach ($camp_array as $campi) {
    echo '<option value="' . $campi['id'] . '">' . $campi['descrizione'] . '</option>';
}
echo '
        </select>
    </div>
    <div class="col-md-2">
        <label for="select_categoria">Categoria</label>
        <select name="select_categoria" id="select_categoria_ufficiale" class="form-control">
            <option></option>';
foreach ($cat_array as $categoria) {
    echo '<option value="' . $categoria['id'] . '">' . $categoria['descrizione'] . '</option>';
}
echo    '</select>
                    </div>            
           <div class="col-md-2">
           <label for="select_laboratorio">Prove</label>
           <select name="select_prove" id="select_prove_autocontrollo" class="form-control">
           <option></option>';
foreach ($prove_array as $prova) {
    echo '<option value="' . $prova['id'] . '">' . $prova['descrizione'] . '</option>';
}
echo '</select>
     </div>
     <div class="col-md-1">
     <label for="input_peso_autocontrollo">Peso prova</label>
     <input type="text" id="input_peso_autocontrollo" name="input_peso_autocontrollo" class="form-control">
  </div>
     <div class="col-md-2">
      <label for="select_laboratorio">Laboratorio</label>
      <select name="select_laboratorio" id="select_laboratorio_autocontrollo" class="form-control">
      <option></option>';
foreach ($lab_array as $labo) {
    echo '<option value="' . $labo['id'] . '">' . $labo['descrizione'] . '</option>';
}
echo '</select>
   </div>
  </form>
            <div class="col-md-1">
            ';

if (!isset($_SESSION['ct_laboratorioid'])) {
    echo '<label for="btn"></label>
        <button name="btn" onclick="add_autocontrollo()" type="button" class="btn btn-success form-control">Aggiungi</button>';
}
echo '</div>
</div>
</div>
<div class="row container_margin">
    <table class="table" id="tab_cam">
        <thead>
            <tr>
                <th class="col-md-2">Sede di consegna</th>
                <th class="col-md-2">Campione</th>
                <th class="col-md-2">Categoria</th>
                <th class="col-md-2">&nbsp; Prova</th>
                <th class="col-md-1">&nbsp; Peso</th>
                <th class="col-md-2">&nbsp; Laboratorio</th>
                <th class="col-md-1">&nbsp; Azione</th>
            </tr>
        </thead>
';
foreach ($autocontrollo as $row) {
    echo '
    <tr>
        <td class="col-md-2">' . $row['s'] . '</td>
        <td class="col-md-2">' . $row['c'] . '</td>
        <td class="col-md-2">' . $row['cat'] . '</td>
        <td class="col-md-2">'.(!empty($row['p']) ? $row['p'] : '').'</td>
        <td class="col-md-1">'.(!empty($row['peso_prova']) ? $row['peso_prova'] : '').'</td>
        <td class="col-md-2">'.(!empty($row['l']) ? $row['l'] : '').'</td>
        <td class="col-md-1">
            <button type="button" class="btn btn-danger" onclick="remove_campione(' . $row['id_izler_campione'] . ')" disabled>Rimuovi</button>
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
    let dropdown = $('#select_laboratorio_ufficiale');
    let dropdown2 = $('#select_laboratorio_autocontrollo');
    dropdown.empty();
    dropdown.prop('selectedIndex', 0);
    dropdown2.empty();
    dropdown2.prop('selectedIndex', 0);
    const url = "../front/get_laboratorio.php?id=<?php
                                                    if (isset($_SESSION['ct_adminid'])) {
                                                        $id_user = $_SESSION['ct_adminid'];
                                                    }
                                                    if (isset($_SESSION['ct_laboratorioid'])) {
                                                        $id_user = $_SESSION['ct_laboratorioid'];
                                                    }
                                                    if (isset($_SESSION['ct_accettazioneid'])) {
                                                        $id_user = $_SESSION['ct_accettazioneid'];
                                                    }
                                                    echo $id_user;
                                                    ?>";
    $.getJSON(url, function(data) {
        $.each(data, function(key, entry) {
            dropdown.append($('<option></option>').attr('value', entry.id).text(entry.descrizione));
            dropdown2.append($('<option></option>').attr('value', entry.id).text(entry.descrizione));
        })
    });

    function add_autocontrollo() {
        var form = $('#myform_autocontrollo').serialize()
        $.ajax({
            type: 'POST',
            url: '../front/save_laboratorio_campione.php',
            data: form,
            success: function(result) {
                var lab = $("#select_laboratorio_autocontrollo option:selected").text();
                var camp = $("#select_campione_autocontrollo option:selected").text();
                var html = `Attenzione, il campione '${camp}' è già stata assegnata al laboratorio '${lab}'`
                result = JSON.parse(result);
                if (result.status == true) {
                    location.reload()
                } else {
                    $.LoadingOverlay("hide")
                    $('#error_modal .modal-body').html(html);
                    $('#error_modal').modal({
                        show: true
                    })
                }
            },
            error: function(result) {

            },
            beforeSend: function(result) {
                $.LoadingOverlay("show")
            }
        });
    }

    function add_ufficiale() {
        var form = $('#myform_ufficiale').serialize()
        $.ajax({
            type: 'POST',
            url: '../front/save_laboratorio_finalita.php',
            data: form,
            success: function(result) {
                var lab = $("#select_laboratorio_ufficiale option:selected").text();
                var fin = $("#select_finalita_ufficiale option:selected").text();
                var html = `Attenzione, la finalita '${fin}' è già stata assegnata al laboratorio '${lab}'`
                result = JSON.parse(result);
                if (result.status == true) {
                    location.reload()
                } else {
                    $.LoadingOverlay("hide")
                    $('#error_modal .modal-body').html(html);
                    $('#error_modal').modal({
                        show: true
                    })
                }
            },
            error: function(result) {

            },
            beforeSend: function(result) {
                $.LoadingOverlay("show")
            }
        });
    }

    $('#tab_cam').DataTable({
        "order": [
            [2, 'asc'],
        ],
        "responsive": true,
        "page_length": 10,
        "language": {
            "zeroRecords": "Non sono presenti relazioni tra laboratori e campioni",
            "info": "Pagina _PAGE_ di _PAGES_",
            "infoEmpty": "Non sono presente relazioni tra laboratori e campioni",
            "infoFiltered": "(filtrati tra _MAX_ relazioni)",
            "search": "Cerca:",
            "lengthMenu": "Visualizza _MENU_ elementi",
            "paginate": {
                "first": "Primo",
                "last": "Ultimo",
                "next": "Avanti",
                "previous": "Indietro"
            },
        }
    });


    $('#tab_fin').DataTable({
        "order": [
            [2, 'asc'],
        ],
        "responsive": true,
        "page_length": 10,
        "language": {
            "zeroRecords": "Non sono presenti relazioni tra laboratori e finalità",
            "info": "Pagina _PAGE_ di _PAGES_",
            "infoEmpty": "Non sono presente relazioni tra laboratori e finalità",
            "infoFiltered": "(filtrati tra _MAX_ relazioni)",
            "search": "Cerca:",
            "lengthMenu": "Visualizza _MENU_ elementi",
            "paginate": {
                "first": "Primo",
                "last": "Ultimo",
                "next": "Avanti",
                "previous": "Indietro"
            },
        }
    });
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