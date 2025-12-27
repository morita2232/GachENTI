<?php
session_start();

/* ---------- Session check ---------- */
if (!isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit();
}

$id_user = (int)$_SESSION['id_user'];

/* ---------- DB connection ---------- */
require_once("config.php");

$conn = mysqli_connect($server, $db_user, $db_pass, $db_db);
if (!$conn) {
    die("Error de conexión a la base de datos");
}

/* ---------- Load user ---------- */
$query = "SELECT id_user, username, email, funds FROM users WHERE id_user={$id_user}";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) !== 1) {
    header("Location: index.php");
    exit();
}

$user = mysqli_fetch_assoc($result);

$username = htmlspecialchars($user["username"]);
$email    = htmlspecialchars($user["email"]);

$is_admin = ($user["id_user"] == 1);
$funds = number_format((float)$user["funds"], 2);


/* ---------- Template ---------- */
require("template.php");

$title = "Dashboard";
$id    = "dashboard";

openHTML($title, $id);
writeHeader();

if (!$is_admin) {

    $datos .= <<<EOD
    <section>
        <h3>Perfil</h3>
        <ul>
			<li>Nombre: {$username}</li>
            <li>Email: {$email}</li>
            <li>Dinero: {$funds} €</li>
		</ul>
		
		<p>
    		<a href="dashboard_cards.php" class="btn">
        		Ver mis cartas
    		</a>
		</p>

	</section>
EOD;

} else {
    $query = "
    SELECT
        l.transaction,
        l.price,
        ub.username AS buyer,
        us.username AS seller,
        ct.card AS card_name
    FROM logs l
    JOIN users ub ON l.id_user_buyer = ub.id_user
    JOIN users us ON l.id_user_seller = us.id_user
    JOIN cards c ON l.id_card = c.id_card
    JOIN card_templates ct ON c.id_card_template = ct.id_card_template
    ORDER BY l.transaction DESC
    ";

    $res = mysqli_query($conn, $query);

    $datos .= "<section><h3>Registro de transacciones</h3><ul>";

    while ($row = mysqli_fetch_assoc($res)) {
        $card  = htmlspecialchars($row["card_name"]);
        $buyer = htmlspecialchars($row["buyer"]);
        $seller= htmlspecialchars($row["seller"]);
        $price = $row["price"];
        $date  = $row["transaction"];

        $datos .= "
        <li>
            <strong>{$card}</strong> —
            {$buyer} compró a {$seller}
            por {$price} € ({$date})
        </li>";
    }

    $datos .= "</ul></section>";
}

$datos .= "</article>";


writeMain($datos);

mysqli_close($conn);
closeHTML();

