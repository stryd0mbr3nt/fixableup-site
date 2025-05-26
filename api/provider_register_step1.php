<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['provider'] = $_POST;
    header("Location: provider_payment_redirect.php");
    exit;
}
?>
<form method="post">
  <!-- Add fields for provider details -->
  Name: <input name="name" type="text" /><br />
  Email: <input name="email" type="email" /><br />
  <input type="submit" value="Proceed to Payment" />
</form>

