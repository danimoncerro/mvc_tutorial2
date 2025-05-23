<?php
$title = 'Products List';
ob_start();
?>
<h1>Products <span class="badge bg-secondary"><?= $totalProducts ?></span></h1>
<a href='<?= BASE_URL ?>products/create' class='btn btn-primary'>Adaugă produs</a>
<form method="GET" class="mb-3">
    <div class="row g-2 align-items-center">
        <div class="col-auto">
            <label for="category_id" class="col-form-label">Filtre:</label>
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
        <div class="col-auto">
            <select name="sort" class="form-select" onchange="this.form.submit()">
                <option value="name" <?= (isset($_GET['sort']) && $_GET['sort'] == 'name') ? 'selected' : '' ?>>Sortează după nume</option>
                <option value="price" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price') ? 'selected' : '' ?>>Sortează după preț</option>
                <option value="category_name" <?= (isset($_GET['sort']) && $_GET['sort'] == 'category_name') ? 'selected' : '' ?>>Sortează după categorie</option>
            </select>
        </div>
        <div class="col-auto">
            <select name="order" class="form-select" onchange="this.form.submit()">
                <option value="asc" <?= (isset($_GET['order']) && $_GET['order'] == 'asc') ? 'selected' : '' ?>>Crescător</option>
                <option value="desc" <?= (isset($_GET['order']) && $_GET['order'] == 'desc') ? 'selected' : '' ?>>Descrescător</option>
            </select>
        </div>
        <div class="col-auto">
            <select name="per_page" class="form-select" onchange="this.form.submit()">
                <?php foreach ([3, 5, 10, 20] as $opt): ?>
                    <option value="<?= $opt ?>" <?= (isset($_GET['per_page']) && $_GET['per_page'] == $opt) ? 'selected' : '' ?>>
                        <?= $opt ?> / pagină
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <input type="number" name="min_price" class="form-control" placeholder="Preț minim"
                   value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>" min="0" step="0.01">
        </div>
        <div class="col-auto">
            <input type="number" name="max_price" class="form-control" placeholder="Preț maxim"
                   value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>" min="0" step="0.01">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-primary">Filtrează</button>
        </div>
    </div>
</form>
<form method="GET" class="mb-3 d-flex align-items-center">
    <input type="text" name="search" class="form-control me-2" placeholder="Caută produs..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    <button type="submit" class="btn btn-outline-primary">Caută</button>
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
            <a class="page-link" href="<?= BASE_URL ?>products?page=<?= max(1, $page - 1) ?>&per_page=<?= $perPage ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="<?= BASE_URL ?>products?page=<?= $i ?>&per_page=<?= $perPage ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= BASE_URL ?>products?page=<?= min($totalPages, $page + 1) ?>&per_page=<?= $perPage ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';