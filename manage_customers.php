<?php
session_start();
include 'db.php';

// Admin access check
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit();
}

// Handle Delete Customer
if(isset($_GET['delete_id'])){
    $delete_id = intval($_GET['delete_id']);

    // Prevent deleting admins
    $user = $conn->query("SELECT role FROM users WHERE id=$delete_id")->fetch_assoc();
    if($user && $user['role'] != 'admin'){
        $conn->query("DELETE FROM users WHERE id=$delete_id");
    }
}

// Fetch all customers
$customers = $conn->query("SELECT * FROM users WHERE role='customer' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Customers | Bar Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-6">

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-blue-800">Manage Customers</h1>
    <a href="admin_dashboard.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
        ← Back to Dashboard
    </a>
</div>

<table class="bg-white w-full rounded shadow">
    <thead class="bg-gray-200">
        <tr>
            <th class="p-2">ID</th>
            <th class="p-2">Name</th>
            <th class="p-2">Email</th>
            <th class="p-2">Registered At</th>
            <th class="p-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($customer = $customers->fetch_assoc()): ?>
        <tr class="border-t">
            <td class="p-2"><?= $customer['id'] ?></td>
            <td class="p-2"><?= htmlspecialchars($customer['name']) ?></td>
            <td class="p-2"><?= htmlspecialchars($customer['email']) ?></td>
            <td class="p-2"><?= htmlspecialchars($customer['created_at']) ?></td>
            <td class="p-2 space-x-2">
                <a href="edit_user.php?id=<?= $customer['id'] ?>" class="bg-yellow-400 hover:bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                <a href="?delete_id=<?= $customer['id'] ?>" onclick="return confirm('Are you sure you want to delete this customer?');" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
