<?php
if (!isset($_POST['token'])) { http_response_code(400); exit('Invalid request'); }

$secretKey = 'sk_test_3d158e0ak3vaAape8954f1cbd545';
$token = $_POST['token'];

$ch = curl_init('https://online.yoco.com/v1/charges/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $secretKey . ":");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
  "token" => $token,
  "amountInCents" => 15000,
  "currency" => "ZAR",
  "description" => "FixableUp Provider Registration"
]));

$response = curl_exec($ch);
curl_close($ch);

$charge = json_decode($response, true);
if (!isset($charge["id"])) { http_response_code(402); exit("Payment failed."); }

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$date = date("Y-m-d H:i:s");

$data = [$name, $email, $phone, "Verified", "5", uniqid("prov_"), $date];
file_put_contents("providers.csv", implode(",", $data) . "\n", FILE_APPEND);

require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
$mail = new PHPMailer(true);
$mail->setFrom("info@fixableup.online", "FixableUp");
$mail->addAddress($email);
$mail->isHTML(true);
$mail->Subject = "Your FixableUp Invoice";
$mail->Body = file_get_contents("invoice_template.html");
$mail->send();

echo "Registration successful and payment received (success)";
?>
