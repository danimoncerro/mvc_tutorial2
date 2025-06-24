<?php
$title = 'Categories List';
ob_start();

// Inițializează sortarea dacă nu vine din controller
$sort = $_GET['sort'] ?? 'id';
$order = $_GET['order'] ?? 'asc';
?>
<h1>Categories</h1>
<a href='<?= BASE_URL ?>categories/create' class='btn btn-primary'>Adaugă categorie</a>

<form method="GET" class="mb-3 d-flex align-items-center">
    <label for="per_page" class="me-2">Categorie pe pagină:</label>
    <select name="per_page" id="per_page" class="form-select w-auto me-2" onchange="this.form.submit()">
        <?php foreach ([3, 5, 10, 20] as $opt): ?>
            <option value="<?= $opt ?>" <?= (isset($_GET['per_page']) && $_GET['per_page'] == $opt) || (!isset($_GET['per_page']) && $perPage == $opt) ? 'selected' : '' ?>>
                <?= $opt ?> / pagină
            </option>
        <?php endforeach; ?>
    </select>
</form>

<table class="table">
    <thead>
        <tr>
            <th>
                <a href="<?= BASE_URL ?>categories?sort=id&order=<?= ($sort === 'id' && $order === 'asc') ? 'desc' : 'asc' ?>&per_page=<?= $perPage ?>">
                    ID <?= $sort === 'id' ? ($order === 'asc' ? '▲' : '▼') : '' ?>
                </a>
            </th>
            <th>
                <a href="<?= BASE_URL ?>categories?sort=name&order=<?= ($sort === 'name' && $order === 'asc') ? 'desc' : 'asc' ?>&per_page=<?= $perPage ?>">
                    Nume <?= $sort === 'name' ? ($order === 'asc' ? '▲' : '▼') : '' ?>
                </a>
            </th>
            <th>Acțiuni</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $category): ?>
        <?php
            $editUrl = BASE_URL . "categories/edit?id=" . $category['id'];
            $deleteUrl = BASE_URL . "categories/delete?id=" . $category['id'];
        ?>
        <tr>
            <td><?= htmlspecialchars($category['id']) ?></td>
            <td><?= htmlspecialchars($category['name']) ?></td>
            <td>
                <a href='<?=$editUrl ?>'>✏️ Editează</a> 
                <form action='<?=$deleteUrl ?>' method='POST' class='d-inline m-0 p-0'>
                    <button type="submit" onclick="return confirm('Ești sigur că vrei să ștergi această categorie?');">🗑️ Șterge</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<nav>
    <ul class="pagination">
        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= BASE_URL ?>categories?page=<?= max(1, $page - 1) ?>&per_page=<?= $perPage ?>&sort=<?= $sort ?>&order=<?= $order ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="<?= BASE_URL ?>categories?page=<?= $i ?>&per_page=<?= $perPage ?>&sort=<?= $sort ?>&order=<?= $order ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= BASE_URL ?>categories?page=<?= min($totalPages, $page + 1) ?>&per_page=<?= $perPage ?>&sort=<?= $sort ?>&order=<?= $order ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>

<script>
    console.log('Categories loaded with sorting and pagination');
    axios.get('<?= BASE_URL ?>api/categories', {
        params: {
            per_page: <?= $perPage ?>,
            page: <?= $page ?>,
            sort: '<?= $sort ?>',
            order: '<?= $order ?>'
        }
    }).then(response => {
        console.log('API Response:', response.data);
    }).catch(error => {
        console.error('API Error:', error);
    }); 
</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';