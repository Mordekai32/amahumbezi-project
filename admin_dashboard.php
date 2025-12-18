<?php
session_start();
include 'db.php';

// Admin access check
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit();
}

// --- Helper function for safe query ---
function get_count($conn, $query){
    $res = $conn->query($query);
    if($res) return $res->fetch_assoc()['count'] ?? 0;
    return 0;
}

// Summary stats
$total_drinks = get_count($conn, "SELECT COUNT(*) as count FROM drinks");
$total_foods = get_count($conn, "SELECT COUNT(*) as count FROM foods");
$total_customers = get_count($conn, "SELECT COUNT(*) as count FROM users WHERE role='customer'");
$total_reservations = get_count($conn, "SELECT COUNT(*) as count FROM reservations");

// Recent reservations
$recent_res = $conn->query("
    SELECT r.id, r.reservation_date, r.reservation_time, r.guests, r.status, u.name as customer_name, d.name as drink_name
    FROM reservations r
    LEFT JOIN users u ON r.user_id = u.id
    LEFT JOIN drinks d ON r.drink_id = d.id
    ORDER BY r.reservation_date DESC, r.reservation_time DESC
    LIMIT 6
");

// Customers
$customers_result = $conn->query("SELECT id, name, email, created_at FROM users WHERE role='customer' ORDER BY created_at DESC LIMIT 50");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | Bar Website</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="bg-slate-50 text-slate-800">

<div class="flex h-screen overflow-hidden">

  <!-- Sidebar -->
  <aside class="hidden md:block w-72 bg-gradient-to-b from-blue-700 to-blue-800 text-white p-6">
    <div class="mb-6">
      <h1 class="text-2xl font-bold mb-1">Bar Admin</h1>
      <p class="text-blue-100/80 text-sm">Dashboard</p>
    </div>
    <nav>
      <ul class="space-y-2">
        <li><a href="admin_dashboard.php" class="block px-3 py-2 rounded hover:bg-white/20">Overview</a></li>
        <li><a href="manage_drinks.php" class="block px-3 py-2 rounded hover:bg-white/20">Manage Drinks</a></li>
        <li><a href="manage_foods.php" class="block px-3 py-2 rounded hover:bg-white/20">Manage Foods</a></li>
        <li><a href="manage_reservations.php" class="block px-3 py-2 rounded hover:bg-white/20">Manage Reservations</a></li>
        <li><a href="manage_customers.php" class="block px-3 py-2 rounded hover:bg-white/20">Manage Customers</a></li>
        <li class="pt-4"><a href="logout.php" class="block px-3 py-2 rounded hover:bg-white/20">Logout</a></li>
      </ul>
    </nav>
    <div class="mt-auto text-xs text-blue-100 mt-6">
      <div>© <?= date('Y') ?> Bar Admin</div>
      <div class="font-semibold text-yellow-300">Designed by Mordekai</div>
    </div>
  </aside>

  <!-- Main Content -->
  <div class="flex-1 flex flex-col">
    <header class="bg-white shadow p-4 flex justify-between items-center">
      <h2 class="text-xl font-semibold">Dashboard</h2>
      <span class="text-sm text-slate-600">Hello, <?= htmlspecialchars($_SESSION['name']) ?></span>
    </header>

    <main class="p-6 flex-1 overflow-y-auto">

      <!-- Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl p-5 shadow">
          <p class="text-sm text-slate-500">Total Drinks</p>
          <p class="mt-2 text-3xl font-bold"><?= $total_drinks ?></p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow">
          <p class="text-sm text-slate-500">Total Foods</p>
          <p class="mt-2 text-3xl font-bold"><?= $total_foods ?></p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow">
          <p class="text-sm text-slate-500">Total Customers</p>
          <p class="mt-2 text-3xl font-bold"><?= $total_customers ?></p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow">
          <p class="text-sm text-slate-500">Total Reservations</p>
          <p class="mt-2 text-3xl font-bold"><?= $total_reservations ?></p>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <a href="manage_drinks.php" class="bg-blue-600 text-white rounded-lg py-3 text-center font-semibold hover:bg-blue-700 transition">Add / Edit Drinks</a>
        <a href="manage_foods.php" class="bg-yellow-600 text-white rounded-lg py-3 text-center font-semibold hover:bg-yellow-700 transition">Add / Edit Foods</a>
        <a href="manage_reservations.php" class="bg-emerald-600 text-white rounded-lg py-3 text-center font-semibold hover:bg-emerald-700 transition">View Reservations</a>
      </div>

      <!-- Recent Reservations -->
      <div class="bg-white rounded-xl p-5 shadow mb-6">
        <h3 class="text-lg font-semibold mb-4">Recent Reservations</h3>
        <?php if($recent_res && $recent_res->num_rows): ?>
          <div class="space-y-3">
          <?php while($r = $recent_res->fetch_assoc()): ?>
            <div class="flex justify-between p-3 border rounded-lg">
              <div>
                <div class="font-semibold"><?= htmlspecialchars($r['customer_name'] ?? 'Guest') ?></div>
                <div class="text-sm text-slate-500"><?= htmlspecialchars($r['drink_name'] ?? '-') ?> • <?= htmlspecialchars($r['reservation_date']) ?> <?= htmlspecialchars($r['reservation_time']) ?></div>
              </div>
              <div class="text-right">
                <span class="px-2 py-1 text-xs rounded-full <?= $r['status']=='completed'?'bg-green-100 text-green-700':($r['status']=='confirmed'?'bg-blue-100 text-blue-700':'bg-gray-100 text-slate-700')?>"><?= ucfirst($r['status']) ?></span>
                <div class="text-xs text-slate-500 mt-1"><?= htmlspecialchars($r['guests']) ?> guests</div>
              </div>
            </div>
          <?php endwhile; ?>
          </div>
        <?php else: ?>
          <p class="text-sm text-slate-500">No recent reservations found.</p>
        <?php endif; ?>
      </div>

      <!-- Customers Table -->
      <div class="bg-white rounded-xl p-5 shadow">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">Customers</h3>
          <a href="manage_customers.php" class="text-blue-600 text-sm hover:underline">View all</a>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-sm table-auto">
            <thead class="bg-slate-50 text-slate-600">
              <tr>
                <th class="p-3 text-left">ID</th>
                <th class="p-3 text-left">Name</th>
                <th class="p-3 text-left">Email</th>
                <th class="p-3 text-left">Joined</th>
              </tr>
            </thead>
            <tbody>
              <?php if($customers_result && $customers_result->num_rows): ?>
                <?php while($c = $customers_result->fetch_assoc()): ?>
                  <tr class="border-t">
                    <td class="p-3"><?= $c['id'] ?></td>
                    <td class="p-3"><?= htmlspecialchars($c['name']) ?></td>
                    <td class="p-3"><?= htmlspecialchars($c['email']) ?></td>
                    <td class="p-3"><?= htmlspecialchars($c['created_at'] ?? '-') ?></td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td class="p-3" colspan="4">No customers yet.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t p-4 text-sm text-slate-600 text-center">
      © <?= date('Y') ?> Bar Admin Dashboard | Designed by Mordekai
    </footer>
  </div>
</div>

<script>
feather.replace();
</script>
</body>
</html>
