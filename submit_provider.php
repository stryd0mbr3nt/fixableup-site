<?php
// === Config ===
$adminEmail = "no-reply@fixableup.online";
$csvFile    = "providers.csv";

// === Get and sanitize POST data ===
function get_input($key) {
    return htmlspecialchars(trim($_POST[$key] ?? ''));
}

$name         = get_input('name');
$email        = get_input('email');
$phone        = get_input('phone');
$location     = get_input('location');
$service      = get_input('service');
$sub_services = get_input('sub_services');
$details      = get_input('details');
$date         = date('Y-m-d H:i:s');
$verified     = 'No';

// === Escape CSV-friendly ===
function clean($value) {
    return '"' . addslashes(trim(str_replace(["\r", "\n"], ' ', $value))) . '"';
}

// === Write header if file is new ===
if (!file_exists($csvFile)) {
    $header = ['Name', 'Email', 'Phone', 'Location', 'Service', 'Sub Services', 'Details', 'Date', 'Verified'];
    file_put_contents($csvFile, implode(',', $header) . "\n");
}

// === Save data to CSV ===
$data = [$name, $email, $phone, $location, $service, $sub_services, $details, $date, $verified];
file_put_contents($csvFile, implode(',', array_map('clean', $data)) . "\n", FILE_APPEND);

// === Email Content ===
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

// === Send Emails ===
mail($adminEmail, $subject, $adminMessage);
mail($email, "Welcome to FixableUp", $providerMessage);

// === Redirect to thank you ===
header("Location: thank_you.html");
exit();
?>
