<?php
$title = 'Orders List';
ob_start();
?>
<h1>Orders</h1>
<a href='<?= BASE_URL ?>orders/create' class='btn btn-primary'>Adaugă comanda</a>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Status</th>
            <th>Data</th>
            <th>Total order</th>
            <th>Acțiuni</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
        <?php
            $editUrl = BASE_URL . "orders/edit?id=" . $order['id'];
            $deleteUrl = BASE_URL . "orderss/delete?id=" . $order['id'];
        ?>
        <tr>
            <td><?= htmlspecialchars($product['id']) ?></td>
            <td><?= htmlspecialchars($product['email']) ?></td>
            <td><?= htmlspecialchars($product['status']) ?></td>
            <td><?= htmlspecialchars($product['created_at'] ?? 'Fără categorie') ?></td>
            <td><?= htmlspecialchars($product['total_order']) ?></td>
            <td>
                <a href='<?=$editUrl ?>'>✏️ Editează</a> 
                <form action='<?=$deleteUrl ?>' method='POST' class='d-inline m-0 p-0'>
                    <button type="submit" onclick="return confirm('Ești sigur că vrei să ștergi aceasta comanda?');">🗑️ Șterge</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';