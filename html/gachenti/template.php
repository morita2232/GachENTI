<?php

function openHTML ($title = "", $id="")
{

    if ($title == ""){
        $title = "gachENTI: Tu Gacha de cartas de profes de ENTI";
    }

    $html_id = "";

    if ($id != ""){
        $html_id = " id=\"".$id."\"";
    }

echo <<<EOD
<!doctype html>

<html>

<head>

    <meta charset="utf-8" />
    <title>{$title}</title>

    <style>

        .card-img {
            max-width: 180px;
            height: auto;
            border-radius: 6px;
            display: block;
        }

        article {
            margin-bottom: 1rem;
        }
    </style>

</head>

<body{$html_id}>
EOD;

}

function writeHeader ()
{

$login_logout = <<<EOD


        <li><a href="login.php">Login/Registro</a></li>
EOD;


if (isset($_SESSION["id_user"])){

$login_logout = <<<EOD


        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="logout.php">Log out/Registro</a></li>

EOD;

}

echo <<<EOD
<header>

    <h1>gachENTI</h1>

<nav>

    <menu>

        <li><a href="index.php">Portada</a></li>
        <li><a href="cards.php">Cartas</a></li>
        <li><a href="shop.php">Compra/Venta</a></li>
        {$login_logout}

    </menu>

</nav>

</header>
EOD;

}

function writeMain ($content)
{

echo <<<EOD
<main>
{$content}
</main>
EOD;

}


function closeHTML ()
{

echo <<<EOD
<footer>

</footer>

</body>

</html>
EOD;

}

?>

