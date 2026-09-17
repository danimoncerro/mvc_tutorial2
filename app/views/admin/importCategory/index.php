<?php
$title = 'Import Categories';
ob_start();
?>

<h1>Importa categorii</h1>

<div id="app" class="container">
    <form action="<?= BASE_URL ?>/import/categories"
            method="POST"
            enctype="multipart/form-data">

        <div class="mb-3">
            <label for="file" class="form-label">
                Choose file
            </label>

            <input
                type="file"
                class="form-control"
                id="file"
                name="csvfile"
                required
            >
        </div>

        <button type="submit" class="btn btn-primary">
            Upload
        </button>

    </form>


</div>



<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';
?>