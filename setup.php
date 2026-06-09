<?php
// ============================================================
// setup.php — Run ONCE to create the database and all tables
// Visit: http://localhost/rembayung/setup.php
// DELETE or RENAME this file after running!
// ============================================================

$host = 'localhost';
$user = 'root';
$pass = '';       // Change to your MySQL password

$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$steps = [];

// 1. Create database
$conn->query("CREATE DATABASE IF NOT EXISTS rembayung_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$steps[] = "✅ Database <strong>rembayung_db</strong> created.";

$conn->select_db('rembayung_db');

// 2. Create reservations table
$conn->query("
    CREATE TABLE IF NOT EXISTS reservations (
        id               INT AUTO_INCREMENT PRIMARY KEY,
        name             VARCHAR(100)  NOT NULL,
        phone            VARCHAR(25)   NOT NULL,
        email            VARCHAR(150)  DEFAULT NULL,
        res_date         DATE          NOT NULL,
        res_time         TIME          NOT NULL,
        guests           VARCHAR(10)   NOT NULL,
        special_requests TEXT          DEFAULT NULL,
        status           ENUM('pending','confirmed','cancelled') DEFAULT 'pending',
        created_at       DATETIME      DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");
$steps[] = "✅ Table <strong>reservations</strong> created.";

// 3. Seed sample reservations
$samples = [
    ['Ahmad Faiz',      '+60 12-111 2222', 'ahmad@email.com',   '2026-06-15', '12:30:00', '3-4',  'Window seat please'],
    ['Nurul Ain',       '+60 11-333 4444', 'nurul@email.com',   '2026-06-16', '19:00:00', '2',    'Anniversary dinner'],
    ['Khairul Fadzly',  '+60 17-555 6666', '',                  '2026-06-17', '13:00:00', '5-6',  ''],
    ['Siti Hajar',      '+60 19-777 8888', 'siti@email.com',    '2026-06-18', '12:00:00', '1',    'Vegetarian options?'],
    ['Rizal Mansor',    '+60 13-999 0000', 'rizal@email.com',   '2026-06-20', '20:00:00', '7-10', 'Birthday celebration'],
];

$stmt = $conn->prepare(
    "INSERT IGNORE INTO reservations (name, phone, email, res_date, res_time, guests, special_requests)
     VALUES (?, ?, ?, ?, ?, ?, ?)"
);
foreach ($samples as $row) {
    $stmt->bind_param('sssssss', $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6]);
    $stmt->execute();
}
$stmt->close();
$steps[] = "✅ <strong>" . count($samples) . " sample reservations</strong> inserted.";

// 4. Create tables table
$conn->query("
    CREATE TABLE IF NOT EXISTS `tables` (
        table_id         INT AUTO_INCREMENT PRIMARY KEY,
        table_number     VARCHAR(5)  NOT NULL UNIQUE,
        seating_capacity INT         NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");
$steps[] = "✅ Table <strong>tables</strong> created.";

$conn->query("
    INSERT IGNORE INTO `tables` (table_number, seating_capacity) VALUES
    ('T1', 2),
    ('T2', 4),
    ('T3', 4),
    ('T4', 6),
    ('T5', 8);
");
$steps[] = "✅ <strong>5 dining tables</strong> inserted.";

// 5. Create admin table
$conn->query("
    CREATE TABLE IF NOT EXISTS admin (
        admin_id  INT AUTO_INCREMENT PRIMARY KEY,
        username  VARCHAR(50)  NOT NULL,
        password  VARCHAR(255) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");
$steps[] = "✅ Table <strong>admin</strong> created.";

$conn->query("
    INSERT IGNORE INTO admin (admin_id, username, password) VALUES
    (1, 'admin', 'admin123');
");
$steps[] = "✅ <strong>1 admin record</strong> inserted (username: admin / password: admin123).";

// 6. Create feedback table
$conn->query("
    CREATE TABLE IF NOT EXISTS feedback (
        feedback_id    INT AUTO_INCREMENT PRIMARY KEY,
        name           VARCHAR(100) NOT NULL,
        rating         INT          NOT NULL CHECK (rating BETWEEN 1 AND 5),
        comment        TEXT         NOT NULL,
        submitted_date DATE         NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");
$steps[] = "✅ Table <strong>feedback</strong> created.";

$conn->query("
    INSERT IGNORE INTO feedback (feedback_id, name, rating, comment, submitted_date) VALUES
    (1, 'Amirah Yusof',     5, 'The Masak Lemak Cili Api was absolutely incredible! Rich, creamy, and perfectly spiced. Will definitely come back.',                  '2026-05-10'),
    (2, 'Hafiz Rahman',     4, 'Great ambience and authentic Malay flavours. The Sambal Berlada was superb. Slightly long wait time but worth it.',                   '2026-05-12'),
    (3, 'Lim Mei Ling',     5, 'Brought my whole family for a birthday dinner. Staff were very accommodating and the food was outstanding. Highly recommended!',      '2026-05-15'),
    (4, 'Syazwan Idris',    3, 'Food was good but portions felt a bit small for the price. The gulai was tasty though. Maybe visit again during a promotion.',       '2026-05-18'),
    (5, 'Rosnani Abdullah', 5, 'Best traditional Malay food I have had in KL. The prayer room facility is a huge plus. Everything was clean and well-organised.',   '2026-05-22');
");
$steps[] = "✅ <strong>5 sample feedback records</strong> inserted.";

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <title>Setup — Rembayung</title>
  <style>
    body { font-family: Arial, sans-serif; background: #1a1208; color: #f5f0e8; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
    .box { background: #2a1f0e; border: 1px solid rgba(200,169,110,.4); border-radius: 8px; padding: 40px 50px; max-width: 540px; width: 100%; }
    h1 { color: #c8a96e; font-size: 24px; margin-bottom: 24px; }
    ul { list-style: none; padding: 0; }
    li { padding: 8px 0; font-size: 15px; border-bottom: 1px solid rgba(255,255,255,.06); }
    .links { margin-top: 28px; display: flex; gap: 16px; flex-wrap: wrap; }
    a { background: #c8a96e; color: #1a1208; padding: 10px 22px; border-radius: 4px; text-decoration: none; font-weight: 700; font-size: 14px; }
    a:hover { background: #e8d5a3; }
    .warn { margin-top: 20px; background: rgba(231,76,60,.15); border: 1px solid #e74c3c; border-radius: 4px; padding: 12px; color: #e74c3c; font-size: 13px; }
    .section-label { color: #c8a96e; font-size: 12px; letter-spacing: 2px; text-transform: uppercase; margin: 18px 0 6px; opacity: 0.7; }
  </style>
</head>
<body>
  <div class="box">
    <h1>🛠 Rembayung Setup</h1>
    <ul>
      <?php foreach ($steps as $step): ?>
      <li><?= $step ?></li>
      <?php endforeach; ?>
    </ul>
    <div class="links">
      <a href="/index.php">View Site</a>
      <a href="/admin/login.php">Admin Panel</a>
    </div>
    <div class="warn">
      ⚠️ <strong>Delete setup.php</strong> from your server after setup is complete!
    </div>
  </div>
</body>
</html>
