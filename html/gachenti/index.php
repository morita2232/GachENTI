<?php
session_start();

require("config.php");
require("template.php");

$title = "GachENTI!";
$id    = "portada";

openHTML($title, $id);
writeHeader();

/* ---------- DB ---------- */
$conn = mysqli_connect($server, $db_user, $db_pass, $db_db);
if (!$conn) {
    writeMain("<p>Error DB: no se pudo conectar a la base de datos</p>");
    closeHTML();
    exit;
}

/* ---------- Most expensive card ---------- */
$queryMost = "
SELECT
    c.id_card,
    CASE
        WHEN c.price IS NULL OR c.price <= 0 THEN 1
        ELSE c.price
    END AS price,
    ct.card AS card_name,
    ct.image AS card_image
FROM cards c
LEFT JOIN card_templates ct ON c.id_card_template = ct.id_card_template
ORDER BY price DESC
LIMIT 1
";

$resMost = mysqli_query($conn, $queryMost);
$mostCard = ($resMost && mysqli_num_rows($resMost) === 1)
    ? mysqli_fetch_assoc($resMost)
    : null;

/* ---------- Last generated card ---------- */
$queryLast = "
SELECT
    c.id_card,
    CASE
        WHEN c.price IS NULL OR c.price <= 0 THEN 1
        ELSE c.price
    END AS price,
    ct.card AS card_name,
    ct.image AS card_image
FROM cards c
LEFT JOIN card_templates ct ON c.id_card_template = ct.id_card_template
ORDER BY c.id_card DESC
LIMIT 1
";

$resLast = mysqli_query($conn, $queryLast);
$lastCard = ($resLast && mysqli_num_rows($resLast) === 1)
    ? mysqli_fetch_assoc($resLast)
    : null;

/* ---------- Render ---------- */
$datos = "<article>";

/* ===== Most expensive ===== */
$datos .= "<section><h2>La carta más cara</h2>";

if ($mostCard) {
    $name  = htmlspecialchars($mostCard["card_name"]);
    $price = (float)$mostCard["price"];
    $img   = htmlspecialchars((string)$mostCard["card_image"]);

    $datos .= "<article>
        <h3>{$name}</h3>
        <p><strong>Precio:</strong> {$price} €</p>";

    if ($img) {
        $datos .= "<figure>
            <img src=\"imgs/{$img}\" alt=\"{$name}\" class=\"card-img\">
        </figure>";
    }

    $datos .= "</article>";
} else {
    $datos .= "<p>Todavía no hay cartas en el sistema.</p>";
}

$datos .= "</section>";

/* ===== Last generated ===== */
$datos .= "<section><h2>Última carta generada</h2>";

if ($lastCard) {
    $name  = htmlspecialchars($lastCard["card_name"]);
    $price = (float)$lastCard["price"];
    $img   = htmlspecialchars((string)$lastCard["card_image"]);

    $datos .= "<article>
        <h3>{$name}</h3>
        <p><strong>Precio:</strong> {$price} €</p>";

    if ($img) {
        $datos .= "<figure>
            <img src=\"imgs/{$img}\" alt=\"{$name}\" class=\"card-img\">
        </figure>";
    }

    $datos .= "</article>";
} else {
    $datos .= "<p>Todavía no hay cartas generadas.</p>";
}

$datos .= "</section></article>";

writeMain($datos);

mysqli_close($conn);
closeHTML();

