<?php
$title = 'resetpass';

ob_start();
?>

<form action="resetareparola" method="POST">
    <div class="mb-3">
        <label for="" class="form-label">Adresa de mail</label>
        <input type="email" class="form-control" name="email" required>
    </div>
    <button type="submit">Reseteaza parola</button>    

</form>






<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';