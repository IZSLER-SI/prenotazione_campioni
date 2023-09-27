<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'On');
ob_start();
include(dirname(dirname(__FILE__)) . '/header.php');
include(dirname(dirname(__FILE__)) . '/objects/class_connection.php');
include(dirname(dirname(__FILE__)) . '/objects/class_setting.php');
include(dirname(dirname(__FILE__)) . '/objects/class_xlsx_gen.php');
use Shuchkin\SimpleXLSXGen;
//check if session admin
//if (isset($_SESSION['ct_accettazioneid'])) {
//} else {
//    header('Location: /');
//    exit();
//}
$con = new prenotazione_campioni_db();
$conn = $con->connect();
$conn->query('SET NAMES utf8');
$setting = new prenotazione_campioni_setting();

$query = '
SELECT izler_mapping.id_izler_mapping,
       izler_strutture.descrizione         AS sede,
       izler_laboratori.descrizione        AS laboratorio,
       izler_categorie_matrici.descrizione AS categoria_matrice,
       izler_finalita.descrizione          AS finalita,
       izler_campione.descrizione          AS tipo_campione,
       izler_prove.descrizione             AS prova,
        izler_mapping.peso_prova           AS peso_prova
FROM izler_mapping
         left JOIN izler_strutture
              ON (izler_mapping.id_izler_struttura = izler_strutture.id AND izler_strutture.record_attivo = 1)
         JOIN izler_prove ON (izler_mapping.id_izler_prove = izler_prove.id AND izler_prove.record_attivo = 1)
         left JOIN izler_laboratori
              ON (izler_mapping.id_izler_laboratorio = izler_laboratori.id AND izler_laboratori.record_attivo = 1)
         left JOIN izler_categorie_matrici ON (izler_mapping.id_izsler_categoria_matrice = izler_categorie_matrici.id AND
                                          izler_categorie_matrici.record_attivo = 1)
         left JOIN izler_finalita
              ON (izler_mapping.id_izler_finalita = izler_finalita.id AND izler_finalita.record_attivo = 1)
         left JOIN izler_campione ON (izler_mapping.id_izler_campione = izler_campione.id AND izler_campione.record_attivo = 1)
WHERE izler_mapping.record_attivo = 1';

$result = mysqli_query($conn, $query);
$mappings = array();
$mappings[] = [
    '<b>id_izler_mapping</b>',
    '<b>Sede di consegna</b>',
    '<b>Laboratorio</b>',
    '<b>Categoria matrice</b>',
    '<b>Finalita</b>',
    '<b>Tipo campione</b>',
    '<b>Prova</b>',
    '<b>Peso prova</b>',
];
if (!empty($result) && $result->num_rows > 0) {
    while ($row = mysqli_fetch_row($result)) {
        $mappings[] = $row;
    }
}
$xlsx = Shuchkin\SimpleXLSXGen::fromArray($mappings);
$name = 'Prenotazione campioni IZSLER - Mapping '.date('d-m-Y').'.xlsx';
$xlsx->downloadAs($name);