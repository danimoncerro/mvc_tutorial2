<?php

$title = 'Adaugă produs';
ob_start();
//$categoryModel = new Category();
$categories = $categoryModel->all();
?>

<h1 class="mb-4">Adaugă un produs nou</h1>
<form action="<?= BASE_URL ?>products/store" method="POST" class="w-50 mx-auto p-4 border rounded shadow-sm bg-white">
    <div class="mb-3">
        <label for="name" class="form-label">Nume produs:</label>
        <input type="text" id="name" name="name" class="form-control" value="" required autocomplete="off">
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Preț:</label>
        <input type="number" id="price" name="price" class="form-control" value="" required min="0" step="0.01" autocomplete="off">
    </div>
    <div class="mb-3">
        <label for="category_id" class="form-label">Categorie:</label>
        <select id="category_id" name="category_id" class="form-select" required>
            <option value="" selected disabled>Alege categoria</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Adaugă produs</button>
</form>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';