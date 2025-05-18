<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'My MVC App' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>frontend/bootstrap/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="<?= BASE_URL ?>">Home</a>
        <a class="nav-link" href="<?= BASE_URL ?>products">Products</a> 
        <a class="nav-link ms-3" href="<?= BASE_URL ?>users">Users</a>
    </nav>
    <div class="container mt-4">
        <?= $content ?>
    </div>
</body>
</html>