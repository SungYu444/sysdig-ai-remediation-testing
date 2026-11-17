<?php
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

$zone = getenv('ZONE') ?: 'unknown';
$rows = [
    'Service' => 'status',
    'Environment' => $zone,
    'PHP version' => PHP_VERSION,
    'Web server' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
    'Pod' => gethostname(),
    'Time (UTC)' => gmdate('Y-m-d H:i:s'),
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Service Status</title>
  <style>
    body { font-family: "BC Sans", Arial, sans-serif; margin: 2rem; color: #2d2d2d; }
    h1 { color: #003366; }
    table { border-collapse: collapse; }
    th, td { text-align: left; padding: 0.5rem 1rem; border-bottom: 1px solid #d8d8d8; }
    .ok { color: #2e8540; font-weight: bold; }
  </style>
</head>
<body>
  <main>
    <h1>Service Status</h1>
    <p>Overall: <span class="ok">Operational</span></p>
    <table>
      <?php foreach ($rows as $label => $value): ?>
        <tr>
          <th scope="row"><?= htmlspecialchars($label, ENT_QUOTES) ?></th>
          <td><?= htmlspecialchars((string) $value, ENT_QUOTES) ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </main>
</body>
</html>
