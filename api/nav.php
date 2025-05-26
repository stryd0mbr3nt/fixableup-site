<?php
// Get current script name to highlight active link
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="bg-black bg-opacity-80 p-4 flex items-center justify-between text-gray-300 font-semibold">
  <a href="index.php" class="flex items-center space-x-2">
    <img src="logo.png" alt="FixableUp Logo" width="48" height="48" class="w-12 hover:scale-110 transition" />
    <span class="luxury text-xl font-bold hidden sm:inline">FixableUp</span>
  </a>

  <button id="menuToggle" class="text-3xl text-gray-300 md:hidden">&#9776;</button>

  <div id="menu" class="hidden md:flex space-x-6">
    <a href="index.php" class="<?= $current_page === 'index.php' ? 'text-white' : 'hover:text-white' ?>">Home</a>
    <a href="client_register.php" class="<?= $current_page === 'client_register.php' ? 'text-white' : 'hover:text-white' ?>">Get Quotes</a>
    <a href="provider_register.php" class="<?= $current_page === 'provider_register.php' ? 'text-white' : 'hover:text-white' ?>">Join as Provider</a>
    <a href="contact.php" class="<?= $current_page === 'contact.php' ? 'text-white' : 'hover:text-white' ?>">Contact Us</a>
    <a href="terms.php" class="<?= $current_page === 'terms.php' ? 'text-white' : 'hover:text-white' ?>">Terms & Conditions</a>
  </div>
</nav>

<script>
  document.getElementById('menuToggle').addEventListener('click', () => {
    document.getElementById('menu').classList.toggle('hidden');
  });
</script>
