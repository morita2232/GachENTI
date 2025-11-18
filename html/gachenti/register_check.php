<?php


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Error 1: Formulario no enviado correctamente");
}


$required = ["name", "surname", "username", "email", "birth_year", "password", "password2", "id_user_type"];

foreach ($required as $r) {
    if (!isset($_POST[$r]) || trim($_POST[$r]) === "") {
        die("Error 2: Falta el campo " . htmlspecialchars($r));
    }
}


$name = trim($_POST["name"]);
$surname = trim($_POST["surname"]);
$username = trim($_POST["username"]);
$email = trim($_POST["email"]);
$birth_year = (int) $_POST["birth_year"];
$password = $_POST["password"];
$password2 = $_POST["password2"];
$id_user_type = (int) $_POST["id_user_type"];


if (strlen($username) < 3 || strlen($username) > 16) {
    die("Error 3: Nombre de usuario debe tener entre 3 y 16 caracteres");
}


if (!preg_match('/^[A-Za-z0-9_]+$/', $username)) {
    die("Error 3b: Usuario sólo puede contener letras, números y guion bajo");
}

if (strlen($password) < 4) {
    die("Error 4: Contraseña muy corta (mínimo 4 caracteres)");
}

if ($password !== $password2) {
    die("Error 5: Las contraseñas no coinciden");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Error 6: Email incorrecto");
}

if (strlen($name) < 1 || strlen($surname) < 1) {
    die("Error 7: Nombre o apellidos demasiado cortos");
}


$current_year = (int) date("Y");
$age = $current_year - $birth_year;
if ($age < 18) {
    die("Error 8: Debes ser mayor de edad (>=18) para registrarte - año de nacimiento indicado: {$birth_year}");
}


$conn = mysqli_connect("localhost", "enti", "enti", "gachenti_db");
if (!$conn) {
    die("Error DB 1: Error en la conexión a la base de datos");
}


$name_db = mysqli_real_escape_string($conn, $name);
$surname_db = mysqli_real_escape_string($conn, $surname);
$username_db = mysqli_real_escape_string($conn, $username);
$email_db = mysqli_real_escape_string($conn, $email);


$q = "SELECT id_user FROM users WHERE username='{$username_db}' OR email='{$email_db}' LIMIT 1";
$res = mysqli_query($conn, $q);

if (!$res) {
    die("Error DB 2: Error en la comprobación de usuario existente");
}

if (mysqli_num_rows($res) > 0) {
    die("Error 9: Ya existe un usuario con ese nombre de usuario o email");
}


$password_hashed = md5($password);
$birthdate = "{$birth_year}-01-01"; // Sólo año, guardamos el 1 de enero de ese año
$funds = "0.00";
$registered = date("Y-m-d H:i:s");
$status = 1;
$id_user_type_db = (int) $id_user_type;

$insert = "INSERT INTO users (name, surname, username, email, password, birthdate, funds, registered, status, id_user_type)
VALUES ('{$name_db}', '{$surname_db}', '{$username_db}', '{$email_db}', '{$password_hashed}', '{$birthdate}', '{$funds}', '{$registered}', {$status}, {$id_user_type_db})";

$ins_res = mysqli_query($conn, $insert);

if (!$ins_res) {
    die("Error DB 3: Error al insertar usuario: " . mysqli_error($conn));
}

$last_id = mysqli_insert_id($conn);

echo "Usuario insertado correctamente. ID: " . intval($last_id);

mysqli_close($conn);
?>

