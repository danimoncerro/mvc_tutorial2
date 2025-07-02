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
            <select name="category_id" class="form-select" onchange="this.form.submit()">
                <option value="">Toate categoriile</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
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
            <input type="text" name="search" class="form-control" placeholder="Caută produs..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-primary">Filtrează</button>
            <a href="<?= BASE_URL ?>products" class="btn btn-secondary">Resetează filtre</a>
        </div>
    </div>
</form>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>
                <a href="<?= BASE_URL ?>products?sort=name&order=<?= ($sort === 'name' && $order === 'asc') ? 'desc' : 'asc' ?>
    &category_id=<?= urlencode($_GET['category_id'] ?? '') ?>
    &min_price=<?= urlencode($_GET['min_price'] ?? '') ?>
    &max_price=<?= urlencode($_GET['max_price'] ?? '') ?>
    &per_page=<?= $perPage ?>
    &search=<?= urlencode($_GET['search'] ?? '') ?>">
                    Nume <?= $sort === 'name' ? ($order === 'asc' ? '▲' : '▼') : '' ?>
                </a>
            </th>
            <th>
                <a href="<?= BASE_URL ?>products?sort=price&order=<?= ($sort === 'price' && $order === 'asc') ? 'desc' : 'asc' ?>
    &category_id=<?= urlencode($_GET['category_id'] ?? '') ?>
    &min_price=<?= urlencode($_GET['min_price'] ?? '') ?>
    &max_price=<?= urlencode($_GET['max_price'] ?? '') ?>
    &per_page=<?= $perPage ?>
    &search=<?= urlencode($_GET['search'] ?? '') ?>">
                    Preț <?= $sort === 'price' ? ($order === 'asc' ? '▲' : '▼') : '' ?>
                </a>
            </th>
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
                
                <a class="page-link" href="<?= BASE_URL . 'products?page=' . $i . '&per_page=' . $perPage . '&category_id=' . urlencode($_GET['category_id'] ?? '') . '&min_price=' . urlencode($_GET['min_price'] ?? '') . '&max_price=' . urlencode($_GET['max_price'] ?? '') . '&sort=' . $sort . '&order=' . $order . '&search=' . urlencode($_GET['search'] ?? '') ?>">
                    <?= $i ?>

                </a>            
            </li>
        <?php endfor; ?>
        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= BASE_URL ?>products?page=<?= min($totalPages, $page + 1) ?>&per_page=<?= $perPage ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>

<script>
axios.get('<?= BASE_URL ?>api/products', {
    params: {
        per_page: <?= $perPage ?>,
        page: <?= $page ?>,
        sort: '<?= $sort ?>',
        order: '<?= $order ?>',
        category_id: '<?= $_GET['category_id'] ?? '' ?>',
        min_price: '<?= $_GET['min_price'] ?? '' ?>',
        max_price: '<?= $_GET['max_price'] ?? '' ?>',
        search: '<?= $_GET['search'] ?? '' ?>'
    }
})
.then(response => {
    console.log('API Response:', response.data);
    const products = response.data.products;
    const tableBody = document.getElementById('apiProductsTable');

    tableBody.innerHTML = '';

    products.forEach(product => {
        const row = document.createElement('tr');

        const editUrl = '<?= BASE_URL ?>products/edit?id=' + product.id;
        const deleteUrl = '<?= BASE_URL ?>products/delete?id=' + product.id;

        row.innerHTML = `
            <td>${product.id}</td>
            <td>${product.name}</td>
            <td>${product.price}</td>
            <td>${product.category_name ?? 'Fără categorie'}</td>
            <td>
                <a href="${editUrl}" class="btn btn-sm btn-warning me-1">
                    ✏️ Editează
                </a>
                <form action="${deleteUrl}" method="POST" style="display:inline;" onsubmit="return confirm('Ești sigur că vrei să ștergi acest produs?');">
                    <button class="btn btn-sm btn-danger">🗑️ Șterge</button>
                </form>
            </td>
        `;

        tableBody.appendChild(row);
    });

})
.catch(error => {
    console.error('API Error:', error);
});

</script>

<h4>Products API Table (Axios)</h4>
<table class="table table-striped table-hover table-bordered">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Nume</th>
            <th>Preț</th>
            <th>Categorie</th>
            <th>Acțiuni</th>
        </tr>
    </thead>
    <tbody id="apiProductsTable">
        <!-- Datele vor fi generate aici -->
    </tbody>
</table>



<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';