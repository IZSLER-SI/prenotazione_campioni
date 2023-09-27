<?php

ob_start();
session_start();
include(dirname(dirname(__FILE__)) . '/header.php');
include(dirname(dirname(__FILE__)) . '/objects/class_connection.php');
include(dirname(dirname(__FILE__)) . '/objects/class_setting.php');
include(dirname(dirname(__FILE__)) . '/objects/class_xlsx.php');

use Plivo\Hangup;
use Shuchkin\SimpleXLSX;
//check if session admin
if (isset($_SESSION['ct_laboratorioid'])) {
} else {
    header('Location: /');
    exit();
}
error_reporting(E_ALL);
ini_set('display_errors', 'On');
$con = new prenotazione_campioni_db();
$conn = $con->connect();
$setting = new prenotazione_campioni_setting();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo '<pre>';
    if ($_FILES['file']['name']) {
        $filename = explode(".", $_FILES['file']['name']);
        if ($filename[1] == 'xlsx') {
            $handle = $_FILES['file']['tmp_name'];
            $xlsx = SimpleXLSX::parse($handle);
            $tipo_mapping = $_POST['tipo_mapping'];
            handle_mapping($xlsx, $tipo_mapping);
        } else {
            echo "Invalid file format. Only xlsx files are allowed.";
        }
    }
} else {
    echo '
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="file" />
        <input type="submit" name="submit" value="Upload" />
        <select name="tipo_mapping">
            <option value="ufficiale">Ufficiale</option>
            <option value="autocontrollo">Autocontrollo</option>
            <option value="conoscitivo">Conoscitivo</option>
            <option value="chimici">Chimici</option>
        </select>
    </form>';
}
function handle_mapping($xlsx, $tipo_mapping) {
    $shift = false;
    foreach ($xlsx->rows() as $key => $row) {
        if ($key > 0) {
            if ($shift == true) {
                array_shift($row);
            }
            switch ($tipo_mapping) {
                case 'ufficiale':
                    handle_ufficiale($row);
                    break;
                case 'autocontrollo':
                    handle_autocontrollo($row);
                    break;
                case 'conoscitivo':
                    handle_conoscitivo($row);
                    break;
                case 'chimici':
                    handle_chimici($row);
                    break;
                default:
                    break;
            }
        } else {
            if (strpos($row[1], 'sede') !== false) {
                $shift = true;
            }
        }
    }
}
function handle_chimici($row){
  global $conn;
  $conn->query('SET NAMES utf8');
  /*
   sede_di_consegna	reparto	categoria_matrice	finalita	prova	Peso_prova	Avviso possibile prenotazione gestibile da backoffice	 da attivare
   */
  $sede                = trim($row[0]);
  $laboratorio         = trim($row[1]);
  $categoria_matrice   = trim($row[2]);
  $finalita            = trim($row[3]);
  $prova               = trim($row[4]);
  $peso                = trim($row[5]);
  $query = "  SELECT
                  f.id AS finalita_id,
                  l.id AS laboratorio_id,
                  p.id AS prova_id,
                  s.id as sede_id,
                  c.id as categoria_matrice_id
              FROM
                  izler_finalita f,
                  izler_laboratori l,
                  izler_prove p,
                  izler_strutture s,
                  izler_categorie_matrici c
              WHERE
                  f.descrizione like '%$finalita%' AND
                  l.descrizione like '%$laboratorio%' AND
                  p.descrizione like '%$prova%' AND
                  s.descrizione like '%$sede%' AND
                  c.descrizione like '%$categoria_matrice%'
  ";
  $result = mysqli_query($conn, $query);
  if ($result->num_rows > 0) {
      $info = mysqli_fetch_assoc($result);
      $finalita_id            = trim($info['finalita_id']);
      $laboratorio_id         = trim($info['laboratorio_id']);
      $prova_id               = trim($info['prova_id']);
      $sede_id                = trim($info['sede_id']);
      $categoria_matrice_id   = trim($info['categoria_matrice_id']);
      $query = "INSERT INTO izler_mapping (
          id_izler_struttura,
          id_izler_campione,
          id_izler_finalita,
          id_izler_laboratorio,
          peso_prova,
          id_izler_prove,
          id_izsler_categoria_matrice
      ) VALUES (
          '$sede_id',
          '0',
          '$finalita_id',
          '$laboratorio_id',
          '$peso',
          '$prova_id',
          '$categoria_matrice_id'
      )";
      echo $query . ";\n";
  } else {
      echo 'Not found '.$query."\n";
  }
}
function handle_conoscitivo($row) {
    global $conn;
    $conn->query('SET NAMES utf8');
    $sede                = $row[0];
    $laboratorio         = $row[1];
    $categoria_matrice   = $row[2];
    $finalita             = $row[3];
    $prova               = $row[4];
    $query = "  SELECT 
                    f.id AS finalita_id, 
                    l.id AS laboratorio_id, 
                    p.id AS prova_id,
                    s.id as sede_id,
                    c.id as categoria_matrice_id
                FROM 
                    izler_finalita f, 
                    izler_laboratori l, 
                    izler_prove p,
                    izler_strutture s,
                    izler_categorie_matrici c
                WHERE 
                    f.descrizione like '%$finalita%' AND 
                    l.descrizione like '%$laboratorio%' AND 
                    p.descrizione like '%$prova%' AND
                    s.descrizione like '%$sede%' AND
                    c.descrizione like '%$categoria_matrice%'
    ";
    $result = mysqli_query($conn, $query);
    if ($result->num_rows > 0) {
        $info = mysqli_fetch_assoc($result);
        $finalita_id             = trim($info['finalita_id']);
        $laboratorio_id         = trim($info['laboratorio_id']);
        $prova_id               = trim($info['prova_id']);
        $sede_id                = trim($info['sede_id']);
        $categoria_matrice_id   = trim($info['categoria_matrice_id']);
        $query = "INSERT INTO izler_mapping (
            id_izler_struttura,
            id_izler_campione,
            id_izler_finalita,
            id_izler_laboratorio,
            peso_prova,
            id_izler_prove,
            id_izsler_categoria_matrice
        ) VALUES (
            $sede_id,
            NULL,
            $finalita_id,
            $laboratorio_id,
            1,
            $prova_id,
            $categoria_matrice_id
        )";
        echo $query . ";\n";
    } else {
        echo "NOT FOUND: $sede, $laboratorio, $categoria_matrice, $finalita, $prova \n";
    }
    return null;
}
function handle_autocontrollo($row) {
    global $conn;
    $conn->query('SET NAMES utf8');
    $sede                = trim($row[0]);
    $laboratorio         = trim($row[1]);
    $categoria_matrice   = trim($row[2]);
    $campione            = trim($row[3]);
    $prova               = trim($row[4]);

    $sede                = str_replace("'", "\'", $sede);
    $laboratorio         = str_replace("'", "\'", $laboratorio);
    $categoria_matrice   = str_replace("'", "\'", $categoria_matrice);
    $campione            = str_replace("'", "\'", $campione);
    $prova               = str_replace("'", "\'", $prova);

    $query = "  SELECT 
                    l.id AS laboratorio_id, 
                    p.id AS prova_id,
                    s.id as sede_id,
                    c.id as categoria_matrice_id,
                    ca.id as campione_id
                FROM 
                    izler_laboratori l, 
                    izler_prove p,
                    izler_strutture s,
                    izler_categorie_matrici c,
                    izler_campione ca
                WHERE 
                    l.descrizione like '%$laboratorio%' AND 
                    p.descrizione like '%$prova%' AND
                    s.descrizione like '%$sede%' AND
                    c.descrizione like '%$categoria_matrice%' AND
                    ca.descrizione like '%$campione%'
    ";
    $result = mysqli_query($conn, $query);
    if (!empty($result) && $result->num_rows > 0) {
        $info = mysqli_fetch_assoc($result);
        $laboratorio_id         = $info['laboratorio_id'];
        $prova_id               = $info['prova_id'];
        $sede_id                = $info['sede_id'];
        $categoria_matrice_id   = $info['categoria_matrice_id'];
        $campione_id            = $info['campione_id'];
        $query = "INSERT INTO izler_mapping (
            id_izler_struttura,
            id_izler_campione,
            id_izler_finalita,
            id_izler_laboratorio,
            peso_prova,
            id_izler_prove,
            id_izsler_categoria_matrice
        ) VALUES (
            $sede_id,
            $campione_id,
            NULL,
            $laboratorio_id,
            1,
            $prova_id,
            $categoria_matrice_id
        )";
        echo $query . ";\n";
    } else {
        echo "NOT FOUND: $sede, $laboratorio, $categoria_matrice, $campione, $prova \n";
    }
    return null;
}
function handle_ufficiale($row) {
    return null;
}
