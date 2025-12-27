<?php
require("template.php");

$title = "Login / Registro";
$id    = "login";

openHTML($title, $id);
writeHeader();

/* ---------- Birth year selector ---------- */
$year_now = date("Y");
$year_options = "";
for ($y = $year_now; $y >= 1930; $y--) {
    $year_options .= "<option value=\"{$y}\">{$y}</option>";
}

/* ---------- Render ---------- */
$datos = <<<EOD
<section>
    <h2>Formulario de Login</h2>
    <form method="POST" action="login_check.php">
        <p>
            <label for="login_username">Usuario</label>
            <input type="text" name="username" id="login_username" required>
        </p>
        <p>
            <label for="login_password">Contraseña</label>
            <input type="password" name="password" id="login_password" required>
        </p>
        <p><input type="submit" value="Login"></p>
    </form>
</section>

<section>
    <h2>Formulario de Registro</h2>
    <form method="POST" action="register_check.php">

        <p>
            <label for="reg_name">Nombre</label>
            <input type="text" name="name" id="reg_name" required>
        </p>

        <p>
            <label for="reg_surname">Apellidos</label>
            <input type="text" name="surname" id="reg_surname" required>
        </p>

        <p>
            <label for="reg_username">Usuario</label>
            <input type="text" name="username" id="reg_username" required>
        </p>

        <p>
            <label for="reg_email">Email</label>
            <input type="email" name="email" id="reg_email" required>
        </p>

        <p>
            <label for="reg_birthyear">Año de nacimiento</label>
            <select name="birth_year" id="reg_birthyear" required>
                {$year_options}
            </select>
        </p>

        <p>
            <label for="reg_password">Contraseña</label>
            <input type="password" name="password" id="reg_password" required>
        </p>

        <p>
            <label for="reg_password2">Repetir contraseña</label>
            <input type="password" name="password2" id="reg_password2" required>
        </p>

        <!-- Usuario normal -->
        <input type="hidden" name="id_user_type" value="2">

        <p><input type="submit" value="Registrarse"></p>
    </form>
</section>
EOD;

writeMain($datos);
closeHTML();

