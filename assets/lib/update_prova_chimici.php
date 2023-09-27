<?php
header('Content-Type: text/json; charset=utf-8');
ob_start();
session_start();
include(dirname(dirname(dirname(__FILE__))) . '/header.php');
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_connection.php');
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_setting.php');

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$con = new prenotazione_campioni_db();
$conn = $con->connect();
$setting = new prenotazione_campioni_setting();

$id = $_POST['id'];
$giorno_prefissato = $_POST['giorno_prefissato'];
//sanitize user input
$giorno_prefissato = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($giorno_prefissato)));
$conn->query('SET NAMES utf8');
$query = "update izler_prove set giorno_prefissato='" . $giorno_prefissato . "' where id=" . $id;
$result = mysqli_query($conn, $query);
echo json_encode('done');
