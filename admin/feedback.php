<?php
// ============================================================
// admin/feedback.php — View and delete customer feedback
// ============================================================

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/db.php';

requireLogin();

// ── DELETE feedback ───────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = (int)($_POST['feedback_id'] ?? 0);
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM feedback WHERE feedback_id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
        setFlash('success', "Feedback #$id has been deleted.");
    }
    header('Location: /projectwp/rembayung_merged/admin/feedback.php');
    exit();
}

// ── READ all feedback ─────────────────────────────────────────
$result    = $conn->query("SELECT * FROM feedback ORDER BY submitted_date DESC, feedback_id DESC");
$feedbacks = $result->fetch_all(MYSQLI_ASSOC);

// ── Stats ─────────────────────────────────────────────────────
$statsRow = $conn->query("SELECT COUNT(*) AS total, ROUND(AVG(rating), 1) AS avg_rating FROM feedback")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Feedback — Rembayung Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Lato:wght@400;700&display=swap" rel="stylesheet"/>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Lato', sans-serif; background: #f0ece4; color: #333; }
    header { background: #1a1208; color: #c8a96e; padding: 16px 30px; display: flex; justify-content: space-between; align-items: center; }
    header h1 { font-family: 'Playfair Display', serif; font-size: 22px; }
    header nav a { color: #c8a96e; text-decoration: none; margin-left: 20px; font-size: 13px; }
    header nav a:hover { text-decoration: underline; }
    .main { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
    .stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px; max-width: 500px; }
    .stat-card { background: #fff; border-radius: 6px; padding: 22px 24px; box-shadow: 0 2px 10px rgba(0,0,0,.06); }
    .stat-card .num { font-size: 36px; font-weight: 700; color: #c8a96e; font-family: 'Playfair Display', serif; }
    .stat-card .lbl { font-size: 12px; letter-spacing: 1px; text-transform: uppercase; color: #999; margin-top: 4px; }
    .card { background: #fff; border-radius: 6px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,.06); }
    .btn { padding: 9px 18px; border: none; border-radius: 4px; font-size: 13px; font-weight: 700; cursor: pointer; font-family: 'Lato', sans-serif; }
    .btn-red     { background: #e74c3c; color: #fff; }
    .btn-red:hover   { background: #c0392b; }
    .btn-sm { padding: 5px 12px; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; font-size: 14px; }
    th { background: #f8f5f0; text-align: left; padding: 11px 14px; font-size: 11px; letter-spacing: 1px; text-transform: uppercase; color: #999; border-bottom: 2px solid #eee; }
    td { padding: 13px 14px; border-bottom: 1px solid #f0ece4; vertical-align: top; }
    tr:hover td { background: #faf8f4; }
    .stars { color: #c8a96e; font-size: 15px; letter-spacing: 1px; }
    .flash { padding: 12px 18px; border-radius: 4px; margin-bottom: 20px; font-weight: 700; }
    .flash.success { background: #d4edda; color: #155724; }
    .flash.error   { background: #f8d7da; color: #721c24; }
    .comment-cell { max-width: 350px; word-break: break-word; white-space: pre-wrap; }
    @media(max-width:700px){ .stats{ grid-template-columns: 1fr 1fr; } table { font-size: 12px; } }
  </style>
</head>
<body>

<header>
  <h1>Rembayung Admin</h1>
  <nav>
    <a href="/projectwp/rembayung_merged/admin/dashboard.php">← Dashboard</a>
    <a href="/projectwp/rembayung_merged/index.php" target="_blank">View Site</a>
    <a href="/projectwp/rembayung_merged/admin/logout.php">Logout (<?= htmlspecialchars($_SESSION['admin_user']) ?>)</a>
  </nav>
</header>

<div class="main">

  <?php
  $flash = getFlash();
  if ($flash) {
      // getFlash() returns an inline-styled <p>; parse class from color
      $cls = (strpos($flash, '#2ecc71') !== false) ? 'success' : 'error';
      echo '<div class="flash ' . $cls . '">' . strip_tags($flash) . '</div>';
  }
  ?>

  <!-- Stats -->
  <div class="stats">
    <div class="stat-card">
      <div class="num"><?= (int)$statsRow['total'] ?></div>
      <div class="lbl">Total Reviews</div>
    </div>
    <div class="stat-card">
      <div class="num"><?= $statsRow['avg_rating'] ?? '—' ?></div>
      <div class="lbl">Average Rating</div>
    </div>
  </div>

  <!-- Feedback table -->
  <div class="card">
    <h2 style="font-family:'Playfair Display',serif;font-size:20px;margin-bottom:18px;">
      Customer Feedback
    </h2>

    <?php if (empty($feedbacks)): ?>
      <p style="color:#999;text-align:center;padding:30px;">No feedback submitted yet.</p>
    <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Rating</th>
          <th>Comment</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($feedbacks as $row): ?>
        <tr>
          <td><?= (int)$row['feedback_id'] ?></td>
          <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
          <td>
            <span class="stars"><?= str_repeat('★', (int)$row['rating']) ?><?= str_repeat('☆', 5 - (int)$row['rating']) ?></span>
            <br/><small style="color:#999;"><?= (int)$row['rating'] ?>/5</small>
          </td>
          <td class="comment-cell"><?= htmlspecialchars($row['comment']) ?></td>
          <td><?= htmlspecialchars($row['submitted_date']) ?></td>
          <td>
            <form method="POST" action="/projectwp/rembayung_merged/admin/feedback.php"
                  onsubmit="return confirm('Delete feedback #<?= (int)$row['feedback_id'] ?>?');">
              <input type="hidden" name="action"      value="delete"/>
              <input type="hidden" name="feedback_id" value="<?= (int)$row['feedback_id'] ?>"/>
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
