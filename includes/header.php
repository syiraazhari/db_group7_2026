<?php
// ============================================================
// includes/header.php — Shared HTML head + navbar
// ============================================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= htmlspecialchars($pageTitle ?? 'Rembayung') ?> — Rembayung Restaurant</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="/projectwp/rembayung_merged/css/style.css"/>
</head>
<body>

<!-- TOP BAR -->
<div id="topbar">
  <div class="container">
    <div class="topbar-left">
      <span><i class="fas fa-map-marker-alt"></i> Lot 2791, Jalan Daud, Kampung Baru, 50300 Kuala Lumpur</span>
    </div>
    <div class="topbar-right">
      <span><i class="fas fa-clock"></i> Mon – Sun: 11:00 AM – 10:00 PM</span>
      <span class="topbar-divider">|</span>
      <a href="tel:+60123456789"><i class="fas fa-phone"></i> +60 12-345 6789</a>
    </div>
  </div>
</div>

<!-- NAVBAR -->
<nav id="navbar">
  <div class="nav-inner">
    <a class="nav-logo" href="/projectwp/rembayung_merged/index.php">Rembayung</a>
    <ul class="nav-links">
      <li><a href="/projectwp/rembayung_merged/index.php#hero">Home</a></li>
      <li><a href="/projectwp/rembayung_merged/index.php#about">About</a></li>
      <li><a href="/projectwp/rembayung_merged/index.php#menu">Menu</a></li>
      <li><a href="/projectwp/rembayung_merged/index.php#specials">Specials</a></li>
      <li><a href="/projectwp/rembayung_merged/index.php#gallery">Gallery</a></li>
      <li><a href="/projectwp/rembayung_merged/index.php#feedback">Reviews</a></li>
      <li><a href="/projectwp/rembayung_merged/index.php#contact">Contact</a></li>
      <li><a href="/projectwp/rembayung_merged/index.php#reservation" class="btn-book">Book A Table</a></li>
    </ul>
    <div class="hamburger" onclick="toggleMenu()">
      <span></span><span></span><span></span>
    </div>
  </div>
</nav>

<!-- MOBILE MENU -->
<div class="mobile-menu" id="mobileMenu">
  <span class="close-menu" onclick="toggleMenu()"><i class="fas fa-times"></i></span>
  <a href="/projectwp/rembayung_merged/index.php#hero"        onclick="toggleMenu()">Home</a>
  <a href="/projectwp/rembayung_merged/index.php#about"       onclick="toggleMenu()">About</a>
  <a href="/projectwp/rembayung_merged/index.php#menu"        onclick="toggleMenu()">Menu</a>
  <a href="/projectwp/rembayung_merged/index.php#specials"    onclick="toggleMenu()">Specials</a>
  <a href="/projectwp/rembayung_merged/index.php#gallery"     onclick="toggleMenu()">Gallery</a>
  <a href="/projectwp/rembayung_merged/index.php#feedback"    onclick="toggleMenu()">Reviews</a>
  <a href="/projectwp/rembayung_merged/index.php#contact"     onclick="toggleMenu()">Contact</a>
  <a href="/projectwp/rembayung_merged/index.php#reservation" onclick="toggleMenu()">Book A Table</a>
</div>
