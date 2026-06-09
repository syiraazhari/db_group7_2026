<?php
// ============================================================
// reservation_handler.php — PHP-side form handling + validation
// Called via POST from the reservation form in index.php
// ============================================================

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/session.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ── 1. Retrieve & sanitise inputs ────────────────────────
    $name     = trim($_POST['name']     ?? '');
    $phone    = trim($_POST['phone']    ?? '');
    $email    = trim($_POST['email']    ?? '');
    $date     = trim($_POST['date']     ?? '');
    $time     = trim($_POST['time']     ?? '');
    $guests   = trim($_POST['guests']   ?? '');
    $requests = trim($_POST['requests'] ?? '');

    // ── 2. PHP-side validation ───────────────────────────────
    if (empty($name)) {
        $errors[] = 'Full name is required.';
    } elseif (strlen($name) < 2 || strlen($name) > 100) {
        $errors[] = 'Name must be between 2 and 100 characters.';
    }

    if (empty($phone)) {
        $errors[] = 'Phone number is required.';
    } elseif (!preg_match('/^[0-9+\-\s]{7,20}$/', $phone)) {
        $errors[] = 'Please enter a valid phone number.';
    }

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (empty($date)) {
        $errors[] = 'Reservation date is required.';
    } elseif (strtotime($date) < strtotime('today')) {
        $errors[] = 'Reservation date cannot be in the past.';
    }

    if (empty($time)) {
        $errors[] = 'Reservation time is required.';
    }

    $validGuests = ['1','2','3-4','5-6','7-10','10+'];
    if (!in_array($guests, $validGuests)) {
        $errors[] = 'Please select a valid number of guests.';
    }

    // ── 3. If valid, INSERT into MySQL ───────────────────────
    if (empty($errors)) {
        $stmt = $conn->prepare(
            "INSERT INTO reservations (name, phone, email, res_date, res_time, guests, special_requests, status, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())"
        );
        $stmt->bind_param('sssssss', $name, $phone, $email, $date, $time, $guests, $requests);

        if ($stmt->execute()) {
            $success = true;
            setFlash('success', '✔ Your reservation has been submitted! We will contact you shortly to confirm.');
        } else {
            $errors[] = 'Database error: could not save reservation. Please try again.';
        }
        $stmt->close();
    }
}

// Redirect back to index with status
if ($success) {
    header('Location: /projectwp/rembayung_merged/index.php?reservation=success#reservation');
} else {
    // Pass errors back via session
    $_SESSION['res_errors'] = $errors;
    $_SESSION['res_old']    = $_POST;   // repopulate form
    header('Location: /projectwp/rembayung_merged/index.php?reservation=error#reservation');
}
exit();
?>
