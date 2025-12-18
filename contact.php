<?php
session_start();
$user_id   = $_SESSION['user_id'] ?? null;
$user_name = $_SESSION['name'] ?? null;
$user_role = $_SESSION['role'] ?? null;

function e($s){ return htmlspecialchars($s, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Contact Us • Sip & Savo</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
  .card-hover:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(0,0,0,.1); transition: all 0.3s; }
  .social-hover:hover { transform: scale(1.1); transition: all 0.3s; }
</style>
</head>
<body class="min-h-screen bg-slate-50 flex flex-col">

<!-- NAVBAR -->
<header class="bg-white shadow sticky top-0 z-40">
  <div class="max-w-6xl mx-auto flex items-center justify-between p-4">
    <a href="index.php" class="flex items-center gap-3">
      <div class="bg-blue-600 text-white rounded-md p-2"><i class="fa-solid fa-phone"></i></div>
      <div>
        <h1 class="font-bold text-lg">Sip & Savo</h1>
        <div class="text-xs text-slate-400">Bar & Dining</div>
      </div>
    </a>

    <nav class="hidden md:flex items-center gap-6 text-sm">
      <a href="index.php" class="hover:text-blue-600"><i class="fa-solid fa-house mr-1"></i>Home</a>
      <a href="about.php" class="hover:text-blue-600"><i class="fa-solid fa-circle-info mr-1"></i>About Us</a>
      <a href="service.php" class="hover:text-blue-600"><i class="fa-solid fa-bell-concierge mr-1"></i>Services</a>
      <a href="contact.php" class="text-blue-600 font-semibold"><i class="fa-solid fa-envelope mr-1"></i>Contact</a>
    </nav>

    <div class="flex items-center gap-3">
      <?php if($user_id): ?>
        <?php if($user_role==='admin'): ?>
          <a href="admin_dashboard.php" class="text-sm px-3 py-2 rounded bg-yellow-100 hover:bg-yellow-200">Admin</a>
        <?php endif; ?>
        <div class="text-sm text-slate-600 hidden sm:block"><i class="fa-solid fa-user mr-1"></i>Hello, <span class="font-semibold"><?= e($user_name) ?></span></div>
        <a href="index.php?action=logout" class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600"><i class="fa-solid fa-right-from-bracket mr-1"></i>Logout</a>
      <?php else: ?>
        <a href="login.php" class="text-sm px-3 py-2 rounded border hover:bg-slate-100"><i class="fa-solid fa-right-to-bracket mr-1"></i>Login</a>
        <a href="register.php" class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700"><i class="fa-solid fa-user-plus mr-1"></i>Register</a>
      <?php endif; ?>
    </div>
  </div>
</header>

<main class="flex-1">

<!-- HERO -->
<section class="bg-gradient-to-b from-blue-600 to-blue-700 text-white py-20">
  <div class="max-w-6xl mx-auto px-4 text-center">
    <h2 class="text-4xl md:text-5xl font-extrabold"><i class="fa-solid fa-envelope mr-2"></i>Contact Us</h2>
    <p class="mt-4 text-slate-200 max-w-2xl mx-auto">
      Reach out to us anytime. Our team is always ready to help you.
    </p>
  </div>
</section>

<!-- CONTACT INFO SECTION -->
<section class="max-w-6xl mx-auto p-6 grid md:grid-cols-1 gap-10 mt-10">
  <div class="space-y-6">
    <div>
      <h3 class="text-2xl font-bold text-blue-700 mb-2"><i class="fa-solid fa-address-book mr-1"></i>Get in Touch</h3>
      <p class="text-slate-600">
        Here’s how you can reach us directly:
      </p>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 space-y-3 card-hover">
      <p><i class="fa-solid fa-envelope mr-2"></i><strong>Email:</strong> mordekai893@gmail.com</p>
      <p><i class="fa-solid fa-phone mr-2"></i><strong>Phone:</strong> +250 796 381 024</p>
      <p><i class="fa-brands fa-whatsapp mr-2"></i><strong>WhatsApp:</strong> +250 728800993</p>
      <p><i class="fa-solid fa-location-dot mr-2"></i><strong>Location:</strong> Kigali, Rwanda</p>
    </div>
  </div>
</section>

</main>

<!-- POP-UP MODAL -->
<div id="promoPopup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
  <div class="bg-white rounded-2xl p-6 max-w-md mx-4 relative card-hover">
    <button id="closePopup" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 font-bold">✖</button>
    <h3 class="text-2xl font-bold mb-2 text-blue-600"><i class="fa-solid fa-gift mr-1"></i>Special Offer!</h3>
    <p class="text-gray-700 mb-4">Sign up for our newsletter and get 10% off your first order!</p>
    <form class="space-y-4">
      <input type="email" placeholder="Your email" class="w-full border px-3 py-2 rounded" required>
      <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Sign Up</button>
    </form>
  </div>
</div>

<!-- FOOTER -->
<footer class="bg-slate-900 text-slate-200 mt-20">
  <div class="max-w-6xl mx-auto p-8 grid gap-8 md:grid-cols-3">

    <div>
      <h5 class="font-bold text-lg text-white"><i class="fa-solid fa-martini-glass-citrus mr-1"></i>Sip & Savo</h5>
      <p class="text-sm text-slate-400 mt-2">
        Premium drinks & delicious food. Your perfect place to relax and celebrate.
      </p>
    </div>

    <div>
      <h6 class="font-semibold text-white mb-3">Contact</h6>
      <ul class="text-sm text-slate-400 space-y-2">
        <li><i class="fa-solid fa-envelope mr-1"></i>Email: <a href="mailto:mordekai893@gmail.com" class="underline hover:text-white">mordekai893@gmail.com</a></li>
        <li><i class="fa-solid fa-phone mr-1"></i>Phone: <a href="tel:+250796381024" class="hover:text-white">+250 796 381 024</a></li>
      </ul>
    </div>

    <div>
      <h6 class="font-semibold text-white mb-3">Follow Us</h6>
      <div class="flex gap-4">
        <a href="https://instagram.com/M.blaise_320" target="_blank" class="bg-pink-600 hover:bg-pink-700 text-white p-3 rounded-full social-hover"><i class="fa-brands fa-instagram"></i></a>
        <a href="https://facebook.com/UM Mordekai" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full social-hover"><i class="fa-brands fa-facebook-f"></i></a>
      </div>
    </div>

  </div>

  <div class="border-t border-slate-700 text-center text-sm text-slate-400 py-4">
    © <?= date('Y') ?> Sip & Savo • Designed by <span class="text-white font-semibold">Mordekai</span>
  </div>
</footer>

<!-- POPUP SCRIPT -->
<script>
window.addEventListener('DOMContentLoaded', () => {
  // Show popup after 3 seconds
  setTimeout(() => {
    document.getElementById('promoPopup').classList.remove('hidden');
  }, 3000);

  // Close popup
  document.getElementById('closePopup').addEventListener('click', () => {
    document.getElementById('promoPopup').classList.add('hidden');
  });
});
</script>

</body>
</html>
