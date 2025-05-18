<?php
$title = 'Products List';
ob_start();
?>
<h1>Products</h1>
<a href='<?= BASE_URL ?>products/create' class='btn btn-primary'>Adaugă produs</a>
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
                <button type='submit' onclick='return confirm(\"Ești sigur că vrei să ștergi acest produs?\");'>🗑️ Șterge</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';