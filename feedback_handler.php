<?php
// ============================================================
// feedback_handler.php — Validate and save feedback to DB
// ============================================================

require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /projectwp/rembayung_merged/index.php');
    exit();
}

$errors = [];

$name    = trim($_POST['fb_name']    ?? '');
$rating  = (int)($_POST['fb_rating'] ?? 0);
$comment = trim($_POST['fb_comment'] ?? '');

if ($name === '') {
    $errors[] = 'Name is required.';
}
if ($rating < 1 || $rating > 5) {
    $errors[] = 'Please select a valid rating (1–5 stars).';
}
if ($comment === '') {
    $errors[] = 'Review comment is required.';
}

if (!empty($errors)) {
    $_SESSION['feedback_errors'] = $errors;
    $_SESSION['feedback_old']    = $_POST;
    header('Location: /projectwp/rembayung_merged/index.php#feedback');
    exit();
}

$today = date('Y-m-d');

$stmt = $conn->prepare(
    "INSERT INTO feedback (name, rating, comment, submitted_date) VALUES (?, ?, ?, ?)"
);
$stmt->bind_param('siss', $name, $rating, $comment, $today);

if ($stmt->execute()) {
    $stmt->close();
    header('Location: /projectwp/rembayung_merged/index.php?feedback=success#feedback');
} else {
    $stmt->close();
    $_SESSION['feedback_errors'] = ['Database error. Please try again.'];
    $_SESSION['feedback_old']    = $_POST;
    header('Location: /projectwp/rembayung_merged/index.php#feedback');
}
exit();
