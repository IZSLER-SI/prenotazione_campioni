<?php

include(dirname(__FILE__) . '/header.php');
include(dirname(__FILE__) . '/user_session_check.php');
include(dirname(dirname(__FILE__)) . '/objects/class_users.php');
include(dirname(dirname(__FILE__)) . '/objects/class_order_client_info.php');
$database = new prenotazione_campioni_db();
$conn = $database->connect();
$database->conn = $conn;
$user = new prenotazione_campioni_users();
$order_client_info = new prenotazione_campioni_order_client_info();
$user->conn = $conn;
$order_client_info->conn = $conn;
$reg_user_data = $user->readallBackoffice();
?>
<div class="panel-body">
 <div class="tab-content">
  <div id="registered-customers-listing" class="tab-pane fade in active">
   <h3 class="pull-left">Utenti back-office</h3>
   <div id="accordion" class="panel-group">
   <table id="backoffice_list" class="display responsive nowrap table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
     <tr>
     <th>Nome e Cognome</th>
     <th>Email</th>
     <th>Ruolo</th>
    </tr>
    </thead>
    <tbody>
   <?php
   while ($r_data = mysqli_fetch_array($reg_user_data)) {
    echo '<tr>';
    echo '<td>'.$r_data['fullname'].'</td>';
    echo '<td>'.$r_data['email'].'</td>';
    echo '<td>'.$r_data['role'].'</td>';
    echo '</tr>';
   }
   ?>
   </tbody>
   </table>
   </div>
  </div>
 </div>
</div>