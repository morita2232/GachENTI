<?php
session_start();


if (!isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit();
}

$id_user = intval($_SESSION['id_user']);

require_once("config.php");

$conn = mysqli_connect($server, $db_user, $db_pass, $db_db);
if (!$conn) {
    die("Error de conexión a la base de datos");
}


$query = "SELECT * FROM users WHERE id_user=" . $id_user;
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) != 1) {
    header("Location: index.php");
    exit();
}

$user = mysqli_fetch_assoc($result);

require("template.php");

$title = "Dashboard cartas";
$id = "dashboard_cards";

openHTML($title, $id);

writeHeader();

$datos = <<<EOD
<article>
    <h2>Dashboard</h2>
    <menu>
        <li><a href="dashboard.php">Perfil</a></li>
        <li><a href="dashboard_cards.php"><strong>Cartas</strong></a></li>
    </menu>
EOD;


if ($user["id_user"] == 1) {

    $datos .= <<<EOD

    <section>
        <h3>Cartas</h3>
EOD;

    $query = <<<EOD
SELECT cards.id_card,
       card_templates.card,
       cards.price,
       card_templates.image
FROM cards
LEFT JOIN card_templates ON cards.id_card_template = card_templates.id_card_template
LEFT JOIN user_cards ON cards.id_card = user_cards.id_card
LEFT JOIN users ON users.id_user = user_cards.id_user
EOD;

    $result = mysqli_query($conn, $query);

    while ($card = mysqli_fetch_assoc($result)) {
        $datos .= <<<EOD
        <article>
            <h4>{$card["card"]}</h4>
            <figure>
               <img src="imgs/{$card["image"]}" alt="{$card["card"]}" class="card-img" />

            </figure>
            <p>Precio: {$card["price"]}</p>
        </article>
EOD;
    }

    // Tipos de cartas
    $query = "SELECT id_card_type, type FROM card_types";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("ERROR NO HAY TIPOS DE CARTAS");
    }

    $options_cards_types = "";
    while ($card_type = mysqli_fetch_assoc($result)) {
        $options_cards_types .= <<<EOD

        <option value="{$card_type["id_card_type"]}">
            {$card_type["type"]}
        </option>

EOD;
    }

    
    $query = "SELECT id_card_rarity, rarity FROM card_rarities";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("ERROR NO HAY RAREZAS DE CARTAS");
    }

    $options_cards_rarities = "";
    while ($card_rarity = mysqli_fetch_assoc($result)) {
        $options_cards_rarities .= <<<EOD

        <option value="{$card_rarity["id_card_rarity"]}">
            {$card_rarity["rarity"]}
        </option>

EOD;
    }

    $datos .= <<<EOD
    </section>
</article>

<form method="POST" action="dashboard_card_check.php">
    <p><label for="card_name">Card: </label><input type="text" name="card_name" id="card_name" /></p>

    <p><label for="card_type">Type: </label>
        <select name="card_type" id="card_type">
            {$options_cards_types}
        </select>
    </p>

    <p><label for="card_rarity">Rarity: </label>
        <select name="card_rarity" id="card_rarity">
            {$options_cards_rarities}
        </select>
    </p>

    <p><label for="card_price">Price: </label><input type="number" name="card_price" id="card_price" /></p>
    <p><label for="card_image">Image: </label><input type="text" name="card_image" id="card_image" /></p>
    <p><input type="submit" value="Añade la carta" /></p>
</form>

EOD;

} else {
    

    $datos .= <<<EOD

<form method="POST" action="get_lucky.php">
    <p><input type="submit" value="¡Voy a tener suerte!" /></p>
</form>

<section>
    <h3>Mis cartas</h3>
EOD;

    $query = <<<EOD
SELECT cards.id_card,
       card_templates.card,
       cards.price,
       card_templates.image
FROM cards
LEFT JOIN card_templates ON cards.id_card_template = card_templates.id_card_template
LEFT JOIN user_cards ON cards.id_card = user_cards.id_card
WHERE user_cards.id_user = $id_user
EOD;

    $result = mysqli_query($conn, $query);

    while ($card = mysqli_fetch_assoc($result)) {
        $datos .= <<<EOD
        <article>
            <h4>{$card["card"]}</h4>
            <figure>
                <img src="imgs/{$card["image"]}" alt="{$card["card"]}" class="card-img" />

            </figure>
            <p>Precio: {$card["price"]}</p>
        </article>
EOD;
    }

    $datos .= <<<EOD
</section>
</article>
EOD;
}

writeMain($datos);

closeHTML();
?>

