<?php

session_start();

require("config.php");
require("template.php");

if(!isset($_SESSION["id_user"])) {

	header("Location: index.php");
	exit();

}

$buyer_id = $_SESSION["id_user"];

$conn = mysqli_connect($server, $db_user, $db_pass, $db_db);

if (!$conn) { 
	die("ERROR DB: no se pudo conectar a la base de datos");
}



$seller_id = $_POST["id_user"];
$sold_card = $_POST["id_card"];

$query = "
SELECT funds
FROM users
WHERE id_user = {$buyer_id}";

$result = mysqli_query($conn, $query);

$buyer = mysqli_fetch_assoc($result);
$buyer_funds = $buyer["funds"];

$query = "
UPDATE users u
JOIN cards c ON c.id_card = {$sold_card}
SET u.funds = u.funds - c.price
WHERE u.id_user = {$buyer_id};";


$result = mysqli_query($conn, $query);

$query = "
UPDATE users u
JOIN cards c ON c.id_card = {$sold_card}
SET u.funds = u.funds +  c.price
WHERE u.id_user = {$seller_id};";

$result = mysqli_query($conn, $query);

$query = "
UPDATE user_cards u
JOIN cards c ON c.id_card = {$sold_card}
SET u.id_user = {$buyer_id}
WHERE u.id_user = {$seller_id};";

$result = mysqli_query($conn, $query);


?>
