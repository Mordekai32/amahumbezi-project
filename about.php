<?php
session_start();
$user_id   = $_SESSION['user_id'] ?? null;
$user_name = $_SESSION['name'] ?? null;

function e($s){ return htmlspecialchars($s, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us • Sip & Savo</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
  .card-hover:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 30px rgba(0,0,0,.1);
    transition: all 0.3s;
  }
  .social-hover:hover {
    transform: scale(1.1);
    transition: all 0.3s;
  }
</style>
</head>

<body class="bg-slate-50 min-h-screen flex flex-col">

<!-- ================= NAVBAR ================= -->
<header class="bg-white shadow sticky top-0 z-40">
  <div class="max-w-6xl mx-auto p-4 flex justify-between items-center">
    <a href="index.php" class="flex items-center gap-3">
      <div class="bg-blue-600 text-white rounded-md p-2"><i class="fa-solid fa-martini-glass-citrus"></i></div>
      <div>
        <h1 class="text-xl font-bold">Sip & Savo</h1>
        <div class="text-xs text-slate-400">Bar & Dining</div>
      </div>
    </a>
    <nav class="hidden md:flex gap-6 text-sm">
      <a href="index.php" class="hover:text-blue-600"><i class="fa-solid fa-house mr-1"></i>Home</a>
      <a href="about.php" class="text-blue-600 font-semibold"><i class="fa-solid fa-circle-info mr-1"></i>About</a>
      <a href="service.php" class="hover:text-blue-600"><i class="fa-solid fa-bell-concierge mr-1"></i>Services</a>
      <a href="contact.php" class="hover:text-blue-600"><i class="fa-solid fa-envelope mr-1"></i>Contact</a>
    </nav>
    <div class="flex gap-3 items-center">
      <?php if($user_id): ?>
        <div class="text-sm text-slate-600 hidden sm:block">
          <i class="fa-solid fa-user mr-1"></i>Hello, <span class="font-semibold"><?= e($user_name) ?></span>
        </div>
        <a href="index.php?action=logout" class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600"><i class="fa-solid fa-right-from-bracket mr-1"></i>Logout</a>
      <?php else: ?>
        <a href="login.php" class="text-sm px-3 py-2 rounded border hover:bg-slate-100"><i class="fa-solid fa-right-to-bracket mr-1"></i>Login</a>
        <a href="register.php" class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700"><i class="fa-solid fa-user-plus mr-1"></i>Register</a>
      <?php endif; ?>
    </div>
  </div>
</header>

<!-- ================= MAIN ================= -->
<main class="flex-1">

  <!-- HERO -->
  <section class="bg-gradient-to-b from-blue-600 to-blue-700 text-white py-20">
    <div class="max-w-4xl mx-auto text-center px-4">
      <h1 class="text-4xl md:text-5xl font-extrabold"><i class="fa-solid fa-circle-info mr-2"></i>About Sip & Savo</h1>
      <p class="mt-4 text-slate-200 text-lg">
        Where quality drinks, delicious food, and great moments come together.
      </p>
    </div>
  </section>

  <!-- CONTENT -->
  <section class="max-w-6xl mx-auto p-6 grid md:grid-cols-2 gap-10 mt-10">
    <div class="bg-white p-6 rounded-xl shadow card-hover">
      <h2 class="text-2xl font-bold text-blue-700 mb-3"><i class="fa-solid fa-users mr-2"></i>Who We Are</h2>
      <p class="text-slate-600 leading-relaxed">
        <b>Sip & Savo</b> is a modern bar & dining place designed to bring people
        together. We serve premium drinks, tasty meals, and offer a comfortable
        environment for relaxation and celebrations.
      </p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow card-hover">
      <h2 class="text-2xl font-bold text-blue-700 mb-3"><i class="fa-solid fa-thumbs-up mr-2"></i>Why Choose Us</h2>
      <ul class="text-slate-600 space-y-2 list-disc list-inside">
        <li>Premium drinks & food</li>
        <li>Friendly customer service</li>
        <li>Easy online reservations</li>
        <li>Perfect place for events</li>
      </ul>
    </div>
  </section>

  <!-- MISSION -->
  <section class="max-w-4xl mx-auto mt-12 px-6">
    <div class="bg-blue-600 text-white p-8 rounded-xl text-center card-hover">
      <h2 class="text-2xl font-bold mb-2"><i class="fa-solid fa-bullseye mr-2"></i>Our Mission</h2>
      <p>
        To deliver unforgettable bar & dining experiences through quality,
        comfort, and excellent service.
      </p>
    </div>
  </section>

</main>

<!-- ================= FOOTER ================= -->
<footer class="bg-slate-900 text-slate-300 mt-20">
  <div class="max-w-6xl mx-auto px-6 py-12 grid gap-10 md:grid-cols-4">

    <!-- BRAND -->
    <div>
      <h3 class="text-2xl font-bold text-white"><i class="fa-solid fa-martini-glass-citrus mr-1"></i>Sip & Savo</h3>
      <p class="mt-3 text-sm text-slate-400">
        Premium bar & dining experience. Enjoy quality drinks, delicious food,
        and unforgettable moments.
      </p>
    </div>

    <!-- LINKS -->
    <div>
      <h4 class="text-white font-semibold mb-3">Quick Links</h4>
      <ul class="space-y-2 text-sm">
        <li><a href="index.php" class="hover:text-white"><i class="fa-solid fa-house mr-1"></i>Home</a></li>
        <li><a href="about.php" class="hover:text-white"><i class="fa-solid fa-circle-info mr-1"></i>About Us</a></li>
        <li><a href="service.php" class="hover:text-white"><i class="fa-solid fa-bell-concierge mr-1"></i>Services</a></li>
        <li><a href="contact.php" class="hover:text-white"><i class="fa-solid fa-envelope mr-1"></i>Contact</a></li>
      </ul>
    </div>

    <!-- CONTACT -->
    <div>
      <h4 class="text-white font-semibold mb-3">Contact</h4>
      <ul class="space-y-2 text-sm">
        <li><i class="fa-solid fa-envelope mr-1"></i>mordekai893@gmail.com</li>
        <li><i class="fa-solid fa-phone mr-1"></i>+250 796 381 024</li>
        <li><i class="fa-solid fa-location-dot mr-1"></i>Kigali, Rwanda</li>
      </ul>
    </div>

    <!-- SOCIAL -->
    <div>
      <h4 class="text-white font-semibold mb-3">Follow Us</h4>
      <div class="flex gap-4">
        <a href="https://instagram.com/M.blaise_320" target="_blank" class="bg-pink-600 hover:bg-pink-700 text-white p-3 rounded-full social-hover"><i class="fa-brands fa-instagram"></i></a>
        <a href="https://facebook.com/UM Mordekai" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full social-hover"><i class="fa-brands fa-facebook-f"></i></a>
      </div>
    </div>

  </div>

  <!-- COPYRIGHT -->
  <div class="border-t border-slate-700 text-center py-4 text-sm text-slate-400">
    © <?= date('Y') ?> Sip & Savo • Designed by <span class="text-white font-semibold">Mordekai</span>
  </div>
</footer>

</body>
</html>
