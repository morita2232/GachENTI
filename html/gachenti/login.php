<?php

require("template.php");

$title = "Log in";

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

EOD;

writeMain($datos);

closeHTML();

?>
