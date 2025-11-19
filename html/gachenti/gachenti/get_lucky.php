<?php


session_start();

if (!session_id()){
header("Location: index.php");
exit();
}

if (!isset($_SESSION["id_user"])){

header("Location: index.php");
exit();

}

$id_user = intval($_SESSION["id_user"]);

require("template.php");

$title = "Get Lucky!";

$id = "get_lucky";

openHTML($title, $id);

writeHeader();

$num_cards = 5;

require_once("config.php");

$conn = mysqli_connect($server, $db_user, $db_pass, $db_db);

$query = "SELECT * FROM card_templates ORDER BY RAND() LIMIT 1;";


$datos = "";

for ($i = $num_cards; $i > 0; $i--) {
	$result = mysqli_query($conn, $query);

	if(!$result) {
		
		die("ERROR NO HAY CARTAS");

	}

	$card = mysqli_fetch_assoc($result);

	$datos .= <<<EOD

	<article>
		<h4>{$card["card"]}</h4>
		<figure>
			<img src="imgs/{$card["image"]}" />
		</figure>
	</article>


EOD;


$card_state = rand(80, 100);

$card_price = 0;

if(isset($card["price"])){
$card_price = rand ($crad["price"] - 4, $card["price"]);

}


$query_insert = <<<EOD

INSERT INTO cards (price, state, id_card_template)
VALUES({$card_price}, {$card_state}, {$card["id_card_template"]});


EOD;

$result = mysqli_query($conn, $query_insert);



if (!$result){

die("ERROR AL INSERTART UNA CARTA");

}

$id_card = mysqli_insert_id($conn);


$query_user_card = <<<EOD

INSERT INTO user_cards (id_user, id_card)
VALUES({$id_user}, {$id_card});

EOD;

$result = mysqli_query($conn, $query_user_card);
if(!$result){
	
die("ERROR AL INSERTAR UNA CARTA");

}

}

writeMain($datos);

closeHTML();

?>
