<?php
// ============================================================
// includes/session.php — PHP Session helpers
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if admin is logged in; redirect if not
function requireLogin() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: /projectwp/rembayung_merged/admin/login.php');
        exit();
    }
}

// Log the admin in
function loginAdmin($username) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_user']      = $username;
    $_SESSION['login_time']      = time();
}

// Log the admin out
function logoutAdmin() {
    $_SESSION = [];
    session_destroy();
    header('Location: /projectwp/rembayung_merged/admin/login.php');
    exit();
}

// Flash message helpers
function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'msg' => $message];
}

function getFlash() {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        $color = ($f['type'] === 'success') ? '#2ecc71' : '#e74c3c';
        return "<p style='color:{$color};text-align:center;margin:10px 0;font-weight:bold;'>{$f['msg']}</p>";
    }
    return '';
}
?>
