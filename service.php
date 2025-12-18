<?php
// service.php
session_start();
$user_id   = $_SESSION['user_id'] ?? null;
$user_name = $_SESSION['name'] ?? null;
$user_role = $_SESSION['role'] ?? null;

function e($s){
  return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Services • Sip & Savo</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    .card-hover:hover {
      transform: translateY(-6px);
      box-shadow: 0 12px 30px rgba(0,0,0,.1);
      transition: all 0.3s;
    }
  </style>
</head>
<body class="min-h-screen bg-slate-50 flex flex-col">

<!-- NAVBAR -->
<header class="bg-white shadow sticky top-0 z-40">
  <div class="max-w-6xl mx-auto flex items-center justify-between p-4">
    <a href="index.php" class="flex items-center gap-3">
      <div class="bg-blue-600 text-white rounded-md p-2"><i class="fa-solid fa-martini-glass-citrus"></i></div>
      <div>
        <h1 class="font-bold text-lg">Sip & Savo</h1>
        <div class="text-xs text-slate-400">Bar & Dining</div>
      </div>
    </a>

    <nav class="hidden md:flex items-center gap-6 text-sm">
      <a href="index.php" class="hover:text-blue-600"><i class="fa-solid fa-house mr-1"></i>Home</a>
      <a href="about.php" class="hover:text-blue-600"><i class="fa-solid fa-circle-info mr-1"></i>About</a>
      <a href="service.php" class="text-blue-600 font-semibold"><i class="fa-solid fa-bell-concierge mr-1"></i>Services</a>
      <a href="index.php#menu" class="hover:text-blue-600"><i class="fa-solid fa-utensils mr-1"></i>Menu</a>
      <a href="index.php#reserve" class="hover:text-blue-600"><i class="fa-solid fa-calendar-check mr-1"></i>Reserve</a>
      <a href="contact.php" class="hover:text-blue-600"><i class="fa-solid fa-envelope mr-1"></i>Contact</a>
    </nav>

    <div class="flex items-center gap-3">
      <?php if($user_id): ?>
        <?php if($user_role === 'admin'): ?>
          <a href="admin_dashboard.php" class="text-sm px-3 py-2 rounded bg-yellow-100 hover:bg-yellow-200"><i class="fa-solid fa-user-shield mr-1"></i>Admin</a>
        <?php endif; ?>
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

<main class="flex-1">

<!-- HERO -->
<section class="bg-gradient-to-b from-blue-600 to-blue-700 text-white py-20">
  <div class="max-w-6xl mx-auto px-4 text-center">
    <h2 class="text-4xl md:text-5xl font-extrabold"><i class="fa-solid fa-bell-concierge mr-2"></i>Our Services</h2>
    <p class="mt-4 text-slate-200 max-w-2xl mx-auto">
      We offer premium bar and dining services designed to give you unforgettable experiences.
    </p>
  </div>
</section>

<!-- SERVICES -->
<section class="max-w-6xl mx-auto p-6 grid sm:grid-cols-2 md:grid-cols-3 gap-8">

  <div class="bg-white p-6 rounded-2xl shadow card-hover transition">
    <h3 class="text-xl font-bold text-blue-700 mb-2"><i class="fa-solid fa-cocktail mr-2"></i>Premium Drinks</h3>
    <p class="text-slate-600">Enjoy a wide range of carefully selected alcoholic and non-alcoholic drinks.</p>
  </div>

  <div class="bg-white p-6 rounded-2xl shadow card-hover transition">
    <h3 class="text-xl font-bold text-blue-700 mb-2"><i class="fa-solid fa-burger mr-2"></i>Quality Food</h3>
    <p class="text-slate-600">Delicious meals prepared with fresh ingredients by experienced chefs.</p>
  </div>

  <div class="bg-white p-6 rounded-2xl shadow card-hover transition">
    <h3 class="text-xl font-bold text-blue-700 mb-2"><i class="fa-solid fa-calendar-check mr-2"></i>Table Reservation</h3>
    <p class="text-slate-600">Reserve your table online easily and avoid waiting.</p>
  </div>

  <div class="bg-white p-6 rounded-2xl shadow card-hover transition">
    <h3 class="text-xl font-bold text-blue-700 mb-2"><i class="fa-solid fa-party-horn mr-2"></i>Event Hosting</h3>
    <p class="text-slate-600">Perfect space for birthdays, parties, and private celebrations.</p>
  </div>

  <div class="bg-white p-6 rounded-2xl shadow card-hover transition">
    <h3 class="text-xl font-bold text-blue-700 mb-2"><i class="fa-solid fa-music mr-2"></i>Live Music & DJ</h3>
    <p class="text-slate-600">Enjoy live music and DJ sessions in a vibrant atmosphere.</p>
  </div>

  <div class="bg-white p-6 rounded-2xl shadow card-hover transition">
    <h3 class="text-xl font-bold text-blue-700 mb-2"><i class="fa-solid fa-hands-helping mr-2"></i>Customer Care</h3>
    <p class="text-slate-600">Friendly staff committed to giving you the best service.</p>
  </div>

</section>

<!-- CTA -->
<section class="bg-blue-50 py-16">
  <div class="max-w-4xl mx-auto px-6 text-center">
    <h3 class="text-3xl font-bold text-slate-800">Experience Our Services Today</h3>
    <p class="mt-3 text-slate-600">
      Book a table or explore our menu and enjoy the Sip & Savo experience.
    </p>
    <div class="mt-6 flex justify-center gap-4">
      <a href="index.php#menu" class="bg-blue-600 text-white px-6 py-3 rounded font-semibold hover:bg-blue-700">
        <i class="fa-solid fa-utensils mr-2"></i>View Menu
      </a>
      <a href="index.php#reserve" class="border border-blue-600 text-blue-600 px-6 py-3 rounded font-semibold hover:bg-blue-50">
        <i class="fa-solid fa-calendar-check mr-2"></i>Make Reservation
      </a>
    </div>
  </div>
</section>

</main>

<!-- FOOTER -->
<footer class="bg-slate-900 text-slate-200">
  <div class="max-w-6xl mx-auto p-6 grid md:grid-cols-3 gap-6">
    <div>
      <h5 class="font-bold text-lg"><i class="fa-solid fa-martini-glass-citrus mr-1"></i>Sip & Savo</h5>
      <p class="text-sm text-slate-400 mt-2">Premium drinks & food. Where good vibes meet great taste.</p>
    </div>

    <div class="text-sm space-y-1">
      <p><i class="fa-solid fa-envelope mr-1"></i>Email: <a href="mailto:mordekai893@gmail.com" class="underline">mordekai893@gmail.com</a></p>
      <p><i class="fa-solid fa-phone mr-1"></i>Phone: 0796 381 024</p>
      <p><i class="fa-solid fa-phone mr-1"></i>Alt Phone: 0728 800 993</p>
    </div>

    <div class="text-sm md:text-right space-y-1">
      <p><i class="fa-brands fa-instagram mr-1"></i>Instagram: <a href="https://instagram.com/M.blaise_320" target="_blank" class="underline">@M.blaise_320</a></p>
      <p><i class="fa-brands fa-facebook-f mr-1"></i>Facebook: UM Mordekai</p>
      <p class="text-xs text-slate-400 mt-2">© <?= date('Y') ?> Sip & Savo</p>
    </div>
  </div>
</footer>

</body>
</html>
