<?php

$title = 'Login';
ob_start();

?>


<h1>Login</h1>
<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form action="<?= BASE_URL ?>auth/authenticate" method="POST" class="w-25 mx-auto p-4 border rounded shadow-sm bg-white">
    <div class="mb-3">
        <label for="email" class="form-label">Email:</label>
        <input type="email" name="email" class="form-control" required autocomplete="off">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Parolă:</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';