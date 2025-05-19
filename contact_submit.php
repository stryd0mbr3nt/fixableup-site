<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Composer autoload for PHPMailer

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST["name"]);
    $email    = trim($_POST["email"]);
    $subject  = trim($_POST["subject"]);
    $message  = trim($_POST["message"]);
    $hp_field = trim($_POST["hp_field"]);

    // Honeypot spam check
    if (!empty($hp_field)) {
        header("Location: contact.html?status=error");
        exit();
    }

    if (empty($name) || empty($email) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: contact.html?status=error");
        exit();
    }

    $mail = new PHPMailer(true);
    try {
        // SMTP setup - adjust these
        $mail->isSMTP();
        $mail->Host       = 'smtp.1grid.co.za';       // Your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'no-reply@fixableup.online';  // SMTP username
        $mail->Password   = 'YOUR_SMTP_PASSWORD';         // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // From & To
        $mail->setFrom('no-reply@fixableup.online', 'FixableUp Contact Form');
        $mail->addAddress('info@fixableup.online');
        $mail->addReplyTo($email, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = "New Contact - $subject";
        $mail->Body = "
            <h2>New Contact Message</h2>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Subject:</strong> {$subject}</p>
            <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
        ";

        $mail->send();
        header("Location: contact.html?status=success");
        exit();
    } catch (Exception $e) {
        // Log $e->getMessage() if needed for debugging
        header("Location: contact.html?status=error");
        exit();
    }
} else {
    header("Location: contact.html?status=error");
    exit();
}
