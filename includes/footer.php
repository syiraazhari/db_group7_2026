<?php
// ============================================================
// includes/footer.php — Shared footer + JS
// ============================================================
?>
<footer>
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <span class="logo">Rembayung</span>
        <p>A traditional Malay restaurant by Khairul Aming, in the heart of Kampung Baru, Kuala Lumpur.</p>
        <div class="footer-social">
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-tiktok"></i></a>
          <a href="#"><i class="fab fa-youtube"></i></a>
        </div>
      </div>
      <div class="footer-col">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="/projectwp/rembayung_merged/index.php#hero">Home</a></li>
          <li><a href="/projectwp/rembayung_merged/index.php#about">About Us</a></li>
          <li><a href="/projectwp/rembayung_merged/index.php#menu">Menu</a></li>
          <li><a href="/projectwp/rembayung_merged/index.php#gallery">Gallery</a></li>
          <li><a href="/projectwp/rembayung_merged/index.php#contact">Contact</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Popular Dishes</h4>
        <ul>
          <li><a href="/projectwp/rembayung_merged/index.php#menu">Smoked Chicken Masak Lemak</a></li>
          <li><a href="/projectwp/rembayung_merged/index.php#menu">Sea Bass Sambal Berlado</a></li>
          <li><a href="/projectwp/rembayung_merged/index.php#menu">Prawns Sambal Tumis Petai</a></li>
          <li><a href="/projectwp/rembayung_merged/index.php#menu">Smoked Beef Masak Lemak</a></li>
          <li><a href="/projectwp/rembayung_merged/index.php#menu">Cucur Udang</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Information</h4>
        <ul>
          <li><a href="/projectwp/rembayung_merged/index.php#reservation">Reservation System</a></li>
          <li><a href="/projectwp/rembayung_merged/admin/login.php">Admin Login</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>© <?= date('Y') ?> <strong style="color:var(--gold)">Rembayung</strong> by Khairul Aming. All Rights Reserved.</p>
    </div>
  </div>
</footer>


<!-- TOAST NOTIFICATION -->
<div id="toast">
  <i class="fas fa-check-circle"></i>
  <div>
    <span class="toast-title" id="toast-title"></span>
    <span class="toast-msg" id="toast-msg"></span>
  </div>
</div>

<!-- LIGHTBOX MODAL -->
<div id="lightbox" onclick="closeLightbox()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.92);z-index:9999;cursor:zoom-out;">
  <span onclick="closeLightbox()" style="position:absolute;top:20px;right:30px;color:#fff;font-size:36px;cursor:pointer;line-height:1;">&times;</span>
  <img id="lightbox-img" src="" alt="" style="max-width:90vw;max-height:90vh;object-fit:contain;border-radius:4px;box-shadow:0 8px 40px rgba(0,0,0,0.6);"/>
</div>

<script src="/projectwp/rembayung_merged/js/main.js"></script>
</body>
</html>
