<?php
$title = 'Home Page';
ob_start();
?>
<h1>Welcome to the Home Page</h1>
<p>This is the home page of your MVC application.</p>
<?php
$content = ob_get_clean();
require_once 'layout.php';