<?php

require("template.php");

$title = "Log in / Registro";

$id = "login";

openHTML($title, $id);

writeHeader();


$datos = <<<EOD

<section>
	<h2>Formulario de Login</h2>
		<form method="POST" action="login_check.php">
			<p><label for="login_username">Usuario</label><input type="text" name="username" id="login_username" /></p>
			<p><label for="login_password">Contraseña</label><input type="password" name="password" id="login_password" /></p>
			<p><input type="submit" value="Login" /></p>

		</form>
</section>

<section>
	<h2>Formulario de Registro</h2>
		<form method="POST" action="register_check.php">
		<p><label for="reg_name">Name</label><input type="text" name="name" id="reg_name" required /></p>
		<p><label for="reg_surname">Apellidos</label><input type="text" name="surname" id="reg_surname" required /></p>
    <p><label for="reg_username">Usuario</label><input type="text" name="username" id="reg_username" required /></p>
    <p><label for="reg_email">Email</label><input type="email" name="email" id="reg_email" required /></p>

    <p><label for="reg_birthyear">Año de nacimiento</label>
      <select name="birth_year" id="reg_birthyear" required>

EOD;

echo $datos;

$year_now = date("Y");
for ($y = $year_now; $y >= 1930; $y--) {
	echo "<option value=\"{$y}\">{$y}</option>\n";
}

$datos2 = <<<EOD
	</select>
	</p>

	
    <p><label for="reg_password">Contraseña</label><input type="password" name="password" id="reg_password" required /></p>
    <p><label for="reg_password2">Repetir Contraseña</label><input type="password" name="password2" id="reg_password2" required /></p>

    <!-- id_user_type por defecto 2 (usuario normal) -->
    <input type="hidden" name="id_user_type" value="2" />

    <p><input type="submit" value="Registrarse" /></p>
  </form>
</section>
EOD;

echo $datos2;

writeMain("");

closeHTML();

?>
