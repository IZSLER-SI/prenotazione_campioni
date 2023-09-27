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

if ($_GET['finalita']) {
 $query = "select distinct izler_prove.descrizione,izler_prove.id,izler_mapping.id_izler_mapping as map ,izler_mapping.peso_prova as peso_prova,izler_mapping.id_izler_laboratorio as id_lab,izler_prove.giorno_prefissato from izler_mapping
 join izler_prove on izler_mapping.id_izler_prove = izler_prove.id and izler_prove.record_attivo = 1
 where izler_mapping.record_attivo = 1 and id_izler_struttura = " . $_GET['struttura'] . " and id_izsler_categoria_matrice = " . $_GET['categoria'] . " and id_izler_finalita = " . $_GET['finalita'];
} else {
 $query = "select distinct izler_prove.descrizione,
 izler_prove.id,
 izler_mapping.id_izler_mapping     as map,
 izler_mapping.peso_prova           as peso_prova,
 izler_mapping.id_izler_laboratorio as id_lab,
 izler_categoria_prove.descrizione as categoria_prove
from izler_mapping
join izler_prove on izler_mapping.id_izler_prove = izler_prove.id and izler_prove.record_attivo = 1
join izler_mapping_prove on izler_mapping.id_izler_prove = izler_mapping_prove.id_izler_prove
join izler_categoria_prove on izler_mapping_prove.id_izler_categoria_prove = izler_categoria_prove.id
 where izler_mapping.record_attivo = 1 and  id_izler_struttura = " . $_GET['struttura'] . " and id_izsler_categoria_matrice = " . $_GET['categoria'] . " and id_izler_campione = " . $_GET['campione'];
}
$result = mysqli_query($conn, $query);
$value = [];
while ($row = mysqli_fetch_assoc($result)) {
 $row['descrizione'] = utf8_encode($row['descrizione']);
 $value[] = $row;
}
$test2 = json_encode($value);
echo $test2;
