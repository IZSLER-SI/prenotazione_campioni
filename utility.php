<?php
function log_data($action, $user){
	global $conn;
  if(empty($conn)){
    include(dirname(__FILE__) . '/objects/class_connection.php');
  }
 $database = new prenotazione_campioni_db();
 $conn = $database->connect();
 $data = array(
  'azione'        => $action,
  'utente'        => $user,
  'data'          => date('Y-m-d H:i:s'),
  'id_elemento'   => 0,
  'extend'        => 'test'
 );

 $query = sprintf(
  'INSERT INTO log (%s) VALUES ("%s")',
  implode(',',array_keys($data)),
  implode('","',array_values($data))
);
mysqli_query($conn, $query);
}

function get_variabili(){
	global $conn;
  if(empty($conn)){
    include(dirname(__FILE__) . '/objects/class_connection.php');
  }
  $database = new prenotazione_campioni_db();
  $conn   = $database->connect();
  $ambiente = getenv("AMBIENTE");
  $query  = "SELECT * FROM `variabili` where ambiente = '".$ambiente."'";
  $result = mysqli_query($conn, $query);
  while ($row = mysqli_fetch_assoc($result)) {
    $value[$row['nome_variabile']] = $row;
   }
   return $value;
}