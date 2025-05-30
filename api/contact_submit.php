<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contact FixableUp</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background-color: #0F0F0F;
      color: #E5E5E5;
    }
    .luxury {
      color: #D4AF37;
    }
    .hidden-honey { display: none; }
  </style>
</head>
<body class="min-h-screen p-6">
  <div class="max-w-3xl mx-auto">
    <!-- Navigation -->
    <nav class="mb-10 flex justify-between items-center text-sm text-gray-300">
      <img src="logo.png" alt="FixableUp Logo" width="90" height="120" class="w-12 hover:scale-300 transition" />
      <a href="/" class="font-bold text-lg luxury">FixableUp</a>
      <div class="space-x-4">
        <a href="index.html" class="hover:text-white ">Home</a>
        <a href="client_register.html" class="hover:text-white">Get Quotes</a>
        <a href="provider_register.html" class="hover:text-white">Join as Provider</a>
        <a href="contact.html" class="hover:text-white">Contact</a>
        <a href="terms.html" class="hover:text-white">Terms & Conditions</a>
        <a href="/privacy.html" class="hover:text-white">Privacy</a>
      </div>
    </nav>

    <!-- Contact Header -->
    <h1 class="text-4xl font-bold luxury mb-6">Contact Us</h1>

    <!-- Feedback Message -->
    <div id="formStatus" class="mb-4 text-sm"></div>

    <!-- Contact Form -->
    <form id="contactForm" action="contact_submit.php" method="POST" class="space-y-6 bg-gray-900 p-6 rounded-2xl shadow-lg">
      <div>
        <label for="name" class="block mb-2 font-semibold text-sm">Full Name *</label>
        <input type="text" id="name" name="name" required placeholder="Your full name"
               class="w-full bg-gray-800 text-white border border-gray-700 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-yellow-500" />
      </div>

      <div>
        <label for="email" class="block mb-2 font-semibold text-sm">Email Address *</label>
        <input type="email" id="email" name="email" required placeholder="you@example.com"
               class="w-full bg-gray-800 text-white border border-gray-700 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-yellow-500" />
      </div>

      <div>
        <label for="phone" class="block mb-2 font-semibold text-sm">Phone Number</label>
        <input type="tel" id="phone" name="phone" placeholder="+27 60 508 9504"
               class="w-full bg-gray-800 text-white border border-gray-700 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-yellow-500" />
      </div>

      <div>
        <label for="subject" class="block mb-2 font-semibold text-sm">Subject *</label>
        <input type="text" id="subject" name="subject" required placeholder="Subject of your message"
               class="w-full bg-gray-800 text-white border border-gray-700 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-yellow-500" />
      </div>

      <div>
        <label for="message" class="block mb-2 font-semibold text-sm">Your Message *</label>
        <textarea id="message" name="message" rows="5" required placeholder="How can we help you?"
                  class="w-full bg-gray-800 text-white border border-gray-700 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-yellow-500"></textarea>
      </div>

      <!-- Honeypot field (hidden to users, visible to bots) -->
      <div class="hidden-honey">
        <label>Leave this field empty</label>
        <input type="text" name="hp_field" />
      </div>

      <button type="submit"
              class="w-full bg-yellow-600 hover:bg-yellow-500 text-black font-bold py-3 rounded-md transition">
        Send Message
      </button>
    </form>

    <!-- Contact Info -->
    <div class="mt-10 text-sm text-gray-400">
      <p><strong>Phone:</strong> <a href="tel:+27605089504" class="text-yellow-500 hover:underline">060 508 9504</a></p>
      <p><strong>Email:</strong> <a href="mailto:info@fixableup.online" class="text-yellow-500 hover:underline">info@fixableup.online</a></p>
      <p><strong>Address:</strong> 50 Diamant Street, Upington</p>
    </div>
  </div>

  <script>
    // Show feedback messages
    const statusDiv = document.getElementById('formStatus');
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    if (status === 'success') {
      statusDiv.textContent = "Thank you! Your message has been sent successfully.";
      statusDiv.className += " text-green-400";
    } else if (status === 'error') {
      statusDiv.textContent = "Oops! Something went wrong. Please try again.";
      statusDiv.className += " text-red-400";
    }
  </script>
</body>
</html>
