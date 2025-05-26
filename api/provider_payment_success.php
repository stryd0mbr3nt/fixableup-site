
<?php
session_start();
include 'config.php';

if (!isset($_SESSION['provider'])) {
    echo "No provider data.";
    exit;
}

$data = $_SESSION['provider'];
$name = $data['name'];
$email = $data['email'];

$stmt = $conn->prepare("INSERT INTO providers (name, email) VALUES (?, ?)");
$stmt->bind_param("ss", $name, $email);
$stmt->execute();

echo "<p>Provider registered and payment received. Thank you!</p>";
session_destroy();
