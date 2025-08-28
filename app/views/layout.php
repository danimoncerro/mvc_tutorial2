<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'My MVC App' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>frontend/bootstrap/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>frontend/js/axios.min.js"></script>
    <script src="<?= BASE_URL ?>frontend/js/vue.js"></script>
    <script src="<?= BASE_URL ?>frontend/js/main.js"></script>
    <script src="<?= BASE_URL ?>frontend/js/components/ShowTitle.js"></script>
    <script src="<?= BASE_URL ?>frontend/js/components/IncrementComponent.js"></script>
    <style>
    .stoc-suficient {
      background-color: green;
      color: white;
      font-weight: bold;
      padding: 6px;
      border-radius: 5px;
    }

    .stoc-limitat {
      background-color: yellow;
      color: red;
      font-weight: bold;
      padding: 6px;
      border-radius: 5px;
    }

    .stoc-alerta {
      background-color: red;
      color: white;
      font-weight: bold;
      padding: 6px;
      border-radius: 5px;
    }
    </style>
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
        <?php if (isset($content)) echo $content; ?>
    </div>

</body>
</html>