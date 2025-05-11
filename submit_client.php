<?php
// === Config ===
$adminEmail = "no-reply@fixableup.online";  // Change to your real admin email
$csvFile = "clients.csv";

// === Get Data ===
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$location = $_POST['location'] ?? '';
$service = $_POST['service'] ?? '';
$details = $_POST['details'] ?? '';
$date = date('Y-m-d H:i:s');
$verified = 'No';  // New clients start unverified until matched

// === Sanitize function ===
function clean($value) {
  $value = str_replace(["\r", "\n"], ' ', $value); // remove line breaks
  return '"' . addslashes($value) . '"'; // wrap in quotes and escape
}

// === Save to CSV ===
if (!file_exists($csvFile)) {
  $header = ['Name', 'Email', 'Location', 'Service', 'Details', 'Date', 'Verified'];
  file_put_contents($csvFile, implode(',', $header) . "\n", FILE_APPEND);
}

$data = [$name, $email, $location, $service, $details, $date, $verified];
file_put_contents($csvFile, implode(',', array_map('clean', $data)) . "\n", FILE_APPEND);

// === Send Emails ===
$subject = "New Client Request - $name";

$adminMessage = "A new client has submitted a request:\n\n"
  . "Name: $name\n"
  . "Email: $email\n"
  . "Location: $location\n"
  . "Service Needed: $service\n"
  . "Details: $details\n"
  . "Date: $date\n\n"
  . "Login to view and assign them to providers.";

$clientMessage = "Hi $name,\n\n"
  . "Thank you for using FixableUp to request quotes. "
  . "We are now matching you with 3 verified service providers in your area.\n\n"
  . "You will receive your quotes shortly.\n\n"
  . "Regards,\nFixableUp Team";

mail($adminEmail, $subject, $adminMessage);
mail($email, "Your FixableUp Quote Request", $clientMessage);

// === Redirect ===
header("Location: thank_you.html");
exit();
?>
