<?php

/* ---------- Validate form ---------- */
if (!isset($_POST["username"], $_POST["password"])) {
    die("Error 1: Formulario no enviado");
}

if (strlen($_POST["username"]) < 3 || strlen($_POST["username"]) > 16) {
    die("Error 2: Nombre de usuario no tiene un tamaño correcto");
}

if (strlen($_POST["password"]) < 4) {
    die("Error 3: Contraseña muy corta");
}

/* ---------- Sanitize ---------- */
$username = addslashes($_POST["username"]);
if ($username !== $_POST["username"]) {
    die("Error 4: El usuario está mal formado");
}

$password = addslashes($_POST["password"]);
if ($password !== $_POST["password"]) {
    die("Error 5: La contraseña está mal formada");
}

/* ---------- Hash ---------- */
$password = md5($password);

/* ---------- DB ---------- */
require_once("config.php");

$conn = mysqli_connect($server, $db_user, $db_pass, $db_db);
if (!$conn) {
    die("Error DB 1: Error en la conexión");
}

/* ---------- Query ---------- */
$query = <<<EOD
SELECT id_user
FROM users
WHERE username='{$username}'
  AND password='{$password}'
EOD;

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Error DB 2: Error al realizar la petición");
}

if (mysqli_num_rows($result) !== 1) {
    die("Error 6: El usuario o la contraseña son incorrectos");
}

$user = mysqli_fetch_assoc($result);

/* ---------- Login ---------- */
session_start();
$_SESSION["id_user"] = $user["id_user"];

header("Location: dashboard.php");
exit();

