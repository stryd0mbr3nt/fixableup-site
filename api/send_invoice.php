
<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php'; // Make sure you run `composer require phpmailer/phpmailer`

$mail = new PHPMailer(true);
try {
    $mail->setFrom('info@fixableup.online', 'FixableUp');
    $mail->addAddress('client@example.com');
    $mail->Subject = 'Your Invoice';
    $mail->Body    = 'Attached is your invoice.';
    $mail->addAttachment('invoices/invoice123.pdf');

    $mail->send();
    echo 'Invoice sent';
} catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
}
