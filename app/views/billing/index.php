<?php
$title = 'billing';

ob_start();
?>

<h1> BILLING ADDRESS </h1>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';