<?php
// === Config ===
$adminEmail = "no-reply@fixableup.online";
$csvFile = "providers.csv";

// === Get POST Data ===
$name         = $_POST['name'] ?? '';
$email        = $_POST['email'] ?? '';
$phone        = $_POST['phone'] ?? '';
$location     = $_POST['location'] ?? '';
$service      = $_POST['service'] ?? '';
$sub_services = $_POST['sub_services'] ?? '';
$details      = $_POST['details'] ?? '';
$date         = date('Y-m-d H:i:s');
$verified     = 'No';  // All new providers start unverified

// === Clean Function ===
function clean($value) {
    $value = str_replace(["\r", "\n"], ' ', $value);
    return '"' . addslashes(trim($value)) . '"';
}

// === Save to CSV ===
if (!file_exists($csvFile)) {
    $header = ['Name', 'Email', 'Phone', 'Location', 'Service', 'Sub Services', 'Details', 'Date', 'Verified'];
    file_put_contents($csvFile, implode(',', $header) . "\n");
}

$data = [$name, $email, $phone, $location, $service, $sub_services, $details, $date, $verified];
file_put_contents($csvFile, implode(',', array_map('clean', $data)) . "\n", FILE_APPEND);

// === Emails ===
$subject = "New Provider Registration - $name";

$adminMessage = <<<EOT
A new provider has registered:

Name: $name
Email: $email
Phone: $phone
Location: $location
Service: $service
Sub-Services: $sub_services
Details: $details
Date: $date

Login to verify them in your dashboard.
EOT;

$providerMessage = <<<EOT
Hi $name,

Thank you for registering as a provider on FixableUp.

Our team will review your details and verify your account shortly. Once verified, you'll start receiving client leads directly.

Regards,
FixableUp Team
EOT;

mail($adminEmail, $subject, $adminMessage);
mail($email, "Welcome to FixableUp", $providerMessage);

// === Redirect ===
header("Location: thank_you.html");
exit();
?>
