<?php
session_start();
include 'db.php';

// Only admin can access
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

$success = "";
$errors = [];

// Handle Add Food
if(isset($_POST['add_food'])){
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $image = $_FILES['image']['name'] ?? '';

    if(!$name || !$price){
        $errors[] = "Name and price are required!";
    } else {
        // Upload image if exists
        $image_path = "";
        if($image){
            $target = "uploads/" . basename($image);
            if(move_uploaded_file($_FILES['image']['tmp_name'], $target)){
                $image_path = $image;
            } else {
                $errors[] = "Image upload failed!";
            }
        }
        if(empty($errors)){
            $stmt = $conn->prepare("INSERT INTO foods (name, price, image) VALUES (?,?,?)");
            $stmt->bind_param("sds", $name, $price, $image_path);
            if($stmt->execute()){
                $success = "Food added successfully!";
            } else {
                $errors[] = "Database error: " . $stmt->error;
            }
        }
    }
}

// Handle Delete Food
if(isset($_GET['delete'])){
    $food_id = intval($_GET['delete']);
    $img = $conn->query("SELECT image FROM foods WHERE id=$food_id")->fetch_assoc()['image'];
    if($img && file_exists("uploads/".$img)){
        unlink("uploads/".$img);
    }
    $conn->query("DELETE FROM foods WHERE id=$food_id");
    header("Location: manage_foods.php");
    exit();
}

// Fetch all foods
$foods = $conn->query("SELECT * FROM foods");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Foods | Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<!-- Navbar -->
<nav class="bg-blue-600 p-4 flex justify-between items-center text-white">
    <h1 class="font-bold text-xl">Admin Dashboard</h1>
    <a href="admin_dashboard.php" class="bg-white text-blue-600 px-3 py-1 rounded hover:bg-gray-100">Back to Dashboard</a>
</nav>

<div class="max-w-7xl mx-auto p-6">

    <h2 class="text-2xl font-bold mb-4 text-blue-600">Manage Foods</h2>

    <!-- Messages -->
    <?php if(!empty($errors)): ?>
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            <ul>
            <?php foreach($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php if($success): ?>
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <!-- Add Food Form -->
    <form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow mb-6 grid gap-4 md:grid-cols-3">
        <input type="text" name="name" placeholder="Food Name" class="p-2 border rounded w-full" required>
        <input type="number" name="price" step="1" placeholder="Price (Rwf)" class="p-2 border rounded w-full" required>
        <input type="file" name="image" accept="image/*" class="p-2 border rounded w-full">
        <button type="submit" name="add_food" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 col-span-full md:col-span-1">Add Food</button>
    </form>

    <!-- Foods Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php while($food = $foods->fetch_assoc()): ?>
        <div class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition">
            <?php if($food['image'] && file_exists("uploads/".$food['image'])): ?>
                <img src="uploads/<?= $food['image'] ?>" class="h-40 w-full object-cover rounded mb-4">
            <?php else: ?>
                <div class="h-40 w-full bg-gray-200 flex items-center justify-center rounded mb-4 text-gray-500">No Image</div>
            <?php endif; ?>
            <h3 class="font-bold text-lg mb-2"><?= htmlspecialchars($food['name']) ?></h3>
            <p class="text-gray-700 mb-4">Rwf <?= number_format($food['price'], 0, '.', ',') ?></p>
            <div class="flex justify-between">
                <a href="edit_food.php?id=<?= $food['id'] ?>" class="bg-yellow-500 px-3 py-1 text-white rounded hover:bg-yellow-600 text-sm">Edit</a>
                <a href="?delete=<?= $food['id'] ?>" onclick="return confirm('Are you sure?')" class="bg-red-500 px-3 py-1 text-white rounded hover:bg-red-600 text-sm">Delete</a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

</div>
</body>
</html>
