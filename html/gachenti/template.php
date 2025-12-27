<?php

function openHTML(string $title = "", string $id = ""): void
{
    if ($title === "") {
        $title = "gachENTI: Tu Gacha de cartas de profes de ENTI";
    }

    $html_id = ($id !== "") ? " id=\"{$id}\"" : "";

    echo <<<EOD
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{$title}</title>
    <link rel="stylesheet" href="gachenti.css">
</head>
<body{$html_id}>
EOD;
}

function writeHeader(): void
{
    /* ---------- Dynamic menu ---------- */
    if (isset($_SESSION["id_user"])) {
        $auth_links = <<<EOD
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="logout.php">Log out</a></li>
EOD;
    } else {
        $auth_links = <<<EOD
        <li><a href="login.php">Login / Registro</a></li>
EOD;
    }

    echo <<<EOD
<header>
    <h1>gachENTI</h1>

    <nav>
        <menu>
            <li><a href="index.php">Portada</a></li>
            <li><a href="cards.php">Cartas</a></li>
			<li><a href="cards.php">Compra / Venta</a></li>

            {$auth_links}
        </menu>
    </nav>
</header>
EOD;
}

function writeMain(string $content): void
{
    echo <<<EOD
<main>
{$content}
</main>
EOD;
}

function closeHTML(): void
{
    echo <<<EOD
<footer>
</footer>
</body>
</html>
EOD;
}

