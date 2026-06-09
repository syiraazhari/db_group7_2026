<?php
// ============================================================
// admin/login.php — Admin login with PHP session
// ============================================================

require_once __DIR__ . '/../includes/session.php';

// Redirect if already logged in
if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: /projectwp/rembayung_merged/admin/dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // ── Hardcoded admin credentials (replace with DB lookup in production) ──
    // Password: admin123  (stored as bcrypt hash below)
    $storedHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password

    if ($username === 'admin' && password_verify($password, $storedHash)) {
        loginAdmin($username);
        header('Location: /projectwp/rembayung_merged/admin/dashboard.php');
        exit();
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Login — Rembayung</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Lato:wght@400;700&display=swap" rel="stylesheet"/>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Lato', sans-serif; background: #1a1208; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
    .login-box { background: #2a1f0e; border: 1px solid rgba(200,169,110,0.3); border-radius: 6px; padding: 50px 40px; width: 100%; max-width: 400px; text-align: center; }
    .login-box h1 { font-family: 'Playfair Display', serif; color: #c8a96e; font-size: 28px; margin-bottom: 6px; }
    .login-box p  { color: #888; font-size: 13px; margin-bottom: 30px; }
    label { display: block; text-align: left; font-size: 12px; letter-spacing: 2px; text-transform: uppercase; color: #c8a96e; margin-bottom: 6px; }
    input { width: 100%; background: rgba(255,255,255,0.07); border: 1px solid rgba(200,169,110,0.3); color: #fff; padding: 12px 14px; font-size: 14px; border-radius: 3px; outline: none; margin-bottom: 20px; transition: border-color .3s; font-family: 'Lato', sans-serif; }
    input:focus { border-color: #c8a96e; }
    button { width: 100%; background: #c8a96e; color: #1a1208; border: none; padding: 13px; font-size: 14px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; cursor: pointer; border-radius: 3px; transition: background .3s; font-family: 'Lato', sans-serif; }
    button:hover { background: #e8d5a3; }
    .error { color: #e74c3c; font-size: 13px; margin-bottom: 16px; }
    .hint  { color: #666; font-size: 12px; margin-top: 20px; }
    a { color: #c8a96e; text-decoration: none; }
    a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <div class="login-box">
    <h1>Rembayung</h1>
    <p>Admin Panel</p>

    <?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="/projectwp/rembayung_merged/admin/login.php">
      <label for="username">Username</label>
      <input type="text" name="username" id="username"
             value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
             placeholder="admin" required autofocus/>

      <label for="password">Password</label>
      <input type="password" name="password" id="password"
             placeholder="••••••••" required/>

      <button type="submit">Login</button>
    </form>

    <p class="hint">Default: admin / password &nbsp;|&nbsp; <a href="/index.php">← Back to site</a></p>
  </div>
</body>
</html>
