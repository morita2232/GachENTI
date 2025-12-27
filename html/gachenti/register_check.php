<?php

/* ---------- Method check ---------- */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Error 1: Formulario no enviado correctamente");
}

/* ---------- Required fields ---------- */
$required = [
    "name", "surname", "username", "email",
    "birth_year", "password", "password2", "id_user_type"
];

foreach ($required as $field) {
    if (!isset($_POST[$field]) || trim($_POST[$field]) === "") {
        die("Error 2: Falta el campo {$field}");
    }
}

/* ---------- Collect ---------- */
$name        = trim($_POST["name"]);
$surname     = trim($_POST["surname"]);
$username    = trim($_POST["username"]);
$email       = trim($_POST["email"]);
$birth_year  = (int)$_POST["birth_year"];
$password    = $_POST["password"];
$password2   = $_POST["password2"];
$id_user_type= (int)$_POST["id_user_type"];

/* ---------- Username ---------- */
if (strlen($username) < 3 || strlen($username) > 16) {
    die("Error 3: El nombre de usuario debe tener entre 3 y 16 caracteres");
}

if (!preg_match('/^[A-Za-z0-9_]+$/', $username)) {
    die("Error 3b: El usuario solo puede contener letras, números y _");
}

/* ---------- Password ---------- */
if (strlen($password) < 4) {
    die("Error 4: Contraseña demasiado corta");
}

if ($password !== $password2) {
    die("Error 5: Las contraseñas no coinciden");
}

/* ---------- Email ---------- */
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Error 6: Email incorrecto");
}

/* ---------- Name ---------- */
if ($name === "" || $surname === "") {
    die("Error 7: Nombre o apellidos incorrectos");
}

/* ---------- Age ---------- */
$current_year = (int)date("Y");
if (($current_year - $birth_year) < 18) {
    die("Error 8: Debes ser mayor de edad para registrarte");
}

/* ---------- DB ---------- */
require_once("config.php");

$conn = mysqli_connect($server, $db_user, $db_pass, $db_db);
if (!$conn) {
    die("Error DB 1: Error en la conexión a la base de datos");
}

/* ---------- Escape ---------- */
$name_db     = mysqli_real_escape_string($conn, $name);
$surname_db  = mysqli_real_escape_string($conn, $surname);
$username_db = mysqli_real_escape_string($conn, $username);
$email_db    = mysqli_real_escape_string($conn, $email);

/* ---------- Duplicate check ---------- */
$query = "
SELECT id_user
FROM users
WHERE username='{$username_db}'
   OR email='{$email_db}'
LIMIT 1
";

$res = mysqli_query($conn, $query);
if (!$res) {
    die("Error DB 2: Error al comprobar usuario existente");
}

if (mysqli_num_rows($res) > 0) {
    die("Error 9: Ya existe un usuario con ese nombre o email");
}

/* ---------- Insert ---------- */
$password_hashed = md5($password); // legacy, compatible with login
$birthdate  = "{$birth_year}-01-01";
$funds      = "0.00";
$registered = date("Y-m-d H:i:s");
$status     = 1;

$insert = "
INSERT INTO users
(name, surname, username, email, password, birthdate, funds, registered, status, id_user_type)
VALUES
('{$name_db}', '{$surname_db}', '{$username_db}', '{$email_db}',
 '{$password_hashed}', '{$birthdate}', '{$funds}', '{$registered}',
 {$status}, {$id_user_type})
";

if (!mysqli_query($conn, $insert)) {
    die("Error DB 3: Error al insertar usuario");
}

/* ---------- Auto login ---------- */
$last_id = mysqli_insert_id($conn);

session_start();
$_SESSION["id_user"] = $last_id;

header("Location: dashboard.php");
exit();

