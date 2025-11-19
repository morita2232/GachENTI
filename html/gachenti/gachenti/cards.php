<?php
require("template.php");

$title = "Cartas";
$id = "cards";

openHTML($title, $id);
writeHeader();

$conn = mysqli_connect("localhost", "enti", "enti", "gachenti_db");
if (!$conn) {
    $datos = "<p>Error DB: no se pudo conectar a la base de datos</p>";
    writeMain($datos);
    closeHTML();
    exit;
}

$query = "
SELECT ct.id_card_template, ct.card AS template_name, ct.initial_price, ct.description,
       t.type AS card_type, r.rarity AS card_rarity
FROM card_templates ct
LEFT JOIN card_types t ON ct.id_card_type = t.id_card_type
LEFT JOIN card_rarities r ON ct.id_card_rarity = r.id_card_rarity
ORDER BY ct.id_card_template ASC
";

$res = mysqli_query($conn, $query);
if (!$res) {
    $datos = "<p>Error DB: fallo en la consulta de plantillas</p>";
    writeMain($datos);
    closeHTML();
    exit;
}

$cards_html = "<section><h2>Listado de plantillas de cartas</h2><ul>";
while ($row = mysqli_fetch_assoc($res)) {
    $id_template = intval($row["id_card_template"]);
    $name = htmlspecialchars($row["template_name"]);
    $price = htmlspecialchars($row["initial_price"]);
    $desc = nl2br(htmlspecialchars($row["description"]));
    $ctype = htmlspecialchars($row["card_type"]);
    $crarity = htmlspecialchars($row["card_rarity"]);

    $cards_html .= "<li><article><h3>{$name} (ID: {$id_template})</h3>
        <p><strong>Tipo:</strong> {$ctype} — <strong>Rareza:</strong> {$crarity}</p>
        <p><strong>Precio inicial:</strong> {$price} €</p>
        <p>{$desc}</p></article></li>";
}
$cards_html .= "</ul></section>";

writeMain($cards_html);
mysqli_close($conn);

closeHTML();
?>

