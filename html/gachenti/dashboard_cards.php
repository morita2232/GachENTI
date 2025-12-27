<?php
session_start();

/* ---------- Session check ---------- */
if (!isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit();
}

$id_user = (int)$_SESSION['id_user'];

/* ---------- DB ---------- */
require_once("config.php");

$conn = mysqli_connect($server, $db_user, $db_pass, $db_db);
if (!$conn) {
    die("Error de conexión a la base de datos");
}

/* ---------- Load user ---------- */
$query = "SELECT id_user, username FROM users WHERE id_user={$id_user}";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) !== 1) {
    header("Location: index.php");
    exit();
}

$user = mysqli_fetch_assoc($result);
$is_admin = ($user["id_user"] == 1);

/* ---------- Template ---------- */
require("template.php");

$title = "Dashboard cartas";
$id    = "dashboard_cards";

openHTML($title, $id);
writeHeader();

/* ---------- Header ---------- */
/* ========================================================= */
/* ======================= ADMIN =========================== */
/* ========================================================= */

if ($is_admin) {

    $datos .= "<section><h3>Cartas del sistema</h3>";

    $query = "
    SELECT
        c.id_card,
        ct.card,
        c.price,
        ct.image
    FROM cards c
    LEFT JOIN card_templates ct ON c.id_card_template = ct.id_card_template
    LEFT JOIN user_cards uc ON c.id_card = uc.id_card
    ";

    $result = mysqli_query($conn, $query);

    while ($card = mysqli_fetch_assoc($result)) {
        $name  = htmlspecialchars($card["card"]);
        $img   = htmlspecialchars($card["image"]);
        $price = htmlspecialchars($card["price"]);

        $datos .= <<<EOD
        <article>
            <h4>{$name}</h4>
            <figure>
                <img src="imgs/{$img}" alt="{$name}" class="card-img">
            </figure>
            <p>Precio: {$price}</p>
        </article>
EOD;
    }

    /* ---------- Card types ---------- */
    $types = "";
    $res = mysqli_query($conn, "SELECT id_card_type, type FROM card_types");
    while ($row = mysqli_fetch_assoc($res)) {
        $types .= "<option value=\"{$row["id_card_type"]}\">{$row["type"]}</option>";
    }

    /* ---------- Card rarities ---------- */
    $rarities = "";
    $res = mysqli_query($conn, "SELECT id_card_rarity, rarity FROM card_rarities");
    while ($row = mysqli_fetch_assoc($res)) {
        $rarities .= "<option value=\"{$row["id_card_rarity"]}\">{$row["rarity"]}</option>";
    }

    $datos .= <<<EOD
    </section>

    <form method="POST" action="dashboard_card_check.php">
        <p><label>Card: <input type="text" name="card_name"></label></p>
        <p><label>Type:
            <select name="card_type">{$types}</select>
        </label></p>
        <p><label>Rarity:
            <select name="card_rarity">{$rarities}</select>
        </label></p>
        <p><label>Price: <input type="number" name="card_price"></label></p>
        <p><label>Image: <input type="text" name="card_image"></label></p>
        <p><input type="submit" value="Añadir carta"></p>
    </form>
EOD;

/* ========================================================= */
/* ======================= USER ============================ */
/* ========================================================= */

} else {

    $datos .= <<<EOD
    <form method="POST" action="get_lucky.php">
        <p><input type="submit" value="¡Voy a tener suerte!"></p>
    </form>

    <section>
        <h3>Mis cartas</h3>
EOD;

    $query = "
    SELECT
        c.id_card,
        ct.card,
        c.price,
        ct.image
    FROM cards c
    LEFT JOIN card_templates ct ON c.id_card_template = ct.id_card_template
    LEFT JOIN user_cards uc ON c.id_card = uc.id_card
    WHERE uc.id_user = {$id_user}
    ";

    $result = mysqli_query($conn, $query);

    while ($card = mysqli_fetch_assoc($result)) {
        $name  = htmlspecialchars($card["card"]);
        $img   = htmlspecialchars($card["image"]);
        $price = htmlspecialchars($card["price"]);

        $datos .= <<<EOD
        <article>
            <h4>{$name}</h4>
            <figure>
                <img src="imgs/{$img}" alt="{$name}" class="card-img">
            </figure>
            <p>Precio: {$price}</p>
        </article>
EOD;
    }

    $datos .= "</section>";
}

$datos .= "</article>";

writeMain($datos);

mysqli_close($conn);
closeHTML();

