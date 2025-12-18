<?php
session_start();
include 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user_id   = $_SESSION['user_id'] ?? null;
$user_name = $_SESSION['name'] ?? null;
$user_role = $_SESSION['role'] ?? null;

$success = "";
$errors  = [];

/* LOGOUT */
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: index.php");
    exit;
}

/* RESERVATION */
if ($user_id && isset($_POST['reserve'])) {
    $item_id = intval($_POST['item_id'] ?? 0);
    $item_type = trim($_POST['item_type'] ?? '');
    $date = trim($_POST['reservation_date'] ?? '');
    $time = trim($_POST['reservation_time'] ?? '');
    $guests = intval($_POST['guests'] ?? 0);

    if (!$item_id || !$item_type || !$date || !$time || $guests < 1) {
        $errors[] = "All fields are required.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO reservations 
            (user_id,item_id,item_type,reservation_date,reservation_time,guests,status)
            VALUES (?,?,?,?,?,?,?)"
        );
        $status = "pending";
        $stmt->bind_param("iisssis",
            $user_id,$item_id,$item_type,$date,$time,$guests,$status
        );
        if ($stmt->execute()) $success = "Reservation successful!";
        else $errors[] = "Database error.";
        $stmt->close();
    }
}

/* DATA */
$drinks = $conn->query("SELECT * FROM drinks ORDER BY name");
$foods  = $conn->query("SELECT * FROM foods ORDER BY name");

$items = $conn->query("
    SELECT id,name,price,'drink' AS type FROM drinks
    UNION ALL
    SELECT id,name,price,'food' AS type FROM foods
");

function e($s){ return htmlspecialchars($s); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sip & Savo | Home</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
.card-hover:hover{
    transform:translateY(-6px);
    box-shadow:0 12px 30px rgba(0,0,0,.1);
    transition: all 0.3s;
}
@keyframes pulse {
  0%, 100% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.2); opacity: 0.7; }
}
.pulse { animation: pulse 1s infinite; }
.shake { animation: shake 0.5s infinite; }
@keyframes shake {
  0% { transform: translateX(0); }
  25% { transform: translateX(-3px); }
  50% { transform: translateX(3px); }
  75% { transform: translateX(-3px); }
  100% { transform: translateX(0); }
}
.modal { display:none; position:fixed; z-index:50; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.5);}
.modal-content { background-color:#fff; margin:10% auto; padding:20px; border-radius:10px; max-width:500px; }
.modal-close { float:right; font-size:1.5rem; cursor:pointer; }
</style>
</head>

<body class="bg-slate-50 min-h-screen flex flex-col">

<!-- NAVBAR -->
<header class="bg-white shadow sticky top-0 z-40">
  <div class="max-w-6xl mx-auto flex justify-between items-center p-4">
    <a href="index.php" class="font-bold text-xl text-blue-600">
      <i class="fa-solid fa-martini-glass-citrus mr-1"></i>Sip & Savo
    </a>

    <nav class="hidden md:flex gap-6 text-sm">
      <a href="index.php" class="text-blue-600 font-semibold"><i class="fa-solid fa-house mr-1"></i>Home</a>
      <a href="about.php" class="hover:text-blue-600"><i class="fa-solid fa-circle-info mr-1"></i>About</a>
      <a href="service.php" class="hover:text-blue-600"><i class="fa-solid fa-bell-concierge mr-1"></i>Services</a>
      <a href="contact.php" class="hover:text-blue-600"><i class="fa-solid fa-envelope mr-1"></i>Contact</a>
    </nav>

    <div class="flex gap-3 items-center">
      <?php if($user_id): ?>
        <span class="text-sm"><i class="fa-solid fa-user mr-1"></i>Hi, <b><?=e($user_name)?></b></span>
        <a href="?action=logout" class="bg-red-500 text-white px-3 py-2 rounded"><i class="fa-solid fa-right-from-bracket mr-1"></i>Logout</a>
      <?php else: ?>
        <a href="login.php" class="border px-3 py-2 rounded"><i class="fa-solid fa-right-to-bracket mr-1"></i>Login</a>
        <a href="register.php" class="bg-blue-600 text-white px-3 py-2 rounded"><i class="fa-solid fa-user-plus mr-1"></i>Register</a>
      <?php endif; ?>
    </div>
  </div>
</header>

<!-- HERO -->
<section class="bg-gradient-to-b from-blue-600 to-blue-700 text-white py-20 text-center">
  <h1 class="text-4xl md:text-5xl font-extrabold"><i class="fa-solid fa-wine-glass mr-2"></i>Taste Great Drinks. Dine in Style.</h1>
  <p class="mt-4 text-slate-200"><i class="fa-solid fa-calendar-check mr-1"></i>Reserve a table and enjoy premium food & drinks</p>
</section>

<!-- MENU -->
<section class="max-w-6xl mx-auto p-6 space-y-12">

<!-- DRINKS -->
<div>
<h2 class="text-2xl font-bold mb-4"><i class="fa-solid fa-martini-glass mr-2"></i>Drinks</h2>
<div class="grid grid-cols-2 md:grid-cols-4 gap-6">
<?php while($d=$drinks->fetch_assoc()): ?>
<div class="bg-white p-4 rounded-xl card-hover relative overflow-hidden">
<img src="uploads/<?=e($d['image'])?>" class="h-36 w-full object-cover rounded">
<h3 class="mt-2 font-semibold"><i class="fa-solid fa-cocktail mr-1"></i><?=e($d['name'])?></h3>
<p class="text-sm"><i class="fa-solid fa-coins mr-1"></i><?=number_format($d['price'])?> RWF</p>
</div>
<?php endwhile; ?>
</div>
</div>

<!-- FOODS -->
<div>
<h2 class="text-2xl font-bold mb-4"><i class="fa-solid fa-burger mr-2"></i>Foods</h2>
<div class="grid grid-cols-2 md:grid-cols-4 gap-6">
<?php while($f=$foods->fetch_assoc()): ?>
<div class="bg-white p-4 rounded-xl card-hover relative overflow-hidden">
<img src="uploads/<?=e($f['image'])?>" class="h-36 w-full object-cover rounded">
<h3 class="mt-2 font-semibold"><i class="fa-solid fa-burger mr-1"></i><?=e($f['name'])?></h3>
<p class="text-sm"><i class="fa-solid fa-coins mr-1"></i><?=number_format($f['price'])?> RWF</p>
</div>
<?php endwhile; ?>
</div>
</div>

</section>

<!-- RESERVATION -->
<section class="max-w-xl mx-auto p-6">
<h2 class="text-2xl font-bold text-center mb-4"><i class="fa-solid fa-calendar-plus mr-1"></i>Make Reservation</h2>

<?php if($success): ?><div class="bg-green-100 p-3 mb-3"><?=$success?></div><?php endif;?>
<?php foreach($errors as $er): ?><div class="bg-red-100 p-3 mb-2"><?=$er?></div><?php endforeach;?>

<?php if($user_id): ?>
<form method="POST" class="bg-white p-6 rounded-xl shadow space-y-4">
<select name="item_id" id="itemSelect" class="w-full border p-2 rounded" required>
<option value="">Select Item</option>
<?php while($i=$items->fetch_assoc()): ?>
<option value="<?=$i['id']?>" data-type="<?=$i['type']?>"><?=ucfirst($i['type'])?>: <?=$i['name']?> (<?=$i['price']?> RWF)</option>
<?php endwhile; ?>
</select>
<input type="hidden" name="item_type" id="item_type">
<input type="date" name="reservation_date" class="w-full border p-2 rounded" required>
<input type="time" name="reservation_time" class="w-full border p-2 rounded" required>
<input type="number" name="guests" min="1" class="w-full border p-2 rounded" required>
<button name="reserve" class="bg-blue-600 text-white w-full py-2 rounded"><i class="fa-solid fa-check mr-1"></i>Reserve</button>
</form>
<script>
document.getElementById('itemSelect').onchange=function(){
  document.getElementById('item_type').value=this.selectedOptions[0].dataset.type;
}
</script>
<?php else: ?>
<p class="text-center bg-yellow-100 p-4"><i class="fa-solid fa-lock mr-1"></i>Login to make reservation</p>
<?php endif; ?>
</section>

<!-- MY RESERVATIONS -->
<?php if($user_id): ?>
<section class="max-w-4xl mx-auto p-6 mt-10">
<h2 class="text-2xl font-bold text-center mb-4"><i class="fa-solid fa-list-check mr-1"></i>My Reservations</h2>

<?php
$reservations = $conn->prepare("
    SELECT r.id, r.item_type, r.reservation_date, r.reservation_time, r.guests, r.status,
           IF(r.item_type='drink', d.name, f.name) AS item_name,
           IF(r.item_type='drink', d.price, f.price) AS item_price
    FROM reservations r
    LEFT JOIN drinks d ON r.item_id=d.id AND r.item_type='drink'
    LEFT JOIN foods f ON r.item_id=f.id AND r.item_type='food'
    WHERE r.user_id=?
    ORDER BY r.reservation_date DESC, r.reservation_time DESC
");
$reservations->bind_param("i", $user_id);
$reservations->execute();
$result = $reservations->get_result();
?>

<?php if($result->num_rows == 0): ?>
<p class="text-center text-gray-600">You have no reservations yet.</p>
<?php else: ?>
<table class="w-full bg-white shadow rounded-lg overflow-hidden">
<thead class="bg-blue-600 text-white">
<tr>
<th class="p-3 text-left">Item</th>
<th class="p-3 text-left">Type</th>
<th class="p-3 text-left">Date</th>
<th class="p-3 text-left">Time</th>
<th class="p-3 text-left">Guests</th>
<th class="p-3 text-left">Price</th>
<th class="p-3 text-left">Status</th>
</tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()): ?>
<tr class="border-b transition-all duration-300 hover:bg-blue-50 hover:scale-105 cursor-pointer" onclick="openModal('<?=$row['item_name']?>','<?=$row['item_type']?>','<?=$row['reservation_date']?>','<?=$row['reservation_time']?>','<?=$row['guests']?>','<?=number_format($row['item_price'])?>','<?=$row['status']?>')">
<td class="p-3"><?=htmlspecialchars($row['item_name'])?></td>
<td class="p-3 capitalize"><?=htmlspecialchars($row['item_type'])?></td>
<td class="p-3"><?=htmlspecialchars($row['reservation_date'])?></td>
<td class="p-3"><?=htmlspecialchars($row['reservation_time'])?></td>
<td class="p-3"><?=htmlspecialchars($row['guests'])?></td>
<td class="p-3"><?=number_format($row['item_price'])?> RWF</td>
<td class="p-3 font-semibold capitalize">
<?php 
$status = $row['status'];
$classes = match($status) {
    'pending' => 'text-yellow-500 pulse',
    'confirmed' => 'text-green-500',
    'cancelled' => 'text-red-500 shake',
    default => 'text-gray-500'
};
$icon = match($status) {
    'pending' => 'fa-clock',
    'confirmed' => 'fa-check',
    'cancelled' => 'fa-xmark',
    default => 'fa-circle'
};
?>
<span class="<?=$classes?>"><i class="fa-solid <?=$icon?> mr-1"></i><?=htmlspecialchars($status)?></span>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<!-- MODAL -->
<div id="reservationModal" class="modal">
  <div class="modal-content">
    <span class="modal-close" onclick="closeModal()">&times;</span>
    <h3 class="font-bold text-xl mb-3">Reservation Details</h3>
    <p><b>Item:</b> <span id="modalItem"></span></p>
    <p><b>Type:</b> <span id="modalType"></span></p>
    <p><b>Date:</b> <span id="modalDate"></span></p>
    <p><b>Time:</b> <span id="modalTime"></span></p>
    <p><b>Guests:</b> <span id="modalGuests"></span></p>
    <p><b>Price:</b> <span id="modalPrice"></span></p>
    <p><b>Status:</b> <span id="modalStatus"></span></p>
  </div>
</div>
<script>
function openModal(item,type,date,time,guests,price,status){
  document.getElementById('modalItem').innerText=item;
  document.getElementById('modalType').innerText=type;
  document.getElementById('modalDate').innerText=date;
  document.getElementById('modalTime').innerText=time;
  document.getElementById('modalGuests').innerText=guests;
  document.getElementById('modalPrice').innerText=price+" RWF";
  document.getElementById('modalStatus').innerText=status;
  document.getElementById('reservationModal').style.display='block';
}
function closeModal(){
  document.getElementById('reservationModal').style.display='none';
}
window.onclick = function(event){
  if(event.target==document.getElementById('reservationModal')) closeModal();
}
</script>
<?php endif; ?>
</section>
<?php endif; ?>

<!-- FOOTER -->
<footer class="bg-slate-900 text-slate-300 mt-20">
<div class="max-w-6xl mx-auto px-6 py-14 grid md:grid-cols-4 gap-10">
<div>
<h3 class="text-xl text-white font-bold"><i class="fa-solid fa-martini-glass mr-1"></i>Sip & Savo</h3>
<p class="text-sm text-slate-400 mt-3">Premium bar & dining experience.</p>
</div>
<div>
<h4 class="text-white font-semibold mb-3">Links</h4>
<ul class="space-y-2 text-sm">
<li><a href="index.php">Home</a></li>
<li><a href="about.php">About</a></li>
<li><a href="contact.php">Contact</a></li>
</ul>
</div>
<div>
<h4 class="text-white font-semibold mb-3">Contact</h4>
<p class="text-sm"><i class="fa-solid fa-envelope mr-1"></i>mordekai893@gmail.com</p>
<p class="text-sm"><i class="fa-solid fa-phone mr-1"></i>+250 796 381 024</p>
</div>
<div>
<h4 class="text-white font-semibold mb-3">Follow</h4>
<div class="flex gap-3">
<a href="https://instagram.com/M.blaise_320" target="_blank" class="bg-pink-600 px-4 py-2 rounded-full text-white text-sm"><i class="fa-brands fa-instagram mr-1"></i>Instagram</a>
<a href="https://facebook.com/UM Mordekai" target="_blank" class="bg-blue-600 px-4 py-2 rounded-full text-white text-sm"><i class="fa-brands fa-facebook-f mr-1"></i>Facebook</a>
</div>
</div>
</div>
<div class="border-t border-slate-700 text-center py-4 text-sm">
© <?=date('Y')?> Sip & Savo • Designed by <b>Mordekai</b>
</div>
</footer>

</body>
</html>
