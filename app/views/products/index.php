<?php
$title = 'Products List';
ob_start();
?>
<h1>Products <span class="badge bg-secondary"><?= $totalProducts ?></span></h1>
<a href='<?= BASE_URL ?>products/create' class='btn btn-primary'>Adaugă produs</a>
<form method="GET" class="mb-3">
    <div class="row g-2 align-items-center">
        <div class="col-auto">
            <label for="category_id" class="col-form-label">Filtrează după categorie:</label>
        </div>
        <div class="col-auto">
            <select name="category_id" id="category_id" class="form-select" onchange="this.form.submit()">
                <option value="">Toate categoriile</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</form>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nume</th>
            <th>Preț</th>
            <th>Categorie</th>
            <th>Acțiuni</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
        <?php
            $editUrl = BASE_URL . "products/edit?id=" . $product['id'];
            $deleteUrl = BASE_URL . "products/delete?id=" . $product['id'];
        ?>
        <tr>
            <td><?= htmlspecialchars($product['id']) ?></td>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td><?= htmlspecialchars($product['price']) ?></td>
            <td><?= htmlspecialchars($product['category_name'] ?? 'Fără categorie') ?></td>
            <td>
                <a href='<?=$editUrl ?>'>✏️ Editează</a> 
                <form action='<?=$deleteUrl ?>' method='POST' class='d-inline m-0 p-0'>
                    <button type="submit" onclick="return confirm('Ești sigur că vrei să ștergi aceast produs?');">🗑️ Șterge</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<nav>
    <ul class="pagination">
        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= BASE_URL ?>products?page=<?= max(1, $page - 1) ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="<?= BASE_URL ?>products?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= BASE_URL ?>products?page=<?= min($totalPages, $page + 1) ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';