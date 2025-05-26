<?php
// Simulate redirecting to payment gateway
echo "<p>Redirecting to payment...</p>";
sleep(2);
header("Location: provider_payment_success.php");
exit;

