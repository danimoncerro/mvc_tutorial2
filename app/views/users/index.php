<?php
$title = 'Users List';
ob_start();
?>
<h1>Users</h1>
<a href='<?= BASE_URL ?>users/create' class='btn btn-primary'>Adaugă user</a>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Role</th>
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