<?php
$title = 'Users List';
ob_start();
?>
<h1>Users</h1>
<a href='<?= BASE_URL ?>users/create' class='btn btn-primary'>Adaugă user</a>
<form method="GET" class="mb-3">
    <div class="row g-2 align-items-center">
        <div class="col-auto d-flex align-items-center">
            <label for="role" class="me-2 mb-0">Filtrează după rol:</label>
            <select name="role" id="role" class="form-select w-auto" onchange="this.form.submit()">
                <option value="">Toate rolurile</option>
                <?php foreach ($roles as $roleOption): ?>
                    <option value="<?= htmlspecialchars($roleOption) ?>" <?= (isset($_GET['role']) && $_GET['role'] == $roleOption) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($roleOption) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-auto d-flex align-items-center">
            <label for="per_page" class="me-2 mb-0">Useri pe pagină:</label>
            <select name="per_page" id="per_page" class="form-select w-auto" onchange="this.form.submit()">
                <?php foreach ([3, 5, 10, 20] as $opt): ?>
                    <option value="<?= $opt ?>" <?= (isset($_GET['per_page']) && $_GET['per_page'] == $opt) || (!isset($_GET['per_page']) && $perPage == $opt) ? 'selected' : '' ?>>
                        <?= $opt ?> / pagină
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</form>
<table class="table">
    <thead>
        <tr>
            <th>
                <a href="<?= BASE_URL ?>users?sort=id&order=<?= ($sort === 'id' && $order === 'asc') ? 'desc' : 'asc' ?>&role=<?= urlencode($_GET['role'] ?? '') ?>&per_page=<?= $perPage ?>">
                    ID <?= $sort === 'id' ? ($order === 'asc' ? '▲' : '▼') : '' ?>
                </a>
            </th>
            <th>
                <a href="<?= BASE_URL ?>users?sort=email&order=<?= ($sort === 'email' && $order === 'asc') ? 'desc' : 'asc' ?>&role=<?= urlencode($_GET['role'] ?? '') ?>&per_page=<?= $perPage ?>">
                    Email <?= $sort === 'email' ? ($order === 'asc' ? '▲' : '▼') : '' ?>
                </a>
            </th>
            <th>
                <a href="<?= BASE_URL ?>users?sort=role&order=<?= ($sort === 'role' && $order === 'asc') ? 'desc' : 'asc' ?>&role=<?= urlencode($_GET['role'] ?? '') ?>&per_page=<?= $perPage ?>">
                    Role <?= $sort === 'role' ? ($order === 'asc' ? '▲' : '▼') : '' ?>
                </a>
            </th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <?php
            $editUrl = BASE_URL . "users/edit?id=" . $user['id'];
            $deleteUrl = BASE_URL . "users/delete?id=" . $user['id'];
        ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['email'] ?></td>
            <td><?= $user['role'] ?></td>
            <td>
                <a href='<?=$editUrl ?>'>✏️ Editează</a> 
                <form action='<?=$deleteUrl ?>' method='POST' class='d-inline m-0 p-0'>
                <button type='submit' onclick='return confirm(\"Ești sigur că vrei să ștergi acest user?\");'>🗑️ Șterge</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<nav>
    <ul class="pagination">
        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= BASE_URL ?>users?page=<?= max(1, $page - 1) ?>&per_page=<?= $perPage ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="<?= BASE_URL ?>users?page=<?= $i ?>&per_page=<?= $perPage ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= BASE_URL ?>users?page=<?= min($totalPages, $page + 1) ?>&per_page=<?= $perPage ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>
<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';