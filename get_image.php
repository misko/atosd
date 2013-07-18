<?php
$homepage = file_get_contents('http://127.0.0.1:8080/?action=snapshot');
header('Content-Type: image/jpeg');
echo $homepage;
?>
