<?php

$title = 'Adaugă categorie';
ob_start();
//$categoryModel = new Category();
$categories = $categoryModel->all();
?>

<h1 class="mb-4">Adaugă o categorie noua</h1>
<form action="<?= BASE_URL ?>categories/store" method="POST" class="w-50 mx-auto p-4 border rounded shadow-sm bg-white">
    <div class="mb-3">
        <label for="name" class="form-label">Nume categorie:</label>
        <input type="text" id="name" name="name" class="form-control" value="" required autocomplete="off">
    </div>
    <button type="submit" class="btn btn-primary">Adaugă cateorie</button>
</form>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';