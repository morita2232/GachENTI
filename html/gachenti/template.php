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

<title>{$title}</title>

</head>

<body{$html_id}>
EOD;

}

function writeHeader ()
{

echo <<<EOD
<header>

	<h1>gachENTI</h1>

<nav>

	<menu>

		<li><a href="index.php">Portada</a></li>
		<li><a href="cards.php">Cartas</a></li>
		<li><a href="shop.php">Compra/Venta</a></li>
		<li><a href="login.php">Login/Registro</a></li>

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
