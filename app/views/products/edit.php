<?php
$title = 'Editează produs';
ob_start();
?>

<h1>Editează produs</h1>

<form action="<?= BASE_URL ?>products/update" method="POST">    <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">

    <label for="name">Nume produs:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
    <br>

    <label for="price">Preț:</label>
    <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required>
    <br>

    <button type="submit">Actualizează</button>
</form>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';
