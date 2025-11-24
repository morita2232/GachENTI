<?php
session_start();

require("config.php");
require("template.php");

$title = "Cartas";
$id = "cards";

openHTML($title, $id);
writeHeader();

// Conexión DB
$conn = mysqli_connect($server, $db_user, $db_pass, $db_db);
if (!$conn) {
    $datos = "<p>Error DB: no se pudo conectar a la base de datos</p>";
    writeMain($datos);
    closeHTML();
    exit;
}

/*
 * Listado de TODAS las cartas reales del sistema,
 * ordenadas por precio (mayor a menor).
 * Precio siempre mínimo 1.
 */
$query = "
SELECT 
    cards.id_card,
    CASE 
        WHEN cards.price IS NULL OR cards.price <= 0 THEN 1 
        ELSE cards.price 
    END AS price,
    card_templates.card AS card_name,
    card_templates.description AS description,
    card_templates.image AS card_image,
    t.type AS card_type,
    r.rarity AS card_rarity
FROM cards
LEFT JOIN card_templates 
    ON cards.id_card_template = card_templates.id_card_template
LEFT JOIN card_types t 
    ON card_templates.id_card_type = t.id_card_type
LEFT JOIN card_rarities r 
    ON card_templates.id_card_rarity = r.id_card_rarity
ORDER BY price DESC
";

$res = mysqli_query($conn, $query);
if (!$res) {
    $datos = "<p>Error DB: fallo en la consulta de cartas</p>";
    writeMain($datos);
    closeHTML();
    exit;
}

$cards_html = "<section><h2>Listado de cartas</h2>";

if (mysqli_num_rows($res) === 0) {
    $cards_html .= "<p>Todavía no hay cartas generadas.</p>";
} else {
    $cards_html .= "<ul>";
    while ($row = mysqli_fetch_assoc($res)) {

        $id_card  = intval($row["id_card"]);
        $name     = htmlspecialchars($row["card_name"]);
        $price    = (float)$row["price"];
        $desc     = nl2br(htmlspecialchars((string)$row["description"]));
        $ctype    = htmlspecialchars((string)$row["card_type"]);
        $crarity  = htmlspecialchars((string)$row["card_rarity"]);
        $img      = htmlspecialchars((string)$row["card_image"]);

        $cards_html .= "<li><article>
            <h3>{$name} (ID carta: {$id_card})</h3>
            <p><strong>Tipo:</strong> {$ctype} — <strong>Rareza:</strong> {$crarity}</p>
            <p><strong>Precio:</strong> {$price} €</p>";

        if (!empty($img)) {
            $cards_html .= "
            <figure>
                <img src=\"imgs/{$img}\" alt=\"{$name}\" class=\"card-img\" />
            </figure>";
        }

        if (!empty($desc)) {
            $cards_html .= "<p>{$desc}</p>";
        }

        $cards_html .= "</article></li>";
    }
    $cards_html .= "</ul>";
}

$cards_html .= "</section>";

writeMain($cards_html);

mysqli_close($conn);
closeHTML();
?>

