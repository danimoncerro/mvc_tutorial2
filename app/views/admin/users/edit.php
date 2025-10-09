<?php
$title = 'Editează user';
ob_start();
?>

<h1 class="mb-4">Editează user</h1>
<form action="<?= BASE_URL ?>users/update" method="POST" class="w-50 mx-auto p-4 border rounded shadow-sm bg-white">
    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
    <div class="mb-3">
        <label for="email" class="form-label">Email user:</label>
        <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required autocomplete="off">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password (lasă gol dacă nu schimbi):</label>
        <input type="password" id="password" name="password" class="form-control" value="" autocomplete="new-password">
    </div>
    <div class="mb-3">
        <label for="role" class="form-label">Role:</label>
        <select id="role" name="role" class="form-select" required>
            <option value="" disabled>Alege rolul</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="agent vanzari" <?= $user['role'] === 'agent vanzari' ? 'selected' : '' ?>>Agent vanzari</option>
            <option value="livrator" <?= $user['role'] === 'livrator' ? 'selected' : '' ?>>Livrator</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Salvează modificările</button>
</form>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';
