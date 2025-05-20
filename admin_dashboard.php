<?php
// === Utility Functions ===
function load_csv($file) {
  $rows = [];
  if (!file_exists($file)) return $rows;

  $lines = file($file, FILE_IGNORE_NEW_LINES);
  if (count($lines) < 2) return $rows;

  $header = str_getcsv(array_shift($lines));
  foreach ($lines as $line) {
    $row = array_combine($header, str_getcsv($line));
    if ($row) $rows[] = $row;
  }
  return $rows;
}

function save_csv($file, $rows) {
  if (empty($rows)) return;
  $fp = fopen($file, 'w');
  fputcsv($fp, array_keys($rows[0]));
  foreach ($rows as $r) {
    fputcsv($fp, $r);
  }
  fclose($fp);
}

// === Verify User ===
function verify_user($email, $type) {
  $file = $type === 'client' ? 'clients.csv' : 'providers.csv';
  $rows = load_csv($file);
  foreach ($rows as &$row) {
    if ($row['Email'] === $email) {
      $row['Verified'] = 'Yes';
    }
  }
  save_csv($file, $rows);
}

// === Match Client with Providers ===
function match_client($clientEmail) {
  $clients = load_csv('clients.csv');
  $providers = load_csv('providers.csv');

  foreach ($clients as &$c) {
    if ($c['Email'] === $clientEmail && ($c['Matched'] ?? 'No') !== 'Yes') {
      $matches = array_filter($providers, function($p) use ($c) {
        return $p['Verified'] === 'Yes'
          && stripos($p['Service'], $c['Service']) !== false
          && stripos($p['Location'], $c['Location']) !== false;
      });

      $selected = array_slice(shuffle_assoc($matches), 0, 3);

      foreach ($selected as $p) {
        $msg = "Hi {$p['Name']},\n\nYou have a new lead from FixableUp:\n\n"
             . "Client Name: {$c['Name']}\nEmail: {$c['Email']}\nLocation: {$c['Location']}\n"
             . "Service: {$c['Service']}\nDetails: {$c['Details']}\nDate: {$c['Date']}\n\n"
             . "Please contact the client directly.\n\nFixableUp Team";
        mail($p['Email'], "New Lead from FixableUp", $msg); // TODO: Replace with PHPMailer
      }

      $c['Matched'] = 'Yes';
      break;
    }
  }
  save_csv('clients.csv', $clients);
}

function shuffle_assoc($list) {
  $keys = array_keys($list);
  shuffle($keys);
  $new = [];
  foreach ($keys as $key) {
    $new[] = $list[$key];
  }
  return $new;
}

// === Handle GET Actions ===
if (isset($_GET['verify'], $_GET['type'], $_GET['email'])) {
  verify_user($_GET['email'], $_GET['type']);
  header("Location: admin_dashboard.php");
  exit();
}

if (isset($_GET['match'], $_GET['email'])) {
  match_client($_GET['email']);
  header("Location: admin_dashboard.php");
  exit();
}

// === Load Data ===
$clients = load_csv('clients.csv');
$providers = load_csv('providers.csv');
?>

<!-- === HTML === -->
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
  <section class="mb-12">
    <h2 class="text-2xl mb-2">Clients</h2>
    <div class="overflow-x-auto">
      <table class="min-w-full bg-gray-800 rounded-lg text-sm">
        <thead class="bg-gray-700">
          <tr>
            <?php foreach (['Name','Email','Location','Service','Verified','Matched','Actions'] as $col): ?>
              <th class="p-2"><?php echo $col; ?></th>
            <?php endforeach; ?>
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
                ✅
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>

  <!-- Providers -->
  <section>
    <h2 class="text-2xl mb-2">Providers</h2>
    <div class="overflow-x-auto">
      <table class="min-w-full bg-gray-800 rounded-lg text-sm">
        <thead class="bg-gray-700">
          <tr>
            <?php foreach (['Name','Email','Phone','Company','Location','Service','Verified','Actions'] as $col): ?>
              <th class="p-2"><?php echo $col; ?></th>
            <?php endforeach; ?>
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
  </section>

</body>
</html>
