<?php
session_start();


if (!isset($_POST["id_user"], $_POST["id_card"])) {
    die("Error: formulario no enviado");
}

$seller_id = intval($_POST["id_user"]);
$card_id   = intval($_POST["id_card"]);

if ($seller_id <= 0 || $card_id <= 0) {
    die("Error: datos incorrectos");
}


if (!isset($_SESSION["id_user"])) {
    header("Location: index.php");
    exit();
}

$buyer_id = intval($_SESSION["id_user"]);

if ($buyer_id === $seller_id) {
    die("Error: no puedes comprar tu propia carta");
}


require_once("config.php");

$conn = mysqli_connect($server, $db_user, $db_pass, $db_db);
if (!$conn) {
    die("ERROR DB: no se pudo conectar a la base de datos");
}


$query = "
SELECT price
FROM cards
WHERE id_card = {$card_id}
";

$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) !== 1) {
    die("Error: carta incorrecta");
}

$card = mysqli_fetch_assoc($result);
$price = (int)$card["price"];


$query = "
SELECT funds
FROM users
WHERE id_user = {$buyer_id}
";

$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) !== 1) {
    die("Error: usuario comprador inválido");
}

$buyer = mysqli_fetch_assoc($result);
if ($buyer["funds"] < $price) {
    die("Error: fondos insuficientes");
}


mysqli_begin_transaction($conn);


$query = "
UPDATE users
SET funds = funds - {$price}
WHERE id_user = {$buyer_id}
";
if (!mysqli_query($conn, $query)) {
    mysqli_rollback($conn);
    die("Error: no se pudo descontar el dinero");
}


$query = "
UPDATE users
SET funds = funds + {$price}
WHERE id_user = {$seller_id}
";
if (!mysqli_query($conn, $query)) {
    mysqli_rollback($conn);
    die("Error: no se pudo pagar al vendedor");
}


$query = "
UPDATE user_cards
SET id_user = {$buyer_id}
WHERE id_card = {$card_id}
  AND id_user = {$seller_id}
";
if (!mysqli_query($conn, $query)) {
    mysqli_rollback($conn);
    die("Error: no se pudo transferir la carta");
}

mysqli_commit($conn);

$query = "
INSERT INTO logs
(price, discount, state, id_user_seller, id_user_buyer, id_card)
VALUES
({$price}, 0, 1, {$seller_id}, {$buyer_id}, {$card_id})
";

mysqli_query($conn, $query);

header("Location: cards.php");
exit();

