<?php

require_once APP_ROOT . '/app/models/User.php';
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;


class ResetPasswordController
{


    public function index()
    {
        require_once APP_ROOT . '/app/views/resetpass/index.php';
    }

    public function resetpass()
    {
        //citim emailul si generam parola
        $password = random_int(100000, 999999);
        $email = $_POST['email'];
        
        //verificam daca userul cu adresa de email introdusa, exista in baza de date

        $useremailModel = new User();
        $userExists = $useremailModel->findByEmail($email);

        if ($userExists===false)
        {
            header('Location: ' . BASE_URL . 'auth/login');
            return;
        }

        //salvam parola noua1
        $userModel = new User();
        $userModel->resetPassword($email, $password);

        //trimitem email cu parola noua
        $this->trimiteParolaNoua($email, $password);

        header('Location: ' . BASE_URL . 'auth/login');
        exit;


    }

    protected function trimiteParolaNoua($email, $password)
    {
        $to = $email;
        $subject = 'Resetare parola';
        $message = 'Salut! Noua ta parola este ' .$password;

        $mailHost = getenv('MAIL_HOST') ?: 'smtp.gmail.com';
        $mailPort = (int) (getenv('MAIL_PORT') ?: 587);
        $mailUsername = getenv('MAIL_USERNAME') ?: '';
        $mailPassword = getenv('MAIL_PASSWORD') ?: '';
        $mailEncryption = getenv('MAIL_ENCRYPTION') ?: PHPMailer::ENCRYPTION_STARTTLS;
        $mailFromAddress = getenv('MAIL_FROM_ADDRESS') ?: $mailUsername;
        $mailFromName = getenv('MAIL_FROM_NAME') ?: 'MVC Tutorial';

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
        } catch (Exception $e) {
            error_log('Eroare la trimitere email reset parola: ' . $mailer->ErrorInfo);
        }

    }
}