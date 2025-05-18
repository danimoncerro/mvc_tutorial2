<?php
$title = 'Categories List';
ob_start();
?>
<h1>Categories</h1>
<a href='<?= BASE_URL ?>categories/create' class='btn btn-primary'>Adaugă categorie</a>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nume</th>
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
<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';