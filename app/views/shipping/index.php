<?php
$title = 'Shipping';

ob_start();
?>

<h1> SHIPPING ADDRESS </h1>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';