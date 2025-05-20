<?php

$title = 'Adaugă comanda';
ob_start();


?>

<h1 class="mb-4">Adaugă o comanda noua</h1>
<form action="<?= BASE_URL ?>orders/store" method="POST" class="w-50 mx-auto p-4 border rounded shadow-sm bg-white">
    <div class="mb-3">
        <label for="name" class="form-label">Nume produs:</label>
        <input type="text" id="name" name="name" class="form-control" value="" required autocomplete="off">
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Preț:</label>
        <input type="number" id="price" name="price" class="form-control" value="" required min="0" step="0.01" autocomplete="off">
    </div>
    <div class="mb-3">
        <label for="category_id" class="form-label d-flex justify-content-between align-items-center">
            <span>Categorie:</span>
            <a href="<?= BASE_URL ?>categories" class="btn btn-sm btn-success ms-2">Adaugă o categorie nouă</a>
        </label>
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