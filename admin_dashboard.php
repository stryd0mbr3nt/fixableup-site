<?php
// === Load CSV Data ===
function load_csv($file) {
  $rows = [];
  if (file_exists($file)) {
    $lines = file($file, FILE_IGNORE_NEW_LINES);
    $header = str_getcsv(array_shift($lines));
    foreach ($lines as $line) {
      $row = array_combine($header, str_getcsv($line));
      $rows[] = $row;
    }
  }
  return $rows;
}

// === Save CSV Data ===
function save_csv($file, $rows) {
  $fp = fopen($file, 'w');
  fputcsv($fp, array_keys($rows[0]));
  foreach ($rows as $r) {
    fputcsv($fp, $r);
  }
  fclose($fp);
}

// === Verify logic ===
if (isset($_GET['verify']) && isset($_GET['type']) && isset($_GET['email'])) {
  $type = $_GET['type'];
  $email = $_GET['email'];
  $file = ($type === 'client') ? 'clients.csv' : 'providers.csv';

  $rows = load_csv($file);
  foreach ($rows as &$row) {
    if ($row['Email'] === $email) {
      $row['Verified'] = 'Yes';
    }
  }
  save_csv($file, $rows);
  header("Location: admin_dashboard.php");
  exit();
}

// === Match logic ===
if (isset($_GET['match']) && isset($_GET['email'])) {
  $clientEmail = $_GET['email'];
  $clients = load_csv('clients.csv');
  $providers = load_csv('providers.csv');

  // Find the client
  foreach ($clients as &$c) {
    if ($c['Email'] === $clientEmail && $c['Matched'] !== 'Yes') {
      $client = $c;

      // Filter matching providers
      $matches = [];
      foreach ($providers as $p) {
        if ($p['Verified'] === 'Yes' &&
            stripos($p['Service'], $c['Service']) !== false &&
            stripos($p['Location'], $c['Location']) !== false) {
          $matches[] = $p;
        }
      }

      // Pick 3 random
      shuffle($matches);
      $selected = array_slice($matches, 0, 3);

      // Send emails
      foreach ($selected as $p) {
        $msg = "Hi {$p['Name']},\n\nYou have a new lead from FixableUp:\n\n"
             . "Client Name: {$c['Name']}\n"
             . "Client Email: {$c['Email']}\n"
             . "Client Location: {$c['Location']}\n"
             . "Service Needed: {$c['Service']}\n"
             . "Details: {$c['Details']}\n"
             . "Date: {$c['Date']}\n\n"
             . "Please contact the client directly.\n\nRegards,\nFixableUp Team";
        mail($p['Email'], "New Lead from FixableUp", $msg);
      }

      // Mark client as matched
      $c['Matched'] = 'Yes';
      break;
    }
  }

  // Save back
  save_csv('clients.csv', $clients);
  header("Location: admin_dashboard.php");
  exit();
}

$clients = load_csv('clients.csv');
$providers = load_csv('providers.csv');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - FixableUp</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white p-8">

  <h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>

  <!-- Clients -->
  <h2 class="text-2xl mb-2">Clients</h2>
  <div class="overflow-x-auto mb-10">
    <table class="min-w-full bg-gray-800 rounded-lg">
      <thead class="bg-gray-700">
        <tr>
          <th class="p-2">Name</th>
          <th class="p-2">Email</th>
          <th class="p-2">Location</th>
          <th class="p-2">Service</th>
          <th class="p-2">Verified</th>
          <th class="p-2">Matched</th>
          <th class="p-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($clients as $c): ?>
        <tr class="border-b border-gray-600">
          <td class="p-2"><?php echo htmlspecialchars($c['Name']); ?></td>
          <td class="p-2"><?php echo htmlspecialchars($c['Email']); ?></td>
          <td class="p-2"><?php echo htmlspecialchars($c['Location']); ?></td>
          <td class="p-2"><?php echo htmlspecialchars($c['Service']); ?></td>
          <td class="p-2"><?php echo $c['Verified']; ?></td>
          <td class="p-2"><?php echo $c['Matched'] ?? 'No'; ?></td>
          <td class="p-2 space-x-2">
            <?php if ($c['Verified'] !== 'Yes'): ?>
              <a href="?verify=1&type=client&email=<?php echo urlencode($c['Email']); ?>" class="bg-green-500 text-black px-2 py-1 rounded">Verify</a>
            <?php endif; ?>
            <?php if (($c['Matched'] ?? 'No') !== 'Yes'): ?>
              <a href="?match=1&email=<?php echo urlencode($c['Email']); ?>" class="bg-blue-500 text-black px-2 py-1 rounded">Match</a>
            <?php else: ?>
              ✅ Matched
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Providers -->
  <h2 class="text-2xl mb-2">Providers</h2>
  <div class="overflow-x-auto">
    <table class="min-w-full bg-gray-800 rounded-lg">
      <thead class="bg-gray-700">
        <tr>
          <th class="p-2">Name</th>
          <th class="p-2">Email</th>
          <th class="p-2">Phone</th>
          <th class="p-2">Company</th>
          <th class="p-2">Location</th>
          <th class="p-2">Service</th>
          <th class="p-2">Verified</th>
          <th class="p-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($providers as $p): ?>
        <tr class="border-b border-gray-600">
          <td class="p-2"><?php echo htmlspecialchars($p['Name']); ?></td>
          <td class="p-2"><?php echo htmlspecialchars($p['Email']); ?></td>
          <td class="p-2"><?php echo htmlspecialchars($p['Phone']); ?></td>
          <td class="p-2"><?php echo htmlspecialchars($p['Company']); ?></td>
          <td class="p-2"><?php echo htmlspecialchars($p['Location']); ?></td>
          <td class="p-2"><?php echo htmlspecialchars($p['Service']); ?></td>
          <td class="p-2"><?php echo $p['Verified']; ?></td>
          <td class="p-2">
            <?php if ($p['Verified'] !== 'Yes'): ?>
              <a href="?verify=1&type=provider&email=<?php echo urlencode($p['Email']); ?>" class="bg-green-500 text-black px-2 py-1 rounded">Verify</a>
            <?php else: ?>
              ✅
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</body>
</html>
