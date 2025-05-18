<?php
$title = 'Editează produs';
ob_start();
$categories = $categoryModel->all();
?>

<h1 class="mb-4">Editează produs</h1>
<form action="<?= BASE_URL ?>products/update" method="POST" class="w-50 mx-auto p-4 border rounded shadow-sm bg-white">
    <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
    <div class="mb-3">
        <label for="name" class="form-label">Nume produs:</label>
        <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required autocomplete="off">
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Preț:</label>
        <input type="number" id="price" name="price" class="form-control" value="<?= htmlspecialchars($product['price']) ?>" required min="0" step="0.01" autocomplete="off">
    </div>
    <div class="mb-3">
        <label for="category_id" class="form-label">Categorie:</label>
        <select id="category_id" name="category_id" class="form-select" required>
            <option value="" disabled>Alege categoria</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Actualizează</button>
</form>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';
