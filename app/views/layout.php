<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'My MVC App' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>frontend/bootstrap/bootstrap.min.css">
    <script src="<?= BASE_URL ?>frontend/js/axios.min.js"></script>
    <script src="<?= BASE_URL ?>frontend/js/main.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="<?= BASE_URL ?>">Home</a>
        <?php if (isset($_SESSION['user'])): ?>
            <a class="nav-link" href="<?= BASE_URL ?>products">Products</a> 
            <a class="nav-link ms-3" href="<?= BASE_URL ?>categories">Categories</a> 
            <a class="nav-link ms-3" href="<?= BASE_URL ?>users">Users</a>
            <span class="ms-3"><?= htmlspecialchars($_SESSION['user']['email']) ?></span>
            <a href="<?= BASE_URL ?>auth/logout" class="btn btn-danger btn-sm ms-3">Logout</a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>auth/login" class="btn btn-primary btn-sm">Login</a>
        <?php endif; ?>
        

    </nav>
    <div class="container mt-4">
        <?= $content ?>
    </div>
</body>
</html>