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

$title = "Dashboard";
$id = "dashboard";

openHTML($title, $id);

writeHeader();

$datos = <<<EOD

<header>
    <h2>DASHBOARD</h2>
    <menu>
        <li><a href="dashboard.php">Profile</a></li>
        <li><a href="dashboard_cards.php">Cartas</a></li>
    </menu>

    <section>
        <h3>Perfil</h3>
        <ul>
            <li>Nombre: {$user["username"]}</li>
            <li>email: {$user["email"]}</li>
        </ul>
    </section>
</header>

EOD;

writeMain($datos);

closeHTML();
?>

