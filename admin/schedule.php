<?php
ini_set("error_log", __DIR__ . DIRECTORY_SEPARATOR . "error.log"); // LOG FILE      
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
<div id="cta-staff-panel" class="panel tab-content">
    <div class="panel-body">
        <span class="login_user_id" id="login_user_id" data-id="<?php echo $_SESSION['lab_selected']; ?>">
        <hr id="hr" />
        <ul class="active nav nav-tabs nav-justified ct-staff-right-menu">
            <li><a href="#member-availabilty" class="availability" data-toggle="tab">Gestione slot</a></li>
            <li><a href="#member-offdays" data-toggle="tab"><?php echo $label_language_values['off_days']; ?></a></li>
        </ul>
        <div class="tab-pane active">
            <!-- first staff nmember -->
            <div class="container-fluid tab-content ct-staff-right-details">
                <div class="tab-pane member-offdays mt-10" id="member-offdays">
                    <div class="panel panel-default">
                        <?php
                        $displaydate = $offday->select_date();
                        $arr_all_off_day = array();
                        while ($readdate = mysqli_fetch_array($displaydate)) {
                            $arr_all_off_day[] = $readdate['off_date'];
                        }
                        $year_arr = array(date('Y'), date('Y') + 1);
                        $month_num = date('n');
                        if (isset($_GET['y']) && in_array($_GET['y'], $year_arr)) {
                            $year = $_GET['y'];
                        } else {
                            $year = date('Y');
                        }
                        $nextYear = date('Y') + 1;
                        $date = date('d');
                        $month = array(
                            ucfirst(strtolower($label_language_values['january'])),
                            ucfirst(strtolower($label_language_values['february'])),
                            ucfirst(strtolower($label_language_values['march'])),
                            ucfirst(strtolower($label_language_values['april'])),
                            ucfirst(strtolower($label_language_values['may'])),
                            ucfirst(strtolower($label_language_values['june'])),
                            ucfirst(strtolower($label_language_values['july'])),
                            ucfirst(strtolower($label_language_values['august'])),
                            ucfirst(strtolower($label_language_values['september'])),
                            ucfirst(strtolower($label_language_values['october'])),
                            ucfirst(strtolower($label_language_values['november'])),
                            ucfirst(strtolower($label_language_values['december']))
                        );
                        echo '<table class="offdaystable">';

                        echo '<tr>';
                        for ($reihe = 1; $reihe <= 12; $reihe++) { /* 4 */
                            $this_month = ($reihe - 1) * 0 + $reihe; /*write 0 instead of 12*/
                            $current_year = date('Y');
                            $currnt_month = date('m');
                            if (($currnt_month < $this_month) || ($currnt_month == $this_month)) {
                                $year = $current_year;
                            }
                            //else {
                            //    $year = $current_year + 1;
                            //}

                            $erster = date('w', mktime(0, 0, 0, $this_month, 1, $year));
                            $insgesamt = date('t', mktime(0, 0, 0, $this_month, 1, $year));
                            if ($erster == 0) $erster = 7;
                            echo '<td class="ct-calendar-box col-lg-4 col-md-4 col-sm-6 col-xs-12 pull-left">';
                            echo '<table align="center" class="table table-bordered table-striped monthtable">'; ?>
                            <tbody class="ta-c">
                                <div class="ct-schedule-month-name pull-right">
                                    <div class="pull-left">
                                        <div class="ct-custom-checkbox">
                                            <ul class="ct-checkbox-list">
                                                <li>
                                                    <label for="<?php echo $year . '-' . $this_month; ?>">
                                                        <?php echo $month[$reihe - 1] . " " . $year; ?>
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </tbody>
                            <?php
                            echo '<tr><td><b>' . $label_language_values['mon'] . '</b></td><td><b>' . $label_language_values['tue'] . '</b></td>';
                            echo '<td><b>' . $label_language_values['wed'] . '</b></td><td><b>' . $label_language_values['thu'] . '</b></td>';
                            echo '<td><b>' . $label_language_values['fri'] . '</b></td><td class="sat"><b>' . $label_language_values['sat'] . '</b></td>';
                            echo '<td class="sun"><b>' . $label_language_values['sun'] . '</b></td></tr>';
                            echo '<tr class="dateline selmonth_' . $year . '-' . $this_month . '"><br>';
                            $i = 1;
                            while ($i < $erster) {
                                echo '<td> </td>';
                                $i++;
                            }
                            $i = 1;
                            while ($i <= $insgesamt) {
                                $rest = ($i + $erster - 1) % 7;
                                $cal_cur_date =  $year . "-" . sprintf('%02d', $this_month) . "-" . sprintf('%02d', $i);
                                $custom_off = $booking->getSlotsOccupati($cal_cur_date,$_SESSION['lab_selected']);
                                if (($i == $date) && ($this_month == $month_num)) {
                                    if (isset($arr_all_off_day) && !empty($custom_off)) {
                                        echo '<td  id="' . $year . '-' . $this_month . '-' . $i . '" data-prov_id="" class="selectedDate RR offsingledate"  align=center >';
                                    } else {
                                        echo '<td  id="' . $year . '-' . $this_month . '-' . $i . '" data-prov_id=""  class="date_single RR offsingledate"  align=center>';
                                    }
                                } else {
                                    if (isset($arr_all_off_day) && !empty($custom_off)) {
                                        echo '<td  id="' . $year . '-' . $this_month . '-' . $i . '"  data-prov_id=""  class="selectedDate RR offsingledate highlight"  align=center>';
                                    } else {
                                        echo '<td  id="' . $year . '-' . $this_month . '-' . $i . '" data-prov_id="" class="date_single RR offsingledate"  align=center>';
                                    }
                                }
                                if (($i == $date) && ($this_month == $month_num)) {
                                    echo '<span style="color:#000;">' . $i . '</span>';
                                } elseif ($rest == 6) {
                                    echo '<span   style="color:#0000cc;">' . $i . '</span>';
                                } elseif ($rest == 0) {
                                    echo '<span  style="color:#cc0000;">' . $i . '</span>';
                                } else {
                                    echo $i;
                                }
                                echo "</td>\n";
                                if ($rest == 0) echo "</tr>\n<tr class='dateline selmonth_" . $year . "-" . $this_month . "'>\n";
                                $i++;
                            }
                            echo '</tr>';
                            echo '</tbody>';
                            echo '</table>';
                            echo '</td>';
                        }
                        echo '</tr>';

                        echo '<tr>';
                        for ($reihe = 1; $reihe <= 12; $reihe++) { /* 4 */
                            $this_month = ($reihe - 1) * 0 + $reihe; /*write 0 instead of 12*/
                            $current_year = date('Y');
                            $currnt_month = date('m');
                            $year = $current_year + 1;


                            $erster = date('w', mktime(0, 0, 0, $this_month, 1, $year));
                            $insgesamt = date('t', mktime(0, 0, 0, $this_month, 1, $year));
                            if ($erster == 0) $erster = 7;
                            echo '<td class="ct-calendar-box col-lg-4 col-md-4 col-sm-6 col-xs-12 pull-left">';
                            echo '<table align="center" class="table table-bordered table-striped monthtable">'; ?>
                            <tbody class="ta-c">
                                <div class="ct-schedule-month-name pull-right">
                                    <div class="pull-left">
                                        <div class="ct-custom-checkbox">
                                            <ul class="ct-checkbox-list">
                                                <li>
                                                    <label for="<?php echo $year . '-' . $this_month; ?>">
                                                        <?php echo $month[$reihe - 1] . " " . $year; ?>
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </tbody>
                            <?php
                            echo '<tr><td><b>' . $label_language_values['mon'] . '</b></td><td><b>' . $label_language_values['tue'] . '</b></td>';
                            echo '<td><b>' . $label_language_values['wed'] . '</b></td><td><b>' . $label_language_values['thu'] . '</b></td>';
                            echo '<td><b>' . $label_language_values['fri'] . '</b></td><td class="sat"><b>' . $label_language_values['sat'] . '</b></td>';
                            echo '<td class="sun"><b>' . $label_language_values['sun'] . '</b></td></tr>';
                            echo '<tr class="dateline selmonth_' . $year . '-' . $this_month . '"><br>';
                            $i = 1;
                            while ($i < $erster) {
                                echo '<td> </td>';
                                $i++;
                            }
                            $i = 1;
                            while ($i <= $insgesamt) {
                                $rest = ($i + $erster - 1) % 7;
                                $cal_cur_date =  $year . "-" . sprintf('%02d', $this_month) . "-" . sprintf('%02d', $i);
                                $custom_off = $booking->getSlotsOccupati($cal_cur_date,$_SESSION['lab_selected']);
                                if (($i == $date) && ($this_month == $month_num)) {
                                    if (isset($arr_all_off_day) && !empty($custom_off)) {
                                        echo '<td  id="' . $year . '-' . $this_month . '-' . $i . '" data-prov_id="" class="selectedDate RR offsingledate"  align=center >';
                                    } else {
                                        echo '<td  id="' . $year . '-' . $this_month . '-' . $i . '" data-prov_id=""  class="date_single RR offsingledate"  align=center>';
                                    }
                                } else {
                                    if (isset($arr_all_off_day) && !empty($custom_off)) {
                                        echo '<td  id="' . $year . '-' . $this_month . '-' . $i . '"  data-prov_id=""  class="selectedDate RR offsingledate highlight"  align=center>';
                                    } else {
                                        echo '<td  id="' . $year . '-' . $this_month . '-' . $i . '" data-prov_id="" class="date_single RR offsingledate"  align=center>';
                                    }
                                }
                                if (($i == $date) && ($this_month == $month_num)) {
                                    echo '<span style="color:#000;font-weight: bold;font-size: 15px;">' . $i . '</span>';
                                } elseif ($rest == 6) {
                                    echo '<span   style="color:#0000cc;">' . $i . '</span>';
                                } elseif ($rest == 0) {
                                    echo '<span  style="color:#cc0000;">' . $i . '</span>';
                                } else {
                                    echo $i;
                                }
                                echo "</td>\n";
                                if ($rest == 0) echo "</tr>\n<tr class='dateline selmonth_" . $year . "-" . $this_month . "'>\n";
                                $i++;
                            }
                            echo '</tr>';
                            echo '</tbody>';
                            echo '</table>';
                            echo '</td>';
                        }
                        echo '</tr>';
                        /*  } */
                        echo '</table>';
                        ?>
                    </div>
                </div>
                <div class="tab-pane active member-availabilty myloadedslots" id="member-availabilty">
                    <?php
                    $staff_id = $_SESSION['lab_selected'];
                    $option = $objdayweek_avail->get_schedule_type_according_provider($staff_id);
                    $weeks = $objdayweek_avail->get_dataof_week();

                    $weekname = array($label_language_values['first'],$label_language_values['second'],$label_language_values['third'],$label_language_values['fourth'],$label_language_values['fifth']);

                    $weeknameid = array($label_language_values['first_week'], $label_language_values['second_week'], $label_language_values['third_week'], $label_language_values['fourth_week'], $label_language_values['fifth_week']);
                    $option[7] = 'weekly';
                    if($option[7]=='monthly'){
                        $minweek=1;
                        $maxweek=5;
                    }elseif($option[7]=='weekly'){
                        $minweek=1;
                        $maxweek=1;
                    }else{
                        $minweek=1;
                        $maxweek=1;
                    }
                    ?>
                    <form id="" method="POST">
                    <a id="ct-add-staff-breaks"  data-staff_id="<?php echo $_SESSION['lab_selected']; ?>"</a>
                        <div class="panel panel-default">
                            <div class="col-sm-3 col-md-3 col-lg-3 col-xs-12 ct-weeks-schedule-menu">
                                <ul class="nav nav-pills nav-stacked">

                                        <li class="<?php if($i==1){ echo "active";}?>"><a href="#<?php echo !empty($weeknameid[$i-1]) ? $weeknameid[$i-1] : ''  ; ?>" data-toggle="tab"><?php echo '';?></a></li>
                                </ul>
                            </div>
                            <div class="col-sm-9 col-md-9 col-lg-9 col-xs-12">
                                <hr id="vr"/>
                                <div class="tab-content">
							<span class="prove_schedule_type" style="visibility: hidden;"><?php echo $option[7]; ?></span>
                                    <?php
                                    for ($i = $minweek; $i <= $maxweek; $i++) {
                                        ?>
                                        <div class="tab-pane <?php  if($i==1 ){ echo "active";}?>" id="<?php echo $weeknameid[$i - 1];?>">
                                            <div class="panel panel-default">
                                                <div class="panel-body">
                                                <h4 class="ct-right-header">Imposta l'email del laboratorio e attiva o disattiva l'invio </h4>
                                                <div class="row">
                                                    <div class='col-md-2'>
                                                        <label for="email_laboratorio">Indirizzo E-mail</label>
                                                        <input value='<?php echo $lab_info['email'];?> 'type='email' name="email_laboratorio" id= "email_laboratorio" class="form-control">
                                                    </div>
                                                    <div class='col-md-1'>
                                                        <label for="email_laboratorio_check">Invia email</label>
                                                        <?php
                                                        if($lab_info['invio_mail'] == 1){
                                                            echo '<input checked type="checkbox" data-toggle="toggle" id="email_laboratorio_check" name="email_laboratorio_check" class="form-control">';
                                                        }else{
                                                            echo '<input type="checkbox" data-toggle="toggle" id="email_laboratorio_check" name="email_laboratorio_check" class="form-control">';
                                                        }

                                                        ?>
                                                    </div>
                                                    <div class='col-md-3'>
                                                        <label>&nbsp;</label>
                                                        <button name="btn" id='update_email' type="button" class="btn btn-success form-control">Aggiorna</button>
                                                    </div>
                                                </div>
                                                <br>
                                                  <?php
                                                  if(!$check_if_chimici){
                                                  ?>
                                                    <h4 class="ct-right-header">Campioni Unica Istanza: Imposta la durata in minuti degli slot </h4>
                                                <div class="row">
                                                    <div class='col-md-3'>
                                                        <label for="numero_minuti">Minuti</label>
                                                        <input value='<?php echo $time_int[0];?>' type="number" id="numero_minuti" name="numero_minuti" class="form-control">
                                                    </div>
                                                    <div class='col-md-3'>
                                                        <label>&nbsp;</label>
                                                        <button name="btn" id='update_minuti' type="button" class="btn btn-success form-control">Aggiorna</button>
                                                    </div>
                                                </div>
                                                <!-- <h4 class="ct-right-header">Imposta il numero massimo di campioni ufficiali diversi da Unica Istanza nello stesso slot</h4>
                                                <div class="row">
                                                    <div class='col-md-3'>
                                                        <label for="numero_minuti">Campioni ufficiali per slot</label>
                                                        <input value='<?php /*echo $slot;*/?>' type="number" id="numero_campioni_ufficiali" name="numero_campioni_ufficiali" class="form-control">
                                                    </div>
                                                    <div class='col-md-3'>
                                                        <label>&nbsp;</label>
                                                        <button name="btn" id='update_campioni_ufficiali' type="button" class="btn btn-success form-control">Aggiorna</button>
                                                    </div>
                                                </div> -->
                                                <h4 class="ct-right-header">Campioni Conoscitivi: N° massimo di prenotazioni al giorno </h4>
                                                <div class="row">
                                                    <div class='col-md-3'>
                                                        <label for="numero_campioni">Prenotazioni</label>
                                                        <input value='<?php echo $campioni_conoscitivi;?>' type="number" id="numero_campioni_conoscitivi" name="numero_campioni_conoscitivi" class="form-control">
                                                    </div>
                                                    <div class='col-md-3'>
                                                        <label>&nbsp;</label>
                                                        <button name="btn" id='update_campioni_conoscitivi' type="button" class="btn btn-success form-control">Aggiorna</button>
                                                    </div>
                                                </div>
                                                <h4 class="ct-right-header">Campioni Autocontrollo Alimenti (DA PG019/M): N° massimo di prenotazioni al giorno </h4>
                                                <div class="row">
                                                    <div class='col-md-3'>
                                                        <label for="numero_campioni">Prenotazioni</label>
                                                        <input value='<?php echo $campioni_alimenti;?>' type="number" id="numero_campioni_alimenti" name="numero_campioni_alimenti" class="form-control">
                                                    </div>
                                                    <div class='col-md-3'>
                                                        <label>&nbsp;</label>
                                                        <button name="btn" id='update_campioni_alimenti' type="button" class="btn btn-success form-control">Aggiorna</button>
                                                    </div>
                                                </div>
                                                <h4 class="ct-right-header">Campioni Autocontrollo Latte (DA PG019/N): N° massimo di prove al giorno (somma peso delle prove) </h4>
                                                <div class="row">
                                                    <div class='col-md-3'>
                                                        <label for="numero_campioni">Prove</label>
                                                        <input value='<?php echo $campioni;?>' type="number" id="numero_campioni" name="numero_campioni" class="form-control">
                                                    </div>
                                                    <div class='col-md-3'>
                                                        <label>&nbsp;</label>
                                                        <button name="btn" id='update_campioni' type="button" class="btn btn-success form-control">Aggiorna</button>
                                                    </div>
                                                </div>
                                                                                                      <?php
                                                  }else{
                                                                                                      ?>
                                                    <h4 class="ct-right-header">Campioni Chimici: N° massimo di prenotazioni al giorno </h4>
                                                <div class="row">
                                                    <div class='col-md-3'>
                                                        <label for="numero_campioni">Prenotazioni</label>
                                                        <input value='<?php echo $campioni_chimici;?>' type="number" id="numero_campioni_chimici" name="numero_campioni_chimici" class="form-control">
                                                    </div>
                                                    <div class='col-md-3'>
                                                        <label>&nbsp;</label>
                                                        <button name="btn" id='update_campioni_chimici' type="button" class="btn btn-success form-control">Aggiorna</button>
                                                    </div>
                                                </div>
                                                    <div class="container_chimici">
                                                        <br>
                                                        <hr>
                                                    <h4 class="ct-right-header">Gestione della visibilitá delle prove Campioni Ufficiali UNICA ISTANZA CHIMICI</h4>
                                                          <?php
                                                          foreach ($prove_chimici as $prova){
                                                                ?>
                                                        <div class="row">
                                                                <div class='col-md-4'>
                                                                    <label for="numero_campioni"><?php echo $prova['descrizione'];?></label>
                                                                    <input type="text" value='<?php echo $prova['giorno_prefissato'] ?>' id="giorno_prefissato_<?php echo $prova['id'];?>" name="giorno_prefissato_<?php echo $prova['id'];?>" class="form-control">
                                                                </div>
                                                                <div class='col-md-3'>
                                                                    <label>&nbsp;</label>
                                                                    <button name="btn" onclick='update_prove_chimici(<?php echo $prova['id'];?>)' id='update_campioni_<?php echo $prova['id'];?>' type="button" class="btn btn-success form-control">Aggiorna</button>
                                                                </div>
                                                        </div>
                                                                <?php
                                                          }
                                                          ?>
                                                        <br>
                                                        <hr>
                                                    </div>
                                                                                                                                                        <?php
                                                                                                                                                        }
                                                                                                                                                        ?>
                                                  <?php
                                                  if(!$check_if_chimici){
                                                  ?>
                                                    <h4 class="ct-right-header">Programmazione settimanale Unica Istanza</h4>
                                                    <ul class="list-unstyled" id="ct-staff-timing">
                                                        <?php

                                                        $staff_id = $_SESSION['lab_selected'];
                                                        for ($j = 1; $j <= 5; $j++) {
                                                            $objdayweek_avail->week_id = $i;
                                                            $objdayweek_avail->weekday_id = $j;
                                                            $getvalue = $objdayweek_avail->get_time_slots($staff_id);
                                                            if(empty($getvalue)){
                                                                $getvalue = $objdayweek_avail->get_time_slots(0);
                                                            }
                                                            $daystart_time = $getvalue[4];
                                                            $dayend_time = $getvalue[5];
                                                            $offdayst = $getvalue[6];
                                                            ?>
                                                            <li class="active">
                                                            <span
                                                                    class="col-sm-3 col-md-3 col-lg-3 col-xs-12 ct-day-name"><?php echo  $label_language_values[strtolower($objdayweek_avail->get_daynamebyid($j))]; ?></span>
														<span class="col-sm-2 col-md-2 col-lg-2 col-xs-12">
															<label class="cta-col2" for="ct-monFirst<?php  echo $i; ?><?php echo $j; ?>_<?php  echo $getvalue[0]; ?>" style="display:none;">

<input class='chkdaynew' data-toggle="toggle" data-size="small" type='hidden' id="ct-monFirst<?php  echo $i; ?><?php echo $j; ?>_<?php  echo $getvalue[0]; ?>" <?php  if ($getvalue[6] == 'Y' || $getvalue[6] == '') { echo ""; } else { echo "checked"; } ?> data-on="<?php echo $label_language_values['o_n'];?>" data-off="<?php echo $label_language_values['off'];?>" data-onstyle='primary' data-offstyle='default' />


                                                            </label>
														</span>
														<span
                                                                class="col-sm-7 col-md-7 col-lg-7 col-xs-12 ct-staff-time-schedule">
															<div class="pull-right">
                                                                <select class="selectpicker starttimenew" data-aid="<?php echo $i;?>_<?php  echo $j;?>" id="starttimenews_<?php  echo $i;?>_<?php  echo $j;?>" data-size="10"
                                                                        style="display: none;">
                                                                    <?php
                                                                    $min = 0;
                                                                    $t = 1;
                                                                    while ($min < 1440) {
                                                                        if ($min == 1440) {
                                                                            $timeValue = date('G:i', mktime(0, $min - 1, 0, 1, 1, 2015));
                                                                        } else {
                                                                            $timeValue = date('G:i', mktime(0, $min, 0, 1, 1, 2015));
                                                                        }
                                                                        $timetoprint = date('G:i', mktime(0, $min, 0, 1, 1, 2014)); ?>
                                                                        <option <?php
                                                                        if ($getvalue[4] == date("H:i:s", strtotime($timeValue))) {
                                                                            $t= 10;
                                                                            echo "selected";
                                                                        }
                                                                        if($t==1) {
                                                                            if ("10:00:00" == date("H:i:s", strtotime($timeValue))) {
                                                                                echo "selected";
                                                                            }
                                                                        }
                                                                        ?> value="<?php echo date("H:i:s", strtotime($timeValue)); ?>">
                                                                            <?php
                                                                            if ($time_format == 24) {
                                                                                echo date("H:i", strtotime($timetoprint));
                                                                            } else {
                                                                                echo str_replace($english_date_array,$selected_lang_label,date("h:i A",strtotime($timetoprint)));
                                                                            }
                                                                            ?>
                                                                        </option>
                                                                        <?php
                                                                        $min = $min + 10;
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <span class="ct-staff-hours-to"> <?php  echo $label_language_values['to'];?> </span>
                                                                <select class="selectpicker endtimenew" data-aid="<?php echo $i;?>_<?php  echo $j;?>" data-size="10" id="endtimenews_<?php  echo $i;?>_<?php  echo $j;?>"
                                                                        style="display: none;">
                                                                    <?php
                                                                    $min = 0;
                                                                    $t = 1;
                                                                    while ($min < 1440) {
                                                                        if ($min == 1440) {
                                                                            $timeValue = date('G:i', mktime(0, $min - 1, 0, 1, 1, 2015));
                                                                        } else {
                                                                            $timeValue = date('G:i', mktime(0, $min, 0, 1, 1, 2015));
                                                                        }
                                                                        $timetoprint = date('G:i', mktime(0, $min, 0, 1, 1, 2014)); ?>
                                                                        <option <?php
                                                                        if ($getvalue[5] == date("H:i:s", strtotime($timeValue))) {
                                                                            $t = 10;
                                                                            echo "selected";
                                                                        }
                                                                        if($t==1) {
                                                                            if ("20:00:00" == date("H:i:s", strtotime($timeValue))) {
                                                                                echo "selected";
                                                                            }
                                                                        }
                                                                        ?>
                                                                            value="<?php echo date("H:i:s", strtotime($timeValue)); ?>">
                                                                            <?php
                                                                            if ($time_format == 24) {
                                                                                echo date("H:i", strtotime($timetoprint));
                                                                            } else {
                                                                                echo str_replace($english_date_array,$selected_lang_label,date("h:i A",strtotime($timetoprint)));
                                                                            }
                                                                            ?>
                                                                        </option>
                                                                        <?php
                                                                        $min = $min + 10;
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                </span>
                                                            </li>
                                                        <?php  }
                                                        ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                                              <?php
                                              }
                                              ?>
                      <?php
                      if(!$check_if_chimici){
                        ?>
                        <table class="ct-staff-common-table">
                            <tbody>
                            <tr>
                                <td></td>
                                <td>
                                    <a id="" value="" name="update_schedule"
                                       class="btn btn-success ct-btn-width btnupdatenewtimeslots_monthly"
                                       type="submit">Aggiorna slot
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                      <?php
                            }
                        ?>
                    </form>
            </div>
                <!-- end first -->
        </div>
    </div>
    </div>
    <?php
    include(dirname(__FILE__) . '/footer.php');
    ?>
    <script type="text/javascript">
        var ajax_url = '<?php echo AJAX_URL; ?>';
    </script>
    <style>
        .modal-xl{
            width: 100%;
            max-width: 1200px;
        }
    </style>
    <div class="modal fade" id="error_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body">
                  <?php
                  if (!$check_if_chimici) {
                    echo '
                     <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#menu1">Gestione campioni ufficiali</a></li>
                        <li><a data-toggle="tab" href="#menu2">Gestione campioni autocontrollo</a></li>
                        <li><a data-toggle="tab" href="#menu3">Gestione campioni conoscitivo</a></li>
                    </ul>
                    ';
                  } else {
                    echo '
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#menu4">Gestione campioni chimici</a></li>
                    </ul>
                      ';
                  }
                  ?>

                    <div class="tab-content">
                        <div id="menu1" class="tab-pane fade <?php echo !$check_if_chimici ? 'in active' : ''; ?>">
                            <h3>Inserimento prenotazioni manuali/Disabilitazione Slot ufficiali</h3>
                            <div id='div_ufficiale'>
                            </div>
                        </div>
                        <div id="menu2" class="tab-pane fade">
                            <h3>Inserimento prenotazioni manuali/Disabilitazione Slot autocontrollo</h3>
                            <div id='div_autocontrollo'>
                            </div>
                        </div>
                        <div id="menu3" class="tab-pane fade">
                            <h3>Inserimento prenotazioni manuali/Disabilitazione Slot conoscitivo</h3>
                            <div id='div_conoscitivo'>
                            </div>
                        </div>
                        <div id="menu4" class="tab-pane fade  <?php echo $check_if_chimici ? 'in active' : ''; ?>">
                            <h3>Inserimento prenotazioni manuali/Disabilitazione Slot chimici</h3>
                            <div id='div_chimici'>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
