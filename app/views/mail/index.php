<?php
$title = 'Test PHPMailer';
ob_start();
?>

<div class="container" style="max-width: 720px;">
    <h1 class="mb-4">Test PHPMailer</h1>

    <?php if (!empty($status)): ?>
        <div class="alert alert-success" role="alert">
            <?= $status ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <div class="alert alert-warning" role="alert">
        Configureaza in mediu: MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION, MAIL_FROM_ADDRESS, MAIL_FROM_NAME.
    </div>

    <form method="post" action="<?= BASE_URL ?>mail/send">
        <div class="mb-3">
            <label class="form-label" for="to">Destinatar</label>
            <input type="email" class="form-control" id="to" name="to" placeholder="destinatar@email.com" required>
        </div>

        <div class="mb-3">
            <label class="form-label" for="subject">Subiect</label>
            <input type="text" class="form-control" id="subject" name="subject" value="Test PHPMailer" required>
        </div>

        <div class="mb-3">
            <label class="form-label" for="message">Mesaj</label>
            <textarea class="form-control" id="message" name="message" rows="5" required>Salut! Acesta este un email de test trimis din PHPMailer.</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Trimite email</button>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';
