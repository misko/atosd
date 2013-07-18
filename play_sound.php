<?php

$z=intval($_GET["x"]);	
$s='/srv/http/play_sound.sh ' . $z;
echo exec($s);

?>
