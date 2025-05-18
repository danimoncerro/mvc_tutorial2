<?php
$title = 'Adauga user';
ob_start();
?>

<h1 class="mb-4">Adaugă un user nou</h1>
<form action="<?= BASE_URL ?>users/store" method="POST" class="w-50 mx-auto p-4 border rounded shadow-sm bg-white">
    <div class="mb-3">
        <label for="email" class="form-label">Email user:</label>
        <input type="email" id="email" name="email" class="form-control" value="" required autocomplete="off">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="password" id="password" name="password" class="form-control" value="" required autocomplete="new-password">
    </div>

    <div class="mb-3">
        <label for="role" class="form-label">Role:</label>
        <select id="role" name="role" class="form-select" required>
            <option value="" selected disabled>Alege rolul</option>
            <option value="admin">Admin</option>
            <option value="agent vanzari">Agent vanzari</option>
            <option value="livrator">Livrator</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Adaugă user</button>
</form>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';