<?php
// === Config ===
$adminEmail = "no-reply@fixableup.online";  // Change to your real admin email
$csvFile = "providers.csv";

// === Get Data ===
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$location = $_POST['location'] ?? '';
$service = $_POST['service'] ?? '';
$sub_services = $_POST['sub_services'] ?? '';
$details = $_POST['details'] ?? '';
$date = date('Y-m-d H:i:s');
$verified = 'No';  // All new providers start unverified

// === Sanitize function ===
function clean($value) {
  $value = str_replace(["\r", "\n"], ' ', $value); // remove line breaks
  return '"' . addslashes($value) . '"'; // wrap in quotes and escape
}

// === Save to CSV ===
if (!file_exists($csvFile)) {
  $header = ['Name', 'Email', 'Phone', 'Location', 'Service', 'Sub Services', 'Details', 'Date', 'Verified'];
  file_put_contents($csvFile, implode(',', $header) . "\n", FILE_APPEND);
}

$data = [$name, $email, $phone, $location, $service, $sub_services, $details, $date, $verified];
file_put_contents($csvFile, implode(',', array_map('clean', $data)) . "\n", FILE_APPEND);

// === Send Emails ===
$subject = "New Provider Registration - $name";

$adminMessage = "A new provider has registered:\n\n"
  . "Name: $name\n"
  . "Email: $email\n"
  . "Phone: $phone\n"
  . "Location: $location\n"
  . "Service: $service\n"
  . "Sub-Services: $sub_services\n"
  . "Details: $details\n"
  . "Date: $date\n\n"
  . "Login to verify them in your dashboard.";

$providerMessage = "Hi $name,\n\n"
  . "Thank you for registering as a provider on FixableUp.\n\n"
  . "Our team will review your details and verify your account shortly. "
  . "Once verified, you'll start receiving client leads directly.\n\n"
  . "Regards,\nFixableUp Team";

mail($adminEmail, $subject, $adminMessage);
mail($email, "Welcome to FixableUp", $providerMessage);

// === Redirect ===
header("Location: thank_you.html");
exit();
?>
