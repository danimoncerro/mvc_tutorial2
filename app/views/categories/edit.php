<?php
$title = 'Editează categorie';
ob_start();
?>

<h1 class="mb-4">Editează categoria</h1>
<form action="<?= BASE_URL ?>categories/update" method="POST" class="w-50 mx-auto p-4 border rounded shadow-sm bg-white">
    <input type="hidden" name="id" value="<?= htmlspecialchars($category['id']) ?>">
    <div class="mb-3">
        <label for="name" class="form-label">Nume categorie:</label>
        <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($category['name']) ?>" required autocomplete="off">
    </div>
    <button type="submit" class="btn btn-primary">Actualizează</button>
</form>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';
