<?php
session_start();
include 'db.php';

// Admin access check
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit();
}

// Get user ID from URL
if(!isset($_GET['id'])){
    header("Location: admin_dashboard.php");
    exit();
}

$user_id = intval($_GET['id']);
$errors = [];

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows !== 1){
    die("User not found!");
}
$user = $result->fetch_assoc();

// Handle form submission
if(isset($_POST['update'])){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    if(empty($name) || empty($email)){
        $errors[] = "Name and Email are required!";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = "Invalid email format!";
    } else {
        $stmt = $conn->prepare("UPDATE users SET name=?, email=?, role=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $email, $role, $user_id);
        if($stmt->execute()){
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $errors[] = "Failed to update user!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit User | Admin Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-50 min-h-screen flex items-center justify-center">

<div class="bg-white p-10 rounded-2xl shadow-xl max-w-md w-full border border-blue-200">

    <h1 class="text-3xl font-extrabold text-center text-blue-800 mb-6">
        Edit User
    </h1>

    <!-- Display Errors -->
    <?php if(!empty($errors)): ?>
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            <ul class="list-disc pl-5">
                <?php foreach($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
        <!-- Name -->
        <input type="text" name="name" placeholder="Full Name" value="<?= htmlspecialchars($user['name']) ?>"
               class="w-full p-4 border border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-400" required>

        <!-- Email -->
        <input type="email" name="email" placeholder="Email Address" value="<?= htmlspecialchars($user['email']) ?>"
               class="w-full p-4 border border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-400" required>

        <!-- Role -->
        <select name="role" class="w-full p-4 border border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
            <option value="customer" <?= $user['role'] === 'customer' ? 'selected' : '' ?>>Customer</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>

        <!-- Update Button -->
        <button type="submit" name="update"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-md transition">
            Update User
        </button>
    </form>

    <p class="mt-4 text-center text-gray-700">
        <a href="manage_customers.php" class="text-blue-600 font-semibold hover:underline">
            ← Back to Dashboard
        </a>
    </p>
</div>

</body>
</html>
