<?php
session_start();

/* ---------- Session ---------- */
if (!isset($_SESSION["id_user"])) {
    header("Location: index.php");
    exit();
}

$id_user = (int)$_SESSION["id_user"];

/* ---------- Template & config ---------- */
require("template.php");
require_once("config.php");

$title = "Get Lucky!";
$id    = "get_lucky";

openHTML($title, $id);
writeHeader();

/* ---------- DB ---------- */
$conn = mysqli_connect($server, $db_user, $db_pass, $db_db);
if (!$conn) {
    writeMain("<p>Error DB: no se pudo conectar</p>");
    closeHTML();
    exit;
}

/* ---------- Logic ---------- */
$num_cards = 5;
$query_template = "SELECT * FROM card_templates ORDER BY RAND() LIMIT 1";

$datos = "<section><h2>¡Tus nuevas cartas!</h2><article id=\"get_lucky_cards\">";

for ($i = $num_cards; $i > 0; $i--) {

    $result = mysqli_query($conn, $query_template);
    if (!$result || mysqli_num_rows($result) === 0) {
        die("ERROR: no hay cartas en las plantillas");
    }

    $card = mysqli_fetch_assoc($result);

    /* ---------- Card values ---------- */
    $name  = htmlspecialchars($card["card"]);
    $img   = htmlspecialchars($card["image"]);
    $state = rand(80, 100);

    $base_price = isset($card["initial_price"]) ? (int)$card["initial_price"] : rand(5, 20);
    if ($base_price <= 0) {
        $base_price = rand(5, 20);
    }

    $min = max(1, $base_price - 4);
    $max = max($min, $base_price + 4);
    $price = rand($min, $max);

    /* ---------- Insert card ---------- */
    $tpl_id = (int)$card["id_card_template"];

    $query = "
    INSERT INTO cards (price, state, id_card_template)
    VALUES ({$price}, {$state}, {$tpl_id})
    ";

    if (!mysqli_query($conn, $query)) {
        die("Error al insertar la carta");
    }

    $id_card = mysqli_insert_id($conn);

    /* ---------- Assign to user ---------- */
    $query = "
    INSERT INTO user_cards (id_user, id_card)
    VALUES ({$id_user}, {$id_card})
    ";

    if (!mysqli_query($conn, $query)) {
        die("Error al asignar la carta al usuario");
    }

    /* ---------- Render ---------- */
    $datos .= <<<EOD
    <section class="get_lucky_card fade{$i}">
        <figure>
            <img src="imgs/{$img}" alt="{$name}" class="card-img">
            <figcaption>{$name}</figcaption>
        </figure>
        <p><strong>Estado:</strong> {$state}</p>
        <p><strong>Precio:</strong> {$price} €</p>
    </section>
EOD;
}

$datos .= "</article></section>";

writeMain($datos);

mysqli_close($conn);
closeHTML();

