<?php
include(dirname(__FILE__) . '/header.php');
include(dirname(dirname(__FILE__)) . "/objects/class_dayweek_avail.php");
include(dirname(__FILE__) . '/user_session_check.php');
include(dirname(dirname(__FILE__)) . "/objects/class_offbreaks.php");
include(dirname(dirname(__FILE__)) . "/objects/class_offtimes.php");
include(dirname((dirname(__FILE__))) . '/objects/class_booking.php');
$obj_offtime = new prenotazione_campioni_offtimes();
$obj_offtime->conn = $conn;
$setting = new prenotazione_campioni_setting();
$setting->conn = $conn;
$getdateformat = $setting->get_option('ct_date_picker_date_format');
$con = new prenotazione_campioni_db();
$conn = $con->connect();
$objdayweek_avail = new prenotazione_campioni_dayweek_avail();
$objdayweek_avail->conn = $conn;
$objoffbreaks = new prenotazione_campioni_offbreaks();
$objoffbreaks->conn = $conn;
$campioni             = $objoffbreaks->getCampioniLab($_SESSION['lab_selected']);
$campioni_conoscitivi = $objoffbreaks->getCampioniConoscitiviLab($_SESSION['lab_selected']);
$campioni_chimici     = $objoffbreaks->getCampioniChimiciLab($_SESSION['lab_selected']);
$campioni_alimenti    = $objoffbreaks->getCampioniAlimentiLab($_SESSION['lab_selected']);
$lab_info             = $objoffbreaks->getLabInfo($_SESSION['lab_selected']);
$prove_chimici        = $objoffbreaks->getProveChimici();

$time_int = $objdayweek_avail->getinterval();
$slot = $objdayweek_avail->getNumeroCampioni()[0];
$time_interval = $time_int[0];
$time_format = $setting->get_option('ct_time_format');
$booking              = new prenotazione_campioni_booking();
$booking->conn = $conn;
$check_if_chimici = false;
$actual_lab = $_SESSION['lab_selected'];
$lab_chimici = [133];
//search if lab is chimici in array of lab chimici
foreach ($lab_chimici as $lab) {
  if ($lab == $actual_lab) {
    $check_if_chimici = true;
  }
}

?>
<style>
    .container_margin {
        margin: 40px;
    }

    .spaced {
        margin-bottom: 10px;
    }
    .column {
        display: flex;
        flex-direction: column;
    }
</style>
<div class="container_margin">
    <h3>Informazioni laboratorio</h3>
    <div class="row">
        <div class="col-md-3">
            <label>Indirizzo e-mail</label>
            <input type="text" class="form-control" value="<?php echo $lab_info['email']; ?>" disabled>
        </div>
        <div class="col-md-1 column">
            <label>Invio email</label>
          <?php
          if($lab_info['invio_mail'] == 1){
            echo '<input checked type="checkbox" data-toggle="toggle" name="email_laboratorio_check" id="email_laboratorio_check" class="form-control" disabled>';
          }else{
            echo '<input type="checkbox" data-toggle="toggle" name="email_laboratorio_check"id="email_laboratorio_check"  class="form-control" disabled>';
          }
          ?>
        </div>
    </div>
    <br>
  <?php
  if(!$check_if_chimici){
    ?>
    <h3>Campioni Unica Istanza: Durata in minuti degli slot</h3>
    <div class="row">
        <div class='col-md-4'>
            <label for="numero_minuti">Minuti</label>
            <input value='<?php echo $time_int[0];?>' type="number" id="numero_minuti" name="numero_minuti" class="form-control" disabled>
        </div>
    </div>
    <br>
    <h3>Campioni Conoscitivi: N° massimo di prenotazioni al giorno </h3>
    <div class="row">
        <div class='col-md-4'>
            <label for="numero_campioni">Prenotazioni</label>
            <input value='<?php echo $campioni_conoscitivi;?>' type="number" id="numero_campioni_conoscitivi" name="numero_campioni_conoscitivi" class="form-control" disabled>
        </div>
    </div>
    <h3>Campioni Autocontrollo Alimenti (DA PG019/M): N° massimo di prenotazioni al giorno </h3>
    <div class="row">
        <div class='col-md-4'>
            <label for="numero_campioni">Prenotazioni</label>
            <input value='<?php echo $campioni_alimenti;?>' type="number" id="numero_campioni_alimenti" name="numero_campioni_alimenti" class="form-control" disabled>
        </div>
    </div>
    <h3>Campioni Autocontrollo Latte (DA PG019/N): N° massimo di prove al giorno (somma peso delle prove) </h3>
    <div class="row">
        <div class='col-md-4'>
            <label for="numero_campioni">Prove</label>
            <input value='<?php echo $campioni;?>' type="number" id="numero_campioni" name="numero_campioni" class="form-control" disabled>
        </div>
    </div>
    <h3>Programmazione settimanale Unica Istanza</h3>
      <?php
      $staff_id = $_SESSION['lab_selected'];
      for ($j = 1; $j <= 5; $j++) {
        $objdayweek_avail->week_id = 1;
        $objdayweek_avail->weekday_id = $j;
        $getvalue = $objdayweek_avail->get_time_slots($staff_id);
        if (empty($getvalue)) {
          $getvalue = $objdayweek_avail->get_time_slots(0);
        }
        $daystart_time = $getvalue[4];
        $daystart_time = substr($daystart_time, 0, 5);
        $dayend_time = $getvalue[5];
        $dayend_time = substr($dayend_time, 0, 5);
        $offdayst = $getvalue[6];
        echo '<div class="row">';
        echo '<div class="col-md-1">';
        echo '<label>' . $label_language_values[strtolower($objdayweek_avail->get_daynamebyid($j))] . ' </label>';
        echo '</div>';
        echo '<div class="col-md-2">';
        echo '<label>Orario di inizio</label>';
        echo '<input type="text" class="form-control" value="' . $daystart_time . '" disabled>';
        echo '</div>';

        echo '<div class="col-md-2">';
        echo '<label>Orario di fine</label>';
        echo '<input type="text" class="form-control" value="' . $dayend_time . '" disabled>';
        echo '</div>';

        echo '</div>';
      }
      }else{

      ?>
      <h4 class="ct-right-header">Campioni Chimici: N° massimo di prenotazioni al giorno </h4>
      <div class="row">
          <div class='col-md-3'>
              <label for="numero_campioni">Prenotazioni</label>
              <input value='<?php echo $campioni_chimici;?>' type="number" id="numero_campioni_chimici" name="numero_campioni_chimici" class="form-control" disabled>
          </div>
      </div>
        <h4 class="ct-right-header">Gestione della visibilitá delle prove Campioni Ufficiali UNICA ISTANZA CHIMICI</h4>
      <?php
      foreach ($prove_chimici as $prova){
        ?>
          <div class="row">
              <div class='col-md-4'>
                  <label for="numero_campioni"><?php echo $prova['descrizione'];?></label>
                  <input type="text" value='<?php echo $prova['giorno_prefissato'] ?>' id="giorno_prefissato_<?php echo $prova['id'];?>" name="giorno_prefissato_<?php echo $prova['id'];?>" class="form-control" disabled>
              </div>
          </div>
        <?php
      }
}
      ?>
</div>