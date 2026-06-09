<?php
// ============================================================
// admin/dashboard.php — Reservation CRUD admin panel
// Requires login. Demonstrates: session, CRUD, MySQL, loops
// ============================================================

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/db.php';

requireLogin(); // PHP session guard

// ── CRUD: UPDATE status ───────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $id     = (int)($_POST['id'] ?? 0);
    $action = $_POST['action'];

    if ($action === 'update_status' && $id > 0) {
        $status = $_POST['status'] ?? 'pending';
        $allowed = ['pending', 'confirmed', 'cancelled'];
        if (in_array($status, $allowed)) {
            $stmt = $conn->prepare("UPDATE reservations SET status = ? WHERE id = ?");
            $stmt->bind_param('si', $status, $id);
            $stmt->execute();
            $stmt->close();
            setFlash('success', "Reservation #$id status updated to '$status'.");
        }
    }

    // CRUD: DELETE
    if ($action === 'delete' && $id > 0) {
        $stmt = $conn->prepare("DELETE FROM reservations WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
        setFlash('success', "Reservation #$id has been deleted.");
    }

    header('Location: /projectwp/rembayung_merged/admin/dashboard.php');
    exit();
}

// ── CRUD: READ — fetch all reservations ──────────────────────
$filter = $_GET['filter'] ?? 'all';
$search = trim($_GET['search'] ?? '');

$sql = "SELECT * FROM reservations WHERE 1=1";
$params = [];
$types  = '';

if ($filter !== 'all') {
    $sql    .= " AND status = ?";
    $types  .= 's';
    $params[] = $filter;
}

if (!empty($search)) {
    $sql    .= " AND (name LIKE ? OR phone LIKE ? OR email LIKE ?)";
    $types  .= 'sss';
    $like    = "%$search%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

$sql .= " ORDER BY res_date DESC, res_time DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result       = $stmt->get_result();
$reservations = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// ── Count stats ───────────────────────────────────────────────
$statsRow = $conn->query("SELECT
    COUNT(*) AS total,
    SUM(status='pending')   AS pending,
    SUM(status='confirmed') AS confirmed,
    SUM(status='cancelled') AS cancelled
    FROM reservations")->fetch_assoc();

$statusColors = ['pending' => '#f39c12', 'confirmed' => '#2ecc71', 'cancelled' => '#e74c3c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard — Rembayung</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Lato:wght@400;700&display=swap" rel="stylesheet"/>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Lato', sans-serif; background: #f0ece4; color: #333; }
    header { background: #1a1208; color: #c8a96e; padding: 16px 30px; display: flex; justify-content: space-between; align-items: center; }
    header h1 { font-family: 'Playfair Display', serif; font-size: 22px; }
    header nav a { color: #c8a96e; text-decoration: none; margin-left: 20px; font-size: 13px; }
    header nav a:hover { text-decoration: underline; }
    .main { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
    .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: #fff; border-radius: 6px; padding: 22px 24px; box-shadow: 0 2px 10px rgba(0,0,0,.06); }
    .stat-card .num { font-size: 36px; font-weight: 700; color: #1a1208; font-family: 'Playfair Display', serif; }
    .stat-card .lbl { font-size: 12px; letter-spacing: 1px; text-transform: uppercase; color: #999; margin-top: 4px; }
    .card { background: #fff; border-radius: 6px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,.06); }
    .toolbar { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 20px; align-items: center; }
    .toolbar form { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
    input[type=text], input[type=search], select { padding: 9px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; font-family: 'Lato', sans-serif; }
    .btn { padding: 9px 18px; border: none; border-radius: 4px; font-size: 13px; font-weight: 700; cursor: pointer; font-family: 'Lato', sans-serif; letter-spacing: .5px; }
    .btn-gold   { background: #c8a96e; color: #1a1208; }
    .btn-gold:hover { background: #b8995e; }
    .btn-red    { background: #e74c3c; color: #fff; }
    .btn-red:hover  { background: #c0392b; }
    .btn-sm { padding: 5px 12px; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; font-size: 14px; }
    th { background: #f8f5f0; text-align: left; padding: 11px 14px; font-size: 11px; letter-spacing: 1px; text-transform: uppercase; color: #999; border-bottom: 2px solid #eee; }
    td { padding: 13px 14px; border-bottom: 1px solid #f0ece4; vertical-align: middle; }
    tr:hover td { background: #faf8f4; }
    .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
    .flash { padding: 12px 18px; border-radius: 4px; margin-bottom: 20px; font-weight: 700; }
    .flash.success { background: #d4edda; color: #155724; }
    .flash.error   { background: #f8d7da; color: #721c24; }
    @media(max-width:700px){ .stats{grid-template-columns:1fr 1fr;} }
  </style>
</head>
<body>
<header>
  <h1>Rembayung Admin</h1>
  <nav>
    <a href="/projectwp/rembayung_merged/index.php" target="_blank">← View Site</a>
    <a href="/projectwp/rembayung_merged/admin/feedback.php">⭐ Feedback</a>
    <a href="/projectwp/rembayung_merged/admin/logout.php">Logout (<?= htmlspecialchars($_SESSION['admin_user']) ?>)</a>
  </nav>
</header>

<div class="main">

  <?php
  // Display flash message
  $flash = getFlash();
  if ($flash) {
      preg_match('/color:(#[0-9a-f]+)/', $flash, $m);
      $cls = (isset($m[1]) && $m[1] === '#2ecc71') ? 'success' : 'error';
      // Strip inline style, use class
      echo '<div class="flash ' . $cls . '">' . strip_tags($flash) . '</div>';
  }
  ?>

  <!-- Stats cards -->
  <div class="stats">
    <?php
    $statDefs = [
        ['label' => 'Total',     'key' => 'total',     'color' => '#1a1208'],
        ['label' => 'Pending',   'key' => 'pending',   'color' => '#f39c12'],
        ['label' => 'Confirmed', 'key' => 'confirmed', 'color' => '#2ecc71'],
        ['label' => 'Cancelled', 'key' => 'cancelled', 'color' => '#e74c3c'],
    ];
    foreach ($statDefs as $s):
    ?>
    <div class="stat-card">
      <div class="num" style="color:<?= $s['color'] ?>"><?= (int)$statsRow[$s['key']] ?></div>
      <div class="lbl"><?= $s['label'] ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Reservations table -->
  <div class="card">
    <h2 style="font-family:'Playfair Display',serif;font-size:20px;margin-bottom:18px;">
      Reservation Management
    </h2>

    <!-- Filter / search toolbar -->
    <div class="toolbar">
      <form method="GET" action="/projectwp/rembayung_merged/admin/dashboard.php">
        <select name="filter" onchange="this.form.submit()">
          <option value="all"       <?= $filter==='all'       ? 'selected':'' ?>>All statuses</option>
          <option value="pending"   <?= $filter==='pending'   ? 'selected':'' ?>>Pending</option>
          <option value="confirmed" <?= $filter==='confirmed' ? 'selected':'' ?>>Confirmed</option>
          <option value="cancelled" <?= $filter==='cancelled' ? 'selected':'' ?>>Cancelled</option>
        </select>
        <input type="search" name="search" placeholder="Search name / phone / email…"
               value="<?= htmlspecialchars($search) ?>"/>
        <button type="submit" class="btn btn-gold">Search</button>
        <?php if ($search || $filter !== 'all'): ?>
        <a href="/admin/dashboard.php" class="btn" style="background:#eee;color:#333;">Clear</a>
        <?php endif; ?>
      </form>
    </div>

    <?php if (empty($reservations)): ?>
      <p style="color:#999;text-align:center;padding:30px;">No reservations found.</p>
    <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Date</th>
          <th>Time</th>
          <th>Guests</th>
          <th>Requests</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reservations as $row): ?>
        <tr>
          <td><?= (int)$row['id'] ?></td>
          <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
          <td><?= htmlspecialchars($row['phone']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= htmlspecialchars($row['res_date']) ?></td>
          <td><?= htmlspecialchars($row['res_time']) ?></td>
          <td><?= htmlspecialchars($row['guests']) ?></td>
          <td style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
              title="<?= htmlspecialchars($row['special_requests']) ?>">
            <?= htmlspecialchars($row['special_requests'] ?: '—') ?>
          </td>
          <td>
            <?php $col = $statusColors[$row['status']] ?? '#999'; ?>
            <span class="badge" style="background:<?= $col ?>22;color:<?= $col ?>;border:1px solid <?= $col ?>44;">
              <?= ucfirst(htmlspecialchars($row['status'])) ?>
            </span>
          </td>
          <td>
            <!-- UPDATE status -->
            <form method="POST" action="/projectwp/rembayung_merged/admin/dashboard.php" style="display:inline;">
              <input type="hidden" name="action" value="update_status"/>
              <input type="hidden" name="id"     value="<?= (int)$row['id'] ?>"/>
              <select name="status" onchange="this.form.submit()" style="font-size:12px;padding:4px 6px;">
                <option value="pending"   <?= $row['status']==='pending'   ? 'selected':'' ?>>Pending</option>
                <option value="confirmed" <?= $row['status']==='confirmed' ? 'selected':'' ?>>Confirmed</option>
                <option value="cancelled" <?= $row['status']==='cancelled' ? 'selected':'' ?>>Cancelled</option>
              </select>
            </form>
            <!-- DELETE -->
            <form method="POST" action="/projectwp/rembayung_merged/admin/dashboard.php" style="display:inline;"
                  onsubmit="return confirm('Delete reservation #<?= (int)$row['id'] ?>?');">
              <input type="hidden" name="action" value="delete"/>
              <input type="hidden" name="id"     value="<?= (int)$row['id'] ?>"/>
              <button type="submit" class="btn btn-red btn-sm">Delete</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>

  </div>
</div>
</body>
</html>
