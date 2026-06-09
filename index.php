<?php
// ============================================================
// index.php — Main page (PHP version of the restaurant site)
// Uses: require/include, arrays, loops, PHP session output
// ============================================================

require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/menu_data.php';

$pageTitle = 'Home';

// ── Reservation feedback from session ────────────────────────
$resErrors  = $_SESSION['res_errors'] ?? [];
$resOld     = $_SESSION['res_old']    ?? [];
$resSuccess = isset($_GET['reservation']) && $_GET['reservation'] === 'success';
unset($_SESSION['res_errors'], $_SESSION['res_old']);

// ── Feedback form state ───────────────────────────────────────
$fbErrors  = $_SESSION['feedback_errors'] ?? [];
$fbOld     = $_SESSION['feedback_old']    ?? [];
$fbSuccess = isset($_GET['feedback']) && $_GET['feedback'] === 'success';
unset($_SESSION['feedback_errors'], $_SESSION['feedback_old']);

// ── Load published feedback from DB (latest 6) ────────────────
$feedbackRows = [];
$fbResult = $conn->query("SELECT name, rating, comment, submitted_date FROM feedback ORDER BY submitted_date DESC, feedback_id DESC LIMIT 6");
if ($fbResult) {
    $feedbackRows = $fbResult->fetch_all(MYSQLI_ASSOC);
}

// ── Load gallery images array ─────────────────────────────────
$galleryImages = [
    ['src' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&q=80', 'alt' => 'Restaurant ambience'],
    ['src' => 'image/daging masak lemak.jpg', 'alt' => 'Traditional dish'],
    ['src' => 'image/ikan siakap belado.jpg.jpeg', 'alt' => 'Sambal Berlada'],
    ['src' => 'image/sambal udang.jpg', 'alt' => 'Masak Lemak'],
    ['src' => 'image/TelurMasakKicap.jpg.jpeg', 'alt' => 'Vegetables'],
    ['src' => 'image/SAMBAL TERUNG.jpg.jpeg', 'alt' => 'Eggplant dish'],
    ['src' => 'image/Telur Dadar.jpg.jpeg', 'alt' => 'Cucur Udang'],
    ['src' => 'image/Resepi Ayam Masak Lemak Cili Api Negeri Sembilan.jpg.jpeg', 'alt' => 'Ayam Masak Lemak Cili Api'],
];

// ── Chef's specials array ─────────────────────────────────────
$specials = [
    [
        'label' => 'Masak Lemak',
        'title' => 'Masak Lemak Cili Api',
        'desc'  => 'The most iconic dish at Rembayung — a thick, fragrant coconut milk gravy infused with fresh cili api (bird\'s eye chilli). Rich, creamy, and with a fiery kick.',
        'items' => [
            'Smoked Chicken Masak Lemak Cili Api — RM 29.90',
            'Smoked Beef Masak Lemak Cili Api — RM 35.90',
            'River Snails (Siput Sedut) Masak Lemak — Market Price',
        ],
        'img'   => 'image/daging masak lemak.jpg',
    ],
    [
        'label' => 'Sambal Berlada',
        'title' => 'Sambal Berlada',
        'desc'  => 'Bold and unapologetic — a sambal made from fresh chillies, shrimp paste, and aromatic spices. The combination produces an unforgettable aroma and depth of flavour.',
        'items' => [
            'Sea Bass (Siakap) Sambal Berlado — RM 68.90',
            'Catfish (Ikan Keli) Sambal Berlada — Market Price',
            'Free-Range Chicken Sambal Berlada — Market Price',
        ],
        'img'   => 'image/ikan siakap belado.jpg.jpeg',
    ],
    [
        'label' => 'Northern Gulai',
        'title' => 'Northern Gulai',
        'desc'  => 'Inspired by the traditional cuisine of Northern Peninsular Malaysia, Rembayung\'s gulai uses hand-processed spice blends to produce a deep, complex flavour.',
        'items' => [
            'Gulai Red Snapper (Ikan Jenahak) — Market Price',
            'Gulai Silver Pomfret (Ikan Temenung) — Market Price',
            'Gulai Salted Fish (Ikan Masin) — Market Price',
        ],
        'img'   => 'image/gulai utara.jpg.jpeg',
    ],
];

// ── About features array ──────────────────────────────────────
$features = [
    ['icon' => 'fa-utensils',       'title' => 'Authentic Recipes',     'desc' => 'Traditional family recipes passed down through generations'],
    ['icon' => 'fa-users',          'title' => '250 Guests',            'desc' => 'Spacious seating for large groups and families'],
    ['icon' => 'fa-calendar-check', 'title' => 'Reservation Required',  'desc' => 'Walk-ins not accepted — booking is mandatory'],
    ['icon' => 'fa-mosque',         'title' => 'Prayer Room',           'desc' => 'Surau available for guests on the premises'],
];

// ── Why-us cards array ────────────────────────────────────────
$whyCards = [
    ['icon' => 'fa-leaf',   'title' => 'Fresh Daily Ingredients', 'desc' => 'Every dish is prepared with fresh ingredients sourced daily to ensure the highest quality and most authentic taste in every bite.'],
    ['icon' => 'fa-award',  'title' => 'Experienced Chefs',       'desc' => 'Managed by chefs skilled in traditional Malay cooking, including iconic dishes like Masak Lemak Cili Api and Sambal Berlada.'],
    ['icon' => 'fa-heart',  'title' => 'Family Friendly',         'desc' => 'A comfortable environment with 18 air conditioning units, kids\' sets available, and a prayer room for the convenience of all guests.'],
];

// ── Category labels map ───────────────────────────────────────
$categoryLabels = [
    'all'   => 'All',
    'main'  => 'Main Dishes',
    'sides' => 'Sides & Vegetables',
    'rice'  => 'Rice',
    'kids'  => "Kids' Set",
];

require_once __DIR__ . '/includes/header.php';
?>

<!-- HERO -->
<section id="hero">
  <div class="hero-content">
    <h1>Rembayung</h1>
    <p>Traditional Malay Cuisine &nbsp;·&nbsp; Kampung Baru, Kuala Lumpur</p>
    <div class="hero-btns">
      <a href="#menu"        class="btn-primary">View Menu</a>
      <a href="#reservation" class="btn-outline">Book A Table</a>
    </div>
  </div>
  <div class="hero-scroll"><i class="fas fa-chevron-down"></i></div>
</section>

<!-- ABOUT -->
<section id="about">
  <div class="container">
    <div class="about-grid">
      <div class="about-img fade-in">
        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800&q=80" alt="Rembayung Food"/>
        <div class="about-badge"><strong>2026</strong>Est.<br/>Jan</div>
      </div>
      <div class="about-text fade-in">
        <h2>Village Flavours,<br/>In the Heart of KL</h2>
        <p>Rembayung is the first restaurant owned by Khairul Aming, located in the historic Kampung Baru area — just 9 minutes from KLCC. The restaurant revives the nostalgia of authentic traditional Malay home cooking in a comfortable and modern setting.</p>
        <p>With an investment of over RM4 million and more than 50 local employees, Rembayung is more than a restaurant — it is a proud celebration of Malay food identity and culture.</p>
        <div class="about-features">
          <?php foreach ($features as $feature): ?>
          <div class="feature-item">
            <div class="feature-icon"><i class="fas <?= $feature['icon'] ?>"></i></div>
            <div>
              <h4><?= htmlspecialchars($feature['title']) ?></h4>
              <p><?= htmlspecialchars($feature['desc']) ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- WHY US -->
<section id="why">
  <div class="container">
    <div class="section-title fade-in">
      <span>Why Choose Us</span>
      <h2>The Rembayung Experience</h2>
      <div class="divider"></div>
    </div>
    <div class="why-grid">
      <?php foreach ($whyCards as $card): ?>
      <div class="why-card fade-in">
        <i class="fas <?= $card['icon'] ?>"></i>
        <h3><?= htmlspecialchars($card['title']) ?></h3>
        <p><?= htmlspecialchars($card['desc']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- MENU — rendered from PHP array via foreach loop -->
<section id="menu">
  <div class="container">
    <div class="section-title fade-in">
      <span>Our Menu</span>
      <h2>Rembayung's Signature Dishes</h2>
      <div class="divider"></div>
    </div>

    <!-- Filter buttons — generated from categoryLabels array -->
    <div class="menu-filters fade-in">
      <?php foreach ($categoryLabels as $catKey => $catLabel): ?>
      <button class="filter-btn <?= $catKey === 'all' ? 'active' : '' ?>"
              onclick="filterMenu('<?= $catKey ?>', this)">
        <?= htmlspecialchars($catLabel) ?>
      </button>
      <?php endforeach; ?>
    </div>

    <!-- Menu cards — looped from $menuItems PHP array -->
    <div class="menu-grid" id="menuGrid">
      <?php foreach ($menuItems as $item): ?>
      <div class="menu-card fade-in" data-cat="<?= htmlspecialchars($item['cat']) ?>">
        <img class="menu-card-img"
             src="<?= htmlspecialchars($item['img']) ?>"
             alt="<?= htmlspecialchars($item['name']) ?>"/>
        <div class="menu-card-body">
          <span class="menu-category-tag"><?= htmlspecialchars($item['tag']) ?></span>
          <h4><?= htmlspecialchars($item['name']) ?></h4>
          <p><?= htmlspecialchars($item['desc']) ?></p>
          <span class="menu-price">RM <?= number_format($item['price'], 2) ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CHEF'S SPECIALS — rendered from $specials PHP array -->
<section id="specials">
  <div class="container">
    <div class="section-title fade-in">
      <span>Chef's Specials</span>
      <h2>Rembayung Signatures</h2>
      <div class="divider"></div>
    </div>

    <div class="specials-tabs fade-in">
      <?php foreach ($specials as $i => $special): ?>
      <button class="tab-btn <?= $i === 0 ? 'active' : '' ?>"
              onclick="switchTab(<?= $i ?>, this)">
        <?= htmlspecialchars($special['label']) ?>
      </button>
      <?php endforeach; ?>
    </div>

    <?php foreach ($specials as $i => $special): ?>
    <div class="tab-content <?= $i === 0 ? 'active' : '' ?>" id="tab<?= $i ?>">
      <div class="special-text fade-in">
        <h3><?= htmlspecialchars($special['title']) ?></h3>
        <p><?= htmlspecialchars($special['desc']) ?></p>
        <ul>
          <?php foreach ($special['items'] as $dish): ?>
          <li><?= htmlspecialchars($dish) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="special-img fade-in">
        <img src="<?= htmlspecialchars($special['img']) ?>"
             alt="<?= htmlspecialchars($special['title']) ?>"/>
      </div>
    </div>
    <?php endforeach; ?>

  </div>
</section>

<!-- RESERVATION — PHP form with server-side validation feedback -->
<section id="reservation">
  <div class="container">
    <div class="section-title fade-in">
      <span>Reservations</span>
      <h2>Book Your Table</h2>
      <div class="divider"></div>
    </div>

    <div class="reservation-form fade-in">

      <?php /* success handled by JS toast */ ?>

      <?php if (!empty($resErrors)): ?>
        <div class="error-box">
          <ul>
            <?php foreach ($resErrors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

     
      <form method="POST" action="/projectwp/rembayung_merged/reservation_handler.php" id="resForm">

        <div class="form-row">
          <div class="form-group">
            <label for="name">Full Name <span style="color:#e74c3c">*</span></label>
            <input type="text" name="name" id="name" placeholder="Your full name"
                   value="<?= htmlspecialchars($resOld['name'] ?? '') ?>" required/>
          </div>
          <div class="form-group">
            <label for="phone">Phone Number <span style="color:#e74c3c">*</span></label>
            <input type="tel" name="phone" id="phone" placeholder="+60 12-345 6789"
                   value="<?= htmlspecialchars($resOld['phone'] ?? '') ?>" required/>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="date">Date <span style="color:#e74c3c">*</span></label>
            <input type="date" name="date" id="date"
                   value="<?= htmlspecialchars($resOld['date'] ?? '') ?>"
                   min="<?= date('Y-m-d') ?>" required/>
          </div>
          <div class="form-group">
            <label for="time">Time <span style="color:#e74c3c">*</span></label>
            <input type="time" name="time" id="time"
                   value="<?= htmlspecialchars($resOld['time'] ?? '') ?>" required/>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="guests">Number of Guests <span style="color:#e74c3c">*</span></label>
            <select name="guests" id="guests" required>
              <?php
              $guestOptions = ['1'=>'1 Person','2'=>'2 People','3-4'=>'3–4 People','5-6'=>'5–6 People','7-10'=>'7–10 People','10+'=>'More than 10'];
              foreach ($guestOptions as $val => $label):
                  $sel = (($resOld['guests'] ?? '') === $val) ? 'selected' : '';
              ?>
              <option value="<?= $val ?>" <?= $sel ?>><?= $label ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" placeholder="email@example.com"
                   value="<?= htmlspecialchars($resOld['email'] ?? '') ?>"/>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group form-full">
            <label for="requests">Special Requests</label>
            <textarea name="requests" id="requests" rows="3"
                      placeholder="Celebrations, food allergies, special requirements..."><?= htmlspecialchars($resOld['requests'] ?? '') ?></textarea>
          </div>
        </div>

        <div class="form-submit">
          <button type="submit" class="btn-submit">Submit Reservation</button>
        </div>

      </form>
    </div>
  </div>
</section>

<!-- GALLERY — rendered from PHP array -->
<section id="gallery">
  <div class="container">
    <div class="section-title fade-in">
      <span>Gallery</span>
      <h2>A Taste of Rembayung</h2>
      <div class="divider"></div>
    </div>
    <div class="gallery-grid fade-in">
      <?php foreach ($galleryImages as $img): ?>
      <div class="gallery-item" onclick="openLightbox(this)">
        <img src="<?= htmlspecialchars($img['src']) ?>"
             alt="<?= htmlspecialchars($img['alt']) ?>"/>
        <div class="gallery-overlay"><i class="fas fa-expand"></i></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FEEDBACK — Customer reviews + submission form -->
<section id="feedback">
  <div class="container">
    <div class="section-title fade-in">
      <span>Customer Reviews</span>
      <h2>What Our Guests Say</h2>
      <div class="divider"></div>
    </div>

    <!-- ── Existing reviews display ─────────────────────── -->
    <?php if (!empty($feedbackRows)): ?>
    <div class="feedback-grid fade-in">
      <?php foreach ($feedbackRows as $fb): ?>
      <div class="feedback-card">
        <div class="feedback-stars">
          <?= str_repeat('★', (int)$fb['rating']) ?><?= str_repeat('☆', 5 - (int)$fb['rating']) ?>
        </div>
        <p class="feedback-comment">"<?= htmlspecialchars($fb['comment']) ?>"</p>
        <div class="feedback-meta">
          <strong><?= htmlspecialchars($fb['name']) ?></strong>
          <span><?= htmlspecialchars($fb['submitted_date']) ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- ── Feedback submission form ─────────────────────── -->
    <div class="feedback-form-wrap fade-in">
      <h3>Leave Your Review</h3>

      <?php /* success handled by JS toast */ ?>

      <?php if (!empty($fbErrors)): ?>
        <div class="error-box">
          <ul>
            <?php foreach ($fbErrors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="POST" action="/projectwp/rembayung_merged/feedback_handler.php" id="fbForm">

        <div class="form-row">
          <div class="form-group">
            <label for="fb_name">Your Name <span style="color:#e74c3c">*</span></label>
            <input type="text" name="fb_name" id="fb_name" placeholder="Your full name"
                   value="<?= htmlspecialchars($fbOld['fb_name'] ?? '') ?>" required/>
          </div>
          <div class="form-group">
            <label>Your Rating <span style="color:#e74c3c">*</span></label>
            <div class="star-rating">
              <?php for ($s = 5; $s >= 1; $s--): ?>
              <input type="radio" name="fb_rating" id="star<?= $s ?>" value="<?= $s ?>"
                     <?= (($fbOld['fb_rating'] ?? '') == $s) ? 'checked' : '' ?>/>
              <label for="star<?= $s ?>" title="<?= $s ?> star<?= $s > 1 ? 's' : '' ?>">★</label>
              <?php endfor; ?>
            </div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group form-full">
            <label for="fb_comment">Your Review <span style="color:#e74c3c">*</span></label>
            <textarea name="fb_comment" id="fb_comment" rows="4"
                      placeholder="Tell us about your experience at Rembayung..." required><?= htmlspecialchars($fbOld['fb_comment'] ?? '') ?></textarea>
          </div>
        </div>

        <div class="form-submit">
          <button type="submit" class="btn-submit">Submit Review</button>
        </div>

      </form>
    </div>
  </div>
</section>

<!-- CONTACT -->
<section id="contact">
  <div class="container">
    <div class="section-title fade-in">
      <span>Get In Touch</span>
      <h2>Find Us Here</h2>
      <div class="divider"></div>
    </div>
    <div class="contact-grid fade-in">
      <div class="contact-card">
        <i class="fas fa-map-marker-alt"></i>
        <h4>Location</h4>
        <p>Lot 2791, Jalan Daud,<br/>Off Jalan Raja Muda Abdul Aziz,<br/>Kampung Baru, 50300 Kuala Lumpur</p>
      </div>
      <div class="contact-card">
        <i class="fas fa-clock"></i>
        <h4>Opening Hours</h4>
        <p>Monday – Sunday<br/>11:00 AM – 10:00 PM<br/><small style="color:#666">*Reservations required — no walk-ins</small></p>
      </div>
      <div class="contact-card">
        <i class="fas fa-phone-alt"></i>
        <h4>Contact Us</h4>
        <p>
          <a href="tel:+60123456789">+60 12-345 6789</a><br/>
          <a href="mailto:info@rembayung.com">info@rembayung.com</a><br/>
          <a href="https://rembayung.com" target="_blank">www.rembayung.com</a>
        </p>
      </div>
    </div>
    <div class="map-wrapper fade-in">
      <iframe src="https://maps.google.com/maps?width=600&height=400&hl=en&q=Rembayung&t=&z=14&ie=UTF8&iwloc=B&output=embed"
              allowfullscreen loading="lazy"></iframe>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
