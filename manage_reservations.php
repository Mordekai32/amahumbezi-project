<?php
session_start();
include 'db.php';

// Admin access check
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit();
}

// Handle status update or delete
if(isset($_GET['action'], $_GET['id'])){
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if($action === 'delete'){
        // Delete reservation
        $stmt = $conn->prepare("DELETE FROM reservations WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } elseif(in_array($action, ['confirmed','completed','cancelled'])){
        // Update status
        $stmt = $conn->prepare("UPDATE reservations SET status=? WHERE id=?");
        $stmt->bind_param("si",$action,$id);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch all reservations with customer name and item name (drinks or foods)
$reservations_sql = "
SELECT r.id, r.item_id, r.item_type, r.reservation_date, r.reservation_time, r.guests, r.status,
       u.name as customer_name,
       COALESCE(d.name, f.name) AS item_name
FROM reservations r
JOIN users u ON r.user_id = u.id
LEFT JOIN drinks d ON (r.item_type='drink' AND r.item_id=d.id)
LEFT JOIN foods f ON (r.item_type='food' AND r.item_id=f.id)
ORDER BY r.reservation_date DESC, r.reservation_time DESC
";
$reservations = $conn->query($reservations_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Reservations | Bar Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-6">

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-blue-800">Manage Reservations</h1>
    <a href="admin_dashboard.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
        ← Back to Dashboard
    </a>
</div>

<table class="bg-white w-full rounded shadow">
    <thead class="bg-gray-200">
        <tr>
            <th class="p-2">ID</th>
            <th class="p-2">Customer</th>
            <th class="p-2">Item</th>
            <th class="p-2">Type</th>
            <th class="p-2">Date</th>
            <th class="p-2">Time</th>
            <th class="p-2">Guests</th>
            <th class="p-2">Status</th>
            <th class="p-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($res = $reservations->fetch_assoc()): ?>
        <tr class="border-t">
            <td class="p-2"><?= $res['id'] ?></td>
            <td class="p-2"><?= htmlspecialchars($res['customer_name']) ?></td>
            <td class="p-2"><?= htmlspecialchars($res['item_name'] ?? 'N/A') ?></td>
            <td class="p-2"><?= htmlspecialchars(ucfirst($res['item_type'])) ?></td>
            <td class="p-2"><?= htmlspecialchars($res['reservation_date']) ?></td>
            <td class="p-2"><?= htmlspecialchars($res['reservation_time']) ?></td>
            <td class="p-2"><?= htmlspecialchars($res['guests']) ?></td>
            <td class="p-2">
                <?php
                    $status_colors = [
                        'pending'=>'bg-gray-300 text-gray-800',
                        'confirmed'=>'bg-blue-500 text-white',
                        'completed'=>'bg-green-500 text-white',
                        'cancelled'=>'bg-red-500 text-white'
                    ];
                ?>
                <span class="px-2 py-1 rounded <?= $status_colors[$res['status']] ?>">
                    <?= ucfirst($res['status']) ?>
                </span>
            </td>
            <td class="p-2 space-x-2">
                <?php if($res['status'] !== 'completed' && $res['status'] !== 'cancelled'): ?>
                    <a href="?action=confirmed&id=<?= $res['id'] ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded">Confirm</a>
                    <a href="?action=cancelled&id=<?= $res['id'] ?>" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">Cancel</a>
                <?php endif; ?>
                <?php if($res['status'] === 'confirmed'): ?>
                    <a href="?action=completed&id=<?= $res['id'] ?>" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded">Complete</a>
                <?php endif; ?>
                <!-- Delete button -->
                <a href="?action=delete&id=<?= $res['id'] ?>" onclick="return confirm('Are you sure you want to delete this reservation?');" class="bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
