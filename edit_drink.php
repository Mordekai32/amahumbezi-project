<?php
session_start();
include 'db.php';

// Admin access check
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit();
}

// Get drink ID from URL
if(!isset($_GET['id'])){
    header("Location: manage_drinks.php");
    exit();
}

$drink_id = intval($_GET['id']);
$errors = [];

// Fetch drink data
$stmt = $conn->prepare("SELECT * FROM drinks WHERE id=?");
$stmt->bind_param("i", $drink_id);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows !== 1){
    die("Drink not found!");
}
$drink = $result->fetch_assoc();

// Handle form submission
if(isset($_POST['update_drink'])){
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $price = floatval($_POST['price']);
    $description = trim($_POST['description']);

    if(empty($name) || $price <= 0){
        $errors[] = "Name and valid price are required!";
    } else {
        $stmt = $conn->prepare("UPDATE drinks SET name=?, category=?, price=?, description=? WHERE id=?");
        $stmt->bind_param("ssdsi", $name, $category, $price, $description, $drink_id);
        if($stmt->execute()){
            header("Location: manage_drinks.php");
            exit();
        } else {
            $errors[] = "Failed to update drink!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Drink | Admin Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-50 min-h-screen flex items-center justify-center">

<div class="bg-white p-10 rounded-2xl shadow-xl max-w-md w-full border border-blue-200">

    <h1 class="text-3xl font-extrabold text-center text-blue-800 mb-6">
        Edit Drink
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
        <input type="text" name="name" placeholder="Drink Name" value="<?= htmlspecialchars($drink['name']) ?>"
               class="w-full p-4 border border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-400" required>

        <!-- Category -->
        <input type="text" name="category" placeholder="Category" value="<?= htmlspecialchars($drink['category']) ?>"
               class="w-full p-4 border border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-400">

        <!-- Price -->
        <input type="number" step="0.01" name="price" placeholder="Price" value="<?= number_format($drink['price'],2) ?>"
               class="w-full p-4 border border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-400" required>

        <!-- Description -->
        <input type="text" name="description" placeholder="Description" value="<?= htmlspecialchars($drink['description']) ?>"
               class="w-full p-4 border border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-400">

        <!-- Update Button -->
        <button type="submit" name="update_drink"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-md transition">
            Update Drink
        </button>
    </form>

    <p class="mt-4 text-center text-gray-700">
        <a href="manage_drinks.php" class="text-blue-600 font-semibold hover:underline">
            ← Back to Drinks
        </a>
    </p>
</div>

</body>
</html>
