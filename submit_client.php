<?php
// === Config ===
$adminEmail = "no-reply@fixableup.online";  // Change this to your email
$csvFile = "clients.csv";
$providersFile = "providers.csv";

// === Get Data ===
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$location = $_POST['location'] ?? '';
$service = $_POST['service'] ?? '';
$details = $_POST['details'] ?? '';
$date = date('Y-m-d H:i:s');
$verified = 'No'; // New clients not "verified", but placeholder

// === Save to CSV ===
if (!file_exists($csvFile)) {
    $header = ['Name', 'Email', 'Phone', 'Location', 'Service', 'Details', 'Date', 'Verified'];
    file_put_contents($csvFile, implode(',', $header) . "\n", FILE_APPEND);
}

$data = [$name, $email, $phone, $location, $service, $details, $date, $verified];
file_put_contents($csvFile, implode(',', array_map('addslashes', $data)) . "\n", FILE_APPEND);

// === Load Verified Providers ===
$providers = [];
if (file_exists($providersFile)) {
    $lines = file($providersFile, FILE_IGNORE_NEW_LINES);
    $header = str_getcsv(array_shift($lines));
    foreach ($lines as $line) {
        $row = array_combine($header, str_getcsv($line));
        if ($row['Verified'] === 'Yes') {
            $providers[] = $row;
        }
    }
}

// === Match Providers ===
$matched = [];
foreach ($providers as $p) {
    if (
        stripos($p['Service'], $service) !== false &&
        stripos($p['Location'], $location) !== false
    ) {
        $matched[] = $p;
    }
}

// === Pick up to 3 random matches ===
shuffle($matched);
$selected = array_slice($matched, 0, 3);

// === Send Emails ===
foreach ($selected as $provider) {
    $to = $provider['Email'];
    $subject = "New Client Lead Available";
    $message = "Hi " . $provider['Name'] . ",\n\n"
             . "A new client is looking for services you offer:\n\n"
             . "Name: $name\n"
             . "Email: $email\n"
             . "Phone: $phone\n"
             . "Location: $location\n"
             . "Service Needed: $service\n"
             . "Details: $details\n\n"
             . "Please contact them directly!\n\n"
             . "Regards,\nFixableUp Team";

    mail($to, $subject, $message);
}

// === Send Confirmation to Client ===
$clientSubject = "Thank you for your request";
$clientMessage = "Hi $name,\n\nThank you for using FixableUp!\n"
               . "We've shared your request with top service providers. They will contact you shortly.\n\n"
               . "Regards,\nFixableUp Team";

mail($email, $clientSubject, $clientMessage);

// === Redirect ===
header("Location: thank_you.html");
exit();
?>
