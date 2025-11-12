<?php

require("template.php");

$title = "GachENTI!";

$id = "portada";

openHTML($title, $id);

writeHeader();


$datos = <<<EOD

<article>
<h2>La carta mas cara</h2>
</article>

EOD;

writeMain($datos);

closeHTML();

?>
