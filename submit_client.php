<?php
// === Config ===
$adminEmail     = "no-reply@fixableup.online";
$csvFile        = "clients.csv";
$providersFile  = "providers.csv";

// === Get POST Data ===
$name     = $_POST['name'] ?? '';
$email    = $_POST['email'] ?? '';
$phone    = $_POST['phone'] ?? '';
$location = $_POST['location'] ?? '';
$service  = $_POST['service'] ?? '';
$details  = $_POST['details'] ?? '';
$date     = date('Y-m-d H:i:s');
$verified = 'No';  // Placeholder

// === Clean Function ===
function clean($value) {
    return '"' . addslashes(trim(str_replace(["\r", "\n"], ' ', $value))) . '"';
}

// === Save to CSV ===
if (!file_exists($csvFile)) {
    $header = ['Name', 'Email', 'Phone', 'Location', 'Service', 'Details', 'Date', 'Verified'];
    file_put_contents($csvFile, implode(',', $header) . "\n");
}

$data = [$name, $email, $phone, $location, $service, $details, $date, $verified];
file_put_contents($csvFile, implode(',', array_map('clean', $data)) . "\n", FILE_APPEND);

// === Load Verified Providers ===
$verifiedProviders = [];
if (file_exists($providersFile)) {
    $lines = file($providersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $header = str_getcsv(array_shift($lines));
    
    foreach ($lines as $line) {
        $provider = array_combine($header, str_getcsv($line));
        if (isset($provider['Verified']) && strtolower($provider['Verified']) === 'yes') {
            $verifiedProviders[] = $provider;
        }
    }
}

// === Match Providers by Service and Location ===
$matched = array_filter($verifiedProviders, function ($p) use ($service, $location) {
    return stripos($p['Service'], $service) !== false && stripos($p['Location'], $location) !== false;
});

// === Shuffle and Select Top 3 ===
shuffle($matched);
$selected = array_slice($matched, 0, 3);

// === Notify Providers ===
foreach ($selected as $provider) {
    $to      = $provider['Email'];
    $subject = "New Client Lead Available";
    $message = <<<EOT
Hi {$provider['Name']},

A new client is looking for services you offer:

Name: $name
Email: $email
Phone: $phone
Location: $location
Service Needed: $service
Details: $details

Please contact them directly!

Regards,
FixableUp Team
EOT;

    mail($to, $subject, $message);
}

// === Notify Client ===
$clientSubject = "Thank you for your request";
$clientMessage = <<<EOT
Hi $name,

Thank you for using FixableUp!

We've shared your request with top service providers. They will contact you shortly.

Regards,
FixableUp Team
EOT;

mail($email, $clientSubject, $clientMessage);

// === Redirect ===
header("Location: thank_you.html");
exit();
?>
