<?php
session_start();

if(!isset($_SESSION["id_user"]) || intval($_SESSION["id_user"]) !== 1) {
    header("Location: index.php");
    exit();
}

require_once("config.php");

$conn = mysqli_connect($server, $db_user, $db_pass, $db_db);
if (!$conn) {
    die("Error DB: no se pudo conectar a la base de datos");
}


$card_name   = isset($_POST["card_name"])   ? trim($_POST["card_name"])   : "";
$card_type   = isset($_POST["card_type"])   ? intval($_POST["card_type"]) : 0;
$card_rarity = isset($_POST["card_rarity"]) ? intval($_POST["card_rarity"]) : 0;
$card_price  = isset($_POST["card_price"])  ? floatval($_POST["card_price"]) : 0;
$card_image  = isset($_POST["card_image"])  ? trim($_POST["card_image"])  : "";


if ($card_name === "" || $card_type <= 0 || $card_rarity <= 0) {
    die("Faltan datos obligatorios para la carta.");
}


if ($card_price <= 0) {
    $card_price = 1;
}


$description = "";


$card_name_esc  = mysqli_real_escape_string($conn, $card_name);
$card_image_esc = mysqli_real_escape_string($conn, $card_image);
$description_esc = mysqli_real_escape_string($conn, $description);


$query_insert = "
INSERT INTO card_templates (card, initial_price, description, image, id_card_type, id_card_rarity)
VALUES ('{$card_name_esc}', {$card_price}, '{$description_esc}', '{$card_image_esc}', {$card_type}, {$card_rarity});
";

$result = mysqli_query($conn, $query_insert);

if (!$result) {
    die("ERROR AL INSERTAR LA PLANTILLA DE CARTA");
}

mysqli_close($conn);


header("Location: dashboard_cards.php");
exit();
?>

