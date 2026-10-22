<?php
$title = 'Thank You Page';
ob_start();
?>

<h1>Comanda a fost plasata cu succes, va multumim frumos!</h1>

<?php
$content = ob_get_clean();
require_once 'layout.php';