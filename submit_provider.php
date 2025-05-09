<?php
// === Config ===
$adminEmail = "youradmin@fixable.online";  // Change this to your email
$csvFile = "providers.csv";

// === Get Data ===
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$company = $_POST['company'] ?? '';
$location = $_POST['location'] ?? '';
$service = $_POST['service'] ?? '';
$details = $_POST['details'] ?? '';
$date = date('Y-m-d H:i:s');
$verified = 'No';  // New providers start as unverified

// === Save to CSV ===
if (!file_exists($csvFile)) {
  $header = ['Name', 'Email', 'Phone', 'Company', 'Location', 'Service', 'Details', 'Date', 'Verified'];
  file_put_contents($csvFile, implode(',', $header) . "\n", FILE_APPEND);
}

$data = [$name, $email, $phone, $company, $location, $service, $details, $date, $verified];
file_put_contents($csvFile, implode(',', array_map('addslashes', $data)) . "\n", FILE_APPEND);

// === Send Emails ===
$subject = "New Provider Registration - $name";
$adminMessage = "A new provider has registered:\n\nName: $name\nEmail: $email\nPhone: $phone\nCompany: $company\nLocation: $location\nService: $service\nDetails: $details\nDate: $date\n\nLogin to verify them.";
$providerMessage = "Hi $name,\n\nThank you for registering as a provider on FixableUp.\n\nOur team will review your details and verify your account shortly. You'll start receiving client leads once verified.\n\nRegards,\nFixableUp Team";

mail($adminEmail, $subject, $adminMessage);
mail($email, "Welcome to FixableUp", $providerMessage);

// === Redirect ===
header("Location: thank_you.html");
exit();
?>
