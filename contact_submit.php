<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST["name"]);
    $email    = trim($_POST["email"]);
    $subject  = trim($_POST["subject"]);
    $message  = trim($_POST["message"]);
    $hp_field = trim($_POST["hp_field"]);

    // Simple spam check
    if (!empty($hp_field)) {
        header("Location: contact.html?status=error");
        exit();
    }

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        header("Location: contact.html?status=error");
        exit();
    }

    $to = "info@fixableup.online";  // Change to your email
    $email_subject = "New Contact - $subject";
    $email_body = "Name: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$message";

    $headers = "From: noreply@fixableup.online\r\n";
    $headers .= "Reply-To: $email\r\n";

    if (mail($to, $email_subject, $email_body, $headers)) {
        header("Location: contact.html?status=success");
    } else {
        header("Location: contact.html?status=error");
    }
    exit();
} else {
    header("Location: contact.html?status=error");
    exit();
}
