<?php
// === Config ===
$adminEmail = "youradmin@fixable.online";  // Change this to your email
$csvFile = "clients.csv";

// === Get Data ===
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$location = $_POST['location'] ?? '';
$service = $_POST['service'] ?? '';
$details = $_POST['details'] ?? '';
$date = date('Y-m-d H:i:s');
$matched = 'No';  // Client has not been matched yet

// === Save to CSV ===
if (!file_exists($csvFile)) {
  $header = ['Name', 'Email', 'Location', 'Service', 'Details', 'Date', 'Matched'];
  file_put_contents($csvFile, implode(',', $header) . "\n", FILE_APPEND);
}

$data = [$name, $email, $location, $service, $details, $date, $matched];
file_put_contents($csvFile, implode(',', array_map('addslashes', $data)) . "\n", FILE_APPEND);

// === Send Emails ===
$subject = "New Client Request - $name";
$adminMessage = "A new client requested quotes:\n\nName: $name\nEmail: $email\nLocation: $location\nService: $service\nDetails: $details\nDate: $date\n\nPrepare to match them with providers.";
$clientMessage = "Hi $name,\n\nThank you for requesting quotes through FixableUp.\n\nOur verified providers will contact you shortly with their offers.\n\nWe appreciate your trust!\nFixableUp Team";

mail($adminEmail, $subject, $adminMessage);
mail($email, "Your FixableUp Quote Request", $clientMessage);

// === Redirect ===
header("Location: thank_you.html");
exit();
?>
