<?php
$title = 'Adauga produs';
ob_start();
?>

<h1>Adaugă un produs nou</h1>
<form action="<?= BASE_URL ?>products/store" method="POST">    <label for="name">Nume produs:</label>
    <input type="text" id="name" name="name" required>
    <br>
    <label for="price">Preț:</label>
    <input type="number" id="price" name="price" step="0.01" required>
    <br>
    <button type="submit">Adaugă produs</button>
</form>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';