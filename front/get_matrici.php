<?php
header('Content-Type: text/json; charset=utf-8');

ob_start();
session_start();
include(dirname(dirname(__FILE__)) . '/header.php');
include(dirname(dirname(__FILE__)) . '/objects/class_connection.php');
include(dirname(dirname(__FILE__)) . '/objects/class_setting.php');

$con = new prenotazione_campioni_db();
$conn = $con->connect();
$setting = new prenotazione_campioni_setting();


if($_GET['finalita']){
    $query = "select distinct izler_categorie_matrici.descrizione,izler_categorie_matrici.id from izler_mapping
    join izler_categorie_matrici on izler_mapping.id_izsler_categoria_matrice = izler_categorie_matrici.id and izler_categorie_matrici.record_attivo = 1
    where izler_mapping.record_attivo = 1 and id_izler_struttura = ".$_GET['struttura']." and id_izler_finalita = ".$_GET['finalita'];
   }else{
    $query = "select distinct izler_categorie_matrici.descrizione,izler_categorie_matrici.id from izler_mapping
    join izler_categorie_matrici on izler_mapping.id_izsler_categoria_matrice = izler_categorie_matrici.id and izler_categorie_matrici.record_attivo = 1
    where izler_mapping.record_attivo = 1 and id_izler_struttura = ".$_GET['struttura']." and id_izler_campione = ".$_GET['campione'];
   }
$query .= ' order by izler_categorie_matrici.order';
$result = mysqli_query($conn, $query);
$value = [];
while ($row = mysqli_fetch_assoc($result)) {
 $row['descrizione'] = utf8_encode($row['descrizione']);
 $value[] = $row;
}
$return_data = json_encode($value);
echo $return_data;