<?php
session_start();

if (!isset($_SESSION["id_user"])) {
    header("Location: index.php");
    exit();
}

$id_user = intval($_SESSION["id_user"]);

require("template.php");
require_once("config.php");

$title = "Get Lucky!";
$id = "get_lucky";

openHTML($title, $id);
writeHeader();

$conn = mysqli_connect($server, $db_user, $db_pass, $db_db);
if (!$conn) {
    $datos = "<p>Error DB: no se pudo conectar a la base de datos</p>";
    writeMain($datos);
    closeHTML();
    exit;
}


$num_cards = 5;

$query_template = "SELECT * FROM card_templates ORDER BY RAND() LIMIT 1;";

$datos = "<section><h2>¡Tus nuevas cartas!</h2>";

for ($i = 0; $i < $num_cards; $i++) {

    $result = mysqli_query($conn, $query_template);
    if (!$result || mysqli_num_rows($result) === 0) {
        die("ERROR: NO HAY CARTAS EN LAS PLANTILLAS");
    }

    $card = mysqli_fetch_assoc($result);


    $card_name  = htmlspecialchars($card["card"]);
    $card_image = htmlspecialchars($card["image"]);

    $card_state = rand(80, 100);


    $base_price = 0;
    if (isset($card["initial_price"])) {
        $base_price = (int)$card["initial_price"];
    }


    if ($base_price <= 0) {

        $base_price = rand(5, 20);
    }


    $min_price = max(1, $base_price - 4);
    $max_price = max($min_price, $base_price + 4);

    $card_price = rand($min_price, $max_price);


    $id_card_template = intval($card["id_card_template"]);

    $query_insert_card = "
        INSERT INTO cards (price, state, id_card_template)
        VALUES ({$card_price}, {$card_state}, {$id_card_template});
    ";

    $result_insert_card = mysqli_query($conn, $query_insert_card);
    if (!$result_insert_card) {
        die("ERROR AL INSERTAR UNA CARTA");
    }


    $id_card = mysqli_insert_id($conn);


    $query_user_card = "
        INSERT INTO user_cards (id_user, id_card)
        VALUES ({$id_user}, {$id_card});
    ";

    $result_user_card = mysqli_query($conn, $query_user_card);
    if (!$result_user_card) {
        die("ERROR AL INSERTAR LA CARTA DEL USUARIO");
    }


    $datos .= <<<EOD
    <article>
        <h4>{$card_name}</h4>
        <p><strong>Estado:</strong> {$card_state}</p>
        <p><strong>Precio:</strong> {$card_price} €</p>
        <figure>
<img src="imgs/{$card["image"]}" alt="{$card["card"]}" class="card-img" />

        </figure>
    </article>
EOD;
}

$datos .= "</section>";

writeMain($datos);

mysqli_close($conn);
closeHTML();
?>

