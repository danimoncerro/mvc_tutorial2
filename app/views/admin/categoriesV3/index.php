<?php
$title = "Lista de categorii - exercitiul3";
ob_start();
?>

<p>Hello world</p>



<?php
$content = ob_get_clean();
require_once APP_ROOT.'/app/views/layout.php';

