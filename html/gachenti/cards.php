<?php
session_start();

require("config.php");
require("template.php");

$title = "Cartas";
$id = "cards";

openHTML($title, $id);
writeHeader();

/* ---------- DB connection ---------- */
$conn = mysqli_connect($server, $db_user, $db_pass, $db_db);
if (!$conn) {
    writeMain("<p>Error DB: no se pudo conectar</p>");
    closeHTML();
    exit;
}

/* ---------- Cards query ---------- */
$query = "
SELECT
    c.id_card,
    CASE
        WHEN c.price IS NULL OR c.price <= 0 THEN 1
        ELSE c.price
    END AS price,
    ct.card AS card_name,
    ct.description,
    ct.image,
    t.type AS card_type,
    r.rarity AS card_rarity,
    u.id_user AS owner_id,
    u.username AS owner_name
FROM cards c
LEFT JOIN user_cards uc ON c.id_card = uc.id_card
LEFT JOIN users u ON uc.id_user = u.id_user
LEFT JOIN card_templates ct ON c.id_card_template = ct.id_card_template
LEFT JOIN card_types t ON ct.id_card_type = t.id_card_type
LEFT JOIN card_rarities r ON ct.id_card_rarity = r.id_card_rarity
ORDER BY price DESC
";

$res = mysqli_query($conn, $query);
if (!$res) {
    writeMain("<p>Error DB: fallo en la consulta</p>");
    closeHTML();
    exit;
}

/* ---------- Render ---------- */
$html = "<section><h2>Listado de cartas</h2>";

if (mysqli_num_rows($res) === 0) {
    $html .= "<p>No hay cartas.</p>";
} else {
    $html .= "<ul>";

    while ($row = mysqli_fetch_assoc($res)) {
        $id_card = (int)$row["id_card"];
        $price   = (float)$row["price"];
        $name    = htmlspecialchars((string)$row["card_name"]);
        $desc    = nl2br(htmlspecialchars((string)$row["description"]));
        $type    = htmlspecialchars((string)$row["card_type"]);
        $rarity  = htmlspecialchars((string)$row["card_rarity"]);
        $img     = htmlspecialchars((string)$row["image"]);
        $ownerId = (int)$row["owner_id"];
        $owner   = htmlspecialchars((string)$row["owner_name"]);

        $html .= "<li><article>
            <h3>{$name} (ID {$id_card})</h3>
            <p><strong>Tipo:</strong> {$type} — <strong>Rareza:</strong> {$rarity}</p>
            <p><strong>Precio:</strong> {$price} €</p>
            <p><strong>Propietario:</strong> {$owner}</p>";

        if ($img) {
            $html .= "<figure>
                <img src=\"imgs/{$img}\" alt=\"{$name}\" class=\"card-img\">
            </figure>";
        }

        if ($desc) {
            $html .= "<p>{$desc}</p>";
        }

        /* Show buy button only if logged and not owner */
        if (
            isset($_SESSION["id_user"]) &&
            $ownerId > 0 &&
            $_SESSION["id_user"] != $ownerId
        ) {
            $html .= "
            <form method=\"POST\" action=\"card_buy.php\">
                <input type=\"hidden\" name=\"id_user\" value=\"{$ownerId}\">
                <input type=\"hidden\" name=\"id_card\" value=\"{$id_card}\">
                <input type=\"submit\" value=\"Comprar\">
            </form>";
        }

        $html .= "</article></li>";
    }

    $html .= "</ul>";
}

$html .= "</section>";

writeMain($html);

mysqli_close($conn);
closeHTML();

