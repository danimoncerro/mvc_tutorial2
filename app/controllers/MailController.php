<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class MailController
{
    public function index()
    {
        $status = $_SESSION['mail_status'] ?? null;
        $error = $_SESSION['mail_error'] ?? null;
        unset($_SESSION['mail_status'], $_SESSION['mail_error']);

        require_once APP_ROOT . '/app/views/mail/index.php';
    }

    public function send()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'mail/test');
            exit;
        }

        $to = trim($_POST['to'] ?? '');
        $subject = trim($_POST['subject'] ?? 'Test PHPMailer');
        $message = trim($_POST['message'] ?? 'Salut! Acesta este un email de test trimis din PHPMailer.');

        if ($to === '') {
            $_SESSION['mail_error'] = 'Destinatarul este obligatoriu.';
            header('Location: ' . BASE_URL . 'mail/test');
            exit;
        }

        $mailHost = getenv('MAIL_HOST') ?: 'smtp.gmail.com';
        $mailPort = (int) (getenv('MAIL_PORT') ?: 587);
        $mailUsername = getenv('MAIL_USERNAME') ?: '';
        $mailPassword = getenv('MAIL_PASSWORD') ?: '';
        $mailEncryption = getenv('MAIL_ENCRYPTION') ?: PHPMailer::ENCRYPTION_STARTTLS;
        $mailFromAddress = getenv('MAIL_FROM_ADDRESS') ?: $mailUsername;
        $mailFromName = getenv('MAIL_FROM_NAME') ?: 'MVC Tutorial';

        if ($mailUsername === '' || $mailPassword === '' || $mailFromAddress === '') {
            $_SESSION['mail_error'] = 'Lipsesc setari SMTP. Configureaza MAIL_USERNAME, MAIL_PASSWORD si MAIL_FROM_ADDRESS.';
            header('Location: ' . BASE_URL . 'mail/test');
            exit;
        }

        $mailer = new PHPMailer(true);

        try {
            $mailer->CharSet = 'UTF-8';
            $mailer->isSMTP();
            $mailer->Host = $mailHost;
            $mailer->SMTPAuth = true;
            $mailer->Username = $mailUsername;
            $mailer->Password = $mailPassword;
            $mailer->SMTPSecure = $mailEncryption;
            $mailer->Port = $mailPort;

            $mailer->setFrom($mailFromAddress, $mailFromName);
            $mailer->addAddress($to);
            $mailer->Subject = $subject;
            $mailer->Body = $message;
            $mailer->isHTML(false);

            $mailer->send();
            $_SESSION['mail_status'] = 'Email trimis cu succes catre ' . htmlspecialchars($to);
        } catch (Exception $e) {
            $_SESSION['mail_error'] = 'Eroare la trimitere: ' . $mailer->ErrorInfo;
        }

        header('Location: ' . BASE_URL . 'mail/test');
        exit;
    }
}
