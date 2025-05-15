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
            <th>Name</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
        <?php
            $editUrl = BASE_URL . "products/edit?id=" . $product['id'];
            $deleteUrl = BASE_URL . "products/delete?id=" . $product['id'];
        ?>
        <tr>
            <td><?= $product['id'] ?></td>
            <td><?= $product['name'] ?></td>
            <td><?= $product['price'] ?></td>
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