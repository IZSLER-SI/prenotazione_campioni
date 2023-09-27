<?php
header('Content-Type: text/json; charset=utf-8');
ob_start();
session_start();
include(dirname(dirname(__FILE__)) . '/header.php');
include(dirname(dirname(__FILE__)) . '/objects/class_connection.php');
include(dirname(dirname(__FILE__)) . '/objects/class_setting.php');

error_reporting(E_ALL);
ini_set('display_errors', 'On'); 

$con = new prenotazione_campioni_db();
$conn = $con->connect();
$setting = new prenotazione_campioni_setting();
$id    = $_GET['id'];
$type  = $_GET['type'];
switch ($type) {
 case 'finalita':
  $query = "
  select 
   izler_prove.*,
   izler_prove_finalita.id as checked
    from 
     izler_prove
  left join 
   izler_prove_finalita on (
    izler_prove.id = izler_prove_finalita.id_izler_prove
   )
 ";
  break;
 case 'campione':
  $query = "
  select 
   izler_prove.*,
   izler_prove_campione.id as checked 
    from 
     izler_prove
  left join 
   izler_prove_campione on (
    izler_prove.id = izler_prove_campione.id_izler_prove
   )
 ";
  break;
 
 default:
  break;
}
$result=mysqli_query($conn,$query);
$value = [];
while($row=mysqli_fetch_assoc($result)){
 $row['descrizione'] = utf8_encode($row['descrizione']);
 $value[] = $row; 
}
$test2 = json_encode($value);
echo $test2;