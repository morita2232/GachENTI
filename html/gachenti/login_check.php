<?php

if (!isset($_POST["username"]) || !isset($_POST["password"])){

	die("Error 1: Formulario no enviado");

}

if (strlen($_POST["username"]) < 3 || strlen($_POST["username"]) > 16){

	die("Error 2: Nombre de usuario no tiene un tamaño correcto");

}


if(strlen($_POST["password"]) < 4){

	die("Error 3: Contraseña muy corta");
}

$username = addslashes($_POST["username"]);

if ($username != $_POST["username"]){
	die("Error 4: El usuario esta mal formado");
}

$password = addslashes($_POST["password"]);

if ($password != $_POST["password"]){
	die("Error 5: La contraseña esta mal formada");
}

$password = md5($password);

$query = <<<EOD
SELECT id_user
FROM users
WHERE
	username='{$username}'
	AND password='{$password}'

EOD;

$conn = mysqli_connect("localhost", "enti", "enti", "gachenti_db");

if (!$conn) {

die("Error DB 1: Error en la conexion");

}

$result = mysqli_query($conn, $query);

if (!$result) {

die("Error DB 2: Error al realizar la peticion");

}

if (mysqli_num_rows($result) != 1){

die("Error 6: El usuario o el password son erroneos");

}

$user = mysqli_fetch_assoc($result);

//echo $user["id_user"];

session_start();

$_SESSION["id_user"] = $user["id_user"];

header("Location: dashboard.php");

exit();

?>
