<?php
session_start();
include 'db.php';

// Check admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = "";
$error = "";

// Fetch admin info
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email);
$stmt->fetch();
$stmt->close();

// Handle update profile
if (isset($_POST['update_profile'])) {
    $new_name = trim($_POST['name']);
    $new_email = trim($_POST['email']);
    
    if ($new_name == "" || $new_email == "") {
        $error = "All fields are required.";
    } else {
        $update = $conn->prepare("UPDATE users SET name=?, email=? WHERE id=?");
        $update->bind_param("ssi", $new_name, $new_email, $user_id);

        if ($update->execute()) {
            $_SESSION['name'] = $new_name;
            $success = "Profile updated successfully!";
        } else {
            $error = "Error updating profile.";
        }
        $update->close();
    }
}

// Handle password update
if (isset($_POST['update_password'])) {
    $current_pass = trim($_POST['current_password']);
    $new_pass = trim($_POST['new_password']);
    $confirm_pass = trim($_POST['confirm_password']);

    // Fetch stored password
    $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($stored_password);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($current_pass, $stored_password)) {
        $error = "Current password is incorrect!";
    } elseif ($new_pass !== $confirm_pass) {
        $error = "New passwords do not match!";
    } elseif (strlen($new_pass) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $update->bind_param("si", $hashed, $user_id);

        if ($update->execute()) {
            $success = "Password updated successfully!";
        } else {
            $error = "Error updating password.";
        }

        $update->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profile | Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-50 min-h-screen">

<!-- NAVBAR -->
<nav class="bg-blue-700 p-4 text-white shadow-lg">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">Admin Profile</h1>
        <a href="admin_dashboard.php" class="bg-yellow-400 px-3 py-1 font-bold rounded hover:bg-yellow-500">
            Back
        </a>
    </div>
</nav>

<div class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded-xl shadow">

    <h2 class="text-2xl font-bold text-blue-700 mb-4">Edit Profile</h2>

    <!-- Success Message -->
    <?php if ($success): ?>
        <p class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $success ?></p>
    <?php endif; ?>

    <!-- Error Message -->
    <?php if ($error): ?>
        <p class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $error ?></p>
    <?php endif; ?>

    <!-- UPDATE PROFILE FORM -->
    <form method="POST" class="space-y-4">
        <div>
            <label class="block font-semibold">Full Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($name) ?>"
                   class="w-full p-3 border rounded focus:outline-none focus:ring focus:ring-blue-300" required>
        </div>

        <div>
            <label class="block font-semibold">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>"
                   class="w-full p-3 border rounded focus:outline-none focus:ring focus:ring-blue-300" required>
        </div>

        <button type="submit" name="update_profile"
                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-bold">
            Save Profile
        </button>
    </form>

    <hr class="my-8">

    <h2 class="text-2xl font-bold text-blue-700 mb-4">Change Password</h2>

    <!-- PASSWORD FORM -->
    <form method="POST" class="space-y-4">
        <div>
            <label class="block font-semibold">Current Password</label>
            <input type="password" name="current_password"
                   class="w-full p-3 border rounded focus:outline-none focus:ring focus:ring-blue-300" required>
        </div>

        <div>
            <label class="block font-semibold">New Password</label>
            <input type="password" name="new_password"
                   class="w-full p-3 border rounded focus:outline-none focus:ring focus:ring-blue-300" required>
        </div>

        <div>
            <label class="block font-semibold">Confirm New Password</label>
            <input type="password" name="confirm_password"
                   class="w-full p-3 border rounded focus:outline-none focus:ring focus:ring-blue-300" required>
        </div>

        <button type="submit" name="update_password"
                class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 font-bold">
            Update Password
        </button>
    </form>

</div>

<div class="text-center text-gray-500 mt-6 pb-4">
    &copy; <?= date('Y') ?> Bar Admin Dashboard — Designed by Mordekai
</div>

</body>
</html>
