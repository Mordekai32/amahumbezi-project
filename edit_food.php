<?php
session_start();
include 'db.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

$food_id = intval($_GET['id'] ?? 0);
if(!$food_id){
    header("Location: manage_foods.php");
    exit();
}

$food = $conn->query("SELECT * FROM foods WHERE id=$food_id")->fetch_assoc();
$errors = [];
$success = "";

// Handle update
if(isset($_POST['update_food'])){
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $image = $_FILES['image']['name'] ?? '';

    if(!$name || !$price){
        $errors[] = "Name and price are required!";
    } else {
        $image_path = $food['image'];
        if($image){
            $target = "uploads/".basename($image);
            if(move_uploaded_file($_FILES['image']['tmp_name'], $target)){
                if($food['image'] && file_exists("uploads/".$food['image'])){
                    unlink("uploads/".$food['image']);
                }
                $image_path = $image;
            } else {
                $errors[] = "Image upload failed!";
            }
        }

        if(empty($errors)){
            $stmt = $conn->prepare("UPDATE foods SET name=?, price=?, image=? WHERE id=?");
            $stmt->bind_param("sdsi", $name, $price, $image_path, $food_id);
            if($stmt->execute()){
                $success = "Food updated successfully!";
                $food = $conn->query("SELECT * FROM foods WHERE id=$food_id")->fetch_assoc();
            } else {
                $errors[] = "Database error: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Food | Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="max-w-3xl mx-auto p-6">

    <h2 class="text-2xl font-bold mb-4 text-blue-600">Edit Food</h2>

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

    <form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow grid gap-4">
        <input type="text" name="name" value="<?= htmlspecialchars($food['name']) ?>" placeholder="Food Name" class="p-2 border rounded" required>
        <input type="number" name="price" step="0.01" value="<?= $food['price'] ?>" placeholder="Price" class="p-2 border rounded" required>
        <input type="file" name="image" accept="image/*" class="p-2 border rounded">
        <?php if($food['image'] && file_exists("uploads/".$food['image'])): ?>
            <img src="uploads/<?= $food['image'] ?>" class="w-32 h-32 object-cover rounded">
        <?php endif; ?>
        <button type="submit" name="update_food" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Food</button>
        <a href="manage_foods.php" class="text-blue-600 hover:underline">Back to Manage Foods</a>
    </form>

</div>
</body>
</html>
