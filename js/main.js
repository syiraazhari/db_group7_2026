// ============================================================
// js/main.js — Rembayung front-end interactions
// ============================================================

// Navbar scroll effect
window.addEventListener('scroll', () => {
  const navbar = document.getElementById('navbar');
  const topbar = document.getElementById('topbar');
  const topbarHeight = topbar.offsetHeight;
  if (window.scrollY > topbarHeight) {
    navbar.classList.add('scrolled');
    navbar.style.top = '0';
    topbar.style.transform = 'translateY(-100%)';
  } else {
    navbar.classList.remove('scrolled');
    navbar.style.top = topbarHeight + 'px';
    topbar.style.transform = 'translateY(0)';
  }
});

// Mobile menu toggle
function toggleMenu() {
  document.getElementById('mobileMenu').classList.toggle('open');
}

// Intersection observer for fade-in animations
const observer = new IntersectionObserver((entries) => {
  entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: 0.1 });
document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));

// Menu category filter
function filterMenu(cat, btn) {
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('.menu-card').forEach(card => {
    card.classList.toggle('hidden', cat !== 'all' && card.dataset.cat !== cat);
  });
}

// Chef's specials tab switcher
function switchTab(idx, btn) {
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('tab' + idx).classList.add('active');
}

// JS-side validation (pre-flight before PHP handles server-side validation)
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('resForm');
  if (!form) return;

  form.addEventListener('submit', (e) => {
    const name  = form.querySelector('[name="name"]').value.trim();
    const phone = form.querySelector('[name="phone"]').value.trim();
    const date  = form.querySelector('[name="date"]').value;
    const time  = form.querySelector('[name="time"]').value;

    const errs = [];
    if (!name)  errs.push('Full name is required.');
    if (!phone) errs.push('Phone number is required.');
    if (!date)  errs.push('Date is required.');
    if (!time)  errs.push('Time is required.');

    if (errs.length) {
      e.preventDefault();
      alert('Please fix the following:\n• ' + errs.join('\n• '));
    }
  });
});

// Gallery lightbox
function openLightbox(el) {
  const img = el.querySelector('img');
  const lb = document.getElementById('lightbox');
  document.getElementById('lightbox-img').src = img.src;
  document.getElementById('lightbox-img').alt = img.alt;
  lb.style.display = 'flex';
  lb.style.alignItems = 'center';
  lb.style.justifyContent = 'center';
  document.body.style.overflow = 'hidden';
}
function closeLightbox() {
  document.getElementById('lightbox').style.display = 'none';
  document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

// Toast notification
function showToast(title, msg) {
  const toast = document.getElementById('toast');
  document.getElementById('toast-title').textContent = title;
  document.getElementById('toast-msg').textContent = msg;
  toast.classList.add('show');
  setTimeout(() => toast.classList.remove('show'), 4500);
}

// Show toast on page load if success param in URL
document.addEventListener('DOMContentLoaded', () => {
  const params = new URLSearchParams(window.location.search);
  if (params.get('reservation') === 'success') {
    showToast('Reservation Confirmed!', 'We will contact you shortly to confirm your booking.');
    // Clean URL without reload
    history.replaceState({}, '', window.location.pathname + '#reservation');
  }
  if (params.get('feedback') === 'success') {
    showToast('Review Submitted!', 'Thank you for your feedback. We truly appreciate it.');
    history.replaceState({}, '', window.location.pathname + '#feedback');
  }
});
